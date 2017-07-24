<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Suppliers extends Admin_controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('suppliers_model');
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
        $data['title']          = _l('suppliers');
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
                var_dump($data);die();
                $id = $this->suppliers_model->add($data);
                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('supplier')));
                    redirect(admin_url('suppliers/supplier/' . $id));
                }
            } else {
                if (!has_permission('customers', '', 'edit')) {
                    if (!is_customer_admin($id)) {
                        access_denied('customers');
                    }
                }
                $success = $this->suppliers_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfuly', _l('supplier')));
                }
                redirect(admin_url('suppliers/supplier/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('suppliers'));
        } else {
            $supplier = $this->suppliers_model->get($id);
            if (!$supplier) {
                blank_page('Client Not Found');
            }

            $data['lightbox_assets'] = true;
            $this->load->model('staff_model');
            $data['staff']           = $this->staff_model->get('', 1);
            $data['customer_admins'] = $this->clients_model->get_admins($id);
            $this->load->model('payment_modes_model');
            $data['payment_modes'] = $this->payment_modes_model->get();
            $data['attachments']   = $this->clients_model->get_all_customer_attachments($id);
            $data['supplier']        = $supplier;
            $title                 = $supplier->company;
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

    /* Delete supplier */
    public function delete($id)
    {
        if (!has_permission('suppliers', '', 'delete')) {
            access_denied('suppliers');
        }
        if (!$id) {
            redirect(admin_url('suppliers'));
        }
        $response = $this->suppliers_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('suppliers')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('suppliers')));
        }
        redirect(admin_url('suppliers'));
    }
}
