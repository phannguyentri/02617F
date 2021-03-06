<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$purpose_type = array(
    "COIL" => "Thu thập thông tin",
    "CORE" => "Giới thiệu, tư vấn",
    "SURV" => "Khảo sát",
    "QUOT" => "Báo giá",
    "NEGO" => "Đàm phán",
    "TCK"  => "Chăm sóc",
    "DELI" => "Giao hàng",
    "PAY"  => "Thanh toán"
);

$transaction_type = array(
    "direct"    => "Gặp trực tiếp",
    "phone"     => "Gọi điện",
    "email"     => "Email",
    "mess"      => "Chát",
);

$assignee_column = 4;
$tags_column = 3;
$aColumns = array(
    'tblclients.company',
    '2',
    '3',
    'name',
    'purpose',
    'transaction',
    'content_detail',
    '4',
    'priority',
    // 'startdate',
    'status',

);

if($this->_instance->input->get('bulk_actions')){
    array_unshift($aColumns, '1');
    $assignee_column = 5;
    $tags_column = 4;
}

$where = array();
if($this->_instance->input->post()) {
    $purpose             = $this->_instance->input->post('purpose');
    $transaction         = $this->_instance->input->post('transaction');
    $status              = $this->_instance->input->post('status');
    $client_id           = $this->_instance->input->post('client_id');
    $assigned_from       = $this->_instance->input->post('assigned_from');
    $task_id             = $this->_instance->input->post('task_id');
    $start_from          = $this->_instance->input->post('start-from');
    $start_to            = $this->_instance->input->post('start-to');
    $duration_date_from  = $this->_instance->input->post('duration-date-from');
    $duration_date_to    = $this->_instance->input->post('duration-date-to');
    $finish_date_from    = $this->_instance->input->post('finish-date-from');
    $finish_date_to      = $this->_instance->input->post('finish-date-to');
    $staff_task_assignee_id    = $this->_instance->input->post('staff_task_assignee_id');

    if($purpose){
        array_push($where, 'AND purpose="'.$purpose.'"');
    }
    if($transaction){
        array_push($where, 'AND transaction="'.$transaction.'"');
    }
    if($status){
        array_push($where, 'AND status="'.$status.'"');
    }
    if($client_id){
        array_push($where, 'AND rel_id="'.$client_id.'"');
    }
    if($assigned_from){
        $arrTaskAssigneesFrom = [];
        $rsTaskAssigneesFrom  = getTaskAssigneesByFromId($assigned_from);
        if ($rsTaskAssigneesFrom) {
            foreach ($rsTaskAssigneesFrom as $val) {
                $arrTaskAssigneesFrom[] = $val['taskid'];
            }
            array_push($where, 'AND id IN('.implode(',', $arrTaskAssigneesFrom).')');
        }else{
            array_push($where, 'AND 0=1');
        }

    }
    if($task_id){
        array_push($where, 'AND id="'.$task_id.'"');
    }
    if($staff_task_assignee_id){
        $arrTaskAssignees = [];
        $rsTaskAssignees  = getTaskAssigneesByStaffId($staff_task_assignee_id);
        if ($rsTaskAssignees) {
            foreach ($rsTaskAssignees as $val) {
                $arrTaskAssignees[] = $val['taskid'];
            }
            array_push($where, 'AND id IN('.implode(',', $arrTaskAssignees).')');
        }else{
            array_push($where, 'AND 0=1');
        }
    }
    if($start_from && $start_to){
        array_push($where, 'AND startdate BETWEEN "' . to_sql_date($start_from) . '" and "' . to_sql_date($start_to) . '"');
    }
    if($duration_date_from && $duration_date_to){
        array_push($where, 'AND duration_finish_date BETWEEN "' . to_sql_date($duration_date_from) . '" and "' . to_sql_date($duration_date_to) . '"');
    }
    if($finish_date_from && $finish_date_to){
        array_push($where, 'AND finish_date BETWEEN "' . to_sql_date($finish_date_from) . '" and "' . to_sql_date($finish_date_to) . '"');
    }
}


include_once(APPPATH . 'views/admin/tables/includes/tasks_filter.php');

