<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$aColumns         = array(
    'tblcontracts.id',
    'CONCAT(tblcontracts.prefix,tblcontracts.code) as full',
    'CASE company WHEN "" THEN (SELECT CONCAT(firstname, " ", lastname) FROM tblcontacts WHERE userid = tblclients.userid and is_primary = 1) ELSE company END as company',
    'contract_type',
    'contract_value',
    'datestart',
    'dateend',
    '(SELECT fullname FROM tblstaff WHERE create_by=tblstaff.staffid)',
);
$sIndexColumn     = "id";
$sTable           = 'tblcontracts';
$additionalSelect = array(
    'tblcontracts.id',
    'tblcontracttypes.name',
    'trash',
    'client',
    'export_status',
);
$join             = array(
    'LEFT JOIN tblclients ON tblclients.userid = tblcontracts.client',
    'LEFT JOIN tblcontracttypes ON tblcontracttypes.id = tblcontracts.contract_type'
);

$custom_fields = get_custom_fields('contracts', array(
    'show_on_table' => 1
));

$i = 0;
foreach ($custom_fields as $field) {
    $select_as = 'cvalue_' . $i;
    if ($field['type'] == 'date_picker') {
        $select_as = 'date_picker_cvalue_' . $i;
    }
    array_push($aColumns, 'ctable_' . $i . '.value as ' . $select_as);
    array_push($join, 'LEFT JOIN tblcustomfieldsvalues as ctable_' . $i . ' ON tblcontracts.id = ctable_' . $i . '.relid AND ctable_' . $i . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $i . '.fieldid=' . $field['id']);
    $i++;
}

$where  = array();
$filter = array();

if ($this->_instance->input->post('exclude_trashed_contracts')) {
    array_push($filter, 'AND trash = 0');
}
if ($this->_instance->input->post('trash')) {
    array_push($filter, 'OR trash = 1');
}
if ($this->_instance->input->post('expired')) {
    array_push($filter, 'OR dateend IS NOT NULL AND dateend <"' . date('Y-m-d') . '" and trash = 0');
}

if ($this->_instance->input->post('without_dateend')) {
    array_push($filter, 'OR dateend IS NULL AND trash = 0');
}

$date_from = $this->_instance->input->post('report-from');
$date_to = $this->_instance->input->post('report-to');
$client_id = $this->_instance->input->post('client_id');
$user_id = $this->_instance->input->post('user_id');
if($date_from && $date_to){
    array_push($where, 'AND datestart BETWEEN "' . to_sql_date($date_from) . '" and "' . to_sql_date($date_to) . '"');
}

if($user_id){
    array_push($where, 'AND create_by='.$user_id);
}
if($client_id){
    array_push($where, 'AND client='.$client_id);
}

$types  = $this->_instance->contracts_model->get_contract_types();
$_types = array();
foreach ($types as $type) {
    if ($this->_instance->input->post('contracts_by_type_' . $type['id'])) {
        array_push($_types, $type['id']);
    }
}
if (count($_types) > 0) {
    array_push($filter, 'AND contract_type IN (' . implode(', ', $_types) . ')');
}
$years  = $this->_instance->contracts_model->get_contracts_years();
$_years = array();
foreach ($years as $year) {
    if ($this->_instance->input->post('year_' . $year['year'])) {
        array_push($_years, $year['year']);
    }
}
if (count($_years) > 0) {
    array_push($filter, 'AND YEAR(datestart) IN (' . implode(', ', $_years) . ')');
}

$_months = array();
for ($m = 1; $m <= 12; $m++) {
    if ($this->_instance->input->post('contracts_by_month_' . $m)) {
        array_push($_months, $m);
    }
}
if (count($_months) > 0) {
    array_push($filter, 'AND MONTH(datestart) IN (' . implode(', ', $_months) . ')');
}
if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

if (is_numeric($clientid)) {
    array_push($where, 'AND client=' . $clientid);
}

if (!has_permission('contracts', '', 'view')) {
    array_push($where, 'AND addedfrom=' . get_staff_user_id());
}

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->_instance->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'contract_value') {
            $_data = format_money($aRow['contract_value'], get_option('default_currency'));
        }
        if ($i == 1) {
            $_data = '<a  href="' . admin_url('contracts/contract/' . $aRow['tblcontracts.id']) . '">' . $aRow['full'] . '</a>';
        }
        if ($i == 2) {
            
            $_data = '<a onclick="init_client_modal_data(' . $aRow['client'] . ');return false;" href="' . admin_url('clients/client/' . $aRow['client']) . '">' . $aRow['company'] . '</a>';
        } else if ($aColumns[$i] == 'dateend' || $aColumns[$i] == 'datestart') {
            $_data = _d($_data);
        } else if ($aColumns[$i] == 'subject') {
            $_data = '<a href="' . admin_url('contracts/contract/' . $aRow['id']) . '">' . $_data . '</a>';
            if ($aRow['trash'] == 1) {
                $_data .= '<span class="label label-danger mleft5 inline-block">' . _l('contract_trash') . '</span>';
            }
        } else if ($aColumns[$i] == 'contract_type') {
            $_data = $aRow['name'];
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = _d($_data);
            }
        }
        
        $row[] = $_data;
    }
    
    $options = '';
    // if($aRow['export_status']==0){
    //     $options .= icon_btn('#', 'exchange','btn-default',array('onclick'=>'init_exports_modal_data('.$aRow['id'].'); return false;'));
    //     // $options .= icon_btn('contracts/create_order/'.$aRow['id'],'exchange','btn-default');
    // }
    $options .= icon_btn('contracts/contract/' . $aRow['id'], 'pencil-square-o');
    if (has_permission('contracts', '', 'delete')) {
        $options .= icon_btn('contracts/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    }
    
    $row[] = $options;
    
    if (!empty($aRow['dateend'])) {
        $_date_end = date('Y-m-d', strtotime($aRow['dateend']));
        if ($_date_end < date('Y-m-d')) {
            $row['DT_RowClass'] = 'alert-danger';
        }
    }
    
    $output['aaData'][] = $row;
}
