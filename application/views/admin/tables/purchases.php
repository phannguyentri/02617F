<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    '1',
    'code',
    'date',
    'fullname',
    'name',
    'reason',
    'status'

);
$sIndexColumn = "id";
$sTable       = 'tblpurchase_plan';
$where        = array(
//    'AND id_lead="' . $rel_id . '"'
);
$join         = array(
    'LEFT JOIN tblstaff  ON tblstaff.staffid=tblpurchase_plan.create_by'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'id',
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
        if ($aColumns[$i] == 'status') {
            $_data=format_purchase_status($aRow['status']);
        }
        if ($aColumns[$i] == '1') {
            $_data=$j;
        }
        if ($aColumns[$i] == 'date') {
            $_data=_d($aRow['date']);
        }
        $row[] = $_data;

    }
    if ($aRow['creator'] == get_staff_user_id() || is_admin()) {
        $_data = '<a href="'.admin_url('purchases/purchase/'.$aRow['id']).'" class="btn btn-default btn-icon" ><i class="fa fa-eye"></i></a>';
        $row[] =$_data.icon_btn('purchases/delete/'. $aRow['id'] , 'remove', 'btn-danger delete-reminders');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
