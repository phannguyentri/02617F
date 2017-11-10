<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contracts_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Get contract/s
     * @param  mixed  $id         contract id
     * @param  array   $where      perform where
     * @param  boolean $for_editor if for editor is false will replace the field if not will not replace
     * @return mixed
     */
    public function get($id = '', $where = array(), $for_editor = false)
    {
        $this->db->select('*,tblcontracttypes.name as type_name,tblcontracts.id as id');
        $this->db->where($where);
        $this->db->join('tblcontracttypes', 'tblcontracttypes.id = tblcontracts.contract_type', 'left');
        $this->db->join('tblclients', 'tblclients.userid = tblcontracts.client');
        if (is_numeric($id)) {
            
            $this->db->where('tblcontracts.id', $id);
            $contract = $this->db->get('tblcontracts')->row();
            
            $contract->attachments = $this->get_contract_attachments('', $contract->id);
            if ($for_editor == false) {
                $merge_fields = array();
                $merge_fields = array_merge($merge_fields, get_contract_merge_fields($id));
                $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($contract->client));
                $merge_fields = array_merge($merge_fields, get_other_merge_fields());
                foreach ($merge_fields as $key => $val) {
                    if (stripos($contract->content, $key) !== false) {
                        $contract->content = str_ireplace($key, $val, $contract->content);
                    } else {
                        $contract->content = str_ireplace($key, '', $contract->content);
                    }
                }
            }
            return $contract;
        }
        $contracts = $this->db->get('tblcontracts')->result_array();
        $i         = 0;
        foreach ($contracts as $contract) {
            $contracts[$i]['attachments'] = $this->get_contract_attachments('', $contract['id']);
            $i++;
        }
        return $contracts;
    }


    public function get3($id = '', $type = '' ,$where = array(), $for_editor = false)
    {
        $this->db->select('*,tblcontracttypes.name as type_name,tblcontracts.id as id');
        $this->db->where($where);
        $this->db->join('tblcontracttypes', 'tblcontracttypes.id = tblcontracts.contract_type', 'left');
        $this->db->join('tblclients', 'tblclients.userid = tblcontracts.client');
        if (is_numeric($id)) {
            
            $this->db->where('tblcontracts.id', $id);
            $contract = $this->db->get('tblcontracts')->row();
            
            $contract->attachments = $this->get_contract_attachments('', $contract->id);
            if ($for_editor == false) {
                $merge_fields = array();
                $merge_fields = array_merge($merge_fields, get_contract_merge_fields($id,$type));
                $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($contract->client));
                $merge_fields = array_merge($merge_fields, get_other_merge_fields());
                foreach ($merge_fields as $key => $val) {
                    if (stripos($contract->content, $key) !== false) {
                        $contract->content = str_ireplace($key, $val, $contract->content);
                    } else {
                        $contract->content = str_ireplace($key, '', $contract->content);
                    }
                }
            }
            return $contract;
        }
        $contracts = $this->db->get('tblcontracts')->result_array();
        $i         = 0;
        foreach ($contracts as $contract) {
            $contracts[$i]['attachments'] = $this->get_contract_attachments('', $contract['id']);
            $i++;
        }
        return $contracts;
    }
    
    public function getContractByIDDate($id,$date){
        $this->db->where('create_by',$id);
        $this->db->where('create_date',$date);
        return $this->db->count_all_results('tblcontracts');    
    }

    public function getContractByID($id = '')
    {
        
        $this->db->select('*,tblcontracttypes.name as type_name,tblcontracts.id as id');
        $this->db->join('tblcontracttypes', 'tblcontracttypes.id = tblcontracts.contract_type', 'left');
        $this->db->join('tblclients', 'tblclients.userid = tblcontracts.client');
        $this->db->from('tblcontracts');
        // $this->db->join('tblstaff','tblstaff.staffid=tblcontracts.create_by','left');
        if (is_numeric($id)) {
            $this->db->where('tblcontracts.id', $id);
            $invoice = $this->db->get()->row();
            if ($invoice) {
                $invoice->items  = $this->getContractItems($id);
                $invoice->items1 = $this->getContractItems1($id);
                $invoice->items2 = $this->getIncurred($id);
            }
            return $invoice;
        }
        
        return false;
    }

     public function getIncurred($idquote){
        $this->db->select('*');
        if($idquote){
            $this->db->where('tblincurred_contract_contract_id',$idquote);
        }
       
        return $this->db->get('tblincurred_contract')->result();


    }

    public function getIncurredByID($id){
        $this->db->select('*');        
        
        $this->db->where('tblincurred_contract_contract_id',$id);
       
        return $this->db->get('tblincurred_contract')->row();


    }
    
    public function getContractItems($id)
    {
        $this->db->select('tblcontract_items.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id, tblitems.prefix,tblitems.code, tblitems.warranty, tblitems.specification,tblcountries.short_name as made_in,tblitems.price,tblitems.long_description, tblitems.product_features,tblitems.avatar as image, tblcategories.category');
        $this->db->from('tblcontract_items');
        $this->db->join('tblitems', 'tblitems.id=tblcontract_items.product_id', 'left');
        $this->db->join('tblcategories', 'tblcategories.id=tblitems.category_id', 'left');
        $this->db->join('tblunits', 'tblunits.unitid=tblitems.unit', 'left');
        $this->db->join('tblcountries', 'tblcountries.country_id=tblitems.country_id', 'left');
        $this->db->where('contract_id', $id);
        $items = $this->db->get()->result();
        return $items;
        
    }
    
    public function getContractItems1($id)
    {
        $this->db->select('tblcontract_items1.*,tblitems.name as product_name,tblitems.description,tblunits.unit as unit_name,tblunits.unitid as unit_id, tblitems.prefix,tblitems.code, tblitems.warranty, tblitems.specification,tblcountries.short_name as made_in,tblitems.price,tblitems.long_description, tblitems.product_features,tblitems.avatar as image, tblcategories.category');
        $this->db->from('tblcontract_items1');
        $this->db->join('tblitems', 'tblitems.id=tblcontract_items1.product_id', 'left');
        $this->db->join('tblcategories', 'tblcategories.id=tblitems.category_id', 'left');
        $this->db->join('tblunits', 'tblunits.unitid=tblitems.unit', 'left');
        $this->db->join('tblcountries', 'tblcountries.country_id=tblitems.country_id', 'left');
        $this->db->where('contract_id', $id);
        $items = $this->db->get()->result();
        return $items;
        
    }
    /**
     * Select unique contracts years
     * @return array
     */
    public function get_contracts_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(datestart)) as year FROM tblcontracts')->result_array();
    }
    /**
     * @param  integer ID
     * @return object
     * Retrieve contract attachments from database
     */
    public function get_contract_attachments($attachment_id = '', $id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);
            return $this->db->get('tblfiles')->row();
        }
        $this->db->order_by('dateadded', 'desc');
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'contract');
        return $this->db->get('tblfiles')->result_array();
    }
    /**
     * @param   array $_POST data
     * @return  integer Insert ID
     * Add new contract
     */
    public function add($data)
    {
        
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['addedfrom'] = get_staff_user_id();
        
        $data['datestart'] = to_sql_date($data['datestart']);
        unset($data['attachment']);
        if ($data['dateend'] == '') {
            unset($data['dateend']);
        } else {
            $data['dateend'] = to_sql_date($data['dateend']);
        }
        if (isset($data['trash'])) {
            $data['trash'] = 1;
        } else {
            $data['trash'] = 0;
        }
        if (isset($data['not_visible_to_client'])) {
            $data['not_visible_to_client'] = 1;
        } else {
            $data['not_visible_to_client'] = 0;
        }
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }
        $contract = array(
            'prefix' => $data['prefix'],
            'code' => $data['code'],
            'rel_id' => $data['rel_id'],
            'subject' => $data['subject'],
            'contract_value' => $data['contract_value'],
            'description' => $data['description'],
            'client' => $data['client'],
            'datestart' => $data['datestart'],
            'contract_type' => $data['contract_type'],
            'content' => $data['content'],
            'dateend' => $data['dateend'],
            'addedfrom' => $data['addedfrom'],
            'dateadded' => $data['dateadded'],
            'incurred' => $data['contract_incurred'],
            'trash' => $data['trash'],
            'not_visible_to_client' => $data['not_visible_to_client'],
            'create_by' => get_staff_user_id(),
            'create_date' => date('d/m/y'),
            
        );

        $data     = do_action('before_contract_added', $data);
        $this->db->insert('tblcontracts', $contract);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if (isset($data['rel_id'])) {
                $items1 = $data['items1'];
                foreach ($items1 as $key => $item) {
                    
                    $product   = $this->getProductById($item['id']);
                    $sub_total = (str_replace('.','',$item['unit_cost'])*1) * $item['quantity'];
                    $total += $sub_total;
                    $tax = ((str_replace('.','',$item['unit_cost'])*1) * ($product->rate * 1)) / 100;
                    
                    
                    $item_data = array(
                        'contract_id' => $insert_id,
                        'product_id' => $item['id'],
                        'serial_no' => $item['serial_no'],
                        'unit_id' => $product->unit,
                        'quantity' => $item['quantity'],
                        'unit_cost' => (str_replace('.','',$item['unit_cost'])*1),
                        'sub_total' => $sub_total,
                        'warehouse_id' => 1,
                        'tax_id' => $product->tax,
                        'tax_rate' => $product->rate,
                        'tax' => $tax,
                        'amount' => $sub_total + $tax
                    );
                    
                    $this->db->insert('tblcontract_items1', $item_data);
                    
                    if ($this->db->affected_rows() > 0) {
                        logActivity('Insert Quote Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                    }
                }
                

                $items2 = $data['incurred'];
                foreach ($items2 as $key => $item) {
                    if($item['pay_incurred'] && $item['name_incurred']){
                        $sub_total = (str_replace('.','',$item['pay_incurred'])*1);
                        
                        
                        
                        $item_data = array(
                            'tblincurred_contract_contract_id ' => $insert_id,
                            'tblincurred_contract_name ' => $item['name_incurred'],
                            'tblincurred_contract_price ' => $sub_total,
                            
                        );
                        
                        $this->db->insert('tblincurred_contract', $item_data);
                        
                        if ($this->db->affected_rows() > 0) {
                            logActivity('Insert Quote Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                        }
                    }
                }
                
                $items = $data['items'];
                
                foreach ($items as $key => $item) {
                    
                    $product   = $this->getProductById($item['id']);
                    $sub_total =(str_replace('.','',$item['unit_cost'])*1) * $item['quantity'];
                    $total += $sub_total;
                    $tax = ((str_replace('.','',$item['unit_cost'])*1) * ($product->rate * 1)) / 100;
                    
                    $item_data = array(
                        'contract_id' => $insert_id,
                        'product_id' => $item['id'],
                        'serial_no' => $item['serial_no'],
                        'unit_id' => $product->unit,
                        'quantity' => $item['quantity'],
                        'unit_cost' => (str_replace('.','',$item['unit_cost'])*1),
                        'sub_total' => $sub_total,
                        'warehouse_id' => 1,
                        'tax_id' => $product->tax,
                        'tax_rate' => $product->rate,
                        'tax' => $tax,
                        'amount' => $sub_total + $tax
                    );
                    $this->db->insert('tblcontract_items', $item_data);
                    
                }
                
                
                
                if ($this->db->affected_rows() > 0) {
                    logActivity('Insert Quote Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                }
                
                $this->UpdateQuoteStatus(1, $data['rel_id']);
            }
            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields);
            }
            do_action('after_contract_added', $insert_id);
            logActivity('New Contract Added [' . $data['subject'] . ']');
            return $insert_id;
        }
        return false;
    }
    
    public function getProductById($id)
    {
        $this->db->select('tblitems.*,tblunits.unit as unit_name');
        $this->db->join('tblunits', 'tblunits.unitid=tblitems.unit', 'left');
        $this->db->where('id', $id);
        return $this->db->get('tblitems')->row();
    }
    
    public function UpdateQuoteStatus($status, $id)
    {
        $this->db->update('tblquotes', array(
            'export_status' => $status
        ), array(
            'id' => $id
        ));
    }

    // public function AddContractItems($rel_id,$contract_id)
    // {
    
    //     if(!$rel_id)
    //     {
    //         return false;
    //     }
    //     else
    //     {
    //         $this->db->select('"contract_id",product_id, serial_no, unit_id, quantity, tax, discount, unit_cost, sub_total, warehouse_id');
    //         $items=$this->db->get_where('tblquote_items',array('quote_id'=>$rel_id))->result_array();
    //         if(!$items)
    //         {
    //             return false;
    //         }
    //         else
    //         {
    //             $affected=0;
    //             foreach ($items as $key => $item) {
    //                 $item['contract_id']=$contract_id;
    //                 $this->db->insert('tblcontract_items',$item);
    //                 if($this->db->affected_rows()>0)
    //                 {
    //                     logActivity('Add Contract Item Added [ID:' . $contract_id .' Item ID:'.$item['product_id'].']');
    //                     $affected++;
    //                 }
    //             }
    //             if($affected)
    //             {
    //                 return true;
    //             }
    //         }
    //         return false;
    //     }
    // }
    
    /**
     * @param  array $_POST data
     * @param  integer Contract ID
     * @return boolean
     */
    public function update($data, $id)
    {
        $affectedRows      = 0;
        $data['datestart'] = to_sql_date($data['datestart']);
        if ($data['dateend'] == '') {
            $data['dateend'] = NULL;
        } else {
            $data['dateend'] = to_sql_date($data['dateend']);
        }
        if (isset($data['trash'])) {
            $data['trash'] = 1;
        } else {
            $data['trash'] = 0;
        }
        if (isset($data['not_visible_to_client'])) {
            $data['not_visible_to_client'] = 1;
        } else {
            $data['not_visible_to_client'] = 0;
        }
        $_data = do_action('before_contract_updated', array(
            'data' => $data,
            'id' => $id
        ));
        
        $data = $_data['data'];
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }
        $contract = array(
            'prefix' => $data['prefix'],
            'code' => $data['code'],
            'rel_id' => $data['rel_id'],
            'subject' => $data['subject'],
            'contract_value' => $data['contract_value'],
            'description' => $data['description'],
            'client' => $data['client'],
            'datestart' => $data['datestart'],
            'contract_type' => $data['contract_type'],
           
            'dateend' => $data['dateend'],
            
           
            'trash' => $data['trash'],
            'not_visible_to_client' => $data['not_visible_to_client']
            
        );
        $this->db->where('id', $id);
        $idd = $this->db->update('tblcontracts', $contract);
        if ($idd) {
            $affected_id1 = array();
            $items1       = $data['items1'];
            
            foreach ($items1 as $key => $item) {
                $affected_id1 = $item['id'];
                $product      = $this->getProductById($item['id']);
                $sub_total    = (str_replace('.','',$item['unit_cost'])*1) * $item['quantity'];
                $total += $sub_total;
                $tax = ((str_replace('.','',$item['unit_cost'])*1) * ($product->rate * 1)) / 100;
                $itm = $this->getQuoteItem1($id, $item['id']);
                
                $item_data = array(
                    'contract_id' => $id,
                    'product_id' => $item['id'],
                    'serial_no' => $item['serial_no'],
                    'unit_id' => $product->unit,
                    'quantity' => $item['quantity'],
                    'unit_cost' => (str_replace('.','',$item['unit_cost'])*1),
                    'sub_total' => $sub_total,
                    'warehouse_id' => 1,
                    'tax_id' => $product->tax,
                    'tax_rate' => $product->rate,
                    'tax' => $tax,
                    'amount' => $sub_total + $tax
                );
                
                if ($itm) {
                    $this->db->update('tblcontract_items1', $item_data, array(
                        'id' => $itm->id
                    ));
                    if ($this->db->affected_rows() > 0) {
                        $affected = true;
                        logActivity('Edit Quote Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');
                    }
                } else {
                    $this->db->insert('tblcontract_items1', $item_data);
                    if ($this->db->affected_rows() > 0) {
                        $affected = true;
                        logActivity('Insert Quote Item Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                    }
                }
                
                if ($this->db->affected_rows() > 0) {
                    logActivity('Insert Quote Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
                }
            }
            
            if (!empty($affected_id1)) {
                $this->db->where('contract_id', $id);
                $this->db->where_not_in('product_id', $affected_id1);
                $this->db->delete('tblcontract_items1');
            }

            $total2 = 0;
            $affected_id2 = array();
            $items        = $data['incurred'];
            if($items){
                foreach ($items as $key => $value) {
                    if($value['name_incurred'] && $value['pay_incurred']){
                        $affected_id2[] = $value['id'];
                        $itm       = $this->getIncurredByID($value['id']);
                        $total2 += (str_replace('.','',$value['pay_incurred'])*1);
                        $item_data = array(
                            'tblincurred_contract_contract_id' => $id,
                            'tblincurred_contract_name' => $value['name_incurred'],
                            'tblincurred_contract_price' => (str_replace('.','',$value['pay_incurred'])*1),
                            
                        );
                        
                

                        if($itm){
                            $this->db->update('tblincurred_contract', $item_data, array(
                                'tblincurred_contract_id' => $itm->tblincurred_contract_id
                            ));
                            if ($this->db->affected_rows() > 0) {                        
                                $affected = true;
                                logActivity('Edit Quote Item Updated [ID:' . $id . ', Item ID' . $value['tblincurred_id'] . ']');

                            }
                        } else {
                            $this->db->insert('tblincurred_contract', $item_data);
                            $affected_id2[] = $this->db->insert_id();
                            if ($this->db->affected_rows() > 0) {
                                $affected = true;
                                logActivity('Insert Quote Item Added [ID:' . $id . ', Item ID' . $value['tblincurred_id'] . ']');
                            }

                        }

                    }
                    
                    if (!empty($affected_id2)) {
                        $this->db->where('tblincurred_contract_contract_id', $id);
                        $this->db->where_not_in('tblincurred_contract_id', $affected_id2);
                        $this->db->delete('tblincurred_contract');
                        if ($this->db->affected_rows() > 0) {
                            $affected = true;
                        }
                    }
                }
            }else{
                $this->db->where('tblincurred_contract_contract_id', $id);
                $this->db->delete('tblincurred_contract');
            }
            
            $items       = $data['items'];
            $affected_id = array();
            foreach ($items as $key => $item) {
                $affected_id[] = $item['id'];
                $product       = $this->getProductById($item['id']);
                $sub_total     = (str_replace('.','',$item['unit_cost'])*1) * $item['quantity'];
                $total += $sub_total;
                $itm       = $this->getQuoteItem($id, $item['id']);
                $tax = ((str_replace('.','',$item['unit_cost'])*1) * ($product->rate * 1)) / 100;
                
                $item_data = array(
                    'contract_id' => $id,
                    'product_id' => $item['id'],
                    'serial_no' => $item['serial_no'],
                    'unit_id' => $product->unit,
                    'quantity' => $item['quantity'],
                    'unit_cost' => (str_replace('.','',$item['unit_cost'])*1),
                    'sub_total' => $sub_total,
                    'warehouse_id' => 1,
                    'tax_id' => $product->tax,
                    'tax_rate' => $product->rate,
                    'tax' => $tax,
                    'amount' => $sub_total + $tax
                );
                if ($itm) {
                    $this->db->update('tblcontract_items', $item_data, array(
                        'id' => $itm->id
                    ));
                    if ($this->db->affected_rows() > 0) {
                        $affected = true;
                        logActivity('Edit Quote Item Updated [ID:' . $id . ', Item ID' . $item['id'] . ']');
                    }
                } else {
                    $this->db->insert('tblcontract_items', $item_data);
                    if ($this->db->affected_rows() > 0) {
                        $affected = true;
                        logActivity('Insert Quote Item Added [ID:' . $id . ', Item ID' . $item['id'] . ']');
                    }
                }
                
            }
            
            
            
            if ($this->db->affected_rows() > 0) {
                logActivity('Insert Quote Item Added [ID:' . $insert_id . ', Product ID' . $item['id'] . ']');
            }
            
            if (!empty($affected_id)) {
                $this->db->where('contract_id', $id);
                $this->db->where_not_in('product_id', $affected_id);
                $this->db->delete('tblcontract_items');
            }
            if ($this->db->affected_rows() > 0) {
                do_action('after_contract_updated', $id);
                logActivity('Contract Updated [' . $data['subject'] . ']');
                return true;
            }
        }
        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }
    /**
     * @param  integer ID
     * @return boolean
     * Delete contract, also attachment will be removed if any found
     */
    
    public function getQuoteItem($quote_id, $product_id)
    {
        if (is_numeric($quote_id) && is_numeric($product_id)) {
            $this->db->where('contract_id', $quote_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblcontract_items')->row();
        }
        return false;
    }
    
    public function getQuoteItem1($quote_id, $product_id)
    {
        if (is_numeric($quote_id) && is_numeric($product_id)) {
            $this->db->where('contract_id', $quote_id);
            $this->db->where('product_id', $product_id);
            return $this->db->get('tblcontract_items1')->row();
        }
        return false;
    }
    
    public function delete($id)
    {
        do_action('before_contract_deleted', $id);
        $contract = $this->get($id);
        $this->db->where('id', $id);
        $this->db->delete('tblcontracts');


        $this->db->where('contract_id', $id);
        $this->db->delete('tblcontract_items');
            
        $this->db->where('contract_id', $id);
        $this->db->delete('tblcontract_items1');
        
        if ($this->db->affected_rows() > 0) {
            // Delete the custom field values
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'contracts');
            $this->db->delete('tblcustomfieldsvalues');
            
            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'contract');


            



            
            
            
            $attachments = $this->db->get('tblfiles')->result_array();
            foreach ($attachments as $attachment) {
                $this->delete_contract_attachment($attachment['id']);
            }

            


            $this->db->update('tblquotes', array('export_status'=>0), array('id' => $contract->rel_id));        


            $this->db->where('contractid', $id);
            $this->db->delete('tblcontractrenewals');
            // Get related tasks
            $this->db->where('rel_type', 'contract');
            $this->db->where('rel_id', $id);
            $tasks = $this->db->get('tblstafftasks')->result_array();
            foreach ($tasks as $task) {
                $this->tasks_model->delete_task($task['id']);
            }
            logActivity('Contract Deleted [' . $id . ']');


            return true;
        }
        return false;
    }
    /**
     * Function that send contract to customer
     * @param  mixed  $id        contract id
     * @param  boolean $attachpdf to attach pdf or not
     * @param  string  $cc        Email CC
     * @return boolean
     */
    public function send_contract_to_client($id, $attachpdf = true, $cc = '')
    {
        $this->load->model('emails_model');
        $contract = $this->get($id);
        if ($attachpdf) {
            $pdf    = contract_pdf($contract);
            $attach = $pdf->Output(slug_it($contract->subject) . '.pdf', 'S');
        }
        $sent_to = $this->input->post('sent_to');
        $send    = false;
        if (is_array($sent_to)) {
            $i = 0;
            foreach ($sent_to as $contact_id) {
                if ($contact_id != '') {
                    if ($attachpdf) {
                        $this->emails_model->add_attachment(array(
                            'attachment' => $attach,
                            'filename' => slug_it($contract->subject) . '.pdf',
                            'type' => 'application/pdf'
                        ));
                    }
                    if ($this->input->post('email_attachments')) {
                        $_other_attachments = $this->input->post('email_attachments');
                        foreach ($_other_attachments as $attachment) {
                            $_attachment = $this->get_contract_attachments($attachment);
                            $this->emails_model->add_attachment(array(
                                'attachment' => get_upload_path_by_type('contract') . $id . '/' . $_attachment->file_name,
                                'filename' => $_attachment->file_name,
                                'type' => $_attachment->filetype,
                                'read' => true
                            ));
                        }
                    }
                    $contact      = $this->clients_model->get_contact($contact_id);
                    $merge_fields = array();
                    $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($contract->client, $contact_id));
                    $merge_fields = array_merge($merge_fields, get_contract_merge_fields($id));

                    // Send cc only for the first contact
                    if (!empty($cc) && $i > 0) {
                        $cc = '';
                    }
                    if ($this->emails_model->send_email_template('send-contract', $contact->email, $merge_fields, '', $cc)) {
                        $send = true;
                    }
                }
                $i++;
            }
        } else {
            return false;
        }
        if ($send) {
            return true;
        }
        return false;
    }
    /**
     * Delete contract attachment
     * @param  mixed $attachment_id
     * @return boolean
     */
    public function delete_contract_attachment($attachment_id)
    {
        $deleted    = false;
        $attachment = $this->get_contract_attachments($attachment_id);
        
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('contract') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Contract Attachment Deleted [ContractID: ' . $attachment->rel_id . ']');
            }
            
            if (is_dir(get_upload_path_by_type('contract') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('contract') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('contract') . $attachment->rel_id);
                }
            }
        }
        return $deleted;
    }
    /**
     * Get contract types data for chart
     * @return array
     */
    public function get_contracts_types_chart_data()
    {
        $labels = array();
        $totals = array();
        $types  = $this->get_contract_types();
        foreach ($types as $type) {
            $total_rows_where = array(
                'contract_type' => $type['id'],
                'trash' => 0
            );
            if (is_client_logged_in()) {
                $total_rows_where['client']                = get_client_user_id();
                $total_rows_where['not_visible_to_client'] = 0;
            } else {
                if (!has_permission('contracts', '', 'view')) {
                    $total_rows_where['addedfrom'] = get_staff_user_id();
                }
            }
            $total_rows = total_rows('tblcontracts', $total_rows_where);
            if ($total_rows == 0 && is_client_logged_in()) {
                continue;
            }
            array_push($labels, $type['name']);
            array_push($totals, $total_rows);
        }
        $chart = array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => _l('contract_summary_by_type'),
                    'backgroundColor' => 'rgba(3,169,244,0.2)',
                    'borderColor' => "#03a9f4",
                    'borderWidth' => 1,
                    'data' => $totals
                )
            )
        );
        return $chart;
    }
    /**
     * Get contract types values for chart
     * @return array
     */
    public function get_contracts_types_values_chart_data()
    {
        $labels = array();
        $totals = array();
        $types  = $this->get_contract_types();
        foreach ($types as $type) {
            array_push($labels, $type['name']);
            
            $where = array(
                'where' => array(
                    'contract_type' => $type['id'],
                    'trash' => 0
                ),
                'field' => 'contract_value'
            );
            
            if (!has_permission('contracts', '', 'view')) {
                $where['where']['addedfrom'] = get_staff_user_id();
            }
            
            $total = sum_from_table('tblcontracts', $where);
            if ($total == null) {
                $total = 0;
            }
            array_push($totals, $total);
        }
        $chart = array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => _l('contract_summary_by_type_value'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor' => "#84c529",
                    'tension' => false,
                    'borderWidth' => 1,
                    'data' => $totals
                )
            )
        );
        return $chart;
    }
    /**
     * Renew contract
     * @param  mixed $data All $_POST data
     * @return mixed
     */
    public function renew($data)
    {
        $data['new_start_date']      = to_sql_date($data['new_start_date']);
        $data['new_end_date']        = to_sql_date($data['new_end_date']);
        $data['date_renewed']        = date('Y-m-d H:i:s');
        $data['renewed_by']          = get_staff_full_name(get_staff_user_id());
        $data['renewed_by_staff_id'] = get_staff_user_id();
        if (!is_date($data['new_end_date'])) {
            unset($data['new_end_date']);
        }
        // get the original contract so we can check if is expiry notified on delete the expiry to revert
        $_contract                         = $this->get($data['contractid']);
        $data['is_on_old_expiry_notified'] = $_contract->isexpirynotified;
        $this->db->insert('tblcontractrenewals', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $this->db->where('id', $data['contractid']);
            $_data = array(
                'datestart' => $data['new_start_date'],
                'contract_value' => $data['new_value'],
                'isexpirynotified' => 0
            );
            if (isset($data['new_end_date'])) {
                $_data['dateend'] = $data['new_end_date'];
            }
            $this->db->update('tblcontracts', $_data);
            if ($this->db->affected_rows() > 0) {
                logActivity('Contract Renewed [ID: ' . $data['contractid'] . ']');
                return true;
            } else {
                // delete the previous entry
                $this->db->where('id', $insert_id);
                $this->db->delete('tblcontractrenewals');
                return false;
            }
        }
        return false;
    }
    /**
     * Delete contract renewal
     * @param  mixed $id         renewal id
     * @param  mixed $contractid contract id
     * @return boolean
     */
    public function delete_renewal($id, $contractid)
    {
        // check if this renewal is last so we can revert back the old values, if is not last we wont do anything
        $this->db->select('id')->from('tblcontractrenewals')->where('contractid', $contractid)->order_by('id', 'desc')->limit(1);
        $query                 = $this->db->get();
        $last_contract_renewal = $query->row()->id;
        $is_last               = false;
        if ($last_contract_renewal == $id) {
            $is_last = true;
            $this->db->where('id', $id);
            $original_renewal = $this->db->get('tblcontractrenewals')->row();
        }
        $this->db->where('id', $id);
        $this->db->delete('tblcontractrenewals');
        if ($this->db->affected_rows() > 0) {
            if ($is_last == true) {
                $this->db->where('id', $contractid);
                $data = array(
                    'datestart' => $original_renewal->old_start_date,
                    'contract_value' => $original_renewal->old_value,
                    'isexpirynotified' => $original_renewal->is_on_old_expiry_notified
                );
                if ($original_renewal->old_end_date != '0000-00-00') {
                    $data['dateend'] = $original_renewal->old_end_date;
                }
                $this->db->update('tblcontracts', $data);
            }
            logActivity('Contract Renewed [RenewalID: ' . $id . ', ContractID: ' . $contractid . ']');
            return true;
        }
        return false;
    }
    /**
     * Get contract renewals
     * @param  mixed $id contract id
     * @return array
     */
    public function get_contract_renewal_history($id)
    {
        $this->db->where('contractid', $id);
        $this->db->order_by('date_renewed', 'asc');
        return $this->db->get('tblcontractrenewals')->result_array();
    }
    /**
     * Add new contract type
     * @since  Version 1.0.3
     * @param mixed $data All $_POST data
     */
    public function add_contract_type($data)
    {
        $this->db->insert('tblcontracttypes', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Contract Type Added [' . $data['name'] . ']');
            return $insert_id;
        }
        return false;
    }
    /**
     * Edit contract type
     * @since  Version 1.0.3
     * @param mixed $data All $_POST data
     * @param mixed $id Contract type id
     */
    public function update_contract_type($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblcontracttypes', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Contract Type Updated [' . $data['name'] . ', ID:' . $id . ']');
            return true;
        }
        return false;
    }
    /**
     * @since  Version 1.0.3
     * @param  integer ID (optional)
     * @return mixed
     * Get contract type object based on passed id if not passed id return array of all types
     */
    public function get_contract_types($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get('tblcontracttypes')->row();
        }
        return $this->db->get('tblcontracttypes')->result_array();
    }
    
    public function get_contract_items($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('quote_id', $id);
            return $this->db->get('tblquote_items')->row();
        }
        return $this->db->get('tblquote_items')->result_array();
    }
    
    public function get_quote_contracts()
    {
        $this->db->select('tblquotes.*, CONCAT(tblquotes.prefix,tblquotes.code) as quote_name');
        $this->db->where('export_status', 0);
        return $this->db->get('tblquotes')->result_array();
    }
    
    public function get_quote_contracts1($id)
    {
        $this->db->select('tblquotes.*, CONCAT(tblquotes.prefix,tblquotes.code) as quote_name');
        $this->db->join('tblquotes', 'tblquotes.id = tblcontracts.rel_id', 'left');
        $this->db->where('tblquotes.id', $id);
        return $this->db->get('tblcontracts')->row();
    }
    
    /**
     * @param  integer ID
     * @return mixed
     * Delete contract type from database, if used return array with key referenced
     */
    public function delete_contract_type($id)
    {
        if (is_reference_in_table('contract_type', 'tblcontracts', $id)) {
            return array(
                'referenced' => true
            );
        }
        $this->db->where('id', $id);
        $this->db->delete('tblcontracttypes');
        if ($this->db->affected_rows() > 0) {
            logActivity('Contract Deleted [' . $id . ']');
            return true;
        }
        return false;
    }
}
