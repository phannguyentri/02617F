<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_model extends CRM_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }

    public function getSaleByID($id = '')
    {
        $this->db->select('tblsales.*,tblstaff.fullname as creater,(SELECT fullname  FROM tblstaff WHERE user_head_id=tblstaff.staffid) as head,(SELECT fullname  FROM tblstaff WHERE user_admin_id=tblstaff.staffid) as admin');
        $this->db->from('tblsales');
        $this->db->join('tblstaff','tblstaff.staffid=tblsales.create_by','left');
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
        $this->db->select('tblsale_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id,tblitems.prefix,tblitems.code,');
        $this->db->from('tblsale_items');
        $this->db->join('tblitems','tblitems.id=tblsale_items.product_id','left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->where('sale_id', $id);
        $items = $this->db->get()->result();
        return $items;

    }

    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblsales',$data);
        if ($this->db->affected_rows() > 0) 
        {
            return true;
        }
        return false;
    }

    public function add($data)
   {
    // var_dump($data);die();
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
        
        $this->db->insert('tblsales', $import);
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
                    'sub_total'=>$sub_total
                    );
                 $this->db->insert('tblsale_items', $item_data);
                 if($this->db->affected_rows()>0)
                 {
                    logActivity('Insert Sale Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                 }
            }
            $this->db->update('tblsales',array('total'=>$total),array('id'=>$insert_id));
            return $insert_id;
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
        $this->db->where('id', $id);
        $this->db->delete('tblsales');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}
