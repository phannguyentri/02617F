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
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            if ($id == '') {
                if (!has_permission('items', '', 'create')) {
                    access_denied('items');
                }
                $data                 = $this->input->post();
                $save_and_add_contact = false;
                if (isset($data['save_and_add_contact'])) {
                    unset($data['save_and_add_contact']);
                    $save_and_add_contact = true;
                }
                $id = $this->clients_model->add($data);
                if (!has_permission('items', '', 'view')) {
                    $assign['customer_admins']   = array();
                    $assign['customer_admins'][] = get_staff_user_id();
                    $this->clients_model->assign_admins($assign, $id);
                }
                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('client')));
                    if ($save_and_add_contact == false) {
                        redirect(admin_url('invoice_items/item/' . $id));
                    } else {
                        redirect(admin_url('invoice_items/item/' . $id . '?new_contact=true'));
                    }
                }
            } else {
                if (!has_permission('customers', '', 'edit')) {
                    if (!is_customer_admin($id)) {
                        access_denied('customers');
                    }
                }
                $success = $this->clients_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfuly', _l('client')));
                }
                redirect(admin_url('clients/client/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('client_lowercase'));
        } else {
            $client = $this->clients_model->get($id);
            if (!$client) {
                blank_page('Client Not Found');
            }

            $data['lightbox_assets'] = true;
            $this->load->model('staff_model');
            $data['staff']           = $this->staff_model->get('', 1);
            $data['customer_admins'] = $this->clients_model->get_admins($id);
            $this->load->model('payment_modes_model');
            $data['payment_modes'] = $this->payment_modes_model->get();
            $data['attachments']   = $this->clients_model->get_all_customer_attachments($id);
            $data['client']        = $client;
            $title                 = $client->company;
            // Get all active staff members (used to add reminder)
            $this->load->model('staff_model');
            $data['members'] = $this->staff_model->get('', 1);
            if ($this->input->is_ajax_request()) {
                $this->perfex_base->get_table_data('tickets', array(
                    'userid' => $id
                ));
            }
            $data['customer_groups'] = $this->clients_model->get_customer_groups($id);

            $this->load->model('estimates_model');
            $data['estimate_statuses'] = $this->estimates_model->get_statuses();

            $this->load->model('invoices_model');
            $data['invoice_statuses'] = $this->invoices_model->get_statuses();

            if (!empty($data['client']->company)) {
                // Check if is realy empty client company so we can set this field to empty
                // The query where fetch the client auto populate firstname and lastname if company is empty
                if (is_empty_customer_company($data['client']->userid)) {
                    $data['client']->company = '';
                }
            }
        }
        if (!$this->input->get('group')) {
            $group = 'profile';
        } else {
            $group = $this->input->get('group');
        }
        $data['group']  = $group;
        $data['groups'] = $this->clients_model->get_groups();
        // var_dump($data['staff']);die();
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        $data['user_notes'] = $this->misc_model->get_notes($id, 'customer');
        $data['bodyclass'] = 'customer-profile';
        $this->load->model('projects_model');
        $data['project_statuses'] = $this->projects_model->get_project_statuses();
        $data['contacts']         = $this->clients_model->get_contacts($id);


        $data['title'] = $title;
        $this->load->view('admin/clients/client', $data);
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
            $item                   = $this->invoice_items_model->get($id);
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
