<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('email_marketing_model');
        $this->load->model('sms_model');
    }

    public function index($id = "")
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('client_phone');
        }

        if ($this->input->post()) {
            if ($id == "") {

                $dataInsert = [
                    'staff_id'     => get_staff_user_id(),
                    'subject'      => $this->input->post('subject'),
                    'message'      => $this->input->post('message'),
                    'phone_number' => $this->input->post('phonenumber'),
                    'date_send'    => $this->input->post('date_send'),
                    'template_sms_id' => $this->input->post('view_template'),
                ];

                if ($this->sms_model->insertSendSms($dataInsert)) {
                    set_alert('success', _l('Gửi SMS thành công'));
                    redirect(admin_url('sms/been_send_sms'));
                }else{
                    set_alert('danger', _l('Gửi SMS không thành công'));
                    redirect(admin_url('sms'));
                }
            }else{
                $dataUpdate = [
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('message'),
                    'phone_number' => $this->input->post('phonenumber'),
                    'date_send'    => $this->input->post('date_send')
                ];
                if ($this->sms_model->updateSendSms($id, $dataUpdate)) {
                    set_alert('success', _l('Sửa SMS thành công'));
                    redirect(admin_url('sms'));
                }else{
                    set_alert('danger', _l('Sửa SMS không thành công'));
                    redirect(admin_url('sms'));
                }

            }
        }else{
            $data['id'] = $id;
            if ($id != "") {
                $data['sms'] = $this->sms_model->getSmsById($id);
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
        $data['email_plate'] = $this->sms_model->get_sms_templete();
        $data['title'] = _l('SMS marketing');
        $this->load->view('admin/sms/manage', $data);
    }

    public function template_sms()
    {
        $data['template_sms']=$this->sms_model->get_sms_templete();
        $data['title']="Mẫu SMS";
        $this->load->view('admin/sms/template',$data);
    }

    public function template_sms_detail($id="")
    {
        if($this->input->post())
        {
            if($id=="")
            {
                $data=$this->input->post();
                $data['content']=$this->input->post('content',false);
                $result= $this->sms_model->add($data);
                if($result)
                {
                    set_alert('success', _l('thêm Mẫu email thành công'));
                    redirect(admin_url('sms/template_sms'));
                }
                else
                {
                    set_alert('danger', _l('thêm Mẫu email không thành công'));
                    redirect(admin_url('sms/template_sms_detail'));
                }
            }
            else
            {
                $data=$this->input->post();
                $data['content']=$this->input->post('content',false);
                $result= $this->sms_model->update($id,$data);
                if($result)
                {
                    set_alert('success', _l('Cập nhật Mẫu email thành công'));
                }
                redirect(admin_url('sms/template_sms_detail/' . $id));
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
                    $data['title']="Thêm Mẫu SMS";
                    $this->load->view('admin/sms/get_template',$data);
                }
                else
                {
                    $data['template']=$this->sms_model->get_sms_templete($id);
                    $data['title']="Mẫu SMS";
                    $this->load->view('admin/sms/get_template',$data);
                }
        }

    }

    public function get_sms($id="")
    {
        $result=$this->sms_model->get_sms_templete($id);
        echo json_encode($result);
    }

    public function delete_sms_template($id)
    {
       $result= $this->sms_model->delete_sms_template($id);
        if($result)
        {
            set_alert('success', _l('Xóa Mẫu email thành công'));
            redirect(admin_url('sms/template_sms'));
        }
        else
        {
            set_alert('danger', _l('Xóa Mẫu email không thành công'));
            redirect(admin_url('sms/template_sms'));
        }

    }

    public function been_send_sms(){
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('sms');
        }

        $data['title']  = "SMS đã gửi";
        $this->load->view('admin/sms/been_send_sms', $data);
    }

    public function detail($id){
        $data['sms'] = $this->sms_model->getSmsByIdJoinTemplate($id);

        $this->load->view('admin/sms/detail', $data);
    }
}
