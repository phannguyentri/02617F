<?php
defined('BASEPATH') OR exit('No direct script access allowed');




$aColumns2    = array(
    'tblstaff.staffid',
    'tblstaff.firstname'


);
$sIndexColumn2 = "staffid";
$sTable2       = 'tblstaff';
$where2        = array();
$join2         = array();
$result2       = data_tables_init($aColumns2, $sIndexColumn2, $sTable2,$join2, $where2, array(
    'firstname',
    'lastname'
));
$rResult2      = $result2['rResult'];
$count_rResult2=count($rResult2);
//var_dump($rResult2);die();
//foreach($rResult2 as $rows)
//{
//    for ($i = 0; $i < count($rResult2); $i++) {
//    }
//}
//var_dump($i);
//die();









$aColumns     = array(
    'tblcall_logs.assigned',
    'tblcall_logs.date_call',
    'tblcall_logs.time_width',
    'tblcall_logs.checkout',
    'tblcall_logs.note'


);
$sIndexColumn = "id";
$sTable       = 'tblcall_logs';
$where        = array(
    'AND id_lead="' . $rel_id . '"'
);
$join         = array(
    'LEFT JOIN tblleads  ON tblleads.id =tblcall_logs.id_lead'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'tblleads.name',
    'tblcall_logs.time_report',
    'tblcall_logs.id_lead',
    'tblcall_logs.ID',
    'tblleads.phonenumber'
));
$output       = $result['output'];
$rResult      = $result['rResult'];


$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        $_data2="";
        if ($aColumns[$i] == 'tblcall_logs.assigned') {
            $staff= explode(",", $aRow['tblcall_logs.assigned']);
            foreach($rResult2 as $rows)
            {
                for($x=0;$x<count($staff);$x++){
                    if($staff[$x] == $rows['tblstaff.staffid'])
                    {
                        $_data2 =$_data2. '<div class="task-user" data-toggle="tooltip" data-title=" '.$rows['firstname']." ".$rows['lastname'].'" data-original-title="" title="">'.
                            '<a href="' . admin_url('staff/profile/' . $rows['tblstaff.staffid']) .'">' . staff_profile_image($rows['tblstaff.staffid'], array(
                                'staff-profile-image-small'
                            )) .'</a></div>';
                    }
                }
            }
            $_data = $_data2;

        }
        if ($aColumns[$i] == 'tblcall_logs.date_call') {
            $_data = _d($_data);
        } else if ($aColumns[$i] == 'tblcall_logs.checkout') {
            if ($_data == 1) {
                $_data = _l('call_logs_bool_yes');
            } else {
                $_data = _l('call_logs_bool_no');
            }
        } else if($aColumns[$i] == 'tblcall_logs.time_width'){
            $_data = _dt($_data);
        }
        $row[] = $_data;
    }
    if ($aRow['creator'] == get_staff_user_id() || is_admin()) {
//        <a href="#" class="btn btn-default btn-icon" onclick="init_lead(11);return false;"><i class="fa fa-eye"></i></a>
        $_data = '<a href="'.admin_url('calllogs/index/' . $aRow['ID']) .'" class="btn btn-default btn-icon" onclick="init_call_log_modal(' . $aRow['ID'] . ','.$aRow['id_lead'].'); return false;"><i class="fa fa-eye"></i></a>';
        $row[] =$_data.icon_btn('calllogs/delete_call_logs/'. $aRow['ID'] , 'remove', 'btn-danger delete-reminder');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
