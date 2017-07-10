<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
    'tblitems.id',
    'tblitems.avatar',
    'tblitems.code',
    'tblitems.name',
    'tblitems.short_name',
    'tblitems.description',
    'tblitems.price',
    'tblitems.unit',
    'tblitems_groups.name',
    'tblitems.minimum_quantity',
    'tblitems.maximum_quantity',
    );
// var_dump($aColumns);die;
$sIndexColumn = "id";
$sTable       = 'tblitems';


$join             = array(
    'LEFT JOIN tbltaxes ON tbltaxes.id = tblitems.tax',     
    'LEFT JOIN tblitems_groups ON tblitems_groups.id = tblitems.group_id',
    'LEFT JOIN district ON district.districtid = tblitems.district_id'
    );
$additionalSelect = array(
    'tblitems.id',
    'tbltaxes.name', 
    'taxrate',
    'group_id',
    );
$result           = data_tables_init($aColumns, $sIndexColumn, $sTable ,$join, array(), $additionalSelect);
$output           = $result['output'];
$rResult          = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $array_link = ['tblitems.code', 'tblitems.name'];
        if(in_array($aColumns[$i],$array_link)){
            $_data = '<a href="'.admin_url('invoice_items/item/').$aRow['id'].'">'.$_data.'</a>';
        }
        if($aColumns[$i] == 'tblitems.avatar' && file_exists($_data)) {
            $_data = '<img src="'.base_url($_data).'" width="50px" />';
        }
        $row[] = $_data;
    }
    $options = '';
    if(has_permission('items','','edit')){
        $options .= icon_btn('invoice_items/item/' . $aRow['id'], 'pencil-square-o', 'btn-default');
    }
    if(has_permission('items','','delete')){
       $options .= icon_btn('invoice_items/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
   }
   $row[] = $options;

   $output['aaData'][] = $row;
}
