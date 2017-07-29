<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
    'tblorders.id',
    'tblorders.code',
    'tblorders.date_create',
    'tblorders.id_user_create',
);

$sIndexColumn = "id";
$sTable       = 'tblorders';

$where = array();
$order_by = 'tblorders.id ASC';

$join             = array(
    );
$additionalSelect = array(

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
                
        $row[] = $_data;
    }
    $options = '';
    if(is_admin() && $aRow['tblpurchase_suggested.status']==2 && $aRow['converted']==0)
    {
        $options=icon_btn('purchase_suggested/convert_to_order/'. $aRow['tblpurchase_suggested.id'] , 'exchange', 'btn-default');
    }
    $options .= icon_btn('purchase_orders/detail_pdf/'. $aRow['tblorders.id'] .'?pdf=true' , 'print', 'btn-default', array('target'=>'_blank'));
   $row[] = $options;

   $output['aaData'][] = $row;
}
