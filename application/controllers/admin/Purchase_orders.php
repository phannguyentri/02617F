<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_orders extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_suggested_model');
        $this->load->model('invoice_items_model');
        $this->load->model('orders_model');
        $this->load->model('currencies_model');
        $this->load->model('warehouse_model');
        $this->load->model('contract_templates_model');
    }
    public function index() {

        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('purchase_orders');
        }
        $data['title'] = _l('orders_ticket');
        $this->load->view('admin/orders/manage', $data);
    }
    public function convert($id='') {
        $data = array();
        $data['title'] = _l('orders_ticket');
        $purchase_suggested = $this->purchase_suggested_model->get($id);
        if(!$purchase_suggested || $purchase_suggested->status != 2 || $this->orders_model->check_exists($purchase_suggested->id)) {
            redirect(admin_url() . 'purchase_orders');
        }
        $data['currencies'] = $this->currencies_model->get();
        $data['purchase_suggested'] = $purchase_suggested;
        $data['warehouses'] = $this->orders_model->get_warehouses();
        $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
        foreach($data['purchase_suggested']->items as $key=>$value) {
            $data['purchase_suggested']->items[$key]->warehouse_type = (object)$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id, true);
        }
        $data['product_list'] = $purchase_suggested->items;
        $data['suppliers'] = $this->orders_model->get_suppliers();
        if($this->input->post()) {
            $data = $this->input->post();
            
            $data['code'] = get_option('prefix_purchase_order') . $data['code'];
            $data['id_user_create'] = get_staff_user_id();

            $this->purchase_suggested_model->convert_to_order($id, $data);
            redirect(admin_url() . 'purchase_orders');
        }
        $this->load->view('admin/orders/convert', $data);
    }
    public function convert_to_contract($id='') {
        $data = array();
        $data['title'] = _l('convert_to_purchase_contract');
        $order = $this->orders_model->get($id);
        if(!$order || $order->user_head_id == 0) {
            redirect(admin_url() . 'purchase_orders');
        }
        $contract_merge_fields  = get_available_merge_fields();
        $_contract_merge_fields = array();
        foreach ($contract_merge_fields as $key => $val) {
            foreach ($val as $type => $f) {
                if ($type == 'contract') {
                    foreach ($f as $available) {
                        foreach ($available['available'] as $av) {
                            if ($av == 'contract') {
                                array_push($_contract_merge_fields, $f);
                                break;
                            }
                        }
                        break;
                    }
                } else if ($type == 'other') {
                    array_push($_contract_merge_fields, $f);
                } else if ($type == 'clients') {
                    array_push($_contract_merge_fields, $f);
                }
            }
        }
        $data['contract_merge_fields'] = $_contract_merge_fields;
        $data['order'] = $order;
        $data['default_template'] = $this->contract_templates_model->get_contract_template_by_id(2)->content;
        foreach($data['order']->products as $key=>$value) {
            $data['order']->products[$key]->warehouse_type = (object)$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id, true);
        }
        $data['currencies'] = $this->currencies_model->get();
        $data['product_list'] = $order->items;
        $data['suppliers'] = $this->orders_model->get_suppliers();

        if($this->input->post()) {
            $data = $this->input->post();
            $data['code'] = get_option('prefix_contract') . $data['code'];
            $data['id_user_create'] = get_staff_user_id();
            $data['id_supplier'] = $order->id_supplier;
            unset($data['items']);
            $data['template'] = $this->contract_templates_model->get_contract_template_by_id(2)->content;

            $result = $this->orders_model->convert_to_contact($id, $data);
            redirect(admin_url() . 'purchase_contracts');
        }
        $this->load->view('admin/orders/convert_to_contract', $data);
    }
    public function view($id='') {
        if(is_numeric($id)) {
            $order = $this->orders_model->get($id);
            if($order) {
                if($this->input->post()) {
                    $data = $this->input->post();

                    $this->orders_model->update($id, $data);
                    $order = $this->orders_model->get($id);
                }
                $data = array();
                $data['title'] = _l('orders_view_heading');
                $data['suppliers'] = $this->orders_model->get_suppliers();
                $data['warehouses'] = $this->orders_model->get_warehouses();
                $data['warehouse_types']= $this->warehouse_model->getWarehouseTypes();
                $data['currencies'] = $this->currencies_model->get();
                $data['products'] = $this->invoice_items_model->get_full();
                // get purchase suggested id
                $this->db->where('id', $order->id_purchase_suggested);
                $ps = $this->db->get('tblpurchase_suggested')->row();
                if($ps) {
                    $order->code_purchase_suggested = $ps->code;
                }
                else {
                    $order->code_purchase_suggested = "";
                }
                $data['item'] = $order;
                foreach($data['item']->products as $key=>$value) {
                    $data['item']->products[$key]->warehouse_type = (object)$this->warehouse_model->getWarehouseProduct($value->warehouse_id,$value->product_id, true);
                }
                $content = $this->load->view('admin/orders/view', $data, true);
                exit($content);
            }
        }
        redirect(admin_url() . 'purchase_orders');
    }
    public function detail($id='') {
        $data = array();
        $data['items'] = $this->invoice_items_model->get_full();
        if($this->input->post()) {
            if( $id == '' ) {
                $data_post = $this->input->post();
                
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
            
        }
        
        $this->load->view('admin/purchase_suggested/detail', $data);
    }
    public function detail_pdf($id='') {
        if (!$id) {
            redirect(admin_url('purchase_orders'));
        }
        $purchase_order        = $this->orders_model->get($id);
        $purchase_order_code = $purchase_order->code;

        $pdf            = purchase_orders_pdf($purchase_order);
        $type           = 'D';
        if ($this->input->get('pdf') || $this->input->get('print')) {
            $type = 'I';
        }
        $pdf->Output(mb_strtoupper(slug_it($purchase_order_code)) . '.pdf', $type);
    }
    public function lock($id) {
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        if (!$id || !is_numeric($id)) {
            redirect(admin_url('purchase_orders'));
        }
        $item = $this->purchase_suggested_model->get($id);
        if($item) {
            $this->db->update('tblorders', array('isLock' => 1), array('id' => $id));
        }
        redirect(admin_url('purchase_orders'));
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
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        
        $staff_id = get_staff_user_id();
        $date = date('Y-m-d H:i:s');
        $inv = $this->orders_model->get($id);        
        if(is_admin() && $status == 0)
        {
            $data['user_head_id'] = $staff_id;
            $data['user_head_date'] = $date;
        }
        elseif(is_head($inv->id_user_create))
        {
            $data['user_head_id'] = $staff_id;
            $data['user_head_date'] = $date;
        }
        $success=false;

        if(is_admin() || is_head($inv->id_user_create))
        {
            $success=$this->orders_model->update_status($id, $data);
        }

        if($success) {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Xác nhận đơn hàng thành công')
            ));
        }
        else
        {
            echo json_encode(array(
                'success' => $success,
                'message' => _l('Không thể cập nhật dữ liệu')
            ));
        }
        exit();
    }
    public function getCurrencyIDFromSupplier($idSupplier) {
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        $value = $this->currencies_model->getCurrencyIDFromSupplier($idSupplier);
        $result = new stdClass();
        $result->id = $value;
        echo json_encode($result);
    }
    public function getExchangeRate() {
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        $currencies = $this->currencies_model->get();
        header('Content-type: application/json');
        $array_currencies = array();
        if(count($currencies) > 0) {
            
            
            $url = "http://www.mycurrency.net/service/rates";
            $content = file_get_contents($url);
            $result = new stdClass();
            $result->error = false;
            $result->currencies = array();
            $data_currencies = array();
            if($content) {
                $object_currencies = json_decode($content);
                
                foreach($currencies as $key=>$value) {
                    foreach($object_currencies as $item_currency) {
                        if(str_replace("Đ", "D", $value['name']) == $item_currency->currency_code) {
                            $data_currencies[$item_currency->currency_code] = $item_currency->rate;
                            break;
                        }
                    }
                }
                $result->currencies['USD'] = $data_currencies['VND'];
                foreach($currencies as $key=>$value) {
                    if($key != 'VND' && isset($data_currencies[$value['name']])) {
                        if($key != 'USD') {
                            $result->currencies[$value['name']] = $result->currencies['USD'] / $data_currencies[$value['name']];
                        }
                    }
                }
            }
            else {
                $result->error = true;
            }
        }
        exit(json_encode($result));
    }

    public function exportexcel()
    {
        $this->db->select('tblsales.*,tblclients');
        $this->db->join('tblclients','tblclients.userid=tblsales.customer_id');
        $orders=$this->db->get('tblsales')->result_array();
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('tiêu đề');
        $colum_array=array('I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $BStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '111112'),
                'size'  => 11,
                'name'  => 'Times New Roman'
            )
        );
        for($row = 1; $row <= 100; $row++)
        {
            $styleArray = [
                'font' => [
                    'size' => 12
                ]
            ];
            $objPHPExcel->getActiveSheet()
                ->getStyle("A1:N1")
                ->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1','CÔNG TY TNHH DUDOFF VIỆT NAM');
            $objPHPExcel->getActiveSheet()->getStyle()->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(100);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:N1');
        }
        $objPHPExcel->getActiveSheet()->setCellValue('A2','STT')->getStyle('A2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('B2','NGÀY TẠO')->getStyle('B2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('C2','MÃ ĐƠN HÀNG')->getStyle('C2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('D2','KHÁCH HÀNG')->getStyle('D2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('E2','SỐ ĐIỆN THOẠI')->getStyle('E2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('F2','ĐỊA CHỈ')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('G2','MÃ SẢN PHẨM')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('H2','TÊN SẢN PHẨM')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('I2','ĐƠN GIÁ')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('J2','SỐ LƯỢNG')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('K2','THÀNH TIỀN')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('L2','NGƯỜI TẠO')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('M2','TRẠNG THÁI')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('N2','ĐƯỢC DUYỆT BỞI')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('O2','NGÀY DUYỆT')->getStyle('F2')->applyFromArray($BStyle);

        foreach($orders as $rom => $order)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($rom+3),($rom+1));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($rom+3),$order['date_create']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.($rom+3),$order['prefix'].$order['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.($rom+3),$order['company']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.($rom+3),$order['phonenumber']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.($order+3),$staff['address']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.($order+3),$staff['phonenumber']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.($rom+3),$order['date_birth']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.($rom+3),$order['current_address']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.($rom+3),$order['emergency_contact']);
            if ($staff['last_login']!= NULL) {
                $_data = time_ago($staff['last_login']);
            } else {
                $_data = 'Never';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('K'.($rom+3),$_data);
            $active='Không';
            if($staff['active']==1)
            {
                $active="Có";
            }
            $objPHPExcel->getActiveSheet()->setCellValue('L'.($rom+3),$active);

        }
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="filexuat.xls"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();


    }
}