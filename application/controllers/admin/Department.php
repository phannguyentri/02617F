<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Department extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('department_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {
        $this->list_department();
    }
    /* List all tasks */
    public function list_department()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }

        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('department');
        }
        $data['roles']=$this->department_model->get_roles();
        $data['title'] = _l('Department');
        $this->load->view('admin/settings/includes/department', $data);
    }
    /* Get task data in a right pane */
    public function delete_department($id)
    {
        if (!$id) {
            die('No department found');
        }
        $success    = $this->department_model->delete_department($id);
        $alert_type = 'warning';
        $message    = _l('department_not_delete');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('department_delete');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
    public function update_add_department($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->department_model->update_department($this->input->post(), $id);
                if ($success) {
                    $alert_type = 'success';
                    $message    = 'Cập nhật dữ liệu thành công';
                };
            }
            echo json_encode(array(
                'alert_type' => $alert_type,
                'message' => $message
            ));
        }
        else
        {
            if ($this->input->post()) {
                $success = $this->department_model->add_department($this->input->post());
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



    public function get_row_department($id)
    {
        echo json_encode($this->department_model->get_row_department($id));
    }


}
