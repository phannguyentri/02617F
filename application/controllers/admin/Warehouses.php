<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehouses extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('warehouse_model');
        $this->load->model('kind_of_warehouse_model');
        $this->load->model('category_model');
    }
    /* Open also all taks if user access this /tasks url */
    public function index()
    {

        $this->list_warehouses();
    }
    public function get_all_products($category_id) {
        if (!is_admin()) {
            access_denied('contracts');
        }
        if ($this->input->is_ajax_request()) {
            exit(json_encode($this->warehouse_model->get_products($category_id)));
        }
    } 
    /* List all tasks */
    public function list_warehouses()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('warehouses');
        }
        $data['roles']=$this->warehouse_model->get_roles();
        $data['title'] = _l('Kho hàng');
        $data['kind_of_warehouse'] = $this->kind_of_warehouse_model->get_array_list();
        $data['categories'] = [];
        $this->category_model->get_by_id(0,$data['categories']);

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
    // public function modal_detail($id) {
    //     $warehouse = $this->warehouse_model->get_full($id);

    //     if( $id != '' && $warehouse) {
    //         $result = new stdClass();
    //         $data['warehouse'] = $warehouse;
    //         $result->body = $this->load->view('admin/warehouses/modal_detail', $data, TRUE);
    //         $result->header = _l('warehouse_info') . " " . $warehouse->warehouse;
    //         exit(json_encode($result));
    //     }
    // }
    public function modal_detail($id) {
        if($this->input->is_ajax_request() && !$this->input->get('get')) {
            $this->perfex_base->get_table_data('warehouse_detail');
        }
        $warehouse = $this->warehouse_model->get_full($id);

        if( $id != '' && $warehouse) {
            $result = new stdClass();
            $data['warehouse'] = $warehouse;
            $data['categories'] = [];
            $this->category_model->get_by_id(0,$data['categories']);
            $data['products_in_warehouse'] = $this->warehouse_model->get_products_in_warehouse($id);
            $product_category = array();
            $product_outof_date = 0;
            $product_low_quantity = 0;
            
            foreach($data['products_in_warehouse'] as $key=>$value) {
                if(!in_array($value['category_id'], $product_category)) {
                    array_push($product_category, $value['category_id']);
                }
                
            }
            $data['product_category'] = $product_category;
            $data['product_outof_date'] = $product_outof_date;
            $data['product_low_quantity'] = $product_low_quantity;

            $result->body = $this->load->view('admin/warehouses/modal_detail', $data, TRUE);
            $result->header = _l('warehouse_info') . " " . $warehouse->warehouse;
            exit(json_encode($result));
        }
    }
}
