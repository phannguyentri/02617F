<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kind_of_warehouse extends Admin_controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('kind_of_warehouse_model');
    }
    public function index() {
        if (!is_admin()) {
            access_denied('contracts');
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('kind_of_warehouses');
        }
        $data['title'] = _l('Loại kho');
        $this->load->view('admin/kind_of_warehouse/manage', $data);
    }
    public function get_row($id) {
        $item = $this->kind_of_warehouse_model->get_row($id);
        if($item)
            exit(json_encode($item));
        else {
            echo false;
        }
    }
    public function add() {
        if (!is_admin()) {
            access_denied('contracts');
        }
        if($this->input->is_ajax_request() && $this->input->post()) {
            $data = $this->input->post();
            $this->db->insert('tbl_kindof_warehouse', $data);
            $success = true;
            $message = "Thêm thành công!";
        }

        exit(json_encode(array(
                'alert_type' => $success,
                'message' => $message
            )));
    }
    public function update($id) {
        if (!is_admin()) {
            access_denied('contracts');
        }
        $this->db->where('id', $id);
        $item = $this->db->get('tbl_kindof_warehouse')->row();
        if($item && $this->input->post()) {
            $this->db->where('id', $id);
            $data = $this->input->post();
            $this->db->update('tbl_kindof_warehouse', $data);
            $success = true;
            $message = "Cập nhật thành công!";
        }
        exit(json_encode(array(
                'alert_type' => $success,
                'message' => $message
            )));
    }
    public function delete($id) {
        if (!is_admin()) {
            access_denied('contracts');
        }
        if($this->input->is_ajax_request()) {
            $this->db->where('kindof_warehouse', $id);
            $items = $this->db->get('tblwarehouses')->result();

            if(count($items) == 0) {
                $this->db->where('id', $id);
                $this->db->delete('tbl_kindof_warehouse');

                $success = true;
                $message = "Cập nhật thành công!";
            }
            else
                $success = false;
        }
        exit(json_encode(array(
                'alert_type' => $success,
                'message' => $message
            )));
    }
}