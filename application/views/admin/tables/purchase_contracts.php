<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$plan_status=array(
    "1"=>"Đã được xác nhận",
    "0"=>"Chưa được xác nhận chọn để xác nhận"
);
$aColumns     = array(
    'tblpurchase_contracts.id',
    'tblpurchase_contracts.code',
    'tblpurchase_contracts.id_order',
    'tblpurchase_contracts.id_user_create',
    'tblpurchase_contracts.date_create',
);

$sIndexColumn = "id";
$sTable       = 'tblpurchase_contracts';

$where = array();
$order_by = 'tblpurchase_contracts.id ASC';

$join             = array(
    );
$additionalSelect = array(
    '(select tblstaff.fullname from tblstaff where tblstaff.staffid = tblpurchase_contracts.id_user_create) as creator',
    '(select tblorders.code from tblorders where tblorders.id = tblpurchase_contracts.id_order) as order_code',
);
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect, $order_by);

$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    $approval = false;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        
        if($aColumns[$i] == "tblpurchase_contracts.id_order"){
            $_data = $aRow["order_code"];
        }
        $array_link = ['tblpurchase_contracts.id', 'tblpurchase_contracts.code'];
        if(in_array($aColumns[$i],$array_link)){
            $_data = '<a href="'.admin_url('purchase_contracts/view/').$aRow['tblpurchase_contracts.id'].'">'.$_data.'</a>';
        }
        $array_user = ['tblpurchase_contracts.id_user_create'];
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
    $options .= icon_btn('purchase_contracts/pdf/'. $aRow['tblpurchase_contracts.id'] .'?pdf=true' , 'file-pdf-o', 'btn-default', array('target'=>'_blank'));
    $options .= icon_btn('purchase_contracts/pdf/'. $aRow['tblpurchase_contracts.id'] .'?print=true' , 'print', 'btn-default', array('target'=>'_blank'));
    $options .= icon_btn('purchase_contracts/pdf/'. $aRow['tblpurchase_contracts.id'] , 'download', 'btn-default', array('target'=>'_blank'));
    $row[] = $options;

    $output['aaData'][] = $row;
}
