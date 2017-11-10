<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "3"=>"Phiếu báo giá không được phê duyệt",
    "2"=>"Phiếu báo giá",
    "1"=>"Phiếu báo giá được xác nhận chọn để duyệt đơn đặt hàng",
    "0"=>"Phiếu báo giá chưa được xác nhận chọn để xác nhận"
);

$aColumns     = array(
    '1',
    'code',
    'company',
    '(SELECT fullname FROM tblstaff WHERE create_by=tblstaff.staffid)',
    
    // 'CONCAT((SELECT fullname FROM tblstaff  WHERE user_head_id=tblstaff.staffid),",",(SELECT fullname FROM tblstaff  WHERE user_admin_id=tblstaff.staffid)) as confirm',

    'date',
    'incurred',
    'total',
    'total_vat',
    'total + total_vat',

    // 'reason',
);
$sIndexColumn = "id";
$sTable       = 'tblquotes';
$where        = array(
    // 'AND rel_type="'.$rel_type.'"',
);
if(!empty($sale_id))
{
    $where[]='AND rel_id="'.$sale_id.'"';
}
if($this->_instance->input->post()) {
    $filter_status = $this->_instance->input->post('filterStatus');
    $date_from = $this->_instance->input->post('report-from');
    $date_to = $this->_instance->input->post('report-to');
    $client_id = $this->_instance->input->post('client_id');
    $user_id = $this->_instance->input->post('user_id');
    if(is_numeric($filter_status)) {
        if($filter_status == 2)
            array_push($where, 'AND status='.$filter_status);
        elseif($filter_status == 4)
            array_push($where, 'AND export_status=1 AND status=2');
        elseif($filter_status == 3)
            array_push($where, 'AND export_status=0');
        elseif($filter_status == 5)
            array_push($where, 'AND status=3');        
        else {
            array_push($where, 'AND status<>2 AND export_status=0');
        }
    }
    if($user_id){
        array_push($where, 'AND create_by='.$user_id);
    }
    if($client_id){
        array_push($where, 'AND customer_id='.$client_id);
    }
    if($date_from && $date_to){
        array_push($where, 'AND tblquotes.date BETWEEN "' . to_sql_date($date_from) . '" and "' . to_sql_date($date_to) . '"');
    }
}

$join         = array(
    'LEFT JOIN tblstaff  ON tblstaff.staffid=tblquotes.create_by',
    'LEFT JOIN tblclients  ON tblclients.userid=tblquotes.customer_id'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
    'id',
    'prefix',
    'status',
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
        if ($aColumns[$i] == 'rel_code') {
            $_data='<a href="'.admin_url('sales/sale_detail/'.$aRow['rel_id']).'">'.$aRow['rel_code'].'</a>';
        }
        if ($aColumns[$i] == 'code') {
            $_data='<a onclick="init_qoute_modal_data('.$aRow['id'].');return false;" href="#">'.$aRow['prefix'].$aRow['code'].'</a>';
        }
        if ($aColumns[$i] == 'date') {
            $_data=_d($aRow['date']);
        }
        if($aColumns[$i] == 'incurred') {
            $_data = number_format($aRow['incurred'],0,',','.');
        }

        if($aColumns[$i] == 'total + total_vat') {
            $_data = number_format($aRow['total + total_vat'],0,',','.');
        }

        if($aColumns[$i] == 'total_vat') {
            $_data = number_format($aRow['total_vat'],0,',','.');
        }

        if($aColumns[$i] == 'total') {
            $_data = number_format($aRow['total'],0,',','.');
        }
       

        // if ($aColumns[$i] == 'status') {
        //     $_data='<span class="inline-block label label-'.get_status_label($aRow['status']).'" task-status-table="'.$aRow['status'].'">' . format_status_quote($aRow['status'],false,true).'';
        //     if(has_permission('invoices', '', 'view') && has_permission('invoices', '', 'view_own'))
        //     {
        //         if($aRow['status']!=2){
        //             $_data.='<a href="javacript:void(0)" onclick="var_status('.$aRow['status'].','.$aRow['id'].')">';
        //         }
        //         else
        //         {
        //             $_data.='<a href="javacript:void(0)">';
        //         }

        //         if($aRow['status']==3){
        //            $_data.='<a href="javacript:void(0)">';
        //         }
                
        //     }
        //     else {
        //         if($aRow['status']==0) {
        //             $_data .= '<a href="javacript:void(0)" onclick="var_status(' . $aRow['status'] . ',' . $aRow['id'] . ')">';
        //         }
                
        //         else
        //         {
        //             $_data .= '<a href="javacript:void(0)">';
        //         }
        //     }
        //         $_data.='<i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l( $plan_status[$aRow['status']]) . '"></i>
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
        // $_data .= icon_btn('quotes/pdf/' . $aRow['id'].'?pdf=true', 'print', 'btn-default',array('target' => '_blank','data-toggle'=>'tooltip',
        //     'title'=>_l('dt_button_print'),
        //     'data-placement'=>'top'));
        if($aRow['status']==2 && $aRow['export_status']!=1)
        {           
            //Tao Hop Dong
            $_data .= icon_btn('quotes/contract_output/'. $aRow['id'] , 'exchange','btn-default',array(
            'data-toggle'=>'tooltip',
            'title'=>_l('create_contract'),
            'data-placement'=>'top'
            ));  
        }

        // if($aRow['status']==0 && $aRow['export_status']==0)
        // {           
        //     //Tao Hop Dong
        //     $_data .= icon_btn('quotes/cancel_quote/'. $aRow['id'] , 'ban','btn-default cancel-remind',array(
        //     'data-toggle'=>'tooltip',
        //     'title'=>_l('Không đồng ý phê duyệt'),
        //     'data-placement'=>'top'
        //     ));           
            
        // }
          
        $_data .=  icon_btn('#', 'eye','btn-default',array('onclick'=>'init_qoute('.$aRow['id'].');return false;'));
        // $options .= icon_btn('invoice_it


        // icon_btn('quotes/quote_detail/'. $aRow['id'] , 'edit','btn-default',array('data-toggle'=>'tooltip',
        // 'title'=>_l('edit'),
        // 'data-placement'=>'top'));     
        $row[] =$_data.icon_btn('quotes/delete/'. $aRow['id'] , 'remove', 'btn-danger delete-remind',array('data-toggle'=>'tooltip',
        'title'=>_l('delete'),
        'data-placement'=>'top'));
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}

