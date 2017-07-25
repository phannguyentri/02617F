<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehouse_model extends CRM_Model
{
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
}