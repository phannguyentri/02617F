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

    public function getProductsInWH($warehouse_id) {
        if(is_numeric($warehouse_id) && $this->input->is_ajax_request()) {
            echo json_encode($this->warehouse_model->getProductsByWarehouseID($warehouse_id));
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
            $this->perfex_base->get_table_data('warehouse_detail', array('warehouse_id' => $id));
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

    public function getWarehouses($warehouse_type,$filterByProduct='', $includeDoesntContain=false) {
        if(is_numeric($warehouse_type) && $this->input->is_ajax_request()) {
            echo json_encode($this->warehouse_model->getWarehousesByType($warehouse_type, $filterByProduct, $includeDoesntContain));
        }
    }

    public function exportexcel()
    {
        $categori=$this->db->get('tblcategories')->result_array();



        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('tiêu đề');
        $this->db->select('tblclients.*,tblcontacts.firstname as contact_firstname,tblcontacts.lastname as contact_lastname');
        $this->db->join('tblcontacts','tblcontacts.userid=tblclients.userid','left');
        $client=$this->db->get('tblclients')->result_array();
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
        $objPHPExcel->getActiveSheet()->setCellValue('A2','ID')->getStyle('A2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('B2','STT')->getStyle('B2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('C2','MÃ SẢN PHẨM')->getStyle('C2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('D2','SẢN PHẨM')->getStyle('D2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('E2','ĐƠN VỊ TÍNH')->getStyle('E2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('F2','GIÁ BÁN')->getStyle('F2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('G2','GIÁ VỐN')->getStyle('G2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('H2','HÀNG CÓ THỂ BÁN')->getStyle('H2')->applyFromArray($BStyle);
        $warehouses=$this->db->get('tblwarehouses')->result_array();
        foreach($warehouses as $num_ware=> $warehouse)
        {
            $objPHPExcel->getActiveSheet()->setCellValue($colum_array[$num_ware].'2',$warehouse['warehouse'])->getStyle($colum_array[$num_ware].'2')->applyFromArray($BStyle);
        }
        $rom=3;
        foreach($categori as $rom_cate => $value_categori)
        {
            $this->db->select('tblitems.*,tblunits.unit as name_unit');
            $this->db->where('category_id',$value_categori['id']);
            $this->db->join('tblunits','tblunits.unitid=tblitems.unit','left');
            $product=$this->db->get('tblitems')->result_array();
            if($product!=array()){

                $rom++;
                for($row = 1; $row <= 100; $row++)
                {
                    $styleArray = [
                        'font' => [
                            'size' => 12
                        ]
                    ];
                    $objPHPExcel->getActiveSheet()
                        ->getStyle("A".$rom.":N".$rom)
                        ->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rom,$value_categori['category']);
                    $objPHPExcel->getActiveSheet()->getStyle()->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(100);
                    $objPHPExcel->getActiveSheet()->mergeCells("A".$rom.":N".$rom);
                }
                foreach($product as $r=>$value)
                {
                    $rom=($rom+1);
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rom,$value['id']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rom,($r+1));
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rom,$value['code']);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$rom,$value['name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rom,$value['name_unit']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$rom,$value['price']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$rom,$value['price_buy']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$rom,$value['price_buy']);
                    foreach($warehouses as $num_ware=> $warehouse)
                    {
                        $this->db->where('tblwarehouses_products.product_id',$value['id']);
                        $this->db->where('tblwarehouses_products.warehouse_id',$warehouse['warehouseid']);
                        $this->db->join('tblwarehouses','tblwarehouses.warehouseid=tblwarehouses_products.warehouse_id');
                        $warehouse_product=$this->db->get('tblwarehouses_products')->row();
                        if($warehouse_product)
                        {
                            $objPHPExcel->getActiveSheet()->setCellValue($colum_array[$num_ware].$rom,$warehouse_product->product_quantity);
                        }
                    }

                }
            }
        }
//        die();
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="filexuat.xls"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();


    }
    public function exportexcel_kindof_warehouse()
    {
        $this->db->join('tbl_kindof_warehouse','tbl_kindof_warehouse.id=tblwarehouses.kindof_warehouse');
        $warehouses=$this->db->get('tblwarehouses')->result_array();

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
        $objPHPExcel->getActiveSheet()->setCellValue('B2','MÃ KHO')->getStyle('B2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('C2','TÊN LOẠI KHO')->getStyle('C2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('D2','ĐỊA CHỈ')->getStyle('D2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('E2','BỘ PHẬN QUẢN LÝ')->getStyle('E2')->applyFromArray($BStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('F2','DÒNG LƯU CHUYỂN')->getStyle('F2')->applyFromArray($BStyle);

        foreach($warehouses as $rom => $value_warehouse)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($rom+3),($rom+1));
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($rom+3),$value_warehouse['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.($rom+3),$value_warehouse['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.($rom+3),$value_warehouse['address']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.($rom+3),'');
            $objPHPExcel->getActiveSheet()->setCellValue('F'.($rom+3),'');

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
