<?php
function handle_mindmap_discussion_comment_attachments($discussion_id, $post_data, $insert_data)
{
    if (isset($_FILES['file']['name']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo json_encode(['message' => _perfex_upload_error($_FILES['file']['error'])]);
        die;
    }

    if (isset($_FILES['file']['name'])) {
        hooks()->do_action('before_upload_project_discussion_comment_attachment');
        $path = MINDMAP_DISCUSSION_ATTACHMENT_FOLDER . $discussion_id . '/';

        // Check for all cases if this extension is allowed
        if (!_upload_extension_allowed($_FILES['file']['name'])) {
            header('HTTP/1.0 400 Bad error');
            echo json_encode(['message' => _l('file_php_extension_blocked')]);
            die;
        }

        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $insert_data['file_name'] = $filename;

                if (isset($_FILES['file']['type'])) {
                    $insert_data['file_mime_type'] = $_FILES['file']['type'];
                } else {
                    $insert_data['file_mime_type'] = get_mime_by_extension($filename);
                }
            }
        }
    }

    return $insert_data;
}
function get_staff_user_email($id){
    $CI     = &get_instance();
     if (is_numeric($id)) {
            $CI->db->where('staffid', $id);
            
           $data =  $CI->db->get(db_prefix() . 'staff')->row();
            return $data->email;
           
        }
        
}

 /* Prepare the data/result for the query. */
