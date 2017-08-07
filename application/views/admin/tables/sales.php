<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "2"=>"Đơn đặt hàng",
    "1"=>"Đơn đặt hàng được xác nhận chọn để duyệt đơn đặt hàng",
    "0"=>"Đơn đặt hàng chưa được xác nhận chọn để xác nhận"
);

$aColumns     = array(
    '1',
    'code',
    'company',
    '(SELECT fullname FROM tblstaff WHERE create_by=tblstaff.staffid)',
    'status',
    'CONCAT((SELECT fullname FROM tblstaff  WHERE user_head_id=tblstaff.staffid),",",(SELECT fullname FROM tblstaff  WHERE user_admin_id=tblstaff.staffid)) as confirm',
    'date'

);
$sIndexColumn = "id";
$sTable       = 'tblsales';
$where        = array(
    // 'AND rel_type="'.$rel_type.'"',
);
$join         = array(
    'LEFT JOIN tblstaff  ON tblstaff.staffid=tblsales.create_by',
    'LEFT JOIN tblclients  ON tblclients.userid=tblsales.customer_id'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'id',
    'prefix',
    'export_status',
    'tblstaff.fullname',
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
        if ($aColumns[$i] == '1') {
            $_data=$j;
        }
        if ($aColumns[$i] == 'code') {
            $_data=$aRow['prefix'].$aRow['code'];
        }
        if ($aColumns[$i] == 'date') {
            $_data=_d($aRow['date']);
        }
        if ($aColumns[$i] == 'status') {
            $_data='<span class="inline-block label label-'.get_status_label($aRow['status']).'" task-status-table="'.$aRow['status'].'">' . format_status_sale($aRow['status'],false,true).'';
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
        $row[] = $_data;
    }
    $_data='';
    if ($aRow['create_by'] == get_staff_user_id() || is_admin()) {
        $_data .= icon_btn('sales/pdf/' . $aRow['id'].'?pdf=true', 'print', 'btn-default',array('target' => '_blank'));
        if($aRow['status']==2)
        {           
            if($aRow['export_status']!=1) 
            {
                //Tao Phieu xuat kho
                $_data .= icon_btn('exports/sale_output/'. $aRow['id'] , 'file-o');
            }

            // if($aRow['delivery_status']!=1) 
            // {
            //     //Tao Phieu Giao hang
            //     $_data .= icon_btn('sales/sale_delivery/'. $aRow['id'] , 'truck');
            // }            
            
        }
        

        if($aRow['status']!=2)
        {            
            $_data .= icon_btn('sales/sale_detail/'. $aRow['id'] , 'edit');
        }
        else
        {
            $_data .= icon_btn('sales/sale_detail/'. $aRow['id'] , 'eye');
        }       
        $row[] =$_data.icon_btn('sales/delete/'. $aRow['id'] , 'remove', 'btn-danger delete-remind');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}

