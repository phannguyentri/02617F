<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workforms extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('workform_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {

        $this->list_workforms();
    }
    /* List all tasks */
    public function list_workforms()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        // var_dump("expression");die();
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('workforms');
        }
        $data['roles']=$this->workform_model->get_roles();
        // var_dump($data['roles']);die();
        $data['title'] = _l('Hình thức làm việc');
        $this->load->view('admin/workforms/manage', $data);
    }
    /* Get task data in a right pane */
    public function delete_workform($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }
        $success    = $this->workform_model->delete_workform($id);
        $alert_type = 'warning';
        $message    = _l('Không thể xóa dữ liệu');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('Xóa dữ liệu thành công');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
     public function add_workform()
    {
        if ($this->input->post()) {
            $message = '';
                $id = $this->workform_model->add_workform($this->input->post(NULL, FALSE));
                if ($id) {
                    $success = true;
                    $message = _l('added_successfuly', _l('als_workforms'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update_workform($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->workform_model->update_workform($this->input->post(), $id);
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
                $success = $this->workform_model->add_workform($this->input->post());
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



    public function get_row_workform($id)
    {
        echo json_encode($this->workform_model->get_row_workform($id));
    }


}
