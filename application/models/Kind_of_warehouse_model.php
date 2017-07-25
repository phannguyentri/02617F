<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kind_of_warehouse_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function get_row($id) {
        if(is_numeric($id)) {
            $this->db->where('id', $id);
            $item = $this->db->get('tbl_kindof_warehouse')->row();
            if($item) {
                return $item;
            }
        }
        return false;        
    }
    public function get_array_list() {
        return $this->db->get('tbl_kindof_warehouse')->result_array();
    }
}