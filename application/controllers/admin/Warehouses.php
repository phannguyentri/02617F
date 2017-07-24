<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehouses extends Admin_controller
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

        $this->list_warehouses();
    }
    /* List all tasks */
    public function list_warehouses()
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
        $data['title'] = _l('Danh mục sản phẩm');
        $this->load->view('admin/warehouses/manage', $data);
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
    public function detail($id) {
        $warehouse = $this->warehouse_model->get_full($id);

        if( $id != '' && $warehouse) {
            
            $data['title'] = _l('purchase_suggested_edit_heading');
            $data['warehouse'] = $warehouse;
            $this->load->view('admin/warehouses/detail', $data);
        }
        else {
            redirect(admin_url('warehouses'));
        }
    }
    public function modal_detail($id) {
        $warehouse = $this->warehouse_model->get_full($id);

        if( $id != '' && $warehouse) {
            $result = new stdClass();
            $data['warehouse'] = $warehouse;
            $result->body = $this->load->view('admin/warehouses/modal_detail', $data, TRUE);
            $result->header = _l('warehouse_info') . " " . $warehouse->warehouse;
            exit(json_encode($result));
        }
    }
}
