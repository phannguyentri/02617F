<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Deliveries extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('exports_model');
        $this->load->model('invoice_items_model');
        $this->load->model('clients_model');
        $this->load->model('warehouse_model');
        $this->load->model('sales_model');
    }
    public function index($sale_id=NULL)
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('deliveries',array('sale_id'=>$sale_id));
        }
        $data['title'] = _l('export_orders');
        $this->load->view('admin/deliveries/manage', $data);
    }

    public function delivery_detail($id='') 
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

        } else {
            
            $data['item'] = $this->exports_model->getExportByID($id);
            $i=0;
            foreach ($data['item']->items as $key => $value) {       
                $data['item']->items[$i]->warehouse_type=$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id);
                $i++;
            }
            
            if (!$data['item']) {
                blank_page('Export Not Found');
            }
        }
        $where_clients = 'tblclients.active=1';

        if (!has_permission('customers', '', 'view')) {
            $where_clients .= ' AND tblclients.userid IN (SELECT customer_id FROM tblcustomeradmins WHERE staff_id=' . get_staff_user_id() . ')';
        }
        $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        // var_dump($data['warehouses']);die();
        $data['receivers'] = $this->staff_model->get('','',array('staffid<>'=>1));
        
        $data['customers'] = $this->clients_model->get('', $where_clients);
        $data['items']= $this->invoice_items_model->get_full(); 
        $data['title'] = $title;
        $this->load->view('admin/deliveries/delivery_detail', $data);
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
        $data=array('delivery_status'=>$status);
        $inv=$this->exports_model->getExportByID($id);
        if($status==0 && is_admin() || $status==0 && is_head($inv->deliverer_id))
        {
            $data['delivery_status']=1;
        } 

        elseif((is_admin() || is_head($inv->deliverer_id) || $inv->deliverer_id==get_staff_user_id()) && $status==1)
        {
            $data['delivery_status']=2;
        }       
        $success=fale;
        
        if(is_admin() || is_head($inv->deliverer_id) || get_staff_user_id()==$inv->deliverer_id)
        {
            $success=$this->exports_model->update_status($id,$data);
        }
        if($success) {
            if($data['delivery_status']==2)
            {
                echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận giao hàng thành công')
            ));
            }
            else
            {
                echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận phiếu giao hàng thành công')
            ));                
            }            
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