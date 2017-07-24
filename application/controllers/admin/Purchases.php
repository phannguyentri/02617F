<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchases extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('purchases_model');
        $this->load->model('invoice_items_model');
    }
    /* Get all invoices in case user go on index page */
    public function index($id = false)
    {
    
        $this->list_invoices($id);
    }
    /* List all invoices datatables */
    public function list_invoices($id = false, $clientid = false)
    {

        if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own')) {
            access_denied('invoices');
        }
        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', array(), true);
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('purchases');
        }
        $data['invoiceid'] = '';
        if (is_numeric($id)) {
            $data['invoiceid'] = $id;
        }
        $data['title']                = _l('Kế hoạch mua hàng');
        $data['invoices_years']       = $this->purchases_model->get_invoices_years();
        $data['invoices_sale_agents'] = $this->purchases_model->get_sale_agents();
        $data['invoices_statuses']    = $this->purchases_model->get_statuses();
        // var_dump($data['invoices_sale_agents']);die();
        $data['bodyclass']            = 'invoices_total_manual';
        $this->load->view('admin/purchases/manage', $data);
    }

    /* Edit client or add new client*/
    public function purchase($id = '')
    {
        // var_dump('AA'.sprintf('%05d',getMaxID('id','tblpurchase_plan')));die();
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            if ($id == '') {
                if (!has_permission('customers', '', 'create')) {
                    access_denied('customers');
                }

                $data                 = $this->input->post();
                if(isset($data['item']) && count($data['item']) > 0)
                {
                    $id = $this->purchases_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('Kế hoạch')));
                    redirect(admin_url('purchases/purchase/' . $id));
                }
            } else {
                if (!has_permission('customers', '', 'edit')) {
                    if (!is_customer_admin($id)) {
                        access_denied('customers');
                    }
                }
                $success = $this->purchases_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfuly', _l('Kế hoạch')));
                }
                redirect(admin_url('purchases/purchase/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('kế hoạch mua'));

        } else {
            $data['purchase'] = $this->purchases_model->getPurchaseByID($id);

            if (!$data['purchase']) {
                blank_page('Purchase Not Found');
            }
        }

        $data['bodyclass'] = 'customer-profile';
        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $this->load->model('invoice_items_model');
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        $data['items_units'] = $this->invoice_items_model->get_units();
        // $data['items']        = $this->invoice_items_model->get_grouped();
               $items= $this->invoice_items_model->get_full();
               for ($i=0; $i < count($items); $i++) { 
                // var_dump($items[$i]);die();
                   $items[$i]['quantity_required']=$item->quantity-$item->minimum_quantity;
                   $items[$i]['quantity_min']=$item->quantity-$item->minimum_quantity;
               }
               $data['items'] =$items;
        // echo "<pre>";
        //     var_dump($data['items']);die();



        $data['title'] = $title;
        $this->load->view('admin/purchases/purchase', $data);
    }

    /* Convert to Suggested */
    public function convert_to_suggested($id)
    {   
        $data['items'] = $this->invoice_items_model->get_full();
        $data['item'] = $this->purchases_model->getPurchaseByID($id);
        // var_dump($data['item']);die();

        $this->load->view('admin/purchases/convert_to_suggested', $data);
    }

    /* Delete purchase */
    public function delete($id)
    {
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        if (!$id) {
            redirect(admin_url('purchases/list_invoices'));
        }

        $success = $this->purchases_model->delete($id);

        if ($success) {
            set_alert('success', _l('deleted', _l('Kế hoạch mua')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('kế hoạch mua')));
        }
        if (strpos($_SERVER['HTTP_REFERER'], 'list_invoices') !== false) {
            redirect(admin_url('invoices/list_invoices'));
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    /* Generates invoice PDF and senting to email of $send_to_email = true is passed */
    public function pdf($id)
    {
        if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own')) {
            access_denied('invoices');
        }
        if (!$id) {
            redirect(admin_url('purchases/list_invoices'));
        }
        $invoice        = $this->purchases_model->getPurchaseByID($id);
        $invoice_number = get_option('prefix_purchase_suggested').$invoice->code;
        $pdf            = purchase_plan_pdf($invoice);
        $type           = 'D';
        if ($this->input->get('pdf') || $this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
    }

    public function update_status()
    {
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $staff_id=get_staff_user_id();
        $date=date('Y-m-d H:i:s');
        $data=array('status'=>$status);
        $inv=$this->purchases_model->getPurchaseByID($id);

        if(is_admin() && $status==0)
        {
            $data['user_head_id']=$staff_id;
            $data['user_head_date']=$date;

            $data['user_admin_id']=$staff_id;
            $data['user_admin_date']=$date;

            $data['status']=2;
        }
        elseif(is_admin() && $status==1)
        {
            $data['status']=2;
            if($inv->user_head_id==NULL || $inv->user_head_id=='')
            {
                $data['user_head_id']=$staff_id;
                $data['user_head_date']=$date;
            }
            if($inv->user_admin_id==NULL || $inv->user_admin_id=='')
            {
                $data['user_admin_id']=$staff_id;
                $data['user_admin_date']=$date;
            }
        }
        elseif(is_head($inv->create_by))
        {
            $data['status']+=1;
            $data['user_head_id']=$staff_id;
            $data['user_head_date']=$date;
        }

        $success=fale;
        
        if(is_admin() || is_head($inv->create_by))
        {
            $success=$this->purchases_model->update_status($id,$data);
        }
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận kế hoạch thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Không thể cập nhật dữ liệu')
            ));
        }
        die;
    }
    
}
