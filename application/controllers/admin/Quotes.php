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
        $data['clients_iv'] = $this->clients_model->get();
        $this->load->model('staff_model');
        $data['user_iv'] = $this->staff_model->get();
        $data['title'] = _l('quote_list');
        $this->load->view('admin/quotes/manage', $data);
    }

    public function send_to_email($id){
        if (!has_permission('quotes', '', 'view') && !has_permission('quotes', '', 'view_own')) {
            access_denied('quotes');
        }
        $success = $this->quotes_model->send_contract_to_client($id, $this->input->post('attach_pdf'), $this->input->post('cc'));
        if ($success) {
            set_alert('success', _l('Gửi thành công'));
        } else {
            set_alert('danger', _l('Gửi thất bại'));
        }
        redirect(admin_url('quotes'));
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
            // var_dump($data['item']);die();
           $data['item']        = $this->quotes_model->getQuoteByID($id);
            $i=0;
            foreach ($data['item']->items as $key => $value) {
                $data['item']->items[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $i++;
            }
            $i=0;
            foreach ($data['item']->items1 as $key => $value) {
                $data['item']->items1[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $i++;
            }
        }

        $data['warehouse_type_id'] = $data['quote']->items[0]->warehouse_id;
        $data['warehouse_type_id1'] = $data['quote']->items1[0]->warehouse_id;
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
        $data['categories_a'] = $this->quotes_model->getCategory(1,NULL,388);
        $data['categories_b'] = $this->quotes_model->getCategory(1,NULL,446);
        $data['types']         = $this->contracts_model->get_contract_types();
        $data['items']          = $this->invoice_items_model->get_full_by_warehouse_id('', 1);
        $data['quotes']         = $this->contracts_model->get_quote_contracts();


        $staff =  $this->quotes_model->getStaff(get_staff_user_id());
        $day = date('d/m/y');
        $count = $this->contracts_model->getContractByIDDate(get_staff_user_id(),$day);

        $dayF = str_replace('/','',$day);
        $code = $staff->staff_code.sprintf('%02d',$count+1).$dayF;
        $data['code'] = $code;


        $data['clients'] = $this->clients_model->get('', $where_clients);
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $data['title'] = _l('add_new', _l('contract_lowercase'));

        $this->load->view('admin/quotes/contract', $data);
    }


    public function quote_add_ajax(){
        if (!has_permission('quote_items', '', 'create')) {
            access_denied('quote_items');
        }

        $data   = $this->input->post();
        if(isset($data['items']) && count($data['items']) > 0 || isset($data['items1']) && count($data['items1']) > 0)
        {
            $id = $this->quotes_model->add($data);
        }
        if ($id) {
            echo json_encode(array('status' => true, 'message' => 'Thêm thành công'));
        }else{
            echo json_encode(array('status' => false, 'message' => 'Thêm thất bại'));
        }

    }


    public function quote_detail($id='')
    {
        if (!has_permission('quote_items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('quote_items');
            }
        }
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('quote_items', '', 'create')) {
                    access_denied('quote_items');
                }

                $data                 = $this->input->post();
                if(isset($data['items']) && count($data['items']) > 0 || isset($data['items1']) && count($data['items1']) > 0)
                {
                    $id = $this->quotes_model->add($data);
                }
                if ($id) {

                    set_alert('success', _l('added_successfuly', _l('quote')));
                    redirect(admin_url('quotes'));
                }else{
                    set_alert('danger', _l('Thêm thất bại', _l('quote')));
                    redirect(admin_url('quotes'));
                }
            } else {

                if (!has_permission('quote_items', '', 'edit')) {
                        access_denied('quote_items');
                }
                $success = $this->quotes_model->update($this->input->post(), $id);
                if ($success == true) {

                    $alert_type = 'success';
                    $message    = _l('updated_successfuly', _l('quote'));
                }
                else
                {
                    $alert_type = 'danger';
                    $message    = _l('Cập nhật thất bại', _l('quote'));
                }
                exit(json_encode(array(
                    'alert_type' => $alert_type,
                    'success' => $success,
                    'message' => $message,
                )));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('quote'));

        } else {
            $this->load->model('clients_model');
            $data['attachments']   = $this->clients_model->get_all_customer_attachments($id);
            $data['item'] = $this->quotes_model->getQuoteByID($id);
            // var_dump($data['item']);die();
            $i=0;
            foreach ($data['item']->items as $key => $value) {
                $data['item']->items[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $i++;
            }
            $i=0;
            foreach ($data['item']->items1 as $key => $value) {
                $data['item']->items1[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $i++;
            }

            $data['warehouse_type_id'] = $data['item']->items[0]->warehouse_id;
            $data['warehouse_type_id1'] = $data['item']->items1[0]->warehouse_id;

            if (!$data['item']) {
                blank_page('Quote Not Found');
            }
        }

        $data['items']= $this->invoice_items_model->get_full_by_warehouse_id('', 1);

        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }


        if($this->input->get('clients_id')){
            $data['client_id'] = $this->input->get('clients_id');
        }

        $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
        $data['customers'] = $this->clients_model->get('', $where_clients);
        $staff =  $this->quotes_model->getStaff(get_staff_user_id());
        $day = date('d/m/y');
        $count = $this->quotes_model->getQuoteByIDDate(get_staff_user_id(),$day);
        $dayF = str_replace('/','',$day);
        $code = $staff->staff_code.sprintf('%02d',$count+1).$dayF;
        $data['code'] = $code;

        $data['categories_a'] = $this->quotes_model->getCategory(1,NULL,388);
        $data['categories_b'] = $this->quotes_model->getCategory(1,NULL,446);
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $data['title'] = $title;
        echo json_encode(array(
            'data' => $this->load->view('admin/quotes/detail', $data, TRUE),
        ));
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

    public function save_quote_data()
    {
        if (!has_permission('contracts', '', 'edit') && !has_permission('contracts', '', 'create')) {
            header('HTTP/1.0 400 Bad error');
            echo json_encode(array(
                'success' => false,
                'message' => _l('access_denied')
            ));
            die;
        }

        $success = false;
        $message = '';

        if ($this->input->post('content')) {
            $this->db->where('id', $this->input->post('contract_id'));
            $this->db->update('tblquotes', array(
                'content' => $this->input->post('content', FALSE)
            ));

            if ($this->db->affected_rows() > 0) {
                $success = true;
                $message = _l('updated_successfuly', _l('quote'));
            }
        }
        echo json_encode(array(
            'success' => $success,
            'message' => $message
        ));
    }

    public function pdf($id)
    {

        if (!has_permission('quote_items', '', 'view') && !has_permission('quote_items', '', 'view_own')) {
            access_denied('quote_items');
        }
        if (!$id) {
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $contract = $this->quotes_model->getQuoteByID($id);
        $pdf      = quote_detail_pdf($contract);

        $type     = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }


        $pdf->Output(slug_it($contract->subject) . '.pdf', $type);
        // if (!has_permission('quote_items', '', 'view') && !has_permission('quote_items', '', 'view_own')) {
        //     access_denied('quote_items');
        // }
        // if (!$id) {
        //     redirect($_SERVER["HTTP_REFERER"]);
        // }
        // // var_dump(get_option('active_language'));die();
        // $invoice        = $this->quotes_model->getQuoteByID($id);
        // $invoice_number = $invoice->prefix.$invoice->code;
        // $pdf            = quote_detail_pdf($invoice);
        // $type           = 'D';
        // if ($this->input->get('pdf') || $this->input->get('print')) {
        //     $type = 'I';
        // }
        // $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
    }

    public function word($id){
        if (!has_permission('quote_items', '', 'view') && !has_permission('quote_items', '', 'view_own')) {
            access_denied('quote_items');
        }
        if (!$id) {
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $contract = $this->quotes_model->getQuoteByID($id);
        header("Content-Type: application/vnd.msword");
        header("Expires: 0");//no-cache
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("content-disposition: attachment;filename=".$contract->subject.".doc");
        echo '<html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                </head>
                <body>'.$contract->content.'</body>
              </html>';
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
    public function upload_attachment($id)    {
        if(handle_client_attachments_upload($id) ){
            $success = true;
            $alert_type = 'success';
            $message    = _l('Tải lên thành công', _l('client'));
        }else{
            $success = false;
            $alert_type = 'danger';
            $message    = _l('Tải lên thất bại', _l('client'));
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'success' => $success,
            'message' => $message
        ));
    }

    // public function add_external_attachment()
    // {
    //     if ($this->input->post()) {
    //         $this->misc_model->add_attachment_to_database($this->input->post('clientid'), 'customer', $this->input->post('files'), $this->input->post('external'));
    //     }
    // }

    public function delete_attachment($customer_id, $id)
    {
        if (has_permission('quotes', '', 'delete') || is_customer_admin($customer_id)) {
            $this->load->model('clients_model');
            if($this->clients_model->delete_attachment($id)){
                $success = true;
                $alert_type = 'success';
                $message    = _l('Xóa thành công');
            }else{
                $success = false;
                $alert_type = 'danger';
                $message    = _l('Xóa thất bại');
            }
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'success' => $success,
            'message' => $message
        ));
    }

    public function cancel_quote($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }


        $data['status']=3;
        $data['user_head_id']=get_staff_user_id();
        $data['user_head_date']=date('Y-m-d H:i:s');
        $data['export_status'] = 1;
        $data['user_admin_id']=get_staff_user_id();
        $data['user_admin_date']=date('Y-m-d H:i:s');

        $success    = $this->quotes_model->cancel_quote($id,$data);
        $alert_type = 'warning';
        $message    = _l('Không thực hiện được');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('Hủy bỏ phê duyệt thành công');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }

}
