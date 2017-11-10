<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Email_marketing extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('email_marketing_model');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('client_email');
        }

        if ($this->input->post()) {

            $this->email->initialize();
            $data = $this->input->post();
            $message = $this->input->post('message', FALSE);
            $name_file = $data['file_send'];
            $to_email = $data['email'];
            $to_email_cc = $data['email_to_cc'];
            $to_email_bc = $data['email_to_bc'];
            $subject = $data['subject'];
            $sender_email = $this->input->post('user_email');
            $user_password = $this->input->post('user_password');

            $template = $data['view_template'];
            $count_send = 0;
            $username = get_staff_full_name();

            if ($name_file) {
                $name_file = explode(',', $name_file);
                if ($name_file != array()) {
                    foreach ($name_file as $file) {
                        if ($file != "") {
                            $this->email->attach(get_upload_path_by_type('email') . $file);
                        }
                    }
                }
            }
            if($data['type_send']==1)
            {

                if ($data['type_email']) {
                    if ($to_email_cc != "" || $to_email_bc != "" || $to_email != "") {
                        $id_log = $this->email_marketing_model->log_sent_email($subject, $message, $data['file_send'], $template, $data['campaign']);
                    }
                    if ($to_email != "") {
                        $to_email = explode(',', $to_email);
                        foreach ($to_email as $rom) {

                            $config['smtp_user'] = $sender_email;
                            $config['smtp_pass'] = $user_password;
                            $this->email->initialize($config);


                            $this->email->set_newline("\r\n");
                            $this->email->from($sender_email, $username);
                            $this->email->set_mailtype("html");
                            $this->email->to($rom);
                            $message_sent = $this->get_content($rom, $message);
                            $this->email->subject($subject);
                            $id_email = $this->log_sent_email($rom, 0, $id_log);
                            $this->email->message($message_sent . "<img border='0' src='" . admin_url() . "images_code/images_code?id=" . $id_email . "' width='1' height='1'>");
                            sleep(2);
                            if ($this->email->send()) {
                                $count_send++;
                            }
                            $data['message_display'] = $this->email->print_debugger();
                        }
                    }
                    if ($to_email_cc != "") {
                        $to_email_cc = explode(',', $to_email_cc);
                        foreach ($to_email_cc as $rom_cc) {
                            $config['smtp_user'] = $sender_email;
                            $config['smtp_pass'] = $user_password;
                            $this->email->initialize($config);
                            $this->email->set_newline("\r\n");
                            $this->email->from($sender_email, $username);
                            $this->email->set_mailtype("html");
                            $this->email->cc($rom_cc);
                            $message_sent = $this->get_content($rom_cc, $message);
                            $this->email->subject($subject);
                            $id_email = $this->log_sent_email($rom_cc, 1, $id_log);
                            $this->email->message($message_sent . "<img border='0' src='" . admin_url() . "images_code/images_code?id=" . $id_email . "' width='1' height='1'>");
                            if ($this->email->send()) {
                                $count_send++;
                            }
                            $data['message_display'] = $this->email->print_debugger();
                        }
                    }
                    if ($to_email_bc != "") {
                        $to_email_bc = explode(',', $to_email_bc);
                        foreach ($to_email_bc as $rom_bc) {
                            $config['smtp_user'] = $sender_email;
                            $config['smtp_pass'] = $user_password;
                            $this->email->initialize($config);
                            $this->email->set_newline("\r\n");
                            $this->email->from($sender_email, $username);
                            $this->email->set_mailtype("html");
                            $this->email->bcc($rom_bc);
                            $message_sent = $this->get_content($rom_bc, $message);
                            $this->email->subject($subject);
                            $id_email = $this->log_sent_email($rom_bc, 2, $id_log);
                            $this->email->message($message_sent . "<img border='0' src='" . admin_url() . "images_code/images_code?id=" . $id_email . "' width='1' height='1'>");
                            if ($this->email->send()) {
                                $count_send++;
                            }
                            $data['message_display'] = $this->email->print_debugger();
                        }
                    }

                    if ($count_send > 0) {
                        $data['message_display'] = 'Message has been sent';
                    } else {
                        $this->email_marketing_model->delete_log_email($id_log);
                        $data['message_display'] = 'Message could not be sent!. <br>' . 'Mailer Error: ' . $this->email->print_debugger();
                    }
                } else {
                    if ($to_email != "" || $to_email_cc != "" || $to_email_bc != "") {
                        $count_send = 0;
                        $id_log = $this->email_marketing_model->log_sent_email($subject, $message, $data['file_send'], $template);
                        $to_email = explode(',', $to_email);
                        foreach ($to_email as $rom) {
                            $config['smtp_user'] = $sender_email;
                            $config['smtp_pass'] = $user_password;
                            $this->email->initialize($config);
                            $this->email->set_newline("\r\n");
                            $this->email->from($sender_email, $username);
                            $this->email->set_mailtype("html");
                            $this->email->to($rom);
                            $message_sent = $this->get_content($rom, $message);
                            $this->email->subject($subject);
                            $id_email = $this->log_sent_email($rom, 0, $id_log);
                            $this->email->message($message_sent . "<img border='0' src='" . admin_url() . "email_marketing/images_code?id=" . $id_email . "' width='1' height='1'>");
                            if ($this->email->send()) {
                                $count_send++;
                            }
                        }
                        $to_email_cc = explode(',', $to_email_cc);
                        foreach ($to_email_cc as $rom_cc) {
                            $config['smtp_user'] = $sender_email;
                            $config['smtp_pass'] = $user_password;
                            $this->email->initialize($config);
                            $this->email->set_newline("\r\n");
                            $this->email->from($sender_email, $username);
                            $this->email->set_mailtype("html");
                            $this->email->to($rom_cc);
                            $message_sent = $this->get_content($rom_cc, $message);
                            $this->email->subject($subject);
                            $id_email = $this->log_sent_email($rom_cc, 1, $id_log);
                            $this->email->message($message_sent . "<img border='0' src='" . admin_url() . "email_marketing/images_code?id=" . $id_email . "' width='1' height='1'>");
                            if ($this->email->send()) {
                                $count_send++;
                            }

                        }
                        $to_email_bc = explode(',', $to_email_bc);
                        foreach ($to_email_bc as $rom_bc) {
                            $config['smtp_user'] = $sender_email;
                            $config['smtp_pass'] = $user_password;
                            $this->email->initialize($config);
                            $this->email->set_newline("\r\n");
                            $this->email->from($sender_email, $username);
                            $this->email->set_mailtype("html");
                            $this->email->to($rom_bc);
                            $message_sent = $this->get_content($rom_bc, $message);
                            $this->email->subject($subject);
                            $id_email = $this->log_sent_email($rom_bc, 2, $id_log);
                            $this->email->message($message_sent . "<img border='0' src='" . admin_url() . "email_marketing/images_code?id=" . $id_email . "' width='1' height='1'>");
                            if ($this->email->send()) {
                                $count_send++;
                            }
                        }

                    }
                    if ($count_send > 0) {
                        $data['message_display'] = 'Message has been sent';
                    } else {
                        $this->email_marketing_model->delete_log_email($id_log);
                        $data['message_display'] = 'Message could not be sent!. <br>' . 'Mailer Error: ' . $this->email->print_debugger();
                    }

                }
            }
            else
            {
                $date_send=$data['date_send'];
                $array_add=array(
                        'subject'=>$subject,
                        'message'=>$message,
                        'email_to'=>$to_email,
                        'email_cc'=>$to_email_cc,
                        'email_bcc'=>$to_email_bc,
                        'file'=>$name_file,
                        'date_create'=>date('Y-m-d'),
                        'date_send'=>$date_send,
                        'create_by'=>get_staff_user_id()
                    );
                if($data['type_email'])
                {
                    $data['template']=$template;
                }

                if($to_email||$to_email_cc||$to_email_bc)
                {
                    $this->db->insert('email_send_later',$array_add);
                    if($this->db->insert_id())
                    {
                        $data['message_display'] = 'Gửi sau đã được lưu';
                    }
                    else
                    {
                        $data['message_display'] = 'Lưu email gửi sau đã bị lỗi';
                    }
                }
                else
                {
                    $data['message_display'] = 'Lưu email gửi sau đã bị lỗi';
                }

            }
        }

        $field=array('code','title','company','short_name','phonenumber',
            'mobilephone_number','address_room_number','address_building','address_home_number',
            'address','address_town','country','address_area','city','state','address_ward','fax',
            'email','id_card','vat','birthday','user_referrer','groups_in','source_approach',
            'default_currency','debt','shipping_area','shipping_country','shipping_area',
            'shipping_city','shipping_state','shipping_ward','shipping_room_number',
            'shipping_building','shipping_home_number','shipping_street','shipping_town',
            'shipping_zip',

        );
        $field2=array(
            'type_of_organization','bussiness_registration_number','legal_representative','website',
            'business','cooperative_day',
        );
        $field_staff=array(
            'staff_code','fullname','email','phonenumber',

        );
        $data['field']=$field;
        $data['field2']=$field2;
        $data['fieldstaff']=$field_staff;
        $data['email_plate'] = $this->email_marketing_model->get_email_templete();
        $data['title'] = _l('Email marketing');
        $this->load->view('admin/email_marketing/managa', $data);
    }





    public function get_email_to_excel()
    {
//        if ($this->input->post()) {
            $data_customer=array();
            if (isset($_FILES['file_excel']['name']) && $_FILES['file_excel']['name'] != '') {
            // Get the temp file path
                $tmpFilePath = $_FILES['file_excel']['tmp_name'];

                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Setup our new file path
                    $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
                    $type = $_FILES["file_excel"]["type"];
                    $newFilePath = TEMP_FOLDER . $_FILES['file_excel']['name'];
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
                                $objPHPExcel =           $objReader->load($newFilePath);
                                $allSheetName       = $objPHPExcel->getSheetNames();
                                $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0);
                                $highestRow         = $objWorksheet->getHighestRow();
                                $highestColumn      = $objWorksheet->getHighestColumn();

                                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                                for ($row = 1; $row <= $highestRow; ++$row) {
                                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                        $value                     = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                                        $rows[$row - 1][$col] = $value;
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
                        if ($this->input->post('simulate')) {
                            if (count($rows) > 500) {
                                set_alert('warning', 'Recommended splitting the CSV file into smaller files. Our recomendation is 500 row, your CSV file has ' . count($rows));
                            }
                        }

                        if (get_option('company_is_required') == 1) {
                            array_push($required, 'company');
                        }
                        foreach($rows as $row) {
                            if(!empty($row[0]))
                            {
                                $data_customer[] = array(
                                    'email' => $row[0],
                                    'name_email' => $row[1]
                                );
                            }
                        }
                    }
                }
            }
            echo json_encode($data_customer);die();
