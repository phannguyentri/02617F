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
            $this->db->select('tblorders.*,tblsuppliers.company as suppliers_company
            ,tblsuppliers.address as suppliers_address
            ,tblsuppliers.vat as suppliers_vat
            ,tblwarehouses.warehouse as warehouse_name
            ,tblwarehouses.address as warehouse_address
            ,tblstaff.fullname as user_fullname');
            $this->db->where('id', $id);
            $this->db->join('tblsuppliers', 'tblsuppliers.userid = tblorders.id_supplier', 'left');
            $this->db->join('tblwarehouses', 'tblwarehouses.warehouseid = tblorders.id_warehouse', 'left');
            $this->db->join('tblstaff', 'tblstaff.staffid = tblorders.id_user_create', 'left');
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
            $this->db->select('tblorders_detail.*, tblunits.*, tblitems.*, tblorders_detail.product_price_buy as price_buy');
            $this->db->where('order_id', $order_id);
            $this->db->join('tblitems',     'tblitems.id = tblorders_detail.product_id', 'left');
            $this->db->join('tblunits',     'tblunits.unitid = tblitems.unit', 'left');
            $items = $this->db->get('tblorders_detail')->result();
            return $items;
        }
        return array();
    }
    public function update($id, $data) {
        if(is_numeric($id)) {
            $order = $this->db->where('id', $id)->get('tblorders')->row();
            if($order) {
                $items = $data['items'];
                unset($data['items']);
                $this->db->where('id', $id);
                $this->db->update('tblorders', $data);
                if(count($items) > 0) {
                    foreach($items as $key => $value) {
                        $item_exists = $this->db->where('order_id', $id)->where('product_id', $value['product_id'])->get('tblorders_detail')->row();
                        if($item_exists) {
                            $data = array(
                                'product_quantity' => $value['quantity'],
                                'product_price_buy' => $value['price_buy'],
                                'currency_id' => $value['currency'],
                                'warehouse_id' => $value['warehouse'],
                            );
                            $this->db->where('id', $item_exists->id);
                            $this->db->update('tblorders_detail', $data);
                        }
                        else {
                            $data = array(
                                'order_id' => $id,
                                'product_id' => $value['product_id'],
                                'product_quantity' => $value['quantity'],
                                'product_price_buy' => $value['price_buy'],
                                'currency_id' => $value['currency'],
                                'warehouse_id' => $value['warehouse'],
                            );
                            $this->db->insert('tblorders_detail', $data);
                        }
                    }
                }
            }
        }
        return false;
    }
    public function get_suppliers() {
        return $this->db->get('tblsuppliers')->result_array();
    }
    public function get_warehouses() {
        return $this->db->get('tblwarehouses')->result_array();
    }
    public function convert_to_contact($id_order, $data) {
        $this->db->where('id', $id_order);
        $order = $this->db->get('tblorders')->row();
        if($order) {
            $this->db->where('id', $id_order);
            $data_order = array(
                'converted' => '1',
            );
            $this->db->update('tblorders', $data_order);
            
            $this->db->insert('tblpurchase_contracts', $data);
            if ($this->db->affected_rows() > 0) {
                $new_id = $this->db->insert_id();
                return $new_id;
            }
        }

        return false;
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
            $this->db->where('purchase_suggested_id', $purchase_suggested_id);
            $this->db->where('order_id', 0);
            $items = $this->db->get('tblpurchase_suggested_details')->result_array();
            if(count($items) == 0) {
                return true;
            }
        }
        return false;
    }
    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblorders',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}