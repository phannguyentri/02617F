<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
  'code_company',
  'company',
  'email',
  'client_type1'
);
$sIndexColumn = "userid";
$sTable       = 'tblclients';
$where        = array();
$join         = array();
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

    $output['aaData'][] = $row;
}

