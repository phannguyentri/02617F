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
        
        $data['categories'] = [];
        $this->category_model->get_by_id(0,$data['categories']);
        // var_dump($data['roles']);die();
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
    public function import()
    {
        $simulate_data  = array();
        $total_imported = 0;
        if ($this->input->post()) {
            if (isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                // Get the temp file path
                $tmpFilePath = $_FILES['file_csv']['tmp_name'];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Setup our new file path
                    $newFilePath = TEMP_FOLDER . $_FILES['file_csv']['name'];
                    if (!file_exists(TEMP_FOLDER)) {
                        mkdir(TEMP_FOLDER, 777);
                    }
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $import_result = true;
                        $fd            = fopen($newFilePath, 'r');
                        $rows          = array();
                        while ($row = fgetcsv($fd)) {
                            $rows[] = $row;
                        }
                        fclose($fd);
                        $data['total_rows_post'] = count($rows);
                        if (count($rows) <= 1) {
                            set_alert('warning', 'Not enought rows for importing');
                            redirect(admin_url('leads/import'));
                        }

                        unset($rows[0]);
                        if ($this->input->post('simulate')) {
                            if (count($rows) > 500) {
                                set_alert('warning', 'Recommended splitting the CSV file into smaller files. Our recomendation is 500 row, your CSV file has ' . count($rows));
                            }
                        }
                        $db_temp_fields = $this->db->list_fields('tblleads');
                        $db_fields      = array();
                        foreach ($db_temp_fields as $field) {
                            if (in_array($field, $this->not_importable_leads_fields)) {
                                continue;
                            }
                            $db_fields[] = $field;
                        }
                        $custom_fields = get_custom_fields('leads');
                        $_row_simulate = 0;
                        foreach ($rows as $row) {
                            // do for db fields
                            $insert = array();
                            for ($i = 0; $i < count($db_fields); $i++) {
                                // Avoid errors on nema field. is required in database
                                if ($db_fields[$i] == 'name' && $row[$i] == '') {
                                    $row[$i] = '/';
                                } else if ($db_fields[$i] == 'country') {
                                    if ($row[$i] != '') {
                                        $this->db->where('iso2', $row[$i]);
                                        $this->db->or_where('short_name', $row[$i]);
                                        $this->db->or_where('long_name', $row[$i]);
                                        $country = $this->db->get('tblcountries')->row();
                                        if ($country) {
                                            $row[$i] = $country->country_id;
                                        } else {
                                            $row[$i] = 0;
                                        }
                                    } else {
                                        $row[$i] = 0;
                                    }
                                }
                                $insert[$db_fields[$i]] = $row[$i];
                            }
                            if (count($insert) > 0) {
                                $total_imported++;
                                $insert['dateadded']   = date('Y-m-d H:i:s');
                                $insert['addedfrom']   = get_staff_user_id();
                                $insert['lastcontact'] = NULL;
                                $insert['status']      = $this->input->post('status');
                                $insert['source']      = $this->input->post('source');
                                if ($this->input->post('responsible')) {
                                    $insert['assigned'] = $this->input->post('responsible');
                                }
                                if (!$this->input->post('simulate')) {
                                    $this->db->insert('tblleads', $insert);
                                    $leadid = $this->db->insert_id();
                                } else {
                                    if ($insert['country'] != 0) {
                                        $insert['country'] = get_country_short_name($insert['country']);
                                    }
                                    $simulate_data[$_row_simulate] = $insert;
                                    $leadid                        = true;
                                }
                                if ($leadid) {
                                    $insert = array();
                                    foreach ($custom_fields as $field) {
                                        if (!$this->input->post('simulate')) {
                                            if ($row[$i] != '') {
                                                $this->db->insert('tblcustomfieldsvalues', array(
                                                    'relid' => $leadid,
                                                    'fieldid' => $field['id'],
                                                    'value' => $row[$i],
                                                    'fieldto' => 'leads'
                                                ));
                                            }
                                        } else {
                                            $simulate_data[$_row_simulate][$field['name']] = $row[$i];
                                        }
                                        $i++;
                                    }
                                }
                            }
                            $_row_simulate++;
                            if ($this->input->post('simulate') && $_row_simulate >= 100) {
                                break;
                            }
                        }
                        unlink($newFilePath);
                    }
                } else {
                    set_alert('warning', _l('import_upload_failed'));
                }
            }
        }
        
        if (isset($import_result)) {
            set_alert('success', _l('import_total_imported', $total_imported));
        }

        $data['title']          = 'Import';
        $this->load->view('admin/categories/import', $data);
    }

}
