<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Quotes_model extends CRM_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }

    public function getQuoteByIDDate($id,$date){
        $this->db->where('create_by',$id);
        $this->db->where('create_date',$date);
        return $this->db->count_all_results('tblquotes');
    }

    public function getQuoteByID($id = '')
    {
        $this->db->select('tblquotes.*,tblstaff.fullname as creater,(SELECT fullname  FROM tblstaff WHERE user_head_id=tblstaff.staffid) as head, tblcategories.description as cdescription ,(SELECT fullname  FROM tblstaff WHERE user_admin_id=tblstaff.staffid) as admin,tblclients.address,tblclients.phonenumber,tblclients.email,tblclients.company as customer_name');
        $this->db->from('tblquotes');
        $this->db->join('tblstaff', 'tblstaff.staffid=tblquotes.create_by', 'left'); //,tblsales.date as order_date
        $this->db->join('tblclients', 'tblclients.userid=tblquotes.customer_id', 'left');

        $this->db->join('tblcategories', 'tblcategories.id = tblquotes.cate_delegate_id', 'left');

        $this->db->join('tblroles', 'tblroles.roleid=tblstaff.role', 'left');

        // $this->db->join('tblsales','tblsales.id=tblquotes.rel_id','left');
        if (is_numeric($id)) {
            $this->db->where('tblquotes.id', $id);
            $invoice = $this->db->get()->row();
            // var_dump($invoice);die();
            if ($invoice) {
                $invoice->items = $this->getQuoteItems($id);

                $invoice->items1 = $this->getQuoteItems1($id);
                $invoice->items2 = $this->getIncurred($id);
            }

            return $invoice;
        }

        return false;
    }

    public function getIncurred($idquote){
        $this->db->select('*');
        if($idquote){
            $this->db->where('tblincurred_quote_id',$idquote);
        }

        return $this->db->get('tblincurred')->result();


    }

    public function getIncurredByID($id){
        $this->db->select('*');

        $this->db->where('tblincurred_id',$id);

        return $this->db->get('tblincurred')->row();


    }

    public function getQuoteItems($id)
    {
        $this->db->select('tblquote_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id, tblitems.prefix,tblitems.code,tblitems.price,tblitems.product_features,tblitems.long_description, tblitems.warranty, tblitems.specification,tblcountries.short_name as made_in,tblitems.avatar as image,tbltaxes.name as tax_name,tbltaxes.taxrate as taxrate, tblcategories.category');
        $this->db->from('tblquote_items');
        $this->db->join('tblitems', 'tblitems.id=tblquote_items.product_id', 'left');
        $this->db->join('tblcategories', 'tblcategories.id=tblitems.category_id', 'left');
        $this->db->join('tblunits', 'tblunits.unitid=tblitems.unit', 'left');
        $this->db->join('tblcountries', 'tblcountries.country_id=tblitems.country_id', 'left');
        $this->db->join('tbltaxes', 'tbltaxes.id=tblitems.tax', 'left');
        $this->db->where('quote_id', $id);
        $items = $this->db->get()->result();
        return $items;

    }

    public function send_contract_to_client($id, $attachpdf = true, $cc = '')
    {

        $this->load->model('emails_model');
        $contract = $this->get($id);
        if ($attachpdf) {
            $pdf    = quote_detail_pdf($contract);
            $attach = $pdf->Output(slug_it($contract->subject) . '.pdf', 'S');
        }
        $sent_to = $this->input->post('sent_to');
        $send    = false;
        if (is_array($sent_to)) {
            $i = 0;
            foreach ($sent_to as $contact_id) {
                if ($contact_id != '') {
                    if ($attachpdf) {
                        $this->emails_model->add_attachment(array(
                            'attachment' => $attach,
                            'filename' => slug_it($contract->subject) . '.pdf',
                            'type' => 'application/pdf'
                        ));
                    }

                    if ($this->input->post('email_attachments')) {
                        $_other_attachments = $this->input->post('email_attachments');

                        foreach ($_other_attachments as $attachment) {
                            $_attachment = $this->get_quote_attachments($attachment);
                            $this->emails_model->add_attachment(array(
                                'attachment' => get_upload_path_by_type('quote') . $id . '/' . $_attachment->file_name,
                                'filename' => $_attachment->file_name,
                                'type' => $_attachment->filetype,
                                'read' => true
                            ));
                        }
                    }
                    $contact      = $this->clients_model->get_contact($contact_id);

                    $merge_fields = array();
                    echo '<pre>';var_dump($merge_fields);die();

                    $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($contract->client, $contact_id));
                    $merge_fields = array_merge($merge_fields, get_contract_merge_fields($id));
                    // Send cc only for the first contact
                    if (!empty($cc) && $i > 0) {
                        $cc = '';
                    }
                    if ($this->emails_model->send_email_template('send-quote', $contact->email, $merge_fields, '', $cc)) {
                        $send = true;
                    }
                }
                $i++;

            }
        } else {
            return false;
        }
        if ($send) {
            return true;
        }
        return false;
    }

    public function get_quote_attachments($attachment_id = '', $id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);
            return $this->db->get('tblfiles')->row();
        }
        $this->db->order_by('dateadded', 'desc');
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'quotes');
        return $this->db->get('tblfiles')->result_array();
    }

    public function getQuoteItems1($id)
    {
        $this->db->select('tblquote_items1.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id, tblitems.prefix,tblitems.code,tblitems.price, tblitems.warranty, tblitems.specification,tblcountries.short_name as made_in,tblitems.avatar as image,tbltaxes.name as tax_name,tbltaxes.taxrate as taxrate, tblcategories.category');
        $this->db->from('tblquote_items1');
        $this->db->join('tblitems', 'tblitems.id=tblquote_items1.product_id', 'left');
        $this->db->join('tblcategories', 'tblcategories.id=tblitems.category_id', 'left');
        $this->db->join('tblunits', 'tblunits.unitid=tblitems.unit', 'left');
        $this->db->join('tblcountries', 'tblcountries.country_id=tblitems.country_id', 'left');
        $this->db->join('tbltaxes', 'tbltaxes.id=tblitems.tax', 'left');
        $this->db->where('quote_id', $id);
        $items = $this->db->get()->result();
        return $items;

    }

    public function update_status($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tblquotes', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblquotes');
        if ($this->db->affected_rows() > 0) {

            // $this->db->where('quote_id', $id);
            // $dataDeleteItem = $this->db->get('tblquote_items1')->result();


            $this->db->where('quote_id', $id);
            $this->db->delete('tblquote_items');

            $this->db->where('quote_id', $id);
            $this->db->delete('tblquote_items1');

            $this->db->where('tblincurred_quote_id', $id);
            $this->db->delete('tblincurred');


            return true;
        }
        return false;
    }

    public function cancel_quote($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update('tblquotes', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }


    public function add($data)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        // foreach ($data['items1'] as $item){
        //     $currentItem = $this->db->get_where('tblwarehouses_products', array('product_id'=>$item['id'], 'warehouse_id'=>1))->row();
        //     if ($currentItem) {
        //         echo "<pre>";
        //         print_r($currentItem);
        //         echo "</pre>";
        //     }

        // }
        // die('2');


        $quote = array(
            'prefix' => $data['prefix'],
            'subject' => $data['subject'],
            'name' => $data['name'],
            'code' => $data['code'],
            'customer_id' => $data['customer_id'],
            'reason' => nl2br_save_html($data['reason']),
            'date' => to_sql_date($data['date']),
            'create_by' => get_staff_user_id(),
            'cate_delegate_id' => $data['cate_delegate_id'],
            'create_date' => date('d/m/y'),
        );
        $this->db->insert('tblquotes', $quote);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Quote Added [ID:' . $insert_id . ', ' . $data['date'] . ']');
            $items          = $data['items'];
            $total          = 0;
            $count          = 0;
            $vat            = 0;
            $affect_product = array();
            $items1         = $data['items1'];
            $incurred       = $data['incurred'];
            foreach ($items1 as $key => $item) {

                $product   = $this->getProductById($item['id']);
                $sub_total = (str_replace('.','',$item['unit_cost'])*1) * $item['quantity'];
                $total += $sub_total;
                $tax       = ((str_replace('.','',$item['unit_cost'])*1) * ($product->rate * 1)) / 100;
                $vat += $tax;
                $item_data = array(
                    'quote_id' => $insert_id,
                    'product_id' => $item['id'],
                    'serial_no' => $item['serial_no'],
                    'unit_id' => $product->unit,
                    'quantity' => $item['quantity'],
                    'unit_cost' => (str_replace('.','',$item['unit_cost'])*1),
                    'sub_total' => $sub_total,
                    'warehouse_id' => 1,
                    'tax_id' => $product->tax,
                    'tax_rate' => $product->rate,
                    'tax' => $tax,
                    'amount' => $sub_total + $tax
                );
                $this->db->insert('tblquote_items1', $item_data);

                if ($this->db->affected_rows() > 0) {
                    /**
                     * Sync quantity ware import
                     */
                    $currentItem = $this->db->get_where('tblwarehouses_products', array('product_id'=>$item['id'], 'warehouse_id' => 1))->row();
                    if ($currentItem) {
                        $changeQuantityWareImport   = $currentItem->product_quantity - $item['quantity'];
                        $arrData                    = array(
                            'product_quantity' => $changeQuantityWareImport
                        );
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 1));
                    }

                    /**
                     * Sync quantity ware wait
                     */
                    $currentItem = $this->db->get_where('tblwarehouses_products', array('product_id'=>$item['id'], 'warehouse_id' => 2))->row();
                    if ($currentItem) {
                        $changeQuantityWareWait   = $currentItem->product_quantity + $item['quantity'];
                        $arrData                     = array(
                            'product_quantity' => $changeQuantityWareWait
                        );
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 2));
                    }else{
                        $arrData = array(
                            'product_id'        => $item['id'],
                            'warehouse_id'      => 2,
                            'product_quantity'  => $item['quantity']
                        );

                        $this->db->insert('tblwarehouses_products',$arrData);
                    }

                    logActivity('Insert Quote Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                }
            }

            foreach ($incurred as $key => $item) {
                if($item['name_incurred'] && $item['pay_incurred']){
                    $price  = (str_replace('.','',$item['pay_incurred'])*1);
                    $total_price += $price;
                    $item_data = array(
                        'tblincurred_quote_id' => $insert_id,
                        'tblincurred_name' => $item['name_incurred'],
                        'tblincurred_price' => $price,
                    );
                    $this->db->insert('tblincurred', $item_data);
                }
            }

            foreach ($items as $key => $item) {

                $product   = $this->getProductById($item['id']);
                $sub_total = (str_replace('.','',$item['unit_cost'])*1) * $item['quantity'];
                $total += $sub_total;
                $tax       = ((str_replace('.','',$item['unit_cost'])*1) * ($product->rate * 1)) / 100;
                $vat += $tax;
                $item_data = array(
                    'quote_id' => $insert_id,
                    'product_id' => $item['id'],
                    'serial_no' => $item['serial_no'],
                    'unit_id' => $product->unit,
                    'quantity' => $item['quantity'],
                    'unit_cost' => (str_replace('.','',$item['unit_cost'])*1),
                    'sub_total' => $sub_total,
                    'warehouse_id' => 1,
                    'tax_id' => $product->tax,
                    'tax_rate' => $product->rate,
                    'tax' => $tax,
                    'amount' => $sub_total + $tax
                );
                $this->db->insert('tblquote_items', $item_data);
                if ($this->db->affected_rows() > 0) {

                    /**
                     * Sync quantity ware import
                     */
                    $currentItem = $this->db->get_where('tblwarehouses_products', array('product_id'=>$item['id'], 'warehouse_id' => 1))->row();
                    if ($currentItem) {

                        $changeQuantityWareImport   = $currentItem->product_quantity - $item['quantity'];
                        $arrData                    = array('product_quantity' => $changeQuantityWareImport);

                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 1));
                    }
                    // END
                    /**
                     * Sync quantity ware wait
                     */
                    $currentItem = $this->db->get_where('tblwarehouses_products', array('product_id'=>$item['id'], 'warehouse_id' => 2))->row();
                    if ($currentItem) {
                        $changeQuantityWareWait   = $currentItem->product_quantity + $item['quantity'];
                        $arrData                  = array(
                            'product_quantity' => $changeQuantityWareWait
                        );
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 2));
                    }else{
                        $arrData = array(
                            'product_id'        => $item['id'],
                            'warehouse_id'      => 2,
                            'product_quantity'  => $item['quantity']
                        );

                        $this->db->insert('tblwarehouses_products',$arrData);
                    }
                    // END

                    logActivity('Insert Quote Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                }
            }

            $this->db->update('tblquotes', array(
                'total' => $total,
                'incurred' => $total_price,
                'total_vat' => $vat,
            ), array(
                'id' => $insert_id
            ));
            return $insert_id;
        }
        return false;
    }

    public function getProductById($id)
    {
        $this->db->select('tblitems.*,tblunits.unit as unit_name');
        $this->db->join('tblunits', 'tblunits.unitid=tblitems.unit', 'left');
        $this->db->where('id', $id);

        return $this->db->get('tblitems')->row();
    }

    public function getStaff($id){
        if($id){
            $this->db->where('tblstaff.staffid',$id);
            return $this->db->get('tblstaff')->row();
        }
        return $this->db->get('tblstaff')->result_array();
    }



    public function getCategory($child,$id,$pro){
        $this->db->select('tblcategories.*');
        if($id){
            $this->db->where('id', $id);
            return $this->db->get('tblcategories')->row();
        }
        if($child){
            $this->db->where('category_parent <>', 0);
        }

        if($pro){
            $this->db->where('category_parent', $pro);
        }
        return $this->db->get('tblcategories')->result_array();
    }

    public function update($data, $id)
    {

        $affected = false;
        $quote    = array(
            'prefix' => $data['prefix'],
            'subject' => $data['subject'],
            'name' => $data['name'],
            'code' => $data['code'],
            'customer_id' => $data['customer_id'],
            'reason' => nl2br_save_html($data['reason']),
            'note' => nl2br_save_html($data['note']),
            'date' => to_sql_date($data['date']),
            'cate_delegate_id' => $data['cate_delegate_id'],
        );


        if ($this->db->update('tblquotes', $quote, array(
            'id' => $id
        ))) {

            logActivity('Edit Quote Updated [ID:' . $id . ', ' . date('Y-m-d') . ']');
            $count    = 0;
            $affected = true;
        }

        if ($id) {
            $vat = 0;
            $items       = $data['items'];
            $total       = 0;
            $affected_id = array();

            foreach ($items as $key => $item) {
                $affected_id[] = $item['id'];
                $product       = $this->getProductById($item['id']);
                $sub_total     = (str_replace('.','',$item['unit_cost'])*1) * $item['quantity'];
                $total     += $sub_total;
                $itm       = $this->getQuoteItem($id, $item['id']);
                $tax       = ((str_replace('.','',$item['unit_cost'])*1) * ($product->rate * 1)) / 100;
                $vat       += $tax;
                $oldItem   = $this->db->get_where('tblquote_items', ['quote_id' => $id, 'product_id' => $item['id']])->row();
                $item_data = array(
                    'quote_id' => $id,
                    'product_id' => $item['id'],
                    'serial_no' => $item['serial_no'],
                    'unit_id' => $product->unit,
                    'quantity' => $item['quantity'],
                    'unit_cost' => (str_replace('.','',$item['unit_cost'])*1),
                    'sub_total' => $sub_total,
                    'warehouse_id' => 1,
                    'tax_id' => $product->tax,
                    'tax_rate' => $product->rate,
                    'tax' => $tax,
                    'amount' => $sub_total + $tax
                );

                $wareItem = $this->db->get_where('tblwarehouses_products', ['product_id'=>$item['id'], 'warehouse_id'=>1])->row();
                $wareWaitItem   = $this->db->get_where('tblwarehouses_products', ['product_id'=>$item['id'], 'warehouse_id'=>2])->row();

                if ($itm) {
                    $this->db->update('tblquote_items', $item_data, array(
                        'id' => $itm->id
                    ));

                    // Sync quantity warehouse
                    $updateQuantity = (int) $item['quantity'];
                    $oldQuantity    = (int) $oldItem->quantity;

                    if ($updateQuantity > $oldQuantity) {

                        $changeQuantity = $updateQuantity - $oldQuantity;
                        $arrData        = array(
                            'product_quantity'  => $wareItem->product_quantity-$changeQuantity,
                        );

                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 1));

                        $arrData        = [
                            'product_quantity'  => $wareWaitItem->product_quantity+$changeQuantity,
                        ];
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 2));

                    }elseif ($updateQuantity < $oldQuantity) {
                        $changeQuantity = $oldQuantity - $updateQuantity;
                        $arrData        = [
                            'product_quantity'  => $wareItem->product_quantity+$changeQuantity,
                        ];
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 1));

                        $arrData        = [
                            'product_quantity'  => $wareWaitItem->product_quantity-$changeQuantity,
                        ];
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 2));
                    }
                    // END


                    if ($this->db->affected_rows() > 0) {
                        $affected = true;
                        logActivity('Edit Quote Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');

                    }
                } else {
                    $this->db->insert('tblquote_items', $item_data);
                    /**
                     * Sync quantity ware import
                     */
                    if ($wareItem) {
                        $changeQuantityWareImport   = $wareItem->product_quantity - $item['quantity'];
                        $arrData                    = array('product_quantity' => $changeQuantityWareImport);

                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 1));
                    }
                    // END

                    /**
                     * Sync quantity ware wait
                     */
                    if ($wareWaitItem) {
                        $changeQuantityWareWait   = $wareWaitItem->product_quantity + $item['quantity'];
                        $arrData                  = array(
                            'product_quantity' => $changeQuantityWareWait
                        );
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 2));
                    }else{
                        $arrData = array(
                            'product_id'        => $item['id'],
                            'warehouse_id'      => 2,
                            'product_quantity'  => $item['quantity']
                        );

                        $this->db->insert('tblwarehouses_products',$arrData);
                    }
                    //END

                    if ($this->db->affected_rows() > 0) {
                        $affected = true;
                        logActivity('Insert Quote Item Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                    }
                }
            }

            if (empty($affected_id)) {
                $this->db->where_not_in('product_id', NULL);
            }else{
                $this->db->where_not_in('product_id', $affected_id);
            }

            $this->db->where('quote_id', $id);
            $dataDeleteItem = $this->db->get('tblquote_items')->result();
            $this->deleteItems($dataDeleteItem, $id);

            if ($this->db->affected_rows() > 0) {
                $affected = true;
            }


            $items        = $data['items1'];
            $affected_id1 = array();
            foreach ($items as $key => $item) {
                $affected_id1[] = $item['id'];

                $product        = $this->getProductById($item['id']);
                $sub_total      = (str_replace('.','',$item['unit_cost'])*1) * $item['quantity'];
                $total          += $sub_total;
                $itm            = $this->getQuoteItem1($id, $item['id']);
                $tax            = ((str_replace('.','',$item['unit_cost'])*1) * ($product->rate * 1)) / 100;
                $vat            += $tax;
                $oldItem   = $this->db->get_where('tblquote_items1', ['quote_id' => $id, 'product_id' => $item['id']])->row();
                $item_data = array(
                    'quote_id' => $id,
                    'product_id' => $item['id'],
                    'serial_no' => $item['serial_no'],
                    'unit_id' => $product->unit,
                    'quantity' => $item['quantity'],
                    'unit_cost' => (str_replace('.','',$item['unit_cost'])*1),
                    'sub_total' => $sub_total,
                    'warehouse_id' => 1,
                    'tax_id' => $product->tax,
                    'tax_rate' => $product->rate,
                    'tax' => $tax,
                    'amount' => $sub_total + $tax
                );

                $wareItem = $this->db->get_where('tblwarehouses_products', ['product_id'=>$item['id'], 'warehouse_id'=>1])->row();
                $wareWaitItem   = $this->db->get_where('tblwarehouses_products', ['product_id'=>$item['id'], 'warehouse_id'=>2])->row();
                if ($itm) {
                    $this->db->update('tblquote_items1', $item_data, array(
                        'id' => $itm->id
                    ));

                    // Sync quantity warehouse
                    $updateQuantity = (int) $item['quantity'];
                    $oldQuantity    = (int) $oldItem->quantity;

                    if ($updateQuantity > $oldQuantity) {

                        $changeQuantity = $updateQuantity - $oldQuantity;
                        $arrData        = array(
                            'product_quantity'  => $wareItem->product_quantity-$changeQuantity,
                        );

                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 1));

                        $arrData        = [
                            'product_quantity'  => $wareWaitItem->product_quantity+$changeQuantity,
                        ];
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 2));

                    }elseif ($updateQuantity < $oldQuantity) {
                        $changeQuantity = $oldQuantity - $updateQuantity;
                        $arrData        = [
                            'product_quantity'  => $wareItem->product_quantity+$changeQuantity,
                        ];
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 1));

                        $arrData        = [
                            'product_quantity'  => $wareWaitItem->product_quantity-$changeQuantity,
                        ];
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 2));
                    }
                    // END


                    if ($this->db->affected_rows() > 0) {
                        $affected = true;
                        logActivity('Edit Quote Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');

                    }

                } else {
                    $this->db->insert('tblquote_items1', $item_data);

                    /**
                     * Sync quantity ware import
                     */
                    if ($wareItem) {
                        $changeQuantityWareImport   = $wareItem->product_quantity - $item['quantity'];
                        $arrData                    = array('product_quantity' => $changeQuantityWareImport);

                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 1));
                    }
                    // END

                    /**
                     * Sync quantity ware wait
                     */
                    if ($wareWaitItem) {
                        $changeQuantityWareWait   = $wareWaitItem->product_quantity + $item['quantity'];
                        $arrData                  = array(
                            'product_quantity' => $changeQuantityWareWait
                        );
                        $this->db->update('tblwarehouses_products', $arrData, array('product_id' => $item['id'], 'warehouse_id' => 2));
                    }else{
                        $arrData = array(
                            'product_id'        => $item['id'],
                            'warehouse_id'      => 2,
                            'product_quantity'  => $item['quantity']
                        );

                        $this->db->insert('tblwarehouses_products',$arrData);
                    }
                    //END

                    if ($this->db->affected_rows() > 0) {
                        $affected = true;
                        logActivity('Insert Quote Item Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                    }

                }
            }

            if (empty($affected_id1)) {
                $this->db->where_not_in('product_id', NULL);
            }else{
                $this->db->where_not_in('product_id', $affected_id1);
            }

            $this->db->where('quote_id', $id);
            $dataDeleteItem = $this->db->get('tblquote_items1')->result();
            $this->deleteItems($dataDeleteItem, $id, 'tblquote_items1');

            if ($this->db->affected_rows() > 0) {
                $affected = true;
            }


            $total2 = 0;
            $affected_id2 = array();
            $items        = $data['incurred'];
            if($items){
                foreach ($items as $key => $value) {
                    if($value['name_incurred'] && $value['pay_incurred']){
                        $affected_id2[] = $value['id'];
                        $itm       = $this->getIncurredByID($value['id']);
                        $total2 += (str_replace('.','',$value['pay_incurred'])*1);
                        $item_data = array(
                            'tblincurred_quote_id' => $id,
                            'tblincurred_name' => $value['name_incurred'],
                            'tblincurred_price' => (str_replace('.','',$value['pay_incurred'])*1),

                        );


                        if($itm){
                            $this->db->update('tblincurred', $item_data, array(
                                'tblincurred_id' => $itm->tblincurred_id
                            ));
                            if ($this->db->affected_rows() > 0) {
                                $affected = true;
                                logActivity('Edit Quote Item Updated [ID:' . $id . ', Item ID' . $value['tblincurred_id'] . ']');

                            }
                        } else {
                            $this->db->insert('tblincurred', $item_data);
                            $affected_id2[] = $this->db->insert_id();
                            if ($this->db->affected_rows() > 0) {
                                $affected = true;
                                logActivity('Insert Quote Item Added [ID:' . $id . ', Item ID' . $value['tblincurred_id'] . ']');
                            }

                        }

                    }

                    if (!empty($affected_id2)) {
                        $this->db->where('tblincurred_quote_id', $id);
                        $this->db->where_not_in('tblincurred_id', $affected_id2);
                        $this->db->delete('tblincurred');
                        if ($this->db->affected_rows() > 0) {
                            $affected = true;
                        }
                    }
                }
            }else{
                $this->db->where('tblincurred_quote_id', $id);
                $this->db->delete('tblincurred');
                $affected = true;
            }

            $this->db->update('tblquotes', array(
                'total' => $total,
                'incurred' => $total2,
                'total_vat' => $vat,
            ), array(
                'id' => $id
            ));
            return $affected;
        }
        return false;
    }

    public function deleteItems($dataDeleteItem, $quote_id, $tableItem = 'tblquote_items'){
        if (!empty($dataDeleteItem)) {
            foreach ($dataDeleteItem as $value) {
                $wareItem       = $this->db->get_where('tblwarehouses_products', ['product_id'=>$value->product_id, 'warehouse_id'=>1])->row();
                $wareWaitItem   = $this->db->get_where('tblwarehouses_products', ['product_id'=>$value->product_id, 'warehouse_id'=>2])->row();

                $this->db->update('tblwarehouses_products',
                 ['product_quantity'    => $wareItem->product_quantity + $value->quantity],
                 ['product_id'          => $value->product_id, 'warehouse_id' => 1]
                );

                $this->db->update('tblwarehouses_products',
                 ['product_quantity'    => $wareWaitItem->product_quantity - $value->quantity],
                 ['product_id'          => $value->product_id, 'warehouse_id' => 2]
                );

                $this->db->delete($tableItem, ['quote_id' => $quote_id, 'product_id' => $value->product_id]);
            }
        }
    }

    public function getQuoteItem($quote_id, $product_id)
    {
        if (is_numeric($quote_id) && is_numeric($product_id)) {
            $this->db->where('quote_id', $quote_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblquote_items')->row();
        }
        return false;
    }

    public function getQuoteItem1($quote_id, $product_id)
    {
        if (is_numeric($quote_id) && is_numeric($product_id)) {
            $this->db->where('quote_id', $quote_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblquote_items1')->row();
        }
        return false;
    }
}
