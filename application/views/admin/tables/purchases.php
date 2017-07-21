<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "1"=>"Kế hoạch mua",
    "2"=>"Kế hoạch mua được xác nhận chọn để duyệt kế hoạch mua",
    "0"=>"Kế hoạch mua chưa được xác nhận chọn để xác nhận"
);

$aColumns     = array(
    '1',
    'code',
    'date',
    'tblstaff.fullname',
    'name',
    'reason',
    'status',
    'CONCAT((SELECT fullname FROM tblstaff  WHERE user_head_id=tblstaff.staffid),",",(SELECT fullname FROM tblstaff  WHERE user_admin_id=tblstaff.staffid)) as confirm'

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
    'id','converted',
    'CONCAT(user_head_id,",",user_admin_id) as confirm_ids'
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
        if ($aColumns[$i] == 'code') {
            $_data=get_option('prefix_purchase_plan').$aRow['code'];
        }
        if ($aColumns[$i] == 'status') {
            $_data='<span class="inline-block label label-'.get_status_label($aRow['status']).'" task-status-table="'.$aRow['status'].'">' . format_status_purchase_plan($aRow['status'],false,true).'';
            if(has_permission('invoices', '', 'view') && has_permission('invoices', '', 'view_own'))
            {
                if($aRow['status']!=2){
                    $_data.='<a href="javacript:void(0)" onclick="var_status('.$aRow['status'].','.$aRow['id'].')">';
                }
                else
                {
                    $_data.='<a href="javacript:void(0)">';
                }
            }
            else {
                if($aRow['status']==0) {
                    $_data .= '<a href="javacript:void(0)" onclick="var_status(' . $aRow['status'] . ',' . $aRow['id'] . ')">';
                }
                else
                {
                    $_data .= '<a href="javacript:void(0)">';
                }
            }
                $_data.='<i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['status']]) . '"></i>
                    </a>
                </span>';
        }
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow['confirm'];
            $confirms=array_unique(explode(',', $_data));
            $confirm_ids=array_unique(explode(',', $aRow['confirm_ids']));
            $_data            = '';
            $result = '';
            $as = 0;
            for ($x=0; $x < count($confirms); $x++) { 
                if($confirms[$x]!='')
                {
                    $_data .= '<a href="' . admin_url('profile/' . $confirm_ids[$x]) . '">' . staff_profile_image($confirm_ids[$x], array(
                        'staff-profile-image-small mright5'
                    ), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => $confirms[$x]
                    )) . '</a>';
                }
            }
        }
        if ($aColumns[$i] == '1') {
            $_data=$j;
        }
        if ($aColumns[$i] == 'date') {
            $_data=_d($aRow['date']);
        }

        $row[] = $_data;

    }
    if(is_admin() && $aRow['status']==2 && $aRow['converted']==0)
    {
        $_data=icon_btn('purchases/convert_to_suggested/'. $aRow['id'] , 'exchange', 'btn-default');
    }
    else
    {
        $_data='';
    }
    $_data.=icon_btn('purchases/pdf/' . $aRow['id'].'?pdf=true', 'print', 'btn-default',array('target' => '_blank'));
    if ($aRow['create_by'] == get_staff_user_id() || is_admin()) {
        if($aRow['status']!=2)
        {

            $_data .= '<a href="'.admin_url('purchases/purchase/'.$aRow['id']).'" class="btn btn-default btn-icon" ><i class="fa fa-eye"></i></a>';
        }
        $row[] =$_data.icon_btn('purchases/delete/'. $aRow['id'] , 'remove', 'btn-danger delete-reminders');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
