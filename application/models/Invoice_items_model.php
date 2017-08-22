<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoice_items_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
    public function get($id = '')
    {
        $this->db->select('tblitems.id as itemid,rate,taxrate,tbltaxes.id as taxid,tbltaxes.name as taxname,description,long_description,group_id,tblitems_groups.name as group_name,unit');
        $this->db->from('tblitems');
        $this->db->join('tbltaxes', 'tbltaxes.id = tblitems.tax', 'left');
        $this->db->join('tblitems_groups', 'tblitems_groups.id = tblitems.group_id', 'left');
        $this->db->order_by('tblitems.id', 'desc');
        if (is_numeric($id)) {
            $this->db->where('tblitems.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function get_full($id = '')
    {
        $this->db->select('tblitems.*,tblunits.unit as unit_name,tbltaxes.name as tax_name, tbltaxes.taxrate as taxrate
            ');
        $this->db->from('tblitems');
        $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
        $this->db->join('tbltaxes','tbltaxes.id=tblitems.tax','left');
        $this->db->order_by('tblitems.id', 'desc');
        if (is_numeric($id)) {
            
            $this->db->where('tblitems.id', $id);
            $item = $this->db->get()->row();
            $item->attachments = $this->get_invoice_attachments($id);
            return $item;
        }
        
        return $this->db->get()->result_array();
    }
    public function get_category_parent_id($id_category, &$array) {
        if(is_numeric($id_category)) {
            $this->db->from('tblcategories');
            $this->db->where('id', $id_category);
            $item = $this->db->get()->row();
            if($item && $item->category_parent != 0) {
                array_unshift($array, array($item->category_parent, $this->get_same_level_categories($item->category_parent)));
                $this->get_category_parent_id($item->category_parent, $array);
            }
        }
    }
    public function get_same_level_categories($id_category) {
        if(is_numeric($id_category)) {
            $item = $this->get_category($id_category);
            if($item) {
                $this->db->from('tblcategories');
                $this->db->where('category_parent', $item->category_parent);
                return $this->db->get()->result_array();
            }
            else if($id==0) {
                $this->db->from('tblcategories');
                $this->db->where('category_parent', 0);
                return $this->db->get()->result_array();
            }
        }
        return [];
    }
    public function get_category($id=0) {
        if(is_numeric($id)) {
            $this->db->from('tblcategories');
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }
    }
    public function get_categories($id=0) {
        if(is_numeric($id)) {
            $this->db->from('tblcategories');
            $this->db->where('category_parent', $id);
            return $this->db->get()->result_array();
        }
    }
    public function getPriceHistory($id = '') {
        $this->db->from('item_price_history');
        $this->db->where('id_item', $id);
        $this->db->order_by('id', 'desc');
        
        return $this->db->get()->result_array();
    }
    public function getProvince($id = '')
    {

        $this->db->select('provinceid,name');
        $this->db->from('province');
        $this->db->order_by('name', 'asc');
        // $re=$this->db->get()->result_array();
        // var_dump($id);die();
        if (isset($id) && $id!='') {
            // var_dump('dvsd');die();
            $this->db->where('provinceid', $id);
            return $this->db->get()->row();
        }
        // var_dump('dvsd1');die();
        return $this->db->get()->result_array();
    }

    public function getDistrict($provinceid='')
    {

        $this->db->select('*');
        $this->db->from('district');
        $this->db->order_by('name', 'asc');
        if (isset($provinceid) && $provinceid!='') {
            $this->db->where('provinceid', $provinceid);
            return $this->db->get()->result_array();
        }
        return $this->db->get()->result_array();
    }

     public function getLandType($id = '')
    {

        $this->db->select('*');
        $this->db->from('landtype');
        $this->db->order_by('name', 'asc');
        if (isset($id) && $id!='') {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function get_grouped()
    {

        $items = array();
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get('tblitems_groups')->result_array();

        array_unshift($groups, array(
            'id' => 0,
            'name' => ''
        ));

        foreach ($groups as $group) {
            $this->db->select('*,tblitems_groups.name as group_name,tblitems.id as id');
            $this->db->where('group_id', $group['id']);
            $this->db->join('tblitems_groups', 'tblitems_groups.id = tblitems.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get('tblitems')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = array();
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }
        return $items;
    }
    /**
     * Add new import item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        // var_dump($data);die();
        unset($data['itemid']);
        if ($data['tax'] == '') {
            unset($data['tax']);
        }
        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        $this->db->insert('tblitems', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Invoice Item Added [ID:' . $insert_id . ', ' . $data['description'] . ']');
            return $insert_id;
        }
        return false;
    }
    /**
     * Update invoiec item
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data ,$item = null)
    {
        $itemid = $data['itemid'];
        unset($data['itemid']);
        
        if(isset($data['price']) && isset($item) && $data['price'] != $item->price) {
            $price_history_data = array(
                'item_id' => $item->id,
                'price' => $item->price,
                'new_price' => $data['price']
            );

            $this->db->insert('tblitem_price_history', $price_history_data);
        }

        if(isset($data['price_buy']) && isset($item) && $data['price_buy'] != $item->price) {
            $price_buy_history_data = array(
                'item_id' => $item->id,
                'price' => $item->price_buy,
                'new_price' => $data['price_buy']
            );

            $this->db->insert('tblitem_price_buy_history', $price_buy_history_data);
        }

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        $this->db->where('id', $itemid);
        $this->db->update('tblitems', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item Updated [ID: ' . $itemid . ', ' . $data['description'] . ']');
            return true;
        }
        return false;
    }
    /**
     * Delete invoice item
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        // kiểm tra hàng tồn trong kho
        $this->db->where('product_id', $id);
        $items = $this->db->get('tblwarehouses_products')->result();
        if(count($items) > 0) {
            return false;
        }
        // kiểm tra sản phẩm tồn tại trong phiếu đề xuất
        $this->db->where('product_id', $id);
        $items = $this->db->get('tblpurchase_suggested_details')->result();
        if(count($items) > 0) {
            return false;
        }
        // kiểm tra sản phẩm tồn tại trong kế hoạch mua
        $this->db->where('product_id', $id);
        $items = $this->db->get('tblpurchase_plan_details')->result();
        if(count($items) > 0) {
            return false;
        }
        // kiểm tra sản phẩm tồn tại trong kế hoạch mua
        $this->db->where('product_id', $id);
        $items = $this->db->get('tblpurchase_plan_details')->result();
        if(count($items) > 0) {
            return false;
        }
        // kiểm tra sản phẩm tồn tại trong đơn hàng
        $this->db->where('product_id', $id);
        $items = $this->db->get('tblorders_detail')->result();
        if(count($items) > 0) {
            return false;
        }
        // Final
        $this->db->where('id', $id);
        $this->db->delete('tblitems');
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }
    public function get_groups()
    {
        $this->db->order_by('name', 'asc');
        return $this->db->get('tblitems_groups')->result_array();
    }
    public function get_units()
    {
        $this->db->order_by('unit', 'asc');
        return $this->db->get('tblunits')->result_array();
    }
    public function add_landtype($data)
    {
        // var_dump("expression");die();
        // set_alert('success', $data, _l('item_group')));
        $this->db->insert('landtype', $data);
        logActivity('Items Group Created [Name: ' . $data['name'] . ']');
        return $this->db->insert_id();
    }
    public function add_group($data)
    {
        $this->db->insert('tblitems_groups', $data);
        logActivity('Items Land Type Created [Name: ' . $data['name'] . ']');
        return $this->db->insert_id();
    }
    public function edit_group($data, $id)
    {

        $this->db->where('id', $id);
        $this->db->update('tblitems_groups', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Items Group Updated [Name: ' . $data['name'] . ']');
            return true;
        }

        return false;
    }
    public function edit_landtype($data, $id)
    {

        $this->db->where('id', $id);
        $this->db->update('landtype', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Items Land Type Updated [Name: ' . $data['name'] . ']');
            return true;
        }

        return false;
    }
    public function delete_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get('tblitems_groups')->row();

        if ($group) {
            $this->db->where('group_id', $id);
            $this->db->update('tblitems', array(
                'group_id' => 0
            ));

            $this->db->where('id', $id);
            $this->db->delete('tblitems_groups');

            logActivity('Item Group Deleted [Name: ' . $group->name . ']');
            return true;
        }

        return false;
    }
    public function delete_landtype($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get('landtype')->row();

        if ($group) {
            $this->db->where('district_id', $id);
            $this->db->update('tblitems', array(
                'district_id' => 0
            ));

            $this->db->where('id', $id);
            $this->db->delete('landtype');

            logActivity('Item Land Type Deleted [Name: ' . $group->name . ']');
            return true;
        }

        return false;
    }
    /**
     * Get invoice items - ajax call for autocomplete when adding invoicei tems
     * @param  mixed $data query
     * @return array
     */
    public function get_all_items_ajax()
    {
        $this->db->select('tblitems.id as itemid,rate,taxrate,tbltaxes.id as taxid,tbltaxes.name as taxname,description as label,long_description,unit');
        $this->db->from('tblitems');
        $this->db->join('tbltaxes', 'tbltaxes.id = tblitems.tax', 'left');
        $this->db->order_by('description', 'asc');
        return $this->db->get()->result_array();
    }

    /**
     * Add invoice item activity from staff
     * @param  mixed  $id          invoice  id
     * @param  string  $description activity description
     */
    public function log_invoice_item_activity($id, $description, $integration = false, $additional_data = '')
    {
        $log = array(
            'date' => date('Y-m-d H:i:s'),
            'description' => $description,
            'leadid' => $id,
            'staffid' => get_staff_user_id(),
            'additional_data' => $additional_data,
            'full_name' => get_staff_full_name(get_staff_user_id())
        );
        if ($integration == true) {
            $log['staffid']   = 0;
            $log['full_name'] = '[CRON]';
        }

        $this->db->insert('tblinvoiceitemactivitylog', $log);
        return $this->db->insert_id();
    }

    /**
     * Get invoice item attachments
     * @since Version 1.0.4
     * @param  mixed $id lead id
     * @return array
     */
    public function get_invoice_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);
            return $this->db->get('tblfiles')->row();
        }

        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'invoice_item');
        $this->db->order_by('dateadded', 'DESC');
        return $this->db->get('tblfiles')->result_array();
    }
    public function add_attachment_to_database($invoice_item_id, $attachment, $external = false, $form_activity = false)
    {

        $this->misc_model->add_attachment_to_database($invoice_item_id, 'invoice_item', $attachment, $external);

        if ($form_activity == false) {
            $this->Invoice_items_model->log_invoice_item_activity($invoice_item_id, 'not_invoice_item_activity_added_attachment');
        } else {
            $this->Invoice_items_model->log_invoice_item_activity($invoice_item_id, 'not_invoice_item_activity_log_attachment', true, serialize(array(
                $form_activity
            )));
        }

        // // No notification when attachment is imported from web to lead form
        // if ($form_activity == false) {
        //     $invoice_item         = $this->get($invoice_item_id);
        //     $not_user_ids = array();

        //     // if ($lead->addedfrom != get_staff_user_id()) {
        //     //     array_push($not_user_ids, $invoice_item->addedfrom);
        //     // }
        //     // if ($lead->assigned != get_staff_user_id() && $lead->assigned != 0) {
        //     //     array_push($not_user_ids, $lead->assigned);
        //     }

        //     foreach ($not_user_ids as $uid) {
        //         add_notification(array(
        //             'description' => 'not_invoice_item_added_attachment',
        //             'touserid' => $uid,
        //             'link' => '#invoice_item_id=' . $invoice_item_id,
        //             'additional_data' => serialize(array(
        //                 $lead->name
        //             ))
        //         ));
        //     }
        // }
    }
    /**
     * Delete lead attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_invoice_item_attachment($id)
    {
        $attachment = $this->get_invoice_attachments('', $id);
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('invoice') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Invoice Attachment Deleted [LeadID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('invoice_item') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('invoice_item') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('invoice_item') . $attachment->rel_id);
                }
            }
        }
        return $deleted;
    }

}
