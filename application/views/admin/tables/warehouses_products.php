<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    '1',
    'warehouse',
    '(SELECT name FROM tbl_kindof_warehouse WHERE warehouse_id=id )',
    'tblshipments.shipment',
    'tblracks.rack',
    '(SELECT name FROM tblitems WHERE tblitems.id=product_id)',
    'tblwarehouses_products.product_quantity'
);
$sIndexColumn = "id";
$sTable       = 'tblwarehouses_products';
$where        = array(
//    'AND id_lead="' . $rel_id . '"'
);
$join         = array(
    'LEFT JOIN tblwarehouses  ON tblwarehouses.warehouseid=tblwarehouses_products.warehouse_id',
    'LEFT JOIN tblshipments  ON tblshipments.shipmentid=tblwarehouses_products.shipment',
    'LEFT JOIN tblracks  ON tblracks.rackid=tblwarehouses_products.rack',
    // 'LEFT JOIN tblitems  ON tblitems.id=tblwarehouses_products.product_id',
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'id'
));
$output       = $result['output'];
$rResult      = $result['rResult'];
//var_dump($rResult);die();


$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'tblroles.id_role') {
            $_data=$aRow['tblroles.name'];
        }
        if ($aColumns[$i] == '1') {
            $_data=$j;
        }
        if($aColumns[$i] == '(select name from tbl_kindof_warehouse where tbl_kindof_warehouse.id=tblwarehouses_products.id) as kindof_ware_house') {
            $_data = $aRow['kindof_ware_house'];
        }
        $row[] = $_data;
    }
    if (is_admin()) {
        $_data = '<a href="#" class="btn btn-default btn-icon" onclick="view_init_adjustment(' . $aRow['id'] . '); return false;"><i class="fa fa-edit"></i></a>';
        $row[] =$_data.icon_btn('imports/delete_warehouses_adjustment/'. $aRow['id'] , 'remove', 'btn-danger delete-remind');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
