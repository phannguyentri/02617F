<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('warehouse_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        // var_dump("expression");die();
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('warehouses');
        }
        $data['roles']=$this->warehouse_model->get_roles();
        // var_dump($data['roles']);die();
        $data['title'] = _l('Danh sách sản phẩm');
        $this->load->view('admin/products/manage', $data);
    }
    
    /* Get task data in a right pane */
    public function delete_warehouse($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }
        $success    = $this->warehouse_model->delete_warehouse($id);
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
     public function add_warehouse()
    {
        if ($this->input->post()) {
            $message = '';
                $id = $this->warehouse_model->add_warehouse($this->input->post(NULL, FALSE));
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
    public function update_warehouse($id="")
    {
        if($id!=""){
            $message    = '';
            $alert_type = 'warning';
            if ($this->input->post()) {
                $success = $this->warehouse_model->update_warehouse($this->input->post(), $id);
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
                $success = $this->warehouse_model->add_warehouse($this->input->post());
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



    public function get_row_warehouse($id)
    {
        echo json_encode($this->warehouse_model->get_row_warehouse($id));
    }


}
