<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function get_suppliers() {
        return $this->db->get('tblsuppliers')->result_array();
    }
    public function get_warehouses() {
        return $this->db->get('tblwarehouses')->result_array();
    }
}