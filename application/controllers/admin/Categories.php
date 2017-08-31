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
        $full_categories = $this->category_model->get_full_detail();
        $data['full_categories'] = $full_categories;
        
        $data['categories'] = [];
        $this->category_model->get_by_id(0,$data['categories']);
        $data['category_1'] = $this->category_model->get_level1();
        
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
    public function get_childs($id) {
        echo json_encode($this->category_model->get_childs($id));
    }
    public function import()
    {
        $simulate_data  = array();
        $total_imported = 0;
        $load_result = false;
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
                                
                                $objReader->setReadDataOnly(true);
                                
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
                        $current_level_1 =  0;
                        $current_level_2 =  0;
                        $current_level_3 =  0;
                        $current_level_4 =  0;
                        $had_item = [];
                        foreach($rows as $key=>$value) {
                            if(trim($value[0]) != '' && (!isset($value[1]) || trim($value[1]) == '')  && (!isset($value[2]) || trim($value[2]) == '')  && (!isset($value[3]) || trim($value[3]) == '')) {
                                $result_array[]['name']  = trim($value[0]);
                                $current_level_1 = count($result_array) - 1;
                                $result_array[$current_level_1]['children']  = [];
                                $duplicate = ($this->category_model->get_single_by_name(trim($value[0])) != false);
                                if($duplicate)
                                    $had_item[] = trim($value[0]);
                                $query_array[] = array(
                                    'name' => trim($value[0]),
                                    'sub'  => ' ',
                                    'parent' => '',
                                    'duplicate' => $duplicate,
                                );
                            }
                            else if(isset($result_array[$current_level_1]) && trim($value[1]) != '' && (!isset($value[0]) || trim($value[0]) == '')  && (!isset($value[2]) || trim($value[2]) == '')  && (!isset($value[3]) || trim($value[3]) == '')) {
                                $result_array[$current_level_1]['children'][]['name']  = trim($value[1]);
                                $current_level_2 = count($result_array[$current_level_1]['children']) - 1;
                                $result_array[$current_level_1]['children'][$current_level_2]['children']  = [];
                                $duplicate = ($this->category_model->get_single_by_name(trim($value[1])) != false);
                                if($duplicate)
                                    $had_item[] = trim($value[1]);
                                $query_array[] = array(
                                    'name' => trim($value[1]),
                                    'sub'  => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;> ',
                                    'parent' => $result_array[$current_level_1]['name'],
                                    'duplicate' => $duplicate,
                                );
                            }
                            else if(isset($result_array[$current_level_1]['children'][$current_level_2]) && trim($value[2]) != '' && (!isset($value[0]) || trim($value[0]) == '')  && (!isset($value[1]) || trim($value[1]) == '')  && (!isset($value[3]) || trim($value[3]) == '')) {
                                $result_array[$current_level_1]['children'][$current_level_2]['children'][]['name']  = trim($value[2]);
                                $current_level_3 = count($result_array[$current_level_1]['children'][$current_level_2]['children']) - 1;
                                $result_array[$current_level_1]['children'][$current_level_2]['children'][$current_level_3]['children']  = [];
                                $duplicate = ($this->category_model->get_single_by_name(trim($value[2])) != false);
                                if($duplicate)
                                    $had_item[] = trim($value[2]);
                                $query_array[] = array(
                                    'name' => trim($value[2]),
                                    'sub'  => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;> ',
                                    'parent' => $result_array[$current_level_1]['children'][$current_level_2]['name'],
                                    'duplicate' => $duplicate,
                                );
                            }
                            else if(isset($result_array[$current_level_1]['children'][$current_level_2]['children'][$current_level_3]) && trim($value[3]) != '' && (!isset($value[0]) || trim($value[0]) == '')  && (!isset($value[1]) || trim($value[1]) == '')  && (!isset($value[2]) || trim($value[2]) == '')) {
                                $result_array[$current_level_1]['children'][$current_level_2]['children'][$current_level_3]['children'][]['name']  = trim($value[3]);
                                $current_level_4 = count($result_array[$current_level_1]['children'][$current_level_2]['children'][$current_level_3]['children']) - 1;
                                $result_array[$current_level_1]['children'][$current_level_2]['children'][$current_level_3]['children'][$current_level_4]['children']  = [];
                                $duplicate = ($this->category_model->get_single_by_name(trim($value[3])) != false);
                                if($duplicate)
                                    $had_item[] = trim($value[3]);
                                $query_array[] = array(
                                    'name' => trim($value[3]),
                                    'sub'  => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;> ',
                                    'parent' => $result_array[$current_level_1]['children'][$current_level_2]['children'][$current_level_3]['name'],
                                    'duplicate' => $duplicate,
                                );
                            }
                        }
                        $this->session->set_userdata('query_array', $query_array);
                        $this->session->set_userdata('query_duplicate', $had_item);
                    }
                } else {
                    set_alert('warning', _l('import_upload_failed'));
                }
            }
            if($this->input->post('confirm') && $this->input->post('confirm')==1) {
            
                $row_imported = 0;
                foreach($this->session->userdata('query_array') as $value) {
                    $parent = 0;
                    if($value['parent'] != '') {
                        $parent = $this->category_model->get_single_by_name($value['parent']);
                        if($parent) 
                            $parent = $parent->id;
                        else
                            $parent = 0;
                    }
                    $data = array(
                        'category' => $value['name'],
                        'category_parent' => $parent,
                    );
                    $this->category_model->add_category($data);
                    $row_imported++;
                }
                $this->session->unset_userdata('query_array');
            }
        }
        
        if (isset($load_result) && $load_result == true) {
            set_alert('success', _l('load_import_success'));
        }
        if(isset($row_imported)) {
            $data['row_imported'] = $row_imported;
            set_alert('success', _l('category_import_success') . $row_imported);
        }

        $data['title']          = 'Import';
        $this->load->view('admin/categories/import', $data);
    }

}