$join          = array();
$custom_fields = get_custom_fields('tasks', array(
    'show_on_table' => 1
));

$i             = 0;
foreach ($custom_fields as $field) {
    $select_as = 'cvalue_' . $i;
    if ($field['type'] == 'date_picker') {
        $select_as = 'date_picker_cvalue_' . $i;
    }
    array_push($aColumns, 'ctable_' . $i . '.value as ' . $select_as);
    array_push($join, 'LEFT JOIN tblcustomfieldsvalues as ctable_' . $i . ' ON tblstafftasks1.id = ctable_' . $i . '.relid AND ctable_' . $i . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $i . '.fieldid=' . $field['id']);
    $i++;
}

$aColumns = do_action('tasks_table_sql_columns',$aColumns);

$sIndexColumn = "id";
$sTable       = 'tblstafftasks1';
// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->_instance->db->query('SET SQL_BIG_SELECTS=1');
}

array_push($join, 'LEFT JOIN tblclients  ON tblclients.userid=tblstafftasks1.rel_id');

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
    'tblstafftasks1.id',
    'tblclients.userid as client_id',
    'name',
    'dateadded',
    'priority',
    'rel_type',
    'tblstafftasks1.rel_id',
    'invoice_id',
    'duedate',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM tbltags_in JOIN tbltags ON tbltags_in.tag_id = tbltags.id WHERE rel_id = tblstafftasks1.id and rel_type="task" ORDER by tag_order ASC) as tags',
    '(SELECT GROUP_CONCAT(CONCAT(firstname, \' \', lastname) SEPARATOR ",") FROM tblstafftaskassignees JOIN tblstaff ON tblstaff.staffid = tblstafftaskassignees.staffid WHERE taskid=tblstafftasks1.id) as assignees',
    'purpose',
    'transaction',
    'tblclients.company',
    'content_detail',
    '(SELECT GROUP_CONCAT(staffid SEPARATOR ",") FROM tblstafftaskassignees WHERE taskid=tblstafftasks1.id) as assignees_ids',
    '1',
    'startdate',
    'duration_finish_date',
    'finish_date'
));


$output  = $result['output'];
$rResult = $result['rResult'];
// echo "<pre>";
// print_r($rResult);
// echo "</pre>";die();

