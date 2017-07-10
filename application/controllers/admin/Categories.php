<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Categories extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('category_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {

        $this->list_categorys();
    }
    /* List all tasks */
    public function list_categorys()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        // var_dump("expression");die();
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('categories');
        }
        $data['roles']=$this->category_model->get_roles();
        $data['categories'] = $this->category_model->get_all();
        // var_dump($data['roles']);die();
        $data['title'] = _l('Danh mục sản phẩm');
        $this->load->view('admin/categories/manage', $data);
    }
    /* Get task data in a right pane */
    public function delete_category($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }
        $success    = $this->category_model->delete_category($id);
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
     public function add_category()
    {
        if ($this->input->post()) {
            $message = '';
                $id = $this->category_model->add_category($this->input->post(NULL, FALSE));
                if ($id) {
                    $success = true;
                    $message = _l('added_successfuly', _l('als_categories'));
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message
                ));
            die;
        }
    }
    public function update_category($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->category_model->update_category($this->input->post(), $id);
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
                $success = $this->category_model->add_category($this->input->post());
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



    public function get_row_category($id)
    {
        echo json_encode($this->category_model->get_row_category($id));
    }


}
