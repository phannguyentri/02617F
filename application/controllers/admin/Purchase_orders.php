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
    }
    public function index() {

        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('purchase_orders');
        }
        $data['title'] = _l('purchase_suggested');
        $this->load->view('admin/orders/manage', $data);
    }
    public function convert($id='') {
        $data = array();
        $data['title'] = _l('orders_create_heading');
        $purchase_suggested = $this->purchase_suggested_model->get($id);
        if(!$purchase_suggested || $purchase_suggested->status != 2 || $this->orders_model->check_exists($purchase_suggested->id)) {
            redirect(admin_url() . 'purchase_orders');
        }

        $data['purchase_suggested'] = $purchase_suggested;
        $data['product_list'] = $purchase_suggested->items;
        $data['suppliers'] = $this->orders_model->get_suppliers();
        $data['warehouses'] = $this->orders_model->get_warehouses();
        if($this->input->post()) {
            $data = $this->input->post();
            $data['code'] = get_option('prefix_purchase_order') . $data['code'];
            $data['id_user_create'] = get_staff_user_id();
            $this->orders_model->insert($data);
            redirect(admin_url() . 'purchase_orders');
        }
        $this->load->view('admin/orders/convert', $data);
    }
    public function view($id='') {
        if(is_numeric($id)) {
            $order = $this->orders_model->get($id);
            if($order) {
                $data = array();
                $data['title'] = _l('orders_view_heading');
                $data['suppliers'] = $this->orders_model->get_suppliers();
                $data['warehouses'] = $this->orders_model->get_warehouses();
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
            // var_dump($data['item']);die();
            
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
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $status=$status+1;
        $staff_id=get_staff_user_id();
        $date=date('Y-m-d H:i:s');
        $data=array('status'=>$status);
        // date('Y-m-d H:i:s'),get_staff_user_id()
        $success=$this->purchase_suggested_model->update_status($id,$data);
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận đề xuất thành công')
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