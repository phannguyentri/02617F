<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$aColumns     = array(
    'tbllog_email_send.id',
    'subject',
    'addedfrom',
    'date_send',
    '4'
    // '5'
);

$sIndexColumn = "id";
$sTable       = 'tbllog_email_send';
// $join=array(
//     ' LEFT JOIN tblcampaign ON tblcampaign.id=tbllog_email_send.campaign'
// );
$where="";
// $filterCampaign=$this->_instance->input->post('filterCampaign');
// if($filterCampaign)
// {
//     $where=array(' AND tbllog_email_send.campaign='.$filterCampaign);
// }


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable,$join,$where,array(
    'tbllog_email_send.id','addedfrom'
    // 'tblcampaign.id as id_campaign',
    // 'name',
));
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if($aColumns[$i]=='tbllog_email_send.id')
        {
            $_data='<a  onclick="load_lish('.$aRow['tbllog_email_send.id'].')" type="button" class="glyphicon glyphicon-plus" data-toggle="modal" data-target="#view-email"></a>';
        }
        if($aColumns[$i]=='4')
        {
            $status=0;
            $review=get_table_where('tblemail_send',array('id_log'=>$aRow['id'],'view > '=>0));
            if($review!=array())
            {
                $status=2;
            }
            $_data='<span class="inline-block label label-default">'.format_status_email($status).'</span>';
            // var_dump($result);die();
        }
        if($aColumns[$i]=='addedfrom')
        {
            if($_data != '0') {
                $_data = '<a href="' . admin_url('profile/' . $_data) . '">' . staff_profile_image($_data, array(
                        'staff-profile-image-small mright5'
                    ), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => get_staff_full_name($aRow["addedfrom"]),
                    )) . '</a>';
            }
            else {
                $_data = "";
            }
        }
        // if($aColumns[$i]=='5')
        // {
        //     if($aRow['id_campaign'])
        //     {
        //         $_data="<a href='".admin_url('campaign/campaign/'.$aRow['id_campaign'])."'>".$aRow['name']."</a>";
        //     }
        //     else
        //     {
        //         $_data=$aRow['id_campaign'];
        //     }
        // }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
