<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Imports_model extends CRM_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }
    public function getImportByID($id = '')
    {
        $this->db->select('tblimports.*,tblstaff.fullname as creater,(SELECT fullname  FROM tblstaff WHERE user_head_id=tblstaff.staffid) as head,(SELECT fullname  FROM tblstaff WHERE user_admin_id=tblstaff.staffid) as admin');
        $this->db->from('tblimports');
        $this->db->join('tblstaff','tblstaff.staffid=tblimports.create_by','left');
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $invoice = $this->db->get()->row();
            if ($invoice) {
                $invoice->items       = $this->getImportItems($id);
            }
            return $invoice;
        }

        return false;
    }

    public function getImportByIDAndCategoryParentID($id = '', $categoryParentId)
    {
        $this->db->select('tblimports.*,tblstaff.fullname as creater,(SELECT fullname  FROM tblstaff WHERE user_head_id=tblstaff.staffid) as head,(SELECT fullname  FROM tblstaff WHERE user_admin_id=tblstaff.staffid) as admin');
        $this->db->from('tblimports');
        $this->db->join('tblstaff','tblstaff.staffid=tblimports.create_by','left');
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $invoice = $this->db->get()->row();
            if ($invoice) {
                $invoice->items       = $this->getImportItemsByParent($id, $categoryParentId);
            }
            return $invoice;
        }

        return false;
    }

    public function getImportItemsByParent($id, $categoryParentId)
    {
        $this->db->select('tblimport_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id,tblitems.prefix,tblitems.code,');
        $this->db->from('tblimport_items');
        $this->db->join('tblitems','tblitems.id=tblimport_items.product_id','left');
        $this->db->join('tblcategories', 'tblcategories.id=tblitems.category_id', 'left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->where('import_id', $id);
        $this->db->where('tblcategories.category_parent', $categoryParentId);
        $items = $this->db->get()->result();
        return $items;

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

    public function update_status($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblimports',$data);
        if ($this->db->affected_rows() > 0) {
            if($data['status']==2)
            {
                $this->updateWarehouse($id);
            }
            return true;
        }
        return false;
    }
    public function updateWarehouse($id)
    {
        $imports=$this->getImportByID($id);
        $count=0;
        if($imports)
        {
            foreach ($imports->items as $key => $value)
            {
                $item=$this->db->get_where('tblwarehouses_products',array('product_id'=>$value->product_id,'warehouse_id'=>$value->warehouse_id))->row();
                if($item)
                {
                    $total_quantity=$value->quantity+$item->product_quantity;
                    $data=array('product_quantity'=>$total_quantity);
                    $this->db->update('tblwarehouses_products',$data,array('id'=>$item->id, 'warehouse_id' => $value->warehouse_id));/////////////////////////////
                    $count++;
                }
                else
                {
                    $data=array(
                        'product_id'=>$value->product_id,
                        'warehouse_id'=>$value->warehouse_id,
                        'product_quantity'=>$value->quantity
                        );
                    $this->db->insert('tblwarehouses_products',$data);
                    $insert_id=$this->db->insert_id();
                    if($insert_id)
                    {
                        logActivity('Insert Warehouse Product [ID:' . $insert_id . ', Item ID' . $value->product_id . ']');
                        $count++;
                    }
                }

            }
        }
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function getImportItems($id)
    {
        $this->db->select('tblimport_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id,tblitems.prefix,tblitems.code,');
        $this->db->from('tblimport_items');
        $this->db->join('tblitems','tblitems.id=tblimport_items.product_id','left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->where('import_id', $id);
        $items = $this->db->get()->result();
        return $items;

    }
    public function get_roles()
    {
        $is_admin = is_admin();
        $roles = $this->db->get('tblroles')->result_array();
        return $roles;
    }
    public function add_warehouses_adjustment($data)
    {
        if (is_admin()) {
            $this->db->insert('tblwarehouses_products',$data);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function update_warehouses_adjustment($data_vestion,$id)
    {
        if (is_admin()) {
            // var_dump($data_vestion);die();
            $this->db->where('id',$id);
            $this->db->update('tblwarehouses_products',$data_vestion);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function delete_warehouses_adjustment($id)
    {
        if (is_admin()) {
            $this->db->where('id', $id);
            $this->db->delete('tblimports');
            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }
        return false;
    }
    public function get_row_unit($id)
    {
        if (is_admin()) {
            $this->db->select('tblwarehouses_products.*');
            $this->db->where('tblwarehouses_products.id', $id);
            return $this->db->get('tblwarehouses_products')->row();
        }
    }

    public function getProductById($id)
    {
            $this->db->select('tblitems.*,tblunits.unit as unit_name');
            $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
            $this->db->where('id', $id);
            return $this->db->get('tblitems')->row();
    }


    /**
     * Get all invoice items
     * @param  mixed $id invoiceid
     * @return array
     */
    public function get_invoice_items($id)
    {
        $this->db->select('tblpurchase_plan_details.*,tblitems.name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id');
        $this->db->from('tblpurchase_plan_details');
        $this->db->join('tblitems','tblitems.id=tblpurchase_plan_details.product_id','left');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->where('purchase_plan_id', $id);
        $items = $this->db->get()->result_array();
        return $items;

    }

   public function add($data)
   {
        $import=array(
            'rel_type'=>$data['rel_type'],
            'prefix'=>$data['prefix'],
            'name'=>$data['name'],
            'code'=>$data['code'],
            'reason'=>$data['reason'],
            'date'=>to_sql_date($data['date']),
            'create_by'=>get_staff_user_id()
            );
    // var_dump($import);die();

        $this->db->insert('tblimports', $import);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Import Added [ID:' . $insert_id . ', ' . $data['description'] . ']');
            $items=$data['items'];
             $total=0;

            foreach ($items as $key => $item) {
                $product=$this->getProductById($item['id']);
                $sub_total=$product->price*$item['quantity'];
                $total+=$sub_total;
                $item_data=array(
                    'import_id'=>$insert_id,
                    'product_id'=>$item['id'],
                    'specifications'=>$product->description,
                    'unit_id'=>$product->unit,
                    'quantity'=>$item['quantity'],
                    'unit_cost'=>$product->price,
                    'sub_total'=>$sub_total,
                    'warehouse_id'=>$data['warehouse_id']
                    );
                 $this->db->insert('tblimport_items', $item_data);
                 if($this->db->affected_rows()>0)
                 {
                    logActivity('Insert Import Item Added [ID:' . $insert_id . ', Item ID' . $item['id'] . ']');
                 }
            }
            $this->db->update('tblimports',array('total'=>$total),array('id'=>$insert_id));
            return $insert_id;
        }
        return false;
    }

     public function update($data,$id)
   {
        $affected=0;
        $import=array(
            'prefix'=>$data['prefix'],
            'name'=>$data['name'],
            'code'=>$data['code'],
            'reason'=>$data['reason']
            );

        if($this->db->update('tblimports',$import,array('id'=>$id)) && $this->db->affected_rows()>0)
        {
            logActivity('Edit Import Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');
            $count=0;
            $affected=1;
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
                $itm=$this->getImportItem($id,$item['id']);
                $item_data=array(
                    'import_id'=>$id,
                    'product_id'=>$item['id'],
                    'specifications'=>$product->description,
                    'unit_id'=>$product->unit,
                    'quantity'=>$item['quantity'],
                    'unit_cost'=>$product->price,
                    'sub_total'=>$sub_total,
                    'warehouse_id'=>$data['warehouse_id']
                    );
                if($itm)
                {
                    $this->db->update('tblimport_items', $item_data,array('id'=>$itm->id));
                    if($this->db->affected_rows()>0)
                     {
                        logActivity('Edit Import Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
                else
                {
                    $this->db->insert('tblimport_items', $item_data);
                    if($this->db->affected_rows()>0)
                     {
                        logActivity('Insert Import Item Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                     }
                }
            }
                if(!empty($affected_id))
                {
                    $this->db->where('import_id', $id);
                    $this->db->where_not_in('product_id', $affected_id);
                    $this->db->delete('tblimport_items');
                }

            $this->db->update('tblimports',array('total'=>$total),array('id'=>$id));
            return $id;
        }
        return false;
    }

    public function getImportItem($import_id,$product_id)
    {
        if (is_numeric($import_id) && is_numeric($product_id)) {
            $this->db->where('import_id', $import_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblimport_items')->row();
        }
        return false;
    }


}
