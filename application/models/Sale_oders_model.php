<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sale_oders_model extends CRM_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }

    public function getSaleByID($id = '')
    {
        $this->db->select('tblsale_orders.*,tblstaff.fullname as creater,(SELECT fullname  FROM tblstaff WHERE user_head_id=tblstaff.staffid) as head,(SELECT fullname  FROM tblstaff WHERE user_admin_id=tblstaff.staffid) as admin');
        $this->db->from('tblsale_orders');
        $this->db->join('tblstaff','tblstaff.staffid=tblsale_orders.create_by','left');
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $invoice = $this->db->get()->row();

            if ($invoice) {
                $invoice->items       = $this->getSaleItems($id);
            }
            return $invoice;
        }

        return false;
    }

    public function getSaleItems($id)
    {
        $this->db->select('tblsale_order_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id,tblitems.prefix,tblitems.code,');
        $this->db->from('tblsale_order_items');
        $this->db->join('tblitems','tblitems.id=tblsale_order_items.product_id','left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->where('sale_id', $id);
        $items = $this->db->get()->result();
        return $items;

    }
    public function getReturnSaleItems($id)
    {
        $this->db->select('tblsale_order_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id,tblitems.prefix,tblitems.code,');
        $this->db->from('tblsale_order_items');
        $this->db->join('tblitems','tblitems.id=tblsale_order_items.product_id','left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->where('reject_id', $id);
        $items = $this->db->get()->result();
        return $items;

    }

    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblsale_orders',$data);
        if ($this->db->affected_rows() > 0) 
        {
            return true;
        }
        return false;
    }

    public function add($data)
   {
        $import=array(
            'rel_type'=>$data['rel_type'],
            'prefix'=>$data['prefix'],
            'name'=>$data['name'],
            'code'=>$data['code'],
            'customer_id'=>$data['customer_id'],
            'reason'=>$data['reason'],
            'date'=>to_sql_date($data['date']),
            'create_by'=>get_staff_user_id()
            );
        
        $this->db->insert('tblsale_orders', $import);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Sale Added [ID:' . $insert_id . ', ' . $data['date'] . ']');
            $items=$data['items'];
             $total=0;

            foreach ($items as $key => $item) {
                $product=$this->getProductById($item['id']);
                $sub_total=$product->price*$item['quantity'];
                $total+=$sub_total;
                // var_dump("expression");die();
                $item_data=array(
                    'sale_id'=>$insert_id,
                    'product_id'=>$item['id'],
                    'serial_no'=>$item['serial_no'],
                    'unit_id'=>$product->unit,
                    'quantity'=>$item['quantity'],
                    'unit_cost'=>$product->price,
                    'sub_total'=>$sub_total,
                    'warehouse_id'=>$item['warehouse']
                    );
                 $this->db->insert('tblsale_order_items', $item_data);
                 if($this->db->affected_rows()>0)
                 {
                    logActivity('Insert Sale Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                 }
            }
            $this->db->update('tblsale_orders',array('total'=>$total),array('id'=>$insert_id));
            return $insert_id;
        }
        return false;
    }

     public function update($data,$id)
   {
        $affected=0;
         $import=array(
            'rel_type'=>$data['rel_type'],
            'prefix'=>$data['prefix'],
            'name'=>$data['name'],
            'code'=>$data['code'],
            'customer_id'=>$data['customer_id'],
            'reason'=>$data['reason'],
            'date'=>to_sql_date($data['date'])
            );
        
        if($this->db->update('tblsale_orders',$import,array('id'=>$id)) && $this->db->affected_rows()>0)
        {
            logActivity('Edit Sale Updated [ID:' . $id . ', Date' . date('Y-m-d') . ']');
            $count=0;
            $affected=1;
        }
        $this->setDafaultConfirm($id);
        if ($id) {
            $items=$data['items'];
            $itemsR=$data['itemsR'];
            $total=0;
            $affected_id=array();
            $affected_idR=array();
            foreach ($items as $key => $item) {
                $affected_id[]=$item['id'];
                $product=$this->getProductById($item['id']);
                $sub_total=$product->price*$item['quantity'];
                $total+=$sub_total;
                $itm=$this->getSaleItem($id,$item['id']);
                $item_data=array(
                    'sale_id'=>$id,
                    'product_id'=>$item['id'],
                    'serial_no'=>$item['serial_no'],
                    'unit_id'=>$product->unit,
                    'quantity'=>$item['quantity'],
                    'unit_cost'=>$product->price,
                    'sub_total'=>$sub_total,
                    'warehouse_id'=>$item['warehouse']
                    );
                if($itm)
                {
                    $this->db->update('tblsale_order_items', $item_data,array('id'=>$itm->id));
                    if($this->db->affected_rows()>0)
                     {
                        logActivity('Edit Sale Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
                else
                {
                    $this->db->insert('tblsale_order_items', $item_data);
                    if($this->db->affected_rows()>0)
                     {
                        logActivity('Insert Sale Item Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
            }

            if(!empty($affected_id))
                {
                    $this->db->where('sale_id', $id);
                    $this->db->where_not_in('product_id', $affected_id);
                    $this->db->delete('tblsale_order_items');
                }

            $this->db->update('tblsale_orders',array('total'=>$total),array('id'=>$id));

            foreach ($itemsR as $key => $item) {
                $affected_idR[]=$item['id'];
                $product=$this->getProductById($item['id']);
                $sub_total=$product->price*$item['quantity'];
                $itm=$this->getSaleItemReturn($id,$item['id']);
                $item_data=array(
                    'reject_id'=>$id,
                    'product_id'=>$item['id'],
                    'serial_no'=>$item['serial_no'],
                    'unit_id'=>$product->unit,
                    'quantity'=>$item['quantity'],
                    'unit_cost'=>$product->price,
                    'sub_total'=>$sub_total,
                    'warehouse_id'=>$item['warehouse']
                    );
                if($itm)
                {
                    $this->db->update('tblsale_order_items', $item_data,array('id'=>$itm->id));
                    if($this->db->affected_rows()>0)
                     {
                        logActivity('Edit  Item Return Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
                else
                {
                    $this->db->insert('tblsale_order_items', $item_data);
                    if($this->db->affected_rows()>0)
                     {
                        logActivity('Insert Sale Item Return Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
            }
            
            if(!empty($affected_idR))
                {
                    $this->db->where('reject_id', $id);
                    $this->db->where_not_in('product_id', $affected_id);
                    $this->db->delete('tblsale_order_items');
                }
            else       
            {
                $this->db->where('reject_id', $id);
                $this->db->delete('tblsale_order_items');
            }     
                
            return $id;
        }
        return false;
    }

    public function setDafaultConfirm($id)
    {
        $data=array(
            'user_head_id'=>NULL,
            'user_admin_id'=>NULL,
            'user_head_date'=>NULL,
            'user_admin_date'=>NULL,
            'status'=>0
            );
        $this->db->update('tblsale_orders',$data,array('id'=>$id));
        if($this->db->affected_rows()>0)
        {
            return true;
        }
        return false;
    }

    public function getSaleItem($sale_id,$product_id)
    {
        if (is_numeric($sale_id) && is_numeric($product_id)) {
            $this->db->where('sale_id', $sale_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblsale_order_items')->row();
        }
        return false;
    }
    public function getSaleItemReturn($sale_id,$product_id)
    {
        if (is_numeric($sale_id) && is_numeric($product_id)) {
            $this->db->where('reject_id', $sale_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblsale_order_items')->row();
        }
        return false;
    }

    public function getProductById($id)
    {       
            $this->db->select('tblitems.*,tblunits.unit as unit_name');
            $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
            $this->db->where('id', $id);
            return $this->db->get('tblitems')->row();
    }
    
    public function getWarehouseTypes($id = '')
    {
        $this->db->select('tbl_kindof_warehouse.*');
        $this->db->from('tbl_kindof_warehouse');
        if (is_numeric($id)) 
        {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }
        else 
        {
            return $this->db->get()->result_array();
        }

        return false;
    }

    public function delete($id)
    {
        if($this->db->delete('tblsale_orders',array('id'=>$id)) && $this->db->delete('tblsale_order_items',array('sale_id'=>$id)));
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}
