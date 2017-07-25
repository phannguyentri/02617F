<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Imports extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        // $this->load->model('imports_model');
    }
    public function index() {
        list_warehouses_products();
        $data['title'] = _l('Nhập hàng');
        $this->load->view('admin/purchase_suggested/manage', $data);
    }

    /* List all products in warehouses  datatables */
    public function list_warehouses_products($warehouse_id = false, $product_id = false)
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('warehouses_products', array(
                'warehouse_id' => $warehouse_id,
                'product_id' => $product_id
            ));
        }
    }

    public function imp_adjustment() {
        $data['title'] = _l('Điều chỉnh kho hàng');

        $this->load->view('admin/imports/adjustment', $data);
    }

    public function warehouses_overview() {
        $data['title'] = _l('Tổng quan kho');
        
        $this->load->view('admin/imports/warehouses', $data);
    }
    
    // /* Delete purchase */
    // public function delete($id)
    // {
    //     if (!has_permission('invoices', '', 'delete')) {
    //         access_denied('invoices');
    //     }
    //     if (!$id) {
    //         redirect(admin_url('purchase_suggested'));
    //     }

    //     $success = $this->purchase_suggested_model->delete($id);

    //     if ($success) {
    //         set_alert('success', _l('deleted', _l('purchase_suggested')));
    //     } else {
    //         set_alert('warning', _l('problem_deleting', _l('purchase_suggested')));
    //     }
    //     if (strpos($_SERVER['HTTP_REFERER'], 'list_invoices') !== false) {
    //         redirect(admin_url('purchase_suggested'));
    //     } else {
    //         redirect($_SERVER['HTTP_REFERER']);
    //     }
    // }

    
}