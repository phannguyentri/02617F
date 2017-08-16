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

        $this->db->where('language', 'english');
        $contract_templates_english = $this->db->get('tblemailtemplates')->result_array();
        foreach ($this->perfex_base->get_available_languages() as $av_language) {
            if ($av_language != 'english') {
                foreach ($contract_templates_english as $template) {
                    if (total_rows('tblemailtemplates', array(
                        'slug' => $template['slug'],
                        'language' => $av_language
                    )) == 0) {
                        $data              = array();
                        $data['slug']      = $template['slug'];
                        $data['type']      = $template['type'];
                        $data['language']  = $av_language;
                        $data['name']      = $template['name'] . ' [' . $av_language . ']';
                        $data['subject']   = $template['subject'];
                        $data['message']   = '';
                        $data['fromname']  = $template['fromname'];
                        $data['plaintext'] = $template['plaintext'];
                        $data['active']    = $template['active'];
                        $data['order']     = $template['order'];
                        $this->db->insert('tblemailtemplates', $data);
                    }
                }
            }
        }
        $data['tickets']   = $this->contract_templates_model->get(array(
            'type' => 'ticket',
            'language' => 'english'
        ));
        $data['estimate']  = $this->contract_templates_model->get(array(
            'type' => 'estimate',
            'language' => 'english'
        ));
        
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

        // English is not included here
        $data['available_languages'] = $this->perfex_base->get_available_languages();

        if (($key = array_search('english', $data['available_languages'])) !== false) {
            unset($data['available_languages'][$key]);
        }

        $data['available_merge_fields'] = get_available_merge_fields();
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
