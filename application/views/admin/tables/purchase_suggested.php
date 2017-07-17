<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
    'tblpurchase_suggested.id',
    'tblpurchase_suggested.code',
    'tblpurchase_suggested.name',
    'tblpurchase_suggested.user_head_id',
    'tblpurchase_suggested.user_admin_id',
    'tblpurchase_suggested.status',
    '(select count(*) from tblpurchase_suggested_details where tblpurchase_suggested.id = tblpurchase_suggested_details.purchase_suggested_id)',
    'tblpurchase_suggested.date',
);

$sIndexColumn = "id";
$sTable       = 'tblpurchase_suggested';

$where = array();
$order_by = '';

$join             = array(

    );
$additionalSelect = array(
    );
// print_r($join);
// exit();
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect, $order_by);

$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    $approval = false;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        $array_fields = ['tblpurchase_suggested.user_head_id', 'tblpurchase_suggested.user_admin_id'];
        if(in_array($aColumns[$i], $array_fields)) {
            $_data = 'Chưa duyệt';
        }

        $array_link = ['tblpurchase_suggested.id', 'tblpurchase_suggested.name', 'tblpurchase_suggested.code'];
        if(in_array($aColumns[$i],$array_link)){
            $_data = '<a href="'.admin_url('purchase_suggested/detail/').$aRow['tblpurchase_suggested.id'].'">'.$_data.'</a>';
        }
        if($aColumns[$i] == 'tblpurchase_suggested.status') {
            if($_data == 0) {
                $_data = "Chưa được duyệt hết";
            }
            else {
                $_data = "Đã duyệt";
                $approval = true;
            }
        }
        $row[] = $_data;
    }
    $options = '';
    // if(has_permission('items','','edit')){
        $options .= icon_btn('purchase_suggested/detail_pdf/' . $aRow['tblpurchase_suggested.id'], 'file-pdf-o', 'btn-default');
        if(!$approval)
            $options .= icon_btn('purchase_suggested/detail/' . $aRow['tblpurchase_suggested.id'], 'pencil-square-o', 'btn-default');

    // }
    // if(has_permission('items','','delete')){
       $options .= icon_btn('purchase_suggested/delete/' . $aRow['tblpurchase_suggested.id'], 'remove', 'btn-danger _delete');
//    }
   $row[] = $options;

   $output['aaData'][] = $row;
}
