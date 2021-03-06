<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Clients extends Admin_controller
{
    private $not_importable_clients_fields = array('userid', 'id', 'is_primary', 'password', 'datecreated', 'last_ip', 'last_login', 'last_password_change', 'active', 'new_pass_key', 'new_pass_key_requested', 'leadid', 'default_currency', 'profile_image', 'default_language', 'direction','show_primary_contact');
    public $pdf_zip;
    function __construct()
    {
        parent::__construct();
    }
    /* List all clients */
    public function index()
    {

        if (!has_permission('customers', '', 'view')) {
            if (!have_assigned_customers() && !has_permission('customers','','create')) {
                access_denied('customers');
            }
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('clients');
        }
        $this->load->model('contracts_model');
        $data['contract_types'] = $this->contracts_model->get_contract_types();
        $data['groups']         = $this->clients_model->get_groups();
        $data['title']          = _l('clients');
        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();

        $this->load->model('invoices_model');
        $data['invoice_statuses'] = $this->invoices_model->get_statuses();

        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();

        $data['clients_iv'] = $this->clients_model->get();
        $this->load->model('projects_model');
        $data['project_statuses'] = $this->projects_model->get_project_statuses();
        $data['customer_admins'] = $this->clients_model->get_customers_admin_unique_ids();
        
        $this->load->view('admin/clients/manage', $data);
    }

    public function quotes($client_id){
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('quote_clients',array('client_id' => $client_id));
        }
        
    }

    function check_exists()
    {

        if ($this->input->post()) {
            $company = $this->input->post('company');
            $phonenumber = $this->input->post('phonenumber');

            $clientid = $this->input->post('clientid');

            if ($clientid != '') {
                $this->db->where('userid', $clientid);
                $_current = $this->db->get('tblclients')->row();

                if ($company != '') {
                    if ($_current->company == $company) {
                        echo json_encode(true);
                        die();
                    }
                }


                if ($phonenumber != ''){
                    if ($_current->phonenumber == $phonenumber) {
                        echo json_encode(true);
                        die();
                    }
                }
            }


            if ($company != '') {
                $this->db->where('company', $company);
            }    

            if ($phonenumber != ''){
                $this->db->where('phonenumber', $phonenumber);                
            }       
            $total_rows = $this->db->count_all_results('tblclients');
            if ($total_rows > 0) {
                echo json_encode(false);
            } else {
                echo json_encode(true);
            }
            die();
        }
    }



    public  function exportexcel()
    {
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('tiêu đề');
        $this->db->select('tblclients.*,tblcontacts.firstname as contact_firstname,tblcontacts.lastname as contact_lastname');
        $this->db->join('tblcontacts','tblcontacts.userid=tblclients.userid','left');
        $client=$this->db->get('tblclients')->result_array();
        $BStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '111112'),
                'size'  => 11,
                'name'  => 'Times New Roman'
            )
        );
        $objPHPExcel->getActiveSheet()->setCellValue('A2','STT')->getStyle('A2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('B2','MÃ KH')->getStyle('B2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('C2','Công ty')->getStyle('C2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('D3','Điện thoại')->getStyle('D3')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('E2','% CHIẾT KHẤU')->getStyle('E2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('F2','Liên hệ chính')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('G2','Email chính')->getStyle('G2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('H2','Địa chỉ')->getStyle('H2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('I2','Mã Nhân viên')->getStyle('I2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('J2','Hoạt động')->getStyle('J2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('K2','NHÓM KH')->getStyle('K2')->applyFromArray($BStyle);

        foreach($client as $rom=>$value)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($rom+3),($rom+1));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($rom+3),$value['userid']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.($rom+3),$value['company']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.($rom+3),$value['phonenumber']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.($rom+3),'chiết khấu');
            $objPHPExcel->getActiveSheet()->setCellValue('F'.($rom+3),$value['contact_firstname'].' '.$value['contact_lastname']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.($rom+3),$value['email']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.($rom+3),$value['address']);

            $code_staff="";
            $this->db->select('staff_code');
            $this->db->join('tblstaff','tblstaff.staffid=tblcustomeradmins.staff_id')->where('tblcustomeradmins.customer_id',$value['userid']);
            $codestaff=$this->db->get('tblcustomeradmins')->result_array();
            foreach($codestaff as $code)
            {
                $code_staff.=$code['staff_code'];
            }


            $objPHPExcel->getActiveSheet()->setCellValue('I'.($rom+3),$code_staff);
            if($value['active']==1)
            {
                $active='Có';
            }
            else
            {
                $active="Không";
            }
            $objPHPExcel->getActiveSheet()->setCellValue('J'.($rom+3),$active);

            $this->db->select('tblcustomersgroups.name as namegroup');
            $this->db->where('tblcustomergroups_in.customer_id',$value['userid']);
            $this->db->join('tblcustomersgroups','tblcustomersgroups.id=tblcustomergroups_in.groupid');
            $group=$this->db->get('tblcustomergroups_in')->result_array();
            $group_clients="";
            foreach($group as $group_name)
            {
                $group_clients.=$group_name['namegroup'].' ';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('K'.($rom+3),$group_clients);
        }
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="filexuat.xls"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();


    }
    public function get_wards($district_id) {
        if(is_numeric($district_id) && $this->input->is_ajax_request()) {
            echo json_encode(get_all_wards($district_id));
        }
    }
    public function get_districts($province_id) {
        if(is_numeric($province_id) && $this->input->is_ajax_request()) {
            echo json_encode(get_all_district($province_id));
        }
    }
    public function get_province($country_id) {
        if(is_numeric($province_id) && $this->input->is_ajax_request()) {
            echo json_encode(get_all_province($country_id));
        }
    }
    /* Edit client or add new client*/
    public function client($id = '')
    {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        

         $reminder_data = '';
         // $this->input->is_ajax_request()
        if ($this->input->post()) {

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
                        redirect(admin_url('clients/'));
                    } else {
                        redirect(admin_url('clients/'));
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
                    $alert_type = 'success';
                    $message    = _l('updated_successfuly', _l('client'));
                }else
                {
                    $alert_type = 'danger';
                    $message    = _l('Cập nhật thất bại', _l('quote'));
                }
                exit(json_encode(array(
                    'alert_type' => $alert_type,
                    'success' => $success,
                    'message' => $message,
                )));
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
        $data['users'] = $this->clients_model->get();
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        $data['user_notes'] = $this->misc_model->get_notes($id, 'customer');
        $data['bodyclass'] = 'customer-profile';
        $this->load->model('projects_model');
        $data['project_statuses'] = $this->projects_model->get_project_statuses();
        $data['contacts']         = $this->clients_model->get_contacts($id);
        $data['sources']  = $this->clients_model->get_source();
        $data['areas']  = $this->clients_model->get_area();
        $data['title'] = $title;
    }
    public function modal($id = '') {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if($id!='') {
            $client = $this->clients_model->get($id);
            if (!$client) {
                blank_page('Client Not Found');
            }
            $data['customer_groups'] = $this->clients_model->get_customer_groups($id);
            $data['customer_groups_name'] = $this->clients_model->get_customer_groups_name($id);
            $this->load->model('currencies_model');
            $data['currencies'] = $this->currencies_model->get();
            $data['group']  = $group;
            $data['groups'] = $this->clients_model->get_groups();
            $data['users'] = $this->clients_model->get();
            $data['sources']  = $this->clients_model->get_source();
            $data['areas']  = $this->clients_model->get_area();
            $data['client'] = $client;
            $data['customer_admins'] = $this->clients_model->get_admins($id);
            $this->load->model('payment_modes_model');
            $data['payment_modes'] = $this->payment_modes_model->get();
            $this->load->model('staff_model');          
            $data['members'] = $this->staff_model->get('', 1);
            $data['attachments']   = $this->clients_model->get_all_customer_attachments($id);
            $data['staff']           = $this->staff_model->get('', 1);
            $data['city'] = $this->clients_model->get_province($client->city);
            $data['stateid'] = $this->clients_model->get_district_by_country_id($client->city);
            $data['stateid1'] = $this->clients_model->get_district_by_country_id($client->shipping_city);
            $data['city1'] = $this->clients_model->get_province($client->shipping_city);
            $data['state'] = $this->clients_model->get_district($client->state);
            $data['state1'] = $this->clients_model->get_district($client->shipping_state);
            $data['user_notes'] = $this->misc_model->get_notes($id, 'customer');
        }

        $data['groups'] = $this->clients_model->get_groups();

        echo json_encode(array(
            'data' => $this->load->view('admin/clients/modals/client', $data, TRUE),
        ));
    }


    public function contact($customer_id, $contact_id = '')
    {
        if (!has_permission('customers', '', 'view')) {
            if (!is_customer_admin($customer_id)) {
                echo _l('access_denied');
                die;
            }
        }
        $data['customer_id'] = $customer_id;
        $data['contactid']   = $contact_id;
        if ($this->input->post()) {
            $data = $this->input->post();
            unset($data['contactid']);
            if ($contact_id == '') {
                if (!has_permission('customers', '', 'create')) {
                    if (!is_customer_admin($customer_id)) {
                        header('HTTP/1.0 400 Bad error');
                        echo json_encode(array(
                            'success' => false,
                            'message' => _l('access_denied')
                        ));
                        die;
                    }
                }
                $id      = $this->clients_model->add_contact($data, $customer_id);
                $message = '';
                $success = false;
                if ($id) {
                    handle_contact_profile_image_upload($id);
                    $success = true;
                    $message = _l('added_successfuly', _l('contact'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
                die;
            } else {
                if (!has_permission('customers', '', 'edit')) {
                    if (!is_customer_admin($customer_id)) {
                        header('HTTP/1.0 400 Bad error');
                        echo json_encode(array(
                            'success' => false,
                            'message' => _l('access_denied')
                        ));
                        die;
                    }
                }
                $original_contact = $this->clients_model->get_contact($contact_id);
                $success          = $this->clients_model->update_contact($data, $contact_id);
                $message          = '';
                $proposal_warning = false;
                $original_email   = '';
                $updated          = false;
                if (is_array($success)) {
                    if (isset($success['set_password_email_sent'])) {
                        $message = _l('set_password_email_sent_to_client');
                    } else if (isset($success['set_password_email_sent_and_profile_updated'])) {
                        $updated = true;
                        $message = _l('set_password_email_sent_to_client_and_profile_updated');
                    }
                } else {
                    if ($success == true) {
                        $updated = true;
                        $message = _l('updated_successfuly', _l('contact'));
                    }
                }
                if (handle_contact_profile_image_upload($contact_id) && !$updated) {
                    $message = _l('updated_successfuly', _l('contact'));
                    $success = true;
                }
                if ($updated == true) {
                    $contact = $this->clients_model->get_contact($contact_id);
                    if (total_rows('tblproposals', array(
                        'rel_type' => 'customer',
                        'rel_id' => $contact->userid,
                        'email' => $original_contact->email
                    )) > 0 && ($original_contact->email != $contact->email)) {
                        $proposal_warning = true;
                        $original_email   = $original_contact->email;
                    }
                }
                echo json_encode(array(
                    'success' => $success,
                    'proposal_warning' => $proposal_warning,
                    'message' => $message,
                    'original_email' => $original_email
                ));
                die;
            }
        }
        if ($contact_id == '') {
            $title = _l('add_new', _l('contact_lowercase'));
        } else {
            $data['contact'] = $this->clients_model->get_contact($contact_id);

            if (!$data['contact']) {
                header('HTTP/1.0 400 Bad error');
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Contact Not Found'
                ));
                die;
            }
            $title = $data['contact']->firstname . ' ' . $data['contact']->lastname;
        }

        $data['customer_permissions'] = $this->perfex_base->get_contact_permissions();
        $data['title']                = $title;
        $this->load->view('admin/clients/modals/contact', $data);
    }
    public function update_file_share_visibility()
    {
        if ($this->input->post()) {

            $file_id           = $this->input->post('file_id');
            $share_contacts_id = array();

            if ($this->input->post('share_contacts_id')) {
                $share_contacts_id = $this->input->post('share_contacts_id');
            }

            $this->db->where('file_id', $file_id);
            $this->db->delete('tblcustomerfiles_shares');

            foreach ($share_contacts_id as $share_contact_id) {
                $this->db->insert('tblcustomerfiles_shares', array(
                    'file_id' => $file_id,
                    'contact_id' => $share_contact_id
                ));
            }

        }
    }
    public function delete_contact_profile_image($contact_id)
    {
        do_action('before_remove_contact_profile_image');
        if (file_exists(get_upload_path_by_type('contact_profile_images') . $contact_id)) {
            delete_dir(get_upload_path_by_type('contact_profile_images') . $contact_id);
        }
        $this->db->where('id', $contact_id);
        $this->db->update('tblcontacts', array(
            'profile_image' => NULL
        ));
    }
    public function mark_as_active($id)
    {
        $this->db->where('userid', $id);
        $this->db->update('tblclients', array(
            'active' => 1
        ));
        redirect(admin_url('clients/client/' . $id));
    }
    public function update_all_proposal_emails_linked_to_customer($contact_id)
    {

        $success = false;
        $email   = '';
        if ($this->input->post('update')) {
            $this->load->model('proposals_model');

            $this->db->select('email,userid');
            $this->db->where('id', $contact_id);
            $contact = $this->db->get('tblcontacts')->row();

            $proposals     = $this->proposals_model->get('', array(
                'rel_type' => 'customer',
                'rel_id' => $contact->userid,
                'email' => $this->input->post('original_email')
            ));
            $affected_rows = 0;

            foreach ($proposals as $proposal) {
                $this->db->where('id', $proposal['id']);
                $this->db->update('tblproposals', array(
                    'email' => $contact->email
                ));
                if ($this->db->affected_rows() > 0) {
                    $affected_rows++;
                }
            }

            if ($affected_rows > 0) {
                $success = true;
            }

        }
        echo json_encode(array(
            'success' => $success,
            'message' => _l('proposals_emails_updated', array(
                _l('contact_lowercase'),
                $contact->email
            ))
        ));
    }

    public function assign_admins($id)
    {
        if (!has_permission('customers', '', 'create') && !has_permission('customers', '', 'edit')) {
            access_denied('customers');
        }
        $success = $this->clients_model->assign_admins($this->input->post(), $id);
        if ($success == true) {
             if ($success) {
                $alert_type = 'success';
                $message    = _l('Thêm thành công');
            }
        }

        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }

    public function delete_customer_admin($customer_id,$staff_id){

        if (!has_permission('customers', '', 'create') && !has_permission('customers', '', 'edit')) {
            access_denied('customers');
        }

        $this->db->where('customer_id',$customer_id);
        $this->db->where('staff_id',$staff_id);
        $this->db->delete('tblcustomeradmins');
        redirect(admin_url('clients/'));
    }
    public function delete_contact($customer_id, $id)
    {


        if (!has_permission('customers', '', 'delete')) {
            if (!is_customer_admin($customer_id)) {
                access_denied('customers');
            }
        }

        $idd = $this->clients_model->delete_contact($id);

        if ($idd) {            
            $success = true;
            $message = _l('Xóa thành công', _l('contact'));
        }

        echo json_encode(array(
            'success' => $success,
            'message' => $message
        ));
        
    }
    public function contacts($client_id)
    {
        $this->perfex_base->get_table_data('contacts', array(
            'client_id' => $client_id
        ));
    }
    public function upload_attachment($id)
    {
        if(handle_client_attachments_upload($id) ){
            $success = true;
            $alert_type = 'success';
            $message    = _l('Tải lên thành công', _l('client'));
        }else{
            $success = false;
            $alert_type = 'danger';
            $message    = _l('Tải lên thất bại', _l('client'));
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'success' => $success,
            'message' => $message
        ));
    }
    public function add_external_attachment()
    {
        if ($this->input->post()) {
            $this->misc_model->add_attachment_to_database($this->input->post('clientid'), 'customer', $this->input->post('files'), $this->input->post('external'));
        }
    }
    
    public function delete_attachment($customer_id, $id)
    {
        if (has_permission('customers', '', 'delete') || is_customer_admin($customer_id)) {
        if($this->clients_model->delete_attachment($id)){
            $success = true;
            $alert_type = 'success';
            $message    = _l('Xóa thành công');
        }else{
            $success = false;
            $alert_type = 'danger';
            $message    = _l('Xóa thất bại');
        }
           
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'success' => $success,
            'message' => $message
        ));
    }


    /* Delete client */
    public function delete($id)
    {
        if (!has_permission('customers', '', 'delete')) {
            access_denied('customers');
        }
        if (!$id) {
            redirect(admin_url('clients'));
        }
        $response = $this->clients_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('client_delete_invoices_warning'));
        } else if ($response == true) {
            set_alert('success', _l('deleted', _l('client')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('client_lowercase')));
        }
        redirect(admin_url('clients'));
    }
    /* Staff can login as client */
    public function login_as_client($id)
    {
        if (is_admin()) {
            $this->clients_model->login_as_client($id);
        }
        do_action('after_contact_login');
        redirect(site_url());
    }
    public function get_customer_billing_and_shipping_details($id)
    {
        echo json_encode($this->clients_model->get_customer_billing_and_shipping_details($id));
    }
    /* Change client status / active / inactive */
    public function change_contact_status($id, $status)
    {
        if (has_permission('customers', '', 'edit')) {
            if ($this->input->is_ajax_request()) {
                $this->clients_model->change_contact_status($id, $status);
            }
        }
    }
    /* Change client status / active / inactive */
    public function change_client_status($id, $status)
    {

        if ($this->input->is_ajax_request()) {
            $this->clients_model->change_client_status($id, $status);
        }

    }
    /* Since version 1.0.2 zip client invoices */
    public function zip_invoices($id)
    {
        $has_permission_view = has_permission('invoices', '', 'view');
        if (!$has_permission_view && !has_permission('invoices', '', 'view_own')) {
            access_denied('Zip Customer Invoices');
        }
        if ($this->input->post()) {
            $status        = $this->input->post('invoice_zip_status');
            $zip_file_name = $this->input->post('file_name');
            if ($this->input->post('zip-to') && $this->input->post('zip-from')) {
                $from_date = to_sql_date($this->input->post('zip-from'));
                $to_date   = to_sql_date($this->input->post('zip-to'));
                if ($from_date == $to_date) {
                    $this->db->where('date', $from_date);
                } else {
                    $this->db->where('date BETWEEN "' . $from_date . '" AND "' . $to_date . '"');
                }
            }
            $this->db->select('id');
            $this->db->from('tblinvoices');
            if ($status != 'all') {
                $this->db->where('status', $status);
            }
            $this->db->where('clientid', $id);
            $this->db->order_by('number,YEAR(date)', 'desc');

            if (!$has_permission_view) {
                $this->db->where('addedfrom', get_staff_user_id());
            }

            $invoices = $this->db->get()->result_array();
            $this->load->model('invoices_model');
            $this->load->helper('file');
            if (!is_really_writable(TEMP_FOLDER)) {
                show_error('/temp folder is not writable. You need to change the permissions to 755');
            }
            $dir = TEMP_FOLDER . $zip_file_name;
            if (is_dir($dir)) {
                delete_dir($dir);
            }
            if (count($invoices) == 0) {
                set_alert('warning', _l('client_zip_no_data_found', _l('invoices')));
                redirect(admin_url('clients/client/' . $id . '?group=invoices'));
            }
            mkdir($dir, 0777);
            foreach ($invoices as $invoice) {
                $invoice_data    = $this->invoices_model->get($invoice['id']);
                $this->pdf_zip   = invoice_pdf($invoice_data);
                $_temp_file_name = slug_it(format_invoice_number($invoice_data->id));
                $file_name       = $dir . '/' . strtoupper($_temp_file_name);
                $this->pdf_zip->Output($file_name . '.pdf', 'F');
            }
            $this->load->library('zip');
            // Read the invoices
            $this->zip->read_dir($dir, false);
            // Delete the temp directory for the client
            delete_dir($dir);
            $this->zip->download(slug_it(get_option('companyname')) . '-invoices-' . $zip_file_name . '.zip');
            $this->zip->clear_data();
        }
    }
    /* Since version 1.0.2 zip client invoices */
    public function zip_estimates($id)
    {
        $has_permission_view = has_permission('estimates', '', 'view');
        if (!$has_permission_view && !has_permission('estimates', '', 'view_own')) {
            access_denied('Zip Customer Estimates');
        }


        if ($this->input->post()) {
            $status        = $this->input->post('estimate_zip_status');
            $zip_file_name = $this->input->post('file_name');
            if ($this->input->post('zip-to') && $this->input->post('zip-from')) {
                $from_date = to_sql_date($this->input->post('zip-from'));
                $to_date   = to_sql_date($this->input->post('zip-to'));
                if ($from_date == $to_date) {
                    $this->db->where('date', $from_date);
                } else {
                    $this->db->where('date BETWEEN "' . $from_date . '" AND "' . $to_date . '"');
                }
            }
            $this->db->select('id');
            $this->db->from('tblestimates');
            if ($status != 'all') {
                $this->db->where('status', $status);
            }
            if (!$has_permission_view) {
                $this->db->where('addedfrom', get_staff_user_id());
            }
            $this->db->where('clientid', $id);
            $this->db->order_by('number,YEAR(date)', 'desc');
            $estimates = $this->db->get()->result_array();
            $this->load->helper('file');
            if (!is_really_writable(TEMP_FOLDER)) {
                show_error('/temp folder is not writable. You need to change the permissions to 777');
            }
            $this->load->model('estimates_model');
            $dir = TEMP_FOLDER . $zip_file_name;
            if (is_dir($dir)) {
                delete_dir($dir);
            }
            if (count($estimates) == 0) {
                set_alert('warning', _l('client_zip_no_data_found', _l('estimates')));
                redirect(admin_url('clients/client/' . $id . '?group=estimates'));
            }
            mkdir($dir, 0777);
            foreach ($estimates as $estimate) {
                $estimate_data   = $this->estimates_model->get($estimate['id']);
                $this->pdf_zip   = estimate_pdf($estimate_data);
                $_temp_file_name = slug_it(format_estimate_number($estimate_data->id));
                $file_name       = $dir . '/' . strtoupper($_temp_file_name);
                $this->pdf_zip->Output($file_name . '.pdf', 'F');
            }
            $this->load->library('zip');
            // Read the invoices
            $this->zip->read_dir($dir, false);
            // Delete the temp directory for the client
            delete_dir($dir);
            $this->zip->download(slug_it(get_option('companyname')) . '-estimates-' . $zip_file_name . '.zip');
            $this->zip->clear_data();
        }
    }
    public function zip_payments($id)
    {
        if (!$id) {
            die('No user id');
        }

        $has_permission_view = has_permission('payments', '', 'view');
        if (!$has_permission_view && !has_permission('invoices', '', 'view_own')) {
            access_denied('Zip Customer Payments');
        }

        if ($this->input->post('zip-to') && $this->input->post('zip-from')) {
            $from_date = to_sql_date($this->input->post('zip-from'));
            $to_date   = to_sql_date($this->input->post('zip-to'));
            if ($from_date == $to_date) {
                $this->db->where('tblinvoicepaymentrecords.date', $from_date);
            } else {
                $this->db->where('tblinvoicepaymentrecords.date BETWEEN "' . $from_date . '" AND "' . $to_date . '"');
            }
        }
        $this->db->select('tblinvoicepaymentrecords.id as paymentid');
        $this->db->from('tblinvoicepaymentrecords');
        $this->db->where('tblclients.userid', $id);
        if (!$has_permission_view) {
            $this->db->where('invoiceid IN (SELECT id FROM tblinvoices WHERE addedfrom=' . get_staff_user_id() . ')');
        }
        $this->db->join('tblinvoices', 'tblinvoices.id = tblinvoicepaymentrecords.invoiceid', 'left');
        $this->db->join('tblclients', 'tblclients.userid = tblinvoices.clientid', 'left');
        if ($this->input->post('paymentmode')) {
            $this->db->where('paymentmode', $this->input->post('paymentmode'));
        }
        $payments      = $this->db->get()->result_array();
        $zip_file_name = $this->input->post('file_name');
        $this->load->helper('file');
        if (!is_really_writable(TEMP_FOLDER)) {
            show_error('/temp folder is not writable. You need to change the permissions to 777');
        }
        $dir = TEMP_FOLDER . $zip_file_name;
        if (is_dir($dir)) {
            delete_dir($dir);
        }
        if (count($payments) == 0) {
            set_alert('warning', _l('client_zip_no_data_found', _l('payments')));
            redirect(admin_url('clients/'));
        }
        mkdir($dir, 0777);
        $this->load->model('payments_model');
        $this->load->model('invoices_model');
        foreach ($payments as $payment) {
            $payment_data               = $this->payments_model->get($payment['paymentid']);
            $payment_data->invoice_data = $this->invoices_model->get($payment_data->invoiceid);
            $this->pdf_zip              = payment_pdf($payment_data);
            $file_name                  = $dir;
            $file_name .= '/' . strtoupper(_l('payment'));
            $file_name .= '-' . strtoupper($payment_data->paymentid) . '.pdf';
            $this->pdf_zip->Output($file_name, 'F');
        }
        $this->load->library('zip');
        // Read the invoices
        $this->zip->read_dir($dir, false);
        // Delete the temp directory for the client
        delete_dir($dir);
        $this->zip->download(slug_it(get_option('companyname')) . '-payments-' . $zip_file_name . '.zip');
        $this->zip->clear_data();
    }
    public function import()
    {
        if (!has_permission('customers', '', 'create')) {
            access_denied('customers');
        }
        $simulate_data  = array();
        $total_imported = 0;
        if ($this->input->post()) {
            if (isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                // Get the temp file path
                $tmpFilePath = $_FILES['file_csv']['tmp_name'];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Setup our new file path
                    $newFilePath = TEMP_FOLDER . $_FILES['file_csv']['name'];
                    if (!file_exists(TEMP_FOLDER)) {
                        mkdir(TEMP_FOLDER, 777);
                    }
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $import_result = true;
                        $fd            = fopen($newFilePath, 'r');
                        $rows          = array();
                        if($ext == 'csv') {
                            while ($row = fgetcsv($fd)) {
                                $rows[] = $row;
                            }
                        }
                        else if($ext == 'xlsx' || $ext == 'xls') {
                            if($type == "application/octet-stream" || $type == "application/vnd.ms-excel" || $type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                                require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');

                                $inputFileType = PHPExcel_IOFactory::identify($newFilePath);
                                
                                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                                
                                $objReader->setReadDataOnly(true);
                                
                                /**  Load $inputFileName to a PHPExcel Object  **/
                            $objPHPExcel =           $objReader->load($newFilePath);
                                $allSheetName       = $objPHPExcel->getSheetNames();
                                $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0);
                                $highestRow         = $objWorksheet->getHighestRow();
                                $highestColumn      = $objWorksheet->getHighestColumn();
                                
                                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                                
                                for ($row = 2; $row <= $highestRow; ++$row) {
                                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                        $value                     = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                                        $rows[$row - 2][$col] = $value;
                                    }
                                }
                            }
                        }
                        $data['total_rows_post'] = count($rows);
                        fclose($fd);
                        if (count($rows) <= 1) {
                            set_alert('warning', 'Not enought rows for importing');
                            redirect(admin_url('clients/import'));
                        }
                        unset($rows[0]);
                        if ($this->input->post('simulate')) {
                            if (count($rows) > 500) {
                                set_alert('warning', 'Recommended splitting the CSV file into smaller files. Our recomendation is 500 row, your CSV file has ' . count($rows));
                            }
                        }
                        $client_contacts_fields = $this->db->list_fields('tblcontacts');
                        $i                      = 0;
                        foreach ($client_contacts_fields as $cf) {
                            if ($cf == 'phonenumber') {
                                $client_contacts_fields[$i] = 'contact_phonenumber';
                            }
                            $i++;
                        }
                        $db_temp_fields = $this->db->list_fields('tblclients');
                        $db_temp_fields = array_merge($client_contacts_fields, $db_temp_fields);
                        $db_fields      = array();
                        foreach ($db_temp_fields as $field) {
                            if (in_array($field, $this->not_importable_clients_fields)) {
                                continue;
                            }
                            $db_fields[] = $field;
                        }
                        $custom_fields = get_custom_fields('customers');
                        $_row_simulate = 0;

                        $required = array(
                            'firstname',
                            'lastname',
                            'email'
                        );

                        if (get_option('company_is_required') == 1) {
                            array_push($required, 'company');
                        }
                        foreach ($rows as $row) {
                            // do for db fields
                            $insert    = array();
                            $duplicate = false;
                            for ($i = 0; $i < count($db_fields); $i++) {
                                if (!isset($row[$i])) {
                                    continue;
                                }
                                if ($db_fields[$i] == 'email') {
                                    $email_exists = total_rows('tblcontacts', array(
                                        'email' => $row[$i]
                                    ));
                                    // dont insert duplicate emails
                                    if ($email_exists > 0) {
                                        $duplicate = true;
                                    }
                                }
                                // Avoid errors on required fields;
                                if (in_array($db_fields[$i], $required) && $row[$i] == '') {
                                    $row[$i] = '/';
                                } else if ($db_fields[$i] == 'country' || $db_fields[$i] == 'billing_country' || $db_fields[$i] == 'shipping_country') {
                                    if ($row[$i] != '') {
                                        $this->db->where('iso2', $row[$i]);
                                        $this->db->or_where('short_name', $row[$i]);
                                        $this->db->or_where('long_name', $row[$i]);
                                        $country = $this->db->get('tblcountries')->row();
                                        if ($country) {
                                            $row[$i] = $country->country_id;
                                        } else {
                                            $row[$i] = 0;
                                        }
                                    } else {
                                        $row[$i] = 0;
                                    }
                                }
                                $insert[$db_fields[$i]] = $row[$i];
                            }

                            if ($duplicate == true) {
                                continue;
                            }
                            if (count($insert) > 0) {
                                $total_imported++;
                                $insert['datecreated'] = date('Y-m-d H:i:s');
                                if ($this->input->post('default_pass_all')) {
                                    $insert['password'] = $this->input->post('default_pass_all');
                                }
                                if (!$this->input->post('simulate')) {
                                    $insert['donotsendwelcomeemail'] = true;
                                    $clientid                        = $this->clients_model->add($insert, true);
                                    if ($clientid) {
                                        if ($this->input->post('groups_in[]')) {
                                            $groups_in = $this->input->post('groups_in[]');
                                            foreach ($groups_in as $group) {
                                                $this->db->insert('tblcustomergroups_in', array(
                                                    'customer_id' => $clientid,
                                                    'groupid' => $group
                                                ));
                                            }
                                        }
                                        if (!has_permission('customers', '', 'view')) {
                                            $assign['customer_admins']   = array();
                                            $assign['customer_admins'][] = get_staff_user_id();
                                            $this->clients_model->assign_admins($assign, $clientid);
                                        }
                                    }
                                } else {
                                    $simulate_data[$_row_simulate] = $insert;
                                    $clientid                      = true;
                                }
                                if ($clientid) {
                                    $insert = array();
                                    foreach ($custom_fields as $field) {
                                        if (!$this->input->post('simulate')) {
                                            if ($row[$i] != '') {
                                                $this->db->insert('tblcustomfieldsvalues', array(
                                                    'relid' => $clientid,
                                                    'fieldid' => $field['id'],
                                                    'value' => $row[$i],
                                                    'fieldto' => 'customers'
                                                ));
                                            }
                                        } else {
                                            $simulate_data[$_row_simulate][$field['name']] = $row[$i];
                                        }
                                        $i++;
                                    }
                                }
                            }
                            $_row_simulate++;
                            if ($this->input->post('simulate') && $_row_simulate >= 100) {
                                break;
                            }
                        }
                        unlink($newFilePath);
                    }
                } else {
                    set_alert('warning', _l('import_upload_failed'));
                }
            }
        }
        if (count($simulate_data) > 0) {
            $data['simulate'] = $simulate_data;
        }
        if (isset($import_result)) {
            set_alert('success', _l('import_total_imported', $total_imported));
        }
        $data['groups']         = $this->clients_model->get_groups();
        $data['not_importable'] = $this->not_importable_clients_fields;
        $data['title']          = 'Import';
        $this->load->view('admin/clients/import', $data);
    }
    public function groups()
    {
        if (!is_admin()) {
            access_denied('Customer Groups');
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('customers_groups');
        }
        $data['title'] = _l('customer_groups');
        $this->load->view('admin/clients/groups_manage', $data);
    }
    public function group()
    {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            if ($data['id'] == '') {
                $success = $this->clients_model->add_group($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfuly', _l('customer_group'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            } else {
                $success = $this->clients_model->edit_group($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfuly', _l('customer_group'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            }
        }
    }
    public function delete_group($id)
    {
        if (!is_admin()) {
            access_denied('Delete Customer Group');
        }
        if (!$id) {
            redirect(admin_url('clients/groups'));
        }
        $response = $this->clients_model->delete_group($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('customer_group')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('customer_group_lowercase')));
        }
        redirect(admin_url('clients/groups'));
    }

    public function bulk_action()
    {
        do_action('before_do_bulk_action_for_customers');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids    = $this->input->post('ids');
            $groups = $this->input->post('groups');

            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_delete')) {
                        if ($this->clients_model->delete($id)) {
                            $total_deleted++;
                        }
                    } else {

                        if (!is_array($groups)) {
                            $groups = false;
                        }
                        $this->clients_model->handle_update_groups($id, $groups);
                    }
                }
            }
        }

        if ($this->input->post('mass_delete')) {
            set_alert('success', _l('total_clients_deleted', $total_deleted));
        }
    }
}
