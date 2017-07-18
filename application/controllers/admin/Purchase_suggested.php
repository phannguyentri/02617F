<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_suggested extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_suggested_model');
        $this->load->model('invoice_items_model');
    }
    public function index() {

        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('purchase_suggested');
        }
        $data['title'] = _l('purchase_suggested');
        $this->load->view('admin/purchase_suggested/manage', $data);
    }
    public function detail($id='') {
        $data = array();
        $data['items'] = $this->invoice_items_model->get_full();
        if($this->input->post()) {
            if( $id == '' ) {
                $data_post = $this->input->post();
                // var_dump($data_post);die();
                if(isset($data_post['items']) && count($data_post['items']) > 0) {
                    $data_post['create_by'] = get_staff_user_id();

                    $result_id = $this->purchase_suggested_model->add($data_post);
                    set_alert('success', _l('added_successfuly', _l('purchase_suggested')));
                    redirect(admin_url('purchase_suggested/detail/' . $result_id));
                }
            }
            else {
                
                $result = $this->purchase_suggested_model->edit($this->input->post(),$id);
                if($result)
                    set_alert('success', _l('updated_successfuly', _l('purchase_suggested')));
            }
        }
        if( $id == '' ) {
            $data['title'] = _l('purchase_suggested_add_heading');
        }
        else {
            $data['title'] = _l('purchase_suggested_edit_heading');
            $data['item'] = $this->purchase_suggested_model->get($id);
            // var_dump($data['item']);die();
            
        }
        
        $this->load->view('admin/purchase_suggested/detail', $data);
    }
    public function detail_pdf($id='') {
        if (!$id) {
            redirect(admin_url('purchase_suggested'));
        }
        $purchase_suggested        = $this->purchase_suggested_model->get($id);
        $purchase_suggested_name = $purchase_suggested->name;

        $pdf            = purchase_suggested_pdf($purchase_suggested);
        $type           = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($purchase_suggested_name)) . '.pdf', $type);
    }

    /* Delete purchase */
    public function delete($id)
    {
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        if (!$id) {
            redirect(admin_url('purchase_suggested'));
        }

        $success = $this->purchase_suggested_model->delete($id);

        if ($success) {
            set_alert('success', _l('deleted', _l('purchase_suggested')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('purchase_suggested')));
        }
        if (strpos($_SERVER['HTTP_REFERER'], 'list_invoices') !== false) {
            redirect(admin_url('purchase_suggested'));
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function update_status()
    {
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $status=$status+1;
        $data=array('status'=>$status);
        // date('Y-m-d H:i:s'),get_staff_user_id()
        $success=$this->purchase_suggested_model->update_status($id,$data);
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận đề xuất thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Không thể cập nhật dữ liệu')
            ));
        }
        die;
    }
}