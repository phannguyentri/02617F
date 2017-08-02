<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
    'tblitem_price_buy_history.date',
    'tblitem_price_buy_history.price',
    'tblitem_price_buy_history.new_price',
    );

$sIndexColumn = "id";
$sTable       = 'tblitems';

$where = array();
$order_by = '';
if(isset($rel_id) && is_numeric($rel_id)) {
    $where[] = "and tblitem_price_buy_history.item_id=".$rel_id;
}

$join             = array(
    'RIGHT JOIN (select tblitem_price_buy_history.* from tblitem_price_buy_history where tblitem_price_buy_history.item_id='.$rel_id.') as tblitem_price_buy_history on tblitem_price_buy_history.item_id = tblitems.id',
    );
$additionalSelect = array(
    'tblitems.id',
    'tblitems.name',
    'tblitem_price_buy_history.price',
    'tblitem_price_buy_history.date',
    );
    
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, $where, $additionalSelect, $order_by);

$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        $format_number_column = ['tblitem_price_buy_history.price','tblitem_price_buy_history.new_price'];
        if(in_array($aColumns[$i], $format_number_column)) {
            $_data = number_format($_data,0,',','.');
        }
        $row[] = $_data;
    }
       
   $output['aaData'][] = $row;
}
