<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_costs_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function insert($data) {
        if(isset($data['items'])) {
            $items = $data['items'];
            unset($data['items']);

            $this->db->insert(' tblpurchase_costs');
        }
        return false;
    }
}