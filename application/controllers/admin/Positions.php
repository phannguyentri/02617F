<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Positions extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('position_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {

        $this->list_position();
    }
    /* List all tasks */
    public function list_position()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        // var_dump("expression");die();
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('positions');
        }
        $data['roles']=$this->position_model->get_roles();
        // var_dump($data['roles']);die();
        $data['title'] = _l('Chức vụ');
        $this->load->view('admin/positions/positions', $data);
    }
    /* Get task data in a right pane */
    public function delete_position($id)
    {
        if (!$id) {
            die('No department found');
        }
        $success    = $this->position_model->delete_position($id);
        $alert_type = 'warning';
        $message    = _l('Không thể xóa chức vụ');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('Xóa chức vụ thành công');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
     public function add_position()
    {
        if ($this->input->post()) {
            $message = '';
                $id = $this->position_model->add_position($this->input->post(NULL, FALSE));
                if ($id) {
                    $success = true;
                    $message = _l('added_successfuly', _l('als_positions'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update_position($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->position_model->update_position($this->input->post(), $id);
                if ($success) {
                    $message    = 'Cập nhật dữ liệu thành công';
                };
            }
            echo json_encode(array(
                'success' => $success,
                'message' => $message
            ));
        }
        else
        {
            if ($this->input->post()) {
                $success = $this->position_model->add_position($this->input->post());
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



    public function get_row_position($id)
    {
        echo json_encode($this->position_model->get_row_position($id));
    }


}
