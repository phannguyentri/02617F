<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Sẽ lọc ra tất cả các kho để tạo cột cho danh sách sản phẩm
 */
$warehouses = $this->_instance->warehouse_model->getWarehouses();

$aColumns     = array(
    'tblitems.id',
    'tblitems.code',
    'tblitems.name',
    );
foreach($warehouses as $warehouse) {
    $aColumns[] = '(select tblwarehouses_products.product_quantity as F_'.$warehouse['warehouseid'].'_'.$warehouse['warehouse_can_export'].' from tblwarehouses_products where tblwarehouses_products.product_id=tblitems.id and tblwarehouses_products.warehouse_id=' .  $warehouse['warehouseid'] . ') as T'.$warehouse['warehouseid'].$warehouse['warehouse_can_export'];
}
// var_dump($aColumns);die;
$sIndexColumn = "id";
$sTable       = 'tblitems';
$where = array(

);
$join             = array(
    
    );
$additionalSelect = array(

    );
if($this->_instance->input->post()) {
    
}

$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect);
$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = array();
    $sum_row = 0;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if($i>2) {
            $_data = $aRow["T".$warehouses[$i-3]['warehouseid'].$warehouses[$i-3]['warehouse_can_export']];
            if($_data == "")
                $_data = 0;
                   
            $flag = $warehouses[$i-3]['warehouse_can_export'];
            if($flag == 1 && $_data != '')
                $sum_row += $_data;
        }
        
        $row[] = $_data;
    }
    $row[] = $sum_row;
    $options = '';
    
   $row[] = $options;

   $output['aaData'][] = $row;
}