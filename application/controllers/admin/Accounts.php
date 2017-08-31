<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends Admin_controller {
    function __construct() {
        parent::__construct();
        $this->load->model('accounts_model');
    }
    public function index() {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        $data['title'] = "Tài khoản kế toán";
        $accounts = $this->accounts_model->get_accounts(array(), true);
        $accountAttributes = $this->accounts_model->get_account_attributes(true);
        $data['accounts'] = $accounts;
        $data['accountAttributes'] = $accountAttributes;

        if($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('account');
        }
        $this->load->view('admin/accounts/manage', $data);
    }
    public function getAccounts() {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if($this->input->is_ajax_request()) {
            $accounts = $this->accounts_model->get_accounts(array(), true);
            exit(json_encode($accounts));
        }
    }
    public function ajax($id='') {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        $result = new stdClass();
        $result->success = false;
        $result->message = 'Xử lý không thành công!';
        if($this->input->is_ajax_request() && $this->input->post()) {
            $data = $this->input->post();
            if($id!='') {
                $result->success = $this->accounts_model->edit($id, $data);
                if($result->success)
                {
                    $result->message = "Sửa thành công";
                }
            }
            else {
                $result->success = $this->accounts_model->add($data);
                if($result->success)
                {
                    $result->message = "Thêm thành công";
                }
            }
        }
        exit(json_encode($result));
    }
    public function attributes() {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('account_attributes');
        }
    }
    
}