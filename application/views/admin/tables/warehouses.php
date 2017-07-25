<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'warehouseid',
    'code',
    'warehouse',
    'address',
    'phone',
    '(select name from tbl_kindof_warehouse a where tblwarehouses.kindof_warehouse = a.id) as kind_of_warehouse',
);
$sIndexColumn = "warehouseid";
$sTable       = 'tblwarehouses';
$where        = array(
//    'AND id_lead="' . $rel_id . '"'
);
$join         = array(
    // 'LEFT JOIN tblroles  ON tblroles.roleid=tbldepartment.id_role'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    // 'tblroles.name',
    // 'tblroles.roleid'
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
        if ($aColumns[$i] == '(select name from tbl_kindof_warehouse a where tblwarehouses.kindof_warehouse = a.id) as kind_of_warehouse') {
            $_data = $aRow['kind_of_warehouse'];
        }
        $row[] = $_data;
    }
    if ($aRow['creator'] == get_staff_user_id() || is_admin()) {
        $_data = '<a href="#" class="btn btn-default btn-icon" onclick="view_init_department(' . $aRow['warehouseid'] . '); return false;"><i class="fa fa-pencil"></i> Sửa</a>';
        $_data.= '<a href="#" class="btn btn-default btn-icon" onclick="view_detail(' . $aRow['warehouseid'] . '); return false;"><i class="fa fa-eye"></i> Chi tiết</a>';

        $_data.= icon_btn('warehouses/delete_warehouse/'. $aRow['warehouseid'] , 'remove', 'btn-danger delete-reminder');
        
        
        $row[] = $_data;


    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
