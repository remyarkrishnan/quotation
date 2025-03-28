<?php

defined('BASEPATH') or exit('No direct script access allowed');

$project_id = $this->ci->input->post('project_id');

$aColumns = [
    'number',
    'YEAR(date) as year',
    get_sql_select_client_company(),
    db_prefix() . 'projects.name as project_name',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'delivery_notes.id and rel_type="delivery_note" ORDER by tag_order ASC) as tags',
    'date',
    'reference_no',
    db_prefix() . 'delivery_notes.status',
    'created_by'
];

$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'delivery_notes.clientid',
    'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'delivery_notes.currency',
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'delivery_notes.project_id',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'delivery_notes';

$custom_fields = get_table_custom_fields('delivery_note');

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'delivery_notes.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where  = [];
$filter = [];

if ($this->ci->input->post('not_sent')) {
    array_push($filter, 'OR (sent= 0 AND ' . db_prefix() . 'delivery_notes.status NOT IN (2,3,4))');
}
if ($this->ci->input->post('invoiced')) {
    array_push($filter, 'OR invoiceid IS NOT NULL');
}

if ($this->ci->input->post('not_invoiced')) {
    array_push($filter, 'OR invoiceid IS NULL');
}
$statuses  = $this->ci->delivery_notes_model->get_statuses();
$statusIds = [];
foreach ($statuses as $status) {
    if ($this->ci->input->post('delivery_notes_' . $status)) {
        array_push($statusIds, $status);
    }
}
if (count($statusIds) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'delivery_notes.status IN (' . implode(', ', $statusIds) . ')');
}

$agents    = $this->ci->delivery_notes_model->get_sale_agents();
$agentsIds = [];
foreach ($agents as $agent) {
    if ($this->ci->input->post('sale_agent_' . $agent['sale_agent'])) {
        array_push($agentsIds, $agent['sale_agent']);
    }
}
if (count($agentsIds) > 0) {
    array_push($filter, 'AND sale_agent IN (' . implode(', ', $agentsIds) . ')');
}

$years      = $this->ci->delivery_notes_model->get_delivery_notes_years();
$yearsArray = [];
foreach ($years as $year) {
    if ($this->ci->input->post('year_' . $year['year'])) {
        array_push($yearsArray, $year['year']);
    }
}
if (count($yearsArray) > 0) {
    array_push($filter, 'AND YEAR(date) IN (' . implode(', ', $yearsArray) . ')');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

if ($clientid != '') {
    array_push($where, 'AND ' . db_prefix() . 'delivery_notes.clientid=' . $this->ci->db->escape_str($clientid));
}

if ($project_id) {
    array_push($where, 'AND project_id=' . $this->ci->db->escape_str($project_id));
}

if (!has_permission('delivery_notes', '', 'view')) {
    $userWhere = 'AND ' . get_delivery_notes_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

$aColumns = hooks()->apply_filters('delivery_notes_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'delivery_notes.id',
    db_prefix() . 'delivery_notes.clientid',
    db_prefix() . 'delivery_notes.invoiceid',
    db_prefix() . 'currencies.name as currency_name',
    'project_id',
    'deleted_customer_name',
    'hash',
    'created_by'
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $numberOutput = '';
    // If is from client area table or projects area request
    if (is_numeric($clientid) || $project_id) {
        $numberOutput = '<a href="' . admin_url('delivery_notes/list_delivery_notes/' . $aRow['id']) . '" target="_blank">' . format_delivery_note_number($aRow['id']) . '</a>';
    } else {
        $numberOutput = '<a href="' . admin_url('delivery_notes/list_delivery_notes/' . $aRow['id']) . '" onclick="init_delivery_note(' . $aRow['id'] . '); return false;">' . format_delivery_note_number($aRow['id']) . '</a>';
    }

    $numberOutput .= '<div class="row-options">';

    $numberOutput .= '<a href="' . site_url('delivery_notes/client/dn/' . $aRow['id'] . '/' . $aRow['hash']) . '" target="_blank">' . _l('view') . '</a>';
    if (has_permission('delivery_notes', '', 'edit')) {
        $numberOutput .= ' | <a href="' . admin_url('delivery_notes/delivery_note/' . $aRow['id']) . '">' . _l('edit') . '</a>';
    }

    $numberOutput .= '</div>';

    $row[] = $numberOutput;

    $row[] = $aRow['year'];

    if (empty($aRow['deleted_customer_name'])) {
        $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
    } else {
        $row[] = $aRow['deleted_customer_name'];
    }

    $row[] = '<a href="' . admin_url('projects/view/' . $aRow['project_id']) . '">' . $aRow['project_name'] . '</a>';

    $row[] = render_tags($aRow['tags']);

    $row[] = _d($aRow['date']);

    $row[] = $aRow['reference_no'];

    $row[] = format_delivery_note_status($aRow[db_prefix() . 'delivery_notes.status']);

    $row[] = empty($aRow['created_by']) ? '' : "<a href='" . admin_url('staff/member/') . "{$aRow['created_by']}'>" . get_staff_full_name($aRow['created_by']) . "</a>";

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('delivery_notes_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}

echo json_encode($output);
die();
