<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
    'tblitems.id',
    'tblitems.code',
    'tblitems.name',
    'tblunits.unit',
    'tblitems.minimum_quantity',
    'product_quantity',
    '(tblwarehouses_products.product_quantity * tblitems.price_buy) as total'
    );
// var_dump($aColumns);die;
$sIndexColumn = "id";
$sTable       = 'tblwarehouses_products';

$join             = array(
    'LEFT JOIN tblitems ON tblwarehouses_products.product_id = tblitems.id',    
    'LEFT JOIN tblunits ON tblitems.unit = tblunits.unitid', 
    );
$additionalSelect = array(
    );
$where = array(
    'AND tblwarehouses_products.warehouse_id='.$warehouse_id
);
if($this->_instance->input->post()) {
    $filter_detail_categories = $this->_instance->input->post('detail_categories');
    $sum_where = "AND (";
    if(is_numeric($filter_detail_categories)) {
        $sum_where .= 'tblitems.category_id='.$filter_detail_categories;
       // array_push($where, 'AND tblitems.category_id='.$filter_detail_categories);
    }
    $filter_detail_products = $this->_instance->input->post('detail_products');
    if(is_numeric($filter_detail_products)) {
        if($sum_where != "AND (")
            $sum_where.= ' OR ';
        $sum_where .= 'tblwarehouses_products.product_id='.$filter_detail_products;
        //array_push($where, 'OR tblwarehouses_products.product_id='.$filter_detail_products);
    }
    $sum_where.= ')';
    array_push($where, $sum_where);
}
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect);
$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $array_link = ['tblitems.code', 'tblitems.name'];
        if(in_array($aColumns[$i],$array_link)){
            $_data = '<a target="_blank" href="'.admin_url('invoice_items/item/').$aRow['tblitems.id'].'">'.$_data.'</a>';
        }
        if($aColumns[$i] == '(tblwarehouses_products.product_quantity * tblitems.price_buy) as total') {
            $_data = number_format($aRow['total'],0,',','.');
        }
        $number_format_column = ['tblitems.minimum_quantity','product_quantity'];
        if(in_array($aColumns[$i],$number_format_column)) {
            $_data = number_format($_data,0,',','.');
        }
        $row[] = $_data;
    }
    // $options = '';
    // if(has_permission('items','','edit')){
    //     $options .= icon_btn('invoice_items/item/' . $aRow['id'], 'pencil-square-o', 'btn-default');
    // }
    // if(has_permission('items','','delete')){
    //     $options .= icon_btn('invoice_items/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    // }
    // $row[] = $options;

    $output['aaData'][] = $row;
}
