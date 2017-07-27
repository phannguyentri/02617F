<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoice_items extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_items_model');
        $this->load->model('category_model');
    }
    /* List all available items */
    public function index()
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('Invoice Items');
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('invoice_items');
        }
        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        $data['items_units'] = $this->invoice_items_model->get_units();
        

        $data['title'] = _l('invoice_items');
        $this->load->view('admin/invoice_items/manage', $data);
    }
    public function get_tax($id_tax) {
        if (!has_permission('items', '', 'view')) {
            access_denied('Invoice Items');
        }
        if ($this->input->is_ajax_request()) {
            $this->load->model('taxes_model');
            exit(json_encode($this->taxes_model->get($id_tax)));
        }
    }
    /* Edit client or add new client*/
    public function item($id = '')
    {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        // Add new without ajax
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('items', '', 'create')) {
                    access_denied('items');
                }
                $data                 = $this->input->post();
                $data['price']=str_replace('.','',$data['price']);
                $data['price_buy']=str_replace('.','',$data['price_buy']);
                $save_and_add_contact = false;
                // Category 4rd level
                if(is_array($data['category_id'])) {
                    for ($i=count($data['category_id'])-1; $i >= 0 ; $i--) { 
                        if( $data['category_id'][$i] != 0 ) {
                            $data['category_id'] = $data['category_id'][$i];
                            break;
                        }
                    }
                }
                // End
                if (isset($data['save_and_add_contact'])) {
                    unset($data['save_and_add_contact']);
                    $save_and_add_contact = true;
                }
                $id = $this->invoice_items_model->add($data);
                if (!has_permission('items', '', 'view')) {
                    $assign['customer_admins']   = array();
                    $assign['customer_admins'][] = get_staff_user_id();
                    $this->invoice_items_model->assign_admins($assign, $id);
                }
                if ($id) {
                    handle_item_avatar_image_upload($id);
                    set_alert('success', _l('added_successfuly', _l('als_products')));
                    //redirect(admin_url('invoice_items/item/' . $id . '?new_contact=true'));
                    redirect(admin_url('invoice_items'));
                }
            } else {
                if (!has_permission('invoice_items', '', 'edit')) {
                    if (!is_customer_admin($id)) {
                        access_denied('invoice_items');
                    }
                }
                $data = $this->input->post();
                $data['price']=str_replace('.','',$data['price']);
                $data['price_buy']=str_replace('.','',$data['price_buy']);
                $data['itemid'] = $id;
                $item = $this->invoice_items_model->get_full($id);
                if(is_array($data['category_id'])) {
                    for ($i=count($data['category_id'])-1; $i >= 0 ; $i--) { 
                        if( $data['category_id'][$i] != 0 ) {
                            $data['category_id'] = $data['category_id'][$i];
                            break;
                        }
                    }
                }
                
                $success = $this->invoice_items_model->edit($data, $item);
                $success_avatar = handle_item_avatar_image_upload($id);
                if ($success == true || $success_avatar == true) {
                    set_alert('success', _l('updated_successfuly', _l('als_products')));
                }
                redirect(admin_url('invoice_items/item/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('als_products'));
            $array_categories[] = array(0, $this->invoice_items_model->get_same_level_categories(0));
            $array_categories[1] = array(0, array());
            $array_categories[2] = array(0, array());
            $array_categories[3] = array(0, array());
            $data['array_categories'] = $array_categories;
            
        } else {
            $title = _l('invoice_item_edit_heading');
            $item = $this->invoice_items_model->get_full($id);
            $array_categories = [];

            $array_categories[] = array($item->category_id, $this->invoice_items_model->get_same_level_categories($item->category_id));
            $this->invoice_items_model->get_category_parent_id($item->category_id, $array_categories);
            
            if(count($array_categories) < 4) {
                if(!isset($array_categories[1])) {
                    $array_categories[1] = array(0, array());
                }
                if(!isset($array_categories[2])) {
                    $array_categories[2] = array(0, array());
                }
                if(!isset($array_categories[3])) {
                    $array_categories[3] = array(0, array());
                }
            }
            if (!$item) {
                blank_page('Client Not Found');
            }
            $data['array_categories'] = $array_categories;
            $data['item'] = $item;
        }


        $data['title'] = $title;
        $this->load->view('admin/invoice_items/item_details', $data);
    }
    public function get_categories($id=0) {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        echo json_encode($this->invoice_items_model->get_categories($id));
    }
    public function get_invoice_item_attachment($id) {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if(is_numeric($id)) {
            $item = $this->invoice_items_model->get_full($id);
            if($item) {
                $this->load->view('admin/invoice_items/item_attachments_template', array('attachments'=>$item->attachments));
            }
        }
        
    }
    public function delete_attachment($id)
    {
        echo json_encode(array(
            'success' => $this->invoice_items_model->delete_invoice_item_attachment($id)
        ));
    }
    public function add_item_attachment()
    {
        $item_id = $this->input->post('leadid');
        echo json_encode(handle_invoice_attachments($item_id));
    }
    public function price_history($id = '') {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if($id!='') {
            if($this->input->is_ajax_request()) {
                $this->perfex_base->get_table_data('item_price_history', array(
                    'rel_id' => $id,
                )); 
            }
        }
    }
    public function price_buy_history($id = '') {
        if (!has_permission('items', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        if($id!='') {
            
            if($this->input->is_ajax_request()) {
                $this->perfex_base->get_table_data('item_price_buy_history', array(
                    'rel_id' => $id,
                )); 
            }
        }
    }
    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                
                if ($data['itemid'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->invoice_items_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item' => $this->invoice_items_model->get($id)
                    ));
                } else {
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->invoice_items_model->edit($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message
                    ));
                }
            }
        }
    }
    public function add_landtype()
    {

        if ($this->input->post() && has_permission('items', '', 'create')) {

            $this->invoice_items_model->add_landtype($this->input->post());
            set_alert('success', _l('added_successfuly', 'Loại nhà đất'));
        }
    }
    public function add_group()
    {

        if ($this->input->post() && has_permission('items', '', 'create')) {
            $this->invoice_items_model->add_group($this->input->post());
            set_alert('success', _l('added_successfuly', _l('item_group')));
        }
    }

    public function update_group($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->invoice_items_model->edit_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfuly', _l('item_group')));
        }
    }
    public function update_landtype($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->invoice_items_model->edit_landtype($this->input->post(), $id);
            set_alert('success', _l('updated_successfuly', "Loại nhà đất"));
        }
    }
    public function delete_group($id)
    {
        if (has_permission('items', '', 'delete')) {
            if ($this->invoice_items_model->delete_group($id)) {
                set_alert('success', _l('deleted', _l('item_group')));
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }
    public function delete_landtype($id)
    {
        if (has_permission('items', '', 'delete')) {
            if ($this->invoice_items_model->delete_landtype($id)) {
                set_alert('success', _l('deleted', 'Loại nhà đất'));
            }
        }
        redirect(admin_url('invoice_items?groups_modal=true'));
    }
    /* Delete item*/
    public function delete($id)
    {
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }

        $response = $this->invoice_items_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } else if ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('invoice_items'));
    }
    /* Get item by id / ajax */
    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            
            $item                   = $this->invoice_items_model->get_full($id);
            $item->long_description = nl2br($item->long_description);
            echo json_encode($item);
        }
    }

    /* Get all items */
    public function get_all_items_ajax()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->invoice_items_model->get_all_items_ajax());
        }
    }
    public function import()
    {
        $total_imported = 0;
        $load_result = false;
        $alert = [
            'success' => 0,
            'fail'    => [],
        ];
        if ($this->input->post()) {
            if (isset($_FILES['file_import']['name']) && $_FILES['file_import']['name'] != '') {
                // Get the temp file path
                $tmpFilePath = $_FILES['file_import']['tmp_name'];
                $ext = strtolower(pathinfo($_FILES['file_import']['name'], PATHINFO_EXTENSION));
                $type = $_FILES["file_import"]["type"];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Setup our new file path
                    $newFilePath = TEMP_FOLDER . $_FILES['file_import']['name'];
                    if (!file_exists(TEMP_FOLDER)) {
                        mkdir(TEMP_FOLDER, 777);
                    }
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $load_result = true;
                        $fd            = fopen($newFilePath, 'r');
                        $rows          = array();
                        if($ext == 'csv') {
                            while ($row = fgetcsv($fd)) {
                                $rows[] = $row;
                            }
                        }
                        else if($ext == 'xlsx' || $ext == 'xls') {
                            if($type == "application/octet-stream" || $type == "application/vnd.ms-excel" || $type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                                require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php');

                                $inputFileType = PHPExcel_IOFactory::identify($newFilePath);
                                
                                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                                
                                // $objReader->setReadDataOnly(true);
                                
                                /**  Load $inputFileName to a PHPExcel Object  **/
                            $objPHPExcel =           $objReader->load($newFilePath);
                                $allSheetName       = $objPHPExcel->getSheetNames();
                                $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0);
                                $highestRow         = $objWorksheet->getHighestRow();
                                $highestColumn      = $objWorksheet->getHighestColumn();
                                
                                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                                
                                for ($row = 2; $row <= $highestRow; ++$row) {
                                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                        $value                     = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                                        $rows[$row - 2][$col] = $value;
                                    }
                                }
                            }
                        }
                        else {
                            fclose($fd);
                            unlink($newFilePath);
                            redirect('/');
                        }

                        fclose($fd);
                        $data['total_rows_post'] = count($rows);
                        unlink($newFilePath);

                        // Works with difficulty
                        $query_array = [];
                        $backup_rows = $rows;
                        unset($rows[0]);
                        $result_array = [];
                        $important_columns = array(
                            'code'                      => array('Mã',                  -1),
                            'name'                      => array('Tên',                 -1),
                            'short_name'                => array('Tên ngắn',            -1),
                            'description'               => array('Miêu tả',             -1),
                            'long_description'          => array('Miêu tả dài',         -1),
                            'unit'                      => array('Đơn vị',              -1),
                            'group_id'                  => array('Nhóm',                -1),
                            'release_date'              => array('Ngày công bố',        -1),
                            'date_of_removal_of_sample' => array('Ngày bỏ mẫu',         -1),
                            'country_id'                => array('Xuất xứ',             -1),
                            'specification'             => array('Quy cách',            -1),
                            'size'                      => array('Kích thước',          -1),
                            'weight'                    => array('Trọng lượng',         -1),
                            'product_features'          => array('Đặc tính sản phẩm',   -1),
                            'price'                     => array('Giá bán',             -1),
                            'price_buy'                 => array('Giá nhập',            -1),
                            'minimum_quantity'          => array('Số lượng tối thiểu',  -1),
                            'maximum_quantity'          => array('Số lượng tối đa',     -1),
                            'quantity'                  => array('Số lượng',            -1),
                            'category_id'               => array('Danh mục',            -1),
                        );
                        $fetch_columns_step = true;
                        $fetch_product_step = false;
                        $columns_found = 0;
                        $product_count = 0;
                        foreach($rows as $row) {
                            if($fetch_columns_step) {
                                $stt=0;
                                foreach($important_columns as $column_key=>$column_value) {
                                    // Nếu bảng tính không có cột để xét thì thoát, kết quả sẽ không thể nhập
                                    if(!isset($row[$stt]))
                                        break;
                                    // Kiểm tra nếu nội dung của ô bằng với nội dung cột cần nhập
                                    if(trim($row[$stt]) == trim($column_value[0])) {
                                        $columns_found++;
                                    }
                                    // Nếu tìm được đủ cột không tìm nữa và bắt đầu chạy thêm sản phẩm
                                    if($columns_found >= count($important_columns)) {
                                        $fetch_columns_step = false;
                                        $fetch_product_step = true;
                                        break;
                                    }
                                    $stt++;
                                }
                                continue;
                            }
                            if($fetch_product_step) {
                                $product_count++;
                                $data = [];
                                $stt = 0;
                                $data_ok = true;
                                $reason = "";
                                // Gán từng ô là field tương ứng trong csdl
                                foreach($important_columns as $column_key=>$column_value) {
                                    if($column_key == 'group_id') {
                                        $all_groups = get_item_groups();
                                        $result_search = false;
                                        
                                        foreach($all_groups as $key=>$group) {
                                            if(trim($group['name']) == trim($row[$stt])) {
                                                $result_search = $key;
                                                break;
                                            }
                                        }
                                        if($result_search !== false) {
                                            $data[$column_key] = $all_groups[$result_search]['name'];
                                        }
                                        else {
                                            $reason .= "Không tìm thấy " . $column_value[0] . " ".$row[$stt] ."<br />";
                                            $data_ok = false;
                                        }
                                    }
                                    if($column_key == 'country_id') {
                                        $all_countries = get_all_countries();
                                        $result_search = false;
                                        foreach($all_countries as $key=>$country) {
                                            if(trim($country['short_name']) == trim($row[$stt])) {
                                                $result_search = $key;
                                                break;
                                            }
                                        }
                                        
                                        if($result_search !== false) {
                                            $data[$column_key] = $all_countries[$result_search]['country_id'];
                                        }
                                        else {
                                            $reason .= "Không tìm thấy " . $column_value[0] . " ".$row[$stt] ."<br />";
                                            $data_ok = false;
                                        }
                                    }
                                    if($column_key == 'category_id') {
                                        $category_name = trim($row[$stt]);
                                        $category = $this->category_model->get_single_by_name($category_name);
                                        if($category)
                                            $data[$column_key] = $category->id;
                                        else {
                                            $reason .= "Không tìm thấy " . $column_value[0] . " ".$category_name ."<br />";
                                            $data_ok = false;
                                        }
                                    }
                                    if($column_key == 'release_date' || $column_key == 'date_of_removal_of_sample') {
                                        $data[$column_key] = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($row[$stt]));
                                    }
                                    if($data[$column_key] == '') {
                                        $data[$column_key] = $row[$stt];
                                    }
                                    $stt++;
                                }
                                if($data_ok) {
                                    $this->db->insert('tblitems',$data);
                                    if($this->db->affected_rows() > 0) {
                                        $alert['success']++;
                                    }
                                    else {
                                        $alert['fail'][] = [$product_count, array_values($data)[0], $reason];
                                    }
                                }
                                else {
                                    $alert['fail'][] = [$product_count, array_values($data)[0], $reason];
                                }
                            }
                        }
                        // var_dump($fetch_columns_step, $fetch_product_step, $columns_found, $alert);
                        // exit();
                        $data['message'] = "
                            Nhập thành công " . $alert['success'] . " sản phẩm.
                        ";
                        if(count($alert['fail']) > 0) {
                            foreach($alert['fail'] as $item) {
                                $data['message'] .= "Dòng ".$item[0]." gặp lỗi ".$item[2];
                            }
                        }
                        // $this->session->set_userdata('query_array', $query_array);
                        // $this->session->set_userdata('query_duplicate', $had_item);
                    }
                } else {
                    set_alert('warning', _l('import_upload_failed'));
                }
            }
            
        }
        
        if (isset($load_result) && $load_result == true) {
            set_alert('success', _l('load_import_success'));
        }

        $data['title']          = 'Import';
        $this->load->view('admin/invoice_items/import', $data);
    }
}
