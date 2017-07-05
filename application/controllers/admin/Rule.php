<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rule extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('rule_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {
        $this->list_rule();
    }
    /* List all tasks */
    public function list_rule()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }

        if ($this->input->is_ajax_request()) {
//            $this->perfex_base->get_table_data('department');
            $this->perfex_base->get_table_data('rule');

        }
        $data['rule']=$this->rule_model->get_rule();
//        $data['rule']=$this->get_rule_lever();
        $data['title'] = _l('rule');
        $this->load->view('admin/settings/includes/rule', $data);
    }
    public function delete_rule($id)
    {
        if (!$id) {
            die('No reminder found');
        }
        $success    = $this->rule_model->delete_rule($id);
        $alert_type = 'warning';
        $message    = _l('rule_not_delete');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('rule_delete');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
    public function get_level($idcha=0,$chuoi="",$name="",$colum="")
    {
        $query=$this->rule_model->get_rule_pa($idcha);
        if($query!=array()){
            foreach($query as $rom)
            {
                if($rom['pater_id']==$idcha){
                    $name=$chuoi.$rom[$colum].'/';
                    $name=$name.$this->get_level($rom['id'],$chuoi.'--',$name,$colum);
                }
            }
            return $name;
        }
    }
    public function get_rule_lever()
    {

        $name=array();
        $name_array=trim($this->get_level(0,"",'','name'),'/');
        $id=str_replace('-','',trim($this->get_level(0,"",'','id'),'/'));
//        echo $name;
        $name_array=explode ('/',$name_array);
        $id=explode ('/',$id);
        return(array('id'=>$id,'name'=>$name));
//        var_dump($name);
//        echo $a;
    }












    /* Get task data in a right pane */
    public function get_call_logs_data()
    {
        $id = $this->input->post('id');
        $idlead = $this->input->post('idlead');
        $data=array();
        if($id!="")
        {
//        // Task main data
            $call=$this->call_logs_model->get_call_logs($id);
            $call_staff=$this->call_logs_model->get_call_logs_and_staff($id);
            $data['staff']=array();
            $data['assignees']=array();
            if (!$call) {
                header("HTTP/1.0 404 Not Found");
                echo 'Task not found';
                die();
            }
            if ($call!=array()) {
                $data['staff']=$call_staff;
            }
            $data['call_logs']           = $call;
            $data['id']             = $call->ID;
        }
        $call_assignees=$this->call_logs_model->get_all_assignees();
        if ($call_assignees!=array()) {
            $data['call_assignees']=$call_assignees;
        }
        $data['idlead']             = $idlead;
        $this->load->view('admin/tasks/view_call_logs_template', $data);
    }

    public function tasks_kanban_load_more()
    {
        $status = $this->input->get('status');
        $page   = $this->input->get('page');

        $where = array();
        if ($this->input->get('project_id')) {
            $where['rel_id']   = $this->input->get('project_id');
            $where['rel_type'] = 'project';
        }

        $tasks = $this->tasks_model->do_kanban_query($status, $this->input->get('search'), $page, false, $where);

        foreach ($tasks as $task) {
            $this->load->view('admin/tasks/_kan_ban_card', array(
                'task' => $task,
                'status' => $status
            ));
        }

    }
    public function update_order()
    {
        $this->tasks_model->update_order($this->input->post());
    }
    public function switch_kanban($set = 0, $manual = false)
    {
        if ($set == 1) {
            $set = 'false';
        } else {
            $set = 'true';
        }

        $this->session->set_userdata(array(
            'tasks_kanban_view' => $set
        ));
        if ($manual == false) {
            // clicked on VIEW KANBAN from projects area and will redirect again to the same view
            if (strpos($_SERVER['HTTP_REFERER'], 'project_id') !== FALSE) {
                redirect(admin_url('tasks'));
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
    public function update_task_description($id)
    {
        if (has_permission('tasks', '', 'edit')) {
            $this->db->where('id', $id);
            $this->db->update('tblstafftasks', array(
                'description' => $this->input->post('description')
            ));
        }
    }
    public function detailed_overview()
    {

        $overview = array();
        if (!has_permission('tasks', '', 'create')) {
            $staff_id = get_staff_user_id();
        } else if ($this->input->post('member')) {
            $staff_id = $this->input->post('member');
        } else {
            $staff_id = '';
        }
        $month  = ($this->input->post('month') ? $this->input->post('month') : '');
        $status = $this->input->post('status');

        $fetch_month_from = ($this->input->post('month_from') ? $this->input->post('month_from') : 'duedate');
        $year             = ($this->input->post('year') ? $this->input->post('year') : date('Y'));

        for ($m = 1; $m <= 12; $m++) {
            if ($month != '' && $month != $m) {
                continue;
            }
            $this->db->where('MONTH(' . $fetch_month_from . ')', $m);
            $this->db->where('YEAR(' . $fetch_month_from . ')', $year);

            if (is_numeric($staff_id)) {
                $this->db->where('(id IN (SELECT taskid FROM tblstafftaskassignees WHERE staffid=' . $staff_id . '))');
            }
            if ($status) {
                $this->db->where('status', $status);
            }
            $this->db->order_by($fetch_month_from, 'ASC');
            array_push($overview, $m);
            $overview[$m] = $this->db->get('tblstafftasks')->result_array();
        }

        unset($overview[0]);

        $overview = array(
            'staff_id' => $staff_id,
            'detailed' => $overview
        );

        $data['members']  = $this->staff_model->get();
        $data['overview'] = $overview['detailed'];
        $data['years']    = $this->tasks_model->get_distinct_tasks_years(($this->input->post('month_from') ? $this->input->post('month_from') : 'duedate'));
        $data['staff_id'] = $overview['staff_id'];
        $data['title']    = _l('detailed_overview');
        $this->load->view('admin/tasks/detailed_overview', $data);
    }
    public function init_relation_logs($rel_id, $rel_type)
    {
        if ($this->input->is_ajax_request()) {
        $this->perfex_base->get_table_data('call_logs_relations', array(
            'rel_id' => $rel_id,
            'rel_type' => $rel_type
        ));
        }
    }




    /* Add new task or update existing */
    public function update_add_rule($id="")
    {

//        $id=$this->input->get('id');
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->rule_model->update_rule($this->input->post(), $id);
                if ($success) {
                    $alert_type = 'success';
                    $message    = 'Cập nhật dữ liệu thành công';
                };
            }
//            redirect("admin/rule/");
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));
        }
        else
        {
            if ($this->input->post()) {
                $success = $this->rule_model->add_rule($this->input->post(),'');
                if ($success) {
                    $alert_type = 'success';
                    $message    = 'Thêm dữ liệu thành công';
                }
            }
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));
        }
        die;
    }
    public function get_row_rule($id)
    {
        echo json_encode($this->rule_model->get_row_rule($id));
    }



    /* Remove assignee / ajax */
    public function remove_call_assignee($id, $staff_id)
    {
        if (has_permission('tasks', '', 'edit') && has_permission('tasks', '', 'create')) {
            $success = $this->call_logs_model->remove_assignee($id, $staff_id);
            $message = '';
            if ($success) {
                $message = _l('task_assignee_removed');
            }
            echo json_encode(array(
                'success' => $success,
                'message' => $message
            ));
        }
    }


}
