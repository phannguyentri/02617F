<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "2"=>"Đề xuất mua",
    "1"=>"Đề xuất mua được xác nhận chọn để duyệt Đề xuất mua",
    "0"=>"Đề xuất mua chưa được xác nhận chọn để xác nhận"
);
$confirm=6;
$aColumns     = array(
    'tblpurchase_suggested.id',
    'tblpurchase_suggested.code',
    'tblpurchase_suggested.name',
    'tblstaff.fullname',
    'tblpurchase_suggested.status',
    '(select count(*) from tblpurchase_suggested_details where tblpurchase_suggested.id = tblpurchase_suggested_details.purchase_suggested_id)',
    'CONCAT((SELECT fullname FROM tblstaff  WHERE user_head_id=tblstaff.staffid),",",(SELECT fullname FROM tblstaff  WHERE user_admin_id=tblstaff.staffid))' ,   
    'tblpurchase_suggested.date',
);

$sIndexColumn = "id";
$sTable       = 'tblpurchase_suggested';

$where = array();
$order_by = 'tblpurchase_suggested.id ASC';

$join             = array(
    'LEFT JOIN tblstaff  ON tblstaff.staffid=tblpurchase_suggested.create_by'
    );
$additionalSelect = array(
    'CONCAT(user_head_id,",",user_admin_id) as confirm_ids'
    );
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect, $order_by);

$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    $approval = false;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'tblpurchase_suggested.date') {
            $_data=_d($aRow['tblpurchase_suggested.date']);
        }
        if ($i==$confirm) {
            $_data = $aRow[$aColumns[$i]];
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

        $array_link = ['tblpurchase_suggested.id', 'tblpurchase_suggested.name', 'tblpurchase_suggested.code'];
        if(in_array($aColumns[$i],$array_link)){
            $_data = '<a href="'.admin_url('purchase_suggested/detail/').$aRow['tblpurchase_suggested.id'].'">'.$_data.'</a>';
        }
        if($aColumns[$i] == 'tblpurchase_suggested.code') {
            $_data = '<a href="'.admin_url('purchase_suggested/detail/').$aRow['tblpurchase_suggested.id'].'">'.$aRow['tblpurchase_suggested.code'].'</a>';
        }
        if($aColumns[$i] == 'tblpurchase_suggested.status') {
            if($aRow['tblpurchase_suggested.status']==0)
                {
                    $type='warning';
                    $status='Chưa duyệt';
                }
                elseif($aRow['tblpurchase_suggested.status']==1)
                {
                    $type='info';
                    $status='Đã xác nhận';
                }
                else
                {
                    $type='success';
                    $status='Đã duyệt';
                }
            $_data='<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tblpurchase_suggested.status'].'">' . $status.'';
            if(has_permission('invoices', '', 'view') && has_permission('invoices', '', 'view_own'))
            {
                if($aRow['tblpurchase_suggested.status']!=2){
                    $_data.='<a href="javacript:void(0)" onclick="var_status('.$aRow['tblpurchase_suggested.status'].','.$aRow['tblpurchase_suggested.id'].')">';
                }
                else
                {
                    $_data.='<a href="javacript:void(0)">';
                }
            }
            else {
                if($aRow['tblpurchase_suggested.status']==0) {
                    $_data .= '<a href="javacript:void(0)" onclick="var_status(' . $aRow['tblpurchase_suggested.status'] . ',' . $aRow['tblpurchase_suggested.id'] . ')">';
                }
                else
                {
                    $_data .= '<a href="javacript:void(0)">';
                }
            }
                $_data.='<i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['tblpurchase_suggested.status']]) . '"></i>
                    </a>
                </span>';
        }
        $row[] = $_data;
    }
    $options = '';
    if(is_admin() && $aRow['tblpurchase_suggested.status']==2 && $aRow['converted']==0)
    {
        $options=icon_btn('purchase_orders/convert/'. $aRow['tblpurchase_suggested.id'] , 'exchange', 'btn-default');
    }
    // if(has_permission('items','','edit')){
        $options .= icon_btn('purchase_suggested/detail_pdf/' . $aRow['tblpurchase_suggested.id'].'?pdf=true', 'print', 'btn-default',array('target' => '_blank'));
        if(!$approval)
            $options .= icon_btn('purchase_suggested/detail/' . $aRow['tblpurchase_suggested.id'], 'pencil-square-o', 'btn-default');

    // }
    // if(has_permission('items','','delete')){
       $options .= icon_btn('purchase_suggested/delete/' . $aRow['tblpurchase_suggested.id'], 'remove', 'btn-danger _delete');
//    }
   $row[] = $options;

   $output['aaData'][] = $row;
}
