<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchases_model extends CRM_Model
{
    private $shipping_fields = array('shipping_street', 'shipping_city', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country');
    private $statuses = array(1, 2, 3, 4, 5, 6);
    function __construct()
    {
        parent::__construct();
    }
    public function get_statuses()
    {
        return $this->statuses;
    }
    public function get_sale_agents()
    {
        return $this->db->query("SELECT DISTINCT(sale_agent) as sale_agent FROM tblinvoices WHERE sale_agent != 0")->result_array();
    }
    /**
     * Get invoice by id
     * @param  mixed $id
     * @return array
     */
    public function getPurchaseByID($id = '')
    {
        $this->db->select('tblpurchase_plan.*,tblstaff.fullname');
        $this->db->from('tblpurchase_plan');
        $this->db->join('tblstaff','tblstaff.staffid=tblpurchase_plan.create_by','left');
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $invoice = $this->db->get()->row();
            if ($invoice) {
                $invoice->items       = $this->get_invoice_items($id);
            }
            return $invoice;
        }

        return false;
    }
    /**
     * Get all invoice items
     * @param  mixed $id invoiceid
     * @return array
     */
    public function get_invoice_items($id)
    {
        $this->db->select('tblpurchase_plan_details.*,tblitems.name, tblitems.price_buy,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id');
        $this->db->from('tblpurchase_plan_details');
        $this->db->join('tblitems','tblitems.id=tblpurchase_plan_details.product_id','left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->where('purchase_plan_id', $id);
        $items = $this->db->get()->result_array();
        return $items;
    }
    public function get_invoice_item($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('tblitems_in')->row();
    }
    public function mark_as_cancelled($id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblinvoices', array(
            'status' => 5
        ));
        if ($this->db->affected_rows() > 0) {
            $this->log_invoice_activity($id, 'invoice_activity_marked_as_cancelled');
            return true;
        }
        return false;
    }
    public function unmark_as_cancelled($id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblinvoices', array(
            'status' => 1
        ));
        if ($this->db->affected_rows() > 0) {
            $this->log_invoice_activity($id, 'invoice_activity_unmarked_as_cancelled');
            return true;
        }
        return false;
    }
    /**
     * Get this invoice generated recuring invoices
     * @since  Version 1.0.1
     * @param  mixed $id main invoice id
     * @return array
     */
    public function get_invoice_recuring_invoices($id)
    {
        $this->db->where('is_recurring_from', $id);
        $invoices          = $this->db->get('tblinvoices')->result_array();
        $recuring_invoices = array();
        foreach ($invoices as $invoice) {
            $recuring_invoices[] = $this->get($invoice['id']);
        }
        return $recuring_invoices;
    }
    /**
     * Get invoice total from all statuses
     * @since  Version 1.0.2
     * @param  mixed $data $_POST data
     * @return array
     */
    public function get_invoices_total($data)
    {

        $this->load->model('currencies_model');

        if (isset($data['currency'])) {
            $currencyid = $data['currency'];
        } else if (isset($data['customer_id']) && $data['customer_id'] != '') {
            $currencyid = $this->clients_model->get_customer_default_currency($data['customer_id']);
            if ($currencyid == 0) {
                $currencyid = $this->currencies_model->get_base_currency()->id;
            }
        } else if (isset($data['project_id']) && $data['project_id'] != '') {
            $this->load->model('projects_model');
            $currencyid = $this->projects_model->get_currency($data['project_id'])->id;
        } else {
            $currencyid = $this->currencies_model->get_base_currency()->id;
        }

        $result            = array();
        $result['due']     = array();
        $result['paid']    = array();
        $result['overdue'] = array();

        $has_permission_view = has_permission('invoices', '', 'view');

        for ($i = 1; $i <= 3; $i++) {
            $this->db->select('id,total');
            $this->db->from('tblinvoices');
            $this->db->where('currency', $currencyid);
            // Exclude cancelled invoices
            $this->db->where('status !=', 5);
            $this->db->where('status !=', 6);

            if (isset($data['project_id']) && $data['project_id'] != '') {
                $this->db->where('project_id', $data['project_id']);
            } else if (isset($data['customer_id']) && $data['customer_id'] != '') {
                $this->db->where('clientid', $data['customer_id']);
            }

            if ($i == 3) {
                $this->db->where('status', 4);
            }

            if (isset($data['years'])) {
                if (count($data['years']) > 0) {
                    $this->db->where_in('YEAR(date)', $data['years']);
                }
            }

            if (isset($data['agents'])) {
                if (count($data['agents']) > 0) {
                    $this->db->where_in('sale_agent', $data['agents']);
                }
            }

            if (!$has_permission_view) {
                $this->db->where('addedfrom', get_staff_user_id());
            }

            $invoices = $this->db->get()->result_array();
            foreach ($invoices as $invoice) {
                if ($i == 1) {
                    $result['due'][] = get_invoice_total_left_to_pay($invoice['id'], $invoice['total']);
                } else if ($i == 2) {
                    $paid_where          = array(
                        'field' => 'amount'
                    );
                    $paid_where['where'] = array(
                        'invoiceid' => $invoice['id']
                    );
                    if (isset($data['payment_modes'])) {
                        if (count($data['payment_modes']) > 0) {
                            $paid_where['where'][] = 'paymentmode IN ("' . implode('", "', $data['payment_modes']) . '")';
                        }
                    }
                    $result['paid'][] = sum_from_table('tblinvoicepaymentrecords', $paid_where);
                } else if ($i == 3) {
                    $result['overdue'][] = $invoice['total'];
                }
            }
        }
        $result['due']        = array_sum($result['due']);
        $result['paid']       = array_sum($result['paid']);
        $result['overdue']    = array_sum($result['overdue']);
        $result['symbol']     = $this->currencies_model->get_currency_symbol($currencyid);
        $result['currencyid'] = $currencyid;

        return $result;

    }
    /**
     * Insert new invoice to database
     * @param array $data invoiec data
     * @return mixed - false if not insert, invoice ID if succes
     */
    public function add($data, $expense = false)
    {
        $purchase = array(
            'code'=>get_option('prefix_purchase_plan').$data['number'],
            'name'=>$data['name'],
            'reason'=>$data['reason'],
            'date'=>to_sql_date($data['date']),
            'create_by'=>get_staff_user_id(),
        );
        if($this->db->insert('tblpurchase_plan',$purchase))
        {
            $id=$this->db->insert_id();
            logActivity('Purchase Plan Insert [ID: ' . $id . ']');

            $count=0;
        }

        $items=$data['item'];

        if($id)
        {
            
            for ($i=0; $i < count($items); $i++) { 
                $item=array(
                    'purchase_plan_id'=>$id,
                    'product_id'=>$items[$i]['id'],
                    'quantity_required'=>$items[$i]['quantity'],
                    'warehouse_id'=>$items[$i]['warehouse'],
                );
                if($this->db->insert('tblpurchase_plan_details',$item))
                {
                    logActivity('Purchase plan detail insert [ID Purchase: ' . $id . ', ID Product: ' . $items[$i]['id'] . ']');
                    $count++;
                }
                else {
                    exit("error");
                }
            }
        }

        if($id)
        {
            return $id;
        }
         
        return false;
    }
    public function update_total_tax($id)
    {
        $total_tax         = 0;
        $taxes             = array();
        $_calculated_taxes = array();
        $invoice           = $this->get($id);
        foreach ($invoice->items as $item) {
            $item_taxes = get_invoice_item_taxes($item['id']);
            if (count($item_taxes) > 0) {
                foreach ($item_taxes as $tax) {
                    $calc_tax     = 0;
                    $tax_not_calc = false;
                    if (!in_array($tax['taxname'], $_calculated_taxes)) {
                        array_push($_calculated_taxes, $tax['taxname']);
                        $tax_not_calc = true;
                    }
                    if ($tax_not_calc == true) {
                        $taxes[$tax['taxname']]          = array();
                        $taxes[$tax['taxname']]['total'] = array();
                        array_push($taxes[$tax['taxname']]['total'], (($item['qty'] * $item['rate']) / 100 * $tax['taxrate']));
                        $taxes[$tax['taxname']]['tax_name'] = $tax['taxname'];
                        $taxes[$tax['taxname']]['taxrate']  = $tax['taxrate'];
                    } else {
                        array_push($taxes[$tax['taxname']]['total'], (($item['qty'] * $item['rate']) / 100 * $tax['taxrate']));
                    }
                }
            }
        }
        foreach ($taxes as $tax) {
            $total = array_sum($tax['total']);
            if ($invoice->discount_percent != 0 && $invoice->discount_type == 'before_tax') {
                $total_tax_calculated = ($total * $invoice->discount_percent) / 100;
                $total                = ($total - $total_tax_calculated);
            }
            $total_tax += $total;
        }
        $this->db->where('id', $id);
        $this->db->update('tblinvoices', array(
            'total_tax' => $total_tax
        ));
    }
    public function get_expenses_to_bill($clientid)
    {
        $this->load->model('expenses_model');
        $where = 'billable=1 AND clientid=' . $clientid . ' AND invoiceid IS NULL';
        if (!has_permission('expenses', '', 'view')) {
            $where .= ' AND addedfrom=' . get_staff_user_id();
        }
        return $this->expenses_model->get('', $where);
    }
    public function check_for_merge_invoice($client_id, $current_invoice)
    {
        if ($current_invoice != 'undefined') {
            $this->db->select('status');
            $this->db->where('id', $current_invoice);
            $row = $this->db->get('tblinvoices')->row();
            // Cant merge on paid invoice and partialy paid and cancelled
            if ($row->status == 2 || $row->status == 3 || $row->status == 5) {
                return array();
            }
        }

        $statuses = array(
            1,
            4,
            6
        );

        $has_permission_view = has_permission('invoices', '', 'view');
        $this->db->select('id');
        $this->db->where('clientid', $client_id);
        $this->db->where('STATUS IN (' . implode(', ', $statuses) . ')');
        if (!$has_permission_view) {
            $this->db->where('addedfrom', get_staff_user_id());
        }
        if ($current_invoice != 'undefined') {
            $this->db->where('id !=', $current_invoice);
        }


        $invoices  = $this->db->get('tblinvoices')->result_array();
        $_invoices = array();
        foreach ($invoices as $invoice) {
            $_invoices[] = $this->get($invoice['id']);
        }
        return $_invoices;
    }
    /**
     * Copy invoice
     * @param  mixed $id invoice id to copy
     * @return mixed
     */
    public function copy($id)
    {
        $_invoice                     = $this->get($id);
        $new_invoice_data             = array();
        $new_invoice_data['clientid'] = $_invoice->clientid;
        $new_invoice_data['number']   = get_option('next_invoice_number');
        $new_invoice_data['date']     = _d(date('Y-m-d'));

        if ($_invoice->duedate && get_option('invoice_due_after') != 0) {
                $new_invoice_data['duedate'] = _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d')))));
        }

        $new_invoice_data['save_as_draft']     = true;
        $new_invoice_data['recurring_type']    = $_invoice->recurring_type;
        $new_invoice_data['custom_recurring']  = $_invoice->custom_recurring;
        $new_invoice_data['show_quantity_as']  = $_invoice->show_quantity_as;
        $new_invoice_data['currency']          = $_invoice->currency;
        $new_invoice_data['subtotal']          = $_invoice->subtotal;
        $new_invoice_data['total']             = $_invoice->total;
        $new_invoice_data['adminnote']         = $_invoice->adminnote;
        $new_invoice_data['adjustment']        = $_invoice->adjustment;
        $new_invoice_data['discount_percent']  = $_invoice->discount_percent;
        $new_invoice_data['discount_total']    = $_invoice->discount_total;
        $new_invoice_data['recurring']         = $_invoice->recurring;
        $new_invoice_data['discount_type']     = $_invoice->discount_type;
        $new_invoice_data['terms']             = $_invoice->terms;
        $new_invoice_data['sale_agent']        = $_invoice->sale_agent;
        $new_invoice_data['project_id']        = $_invoice->project_id;
        $new_invoice_data['recurring_ends_on'] = $_invoice->recurring_ends_on;
        // Since version 1.0.6
        $new_invoice_data['billing_street']    = $_invoice->billing_street;
        $new_invoice_data['billing_city']      = $_invoice->billing_city;
        $new_invoice_data['billing_state']     = $_invoice->billing_state;
        $new_invoice_data['billing_zip']       = $_invoice->billing_zip;
        $new_invoice_data['billing_country']   = $_invoice->billing_country;
        $new_invoice_data['shipping_street']   = $_invoice->shipping_street;
        $new_invoice_data['shipping_city']     = $_invoice->shipping_city;
        $new_invoice_data['shipping_state']    = $_invoice->shipping_state;
        $new_invoice_data['shipping_zip']      = $_invoice->shipping_zip;
        $new_invoice_data['shipping_country']  = $_invoice->shipping_country;
        if ($_invoice->include_shipping == 1) {
            $new_invoice_data['include_shipping'] = $_invoice->include_shipping;
        }
        $new_invoice_data['show_shipping_on_invoice'] = $_invoice->show_shipping_on_invoice;
        // Set to unpaid status automatically
        $new_invoice_data['status']                   = 1;
        $new_invoice_data['clientnote']               = $_invoice->clientnote;
        $new_invoice_data['adminnote']                = $_invoice->adminnote;
        $new_invoice_data['allowed_payment_modes']    = unserialize($_invoice->allowed_payment_modes);
        $new_invoice_data['newitems']                 = array();
        $key                                          = 1;
        foreach ($_invoice->items as $item) {
            $new_invoice_data['newitems'][$key]['description']      = $item['description'];
            $new_invoice_data['newitems'][$key]['long_description'] = clear_textarea_breaks($item['long_description']);
            $new_invoice_data['newitems'][$key]['qty']              = $item['qty'];
            $new_invoice_data['newitems'][$key]['unit']             = $item['unit'];
            $new_invoice_data['newitems'][$key]['taxname']          = array();
            $taxes                                                  = get_invoice_item_taxes($item['id']);
            foreach ($taxes as $tax) {
                // tax name is in format TAX1|10.00
                array_push($new_invoice_data['newitems'][$key]['taxname'], $tax['taxname']);
            }
            $new_invoice_data['newitems'][$key]['rate']  = $item['rate'];
            $new_invoice_data['newitems'][$key]['order'] = $item['item_order'];
            $key++;
        }
        $id = $this->invoices_model->add($new_invoice_data);
        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('tblinvoices', array(
                'cancel_overdue_reminders' => $_invoice->cancel_overdue_reminders
            ));

            $custom_fields = get_custom_fields('invoice');
            foreach ($custom_fields as $field) {
                $value = get_custom_field_value($_invoice->id, $field['id'], 'invoice');
                if ($value == '') {
                    continue;
                }
                $this->db->insert('tblcustomfieldsvalues', array(
                    'relid' => $id,
                    'fieldid' => $field['id'],
                    'fieldto' => 'invoice',
                    'value' => $value
                ));
            }
            logActivity('Copied Invoice ' . format_invoice_number($_invoice->id));
            return $id;
        }
        return false;
    }
    /**
     * Update purchase data
     * @param  array $data invoice data
     * @param  mixed $id   invoiceid
     * @return boolean
     */
    public function update($data, $id)
    {
        $purchase = array(
            'name'=>$data['name'],
            'reason'=>$data['reason'],
            'date'=>to_sql_date($data['date']),
        );

        if($this->db->update('tblpurchase_plan',$purchase,array('id'=>$id)))
        {
            logActivity('Purchase Plan Update [ID: ' . $id . ']');
            $count=0;
        }
        $items=$data['item'];
        if($this->db->affected_rows())
        {  
            print_r($items);
            $affected_id = array();
            for ($i=0; $i < count($items['id']); $i++) { 
                $affected_id[]=$items['id'][$i];
                $it=$this->db->get_where('tblpurchase_plan_details',array('purchase_plan_id'=>$id,'product_id'=>$items['id'][$i]),1)->row();
                $product=$this->getItemByID($items['id'][$i]);
                // var_dump($product);die();
                if($it)
                {
                    $item=array(
                    'specifications'=>$product->description,
                    'quantity_required'=>$items['quantity_required'][$i],
                    'quantity_current'=>$items['quantity_current'][$i],
                    'minimum_quantity'=>$items['minimum_quantity'][$i],
                    'quantity_min'=>$items['quantity_min'][$i]
                    );

                    $this->db->update('tblpurchase_plan_details',$item,array('id'=>$it->id));
                    if($this->db->affected_rows())
                    {
                        logActivity('Purchase plan detail updateted [ID Purchase: ' . $id . ', ID Product: ' . $it->product_id . ']');
                        $count++;
                    }

                }
                else
                {

                    $item=array(
                    'purchase_plan_id'=>$id,
                    'product_id'=>$items['id'][$i],
                    'specifications'=>$product->description,
                    'quantity_required'=>$items['quantity_required'][$i],
                    'quantity_current'=>$items['quantity_current'][$i],
                    'minimum_quantity'=>$items['minimum_quantity'][$i],
                    'quantity_min'=>$items['quantity_min'][$i]
                    );
                    $this->db->insert('tblpurchase_plan_details',$item);
                    if($this->db->affected_rows())
                    {
                        logActivity('Purchase plan detail inserted [ID Purchase: ' . $id . ', ID Product: ' . $items['id'][$i] . ']');
                        $count++;
                    }
                }
            }
            if(empty($affected_id))
            {
                $this->db->where('purchase_plan_id', $id);
                $this->db->delete('tblpurchase_plan_details');
            }
            else
            {
                $this->db->where('purchase_plan_id', $id);
                $this->db->where_not_in('product_id', $affected_id);
                $this->db->delete('tblpurchase_plan_details');
            }
            
        }
        
        
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblpurchase_plan',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function getItemByID($id = '')
    {
        
        $this->db->from('tblitems');
        $this->db->order_by('id', 'desc');
        if (is_numeric($id)) {
            $this->db->where('tblitems.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();

    }

    public function get_attachments($invoiceid, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $invoiceid);
        }
        $this->db->where('rel_type', 'invoice');
        $result = $this->db->get('tblfiles');
        if (is_numeric($id)) {
            return $result->row();
        } else {
            return $result->result_array();
        }
    }
    /**
     *  Delete invoice attachment
     * @since  Version 1.0.4
     * @param   mixed $id  attachmentid
     * @return  boolean
     */
    public function delete_attachment($id)
    {
        $attachment = $this->get_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('invoice') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Invoice Attachment Deleted [InvoiceID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('invoice') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('invoice') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('invoice') . $attachment->rel_id);
                }
            }

        }
        return $deleted;
    }
    /**
     * Delete invoice items and all connections
     * @param  mixed $id invoiceid
     * @return boolean
     */
    public function delete($id, $merge = false)
    {

        $number = $this->getPurchaseByID($id);
        
        if($this->db->delete('tblpurchase_plan',array('id'=>$id)) && $this->db->delete('tblpurchase_plan_details',array('purchase_plan_id'=>$id)))
        {
            if($this->db->affected_rows()>0)
                {
                    if($merge == false){
                        logActivity('Purchase Plan Deleted ['.$number->code.']');
                    }
                    return true;
                }
        }
        return false;
    }
    /**
     * Set invoice to sent when email is successfuly sended to client
     * @param mixed $id invoiceid
     * @param  mixed $manually is staff manualy marking this invoice as sent
     * @return  boolean
     */
    public function set_invoice_sent($id, $manually = false, $emails_sent = array(), $is_status_updated = false)
    {
        $this->db->where('id', $id);
        $this->db->update('tblinvoices', array(
            'sent' => 1,
            'datesend' => date('Y-m-d H:i:s')
        ));
        $marked = false;
        if ($this->db->affected_rows() > 0) {
            $marked = true;
        }
        if (DEFINED('CRON')) {
            $additional_activity_data = serialize(array(
                '<custom_data>' . implode(', ', $emails_sent) . '</custom_data>'
            ));
            $description              = 'invoice_activity_sent_to_client_cron';
        } else {
            if ($manually == false) {
                $additional_activity_data = serialize(array(
                    '<custom_data>' . implode(', ', $emails_sent) . '</custom_data>'
                ));
                $description              = 'invoice_activity_sent_to_client';
            } else {
                $additional_activity_data = serialize(array());
                $description              = 'invoice_activity_marked_as_sent';
            }
        }

        if ($is_status_updated == false) {
            update_invoice_status($id, true);
        }

        $this->log_invoice_activity($id, $description, false, $additional_activity_data);
        return $marked;
    }
    /**
     * Sent overdue notice to client for this invoice
     * @since  Since Version 1.0.1
     * @param  mxied  $id   invoiceid
     * @return boolean
     */
    public function send_invoice_overdue_notice($id)
    {
        $this->load->model('emails_model');
        $invoice        = $this->get($id);
        $invoice_number = format_invoice_number($invoice->id);
        $pdf            = invoice_pdf($invoice);
        $attach         = $pdf->Output($invoice_number . '.pdf', 'S');
        $emails_sent    = array();
        $send           = false;
        $contacts       = $this->clients_model->get_contacts($invoice->clientid);
        foreach ($contacts as $contact) {
            if (has_contact_permission('invoices', $contact['id'])) {
                $this->emails_model->add_attachment(array(
                    'attachment' => $attach,
                    'filename' => $invoice_number . '.pdf',
                    'type' => 'application/pdf'
                ));
                $merge_fields = array();
                $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($invoice->clientid, $contact['id']));
                $merge_fields = array_merge($merge_fields, get_invoice_merge_fields($invoice->id));
                if ($this->emails_model->send_email_template('invoice-overdue-notice', $contact['email'], $merge_fields)) {
                    array_push($emails_sent, $contact['email']);
                    $send = true;
                }
            }
        }
        if ($send) {
            if (DEFINED('CRON')) {
                $_from = '[CRON]';
            } else {
                $_from = get_staff_full_name();
            }
            $this->db->where('id', $id);
            $this->db->update('tblinvoices', array(
                'last_overdue_reminder' => date('Y-m-d')
            ));
            $this->log_invoice_activity($id, 'user_sent_overdue_reminder', false, serialize(array(
                '<custom_data>' . implode(', ', $emails_sent) . '</custom_data>',
                $_from
            )));
            return true;
        }
        return false;
    }
    /**
     * Send invoice to client
     * @param  mixed  $id        invoiceid
     * @param  string  $template  email template to sent
     * @param  boolean $attachpdf attach invoice pdf or not
     * @return boolean
     */
    public function send_invoice_to_client($id, $template = '', $attachpdf = true, $cc = '')
    {
        $this->load->model('emails_model');
        $invoice = $this->get($id);

        if ($template == '') {
            if ($invoice->sent == 0) {
                $template = 'invoice-send-to-client';
            } else {
                $template = 'invoice-already-send';
            }
            $template = do_action('after_invoice_sent_template_statement', $template);
        }
        $invoice_number = format_invoice_number($invoice->id);

        $emails_sent = array();
        $send        = false;
        if (!DEFINED('CRON')) {
            $sent_to = $this->input->post('sent_to');
        } else {
            $sent_to  = array();
            $contacts = $this->clients_model->get_contacts($invoice->clientid);
            foreach ($contacts as $contact) {
                if (has_contact_permission('invoices', $contact['id'])) {
                    array_push($sent_to, $contact['id']);
                }
            }
        }

        if (is_array($sent_to) && count($sent_to) > 0) {

            $status_updated = update_invoice_status($invoice->id, true, true);

            if ($attachpdf) {
                $_pdf_invoice = $this->get($id);
                $pdf    = invoice_pdf($_pdf_invoice);
                $attach = $pdf->Output($invoice_number . '.pdf', 'S');
            }

            $i              = 0;
            foreach ($sent_to as $contact_id) {
                if ($contact_id != '') {
                    if ($attachpdf) {
                        $this->emails_model->add_attachment(array(
                            'attachment' => $attach,
                            'filename' => $invoice_number . '.pdf',
                            'type' => 'application/pdf'
                        ));
                    }
                    if ($this->input->post('email_attachments')) {
                        $_other_attachments = $this->input->post('email_attachments');
                        foreach ($_other_attachments as $attachment) {
                            $_attachment = $this->get_attachments($id, $attachment);
                            $this->emails_model->add_attachment(array(
                                'attachment' => get_upload_path_by_type('invoice') . $id . '/' . $_attachment->file_name,
                                'filename' => $_attachment->file_name,
                                'type' => $_attachment->filetype,
                                'read' => true
                            ));
                        }
                    }
                    $contact      = $this->clients_model->get_contact($contact_id);
                    $merge_fields = array();
                    $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($invoice->clientid, $contact_id));

                    $merge_fields = array_merge($merge_fields, get_invoice_merge_fields($invoice->id));
                    // Send cc only for the first contact
                    if (!empty($cc) && $i > 0) {
                        $cc = '';
                    }
                    if ($this->emails_model->send_email_template($template, $contact->email, $merge_fields, '', $cc)) {
                        $send = true;
                        array_push($emails_sent, $contact->email);
                    }
                }
                $i++;
            }
        } else {
            return false;
        }
        if ($send) {
            $this->set_invoice_sent($id, false, $emails_sent, true);
            return true;
        } else {
            // In case the invoice not sended and the status was draft and the invoiec status is updated before send return back to draft status
            if ($invoice->status == 6 && $status_updated !== false) {
                $this->db->where('id', $invoice->id);
                $this->db->update('tblinvoices', array(
                    'status' => 6
                ));
            }
        }
        return false;
    }
    /**
     * All invoice activity
     * @param  mixed $id invoiceid
     * @return array
     */
    public function get_invoice_activity($id)
    {
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'invoice');
        $this->db->order_by('date', 'asc');
        return $this->db->get('tblsalesactivity')->result_array();
    }
    /**
     * Log invoice activity to database
     * @param  mixed $id   invoiceid
     * @param  string $description activity description
     */
    public function log_invoice_activity($id, $description = '', $client = false, $additional_data = '')
    {
        $staffid   = get_staff_user_id();
        $full_name = get_staff_full_name(get_staff_user_id());
        if (DEFINED('CRON')) {
            $staffid   = '[CRON]';
            $full_name = '[CRON]';
        } else if ($client == true) {
            $staffid   = NULL;
            $full_name = '';
        }
        $this->db->insert('tblsalesactivity', array(
            'description' => $description,
            'date' => date('Y-m-d H:i:s'),
            'rel_id' => $id,
            'rel_type' => 'invoice',
            'staffid' => $staffid,
            'full_name' => $full_name,
            'additional_data' => $additional_data
        ));
    }

    public function get_invoices_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM tblinvoices ORDER BY year DESC')->result_array();
    }
}
