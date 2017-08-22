<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_orders extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_suggested_model');
        $this->load->model('invoice_items_model');
        $this->load->model('orders_model');
        $this->load->model('currencies_model');
        $this->load->model('warehouse_model');
        $this->load->model('contract_templates_model');
    }
    public function index() {

        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('purchase_orders');
        }
        $data['title'] = _l('orders_ticket');
        $this->load->view('admin/orders/manage', $data);
    }
    public function convert($id='') {
        $data = array();
        $data['title'] = _l('orders_ticket');
        $purchase_suggested = $this->purchase_suggested_model->get($id);
        if(!$purchase_suggested || $purchase_suggested->status != 2 || $this->orders_model->check_exists($purchase_suggested->id)) {
            redirect(admin_url() . 'purchase_orders');
        }
        $data['currencies'] = $this->currencies_model->get();
        $data['purchase_suggested'] = $purchase_suggested;
        foreach($data['purchase_suggested']->items as $key=>$value) {
            $data['purchase_suggested']->items[$key]->warehouse_type = (object)$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id, true);
        }
        $data['product_list'] = $purchase_suggested->items;
        $data['suppliers'] = $this->orders_model->get_suppliers();
        if($this->input->post()) {
            $data = $this->input->post();
            
            $data['code'] = get_option('prefix_purchase_order') . $data['code'];
            $data['id_user_create'] = get_staff_user_id();

            $this->purchase_suggested_model->convert_to_order($id, $data);
            redirect(admin_url() . 'purchase_orders');
        }
        $this->load->view('admin/orders/convert', $data);
    }
    public function convert_to_contract($id='') {
        $data = array();
        $data['title'] = _l('convert_to_purchase_contract');
        $order = $this->orders_model->get($id);
        if(!$order || $order->user_head_id == 0) {
            redirect(admin_url() . 'purchase_orders');
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
        $data['contract_merge_fields'] = $_contract_merge_fields;
        $data['order'] = $order;
        $data['default_template'] = $this->contract_templates_model->get_contract_template_by_id(2)->content;
        foreach($data['order']->products as $key=>$value) {
            $data['order']->products[$key]->warehouse_type = (object)$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id, true);
        }
        $data['currencies'] = $this->currencies_model->get();
        $data['product_list'] = $order->items;
        $data['suppliers'] = $this->orders_model->get_suppliers();

        if($this->input->post()) {
            $data = $this->input->post();
            $data['code'] = get_option('prefix_contract') . $data['code'];
            $data['id_user_create'] = get_staff_user_id();
            $data['id_supplier'] = $order->id_supplier;
            unset($data['items']);
            $data['template'] = $this->contract_templates_model->get_contract_template_by_id(2)->content;

            $result = $this->orders_model->convert_to_contact($id, $data);
            redirect(admin_url() . 'purchase_contracts');
        }
        $this->load->view('admin/orders/convert_to_contract', $data);
    }
    public function view($id='') {
        if(is_numeric($id)) {
            $order = $this->orders_model->get($id);
            if($order) {
                if($this->input->post()) {
                    $data = $this->input->post();
                    $this->orders_model->update($id, $data);
                    $order = $this->orders_model->get($id);
                }
                $data = array();
                $data['title'] = _l('orders_view_heading');
                $data['suppliers'] = $this->orders_model->get_suppliers();
                $data['warehouses'] = $this->orders_model->get_warehouses();
                $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
                $data['currencies'] = $this->currencies_model->get();
                $data['products'] = $this->invoice_items_model->get_full();
                // get purchase suggested id
                $this->db->where('id', $order->id_purchase_suggested);
                $ps = $this->db->get('tblpurchase_suggested')->row();
                if($ps) {
                    $order->code_purchase_suggested = $ps->code;
                }
                else {
                    $order->code_purchase_suggested = "";
                }
                $data['item'] = $order;
                foreach($data['item']->products as $key=>$value) {
                    $data['item']->products[$key]->warehouse_type = (object)$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id, true);
                }
                $content = $this->load->view('admin/orders/view', $data, true);
                exit($content);
            }
        }
        redirect(admin_url() . 'purchase_orders');
    }
    public function detail($id='') {
        $data = array();
        $data['items'] = $this->invoice_items_model->get_full();
        if($this->input->post()) {
            if( $id == '' ) {
                $data_post = $this->input->post();
                
                if(isset($data_post['items']) && count($data_post['items']) > 0) {
                    $data_post['create_by'] = get_staff_user_id();

                    $result_id = $this->purchase_suggested_model->add($data_post);
                    set_alert('success', _l('added_successfuly', _l('purchase_suggested')));
                    redirect(admin_url('purchase_suggested/detail/' . $result_id));
                }
            }
            else {
                
                $result = $this->purchase_suggested_model->edit($this->input->post(),$id);
                if($result)
                    set_alert('success', _l('updated_successfuly', _l('purchase_suggested')));
            }
        }
        if( $id == '' ) {
            $data['title'] = _l('purchase_suggested_add_heading');
        }
        else {
            $data['title'] = _l('purchase_suggested_edit_heading');
            $data['item'] = $this->purchase_suggested_model->get($id);
            
        }
        
        $this->load->view('admin/purchase_suggested/detail', $data);
    }
    public function detail_pdf($id='') {
        if (!$id) {
            redirect(admin_url('purchase_orders'));
        }
        $purchase_order        = $this->orders_model->get($id);
        $purchase_order_code = $purchase_order->code;

        $pdf            = purchase_orders_pdf($purchase_order);
        $type           = 'D';
        if ($this->input->get('pdf') || $this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($purchase_order_code)) . '.pdf', $type);
    }
    public function lock($id) {
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        if (!$id || !is_numeric($id)) {
            redirect(admin_url('purchase_orders'));
        }
        $item = $this->purchase_suggested_model->get($id);
        if($item) {
            $this->db->update('tblorders', array('isLock' => 1), array('id' => $id));
        }
        redirect(admin_url('purchase_orders'));
    }
    /* Delete purchase */
    public function delete($id)
    {
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        if (!$id) {
            redirect(admin_url('purchase_suggested'));
        }

        $success = $this->purchase_suggested_model->delete($id);

        if ($success) {
            set_alert('success', _l('deleted', _l('purchase_suggested')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('purchase_suggested')));
        }
        if (strpos($_SERVER['HTTP_REFERER'], 'list_invoices') !== false) {
            redirect(admin_url('purchase_suggested'));
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function update_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        
        $staff_id = get_staff_user_id();
        $date = date('Y-m-d H:i:s');
        $inv = $this->orders_model->get($id);        
        if(is_admin() && $status == 0)
        {
            $data['user_head_id'] = $staff_id;
            $data['user_head_date'] = $date;
        }
        elseif(is_head($inv->id_user_create))
        {
            $data['user_head_id'] = $staff_id;
            $data['user_head_date'] = $date;
        }
        $success=false;

        if(is_admin() || is_head($inv->id_user_create))
        {
            $success=$this->orders_model->update_status($id, $data);
        }

        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận đơn hàng thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Không thể cập nhật dữ liệu')
            ));
        }
        exit();
    }
    public function getCurrencyIDFromSupplier($idSupplier) {
        $value = $this->currencies_model->getCurrencyIDFromSupplier($idSupplier);
        $result = new stdClass();
        $result->id = $value;
        echo json_encode($result);
    }
}