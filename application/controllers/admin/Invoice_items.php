<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoice_items extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_items_model');

    }
    /* List all available items */
    public function index()
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('Invoice Items');
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('invoice_items');
        }
        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        $data['items_units'] = $this->invoice_items_model->get_units();
        // var_dump($data['items_units']);die();
        

        $data['title'] = _l('invoice_items');
        $this->load->view('admin/invoice_items/manage', $data);
    }
    /* Edit client or add new client*/
    public function item($id = '')
    {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        // Add new without ajax
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('items', '', 'create')) {
                    access_denied('items');
                }
                $data                 = $this->input->post();
                $save_and_add_contact = false;
                // Category 3rd level
                if(is_array($data['category_id'])) {
                    for ($i=count($data['category_id'])-1; $i >= 0 ; $i--) { 
                        if( $data['category_id'][$i] != 0 ) {
                            $data['category_id'] = $data['category_id'][$i];
                            break;
                        }
                    }
                }
                // End
                if (isset($data['save_and_add_contact'])) {
                    unset($data['save_and_add_contact']);
                    $save_and_add_contact = true;
                }
                $id = $this->invoice_items_model->add($data);
                if (!has_permission('items', '', 'view')) {
                    $assign['customer_admins']   = array();
                    $assign['customer_admins'][] = get_staff_user_id();
                    $this->invoice_items_model->assign_admins($assign, $id);
                }
                if ($id) {
                    handle_item_avatar_image_upload($id);
                    set_alert('success', _l('added_successfuly', _l('als_products')));
                    //redirect(admin_url('invoice_items/item/' . $id . '?new_contact=true'));
                    redirect(admin_url('invoice_items'));
                }
            } else {
                if (!has_permission('invoice_items', '', 'edit')) {
                    if (!is_customer_admin($id)) {
                        access_denied('invoice_items');
                    }
                }
                $data = $this->input->post();
                $data['itemid'] = $id;
                $item = $this->invoice_items_model->get_full($id);
                if(is_array($data['category_id'])) {
                    for ($i=count($data['category_id'])-1; $i >= 0 ; $i--) { 
                        if( $data['category_id'][$i] != 0 ) {
                            $data['category_id'] = $data['category_id'][$i];
                            break;
                        }
                    }
                }
                
                $success = $this->invoice_items_model->edit($data, $item);
                $success_avatar = handle_item_avatar_image_upload($id);
                if ($success == true || $success_avatar == true) {
                    set_alert('success', _l('updated_successfuly', _l('als_products')));
                }
                // else {
                //     exit("fail");
                // }
                redirect(admin_url('invoice_items/item/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('als_products'));
            $array_categories[] = array(0, $this->invoice_items_model->get_same_level_categories(0));
            $array_categories[1] = array(0, array());
            $array_categories[2] = array(0, array());
            $data['array_categories'] = $array_categories;
            
            
        } else {
            $title = _l('invoice_item_edit_heading');
            $item = $this->invoice_items_model->get_full($id);
            $array_categories = [];

            $array_categories[] = array($item->category_id, $this->invoice_items_model->get_same_level_categories($item->category_id));
            $this->invoice_items_model->get_category_parent_id($item->category_id, $array_categories);
            
            if(count($array_categories) < 3) {
                if(!isset($array_categories[1])) {
                    $array_categories[1] = array(0, array());
                }
                if(!isset($array_categories[2])) {
                    $array_categories[2] = array(0, array());
                }
            }
            if (!$item) {
                blank_page('Client Not Found');
            }
            $data['array_categories'] = $array_categories;
            $data['item'] = $item;
        }


        $data['title'] = $title;
        $this->load->view('admin/invoice_items/item_details', $data);
    }
    public function get_categories($id='') {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        echo json_encode($this->invoice_items_model->get_categories($id));
    }
    public function get_invoice_item_attachment($id) {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if(is_numeric($id)) {
            $item = $this->invoice_items_model->get_full($id);
            if($item) {
                $this->load->view('admin/invoice_items/item_attachments_template', array('attachments'=>$item->attachments));
            }
        }
        
    }
    public function delete_attachment($id)
    {
        echo json_encode(array(
            'success' => $this->invoice_items_model->delete_invoice_item_attachment($id)
        ));
    }
    public function add_item_attachment()
    {
        $item_id = $this->input->post('leadid');
        echo json_encode(handle_invoice_attachments($item_id));
    }
    public function price_history($id = '') {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if($id!='') {
            if($this->input->is_ajax_request()) {
                $this->perfex_base->get_table_data('item_price_history', array(
                    'rel_id' => $id,
                )); 
            }
        }
    }
    public function price_buy_history($id = '') {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if($id!='') {
            
            if($this->input->is_ajax_request()) {
                $this->perfex_base->get_table_data('item_price_buy_history', array(
                    'rel_id' => $id,
                )); 
            }
        }
    }
    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                
                if ($data['itemid'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->invoice_items_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item' => $this->invoice_items_model->get($id)
                    ));
                } else {
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->invoice_items_model->edit($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message
                    ));
                }
            }
        }
    }
    public function add_landtype()
    {

        if ($this->input->post() && has_permission('items', '', 'create')) {

            $this->invoice_items_model->add_landtype($this->input->post());
            set_alert('success', _l('added_successfuly', 'Loại nhà đất'));
        }
    }
    public function add_group()
    {

        if ($this->input->post() && has_permission('items', '', 'create')) {
            $this->invoice_items_model->add_group($this->input->post());
            set_alert('success', _l('added_successfuly', _l('item_group')));
        }
    }

    public function update_group($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->invoice_items_model->edit_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfuly', _l('item_group')));
        }
    }
    public function update_landtype($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->invoice_items_model->edit_landtype($this->input->post(), $id);
            set_alert('success', _l('updated_successfuly', "Loại nhà đất"));
        }
    }
    public function delete_group($id)
    {
        if (has_permission('items', '', 'delete')) {
            if ($this->invoice_items_model->delete_group($id)) {
                set_alert('success', _l('deleted', _l('item_group')));
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }
    public function delete_landtype($id)
    {
        if (has_permission('items', '', 'delete')) {
            if ($this->invoice_items_model->delete_landtype($id)) {
                set_alert('success', _l('deleted', 'Loại nhà đất'));
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }
    /* Delete item*/
    public function delete($id)
    {
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }

        $response = $this->invoice_items_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } else if ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('invoice_items'));
    }
    /* Get item by id / ajax */
    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            
            $item                   = $this->invoice_items_model->get_full($id);
            $item->long_description = nl2br($item->long_description);
            echo json_encode($item);
        }
    }

    /* Get all items */
    public function get_all_items_ajax()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->invoice_items_model->get_all_items_ajax());
        }
    }
}
