<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$aColumns = array(
    '1',
    'company',
    'tblcontacts.id',
    'tblsuppliers.email',
    'tblsuppliers.phonenumber',
    'tblsuppliers.active'
);

$join = array();
array_push($join, 'LEFT JOIN tblcontacts ON tblcontacts.userid=tblsuppliers.userid AND tblcontacts.is_primary=1');


$aColumns = do_action('customers_table_sql_columns', $aColumns);

$sIndexColumn = "userid";
$sTable       = 'tblsuppliers';

$where   = array();
// Add blank where all filter can be stored




// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->_instance->db->query('SET SQL_BIG_SELECTS=1');
}

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
    'tblsuppliers.userid',
    'firstname',
    'lastname'
));
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

        if ($aColumns[$i] == '1') {
            $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['userid'] . '"><label></label></div>';
        } else if ($i == 6) {
            if ($_data != '') {
                $groups = explode(',', $_data);
                $_data  = '';
                foreach ($groups as $group) {
                    $_data .= '<span class="label label-default mleft5 inline-block">' . $group . '</span>';
                }
            }
        } else if ($aColumns[$i] == 'company') {
            if ($aRow['company'] == '') {
                $aRow['company'] = _l('no_company_view_profile');
            }
            $_data = '<a href="' . admin_url('suppliers/supplier/' . $aRow['userid']) . '">' . $aRow['company'] . '</a>';

        } else if ($aColumns[$i] == 'phonenumber') {
            $_data = '<a href="tel:' . $_data . '">' . $_data . '</a>';
        } else if ($aColumns[$i] == $aColumns[3]) {
            $_data = '<a href="mailto:' . $_data . '">' . $_data . '</a>';
        } else if ($i == 2) {
            // primary contact add link
            $_data = '<a href="' . admin_url('suppliers/supplier/' . $aRow['userid'] . '?contactid=' . get_primary_contact_user_id($aRow['userid'])) . '" target="_blank">' . $aRow['firstname'] . ' ' . $aRow['lastname'] . '</a>';
        } else if ($aColumns[$i] == 'tblsuppliers.active') {
            $checked = '';
            if ($aRow['tblsuppliers.active'] == 1) {
                $checked = 'checked';
            }

            $_data = '';
            $_data .= '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
            <input type="checkbox" data-switch-url="' . admin_url().'clients/change_client_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['userid'] . '" data-id="' . $aRow['userid'] . '" ' . $checked . '>
            <label class="onoffswitch-label" for="' . $aRow['userid'] . '"></label>
        </div>';
            // For exporting
            $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
            // $_data .= '</div>';
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = _d($_data);
            }
        }

        $hook_data = do_action('customers_tr_data_output', array(
            'output' => $_data,
            'column' => $aColumns[$i],
            'id' => $aRow['userid']
        ));
        $_data     = $hook_data['output'];

        $row[] = $_data;
    }

    $options = '';
    $options .= icon_btn('suppliers/supplier/' . $aRow['userid'], 'pencil-square-o');
    if (has_permission('suppliers', '', 'delete')) {
        $options .= icon_btn('suppliers/delete/' . $aRow['userid'], 'remove', 'btn-danger _delete', array(
            'data-toggle' => 'tooltip',
            'data-placement' => 'left',
            'title' => _l('client_delete_tooltip')
        ));
    }

    $row[] = $options;

    $output['aaData'][] = $row;
}
