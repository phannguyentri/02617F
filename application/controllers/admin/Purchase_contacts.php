<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_orders extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_contacts_model');
        $this->load->model('invoice_items_model');
        $this->load->model('orders_model');
    }
    public function index() {
        $item = 
    }
}