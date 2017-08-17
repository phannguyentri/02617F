<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Quotes extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('quotes_model'); 
        $this->load->model('invoice_items_model');
        $this->load->model('warehouse_model');
        $this->load->model('currencies_model');
        $this->load->model('contracts_model');
        $this->load->model('contract_templates_model');
    }
    public function index() 
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('quotes');
        }
        $data['title'] = _l('quote_list');
        $this->load->view('admin/quotes/manage', $data);
    } 

    public function contract_output($id) 
    {
        if(!$id)
        {
            set_alert('warning', _l('info_not_found'));
            redirect(admin_url('quotes'));
        }
        else
        {
           $data['quote']        = $this->quotes_model->getQuoteByID($id);      
           // var_dump($data['customer_id'])  ;die();     
        }
        if ($data['quote']->customer_id) {
            $data['customer_id']        = $data['quote']->customer_id;
            $data['do_not_auto_toggle'] = true;
        }
        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }
        $contract_merge_fields  = get_available_merge_fields();
            $_contract_merge_fields = array();
            foreach ($contract_merge_fields as $key => $val) {
                foreach ($val as $type => $f) {
                    if ($type == 'contract') {
                        foreach ($f as $available) {
                            foreach ($available['available'] as $av) {
                                if ($av == 'contract') {
                                    array_push($_contract_merge_fields, $f);
                                    break;
                                }
                            }
                            break;
                        }
                    } else if ($type == 'other') {
                        array_push($_contract_merge_fields, $f);
                    } else if ($type == 'clients') {
                        array_push($_contract_merge_fields, $f);
                    }
                }
            }
        $data['contract_template']=$this->contract_templates_model->get_contract_template_by_id(1);        
        $data['contract_merge_fields'] = $_contract_merge_fields;
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['types']         = $this->contracts_model->get_contract_types();
        $data['clients'] = $this->clients_model->get('', $where_clients);
        $data['title'] = _l('add_new', _l('contract_lowercase'));
        $this->load->view('admin/quotes/contract', $data);
    }    

    public function quote_detail($id='') 
    {
        if (!has_permission('quote_items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('quote_items');
            }
        }
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            if ($id == '') {
                if (!has_permission('quote_items', '', 'create')) {
                    access_denied('quote_items');
                }

                $data                 = $this->input->post();
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->quotes_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('quote')));
                    redirect(admin_url('quotes'));
                }
            } else {

                if (!has_permission('quote_items', '', 'edit')) {
                        access_denied('quote_items');
                }
                $success = $this->quotes_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfuly', _l('quote')));
                    redirect(admin_url('quotes'));
                }
                else
                {
                    redirect(admin_url('quotes/quote_detail/'.$id));
                }
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('quote'));

        } else {
            $data['item'] = $this->quotes_model->getQuoteByID($id);
            $i=0;
            foreach ($data['item']->items as $key => $value) {       
                $data['item']->items[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $i++;
            }
            
            if (!$data['item']) {
                blank_page('Quote Not Found');
            }
        }
        $data['items']= $this->invoice_items_model->get_full();
        
        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }
        $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
        $data['customers'] = $this->clients_model->get('', $where_clients);
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $data['title'] = $title;
        $this->load->view('admin/quotes/detail', $data);
    }

    public function update_status()
    {
        
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $staff_id=get_staff_user_id();
        $date=date('Y-m-d H:i:s');
        $data=array('status'=>$status);
        $inv=$this->quotes_model->getQuoteByID($id);  

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
            $success=$this->quotes_model->update_status($id,$data);
        }
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận phiếu báo giá thành công')
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

    public function pdf($id)
    {
        if (!has_permission('quote_items', '', 'view') && !has_permission('quote_items', '', 'view_own')) {
            access_denied('quote_items');
        }
        if (!$id) {
            redirect($_SERVER["HTTP_REFERER"]);
        }
        // var_dump(get_option('active_language'));die();
        $invoice        = $this->quotes_model->getQuoteByID($id);
        $invoice_number = $invoice->prefix.$invoice->code;
        $pdf            = quote_detail_pdf($invoice);
        $type           = 'D';
        if ($this->input->get('pdf') || $this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
    }



    /* Get task data in a right pane */
    public function delete($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }

        $success    = $this->quotes_model->delete($id);
        $alert_type = 'warning';
        $message    = _l('Không thể xóa dữ liệu');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('Xóa dữ liệu thành công');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
    
}