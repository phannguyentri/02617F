<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_cost extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_contacts_model');
        $this->load->model('invoice_items_model');
        $this->load->model('orders_model');
        $this->load->model('currencies_model');
        $this->load->model('warehouse_model');
        $this->load->model('contract_templates_model');
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('purchase_contracts');
        }
        $data['title'] = _l('purchase_contract');
        $this->load->view('admin/purchase_contracts/manage', $data);
    }
}