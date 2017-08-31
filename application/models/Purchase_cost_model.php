<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_cost_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function insert($data) {
        if(isset($data['items'])) {
            $items = $data['items'];
            unset($data['items']);

            $data['user_create'] = get_staff_user_id();
            $this->db->insert('tblpurchase_costs', $data);
            if($this->db->affected_rows() > 0) {
                $purchase_costs_id = $this->db->insert_id();

                foreach($items as $item) {
                    $data = array(
                        'purchase_costs_id' => $purchase_costs_id,
                        'cost' => $item['cost_value'],
                        'type' => $item['cost_type'],
                        'note' => $item['cost_note'],
                    );
                    $this->db->insert('tblpurchase_costs_detail', $data);
                }
                return $purchase_costs_id;
            }
        }
        return false;
    }
    public function get($id) {
        if(is_numeric($id)) {
            $this->db->select('tblpurchase_costs.*,
            ,tblstaff.fullname as user_fullname
            ');
            $this->db->where('id', $id);
            $this->db->join('tblstaff', 'tblstaff.staffid = tblpurchase_costs.user_create', 'left');
            $item = $this->db->get('tblpurchase_costs')->row();
            if($item) {
                $item->items = $this->get_detail($item->id);
                return $item;
            }
        }
        return false;
    }
    public function get_detail($purchase_cost_id) {
        if(is_numeric($purchase_cost_id)) {
            $this->db->select('*');
            $this->db->where('purchase_costs_id', $purchase_cost_id);
            $items = $this->db->get('tblpurchase_costs_detail')->result();
            return $items;
        }
        return array();
    }
    public function edit($id, $data) {
        if(isset($data['items'])) {
            $items = $data['items'];
            unset($data['items']);
            $this->db->where('id', $id);
            $this->db->where('status', 0);
            $this->db->update('tblpurchase_costs', $data);
            if($this->db->affected_rows() > 0 || (isset($items) && count($items) > 0)) {

                $this->db->where('purchase_costs_id', $id)->delete('tblpurchase_costs_detail');
                foreach($items as $item) {
                    $data = array(
                        'purchase_costs_id' => $id,
                        'cost' => $item['cost_value'],
                        'type' => $item['cost_type'],
                        'note' => $item['cost_note'],
                    );
                    $this->db->insert('tblpurchase_costs_detail', $data);
                }
                return true;
            }
        }
        return false;
    }
}