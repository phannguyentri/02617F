<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workplaces extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('workplace_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {

        $this->list_Workplaces();
    }
    /* List all tasks */
    public function list_Workplaces()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        // var_dump("expression");die();
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('workplaces');
        }
        $data['roles']=$this->workplace_model->get_roles();
        // var_dump($data['roles']);die();
        $data['title'] = _l('Nơi làm việc');
        $this->load->view('admin/workplaces/manage', $data);
    }
    /* Get task data in a right pane */
    public function delete_workplace($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }
        $success    = $this->workplace_model->delete_workplace($id);
        $alert_type = 'warning';
        $message    = _l('Không thể xóa nơi làm việc');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('Xóa nơi làm việc thành công');
        }
        echo json_encode(array(
            'alert_type' => $alert_type,
            'message' => $message
        ));

    }
     public function add_workplace()
    {
        if ($this->input->post()) {
            $message = '';
                $id = $this->workplace_model->add_workplace($this->input->post(NULL, FALSE));
                if ($id) {
                    $success = true;
                    $message = _l('added_successfuly', _l('als_workplaces'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update_workplace($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->workplace_model->update_workplace($this->input->post(), $id);
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
                $success = $this->workplace_model->add_workplace($this->input->post());
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



    public function get_row_workplace($id)
    {
        echo json_encode($this->workplace_model->get_row_workplace($id));
    }


}
