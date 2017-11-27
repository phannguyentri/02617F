<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$aColumns     = array(
  'tbllog_sms_send.subject',
  'tbllog_sms_send.staff_id',
  'tbllog_sms_send.phone_number',
  'tblsms_templates.name',
  'tbllog_sms_send.message',
  'tbllog_sms_send.date_send',
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
        } else if($aColumns[$i] == 'tbllog_sms_send.phone_number'){
            $_data = '';
            $arrPhones = explode(',', $aRow['tbllog_sms_send.phone_number']);
            foreach ($arrPhones as $phone) {
                $_data .= '<i class="span-tag">'.$phone.'</i> ';
            }
        } else if($aColumns[$i] == 'tbllog_sms_send.staff_id'){
            $_data = '<a href="'.admin_url('profile/'.$aRow['tbllog_sms_send.staff_id']).'" data-toggle="tooltip" data-title="'.get_staff_full_name($aRow['tbllog_sms_send.staff_id']).'">'.staff_profile_image($aRow['tbllog_sms_send.staff_id'], array('staff-profile-image-small', 'mright5')).'</a>';
        }

        $row[] = $_data;
    }

    $options = '';
    $options .= icon_btn('#', 'eye', 'btn-default', array(
        'data-toggle' => 'tooltip',
        'title'       => _l('Xem'),
    ));

    $row[]              = $options;
    $output['aaData'][] = $row;
}

