<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_suggested_model extends CRM_Model
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
    public function add_detail_item($purchase_suggested_id, $item_id, $quantity, $warehouse, $currency, $price_buy) {
        
        $this->db->where('id', $purchase_suggested_id);
        $purchase_suggested = $this->db->get('tblpurchase_suggested')->row();
        $this->db->where('id', $item_id);
        $item = $this->db->get('tblitems')->row();
        if($purchase_suggested && $item) {
            $data = array(
                'purchase_suggested_id'  => $purchase_suggested_id,
                'product_id'             => $item->id,
                'product_quantity'       => $quantity,
                'warehouse_id'           => $warehouse,
                'currency_id'            => $currency,
                'price_buy'              => $price_buy,
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
                    $this->add_detail_item($insert_id, $value['id'], $value['quantity'], $value['warehouse'], $value['currency'], $value['price_buy']);
                }       
                return $insert_id;
            }
            
        }
        return false;
    }
    public function edit_detail_item($purchase_suggested_id, $item_id, $quantity, $warehouse, $currency, $price_buy) {
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
                $item->currency_id = $currency;
                $item->price_buy = $price_buy;

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
                    'product_quantity'       => $quantity,
                    'warehouse_id'           => $warehouse,
                    'currency_id'            => $currency,
                    'price_buy'              => $price_buy,
                );
                $result = $this->db->insert('tblpurchase_suggested_details', $data);
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
        
        if(!$item_temp) return false;
        if(is_array($data['items']) && count($data['items']) > 0) {
            $items = $data['items'];
            unset($data['items']);
            $data['user_head_id'] = 0;
            $data['user_admin_id'] = 0;
            $data['status'] = 0;
            $this->db->update('tblpurchase_suggested', $data);
            $affect_id = [];
            foreach($items as $value) {
                array_push($affect_id, $value['id']);
                $this->edit_detail_item($id, $value['id'], $value['quantity'], $value['warehouse'],$value['currency'], $value['price_buy']);
            }
            
            // Remove all product will be remove if doesn't conveter
            $this->db->where('purchase_suggested_id', $id);
            $this->db->where('order_id', 0);
            $this->db->where_not_in('product_id', $affect_id);
            $this->db->delete('tblpurchase_suggested_details');
            
            
            logActivity('Purchase suggested Updated [ID: ' . $itemid . ']');
            return true;
        }
    }
    public function get($id) {
        if(is_numeric($id)) {
            $sql = "select *,
            (select fullname from tblstaff where user_admin_id=staffid) as user_admin_name, 
            (select fullname from tblstaff where user_head_id=staffid) as user_head_name, 
            (select fullname from tblstaff where create_by=staffid) as user_name 
            from tblpurchase_suggested where id=". $id;
            $query = $this->db->query($sql);
            $item = $query->row();

            $item->items = $this->get_detail($id);
            return $item;
        }
    }
    public function get_detail($purchase_suggested_id) {
        if(is_numeric($purchase_suggested_id)) {
            $this->db->select("*,
            tblpurchase_suggested_details.id as id,
            tblpurchase_suggested_details.price_buy as price_buy,
            (select unit from tblunits where tblunits.unitid=tblitems.unit) as unit_name,
            ");
            $this->db->where('purchase_suggested_id', $purchase_suggested_id);
            $this->db->join('tblitems', 'tblitems.id = tblpurchase_suggested_details.product_id', 'left');
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
    public function delete_detail($purchase_suggested_id) {
        $this->db->where('purchase_suggested_id', $purchase_suggested_id);
        $this->db->delete('tblpurchase_suggested_details');
        if ($this->db->affected_rows() > 0) {
            logActivity('Purchase suggested detail Deleted [ID: ' . $purchase_suggested_id . ', Items: ' . $this->db->affected_rows() . ']');
            return true;
        }
        return false;
    }
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('tblpurchase_suggested');
        if ($this->db->affected_rows() > 0) {
            $this->delete_detail($id);
            logActivity('Purchase suggested Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }
    public function convert_to_order($id, $data) {
        if(is_numeric($id)) {
            $this->db->where('id', $id);
            $purchase_suggested = $this->db->get('tblpurchase_suggested')->row();
            if($purchase_suggested) {
                
                // Doesn't update
                // $data_suggested = array(
                //     'converted' => '1',
                // );
                // $this->db->where('id', $id);
                // $this->db->update('tblpurchase_suggested', $data_suggested);

                $items = $data['items'];
                unset($data['items']);
                $this->db->insert('tblorders', $data);
                if ($this->db->affected_rows() > 0) {
                    $new_id = $this->db->insert_id();
                    foreach($items as $key=>$value) {
                        $data_order = array(
                            'order_id' => $new_id,
                            'product_id' => $value['product_id'],
                            'product_quantity' => $value['quantity'],
                            'currency_id' => $value['currency'],
                            'warehouse_id' => $value['warehouse'],
                            'product_price_buy' => $value['price_buy'],
                            'purchase_suggested_detail_id' => $value['id'],
                        );
                        $this->db->insert('tblorders_detail', $data_order);
                        // Update suggested detail
                        $this->db->update('tblpurchase_suggested_details', array('order_id' => $new_id),array('id' => $value['id']));
                    }
                    return true;
                }
            }
        }
        return false;
    }
    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblpurchase_suggested',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}