<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Accounts_model extends CRM_Model
{
    function __construct() {
        parent::__construct();
    }
    public function add($data) {
        $this->db->insert('tblaccounts', $data);
        if($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    public function edit($id, $data) {
        if(is_numeric($id)) {
            $this->db->update('tblaccounts', $data);
            if($this->db->affected_rows() > 0)
                return true;
        }
        return false;
    }
    public function delete($id) {
        // It's specical, i wonder how to delete it when it existed in other columns
    }
    public function get_accounts($except_id = array(), $ARRAY_RESULT = FALSE) {
        if(is_array($except_id) && count($except_id) > 0) {
            $this->db->where_not_in('idAccount', $except_id);
        }
        if(!$ARRAY_RESULT) {
            return $this->db->order_by('accountCode', 'ASC')->get('tblaccounts')->result();
        }
        else {
            return $this->db->order_by('accountCode', 'ASC')->get('tblaccounts')->result_array();
        }
        
    }
    public function get_account_attributes($ARRAY_RESULT = FALSE) {
        if(!$ARRAY_RESULT) {
            return $this->db->get('tblaccount_attributes')->result();
        }
        else {
            return $this->db->get('tblaccount_attributes')->result_array();
        }
    }
}