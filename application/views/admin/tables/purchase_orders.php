<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "1"=>"Đã được xác nhận",
    "0"=>"Chưa được xác nhận chọn để xác nhận"
);
$aColumns     = array(
    'tblorders.id',
    'tblorders.code',
    'tblorders.date_create',
    'tblorders.id_user_create',
    'tblorders.user_head_id',
    'IF(tblorders.user_head_id=0,0,1)',
);

$sIndexColumn = "id";
$sTable       = 'tblorders';

$where = array();
$order_by = 'tblorders.id ASC';

$join             = array(
    );
$additionalSelect = array(
    'converted',
    'isLock',
    '(select tblstaff.fullname from tblstaff where tblstaff.staffid = tblorders.id_user_create) as creator',
    );
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect, $order_by);

$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    $approval = false;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        

        $array_link = ['tblorders.id', 'tblorders.name', 'tblorders.code'];
        if(in_array($aColumns[$i],$array_link)){
            $_data = '<a href="'.admin_url('purchase_orders/view/').$aRow['tblorders.id'].'">'.$_data.'</a>';
        }
        $array_user = ['tblorders.id_user_create', 'tblorders.user_head_id'];
        if(in_array($aColumns[$i],$array_user)) {
            if($_data != '0') {
                $_data = '<a href="' . admin_url('profile/' . $_data) . '">' . staff_profile_image($_data, array(
                        'staff-profile-image-small mright5'
                    ), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => $aRow["creator"],
                    )) . '</a>';
            }
            else {
                $_data = "";
            }
        }
        if($aColumns[$i] == 'IF(tblorders.user_head_id=0,0,1)') {
            if($aRow['IF(tblorders.user_head_id=0,0,1)']==0)
            {
                $type='warning';
                $status='Chưa duyệt';
            }
            else
            {
                $type='success';
                $status='Đã duyệt';
            }
            $_data = '<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['IF(tblorders.user_head_id=0,0,1)'].'">' . $status.'';
            if(has_permission('invoices', '', 'view') && has_permission('invoices', '', 'view_own'))
            {
                if($aRow['IF(tblorders.user_head_id=0,0,1)']!=1){
                    $_data.='<a href="javacript:void(0)" onclick="var_status('.$aRow['IF(tblorders.user_head_id=0,0,1)'].','.$aRow['tblorders.id'].')">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['tblpurchase_suggested.status']]) . '"></i>';
                }
                else
                {
                    $_data.='<a href="javacript:void(0)">
                    <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['tblpurchase_suggested.status']]) . '"></i>';
                }
            }
            else {
                if($aRow['IF(tblorders.user_head_id=0,0,1)']==0) {
                    $_data .= '<a href="javacript:void(0)" onclick="var_status(' . $aRow['IF(tblorders.user_head_id=0,0,1)'] . ',' . $aRow['tblorders.id'] . ')">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['tblpurchase_suggested.status']]) . '"></i>';
                }
                else
                {
                    $_data .= '<a href="javacript:void(0)">
                    <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['tblpurchase_suggested.status']]) . '"></i>';
                }
            }
            $_data.='
                </a>
            </span>';
            
        }
        $row[] = $_data;
    }
    $options = '';
    if(is_admin() && $aRow['IF(tblorders.user_head_id=0,0,1)']==1 && $aRow['converted']==0 && $aRow['isLock'] == 1)
    {
        $options=icon_btn('purchase_orders/convert_to_contract/'. $aRow['tblorders.id'] , 'exchange', 'btn-default');
    }
    else if(is_admin() && $aRow['IF(tblorders.user_head_id=0,0,1)']==1 && $aRow['converted']==0 && $aRow['isLock'] == 0) {
        $options=icon_btn('purchase_orders/lock/'. $aRow['tblorders.id'] , 'lock', 'btn-default');
    }
    $options .= icon_btn('purchase_orders/detail_pdf/'. $aRow['tblorders.id'] .'?pdf=true' , 'file-pdf-o', 'btn-default', array('target'=>'_blank'));
    $options .= icon_btn('purchase_orders/detail_pdf/'. $aRow['tblorders.id'] .'?print=true' , 'print', 'btn-default', array('target'=>'_blank'));
    $options .= icon_btn('purchase_orders/detail_pdf/'. $aRow['tblorders.id'] , 'download', 'btn-default', array('target'=>'_blank'));
    $row[] = $options;

    $output['aaData'][] = $row;
}
