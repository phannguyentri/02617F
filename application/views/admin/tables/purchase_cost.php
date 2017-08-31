<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "1"=>"Đã được xác nhận",
    "0"=>"Chưa được xác nhận chọn để xác nhận"
);
$aColumns     = array(
    'tblpurchase_costs.id',
    'tblpurchase_costs.code',
    'tblpurchase_costs.purchase_contract_id',
    'tblpurchase_costs.user_create',
    'tblpurchase_costs.date_created',
    'tblpurchase_costs.status',
    'tblpurchase_costs.user_head_id',
    'tblpurchase_costs.user_head_date',
);

$sIndexColumn = "id";
$sTable       = 'tblpurchase_costs';

$where = array();
$order_by = 'tblpurchase_costs.id ASC';

$join             = array(
    );
$additionalSelect = array(
    'CONCAT(user_head_id) as confirm_ids',
    '(select tblstaff.fullname from tblstaff where tblstaff.staffid = tblpurchase_costs.user_create) as creator',
    '(select tblstaff.fullname from tblstaff where tblstaff.staffid = tblpurchase_costs.user_head_id) as head_user',
    '(select tblpurchase_contracts.code from tblpurchase_contracts where tblpurchase_contracts.id = tblpurchase_costs.purchase_contract_id) as contract_code',
);
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect, $order_by);

$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    $approval = false;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        
        if ($aColumns[$i] == 'tblpurchase_costs.user_head_id') {
            $_data = $aRow[$aColumns[$i]];
            $confirms=array_unique(explode(',', $_data));
            $confirm_ids=array_unique(explode(',', $aRow['confirm_ids']));
            $_data            = '';
            $result = '';
            $as = 0;
            for ($x=0; $x < count($confirms); $x++) { 
                if($confirms[$x]!='' && $confirms[$x]!=0)
                {
                    $_data .= '<a href="' . admin_url('profile/' . $confirm_ids[$x]) . '">' . staff_profile_image($confirm_ids[$x], array(
                        'staff-profile-image-small mright5'
                    ), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => $aRow['head_user'],
                    )) . '</a>';
                }
            }
        }

        if($aColumns[$i] == 'tblpurchase_costs.status') {
            if($aRow['tblpurchase_costs.status']==0)
            {
                $type='warning';
                $status='Chưa duyệt';
            }
            else
            {
                $type='success';
                $status='Đã duyệt';
            }
            $_data = '<span class="inline-block label label-'.$type.'" task-status-table="'.$aRow['tblpurchase_costs.status'].'">' . $status.'';
            if(has_permission('invoices', '', 'view') && has_permission('invoices', '', 'view_own'))
            {
                if($aRow['tblpurchase_costs.status']!=1){
                    $_data.='<a href="javacript:void(0)" onclick="return change_status('.$aRow['tblpurchase_costs.id'].')">
                    <i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['tblpurchase_costs.status']]) . '"></i>                    
                    ';
                }
                else
                {
                    $_data.='<a href="javacript:void(0)">
                    <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['tblpurchase_costs.status']]) . '"></i>';
                }
            }
            else {
                $_data .= '<a href="javacript:void(0)">
                <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['tblpurchase_costs.status']]) . '"></i>';
            }
            $_data .= '
                </a>
                </span>';
        }

        if($aColumns[$i] == "tblpurchase_costs.purchase_contract_id"){
            $_data = $aRow["contract_code"];
        }
        $array_link = ['tblpurchase_costs.id', 'tblpurchase_costs.code'];
        if(in_array($aColumns[$i],$array_link)){
            $_data = '<a href="'.admin_url('purchase_cost/detail/').$aRow['tblpurchase_costs.id'].'">'.$_data.'</a>';
        }
        $array_user = ['tblpurchase_costs.user_create'];
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
        $row[] = $_data;
    }
    $options = '';
    if($aRow['tblpurchase_costs.status']==0)
    {
        $options .= icon_btn('purchase_cost/detail/'. $aRow['tblpurchase_costs.id'] , 'pencil', 'btn-default');
    }
    else
    {
        $options .= icon_btn('purchase_cost/detail/'. $aRow['tblpurchase_costs.id'] , 'eye', 'btn-default');
    }
    
    // $options .= icon_btn('purchase_cost/pdf/'. $aRow['tblpurchase_costs.id'] .'?pdf=true' , 'file-pdf-o', 'btn-default', array('target'=>'_blank'));
    // $options .= icon_btn('purchase_cost/pdf/'. $aRow['tblpurchase_costs.id'] .'?print=true' , 'print', 'btn-default', array('target'=>'_blank'));
    // $options .= icon_btn('purchase_cost/pdf/'. $aRow['tblpurchase_costs.id'] , 'download', 'btn-default', array('target'=>'_blank'));
    $row[] = $options;

    $output['aaData'][] = $row;
}