if(!function_exists('get_cl_lists_query')){
function get_cl_lists_query($aColumns, $sIndexColumn, $sTable, $join = [], $where = [], $additionalSelect = [], $sGroupBy = '', $searchAs = [])

{

    $CI          = & get_instance();

    $__post      = $CI->input->post();

    $havingCount = '';

    /*

     * Paging

     */

    $sLimit = '';

    if ((is_numeric($CI->input->post('start'))) && $CI->input->post('length') != '-1') {

        $sLimit = 'LIMIT ' . intval($CI->input->post('start')) . ', ' . intval($CI->input->post('length'));

    }

    $_aColumns = [];

    foreach ($aColumns as $column) {

        // if found only one dot

        if (substr_count($column, '.') == 1 && strpos($column, ' as ') === false) {

            $_column = explode('.', $column);

            if (isset($_column[1])) {

                if (startsWith($_column[0], db_prefix())) {

                    $_prefix = prefixed_table_fields_wildcard($_column[0], $_column[0], $_column[1]);

                    array_push($_aColumns, $_prefix);

                } else {

                    array_push($_aColumns, $column);

                }

            } else {

                array_push($_aColumns, $_column[0]);

            }

        } else {

            array_push($_aColumns, $column);

        }

    }



    /*

     * Ordering

     */

    $nullColumnsAsLast = get_null_columns_that_should_be_sorted_as_last();



    $sOrder = 'ORDER BY id DESC';

    if ($CI->input->post('order')) {

        $sOrder = 'ORDER BY ';

        foreach ($CI->input->post('order') as $key => $val) {

            $columnName = $aColumns[intval($__post['order'][$key]['column'])];

            $dir        = strtoupper($__post['order'][$key]['dir']);



            if (strpos($columnName, ' as ') !== false) {

                $columnName = strbefore($columnName, ' as');

            }



            // first checking is for eq tablename.column name

            // second checking there is already prefixed table name in the column name

            // this will work on the first table sorting - checked by the draw parameters

            // in future sorting user must sort like he want and the duedates won't be always last

            if ((in_array($sTable . '.' . $columnName, $nullColumnsAsLast)

                || in_array($columnName, $nullColumnsAsLast))

        ) {

                $sOrder .= $columnName . ' IS NULL ' . $dir . ', ' . $columnName;

        } else {

            $sOrder .= hooks()->apply_filters('datatables_query_order_column', $columnName, $sTable);

        }

        $sOrder .= ' ' . $dir . ', ';

    }

    if (trim($sOrder) == 'ORDER BY') {

        $sOrder = '';

    }

    $sOrder = rtrim($sOrder, ', ');



    if (get_option('save_last_order_for_tables') == '1'

        && $CI->input->post('last_order_identifier')

        && $CI->input->post('order')) {

            // https://stackoverflow.com/questions/11195692/json-encode-sparse-php-array-as-json-array-not-json-object



        $indexedOnly = [];

    foreach ($CI->input->post('order') as $row) {

        $indexedOnly[] = array_values($row);

    }



    $meta_name = $CI->input->post('last_order_identifier') . '-table-last-order';



    update_staff_meta(get_staff_user_id(), $meta_name, json_encode($indexedOnly, JSON_NUMERIC_CHECK));

}

}

    /*

     * Filtering

     * NOTE this does not match the built-in DataTables filtering which does it

     * word by word on any field. It's possible to do here, but concerned about efficiency

     * on very large tables, and MySQL's regex functionality is very limited

     */

    $sWhere = '';

    if ((isset($__post['search'])) && $__post['search'] != '') {

        $search_value = $__post['search'];

        $search_value = trim($search_value);



        $sWhere             = 'WHERE (';

        $sMatchCustomFields = [];

        // Not working, do not use it

        $useMatchForCustomFieldsTableSearch = hooks()->apply_filters('use_match_for_custom_fields_table_search', 'false');



        for ($i = 0; $i < count($aColumns); $i++) {

            $columnName = $aColumns[$i];

            if (strpos($columnName, ' as ') !== false) {

                $columnName = strbefore($columnName, ' as');

            }



            if (stripos($columnName, 'AVG(') !== false || stripos($columnName, 'SUM(') !== false) {

            } else {

                if (isset($searchAs[$i])) {

                    $columnName = $searchAs[$i];

                }

                    // Custom fields values are FULLTEXT and should be searched with MATCH

                    // Not working ATM

                if ($useMatchForCustomFieldsTableSearch === 'true' && startsWith($columnName, 'ctable_')) {

                    $sMatchCustomFields[] = $columnName;

                } else {

                    $sWhere .= 'convert(' . $columnName . ' USING utf8)' . " LIKE '%" . $CI->db->escape_like_str($search_value) . "%' OR ";

                }

            }

        }



        if (count($sMatchCustomFields) > 0) {

            $s = $CI->db->escape_like_str($search_value);

            foreach ($sMatchCustomFields as $matchCustomField) {

                $sWhere .= "MATCH ({$matchCustomField}) AGAINST (CONVERT(BINARY('{$s}') USING utf8)) OR ";

            }

        }



        if (count($additionalSelect) > 0) {

            foreach ($additionalSelect as $searchAdditionalField) {

                if (strpos($searchAdditionalField, ' as ') !== false) {

                    $searchAdditionalField = strbefore($searchAdditionalField, ' as');

                }

                if (stripos($columnName, 'AVG(') !== false || stripos($columnName, 'SUM(') !== false) {

                } else {

                    // Use index

                    $sWhere .= 'convert(' . $searchAdditionalField . ' USING utf8)' . " LIKE '%" . $CI->db->escape_like_str($search_value) . "%' OR ";

                }

            }

        }

        $sWhere = substr_replace($sWhere, '', -3);

        $sWhere .= ')';

    }



    /*

     * SQL queries

     * Get data to display

     */

    $_additionalSelect = '';

    if (count($additionalSelect) > 0) {

        $_additionalSelect = ',' . implode(',', $additionalSelect);

    }

    $where = implode(' ', $where);

    if ($sWhere == '') {

        $where = trim($where);

        if (startsWith($where, 'AND') || startsWith($where, 'OR')) {

            if (startsWith($where, 'OR')) {

                $where = substr($where, 2);

            } else {

                $where = substr($where, 3);

            }

            $where = 'WHERE ' . $where;

        }

    }



    $join = implode(' ', $join);



    $sQuery = '

    SELECT SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $_aColumns)) . ' ' . $_additionalSelect . "

    FROM $sTable

    " . $join . "

    $sWhere

    " . $where . "

    $sGroupBy

    $sOrder

    $sLimit

    ";



    $rResult = $CI->db->query($sQuery)->result_array();

    $str =  $CI->db->last_query();;



    $rResult = hooks()->apply_filters('datatables_sql_query_results', $rResult, [

        'table' => $sTable,

        'limit' => $sLimit,

        'order' => $sOrder,

    ]);



    /* Data set length after filtering */

    $sQuery = '

    SELECT FOUND_ROWS()

    ';

    $_query         = $CI->db->query($sQuery)->result_array();

    $iFilteredTotal = $_query[0]['FOUND_ROWS()'];

    if (startsWith($where, 'AND')) {

        $where = 'WHERE ' . substr($where, 3);

    }

    /* Total data set length */

    $sQuery = '

    SELECT COUNT(' . $sTable . '.' . $sIndexColumn . ")

    FROM $sTable " . $join . ' ' . $where;



    $_query = $CI->db->query($sQuery)->result_array();

    $iTotal = $_query[0]['COUNT(' . $sTable . '.' . $sIndexColumn . ')'];

    /*

     * Output

     */

    $output = [

        'draw'                 => $__post['draw'] ? intval($__post['draw']) : 0,

        'iTotalRecords'        => $iTotal,

        'iTotalDisplayRecords' => $iFilteredTotal,

        'aaData'               => [],

    ];



    return [

        'rResult' => $rResult,

        'output'  => $output,

        "query"     =>  $str

    ];

}

}
?>