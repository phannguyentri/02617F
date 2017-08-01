<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('suppliers_model');
        $this->load->model('invoice_items_model');
        $this->load->model('purchase_suggested_model');
        $this->load->model('orders_model');
    }
    // public function convert($id='') {
    //     $data = array();
    //     $purchase_suggested = $this->purchase_suggested_model->get($id);
    //     if(!$purchase_suggested) {
    //         redirect(admin_url + 'orders');
    //     }
    //     $data['purchase_suggested'] = $purchase_suggested;
    //     $data['product_list'] = $purchase_suggested->items;
    //     $data['suppliers'] = $this->orders_model->get_suppliers();
    //     $data['warehouses'] = $this->orders_model->get_warehouses();
    //     // print_r($data['product_list']);
    //     // exit();
    //     $this->load->view('admin/orders/convert', $data);
    // }
}