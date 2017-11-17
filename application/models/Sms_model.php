<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('EMAIL_TEMPLATE_SEND', true);
class Sms_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }
    public function get_sms_templete($id="")
    {
        if($id==""){
            $this->db->where('status','0');
            return $this->db->get('tblsms_templates')->result_array();
        }
        else
        {
            $this->db->where('status','0');
            $this->db->where('id',$id);
            return $this->db->get('tblsms_templates')->row();
        }
    }
    public function delete_sms_template($id)
    {
        $this->db->where('id',$id);
        $this->db->update('tblsms_templates',array('status'=>1));
        if ($this->db->affected_rows() > 0) {
            logActivity('Delete sms template  ID: ' . $id . ' by: '.get_staff_user_id().'');
            return true;
        }
        return false;

    }
    public function insertSendSms($data){
        $this->db->insert('tbllog_sms_send', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Send SMS success ID: ' . $id . ' by: '.get_staff_user_id().'');
            return true;
        }

        return false;
    }

    public function log_sent_email($subject="",$message="",$name="",$template="",$campaign="")
    {
        $date=date('Y-m-d H:i:s');
        $data=array(
            'template'=>$template,
            'file'=>$name,
            'addedfrom'=>get_staff_user_id(),
            'date_send'=>$date,
            'status'=>'0',
            'subject'=>$subject,
            'message'=>$message,
            'campaign'=>$campaign,
        );
        $this->db->insert('tbllog_email_send',$data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('Send New Email [time:' . $date . ']');
            return $insert_id;
        }
        return false;
    }
    public function delete_log_email($id_log="")
    {
        if(is_numeric($id_log))
        {
            $this->db->where('id',$id_log);
            $this->db->delete('tbllog_email_send');
            if ($this->db->affected_rows() > 0) {
                $this->db->where('id_log',$id_log);
                $this->db->delete('tblemail_send');
                if ($this->db->affected_rows() > 0) {
                    return true;
                }
            }
            return false;
        }
    }
    public function update($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('tblsms_templates',$data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Update SMS template [ID:' . $id . ', Name:' . $data['name'] . ']');
            return true;
        }
        return false;
    }
    public function update_status($id)
    {
        $this->db->where('id',$id);
        $data=$this->db->get('tblemail_send')->row();
        $this->db->where('id',$id);
        $this->db->update('tblemail_send',array('view'=>$data->view+1,'read'=>1,'time'=>date('Y-m-d H:i:s')));
        return true;
    }
    public function add($data)
    {
        $this->db->insert('tblsms_templates',$data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New SMS template Created [ID:' . $insert_id . ', Name:' . $data['name'] . ']');
            return true;
        }
        return false;
    }
    public function get_array_email($array_id=array())
    {
        if($array_id!=array())
        {
            $this->db->where_in('userid',$array_id);
            return $this->db->get('tblclients')->result_array();
        }
        return array();
    }
    public function get_log_email()
    {
        $this->db->order_by('date_send','desc');
        return $this->db->get('tbllog_email_send')->result_array();
    }
    public function get_staff_email($id="")
    {
        if($id!="")
        {
            $this->db->where('staffid',$id);
            return $this->db->get('tblstaff')->row();
        }
        else
        {
            return $this->db->get('tblstaff')->result_array();
        }

    }

}
