<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_contacts_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function get($id) {
        if(is_numeric($id)) {
            $this->db->select('tblpurchase_contracts.*,tblsuppliers.company as suppliers_company
            ,tblsuppliers.address as suppliers_address
            ,tblsuppliers.vat as suppliers_vat
            ,tblstaff.fullname as user_fullname');
            $this->db->where('id', $id);
            $this->db->join('tblsuppliers', 'tblsuppliers.userid = tblpurchase_contracts.id_supplier', 'left');
            $this->db->join('tblstaff', 'tblstaff.staffid = tblpurchase_contracts.id_user_create', 'left');
            $item = $this->db->get('tblpurchase_contracts')->row();
            if($item) {
                $item->products = $this->get_detail($item->id_order);
                return $item;
            }
        }
        return false;
    }
    public function get_detail($order_id) {
        if(is_numeric($order_id)) {
            $this->db->where('order_id', $order_id);
            $this->db->join('tblitems',     'tblitems.id = tblorders_detail.product_id', 'left');
            $this->db->join('tblunits',     'tblunits.unitid = tblitems.unit', 'left');
            $items = $this->db->get('tblorders_detail')->result();
            return $items;
        }
        return array();
    }
}