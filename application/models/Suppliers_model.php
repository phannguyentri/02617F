<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Suppliers_model extends CRM_Model
{
    
    function __construct()
    {
        parent::__construct();
    }

     /**
     * @param  integer ID
     * @return boolean
     * Delete client, also deleting rows from, dismissed client announcements, ticket replies, tickets, autologin, user notes
     */
    public function delete($id)
    {
        $affectedRows = 0;

        

        do_action('before_client_deleted', $id);

        $this->db->where('userid', $id);
        $this->db->delete('tblsuppliers');
        
        if ($this->db->affected_rows() > 0) {
            do_action('after_client_deleted');
            logActivity('Supplier Deleted [' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * @param  mixed $id supplier id (optional)
     * @param  integer $active (optional) get all active or inactive
     * @return mixed
     * Get supplier object based on passed supplierid if not passed supplierid return array of all clients
     */
    public function get($id = '', $where = array('tblsuppliers.active' => 1), $single_result_type = 'row')
    {
        $this->db->select(implode(',', prefixed_table_fields_array('tblsuppliers')) . ',CASE company WHEN "" THEN (SELECT CONCAT(firstname, " ", lastname) FROM tblcontacts WHERE userid = tblsuppliers.userid and is_primary = 1) ELSE company END as company');

        $this->db->join('tblcountries', 'tblcountries.country_id = tblsuppliers.country', 'left');
        $this->db->join('tblcontacts', 'tblcontacts.userid = tblsuppliers.userid AND is_primary = 1', 'left');
        if (is_numeric($id)) {
            $this->db->where('tblsuppliers.userid', $id);
            $client = $this->db->get('tblsuppliers')->$single_result_type();
            return $client;
        }

        $this->db->where($where);
        $this->db->order_by('company', 'asc');
        return $this->db->get('tblsuppliers')->result_array();
    }
    
}
