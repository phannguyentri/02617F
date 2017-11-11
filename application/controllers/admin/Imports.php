<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Imports extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('imports_model');
        $this->load->model('invoice_items_model');
        $this->load->model('warehouse_model');
    }
    public function index() {
        // var_dump($this->imports_model->getImportByID(64));die();
        // var_dump($this->db->query("SHOW COLUMNS FROM tbloptions")->result_array());die();
    }

    public function imp_adjustment()
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('adjustments',array('rel_type'=>'adjustment'));
        }
        $data['title'] = _l('Điều chỉnh kho hàng');
        $this->load->view('admin/imports/adjustments/adjustment', $data);
    }

    public function adjustment_detail($id='')
    {
        if (!has_permission('import_items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('import_items');
            }
        }
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            if ($id == '') {
                if (!has_permission('import_items', '', 'create')) {
                    access_denied('import_items');
                }

                $data                 = $this->input->post();
                // var_dump($data);die();
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->imports_model->add($data);
                }

                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('adjustments')));
                    redirect(admin_url('imports/imp_adjustment'));
                }
            } else {

                if (!has_permission('import_items', '', 'edit')) {
                        access_denied('import_items');
                }
                $success = $this->imports_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfuly', _l('adjustments')));
                    redirect(admin_url('imports/imp_adjustment'));
                }
                else
                {
                    redirect(admin_url('imports/adjustment_detail/'.$id));
                }
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('adjustments'));

        } else {
            $data['item'] = $this->imports_model->getImportByID($id);
            $data['warehouse_id']=$data['item']->items[0]->warehouse_id;
            $data['warehouse_type']=$this->warehouse_model->getWarehouses($data['warehouse_id'])->kindof_warehouse;
            if (!$data['item']) {
                blank_page('Purchase Not Found');
            }
        }
        $data['items']= $this->invoice_items_model->get_full();

        $data['warehouse_types']= $this->imports_model->getWarehouseTypes();
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $data['title'] = $title;

        $this->load->model('quotes_model');
        $data['categories_a'] = $this->quotes_model->getCategory(1,NULL,388);
        $data['categories_b'] = $this->quotes_model->getCategory(1,NULL,446);


        $this->load->view('admin/imports/adjustments/detail', $data);
    }



    public function imp_internal()
    {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('adjustments',array('rel_type'=>'internal'));
        }
        $data['title'] = _l('Điều chỉnh kho hàng nội địa');
        $this->load->view('admin/imports/internals/internal', $data);
    }

    public function internal_detail($id='')
    {
        if (!has_permission('import_items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('import_items');
            }
        }
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            if ($id == '') {
                if (!has_permission('import_items', '', 'create')) {
                    access_denied('import_items');
                }

                $data                 = $this->input->post();
                if(isset($data['items']) && count($data['items']) > 0)
                {
                    $id = $this->imports_model->add($data);
                }

                if ($id) {
                    set_alert('success', _l('added_successfuly', _l('internals')));
                    redirect(admin_url('imports/imp_internal'));
                }
            } else {

                if (!has_permission('import_items', '', 'edit')) {
                        access_denied('import_items');
                }
                $success = $this->imports_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfuly', _l('internals')));
                    redirect(admin_url('imports/imp_internal'));
                }
                else
                {
                    redirect(admin_url('imports/internal_detail/'.$id));
                }
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('internals'));

        } else {
            $data['item'] = $this->imports_model->getImportByID($id);

            $data['warehouse_id']=$data['item']->items[0]->warehouse_id;
            $data['warehouse_type']=$this->warehouse_model->getWarehouses($data['warehouse_id'])->kindof_warehouse;
            if (!$data['item']) {
                blank_page('Purchase Not Found');
            }
        }
        $data['items']= $this->invoice_items_model->get_full();

        $data['warehouse_types']= $this->imports_model->getWarehouseTypes();
        $data['warehouses']= $this->warehouse_model->getWarehouses();
        $data['title'] = $title;
        $this->load->view('admin/imports/internals/detail', $data);
    }

    /* Get task data in a right pane */
    public function delete_import($id)
    {
        if (!$id) {
            die('Không tìm thấy mục nào');
        }

        $success    = $this->imports_model->delete_warehouses_adjustment($id);
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

    public function warehouses_overview() {
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('warehouses_products');
        }
        $data['title'] = _l('Tổng quan kho');
        $this->load->view('admin/imports/warehouse_products', $data);
    }

    public function update_status()
    {
        $id=$this->input->post('id');
        $status=$this->input->post('status');
        $staff_id=get_staff_user_id();
        $date=date('Y-m-d H:i:s');
        $data=array('status'=>$status);

        $inv=$this->imports_model->getImportByID($id);
        // var_dump($inv);die();
        if(is_admin() && $status==0)
        {
            $data['user_head_id']=$staff_id;
            $data['user_head_date']=$date;

            $data['user_admin_id']=$staff_id;
            $data['user_admin_date']=$date;

            $data['status']=2;
        }
        elseif(is_admin() && $status==1)
        {
            $data['status']=2;
            if($inv->user_head_id==NULL || $inv->user_head_id=='')
            {
                $data['user_head_id']=$staff_id;
                $data['user_head_date']=$date;
            }
            if($inv->user_admin_id==NULL || $inv->user_admin_id=='')
            {
                $data['user_admin_id']=$staff_id;
                $data['user_admin_date']=$date;
            }
        }
        elseif(is_head($inv->create_by))
        {
            $data['status']+=1;
            $data['user_head_id']=$staff_id;
            $data['user_head_date']=$date;
        }

        $success=fale;

        if(is_admin() || is_head($inv->create_by))
        {
            $success=$this->imports_model->update_status($id,$data);
        }
        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận phiếu thành công')
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

    public function detail_pdf($id)
    {
        if (!has_permission('import_items', '', 'view') && !has_permission('import_items', '', 'view_own')) {
            access_denied('import_items');
        }
        if (!$id) {
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $invoice        = $this->imports_model->getImportByID($id);
        $invoice_number = $invoice->prefix.$invoice->code;

        $pdf            = import_detail_pdf($invoice);
        $type           = 'D';
        if ($this->input->get('pdf') || $this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
    }

}
