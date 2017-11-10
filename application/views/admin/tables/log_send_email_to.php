<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$aColumns     = array(
    'email',
    '2',
    '3',
    'time',
    'view'
);

$sIndexColumn = "id";
$sTable       = 'tblemail_send';
$where=array();
if($id_log)
{
    array_push($where,' AND tblemail_send.id_log='.$id_log.'');
}
if($type)
{
    array_push($where,' AND tblemail_send.type='.$type);
}
$join=array();
array_push($join, 'LEFT JOIN tbllog_email_send ON tbllog_email_send.id=tblemail_send.id_log');
$result  = data_tables_init($aColumns, $sIndexColumn,$sTable,$join,$where,array(
    'tblemail_send.id',
    'id_log',
    'template'
));
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i]=='view')
        {
            $lable="success";
            if($aRow['view']==0)
            {
                $lable='danger';
            }
            $_data='<span class="inline-block label label-default"><span class="inline-block label label-'.$lable.'">'.$aRow['view'].'</span></span>';
        }
        if($aColumns[$i]=='2'){
            $_data='<a>'.$aRow['template'].'</a>';
        }
        if($aColumns[$i]=='3')
        {
            $lable="danger";
            $title="Email đã được gửi";
            if($aRow['view']>0)
            {
                $lable='success';
                $title="Email đã được xem";
            }
            $_data='<span class="inline-block label label-default"><span class="inline-block label label-'.$lable.'">'.$title.'</span></span>';
        }
        if($aColumns[$i]=='time'){
            $_data=_dt($aRow['time']);
        }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
