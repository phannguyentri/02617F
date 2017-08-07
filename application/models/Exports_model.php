<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Exports_model extends CRM_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }

    public function getExportByID($id = '')
    {
        $this->db->select('tblexports.*,tblstaff.fullname as creater,(SELECT fullname  FROM tblstaff WHERE user_head_id=tblstaff.staffid) as head,(SELECT fullname  FROM tblstaff WHERE user_admin_id=tblstaff.staffid) as admin,(SELECT fullname  FROM tblstaff WHERE receiver_id=tblstaff.staffid) as receiver_name,(SELECT tblroles.name  FROM tblstaff JOIN tblroles ON tblroles.roleid=tblstaff.role WHERE receiver_id=tblstaff.staffid) as receiver_department,tblclients.company as customer_name,(SELECT tblsales.date  FROM tblsales  WHERE tblexports.rel_id=tblsales.id) as order_date');
        $this->db->from('tblexports');
        $this->db->join('tblstaff','tblstaff.staffid=tblexports.create_by','left');//,tblsales.date as order_date
        $this->db->join('tblclients','tblclients.userid=tblexports.customer_id','left');
        $this->db->join('tblroles','tblroles.roleid=tblstaff.role','left');
        // $this->db->join('tblsales','tblsales.id=tblexports.rel_id','left');
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $invoice = $this->db->get()->row();
            // var_dump($invoice);die();
            if ($invoice) {
                $invoice->items       = $this->getExportItems($id);
            }
            return $invoice;
        }

        return false;
    }

    public function getExportItems($id)
    {
        $this->db->select('tblexport_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id,tblitems.prefix,tblitems.code,');
        $this->db->from('tblexport_items');
        $this->db->join('tblitems','tblitems.id=tblexport_items.product_id','left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->where('export_id', $id);
        $items = $this->db->get()->result();
        return $items;

    }

    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblexports',$data);

        if ($this->db->affected_rows() > 0) 
        {
            return true;
        }
        return false;
    }

    // $export=$this->getExportByID($id);
        
    //     if($data['status']==2 && isset($export->rel_id))
    //     {
    //         $sale=$this->getSaleByID($export->rel_id);
    //         $saleitem=$sale->items;
    //         foreach ($saleitem as $key => $value) {
    //             $export_quantity=0;
    //             $export_quantity=$value->export_quantity+
    //             # code...
    //         }

    //         var_dump($sale);die();
    //     }
    // if(!empty($data['rel_id']))
    // {
    //     $sale=$this->getSaleByID($data['rel_id']);
    //     $export_quantity=$sale->export_quantity+$item['quantity'];
    //     $this->db->update('tblsale_items',array('export_quantity'=>$export_quantity),array('sale_id'=>$sale->id));
    //     if($this->db->affected_rows()>0)
    //     {
    //         if($export_quantity==$sale->quantity)
    //         {
    //             $this->db->update('tblsales',array('export_status'=>1),array('id'=>$sale->id));
    //         }
    //     }
    // }

    public function updateWarehouse($id)
    {
        $export=$this->getExportByID($id);
        $count=0;
        if($export)
        {
            foreach ($export->items as $key => $value) 
            {
                $item=$this->db->get_where('tblwarehouses_products',array('product_id'=>$value->product_id,'warehouse_id'=>$value->warehouse_id))->row();
                if($item)
                {
                    $total_quantity=$item->product_quantity-$value->quantity;
                    // $total_quantity=($item->product_quantity-$value->quantity)? ($item->product_quantity-$value->quantity): 0 ;
                    $data=array('product_quantity'=>$total_quantity);
                    $this->db->update('tblwarehouses_products',$data,array('id'=>$item->id));
                    $count++;
                }
                else
                {
                    return -1;
                }
                
            }
        }        
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function add($data)
    {
        $export=array(
            'rel_type'=>$data['rel_type'],
            'rel_id'=>$data['rel_id'],
            'rel_code'=>$data['rel_code'],
            'prefix'=>$data['prefix'],
            'name'=>$data['name'],
            'code'=>$data['code'],
            'customer_id'=>$data['customer_id'],
            'receiver_id'=>$data['receiver_id'],
            'reason'=>$data['reason'],
            'date'=>to_sql_date($data['date']),
            'create_by'=>get_staff_user_id()
            );
        $this->db->insert('tblexports', $export);        
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Export Added [ID:' . $insert_id . ', ' . $data['date'] . ']');
            $items=$data['items'];
             $total=0;
             $count=0;
             $affect_product=array();
            foreach ($items as $key => $item) {

                $product=$this->getProductById($item['id']);
                $sub_total=$product->price*$item['quantity'];
                $total+=$sub_total;
                
                $item_data=array(
                    'export_id'=>$insert_id,
                    'product_id'=>$item['id'],
                    'serial_no'=>$item['serial_no'],
                    'unit_id'=>$product->unit,
                    'quantity'=>$item['quantity'],
                    'unit_cost'=>$product->price,
                    'sub_total'=>$sub_total,
                    'warehouse_id'=>$item['warehouse']
                    );
                 $this->db->insert('tblexport_items', $item_data);
                 // var_dump($data);die();
                 if($this->db->affected_rows()>0)
                 {        
                     
                    $affect_product[]=$item['id'];  
                    if(!empty($data['rel_id']))
                    {
                        $sale=$this->getSaleItemByID($data['rel_id'],$item['id']);
                        $export_quantity=$sale->export_quantity+$item['quantity'];
                        $this->db->update('tblsale_items',array('export_quantity'=>$export_quantity),array('id'=>$sale->id));
                        if($this->db->affected_rows()>0 && $export_quantity==$sale->quantity)
                        {
                            $count++;
                        }
                        
                    }                            
                    logActivity('Insert Export Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                 }
            }
                if($count==count($this->getSaleItems($data['rel_id'],$affect_product)))
                {
                    $this->db->update('tblsales',array('export_status'=>1),array('id'=>$data['rel_id']
                        ));
                }
            $this->db->update('tblexports',array('total'=>$total),array('id'=>$insert_id));
            return $insert_id;
        }
        return false;
    }

    public function getSaleItemByID($id,$product_id)
    {       
            $this->db->where('sale_id', $id);
            $this->db->where('product_id', $product_id);
            $q=$this->db->get('tblsale_items');
            if($q->num_rows() > 0)
            {
                return $q->row();
            }
            return false;
    }

    public function getSaleItems($id,$product_id)
    {       
            $this->db->where('sale_id', $id);
            $this->db->where_in('product_id', $product_id);
            $q=$this->db->get('tblsale_items');
            if($q->num_rows() > 0)
            {
                return $q->result();
            }
            return false;
    }

    public function getSaleByID($id)
    {       
        $this->db->where('id', $id);
        $q=$this->db->get('tblsales');
        if($q->num_rows() > 0)
        {
            $res=$q->row();
            $res->items=$this->getSaleItems($id);
            return $res;
        }
        return false;
    }

    public function update($data,$id)
   {
        $affected=false;
        $export=array(
            'rel_type'=>$data['rel_type'],
            'rel_code'=>$data['rel_code'],
            'prefix'=>$data['prefix'],
            'name'=>$data['name'],
            'code'=>$data['code'],
            'customer_id'=>$data['customer_id'],
            'receiver_id'=>$data['receiver_id'],
            'reason'=>$data['reason'],
            'date'=>to_sql_date($data['date'])
            );
        
        if($this->db->update('tblexports',$export,array('id'=>$id)) && $this->db->affected_rows()>0)
        {
            logActivity('Edit Export Updated [ID:' . $id . ', ' . date('Y-m-d') . ']');
            $count=0;
            $affected=true;
        }
        if ($id) {
            $items=$data['items'];
            $total=0;
            $affected_id=array();
            foreach ($items as $key => $item) {
                $affected_id[]=$item['id'];
                $product=$this->getProductById($item['id']);
                $sub_total=$product->price*$item['quantity'];
                $total+=$sub_total;
                $itm=$this->getExportItem($id,$item['id']);
                $item_data=array(
                    'export_id'=>$id,
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
                    $this->db->update('tblexport_items', $item_data,array('id'=>$itm->id));
                    if($this->db->affected_rows()>0)
                     {
                        logActivity('Edit Export Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
                else
                {
                    $this->db->insert('tblexport_items', $item_data);
                    if($this->db->affected_rows()>0)
                     {                        
                        logActivity('Insert Export Item Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
            }
                if(!empty($affected_id))
                {
                    $this->db->where('export_id', $id);
                    $this->db->where_not_in('product_id', $affected_id);
                    $this->db->delete('tblexport_items');
                }

            $this->db->update('tblexports',array('total'=>$total),array('id'=>$id));
            return $id;
        }
        return false;
    }

    public function getExportItem($import_id,$product_id)
    {
        if (is_numeric($import_id) && is_numeric($product_id)) {
            $this->db->where('export_id', $import_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblexport_items')->row();
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
        $this->db->delete('tblexports');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('export_id', $id);
            $this->db->delete('tblexport_items');
            return true;
        }
        return false;
    }
}
