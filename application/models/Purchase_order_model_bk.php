<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_order_mode extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Adding data
     *
     * @param array $data
     * @return void
     */
    public function add_detail_item($purchase_suggested_id, $item_id, $quantity) {
        
        $this->db->where('id', $purchase_suggested_id);
        $purchase_suggested = $this->db->get('tblpurchase_suggested')->row();
        $this->db->where('id', $item_id);
        $item = $this->db->get('tblitems')->row();
        if($purchase_suggested && $item) {
            $data = array(
                'purchase_suggested_id'  => $purchase_suggested_id,
                'product_id'             => $item->id,
                'product_name'           => $item->name,
                'product_quantity'       => $quantity,
                'product_unit'           => $item->unit,
                'product_price_buy'      => $item->price_buy,
                'product_specifications' => $item->specification,
            );
            $this->db->insert('tblpurchase_suggested_details', $data);

            return true;
        }
        return false;
    }
    public function add($data) {
        if(is_array($data['items']) && count($data['items']) > 0) {
            $items = $data['items'];
            unset($data['items']);

            $this->db->insert('tblpurchase_suggested', $data);
            $insert_id = $this->db->insert_id();
            if($insert_id) {
                $this->db->update('tblpurchase_plan',array('converted'=>1),array('id'=>
                    $data['purchase_plan_id']));
                // var_dump($this->db->affected_rows());die();
                if($this->db->affected_rows() && $data['purchase_plan_id'])
                {
                    logActivity('Purchase suggested Insert From Purchase Plan [ID: ' . $insert_id .'Purchase Plan ID:'.$data['purchase_plan_id']. ']');
                }
                else
                {
                    logActivity('Purchase suggested Insert [ID: ' . $insert_id . ']');
                }
                // var_dump($items);die();
                foreach($items as $value) {
                    $this->add_detail_item($insert_id, $value['id'], $value['quantity']);
                }       
                return $insert_id;
            }
            
        }
        return false;
    }
    public function edit_detail_item($purchase_suggested_id, $item_id, $quantity) {
        $this->db->where('id', $purchase_suggested_id);
        $purchase_suggested = $this->db->get('tblpurchase_suggested')->result();
        $this->db->where('id', $item_id);
        $item_origin = $this->db->get('tblitems')->row();
        $this->db->where(array('purchase_suggested_id' => $purchase_suggested_id, 'product_id' => $item_id));
        $item = $this->db->get('tblpurchase_suggested_details')->row();

        if($purchase_suggested && $item_origin) {   
            if($item) {
                
                $data = $item;
                $detail_id = $item->id;
                unset($item->id);
                $item->product_quantity = $quantity;
                $this->db->where('id', $detail_id);
                $this->db->update('tblpurchase_suggested_details', $data);
                if($this->db->affected_rows() > 0) {
                    logActivity('Purchase suggested detail Updated [ID Purchase: ' . $itemid . ', ID Product: ' . $item->product_id . ']');
                }
                else 
                    logActivity('Purchase suggested detail cannot update [ID Purchase: ' . $itemid . ', ID Product: ' . $item->product_id . ']');
            }
            else {
                
                $data = array(
                    'purchase_suggested_id'  => $purchase_suggested_id,
                    'product_id'             => $item_origin->id,
                    'product_name'           => $item_origin->name,
                    'product_quantity'       => $quantity,
                    'product_unit'           => $item_origin->unit,
                    'product_price_buy'      => $item_origin->price_buy,
                    'product_specifications' => $item_origin->specification,
                );
                $this->db->insert('tblpurchase_suggested_details', $data);
                if($this->db->affected_rows() > 0)
                    logActivity('Purchase suggested detail inserted [ID Purchase: ' . $itemid . ', ID Product: ' . $item_origin->id . ']');
                else 
                    logActivity('Purchase suggested detail cannot inserted [ID Purchase: ' . $itemid . ', ID Product: ' . $item_origin->id . ']');
            }
        }
        return false;
    }
    public function edit($data, $id) {
        $item_temp = $this->get($id);
        // Approval cannot edit
        if(!$item_temp || ($item_temp && $item_temp->status == 1))
            return false;
        if(is_array($data[items]) && count($data['items']) > 0) {
            $items = $data['items'];
            unset($data['items']);

            $this->db->update('tblpurchase_suggested', $data);
            $affect_id = [];
            foreach($items as $value) {
                array_push($affect_id, $value['id']);
                $this->edit_detail_item($id, $value['id'], $value['quantity']);
            }

            // Remove all product will be remove
            $this->db->where('purchase_suggested_id', $id);
            $this->db->where_not_in('product_id', $affect_id);
            $this->db->delete('tblpurchase_suggested_details');
            
            logActivity('Purchase suggested Updated [ID: ' . $itemid . ']');
            return true;
        }
    }
    public function get($id) {
        if(is_numeric($id)) {
            $sql = "select *,(select fullname from tblstaff where user_admin_id=staffid) as user_admin_name, (select fullname from tblstaff where user_head_id=staffid) as user_head_name, (select fullname from tblstaff where create_by=staffid) as user_name  from tblpurchase_suggested where id=". $id;
            $query = $this->db->query($sql);
            $item = $query->row();
            $item->items = $this->get_detail($id);
            return $item;
        }
    }
    public function get_detail($purchase_suggested_id) {
        if(is_numeric($purchase_suggested_id)) {
            $this->db->where('purchase_suggested_id', $purchase_suggested_id);
            return $this->db->get('tblpurchase_suggested_details')->result();
        }
        return [];
    }
    public function update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('tblpurchase_suggested', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Purchase suggested Updated [ID: ' . $id . ']');
            return true;
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