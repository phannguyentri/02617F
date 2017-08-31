<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_cost extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_contacts_model');
        $this->load->model('purchase_cost_model');
        $this->load->model('invoice_items_model');
        $this->load->model('orders_model');
        $this->load->model('currencies_model');
        $this->load->model('warehouse_model');

        $this->load->library('form_validation');
        $this->form_validation->set_message('required', _l('form_validation_required'));
        $this->form_validation->set_message('valid_email', _l('form_validation_valid_email'));
        $this->form_validation->set_message('matches', _l('form_validation_matches'));
        $this->form_validation->set_message('is_unique', _l('form_validation_is_unique'));
        $this->form_validation->set_error_delimiters('<p class="text-danger alert-validation">', '</p>');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('purchase_cost');
        }
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        $data['title'] = _l('purchase_contract');
        $this->load->view('admin/purchase_cost/manage', $data);
    }
    public function detail($id='') {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if($id=='') {
            $this->form_validation->set_rules('code', _l('cost_code'), 'required');
            $this->form_validation->set_rules('date_created', _l('project_datecreated'), 'required');
            $this->form_validation->set_rules('unit_shipping_name', _l('Tên đơn vị vận chuyển'), 'required');
            $this->form_validation->set_rules('unit_shipping_address', _l('Địa chỉ đơn vị vận chuyển'), 'required');
            $this->form_validation->set_rules('unit_shipping_unit', _l('Đối tác'), 'required');
            $this->form_validation->set_rules('note', _l('sumary_note'), 'required');
            $this->form_validation->set_rules('purchase_contract_id', _l('Hợp đồng'), 'required');

            if($this->input->post()) {
                $data_post = $this->input->post();
                $data_post['code'] = get_option('prefix_purchase_cost').$data_post['code'];
                
                if($this->form_validation->run() == true) {
                    
                    if(!isset($data_post['items']) || !is_array($data_post['items']) || count($data_post['items']) == 0) {
                        set_alert('danger', 'Vui lòng thêm các chi phí!');
                    }
                    else if($this->purchase_cost_model->insert($data_post)) {
                        redirect(admin_url() . 'purchase_cost');
                    }
                    else {
                        set_alert('danger', 'Có lỗi xảy ra vui lòng kiểm tra lại!');
                    }
                }
                else if(!is_null(validation_errors())) {
                    $each_alert = explode("\n", validation_errors());
                    $each_alert = array_filter($each_alert, function($value) {return !empty($value);});
                    
                    foreach($each_alert as $alert) {
                        set_alert('danger', $alert);
                    }
                }
            }
        }
        else {
            $this->form_validation->set_rules('date_created', _l('project_datecreated'), 'required');
            $this->form_validation->set_rules('unit_shipping_name', _l('Tên đơn vị vận chuyển'), 'required');
            $this->form_validation->set_rules('unit_shipping_address', _l('Địa chỉ đơn vị vận chuyển'), 'required');
            $this->form_validation->set_rules('unit_shipping_unit', _l('Đối tác'), 'required');
            $this->form_validation->set_rules('note', _l('sumary_note'), 'required');
            $this->form_validation->set_rules('purchase_contract_id', _l('Hợp đồng'), 'required');

            if($this->input->post()) {
                $data_post = $this->input->post();
                if($this->form_validation->run() == true) {
                    
                    if(!isset($data_post['items']) || !is_array($data_post['items']) || count($data_post['items']) == 0) {
                        set_alert('danger', 'Vui lòng thêm các chi phí!');
                    }
                    else if($this->purchase_cost_model->edit($id, $data_post)) {
                        // Do nothing

                    }
                    else {
                        set_alert('danger', 'Có lỗi xảy ra vui lòng kiểm tra lại!');
                    }
                }
                else if(!is_null(validation_errors())) {
                    $each_alert = explode("\n", validation_errors());
                    $each_alert = array_filter($each_alert, function($value) {return !empty($value);});
                    
                    foreach($each_alert as $alert) {
                        set_alert('danger', $alert);
                    }
                }
            }
            $purchase_cost = $this->purchase_cost_model->get($id);
            $data['purchase_cost'] = $purchase_cost;
        }
        $data['title'] = isset($purchase_cost) ? ($purchase_cost->status == 1 ? str_replace("Sửa", "Xem", _l('cost_edit_heading')) : _l('cost_edit_heading')) : _l('cost_add_heading');
        $data['contracts'] = $this->purchase_contacts_model->get_list();
        foreach($data['contracts'] as $key=>$value) {
            $data['contracts'][$key] = (array)$value;
        }

        $this->load->view('admin/purchase_cost/detail', $data);
    }
    public function change_status($id) {
        if(!has_permission('invoices', '', 'view') || !has_permission('invoices', '', 'view_own'))
        {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        $result = new stdClass();
        $result->success = false;
        $purchase_cost = $this->purchase_cost_model->get($id);

        if($purchase_cost->status == 0) {
            $this->db->where('id', $id)->update('tblpurchase_costs', array('status' => 1, 'user_head_id' => get_staff_user_id(), 'user_head_date' => date("Y-m-d H:i:s")));
            if($this->db->affected_rows() > 0)
                $result->success = true;
        }
        exit(json_encode($result));
    }
}