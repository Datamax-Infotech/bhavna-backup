<?
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

if (isset($_POST['form_action']) && $_POST['form_action'] == "add") {
    db();
    $flg = 0;
    $account_no = isset($_POST['generate_unique_account_no']) && $_POST['generate_unique_account_no'] == 1 ? '0' : $_POST['account_no'];

    $account_mapping_sql = db_query("INSERT INTO water_ocr_account_mapping (`company_id`,`vendor_id`,`account_no`,`template_name`,`generic_flg`,`ocr_file_name`,`ocr_extract_text`,`created_by`,`created_on`)
                           VALUES('" . $_POST['company_id'] . "','" . $_POST['vendor_id'] . "','" . $account_no . "','" . $_POST['template_name'] . "',1,'" . $_POST['ocr_file_name'] . "','". str_replace("'", "\'" , $_POST['ocr_extract_text']) ."','" . $_COOKIE['b2b_id'] . "','" . date("Y-m-d h:i:s") . "')");
						   
    $template_id = tep_db_insert_id();
    if ($account_no == 0) {
        for ($index = 0; $index < count($_POST['acc_no_not_found_mapped_field']); $index++) {
            db_query("INSERT INTO water_ocr_account_mapping_account_not_found (`template_id`,`field_name`,`field_value`)
                VALUES('" . $template_id . "','" . $_POST['acc_no_not_found_mapped_field'][$index] . "','" . $_POST['acc_no_not_found_field_value'][$index] . "' )");
        }
    }
    if ($template_id > 0) {
        for ($index = 0; $index < count($_POST['mapped_with']); $index++) {
            db_query("INSERT INTO water_ocr_mapping_field_and_values (`template_id`,`mapped_with`,`field_name`,`field_value`)
            VALUES('" . $template_id . "','" . $_POST['mapped_with'][$index] . "','" . $_POST['mapped_field'][$index] . "','" . $_POST['field_value'][$index] . "')");
        }
    }
    $qry = "Select id from loop_warehouse where b2bid = '" . $_POST['company_id'] . "'";
    $row1 = db_query($qry);

    while ($main_res1 = array_shift($row1)) {
        $warehouse_id = $main_res1["id"];
    }
    $all_generic_fields = explode(' && ', $_POST['all_generic_fields_array']);
    foreach ($all_generic_fields as $field_data) {
        $each_generic_field = explode('||', $field_data);
        $each_generic_field;
        $generic_field_name =  str_replace("'", "\'" , $each_generic_field[0]);
        $generic_field_value = str_replace("'", "\'" , $each_generic_field[1]);
        $cordinates = $each_generic_field[2];
        $insert_generic_fields_dp = "INSERT INTO water_ocr_all_generic_fields_dropdown (`template_id`,`field_name`,`field_val`,`coordinates`) 
        values($template_id,'$generic_field_name','$generic_field_value','$cordinates')";
        db_query($insert_generic_fields_dp);
    }
    $consider_water_mapping_table_array = $_POST['consider_water_mapping_table_array'];
    $ocr_material_name_group = "";
    for ($index = 1; $index <= count($consider_water_mapping_table_array); $index++) {
        $table_consideration = $consider_water_mapping_table_array[$index - 1];
        $insert_considaration = "INSERT INTO water_ocr_consideration_of_tables_for_mapping (template_id,table_no,consideration) VALUES($template_id,$index,'" . $table_consideration . "')";
        //echo $insert_considaration."<br>";
        db_query($insert_considaration);
        $water_material_array = $_POST['water_material' . $index];
       $selected_index_for_material_fee = 111;
        for ($index2 = 0; $index2 < count($water_material_array); $index2++) {
            if ($index2 == 0) {
                $tbl_column_no = implode('&&', $_POST['tbl_column_no-' . $index]);
                $tbl_column_text = implode('&&', $_POST['tbl_column_text-' . $index]);
                $table_mapping_field = implode('&&', $_POST['table_mapping_field' . $index . "0"]);
                $insert_tbl_head_data = "INSERT INTO water_ocr_mapping_item_list_table_header (`template_id`,`table_no`,`tbl_column_no`,`column_text`,`selected_value`)
            VALUES($template_id,$index,'" . $tbl_column_no . "','" . $tbl_column_text . "','" . $table_mapping_field . "')";
                //echo $insert_tbl_head_data."<br>";
                db_query(($insert_tbl_head_data));
                if ($table_consideration == 1) {
                    for ($index3 = 0; $index3 < count($_POST['table_mapping_field' . $index . "0"]); $index3++) {
                        if ($_POST['table_mapping_field' . $index . "0"][$index3] == "Material/Fee column") {
                            $selected_index_for_material_fee = $index3;
                            break;
                        }
                    }
                } else {
                   $selected_index_for_material_fee = 111;
                }
            } else {
                $water_material = $water_material_array[$index2] == "" ? 0 : $water_material_array[$index2];
                $add_fee = $_POST['add_fee' . $index][$index2] == "" ? 0 : $_POST['add_fee' . $index][$index2];
                $line_item = $_POST['consider_line_item_array' . $index][$index2] == "" ? 0 : $_POST['consider_line_item_array' . $index][$index2];
                $group_text = $_POST['group_text_array' . $index][$index2] == "" ? 0 : $_POST['group_text_array' . $index][$index2];
                $start_position = $_POST['text_start_position' . $index][$index2] == "" ? 0 : $_POST['text_start_position' . $index][$index2];
                $end_position = $_POST['text_end_position' . $index][$index2] == "" ? 0 : $_POST['text_end_position' . $index][$index2];
                $row_no = $_POST['row_no' . $index][$index2];
                $table_mapping_field = $_POST['table_mapping_field' . $index . "" . $row_no];
                $field_coordinates = $_POST['table_mapping_field_cord' . $index . "" . $row_no];
                $table_mapping_field_data = implode(' && ', $table_mapping_field);
                $field_coordinates_data = implode(' && ', $field_coordinates);
				
				$ocr_selected_text = $_POST['ocr_selected_text' . $index][$index2];
				
                $insert_table_data = "INSERT INTO water_ocr_mapping_item_list_table_data (`table_no`,`template_id`,`row_no`,`water_material`,`add_fee`,`line_item`,`group_text`,`start_position`,`end_position`,`field_data`,`coordinates`, ocr_selected_text)
				VALUES($index,$template_id,$row_no,$water_material,$add_fee,$line_item,$group_text,$start_position, $end_position,'" . $table_mapping_field_data . "','" . $field_coordinates_data . "','" . $ocr_selected_text . "')";
                db_query($insert_table_data);
				
                if ($table_consideration == 1) {
                    if ($line_item == 1) {
                        if ($selected_index_for_material_fee != 111) {
                            /*print_r($_POST['table_mapping_field' . $index . "" . $row_no]);
                            echo $selected_index_for_material_fee. " selected_index_for_material_fee";
                            */
                            $ocr_material_name = $_POST['table_mapping_field' . $index . "" . $row_no][$selected_index_for_material_fee];
                            if ($start_position != "" && $end_position != "") {
                                $ocr_material_name = substr($ocr_material_name, $start_position - 1, $end_position - ($start_position - 1));
                            }
                            if ($group_text == 1) {
                                $ocr_material_name_group .= $ocr_material_name . "|";
                            } else {
                                $vendor_id = $_POST['vendor_id'];
                                $ocr_data_name =  $ocr_material_name_group . $ocr_material_name;
                                if ($water_material != "") {
                                   $insert_table_data = "INSERT INTO water_company_vendor_ocr_name (`template_id`,`table_no`,`row_no`,`warehouse_id`,`vendor_id`,`inventory_id`,`ocr_material_name`,`special_case`)
                                VALUES ($template_id, $index ,$row_no,'" . $warehouse_id . "','" . $vendor_id . "','" . $water_material . "','" . $ocr_data_name  . "',0)";
                                } else if ($add_fee != "") {
                                    $insert_table_data = "INSERT INTO  water_vendor_additional_fee_master (template_id,`table_no`,`row_no`,`water_vendor_id`,`additional_fee_id`,`ocr_additional_fee_name`)
                                VALUES($template_id, $index, $row_no ,'" . $vendor_id . "','" . $add_fee . "','" . $ocr_data_name . "')";
                                }
                                db_query($insert_table_data);
                                $ocr_material_name_group = "";
                            }
                        }
                    }
                }
            }
        }
    }
	
	$flg = 1;

    echo $flg;
}

if (isset($_POST['check_for_template']) && $_POST['check_for_template'] == 1) {
    $flg = 0;
    $filter_str = $_POST['template_form_action'] && $_POST['template_form_action'] == 'update' ? ' &&  unqid !=' . $_POST['template_id'] : '';
    //echo "SELECT unqid from water_ocr_account_mapping where template_name='".$_POST['template_name']."' $filter_str";
    $select_template_name = db_query("SELECT unqid from water_ocr_account_mapping where template_name='" . $_POST['template_name'] . "' $filter_str", db());
    $template_name_count = tep_db_num_rows($select_template_name);
    if ($template_name_count > 0) {
        $flg = 1;
    }
    echo $flg;
}

if (isset($_POST['form_action']) && $_POST['form_action'] == "update") {
    $template_id = $_POST['template_id_hidden'];
    $account_no = isset($_POST['generate_unique_account_no']) && $_POST['generate_unique_account_no'] == 1 ? '0' : $_POST['account_no'];
    $update_account = "UPDATE water_ocr_account_mapping set `company_id`='" . $_POST['company_id'] . "',`vendor_id`='" . $_POST['vendor_id'] . "',`account_no`='" . $account_no . "'
        ,`template_name`='" . $_POST['template_name'] . "' where unqid=$template_id ";
    db_query($update_account, db());

    if ($account_no == 0) {
        for ($index = 0; $index < count($_POST['acc_no_not_found_mapped_field']); $index++) {
            if ($_POST['no_acc_fv_id'][$index] != "" && $_POST['no_acc_fv_id'][$index] != 0) {
                db_query("UPDATE water_ocr_account_mapping_account_not_found set field_name = '" . $_POST['acc_no_not_found_mapped_field'][$index] . "' ,
                    field_value = '" . $_POST['acc_no_not_found_field_value'][$index] . "' where id = '" . $_POST['no_acc_fv_id'][$index] . "'", db());
            } else {
                db_query("INSERT INTO water_ocr_account_mapping_account_not_found (`template_id`,`field_name`,`field_value`)
                    VALUES('" . $template_id . "','" . $_POST['acc_no_not_found_mapped_field'][$index] . "','" . $_POST['acc_no_not_found_field_value'][$index] . "' )", db());
            }
        }
    } else {
        db_query("DELETE FROM water_ocr_account_mapping_account_not_found where template_id = $template_id");
    }

    for ($index = 0; $index < count($_POST['mapped_with']); $index++) {
        if ($_POST['fv_id'][$index] != "" && $_POST['fv_id'][$index] != 0) {
            db_query("UPDATE water_ocr_mapping_field_and_values set  `mapped_with`='" . $_POST['mapped_with'][$index] . "' , `field_name`='" . $_POST['mapped_field'][$index] . "',
                `field_value`='" . $_POST['field_value'][$index] . "' where id='" . $_POST['fv_id'][$index] . "'", db());
        } else {
            db_query("INSERT INTO water_ocr_mapping_field_and_values (`template_id`,`mapped_with`,`field_name`,`field_value`)
                VALUES('" . $template_id . "','" . $_POST['mapped_with'][$index] . "','" . $_POST['mapped_field'][$index] . "','" . $_POST['field_value'][$index] . "')", db());
        }
    }
    $warehouse_id = 0;
    $qry = "Select id from loop_warehouse where b2bid = '" . $_POST['company_id'] . "'";
    $row1 = db_query($qry);

    while ($main_res1 = array_shift($row1)) {
        $warehouse_id = $main_res1["id"];
    }
    $consider_water_mapping_table_array = $_POST['consider_water_mapping_table_array'];
    $ocr_material_name_group = "";
    for ($index = 1; $index <= count($consider_water_mapping_table_array); $index++) {
        $table_consideration = $consider_water_mapping_table_array[$index - 1];
        $consider_tbl_row_id = $_POST['consider_water_mapping_table_id_array'][$index - 1];
        $select_existing_consideration = db_query("SELECT * FROM water_ocr_consideration_of_tables_for_mapping where id = $consider_tbl_row_id", db());
        $existing_consideration = array_shift($select_existing_consideration);
        if ($existing_consideration['consideration'] != $table_consideration) {
            $update_considaration = "UPDATE water_ocr_consideration_of_tables_for_mapping set consideration = '" . $table_consideration . "' where id = $consider_tbl_row_id";
            db_query($update_considaration);
        }
        $water_material_array = $_POST['water_material' . $index];
       $selected_index_for_material_fee = 111;
        for ($index2 = 0; $index2 < count($water_material_array); $index2++) {
            if ($index2 == 0) {
                $tbl_column_no = implode('&&', $_POST['tbl_column_no-' . $index]);
                $tbl_column_text = implode('&&', $_POST['tbl_column_text-' . $index]);
                $table_mapping_field = implode('&&', $_POST['table_mapping_field' . $index . "0"]);
                $water_list_item_header_id = $_POST['water_list_item_header_id' . $index];
                $select_tbl_head_data = db_query("SELECT * FROM water_ocr_mapping_item_list_table_header where id = $water_list_item_header_id");
                $tbl_head_data = array_shift($select_tbl_head_data);
                if ($tbl_head_data['tbl_column_no'] != $tbl_column_no || $tbl_head_data['column_text'] != $tbl_column_text || $tbl_head_data['selected_value'] != $table_mapping_field) {
                    $update_tbl_head_data = "UPDATE water_ocr_mapping_item_list_table_header set tbl_column_no = '" . $tbl_column_no . "', column_text = '" . $tbl_column_text . "', selected_value = '" . $table_mapping_field . "' where id = $water_list_item_header_id";
                    db_query($update_tbl_head_data);
                }
                    if ($table_consideration == 1) {
                        for ($index3 = 0; $index3 < count($_POST['table_mapping_field' . $index . "0"]); $index3++) {
                            if ($_POST['table_mapping_field' . $index . "0"][$index3] == "Material/Fee column") {
                                $selected_index_for_material_fee = $index3;
                                break;
                            }
                        }
                    } else {
                       $selected_index_for_material_fee = 111;
                    }
            } else {
                $water_material = $water_material_array[$index2] == "" ? 0 : $water_material_array[$index2];
                $add_fee = $_POST['add_fee' . $index][$index2] == "" ? 0 : $_POST['add_fee' . $index][$index2];
                $line_item = $_POST['consider_line_item_array' . $index][$index2] == "" ? 0 : $_POST['consider_line_item_array' . $index][$index2];
                $group_text = $_POST['group_text_array' . $index][$index2] == "" ? 0 : $_POST['group_text_array' . $index][$index2];
                $start_position = $_POST['text_start_position' . $index][$index2] == "" ? 0 : $_POST['text_start_position' . $index][$index2];
                $end_position = $_POST['text_end_position' . $index][$index2] == "" ? 0 : $_POST['text_end_position' . $index][$index2];
                $row_no = $_POST['row_no' . $index][$index2];
                $table_mapping_field = $_POST['table_mapping_field' . $index . "" . $row_no];
                $field_coordinates = $_POST['table_mapping_field_cord' . $index . "" . $row_no];
                $table_mapping_field_data = implode(' && ', $table_mapping_field);
                $field_coordinates_data = implode(' && ', $field_coordinates);
                $water_list_item_data_id = $_POST['water_list_item_body_id' . $index][$index2 - 1];
                //echo "SELECT * FROM water_ocr_mapping_item_list_table_data where id = $water_list_item_data_id";
                
                $ocr_selected_text = $_POST['ocr_selected_text' . $index][$index2];
                if($water_list_item_data_id != ""){
                $select_water_list_items_data = db_query("SELECT * FROM water_ocr_mapping_item_list_table_data where id = $water_list_item_data_id");
                $water_list_item_data = array_shift($select_water_list_items_data);
                if ($water_list_item_data['water_material'] != $water_material || $water_list_item_data['add_fee'] != $add_fee || $water_list_item_data['line_item'] != $line_item || $water_list_item_data['group_text'] != $group_text || $water_list_item_data['start_position'] != $start_position || $water_list_item_data['end_position'] != $end_position || $water_list_item_data['field_data'] != $table_mapping_field_data || $water_list_item_data['coordinates'] != $field_coordinates_data) {
                    $update_table_data = "UPDATE water_ocr_mapping_item_list_table_data set water_material = $water_material, add_fee = $add_fee, line_item = $line_item, group_text = $group_text, start_position = $start_position, end_position = $end_position, field_data = '" . $table_mapping_field_data . "', coordinates = '" . $field_coordinates_data . "', ocr_selected_text = '".$ocr_selected_text."' where id = $water_list_item_data_id";
                    db_query($update_table_data);
                }
            }else{
                $insert_table_data = "INSERT INTO water_ocr_mapping_item_list_table_data (`table_no`,`template_id`,`row_no`,`water_material`,`add_fee`,`line_item`,`group_text`,`start_position`,`end_position`,`field_data`,`coordinates`, ocr_selected_text)
				VALUES($index,$template_id,$row_no,$water_material,$add_fee,$line_item,$group_text,$start_position, $end_position,'" . $table_mapping_field_data . "','" . $field_coordinates_data . "','" . $ocr_selected_text . "')";
                db_query($insert_table_data); 
            }
                if ($table_consideration == 1) {
                if ($line_item == 1) {
                   // echo "table no =".$index." selected_index_for_material_fee is = ".$selected_index_for_material_fee;
                    if ($selected_index_for_material_fee != 111) {
                 
                        $ocr_material_name = $_POST['table_mapping_field' . $index . "" . $row_no][$selected_index_for_material_fee];
                        if ($start_position != "" && $end_position != "") {
                            $ocr_material_name = substr($ocr_material_name, $start_position - 1, $end_position - ($start_position - 1));
                        }
                        if ($group_text == 1) {
                            $ocr_material_name_group .= $ocr_material_name . "|";
                            db_query("DELETE FROM water_company_vendor_ocr_name where template_id = $template_id and table_no = $index and row_no = $row_no");
                            db_query("DELETE FROM water_vendor_additional_fee_master where template_id = $template_id and table_no = $index and row_no = $row_no");        
                        } else {
                            $vendor_id = $_POST['vendor_id'];
                            $ocr_data_name =  $ocr_material_name_group . $ocr_material_name;
                            $select_company_vendor_ocr_name = db_query("SELECT * FROM water_company_vendor_ocr_name where template_id = $template_id and table_no = $index and row_no = $row_no");
                            $select_vendor_additional_fee_master = db_query("SELECT * FROM water_vendor_additional_fee_master where template_id = $template_id and table_no = $index and row_no = $row_no");
                            if (tep_db_num_rows($select_company_vendor_ocr_name) > 0) {
                                $company_vendor_ocr_name = array_shift($select_company_vendor_ocr_name);
                                if ($company_vendor_ocr_name['warehouse_id'] != $warehouse_id || $company_vendor_ocr_name['vendor_id'] != $vendor_id || $company_vendor_ocr_name['inventory_id'] != $water_material || $company_vendor_ocr_name['ocr_material_name'] != $ocr_data_name) {
                                    $update_table_data = "UPDATE water_company_vendor_ocr_name set warehouse_id = '" . $warehouse_id . "', vendor_id = '" . $vendor_id . "', inventory_id = '" . $water_material . "', ocr_material_name = '" . $ocr_data_name . "' where template_id = $template_id and table_no = $index and row_no = $row_no";
                                    db_query($update_table_data);
                                }
                            } else if (tep_db_num_rows($select_vendor_additional_fee_master) > 0) {
                                $company_vendor_additional_fee = array_shift($select_vendor_additional_fee_master);
                                if ($company_vendor_additional_fee['water_vendor_id'] != $vendor_id || $company_vendor_additional_fee['additional_fee_id'] != $add_fee || $company_vendor_additional_fee['ocr_additional_fee_name'] != $ocr_data_name) {
                                    $update_table_data = "UPDATE water_vendor_additional_fee_master set water_vendor_id = '" . $vendor_id . "', additional_fee_id = '" . $add_fee . "', ocr_additional_fee_name = '" . $ocr_data_name . "' where template_id = $template_id and table_no = $index and row_no = $row_no";
                                    db_query($update_table_data);
                                }
                            } else if ($water_material != "") {
                                 $insert_table_data = "INSERT INTO water_company_vendor_ocr_name (`template_id`,`table_no`,`row_no`,`warehouse_id`,`vendor_id`,`inventory_id`,`ocr_material_name`,`special_case`)
                                    VALUES ($template_id,$index,$row_no,'" . $warehouse_id . "','" . $vendor_id . "','" . $water_material . "','" . $ocr_data_name  . "',0)";

                                db_query($insert_table_data);
                            } else if ($add_fee != "") {
                                $insert_table_data = "INSERT INTO  water_vendor_additional_fee_master (template_id,`table_no`, row_no , `water_vendor_id`,`additional_fee_id`,`ocr_additional_fee_name`)
                                    VALUES($template_id, $index ,$row_no,'" . $vendor_id . "','" . $add_fee . "','" . $ocr_data_name . "')";
                                db_query($insert_table_data);
                            }
                            $ocr_material_name_group = "";
                        }
                    }
                } else {
                    db_query("DELETE FROM water_company_vendor_ocr_name where template_id = $template_id and table_no = $index and row_no = $row_no");
                    db_query("DELETE FROM water_vendor_additional_fee_master where template_id = $template_id and table_no = $index and row_no = $row_no");
                }}else{
                    db_query("DELETE FROM water_company_vendor_ocr_name where template_id = $template_id and table_no = $index and row_no = $row_no");
                    db_query("DELETE FROM water_vendor_additional_fee_master where template_id = $template_id and table_no = $index and row_no = $row_no");
              
                }
            }
        }
    }
    echo 1;
}

if (isset($_POST['form_action']) && $_POST['form_action'] == "remove_value") {
    if ($_POST['data_id'] != "") {
        $qry = db_query("DELETE FROM water_ocr_account_mapping_account_not_found where id='" . $_POST['data_id'] . "'", db());
    }
    echo 1;
}

if (isset($_POST['form_action']) && $_POST['form_action'] == "delete_template"){
    if ($_POST['template_id'] != "") {
        db();
        $qry = db_query("DELETE FROM water_ocr_account_mapping where unqid='" . $_POST['template_id'] . "'");
		$qry = db_query("DELETE FROM water_company_vendor_ocr_name where template_id='" . $_POST['template_id'] . "'");
        $qry = db_query("DELETE FROM water_ocr_account_mapping_account_not_found where template_id='" . $_POST['template_id'] . "'");
        $qry = db_query("DELETE FROM water_ocr_mapping_field_and_values where template_id='" . $_POST['template_id'] . "'");
        $qry = db_query("DELETE FROM water_ocr_mapping_item_list_table_header where template_id='" . $_POST['template_id'] . "'");
        $qry = db_query("DELETE FROM water_ocr_mapping_item_list_table_data where template_id='" . $_POST['template_id'] . "'");
        $qry = db_query("DELETE FROM water_ocr_all_generic_fields_dropdown where template_id='" . $_POST['template_id'] . "'");
        $qry = db_query("DELETE FROM water_ocr_consideration_of_tables_for_mapping where template_id='" . $_POST['template_id'] . "'");
    }
    echo 1;
}