<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
    'tblitems.id',
    'tblitems.code',
    'tblitems.name',
    // 'tblitems.short_name',
    'tblitems.product_features',
    'tblitems.price',
    'tblunits.unit',
    'tblitems_groups.name',
    // 'tblitems.minimum_quantity',
    // 'tblitems.maximum_quantity',
    );
// var_dump($aColumns);die;
$sIndexColumn = "id";
$sTable       = 'tblitems';
$where = array(

);
$join             = array(
    'LEFT JOIN tbltaxes ON tbltaxes.id = tblitems.tax',     
    'LEFT JOIN tblitems_groups ON tblitems_groups.id = tblitems.group_id',
    'LEFT JOIN district ON district.districtid = tblitems.district_id',
    'LEFT JOIN tblunits ON tblitems.unit = tblunits.unitid',
    );
$additionalSelect = array(
    'tblitems.avatar',
    
    'tblitems.id',
    'tbltaxes.name', 
    'taxrate',
    'group_id',
    );
if($this->_instance->input->post()) {
    $filter_category_1 = $this->_instance->input->post('category_1');
    $filter_category_2 = $this->_instance->input->post('category_2');
    $filter_category_3 = $this->_instance->input->post('category_3');
    $filter_category_4 = $this->_instance->input->post('category_4');

    if(!is_null($filter_category_4) && $filter_category_4 != "") {
        // array_push($where, 'AND tblitems.category_id='.$filter_category_4);
        $result=[];
        $this->_instance->category_model->get_full_childs_id($filter_category_4, $result);
        $sum_where = 'AND (';
        foreach($result as $value) {
            if($sum_where != 'AND (')
                $sum_where.=' OR ';
            $sum_where .= 'tblitems.category_id='.$value;
        }
        $sum_where .= ')';
        array_push($where, $sum_where);
    }
    else if(!is_null($filter_category_3) && $filter_category_3 != "") {
        // array_push($where, 'AND tblitems.category_id='.$filter_category_3);
        $result=[];
        $this->_instance->category_model->get_full_childs_id($filter_category_3, $result);
        $sum_where = 'AND (';
        foreach($result as $value) {
            if($sum_where != 'AND (')
                $sum_where.=' OR ';
            $sum_where .= 'tblitems.category_id='.$value;
        }
        $sum_where .= ')';
        array_push($where, $sum_where);
    }
    else if(!is_null($filter_category_2) && $filter_category_2 != "") {
        // array_push($where, 'AND tblitems.category_id='.$filter_category_2);
        $result=[];
        $this->_instance->category_model->get_full_childs_id($filter_category_2, $result);
        $sum_where = 'AND (';
        foreach($result as $value) {
            if($sum_where != 'AND (')
                $sum_where.=' OR ';
            $sum_where .= 'tblitems.category_id='.$value;
        }
        $sum_where .= ')';
        array_push($where, $sum_where);
    }
    else if(!is_null($filter_category_1) && $filter_category_1 != "") {
        //array_push($where, 'AND tblitems.category_id='.$filter_category_1);
        $result=[];
        $this->_instance->category_model->get_full_childs_id($filter_category_1, $result);
        $sum_where = 'AND (';
        foreach($result as $value) {
            if($sum_where != 'AND (')
                $sum_where.=' OR ';
            $sum_where .= 'tblitems.category_id='.$value;
        }
        $sum_where .= ')';
        array_push($where, $sum_where);
    } 
}
// print_r($where);
// exit();
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect);
$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $array_link = ['tblitems.code', 'tblitems.name'];
        if(in_array($aColumns[$i],$array_link)){
            $_data = '<a onclick="init_product_modal_data('.$aRow['id'].');return false;" href="'.admin_url('invoice_items/item/').$aRow['id'].'">'.$_data.'</a>';
        }
        if($aColumns[$i] == 'tblitems.avatar' && file_exists($_data)) {
            $_data = '<img src="'.base_url($_data).'" width="50px" />';
        }
        $format_number_column = ['tblitems.price','tblitems.minimum_quantity','tblitems.maximum_quantity'];
        if(in_array($aColumns[$i], $format_number_column)) {
            $_data = number_format($_data,0,',','.');
        }
        if($aColumns[$i] == 'tblitems.description') {
            $_data = strlen($_data) > 50 ? substr($_data,0,50)."..." : $_data;
        }
        // if($aColumns[$i] == 'tblitems.price') {
        //     $_data = number_format($aRow['tblitems.price'],0,',','.');
        // }
        $row[] = $_data;
    }
    $options = '';
    if(has_permission('items','','edit')){
        $options .= icon_btn('#', 'eye','btn-default',array('onclick'=>'init_product_modal_data('.$aRow['id'].');return false;'));
        // $options .= icon_btn('invoice_items/item/' . $aRow['id'], 'pencil-square-o', 'btn-default');
    }
    if(has_permission('items','','delete')){
       $options .= icon_btn('invoice_items/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
   }
   $row[] = $options;

   $output['aaData'][] = $row;
}
