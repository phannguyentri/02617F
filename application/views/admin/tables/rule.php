<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns     = array(
    'tblrule.id',
    'tblrule.name',
    'tblrule.content'

);
$sIndexColumn = "id";
$sTable       = 'tblrule';
$where        = array(
);
$join         = array(
);
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable,$join, $where, array(
));
$output       = $result['output'];
$rResult      = $result['rResult'];
$j=0;
foreach ($rResult as $aRow) {
    $row = array();
    $j++;
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
