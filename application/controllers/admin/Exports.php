<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Exports extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('exports_model');
        $this->load->model('invoice_items_model');
        $this->load->model('clients_model');
        $this->load->model('quotes_model');
        $this->load->model('warehouse_model');
        $this->load->model('sales_model');
    }
    public function index($sale_id=NULL)
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('exports',array('sale_id'=>$sale_id));
        }
        $data['title'] = _l('export_orders');
        $this->load->view('admin/exports/manage', $data);
    }

    public function cancel_export($id)
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

        $success    = $this->quotes_model->cancel_export($id,$data);
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

    public function getIteamContact(){
        $this->load->model('contracts_model');
        $data         = $this->contracts_model->getContractByID($this->input->post('id'));
        foreach ($data->items as $key => $value) {
            $value->product_quantity = $this->warehouse_model->getProductsByWarehouseID1($value->warehouse_id,$value->product_id)->product_quantity;

        }
        foreach ($data->items1 as $key => $value) {
            $value->product_quantity = $this->warehouse_model->getProductsByWarehouseID1($value->warehouse_id,$value->product_id)->product_quantity;

        }
        echo json_encode($data);
    }

    public function export_detail($id='') 
    {
        if (!has_permission('export_items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('export_items');
            }
        }
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            if ($id == '') {
                if (!has_permission('export_items', '', 'create')) {
                    access_denied('export_items');
                }

                $data                 = $this->input->post();
                
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->exports_model->add($data);
                }
                
                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('exports')));
                    redirect(admin_url('exports'));
                }
            } else {

                if (!has_permission('export_items', '', 'edit')) {
                        access_denied('export_items');
                }
                $data                 = $this->input->post();
                $success = $this->exports_model->update($data, $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfuly', _l('exports')));
                    redirect(admin_url('exports'));
                }
                else
                {
                    redirect(admin_url('exports/export_detail/'.$id));
                }
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('exports'));
            $data['contract'] = $this->exports_model->get_export_contracts();
            $data['contract_id'] = $contract_id;
        } else {
            
            $data['item'] = $this->exports_model->getExportByID($id);
            $i=0;
            foreach ($data['item']->items as $key => $value) {       
                $data['item']->items[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $data['item']->items[$i]->product_quantity = $this->warehouse_model->getProductsByWarehouseID1($value->warehouse_id,$value->product_id)->product_quantity;

                $i++;
            }
            $i=0;
            foreach ($data['item']->items1 as $key => $value) {       
                $data['item']->items1[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $data['item']->items1[$i]->product_quantity = $this->warehouse_model->getProductsByWarehouseID1($value->warehouse_id,$value->product_id)->product_quantity;

                $i++;
            }
            $data['warehouse_id'] = $data['item']->items[0]->warehouse_id;
            $data['warehouse_id1'] = $data['item']->items1[0]->warehouse_id;
           
            $a = $this->exports_model->get_export_contracts();
            array_push($a, (array)$this->exports_model->get_export_contracts($data['item']->rel_id));
            $data['contract'] = $a;
            if (!$data['item']) {
                blank_page('Export Not Found');
            }
        }
        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }

        $staff =  $this->quotes_model->getStaff(get_staff_user_id());
        $day = date('d/m/y');
        $count = $this->exports_model->getExportByIDDate(get_staff_user_id(),$day);

        $dayF = str_replace('/','',$day);
        $code = $staff->staff_code.sprintf('%02d',$count+1).$dayF;
        $data['code'] = $code;

        $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        // var_dump($data['warehouses']);die();
        $data['receivers'] = $this->staff_model->get('','',array('staffid<>'=>1));
        $data['categories_a'] = $this->quotes_model->getCategory(1,NULL,388);
        $data['categories_b'] = $this->quotes_model->getCategory(1,NULL,446);
        $data['customers'] = $this->clients_model->get('', $where_clients);
        $items = $this->invoice_items_model->get_full(); 

        $i=0;
        foreach ($items as $key => $value) {
            $items[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
            if($this->warehouse_model->getProductsByWarehouseID1(1,$value['id'])->product_quantity){
                $items[$i]['product_quantity'] = $this->warehouse_model->getProductsByWarehouseID1(1,$value['id'])->product_quantity;
            }else{
                $items[$i]['product_quantity'] = 0;
            }
            $i++;
        }
        $data['items'] = $items;
        $data['title'] = $title;
          echo json_encode(array(
            'data' => $this->load->view('admin/exports/detail', $data, TRUE),
        ));
    }

    public function sale_output($id)
    {

         if (!has_permission('export_items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('export_items');
            }
        }          
        $data['item'] = $this->sales_model->getSaleByID($id);
        $i=0;
        foreach ($data['item']->items as $key => $value) {    
            $warehouse=(is_array($this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id))&& count($this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id))==1)? ($this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id)[0]) : ($this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id));
            $data['item']->items[$i]->warehouse_type=$warehouse;

            $i++;
        }
        if (!$data['item']) {
            blank_page('Export Not Found');
        }  

        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }

        $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $data['receivers'] = $this->staff_model->get('','',array('staffid<>'=>1));
        
        $data['customers'] = $this->clients_model->get('', $where_clients);
        $data['items']= $this->invoice_items_model->get_full(); 

        $data['title'] = _l('Tạo phiếu xuất kho');        
        $this->load->view('admin/exports/export', $data);
    }

    public function sale_delivery($id)
    {

         if (!has_permission('export_items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('export_items');
            }
        } 
        if ($this->input->post() && !$this->input->is_ajax_request()) {

            if (!has_permission('export_items', '', 'edit')) {
                    access_denied('export_items');
            }
            $data                 = $this->input->post();
            $success = $this->exports_model->update_delivery($data, $id);
            if ($success == true) {
                set_alert('success', _l('updated_successfuly', _l('deliveries')));
                redirect(admin_url('exports'));
            }
            else
            {
                redirect(admin_url('exports/sale_delivery/'.$id));
            }
        }         
        $data['item'] = $this->exports_model->getExportByID($id);
        $i=0;
        foreach ($data['item']->items as $key => $value) {    
            $warehouse=(is_array($this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id))&& count($this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id))==1)? ($this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id)[0]) : ($this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id));
            $data['item']->items[$i]->warehouse_type=$warehouse;

            $i++;
        }
        if (!$data['item']) {
            blank_page('Export Not Found');
        }  

        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }

        $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $data['receivers'] = $this->staff_model->get('','',array('staffid<>'=>1));
        
        $data['customers'] = $this->clients_model->get('', $where_clients);
        $data['items']= $this->invoice_items_model->get_full(); 

        $data['title'] = _l('Tạo phiếu giao hàng');        
        $this->load->view('admin/exports/delivery_detail', $data);
    }


    /* Get task data in a right pane */
    public function delete($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }

        $success    = $this->exports_model->delete($id);
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

    public function update_status()
    {
        
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $staff_id=get_staff_user_id();
        $date=date('Y-m-d H:i:s');
        $data=array('status'=>$status);

        $inv=$this->exports_model->getExportByID($id);
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
            $success=$this->exports_model->update_status($id,$data);
        }
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận phiếu xuất kho thành công')
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
        if (!has_permission('export_items', '', 'view') && !has_permission('export_items', '', 'view_own')) {
            access_denied('export_items');
        }
        if (!$id) {
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $type=$this->input->get('type');
        $invoice        = $this->exports_model->getExportByID($id);
        if(isset($type))
        {
            $invoice_number = $invoice->delivery_code.$invoice->code;
            $pdf            = delivery_detail_pdf($invoice);
        }
        else
        {
            $invoice_number = $invoice->prefix.$invoice->code;
            $pdf            = export_detail_pdf($invoice);
        }        
        $type           = 'D';
        if ($this->input->get('pdf') || $this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
    }
    
}