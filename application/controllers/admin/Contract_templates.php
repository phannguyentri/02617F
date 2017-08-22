<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contract_templates extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('contract_templates_model');
    }
    /* List all email templates */
    public function index()
    {
        if (!has_permission('contract_templates', '', 'view')) {
            access_denied('contract_templates');
        }
        
        $data['templates']   = $this->contract_templates_model->getContractTypes();
        $data['title']     = _l('contract_templates');

        $this->load->view('admin/contract_templates/contract_templates', $data);
    }
    /* Edit contract template */
    public function contract_template($id)
    {
        if (!has_permission('contract_templates', '', 'view')) {
            access_denied('contract_templates');
        }
        if (!$id) {
            redirect(admin_url('contract_templates'));
        }
        if ($this->input->post()) {

            if (!has_permission('contract_templates', '', 'edit')) {
                access_denied('contract_templates');
            }
            $success = $this->contract_templates_model->update($this->input->post(NULL, FALSE), $id);
            if ($success) {
                set_alert('success', _l('updated_successfuly', _l('contract_template')));
            }
            redirect(admin_url('contract_templates/contract_template/' . $id));
        }
        // echo "<pre>";var_dump($contract_merge_fields);die();
        $contract_merge_fields  = get_available_merge_fields();
        $_contract_merge_fields = array();
        foreach ($contract_merge_fields as $key => $val) {
            foreach ($val as $type => $f) {
                if ($type == 'contract') {
                    foreach ($f as $available) {
                        foreach ($available['available'] as $av) {
                            if ($av == 'contract') {
                                array_push($_contract_merge_fields, $f);
                                break;
                            }
                        }
                        break;
                    }
                } else if ($type == 'contract_purchase') {
                    array_push($_contract_merge_fields, $f);
                } else if ($type == 'suppliers') {
                    array_push($_contract_merge_fields, $f);
                } else if ($type == 'other') {
                    array_push($_contract_merge_fields, $f);
                } else if ($type == 'clients') {
                    array_push($_contract_merge_fields, $f);
                } 
            }
        }
        // print_r($_contract_merge_fields);
        // exit();
        $data['contract_merge_fields'] = $_contract_merge_fields;
        $data['template']               = $this->contract_templates_model->get_contract_template_by_id($id);

        $title                          = _l('edit', _l('contract_template'));
        $data['title']                  = $title;
        $this->load->view('admin/contract_templates/template', $data);
    }
    /* Since version 1.0.1 - test your smtp settings */
    public function sent_smtp_test_email()
    {
        if ($this->input->post()) {
            do_action('before_send_test_smtp_email');
            $this->email->initialize();
            $this->email->set_newline("\r\n");
            $this->email->from(get_option('smtp_email'), get_option('companyname'));
            $this->email->to($this->input->post('test_email'));
            $this->email->subject('Perfex SMTP setup testing');
            $this->email->message('This is test email SMTP from Perfex. <br />If you received this message that means that your SMTP settings is set correctly');
            if ($this->email->send()) {
                set_alert('success', 'Seems like your SMTP settings is set correctly. Check your email now.');
            } else {
                set_debug_alert('<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . $this->email->print_debugger());
            }
        }
    }
}
