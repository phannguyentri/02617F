<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "2"=>"Phiếu giao hàng đã giao",
    "1"=>"Phiếu giao hàng đang hàng",
    "0"=>"Phiếu giao hàng chưa giao"
);

$aColumns     = array(
    '1',
    'code',
    'rel_code',
    'company',
    '(SELECT fullname FROM tblstaff WHERE create_by=tblstaff.staffid)',
    'delivery_status',
    // 'status',
    // 'CONCAT((SELECT fullname FROM tblstaff  WHERE user_head_id=tblstaff.staffid),",",(SELECT fullname FROM tblstaff  WHERE user_admin_id=tblstaff.staffid)) as confirm',
    'delivery_date'
);
$sIndexColumn = "id";
$sTable       = 'tblexports';
$where        = array(
    // 'AND rel_type="'.$rel_type.'"',
);
if(!empty($sale_id))
{
    $where[]='AND rel_id="'.$sale_id.'"';
}
$join         = array(
    'LEFT JOIN tblstaff  ON tblstaff.staffid=tblexports.create_by',
    'LEFT JOIN tblclients  ON tblclients.userid=tblexports.customer_id'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'id',
    'rel_id',
    'prefix',
    'delivery_code',
    'tblstaff.fullname',
    // 'CONCAT(user_head_id,",",user_admin_id) as confirm_ids'
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
        if ($aColumns[$i] == 'rel_code') {
            $_data='<a href="'.admin_url('sales/sale_detail/'.$aRow['rel_id']).'">'.$aRow['rel_code'].'</a>';
        }
        if ($aColumns[$i] == 'code') {
            $_data=$aRow['prefix'].$aRow['code'];
        }
        if ($aColumns[$i] == 'delivery_date') {
            $_data=_d($aRow['delivery_date']);
        }
        if ($aColumns[$i] == 'delivery_status') {
            $_data='<span class="inline-block label label-'.get_status_label($aRow['delivery_status']).'" task-status-table="'.$aRow['delivery_status'].'">' . format_status_delivery($aRow['delivery_status'],false,true).'';
            if(has_permission('invoices', '', 'view') && has_permission('invoices', '', 'view_own'))
            {
                if($aRow['delivery_status']!=2){
                    $_data.='<a href="javacript:void(0)" onclick="var_status('.$aRow['delivery_status'].','.$aRow['id'].')">';
                }
                else
                {
                    $_data.='<a href="javacript:void(0)">';
                }
            }
            else {
                if($aRow['delivery_status']==0) {
                    $_data .= '<a href="javacript:void(0)" onclick="var_status(' . $aRow['delivery_status'] . ',' . $aRow['id'] . ')">';
                }
                else
                {
                    $_data .= '<a href="javacript:void(0)">';
                }
            }
                $_data.='<i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['delivery_status']]) . '"></i>
                    </a>
                </span>';
        }
        // if ($aColumns[$i] == 'delivery_status'){
        //     $_data='<span class="inline-block label label-success" task-status-table="2">Đã duyệt<a href="javacript:void(0)">
        //             <i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="Đề xuất mua"></i>
        //             </a>
        //         </span>';
        // }
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
        if(isset($aRow['delivery_code']))
        {            
            $_data .= icon_btn('deliveries/pdf/' . $aRow['id'].'?pdf=true&type=delivery', 'print', 'btn-default',array('target' => '_blank','data-toggle'=>'tooltip',
            'title'=>_l('print_delivery'),
            'data-placement'=>'top'));
        } 


        if($aRow['delivery_status']!=2)
        {  
            $_data .= '<a class="btn btn-info btn-icon" href="javacript:void(0)" onclick="var_status('.$aRow['delivery_status'].','.$aRow['id'].')"><i class="fa fa-check"></i></a>';  
                    
            $_data .= icon_btn('deliveries/delivery_detail/'. $aRow['id'] , 'edit', 'btn-default',array('data-toggle'=>'tooltip',
            'title'=>_l('edit'),
            'data-placement'=>'top'));
        }  
        else
        { 
            $_data .= icon_btn('deliveries/delivery_detail/'. $aRow['id'] , 'eye', 'btn-default',array('data-toggle'=>'tooltip',
            'title'=>_l('view'),
            'data-placement'=>'top'));
        }      
        $row[] =$_data.icon_btn('deliveries/delete/'. $aRow['id'] , 'remove', 'btn-danger delete-remind',array('data-toggle'=>'tooltip',
            'title'=>_l('delete'),
            'data-placement'=>'top'));
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}