//        }
    }
    public function log_sent_email($email,$type,$id_log)
    {
        $this->db->insert('tblemail_send',array('email'=>$email,'type'=>$type,'id_log'=>$id_log));
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('Send email [ID:' . $insert_id);
            return $insert_id;
        }
        return false;
    }

    public function tring_field()
    {
        $string=$this->input->post('s_tring');
        if($this->input->post('name_remove'))
        {
            $name_remove=$this->input->post('name_remove');
            $string =str_replace($name_remove,'',$string);
        }
        $string =str_replace(',,',',',$string);
        echo $string=trim($string,',');
    }
    public function get_email($id="")
    {
        $result=$this->email_marketing_model->get_email_templete($id);
        echo json_encode($result);
    }

    public function template_email($id="")
    {
        if($this->input->post())
        {
            if($id=="")
            {
                $data=$this->input->post();
                $data['content']=$this->input->post('content',false);
                $result= $this->email_marketing_model->add($data);
                if($result)
                {
                    set_alert('success', _l('thêm Mẫu email thành công'));
                    redirect(admin_url('email_marketing/template_emails'));
                }
                else
                {
                    set_alert('danger', _l('thêm Mẫu email không thành công'));
                    redirect(admin_url('email_marketing/template_email'));
                }
            }
            else
            {
                $data=$this->input->post();
                $data['content']=$this->input->post('content',false);
                $result= $this->email_marketing_model->update($id,$data);
                if($result)
                {
                    set_alert('success', _l('Cập nhật Mẫu email thành công'));
                }
                redirect(admin_url('email_marketing/template_email/' . $id));
            }
        }
        else
            {
                $data['id']=$id;
                $field=array('code','title','company','short_name','phonenumber',
                    'mobilephone_number','address_room_number','address_building','address_home_number',
                    'address','address_town','country','address_area','city','state','address_ward','fax',
                    'email','id_card','vat','birthday','user_referrer','groups_in','source_approach',
                    'default_currency','debt','shipping_area','shipping_country','shipping_area',
                    'shipping_city','shipping_state','shipping_ward','shipping_room_number',
                    'shipping_building','shipping_home_number','shipping_street','shipping_town',
                    'shipping_zip',

                );
                $field2=array(
                    'type_of_organization','bussiness_registration_number','legal_representative','website',
                    'business','cooperative_day',
                );
                $field_staff=array(
                    'staff_code','fullname','email','phonenumber',

                );
                $data['field']=$field;
                $data['field2']=$field2;
                $data['fieldstaff']=$field_staff;
                if($id=="")
                {
                    $data['title']="Thêm Mẫu email";
                    $this->load->view('admin/email_marketing/get_template',$data);
                }
                else
                {
                    $data['template']=$this->email_marketing_model->get_email_templete($id);
                    $data['title']="Mẫu email";
                    $this->load->view('admin/email_marketing/get_template',$data);
                }
        }

    }
    public function template_emails()
    {
        $data['template_email']=$this->email_marketing_model->get_email_templete();
        $data['title']="Mẫu email";
        $this->load->view('admin/email_marketing/template.php',$data);
    }

    public function get_content($id,$content="")
    {
        $this->db->select('tblclients.*,tblcountries.short_name as country,
                            tblarea.name as address_area,province.name as city,
                            district.name as state,ward.name as address_ward,
                            tblleadssources.name as source_approach,
                            tblcurrencies.name as default_currency
        ');
        $this->db->where('email',$id);
        $this->db->join('tblcountries','tblcountries.country_id=tblclients.country','left');
        $this->db->join('tblarea','tblarea.id=tblclients.address_area','left');
        $this->db->join('province','province.provinceid=tblclients.city','left');
        $this->db->join('district','district.districtid=tblclients.state','left');
        $this->db->join('ward','ward.wardid=tblclients.address_ward','left');
        $this->db->join('tblleadssources','tblleadssources.id=tblclients.source_approach','left');
        $this->db->join('tblcurrencies','tblcurrencies.id=tblclients.default_currency','left');
        $client=$this->db->get('tblclients')->row();
        if($client)
        {
            if($client->user_referrer)
            {
                $client->user_referrer=get_table_where('tblclients',array('userid'=>$client->userid))[0]['company'];
            }
            if($client->shipping_area)
            {
                $client->shipping_area=get_table_where('tblarea',array('id'=>$client->shipping_area))[0]['name'];
            }
            if($client->shipping_country)
            {
                $client->shipping_country=get_table_where('tblcountries',array('country_id'=>$client->shipping_country))[0]['short_name'];
            }
            if($client->shipping_city)
            {
                $client->shipping_city=get_table_where('province',array('provinceid'=>$client->shipping_city))[0]['name'];
            }
            if($client->shipping_state)
            {
                $client->shipping_state=get_table_where('district',array('districtid'=>$client->shipping_state))[0]['name'];
            }
            if($client->shipping_ward)
            {
                $client->shipping_ward=get_table_where('ward',array('wardid'=>$client->shipping_ward))[0]['name'];
            }
        }


        $field=array('code','title','company','short_name','phonenumber',
            'mobilephone_number','address_room_number','address_building','address_home_number',
            'address','address_town','country','address_area','city','state','address_ward','fax',
            'email','id_card','vat','birthday','user_referrer','groups_in','source_approach',
            'default_currency','debt','shipping_area','shipping_country','shipping_area',
            'shipping_city','shipping_state','shipping_ward','shipping_room_number',
            'shipping_building','shipping_home_number','shipping_street','shipping_town',
            'shipping_zip',

        );
        $field2=array(
            'type_of_organization','bussiness_registration_number','legal_representative','website',
            'business','cooperative_day',
        );
        $field_staff=array(
            'staff_code','fullname','email','phonenumbser',

        );
        foreach($field as $rom)
        {
            $content=preg_replace('"{tblclients.'.$rom.'}"',$client->$rom,$content);
        }
        foreach($field2 as $rom2)
        {
            $content=preg_replace('"{tblclients.'.$rom2.'}"',$client->$rom2,$content);
        }
        foreach($field_staff as $rom_s)
        {
            $content=preg_replace('"{tblstaff.'.$rom_s.'}"',$client->$rom_s,$content);
        }
        return $content;

    }
    public function view_content()
    {
        $email=$this->input->post('email',false);
        $content=$this->input->post('content',false);
        $content_send=$this->get_content($email,$content);
        echo json_encode(array('content'=>$content_send));
    }
    public function get_email_client()
    {
       $array_id=$this->input->post('listid');
       $row_id= $this->email_marketing_model->get_array_email($array_id);
       echo json_encode($row_id);

    }
    public function delete_email_template($id)
    {
       $result= $this->email_marketing_model->delete_email_template($id);
        if($result)
        {
            set_alert('success', _l('Xóa Mẫu email thành công'));
            redirect(admin_url('email_marketing/template_emails'));
        }
        else
        {
            set_alert('danger', _l('Xóa Mẫu email không thành công'));
            redirect(admin_url('email_marketing/template_emails'));
        }

    }
    public function been_send_email()
    {
        
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('log_email');
        }
        $data['log_email']  = $this->email_marketing_model->get_log_email();
        $data['count_group_email']=count(get_table_where('tbllog_email_send'));
        $data['count_email']=count(get_table_where('tblemail_send'));
        $data['count_email_view']=count(get_table_where('tblemail_send',array('view >'=>0)));
        // $data['campaign']=get_table_where('tblcampaign');
        $data['title']      ="Lịch sử gửi email";
        $this->load->view('admin/email_marketing/been_send_to',$data);

    }

    public function upload_file()
    {
        $path = get_upload_path_by_type('email');
        if (isset($_FILES['file']['name'])) {
            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Setup our new file path
                if (!file_exists($path)) {
                    mkdir($path);
                    fopen($path . 'index.html', 'w');
                }
                $filename    = unique_filename($path, $_FILES['file']['name']);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $attachment = array();
                    $attachment[]= array(
                        'file_name'=>$filename,
                        'filetype'=>$_FILES["file"]["type"],
                    );

                }
                echo $filename;

            }
        }
    }
    public function load_log_id()
    {
        $id=$this->input->post('id');
        $this->db->where('id',$id);
        $result=$this->db->get('tbllog_email_send')->row();
        $this->db->where('id_log',$id);
        $item=$this->db->get('tblemail_send')->result_array();
        $result->item=$item;
        echo json_encode($result);
    }
    public function init_email_marketing($id_log,$type=0)
    {
        $this->perfex_base->get_table_data('log_send_email_to',array('id_log'=>$id_log,'type'=>$type));
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('log_send_email_to',array('id_log'=>$id_log,'type'=>$type));
        }
    }

    public function images_code()
    {
        $id=$this->input->get('id');
        $this->email_marketing_model->update_status($id);
        header("Content-Type: image/png");
        $im = @imagecreate(110, 20)
        or die("Cannot Initialize new GD image stream");
        $background_color = imagecolorallocate($im, 0, 0, 0);
        $text_color = imagecolorallocate($im, 233, 14, 91);
        imagestring($im, 1, 5, 5,  "A Simple Text String", $text_color);
        imagepng($im);
        imagedestroy($im);
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Disposition: attachment; filename="photos_icon.png"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $im);
        readfile($im);
        exit;
    }
   public function set_status()
   {
       $this->db->select('tblemail_send.*,tbllog_email_send.subject as subject');
       $this->db->where('read',1);
       $this->db->join('tbllog_email_send','tbllog_email_send.id=tblemail_send.id_log');
       $data=$this->db->get('tblemail_send')->result_array();
       $this->db->update('tblemail_send',array('read'=>0));
       echo json_encode($data);
   }
}
?>