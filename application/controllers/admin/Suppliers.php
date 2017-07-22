<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Suppliers extends Admin_controller
{
    
    function __construct()
    {
        parent::__construct();
    }
    /* List all suppliers */
    public function index()
    {
        // var_dump(has_permission('suppliers','','create'));die();

        if (!has_permission('suppliers', '', 'view')) {
            if (!has_permission('suppliers','','create')) {
                access_denied('suppliers');
            }
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('suppliers');
        }
        $this->load->model('contracts_model');
        $data['contract_types'] = $this->contracts_model->get_contract_types();
        $data['groups']         = $this->clients_model->get_groups();
        $data['title']          = _l('suppliers');

        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();

        $this->load->model('invoices_model');
        $data['invoice_statuses'] = $this->invoices_model->get_statuses();

        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();

        $this->load->model('projects_model');
        $data['project_statuses'] = $this->projects_model->get_project_statuses();

        $data['customer_admins'] = $this->clients_model->get_customers_admin_unique_ids();

        $this->load->view('admin/suppliers/manage', $data);
    }
    /* Edit supplier or add new supplier*/
    public function supplier($id = '')
    {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            if ($id == '') {
                if (!has_permission('customers', '', 'create')) {
                    access_denied('customers');
                }
                $data                 = $this->input->post();
                $save_and_add_contact = false;
                if (isset($data['save_and_add_contact'])) {
                    unset($data['save_and_add_contact']);
                    $save_and_add_contact = true;
                }
                $id = $this->clients_model->add($data);
                if (!has_permission('customers', '', 'view')) {
                    $assign['customer_admins']   = array();
                    $assign['customer_admins'][] = get_staff_user_id();
                    $this->clients_model->assign_admins($assign, $id);
                }
                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('client')));
                    if ($save_and_add_contact == false) {
                        redirect(admin_url('clients/client/' . $id));
                    } else {
                        redirect(admin_url('clients/client/' . $id . '?new_contact=true'));
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
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        $data['user_notes'] = $this->misc_model->get_notes($id, 'customer');
        $data['bodyclass'] = 'customer-profile';
        $this->load->model('projects_model');
        $data['project_statuses'] = $this->projects_model->get_project_statuses();
        $data['contacts']         = $this->clients_model->get_contacts($id);


        $data['title'] = $title;
        $this->load->view('admin/suppliers/supplier', $data);
    }
}
