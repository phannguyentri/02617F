<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Calllogs extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
//        $this->load->model('call_logs_model');
        $this->load->model('call_logs_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index($id = '')
    {
        $this->list_tasks($id);
    }
    /* List all tasks */
    public function list_tasks($id = '')
    {
        // if passed from url
        $_custom_view = '';
        if ($this->input->get('custom_view')) {
            $_custom_view = $this->input->get('custom_view');
        }

//        if ($this->input->is_ajax_request()) {
//            if ($this->input->get('kanban')) {
//                $data['statuses'] = $this->tasks_model->get_statuses();
//                echo $this->load->view('admin/tasks/kan_ban', $data, true);
//                die();
//            } else {
//                $this->perfex_base->get_table_data('tasks');
//
//            }
//        }
        $data['phone'] = '';
        if (is_numeric($id)) {
            $data['phone'] = $id;
        }
//
//        if ($this->input->get('kanban')) {
//            $this->switch_kanban(0, true);
//        }

        $data['switch_kanban'] = false;
        $data['bodyclass']     = 'tasks_page';
//        if ($this->session->has_userdata('tasks_kanban_view') && $this->session->userdata('tasks_kanban_view') == 'true') {
//            $data['switch_kanban'] = true;
//            $data['bodyclass']     = 'tasks_page kan-ban-body';
//        }

        $data['custom_view'] = $_custom_view;
        $data['title']       = _l('tasks');
        $this->load->view('admin/tasks/call_log_manage', $data);
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
    public function delete_call_logs($id)
    {
        if (!$id) {
            die('No reminder found');
        }
        $success    = $this->call_logs_model->delete_call_logs($id);
        $alert_type = 'warning';
        $message    = _l('call_logs_not_delete');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('call_logs_delete');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
































    /* Add new task or update existing */
    public function update_or_add_call_logs($id = '')
    {

        $idlead=$this->input->get('idlead');
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->call_logs_model->update($this->input->post(), $id);
                if ($success) {
                    $alert_type = 'success';
                    $message    = 'Cập nhật dữ liệu thành công';
                };
            }
            redirect("admin/leads/");
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));
        }
        else
        {
            if ($this->input->post()) {
                $success = $this->call_logs_model->add($this->input->post(),$idlead);
                if ($success) {
                    $alert_type = 'success';
                    $message    = 'Thêm dữ liệu thành công';
                }
            }
            //        $this->load->view('admin/tasks/detailed_overview', $data);
            redirect("admin/leads/");
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));
        }
        die;


//        $data=array('name'=>$this->input->post('name'),'date_width'=>$this->input->post('date_width'),'address'=>$this->input->post('address'),
//            'document'=>$this->input->post('document'));
//
//        $success    = $this->call_logs_model->update($data, $id);
//        var_dump($success);die();
//        $alert_type = 'warning';
//        $message    = _l('call_logs_not_delete');
//        if ($success) {
//            $alert_type = 'success';
//            $message    = _l('call_logs_delete');
//        }
//        echo json_encode(array(
//            'alert_type' => $alert_type,
//            'message' => $message
//        ));


//        var_dump($data);die();
//        $this->load->view('admin/tasks/task', $data);
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
    public function copy()
    {
        if (has_permission('tasks', '', 'create')) {
            $new_task_id = $this->tasks_model->copy($this->input->post());
            $response    = array(
                'new_task_id' => '',
                'alert_type' => 'warning',
                'message' => _l('failed_to_copy_task'),
                'success' => false
            );
            if ($new_task_id) {
                $response['message']     = _l('task_copied_successfuly');
                $response['new_task_id'] = $new_task_id;
                $response['success']     = true;
                $response['alert_type']  = 'success';
            }
            echo json_encode($response);
        }
    }

}