foreach ($rResult as $aRow) {
    $row = array();

    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if($aColumns[$i] == '1'){
            $_data = '<div class="checkbox"><input type="checkbox" value="'.$aRow['id'].'"><label></label></div>';
        }else if($aColumns[$i] == 'tblclients.company'){
            $_data = '<a onclick="init_client_modal_data('.$aRow['client_id'].');return false;" href="'.admin_url('clients/client/').$aRow['client_id'].'" class="display-block main-tasks-table-href-name mbot5">'.$aRow['tblclients.company'].'</a>';
        }else if ($aColumns[$i] == 'name') {
            $_data = '<a href="'.admin_url('tasks/index/'.$aRow['id']).'" class="display-block main-tasks-table-href-name'.(!empty($aRow['rel_id']) ? ' mbot5' : '').'' . $_data . '</a>';
            /*" onclick="new_work_from(' . $aRow['id'] . '); return false;">*/

                if (!empty($aRow['rel_id'])) {
                $rel_data   = get_relation_data($aRow['rel_type'], $aRow['rel_id']);
                $rel_values = get_relation_values($rel_data, $aRow['rel_type']);
                // Show client company if task is related to project
                // if ($aRow['rel_type'] == 'project') {
    //                 $this->_instance->db->select('clientid');
    //                 $this->_instance->db->where('id', $aRow['rel_id']);
    //                 $client = $this->_instance->db->get('tblprojects')->row();
    //                 if ($client) {
    //                     $this->_instance->db->select('CASE company WHEN "" THEN (SELECT CONCAT(firstname, " ", lastname) FROM tblcontacts WHERE userid = tblclients.userid and is_primary = 1) ELSE company END as company');
    //                     $this->_instance->db->where('userid', $client->clientid);
    //                     $company = $this->_instance->db->get('tblclients')->row();
    //                     if ($company) {
    //                         $rel_values['name'] .= ' - ' . $company->company;
    //                     }
    //                 }
    //             }
                $_data .= '<span class="hide"> - </span>'. $aRow['name'] .': <a class="text-muted" data-toggle="tooltip" title="' . ucfirst($aRow['rel_type']) . '" href="' . $rel_values['link'] . '">' . $rel_values['name'] . '</a>';
            }

        }else if($aColumns[$i] == '2'){
            $_data = '';
            $contacts = get_contact_by_client_id($aRow['client_id']);

            if ($contacts) {
                $_data = '';
                foreach ($contacts as $value) {
                    $_data  .= '<a href="#" onclick="return false;"><img src="'.contact_profile_image_url($value['id']).'" class="staff-profile-image-small mright5" data-toggle="tooltip" data-title="'.$value['fullname'].'"><span class="text-info">'.$value['fullname'].'</span></a>';
                }
            }

        } else if($aColumns[$i] == '3'){
            $_data = '';
            $staffs = get_askassignees_by_task_id($aRow['id']);

            if ($staffs) {
                foreach ($staffs as $value) {
                    $_data .= '<a href="'.admin_url('profile/'.$value['staffid']).'" data-toggle="tooltip" data-title="'.get_staff_full_name($value['staffid']).'">'.staff_profile_image($value['staffid'], array(
                        'staff-profile-image-small',
                        'mright5'
                    )).'</a>';
                }
            }
        } else if ($aColumns[$i] == 'purpose') {
            $_data = $purpose_type[$aRow['purpose']];
        } else if ($aColumns[$i] == 'startdate' || $aColumns[$i] == 'duedate') {
            if ($aColumns[$i] == 'startdate') {
                $_data = _d($aRow['startdate']);
            } else {
                $_data = _d($aRow['duedate']);
            }
        } else if ($aColumns[$i] == 'status') {
            $_data = '<span class="inline-block label label-'.get_status_label($aRow['status']).'" task-status-table="'.$aRow['status'].'">' . format_task_status($aRow['status'],false,true);
            if ($aRow['status'] == 5) {
                // $_data .= '<a href="#" onclick="unmark_complete(' . $aRow['id'] . '); return false;"><i class="fa fa-check task-icon task-finished-icon" data-toggle="tooltip" title="' . _l('task_unmark_as_complete') . '"></i></a>';
                $_data .= '<a href="#"><i class="fa fa-check task-icon task-finished-icon" onclick="return false;" data-toggle="tooltip"></i></a>';
            } else {
                $_data .= '<a href="#" onclick="mark_complete(' . $aRow['id'] . '); return false;"><i class="fa fa-check task-icon task-unfinished-icon" data-toggle="tooltip" title="' . _l('task_single_mark_as_complete') . '"></i></a>';
            }
            $_data .= '</span>';
        }else if ($aColumns[$i] == 'transaction') {
            $_data = '<span>' . _l($transaction_type[$aRow['transaction']]). '</span>';
        }else if($aColumns[$i] == 'content_detail'){
            $_data = '<span>' . _l($aRow['content_detail']). '</span>';
        }else if($aColumns[$i] == '4'){
            $_data  = '<span>' . _d($aRow['startdate']). '</span></br>';
            $_data .= '<span>' . _d($aRow['duration_finish_date']). '</span></br>';
            $_data .= '<span class="text-success inline-block">' . _d($aRow['finish_date']). '</span></br>';
        }else if ($aColumns[$i] == 'priority') {
            $_data = '<span class="text-' . get_task_priority_class($_data) . ' inline-block">' . task_priority($_data) . '</span>';
        }  else if ($i == $tags_column) {
            $_data = render_tags($_data);
        } else if ($aColumns[$i] == 'billable') {
            if ($_data == 1) {
                $billable = _l("task_billable_yes");
            } else {
                $billable = _l("task_billable_no");
            }
            $_data = $billable;
        } else if ($aColumns[$i] == 'billed') {
            if ($aRow['billable'] == 1) {
                if ($_data == 1) {
                    $_data = '<span class="label label-success inline-block">' . _l('task_billed_yes') . '</span>';
                } else {
                    $_data = '<span class="label label-danger inline-block">' . _l('task_billed_no') . '</span>';
                }
            } else {
                $_data = '';
            }
        } else if ($aColumns[$i] == $aColumns[$assignee_column]) {
            $assignees        = explode(',', $_data);
            $assignee_ids        = explode(',', $aRow['assignees_ids']);
            $_data            = '';
            $export_assignees = '';
            $as = 0;
            foreach ($assignees as $assigned) {
                $assignee_id = $assignee_ids[$as];
                if ($assigned != '') {
                    $_data .= '<a href="' . admin_url('profile/' . $assignee_id) . '">' . staff_profile_image($assignee_id, array(
                        'staff-profile-image-small mright5'
                    ), 'small', array(
                        'data-toggle' => 'tooltip',
                        'data-title' => $assigned
                    )) . '</a>';
                    // For exporting
                    $export_assignees .= $assigned . ', ';
                }

                $as++;
            }
            if ($export_assignees != '') {
                $_data .= '<span class="hide">' . mb_substr($export_assignees, 0, -2) . '</span>';
            }
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = _d($_data);
            }
        }
        $hook_data = do_action('tasks_tr_data_output',array('output'=>$_data,'column'=>$aColumns[$i],'id'=>$aRow['id']));
        $_data = $hook_data['output'];
        $row[] = $_data;
    }
    $options = '';

    if (has_permission('tasks', '', 'edit')) {
        $options .= icon_btn('#', 'pencil-square-o', 'btn-default  mleft5', array(
            'onclick' => 'edit_task1(' . $aRow['id'] . '); return false'
        ));

    }
    // Công việc
    // $options .= icon_btn('#', 'list-alt', 'btn-default ', array(
    //         'onclick' => 'new_work_from(' . $aRow['id'] . '); return false'
    //     ));

    if (has_permission('tasks', '', 'delete')) {
        $options .= icon_btn('tasks/delete_task1/' . $aRow['id'], 'remove', 'btn-danger _delete', array(
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'title' => _l('Những thông tin liên quan sẽ bị xóa')
        ));
    }

    $class = 'btn-success no-margin';
    // $atts  = array(
    //     'onclick' => 'timer_action(this,' . $aRow['id'] . '); return false'
    // );


    $tooltip        = '';
    $is_assigned    = $this->_instance->tasks_model->is_task_assignee(get_staff_user_id(), $aRow['id']);
    $is_task_billed = $this->_instance->tasks_model->is_task_billed($aRow['id']);
    if ($is_task_billed || !$is_assigned || $aRow['status'] == 5) {
        $class = 'btn-default disabled';
        if($aRow['status'] == 5){
            $tooltip = ' data-toggle="tooltip" data-title="' . format_task_status($aRow['status'],false,true) . '"';
        } else if ($is_task_billed) {
            $tooltip = ' data-toggle="tooltip" data-title="' . _l('task_billed_cant_start_timer') . '"';
        } else if(!$is_assigned) {
            $tooltip = ' data-toggle="tooltip" data-title="' . _l('task_start_timer_only_assignee') . '"';
        }
    }

    // if (!$this->_instance->tasks_model->is_timer_started($aRow['id'])) {
    //     $options .= '<span' . $tooltip . ' class="pull-right">' . icon_btn('#', 'clock-o', $class . ' no-margin', $atts) . '</span>';
    // } else {
    //     $options .= icon_btn('#', 'clock-o', 'btn-danger pull-right no-margin', array(
    //         'onclick' => 'timer_action(this,' . $aRow['id'] . ',' . $this->_instance->tasks_model->get_last_timer($aRow['id'])->id . '); return false'
    //     ));
    // }

    $row[]              = $options;
    $rowClass = '';
    if ((!empty($aRow['duedate']) && $aRow['duedate'] < date('Y-m-d')) && $aRow['status'] != 5) {
        $rowClass = 'text-danger bold ';
    }
    $row['DT_RowClass'] = $rowClass;
    $output['aaData'][] = $row;
}
