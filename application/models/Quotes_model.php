<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Quotes_model extends CRM_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }

    public function getQuoteByID($id = '')
    {
        $this->db->select('tblquotes.*,tblstaff.fullname as creater,(SELECT fullname  FROM tblstaff WHERE user_head_id=tblstaff.staffid) as head,(SELECT fullname  FROM tblstaff WHERE user_admin_id=tblstaff.staffid) as admin,tblclients.company as customer_name');
        $this->db->from('tblquotes');
        $this->db->join('tblstaff','tblstaff.staffid=tblquotes.create_by','left');//,tblsales.date as order_date
        $this->db->join('tblclients','tblclients.userid=tblquotes.customer_id','left');
        $this->db->join('tblroles','tblroles.roleid=tblstaff.role','left');
        // $this->db->join('tblsales','tblsales.id=tblquotes.rel_id','left');

        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $invoice = $this->db->get()->row();
            // var_dump($invoice);die();
            if ($invoice) {
                $invoice->items       = $this->getQuoteItems($id);
            }
            return $invoice;
        }

        return false;
    }

    public function getQuoteItems($id)
    {
        $this->db->select('tblquote_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id, tblitems.prefix,tblitems.code, tblitems.warranty, tblitems.specification,tblcountries.short_name as made_in,tblitems.avatar as image,tbltaxes.name as tax_name,tbltaxes.taxrate as taxrate');
        $this->db->from('tblquote_items');
        $this->db->join('tblitems','tblitems.id=tblquote_items.product_id','left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->join('tblcountries','tblcountries.country_id=tblitems.country_id','left');
        $this->db->join('tbltaxes','tbltaxes.id=tblitems.tax','left');
        $this->db->where('quote_id', $id);
        $items = $this->db->get()->result();
        return $items;

    }

    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblquotes',$data);

        if ($this->db->affected_rows() > 0) 
        {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblquotes');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('quote_id', $id);
            $this->db->delete('tblquote_items');
            return true;
        }
        return false;
    }

    public function add($data)
    {
        $quote=array(            
            'prefix'=>$data['prefix'],
            'name'=>$data['name'],
            'code'=>$data['code'],
            'customer_id'=>$data['customer_id'],
            'reason'=>nl2br_save_html($data['reason']),
            'date'=>to_sql_date($data['date']),
            'create_by'=>get_staff_user_id()
            );
        $this->db->insert('tblquotes', $quote);        
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Quote Added [ID:' . $insert_id . ', ' . $data['date'] . ']');
            $items=$data['items'];
             $total=0;
             $count=0;
             $affect_product=array();
            foreach ($items as $key => $item) {

                $product=$this->getProductById($item['id']);
                $sub_total=$product->price*$item['quantity'];
                $total+=$sub_total;
                
                $item_data=array(
                    'quote_id'=>$insert_id,
                    'product_id'=>$item['id'],
                    'serial_no'=>$item['serial_no'],
                    'unit_id'=>$product->unit,
                    'quantity'=>$item['quantity'],
                    'unit_cost'=>$product->price,
                    'sub_total'=>$sub_total,
                    'warehouse_id'=>$item['warehouse']
                    );
                 $this->db->insert('tblquote_items', $item_data);
                 if($this->db->affected_rows()>0)
                 {                            
                    logActivity('Insert Quote Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                 }
            }

            $this->db->update('tblquotes',array('total'=>$total),array('id'=>$insert_id));
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

    public function update($data,$id)
   { 
        $affected=false;
        $quote=array(
            'prefix'=>$data['prefix'],
            'name'=>$data['name'],
            'code'=>$data['code'],
            'customer_id'=>$data['customer_id'],
            'reason'=>nl2br_save_html($data['reason']),
            'date'=>to_sql_date($data['date'])
            );
        
        if($this->db->update('tblquotes',$quote,array('id'=>$id)) && $this->db->affected_rows()>0)
        {
            logActivity('Edit Quote Updated [ID:' . $id . ', ' . date('Y-m-d') . ']');
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
                $itm=$this->getQuoteItem($id,$item['id']);
                $item_data=array(
                    'quote_id'=>$id,
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
                    $this->db->update('tblquote_items', $item_data,array('id'=>$itm->id));
                    if($this->db->affected_rows()>0)
                     {
                     	$affected=true;
                        logActivity('Edit Quote Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
                else
                {
                    $this->db->insert('tblquote_items', $item_data);
                    if($this->db->affected_rows()>0)
                     {     
                     	$affected=true;                   
                        logActivity('Insert Quote Item Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
            }
                if(!empty($affected_id))
                {
                    $this->db->where('quote_id', $id);
                    $this->db->where_not_in('product_id', $affected_id);
                    $this->db->delete('tblquote_items');
                }

            $this->db->update('tblquotes',array('total'=>$total),array('id'=>$id));
            return $affected;
        }
        return false;
    }

    public function getQuoteItem($quote_id,$product_id)
    {
        if (is_numeric($quote_id) && is_numeric($product_id)) {
            $this->db->where('quote_id', $quote_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblquote_items')->row();
        }
        return false;
    }
}
