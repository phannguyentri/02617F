<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
  'tbllog_sms_send.subject',
  'tbllog_sms_send.phone_number',
  'tblsms_templates.name',
  'tbllog_sms_send.message'
);
$sIndexColumn = "id";
$sTable       = 'tbllog_sms_send';
$where        = array();
$join         = array(
  'LEFT JOIN tblsms_templates ON tblsms_templates.id=tbllog_sms_send.template_sms_id'
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(

));
$output       = $result['output'];
$rResult      = $result['rResult'];
// var_dump($rResult);die();


$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
       if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if ($aColumns[$i] == '1') {
            $_data=$j;
        }

        $row[] = $_data;
    }
    // $_data='';
    // if ($aRow['create_by'] == get_staff_user_id() || is_admin()) {
    //     if(isset($aRow['delivery_code']))
    //     {
    //         $_data .= icon_btn('deliveries/pdf/' . $aRow['id'].'?pdf=true&type=delivery', 'print', 'btn-default',array('target' => '_blank','data-toggle'=>'tooltip',
    //         'title'=>_l('print_delivery'),
    //         'data-placement'=>'top'));
    //     }


    //     if($aRow['delivery_status']!=2)
    //     {
    //         $_data .= '<a class="btn btn-info btn-icon" href="javacript:void(0)" onclick="var_status('.$aRow['delivery_status'].','.$aRow['id'].')"><i class="fa fa-check"></i></a>';

    //         $_data .= icon_btn('deliveries/delivery_detail/'. $aRow['id'] , 'edit', 'btn-default',array('data-toggle'=>'tooltip',
    //         'title'=>_l('edit'),
    //         'data-placement'=>'top'));
    //     }
    //     else
    //     {
    //         $_data .= icon_btn('deliveries/delivery_detail/'. $aRow['id'] , 'eye', 'btn-default',array('data-toggle'=>'tooltip',
    //         'title'=>_l('view'),
    //         'data-placement'=>'top'));
    //     }
    //     $row[] =$_data.icon_btn('deliveries/delete/'. $aRow['id'] , 'remove', 'btn-danger delete-remind',array('data-toggle'=>'tooltip',
    //         'title'=>_l('delete'),
    //         'data-placement'=>'top'));
    // } else {
    //     $row[] = '';
    // }
    //
    $options = '';
    $options .= icon_btn('#', 'pencil-square-o', 'btn-default', array(
        'data-toggle' => 'tooltip',
        'title'       => _l('Sửa'),
    ));
    $options .= icon_btn('#', 'remove', 'btn-danger', array(
        'data-toggle' => 'tooltip',
        'title'       => _l('Xóa'),
    ));

    $row[]              = $options;
    $output['aaData'][] = $row;
}

