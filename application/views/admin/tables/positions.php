<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'positionid',
    'name',

);
$sIndexColumn = "positionid";
$sTable       = 'tblpositions';
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
        $row[] = $_data;
    }
    if ($aRow['creator'] == get_staff_user_id() || is_admin()) {
        $_data = '<a href="#" class="btn btn-default btn-icon" onclick="view_init_department(' . $aRow['positionid'] . '); return false;"><i class="fa fa-eye"></i></a>';
        $row[] =$_data.icon_btn('positions/delete_position/'. $aRow['positionid'] , 'remove', 'btn-danger delete-reminder');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
