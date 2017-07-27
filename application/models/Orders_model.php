<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function get($id) {
        if(is_numeric($id)) {
            $this->db->where('id', $id);
            $item = $this->db->get('tblorders')->row();
            if($item) {
                $item->products = $this->get_detail($id);
                return $item;
            }
        }
        return false;
    }
    public function get_detail($order_id) {
        if(is_numeric($order_id)) {
            $this->db->where('order_id', $order_id);
            $this->db->join('tblitems', 'tblitems.id = tblorders_detail.product_id', 'left');
            $items = $this->db->get('tblorders_detail')->result();
            return $items;
        }
        return array();
    }
    public function get_suppliers() {
        return $this->db->get('tblsuppliers')->result_array();
    }
    public function get_warehouses() {
        return $this->db->get('tblwarehouses')->result_array();
    }
    public function insert($data) {
        $purchase_suggested_id = $data['id_purchase_suggested'];
        $this->db->where('purchase_suggested_id', $purchase_suggested_id);
        $this->db->join('tblitems', 'tblitems.id = tblpurchase_suggested_details.product_id', 'left');
        $purchase_suggested_products = $this->db->get('tblpurchase_suggested_details')->result();
        $this->db->insert('tblorders',$data);
        $new_id = $this->db->insert_id();
        foreach($purchase_suggested_products as $key=>$value) {
            $data_order = array(
                'order_id' => $new_id,
                'product_id' => $value->id,
                'product_code' => $value->code,
                'product_quantity' => $value->product_quantity,
                'product_price_buy' => $value->product_price_buy,
                'product_discount' => $value->discount,
                'product_taxrate' => $value->rate,
            );
            $this->db->insert('tblorders_detail', $data_order);
        }
        
    }
    public function check_exists($purchase_suggested_id) {
        if(is_numeric($purchase_suggested_id)) {
            $this->db->where('id_purchase_suggested', $purchase_suggested_id);
            $items = $this->db->get('tblorders')->result_array();
            if(count($items) > 0) {
                return true;
            }
        }
        return false;
    }
}