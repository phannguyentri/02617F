<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'id',
    'category',
    '(select category from tblcategories a where a.id=tblcategories.category_parent) as parent',

);
$sIndexColumn = "id";
$sTable       = 'tblcategories';
$where        = array(
//    'AND id_lead="' . $rel_id . '"'
);
$join         = array(
    // 'LEFT JOIN tblroles  ON tblroles.roleid=tbldepartment.id_role'
);

if($this->_instance->input->post()) {
    $filter_category_1 = $this->_instance->input->post('category_1');
    $filter_category_2 = $this->_instance->input->post('category_2');
    $filter_category_3 = $this->_instance->input->post('category_3');

    if(!is_null($filter_category_3) && $filter_category_3 != "") {
        array_push($where, 'AND category_parent='.$filter_category_3);
    }
    else if(!is_null($filter_category_2) && $filter_category_2 != "") {
        array_push($where, 'AND category_parent='.$filter_category_2);
    }
    else if(!is_null($filter_category_1) && $filter_category_1 != "") {
        array_push($where, 'AND category_parent='.$filter_category_1);
    } 
}

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    // 'tblroles.name',
    // 'tblroles.roleid'
));
$output       = $result['output'];
$rResult      = $result['rResult'];
//var_dump($rResult);die();

// Tuan anh : Custom
$this->_instance->load->model('category_model');

$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'tblroles.id_role') {
            $_data=$aRow['tblroles.name'];
        }
        if ($aColumns[$i] == '(select category from tblcategories a where a.id=tblcategories.category_parent) as parent') {
            $category = $this->_instance->category_model->get_single_by_name($aRow['parent']);
    
            $categories = [];
            while($category) {
                array_unshift($categories, $category->category);

                // bad way
                $temp = $this->_instance->category_model->get_single($category->category_parent);
                if($temp)
                    $category = $this->_instance->category_model->get_single_by_name($temp->category);
                else
                    $category = $temp;
            }
            $sub = "";
            foreach($categories as $value) {
                
                $_data .= $sub.">".$value."<br />";    
                $sub .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
            }
            
            // exit();
        }
        $row[] = $_data;
    }
    if ($aRow['creator'] == get_staff_user_id() || is_admin()) {
        $_data = '<a href="#" class="btn btn-default btn-icon" onclick="view_init_department(' . $aRow['id'] . '); return false;"><i class="fa fa-eye"></i></a>';
        $row[] =$_data.icon_btn('categories/delete_category/'. $aRow['id'] , 'remove', 'btn-danger delete-reminder');
    } else {
        $row[] = '';
    }
    // print_r($aColumns);
    $output['aaData'][] = $row;
}
