<? 
$sales_rep_login = "no";
if (isset($_REQUEST["repchk"])) {
	if ($_REQUEST["repchk"] == "yes") {
		$sales_rep_login = "yes";
	}	
}else {
	require ("inc/header_session_client.php");
}

require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

	$start_date = (date("Y-m-d" , strtotime($_REQUEST["start_date"]))." 00:00:00");
	$end_date = (date("Y-m-d" , strtotime($_REQUEST["end_date"])). " 23:59:00");
	$client_companyid = $_REQUEST["compnewid"];
	
	$client_loopid_array = array();
	
	db();
	$sql1 = "SELECT id FROM loop_warehouse where b2bid = '" . $client_companyid . "' ";
	$result1 = db_query($sql1 );
	while ($myrowsel1 = array_shift($result1)) {
		$client_loopid = $myrowsel1["id"];
		$client_loopid_array[] = $client_loopid;
	}
	
	$child_comp = "";
	if ($_REQUEST['dView'] == 2) {
		db_b2b();
		$sql="SELECT ID FROM companyInfo WHERE parent_comp_id = " . $client_companyid ;
		$result = db_query($sql);
		while ($rq = array_shift($result)) {
			
			db();
			$sql1 = "SELECT id FROM loop_warehouse where b2bid = " . $rq["ID"] . " ";
			$result1 = db_query($sql1 );
			while ($myrowsel1 = array_shift($result1)) {
				$child_comp = $child_comp . $myrowsel1["id"] . ",";
			}
		}
		if (trim($child_comp) != ""){
			$child_comp = substr($child_comp, 0, strlen($child_comp) - 1);
		}
	}	
	
	db();
	
	if ($client_companyid == 1){
		$user_companies = db_query("SELECT company_id FROM boomerang_user_companies where user_id = '". $_REQUEST["user_id"] ."'");
		
		while($row = array_shift($user_companies)){
			$usr_company_id =  $row['company_id'];
			
			$sql1 = "SELECT id FROM loop_warehouse where b2bid = '" . $usr_company_id . "' ";
			$result1 = db_query($sql1 );
			while ($myrowsel1 = array_shift($result1)) {
				$client_loopid = $myrowsel1["id"];
				$client_loopid_array[] = $client_loopid;
			}
		}
	}

	
?>
	

	<!-- New report start  -->
		<?
		
		foreach ($client_loopid_array as $client_loopid)
		{
			db_b2b();
			$company_nm_qry = db_query("SELECT nickname FROM companyInfo where loopid = '" . $client_loopid . "'");
			$company_nm = array_shift($company_nm_qry);
			$company_name = $company_nm['nickname']; 
			db();
			
			/*************By Total start************/
			if ($child_comp != ""){
				$getData = "SELECT loop_boxes.*, loop_bol_tracking.qty, loop_boxes.bweight, loop_bol_tracking.box_id FROM loop_transaction_buyer LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id 
				WHERE loop_transaction_buyer.warehouse_id IN ( " . $client_loopid . "," . $child_comp . ") AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0 ";
			}else{
				$getData = "SELECT loop_boxes.*, loop_bol_tracking.qty, loop_boxes.bweight, loop_bol_tracking.box_id FROM loop_transaction_buyer LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id 
				WHERE loop_transaction_buyer.warehouse_id = " . $client_loopid . " AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0 ";
			}
			//echo "<br />".$getData;
			$resData = db_query($getData);
			$typeArr = $boxidArr = array();
			foreach($resData as $resDataK => $resDataVal){
				$boxidArr[$resDataVal['box_id']][]=$resDataVal;
			}
			//echo "<pre> boxidArr -> "; print_r($boxidArr); echo "</pre>";
			foreach($boxidArr as $boxidArrK => $boxidArrVal){
				//echo "<pre> boxidArrVal -> ".$boxidArrK; print_r($boxidArrVal); echo "</pre>";
				foreach($boxidArrVal as $boxidArrValK => $boxidArr2){
					if (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
						$typeArr[$boxidArrK]['Gaylord'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){ 
						$typeArr[$boxidArrK]['Shipping Box'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
						$typeArr[$boxidArrK]['SuperSack'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
						$typeArr[$boxidArrK]['Pallet'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Recycling", "Other", "Waste-to-Energy")))){ 
						$typeArr[$boxidArrK]['Recycling+Other'][]=$boxidArr2;
					}/*elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("DrumBarrelUCB", "DrumBarrelnonUCB")))){ 
						$typeArr[$boxidArrK]['Drums/Barrels/IBCs'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array(" ")))){ 
						$typeArr[$boxidArrK]['Type not selected'][]=$boxidArr2;
					}*/
				}
			}
			//echo "<pre> typeArr -> "; print_r($typeArr); echo "</pre>";
			$qtySum = 0; 
			$arrFields = array();
			foreach($typeArr as $typeArrK => $typeArrVal){
				//echo "<pre> typeArrVal -> "; print_r($typeArrVal); echo "</pre>";
				$i = 0;
				foreach($typeArrVal as $typeArrValK => $typeArr1){
					//echo "<pre> typeArr1 -> ".$typeArrK." / ".$typeArrValK."<br />"; print_r($typeArr1); echo "</pre>";
					$qtySum = array_sum(array_column($typeArr1, 'qty'));
					foreach($typeArr1 as $typeArr1K => $typeArr1Val){
						$arrFields[$typeArrK][$typeArrValK]['id'] = $typeArr1Val['id'];
						$arrFields[$typeArrK][$typeArrValK]['b2b_id'] = $typeArr1Val['b2b_id'];							
						$arrFields[$typeArrK][$typeArrValK]['isbox'] = $typeArr1Val['isbox'];							
						$arrFields[$typeArrK][$typeArrValK]['blength'] = $typeArr1Val['blength'];							
						$arrFields[$typeArrK][$typeArrValK]['blength_frac'] = $typeArr1Val['blength_frac'];							
						$arrFields[$typeArrK][$typeArrValK]['bwidth'] = $typeArr1Val['bwidth'];							
						$arrFields[$typeArrK][$typeArrValK]['bwidth_frac'] = $typeArr1Val['bwidth_frac'];							
						$arrFields[$typeArrK][$typeArrValK]['bdepth'] = $typeArr1Val['bdepth'];							
						$arrFields[$typeArrK][$typeArrValK]['bdepth_frac'] = $typeArr1Val['bdepth_frac'];							
						$arrFields[$typeArrK][$typeArrValK]['bwall'] = $typeArr1Val['bwall'];							
						$arrFields[$typeArrK][$typeArrValK]['bdescription'] = $typeArr1Val['bdescription'];							
						$arrFields[$typeArrK][$typeArrValK]['type'] = $typeArr1Val['type'];	
						$arrFields[$typeArrK][$typeArrValK]['box_id'] = $typeArr1Val['box_id'];	
						$arrFields[$typeArrK][$typeArrValK]['sumboxqty'] = $qtySum;
						$arrFields[$typeArrK][$typeArrValK]['boxweight'] = ($qtySum * $typeArr1Val['bweight']);
					}
					$i++;
				}
			}			
			//echo "<pre> arrFields -> "; print_r($arrFields); echo "</pre>";
			$arrRes = array();
			foreach($arrFields as $arrFieldsK => $arrFieldsVal){
				foreach($arrFieldsVal as $arrFieldsValK => $arrFields2){
					//echo "<br />".$arrFieldsK." / ".$arrFieldsValK;
					if($arrFieldsValK == 'Gaylord'){
						$arrRes[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Shipping Box'){
						$arrRes[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'SuperSack'){
						$arrRes[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Pallet'){
						$arrRes[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Recycling+Other'){
						$arrRes[$arrFieldsValK][] = $arrFields2;
					}/*elseif($arrFieldsValK == 'Drums/Barrels/IBCs'){
						$arrRes[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Type not selected'){
						$arrRes[$arrFieldsValK][] = $arrFields2;
					}*/
				}
			}
			//echo "<pre> arrRes -> "; print_r($arrRes); echo "</pre>";
			$res = array();

			//FOR MANUAL BOX
			//description1, description2 & description3
			if ($child_comp != ""){
				$getData1 = "SELECT loop_bol_tracking.description1, loop_bol_tracking.quantity1, weight1, loop_bol_tracking.box_id, loop_boxes.type FROM loop_transaction_buyer LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id WHERE loop_bol_tracking.description1 <> '' and  loop_bol_tracking.quantity1 > 0 and loop_transaction_buyer.warehouse_id IN ( " . $client_loopid . "," . $child_comp . ")  AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0";
		    }else {
				$getData1 = "SELECT loop_bol_tracking.description1, loop_bol_tracking.quantity1, weight1, loop_bol_tracking.box_id, loop_boxes.type FROM loop_transaction_buyer LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id WHERE loop_bol_tracking.description1 <> '' and  loop_bol_tracking.quantity1 > 0 and loop_transaction_buyer.warehouse_id = " . $client_loopid . " AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0";
			}
			//echo "<br />".$getData1;
			$resData1 = db_query($getData1);
			$typeArr1 = $boxidArr1 = array();
			foreach($resData1 as $resDataK => $resDataVal){
				$boxidArr1[$resDataVal['box_id']][]=$resDataVal;
			}
			//echo "<pre> boxidArr1 -> "; print_r($boxidArr1); echo "</pre>";
			foreach($boxidArr1 as $boxidArrK => $boxidArrVal){
				foreach($boxidArrVal as $boxidArrValK => $boxidArr2){
					if (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
						$typeArr1[$boxidArrK]['Gaylord'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){ 
						$typeArr1[$boxidArrK]['Shipping Box'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
						$typeArr1[$boxidArrK]['SuperSack'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
						$typeArr1[$boxidArrK]['Pallet'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Recycling", "Other", "Waste-to-Energy")))){ 
						$typeArr1[$boxidArrK]['Recycling+Other'][]=$boxidArr2;
					}/*elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("DrumBarrelUCB", "DrumBarrelnonUCB")))){ 
						$typeArr1[$boxidArrK]['Drums/Barrels/IBCs'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array(" ")))){ 
						$typeArr1[$boxidArrK]['Type not selected'][]=$boxidArr2;
					}*/
				}
			}
			//echo "<pre> typeArr1 -> "; print_r($typeArr1); echo "</pre>";
			$quantity1Sum = 0; 
			$arrFields1 = array();
			foreach($typeArr1 as $typeArrK => $typeArrVal){
				foreach($typeArrVal as $typeArrValK => $typeArr1){
					$quantity1Sum = array_sum(array_column($typeArr1, 'quantity1'));
					foreach($typeArr1 as $typeArr1K => $typeArr1Val){
						$arrFields1[$typeArrK][$typeArrValK]['description1'] = $typeArr1Val['description1'];
						$arrFields1[$typeArrK][$typeArrValK]['type'] = $typeArr1Val['type'];	
						$arrFields1[$typeArrK][$typeArrValK]['box_id'] = $typeArr1Val['box_id'];	
						$arrFields1[$typeArrK][$typeArrValK]['sumboxqty'] = $quantity1Sum;
						$arrFields1[$typeArrK][$typeArrValK]['boxweight'] = ($quantity1Sum * $typeArr1Val['weight1']);
					}
				}
			}			
			//echo "<pre> arrFields1 -> "; print_r($arrFields1); echo "</pre>";
			$arrRes1 = array();
			foreach($arrFields1 as $arrFieldsK => $arrFieldsVal){
				foreach($arrFieldsVal as $arrFieldsValK => $arrFields2){
					//echo "<br />".$arrFieldsK." / ".$arrFieldsValK;
					if($arrFieldsValK == 'Gaylord'){
						$arrRes1[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Shipping Box'){
						$arrRes1[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'SuperSack'){
						$arrRes1[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Pallet'){
						$arrRes1[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Recycling+Other'){
						$arrRes1[$arrFieldsValK][] = $arrFields2;
					}/*elseif($arrFieldsValK == 'Drums/Barrels/IBCs'){
						$arrRes1[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Type not selected'){
						$arrRes1[$arrFieldsValK][] = $arrFields2;
					}*/
				}
			}
			//echo "<pre> arrRes1 -> "; print_r($arrRes1); echo "</pre>";
			$res1 = array();

			if ($child_comp != ""){
				$getData2 = "SELECT loop_bol_tracking.description2, loop_bol_tracking.quantity2, weight2, loop_bol_tracking.box_id, loop_boxes.type FROM loop_transaction_buyer LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id WHERE loop_bol_tracking.description2 <> '' and  loop_bol_tracking.quantity2 > 0 and loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp . ") AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0";
			}else{
				$getData2 = "SELECT loop_bol_tracking.description2, loop_bol_tracking.quantity2, weight2, loop_bol_tracking.box_id, loop_boxes.type FROM loop_transaction_buyer LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id WHERE loop_bol_tracking.description2 <> '' and  loop_bol_tracking.quantity2 > 0 and loop_transaction_buyer.warehouse_id = " . $client_loopid . " AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0";
			}
			//echo "<br />".$getData2;
			$resData2 = db_query($getData2);
			$typeArr2 = $boxidArr2 = array();
			foreach($resData2 as $resDataK => $resDataVal){
				$boxidArr2[$resDataVal['box_id']][]=$resDataVal;
			}
			//echo "<pre> boxidArr2 -> "; print_r($boxidArr2); echo "</pre>";
			foreach($boxidArr2 as $boxidArrK => $boxidArrVal){
				foreach($boxidArrVal as $boxidArrValK => $boxidArrV2){
					if (in_array(strtolower(trim($boxidArrV2['type'])), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
						$typeArr2[$boxidArrK]['Gaylord'][]=$boxidArrV2;
					}elseif (in_array(strtolower(trim($boxidArrV2['type'])), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){ 
						$typeArr2[$boxidArrK]['Shipping Box'][]=$boxidArrV2;
					}elseif (in_array(strtolower(trim($boxidArrV2['type'])), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
						$typeArr2[$boxidArrK]['SuperSack'][]=$boxidArrV2;
					}elseif (in_array(strtolower(trim($boxidArrV2['type'])), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
						$typeArr2[$boxidArrK]['Pallet'][]=$boxidArrV2;
					}elseif (in_array(strtolower(trim($boxidArrV2['type'])), array_map('strtolower', array("Recycling", "Other", "Waste-to-Energy")))){ 
						$typeArr2[$boxidArrK]['Recycling+Other'][]=$boxidArrV2;
					}/*elseif (in_array(strtolower(trim($boxidArrV2['type'])), array_map('strtolower', array("DrumBarrelUCB", "DrumBarrelnonUCB")))){ 
						$typeArr2[$boxidArrK]['Drums/Barrels/IBCs'][]=$boxidArrV2;
					}elseif (in_array(strtolower(trim($boxidArrV2['type'])), array_map('strtolower', array(" ")))){ 
						$typeArr2[$boxidArrK]['Type not selected'][]=$boxidArrV2;
					}*/
				}
			}
			//echo "<pre> typeArr2 -> "; print_r($typeArr2); echo "</pre>";
			$quantity2Sum = 0; 
			$arrFields2 = array();
			foreach($typeArr2 as $typeArrK => $typeArrVal){
				foreach($typeArrVal as $typeArrValK => $typeArr1){
					$quantity2Sum = array_sum(array_column($typeArr1, 'quantity2'));
					foreach($typeArr1 as $typeArr1K => $typeArr1Val){
						$arrFields2[$typeArrK][$typeArrValK]['description2'] = $typeArr1Val['description2'];
						$arrFields2[$typeArrK][$typeArrValK]['type'] = $typeArr1Val['type'];	
						$arrFields2[$typeArrK][$typeArrValK]['box_id'] = $typeArr1Val['box_id'];	
						$arrFields2[$typeArrK][$typeArrValK]['sumboxqty'] = $quantity2Sum;
						$arrFields2[$typeArrK][$typeArrValK]['boxweight'] = ($quantity2Sum * $typeArr1Val['weight2']);
					}
				}
			}			
			//echo "<pre> arrFields2 -> "; print_r($arrFields2); echo "</pre>";
			$arrRes2 = array();
			foreach($arrFields2 as $arrFieldsK => $arrFieldsVal){
				foreach($arrFieldsVal as $arrFieldsValK => $arrFields2){
					//echo "<br />".$arrFieldsK." / ".$arrFieldsValK;
					if($arrFieldsValK == 'Gaylord'){
						$arrRes2[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Shipping Box'){
						$arrRes2[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'SuperSack'){
						$arrRes2[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Pallet'){
						$arrRes2[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Recycling+Other'){
						$arrRes2[$arrFieldsValK][] = $arrFields2;
					}/*elseif($arrFieldsValK == 'Drums/Barrels/IBCs'){
						$arrRes2[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Type not selected'){
						$arrRes2[$arrFieldsValK][] = $arrFields2;
					}*/
				}
			}
			//echo "<pre> arrRes2 -> "; print_r($arrRes2); echo "</pre>";
			$res2 = array();

			if ($child_comp != ""){
				$getData3 = "SELECT loop_bol_tracking.description3, loop_bol_tracking.quantity3, weight3, loop_bol_tracking.box_id, loop_boxes.type FROM loop_transaction_buyer LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id WHERE loop_bol_tracking.description3 <> '' and  loop_bol_tracking.quantity3 > 0 and loop_transaction_buyer.warehouse_id IN ( " . $client_loopid . "," . $child_comp . ") AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0";
			}else{
				$getData3 = "SELECT loop_bol_tracking.description3, loop_bol_tracking.quantity3, weight3, loop_bol_tracking.box_id, loop_boxes.type FROM loop_transaction_buyer LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id WHERE loop_bol_tracking.description3 <> '' and  loop_bol_tracking.quantity3 > 0 and loop_transaction_buyer.warehouse_id = " . $client_loopid . " AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0";
			}
			//echo "<br />".$getData3;
			$resData3 = db_query($getData3);
			$typeArr3 = $boxidArr3 = array();
			foreach($resData3 as $resDataK => $resDataVal){
				$boxidArr3[$resDataVal['box_id']][]=$resDataVal;
			}
			//echo "<pre> boxidArr3 -> "; print_r($boxidArr3); echo "</pre>";
			foreach($boxidArr3 as $boxidArrK => $boxidArrVal){
				foreach($boxidArrVal as $boxidArrValK => $boxidArr2){
					if (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
						$typeArr3[$boxidArrK]['Gaylord'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){ 
						$typeArr3[$boxidArrK]['Shipping Box'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
						$typeArr3[$boxidArrK]['SuperSack'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
						$typeArr3[$boxidArrK]['Pallet'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("Recycling", "Other", "Waste-to-Energy")))){ 
						$typeArr3[$boxidArrK]['Recycling+Other'][]=$boxidArr2;
					}/*elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array("DrumBarrelUCB", "DrumBarrelnonUCB")))){ 
						$typeArr3[$boxidArrK]['Drums/Barrels/IBCs'][]=$boxidArr2;
					}elseif (in_array(strtolower(trim($boxidArr2['type'])), array_map('strtolower', array(" ")))){ 
						$typeArr3[$boxidArrK]['Type not selected'][]=$boxidArr2;
					}*/
				}
			}
			//echo "<pre> typeArr3 -> "; print_r($typeArr3); echo "</pre>";
			$quantity3Sum = 0; 
			$arrFields3 = array();
			foreach($typeArr3 as $typeArrK => $typeArrVal){
				foreach($typeArrVal as $typeArrValK => $typeArr1){
					$quantity3Sum = array_sum(array_column($typeArr1, 'quantity3'));
					foreach($typeArr1 as $typeArr1K => $typeArr1Val){
						$arrFields3[$typeArrK][$typeArrValK]['description3'] = $typeArr1Val['description1'];
						$arrFields3[$typeArrK][$typeArrValK]['type'] = $typeArr1Val['type'];	
						$arrFields3[$typeArrK][$typeArrValK]['box_id'] = $typeArr1Val['box_id'];	
						$arrFields3[$typeArrK][$typeArrValK]['sumboxqty'] = $quantity3Sum;
						$arrFields3[$typeArrK][$typeArrValK]['boxweight'] = ($quantity3Sum * $typeArr1Val['weight3']);
					}
				}
			}			
			//echo "<pre> arrFields3 -> "; print_r($arrFields3); echo "</pre>";
			$arrRes3 = array();
			foreach($arrFields3 as $arrFieldsK => $arrFieldsVal){
				foreach($arrFieldsVal as $arrFieldsValK => $arrFields2){
					//echo "<br />".$arrFieldsK." / ".$arrFieldsValK;
					if($arrFieldsValK == 'Gaylord'){
						$arrRes3[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Shipping Box'){
						$arrRes3[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'SuperSack'){
						$arrRes3[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Pallet'){
						$arrRes3[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Recycling+Other'){
						$arrRes3[$arrFieldsValK][] = $arrFields2;
					}/*elseif($arrFieldsValK == 'Drums/Barrels/IBCs'){
						$arrRes3[$arrFieldsValK][] = $arrFields2;
					}elseif($arrFieldsValK == 'Type not selected'){
						$arrRes3[$arrFieldsValK][] = $arrFields2;
					}*/
				}
			}
			//echo "<pre> arrRes3 -> "; print_r($arrRes3); echo "</pre>";
			$res3 = array();
		/*************By Total end************/

		/*************By Trailer start************/
			if ($child_comp != ""){
				$getTrailerDt = "SELECT loop_transaction_buyer.id, loop_bol_tracking.bol_pickupdate, loop_bol_tracking.trailer_no, loop_bol_tracking.qty, loop_bol_tracking.quantity1, loop_bol_tracking.quantity2, loop_bol_tracking.quantity3, loop_boxes.type FROM loop_transaction_buyer INNER JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id WHERE loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp . ")  AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0 ORDER BY loop_bol_tracking.bol_pickupdate DESC";
			}else{
				$getTrailerDt = "SELECT loop_transaction_buyer.id, loop_bol_tracking.bol_pickupdate, loop_bol_tracking.trailer_no, loop_bol_tracking.qty, loop_bol_tracking.quantity1, loop_bol_tracking.quantity2, loop_bol_tracking.quantity3, loop_boxes.type FROM loop_transaction_buyer INNER JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id WHERE loop_transaction_buyer.warehouse_id = ". $client_loopid  ." AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' AND loop_transaction_buyer.ignore = 0 ORDER BY loop_bol_tracking.bol_pickupdate DESC";
			}
			//echo " <br /> getTrailerDt - ".$getTrailerDt;
			$resTrailerDt = db_query($getTrailerDt, db());
			//echo "<pre> resTrailerDt -> "; print_r($resTrailerDt); echo "</pre>";exit();
			$typeTrailerArr = $groupidArr = array();
			foreach($resTrailerDt as $resTrailerDtK => $resTrailerDtVal){
				$groupidArr[$resTrailerDtVal['id']][]=$resTrailerDtVal;
			}
			//echo "<pre> groupidArr -> "; print_r($groupidArr); echo "</pre>";
			foreach($groupidArr as $groupidArrK => $groupidArrVal){
				//echo "<pre> groupidArrVal -> ".$groupidArrK; print_r($groupidArrVal); echo "</pre>";
				foreach($groupidArrVal as $groupidArrValK => $groupidArrVal2){
					if (in_array(strtolower(trim($groupidArrVal2['type'])), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
						$typeTrailerArr[$groupidArrK]['Gaylord'][]=$groupidArrVal2;
					}elseif (in_array(strtolower(trim($groupidArrVal2['type'])), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){ 
						$typeTrailerArr[$groupidArrK]['Shipping Box'][]=$groupidArrVal2;
					}elseif (in_array(strtolower(trim($groupidArrVal2['type'])), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
						$typeTrailerArr[$groupidArrK]['SuperSack'][]=$groupidArrVal2;
					}elseif (in_array(strtolower(trim($groupidArrVal2['type'])), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
						$typeTrailerArr[$groupidArrK]['Pallet'][]=$groupidArrVal2;
					}elseif (in_array(strtolower(trim($groupidArrVal2['type'])), array_map('strtolower', array("Recycling", "Other", "Waste-to-Energy")))){ 
						$typeTrailerArr[$groupidArrK]['Recycling+Other'][]=$groupidArrVal2;
					}/*elseif (in_array(strtolower(trim($groupidArrVal2['type'])), array_map('strtolower', array("DrumBarrelUCB", "DrumBarrelnonUCB")))){ 
						$typeTrailerArr[$groupidArrK]['Drums/Barrels/IBCs'][]=$groupidArrVal2;
					}elseif (in_array(strtolower(trim($groupidArrVal2['type'])), array_map('strtolower', array(" ")))){ 
						$typeTrailerArr[$groupidArrK]['Type not selected'][]=$groupidArrVal2;
					}*/
				}
			}
			//echo "<pre> typeTrailerArr -> "; print_r($typeTrailerArr); echo "</pre>";
			$boxqtySum = $boxquantity1Sum = $boxquantity2Sum = $boxquantity3Sum = 0; 
			$arrTrailerFields = array();
			foreach($typeTrailerArr as $typeTrailerArrK => $typeTrailerArrVal){
				//echo "<pre> typeTrailerArrVal -> "; print_r($typeTrailerArrVal); echo "</pre>";
				foreach($typeTrailerArrVal as $typeTrailerArrValK => $typeTrailerArr1){
					//echo "<pre> typeTrailerArr1 -> ".$typeTrailerArrK." / ".$typeTrailerArrValK."<br />"; print_r($typeTrailerArr1); echo "</pre>";
					$boxqtySum = array_sum(array_column($typeTrailerArr1, 'qty'));
					$boxquantity1Sum = array_sum(array_column($typeTrailerArr1, 'quantity1'));
					$boxquantity2Sum = array_sum(array_column($typeTrailerArr1, 'quantity2'));
					$boxquantity3Sum = array_sum(array_column($typeTrailerArr1, 'quantity3'));
				
					foreach($typeTrailerArr1 as $typeTrailerArr1K => $typeTrailerArr1Val){
						$arrTrailerFields[$typeTrailerArrK][$typeTrailerArrValK]['id'] = $typeTrailerArr1Val['id'];
						$arrTrailerFields[$typeTrailerArrK][$typeTrailerArrValK]['bol_pickupdate'] = $typeTrailerArr1Val['bol_pickupdate'];
						$arrTrailerFields[$typeTrailerArrK][$typeTrailerArrValK]['trailer_no'] = $typeTrailerArr1Val['trailer_no'];
						$arrTrailerFields[$typeTrailerArrK][$typeTrailerArrValK]['type'] = $typeTrailerArr1Val['type'];
						$arrTrailerFields[$typeTrailerArrK][$typeTrailerArrValK]['boxqty'] = $boxqtySum;
						$arrTrailerFields[$typeTrailerArrK][$typeTrailerArrValK]['sumboxqty1'] = '';
						$arrTrailerFields[$typeTrailerArrK][$typeTrailerArrValK]['sumboxqty2'] = '';
						$arrTrailerFields[$typeTrailerArrK][$typeTrailerArrValK]['sumboxqty3'] = '';
					}
				}
			}			
			//echo "<pre> arrTrailerFields -> "; print_r($arrTrailerFields); echo "</pre>";
			$arrTrailerRes = array();
			foreach($arrTrailerFields as $arrTrailerFieldsK => $arrTrailerFieldsVal){
				foreach($arrTrailerFieldsVal as $arrTrailerFieldsValK => $arrTrailerFields2){
					//echo "<br />".$arrTrailerFieldsK." / ".$arrFieldsValK;
					if($arrTrailerFieldsValK == 'Gaylord'){
						$arrTrailerRes[$arrTrailerFieldsValK][] = $arrTrailerFields2;
					}elseif($arrTrailerFieldsValK == 'Shipping Box'){
						$arrTrailerRes[$arrTrailerFieldsValK][] = $arrTrailerFields2;
					}elseif($arrTrailerFieldsValK == 'SuperSack'){
						$arrTrailerRes[$arrTrailerFieldsValK][] = $arrTrailerFields2;
					}elseif($arrTrailerFieldsValK == 'Pallet'){
						$arrTrailerRes[$arrTrailerFieldsValK][] = $arrTrailerFields2;
					}elseif($arrTrailerFieldsValK == 'Recycling+Other'){
						$arrTrailerRes[$arrTrailerFieldsValK][] = $arrTrailerFields2;
					}/*elseif($arrTrailerFieldsValK == 'Drums/Barrels/IBCs'){
						$arrTrailerRes[$arrTrailerFieldsValK][] = $arrTrailerFields2;
					}elseif($arrTrailerFieldsValK == 'Type not selected'){
						$arrTrailerRes[$arrTrailerFieldsValK][] = $arrTrailerFields2;
					}*/
				}
			}
			//echo "<pre> arrTrailerRes -> "; print_r($arrTrailerRes); echo "</pre>";
			$resTrailer = array();	
		/*************By Trailer end************/

		
		$box_name_arr= array('Gaylord', 'Shipping Box', 'SuperSack', 'Pallet', 'Recycling+Other' );
		?>
		<br><br>
		<table width="70%" cellSpacing="1" cellPadding="1" border="0" >
			<thead>
				<tr align="middle">
					<th colSpan="10" >
					 </th>
				</tr>
				<tr align="middle">
					<th colSpan="10" style="background: #abc5df;">
						<b><? echo $company_name?></b>
					</th>
				</tr>
				<tr align="middle">
					<th colSpan="10" style="background: #abc5df;">
						<b>Box Report (By Total)</b>
					</th>
				</tr>
		
			<?
			$boxweight = 0;  $countDt = 0; $in_top_tbl_flg = "no";
			foreach($box_name_arr as $box_name){
				$countDt = tep_db_num_rows($arrRes[$box_name]);
				//echo "<br />".$countDt;
				
				if ($countDt >0) { 
					$res = $arrRes[$box_name];
					$in_top_tbl_flg = "yes";
					?>
				
					<tr align="middle">
						<th colSpan="2" style="background: #abc5df;">
						<b><?=strtoupper($box_name);?> DATA</b></th>
					</tr>
					<tr>
						<th style="width: 70%; background: #abc5df;" align="center" >
						<b>BOX DESCRIPTION</b></font></th>
						<th style="width: 30%; background: #abc5df;" align="center" >
						<b>BOX QTY</b></font></th>
					</tr>
					
					<?
					$goodtot = 0; /*$boxweight = 0; */$trees = 0;
					$i = 0;
					while($row = array_shift($res)){
						if ($row["sumboxqty"] > 0 ) {
							if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
							?>
							<tr class="<?=$rowclr?>">
								<td class="style12left" align="left">
									<? if ($row["isbox"]=='Y') { ?>
										<? echo $row["blength"];?> <? echo $row["blength_frac"];?> x <? echo $row["bwidth"];?> <? echo $row["bwidth_frac"];?> x  <? echo $row["bdepth"];?> <? echo $row["bdepth_frac"];?>
								  <? } ?>

								  <?
									if ($row["bwall"] > 1)
									{
										echo " " . $row["bwall"] . "-Wall ";
									}
								  ?>
								  <? echo $row["bdescription"];?></td>

								  <td class="style12right" ><? echo number_format($row["sumboxqty"],0);?></td>
							</tr>
							<?
							$goodtot += $row["sumboxqty"];
							$boxweight += $row["boxweight"]; 

							$i++;
						}						
					}

					/*//DESCRIPTION 1
					$res1 = $arrRes1[$box_name];
					$i = 0;
					while($row = array_shift($res1)) {
						if ($row["sumboxqty"] > 0 ) {
							if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
							?>
							<tr class="<?=$rowclr?>">
								<td class="style12left" align="left">
								  <? echo $row["description1"];?></td>

								<td class="style12right" ><? echo number_format($row["sumboxqty"],0);?></td>
							</tr>
							
							<?
							$goodtot += $row["sumboxqty"];
							$boxweight += $row["boxweight"]; 

							$i++;
						}						
					}

					//DESCRIPTION 2
					$res2 = $arrRes2[$box_name];
					$i = 0;
					while($row = array_shift($res2)){
						if ($row["sumboxqty"] > 0 ) {
							if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
							?>
							<tr class="<?=$rowclr?>">
								<td class="style12left" align="left">
								  <? echo $row["description2"];?> </td>

								<td class="style12right" ><? echo number_format($row["sumboxqty"],0);?></td>
							</tr>
							
							<?
							$goodtot += $row["sumboxqty"];
							$boxweight += $row["boxweight"]; 

							$i++;
						}						
					}

					//DESCRIPTION 3
					$res3 = $arrRes3[$box_name];
					$i = 0;
					while($row = array_shift($res3)){
						if ($row["sumboxqty"] > 0 ){
							if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
							?>
							<tr class="<?=$rowclr?>">
								<td class="style12left" align="left">
								  <? echo $row["description3"];?></td>

								<td class="style12right" ><? echo number_format($row["sumboxqty"],0);?></td>
							</tr>
							<?
							$goodtot += $row["sumboxqty"];
							$boxweight += $row["boxweight"]; 

							$i++;
						}						
					}
					
					*/
					?>
					
					<tr>
					  <td bgColor="#e4e4e4" class="style12right" >
					  <strong>BOX TOTAL</strong></td>
					  <td bgColor="#e4e4e4" class="style_total" ><strong><? echo number_format($goodtot, 0);?></strong></td>
					</tr>
					<?
				}
			}
			?>
		</table>
		
		<? if ($in_top_tbl_flg == "yes") { ?>
			<table width="70%" cellspacing="1" cellpadding="1" border="0" class="mb-10">
				<tbody>
				<tr class="headrow"><td colspan="2" >Grand total</td></tr>
				<?
				$rowno = 1;
				$grandTotal = 0;
				foreach($box_name_arr as $box_name){
					$countDt = tep_db_num_rows($arrRes[$box_name]);					
					if ($countDt >0) { 
						$res = $arrRes[$box_name];

						$goodtot = 0; 
						while($row = array_shift($res)){
							if ($row["sumboxqty"] > 0 ) {								
								$goodtot += $row["sumboxqty"];
							}						
						}

						/*//DESCRIPTION 1
						$res1 = $arrRes1[$box_name];
						while($row = array_shift($res1)) {
							if ($row["sumboxqty"] > 0 ) {								
								$goodtot += $row["sumboxqty"];
							}						
						}

						//DESCRIPTION 2
						$res2 = $arrRes2[$box_name];
						while($row = array_shift($res2)){
							if ($row["sumboxqty"] > 0 ) {								
								$goodtot += $row["sumboxqty"];
							}						
						}

						//DESCRIPTION 3
						$res3 = $arrRes3[$box_name];
						while($row = array_shift($res3)){
							if ($row["sumboxqty"] > 0 ){								
								$goodtot += $row["sumboxqty"];
							}						
						}*/
						
						$grandTotal = $grandTotal + $goodtot;

						if ($rowno % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
						?>
						<tr class="<?=$rowclr?>">
							<td><?=strtoupper($box_name);?> DATA</td>
							<td align="right"><?=number_format($goodtot, 0);?></td>
						</tr>
						<?
						$rowno++;
					}
				}
				?>
				<tr class="">
					<td><b>GRAND DATA</b></td>
					<td align="right"><strong><?=number_format($grandTotal, 0);?></strong></td>
				</tr>
				
			<? $trees = ($boxweight / 2000) * 17; ?>
			<tr>
			  <td colspan="2" >
				<font size=2>
					<img src="images/trees.jpg"> Did you know you would have to cut down <b><?= number_format($trees); ?></b> trees to make as many boxes as you rescued?
				</font>
			  </td>
			</tr>

			</tbody>
		</table>
		
	<? }
		
	}?>
<!--		
		<table cellSpacing="1" cellPadding="1" width="80%" border="0">
			<tr align="middle">
				<td colSpan="3" class="style12left">
				*Please click a trailer number to view details
				</td>
			</tr>	
			<tr align="middle">
				<td colSpan="3" class="style7">
				<b>Box Order Report (By Trailer)</b></td>
			</tr>
			<?
			foreach($box_name_arr as $box_name){
				$countDt = tep_db_num_rows($arrTrailerRes[$box_name]);
				//echo "<br />".$countDt;
				$res = $arrTrailerRes[$box_name];
				?>
			
				<tr align="middle">
					<td colSpan="3" class="style7">
					<b><?=strtoupper($box_name);?> DATA</b></td>
				</tr>
				<tr>
					<td class="style17" align="center">
					<b>DATE SHIPPED</b></font></td>
					<td class="style17" align="center">
					<b>TRAILER #</b></font></td>
					<td align="middle" class="style16" align="center">
					<b>BOX QTY</b></td>		
				</tr>
				<?
				$grandtotal = 0; $running_cnt = 0;
				$i = 0;
				while($row = array_shift($res)){
					if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
					?>
					<tr vAlign="center" class="<?=$rowclr?>">
						<td <? if (($row["trailer_no"] == $_REQUEST["trailer"]) && $row["trailer_no"] != "") { echo " bgColor=yellow "; } else { echo " bgColor=#e4e4e4 "; }; ?> class="style3"  align="center">
							<? echo date('m-d-Y', strtotime($row["bol_pickupdate"])); ?></td>
						 <td <? if (($row["trailer_no"] == $_REQUEST["trailer"]) && $row["trailer_no"] != "") { echo " bgColor=yellow "; } else { echo " bgColor=#e4e4e4 "; }; ?> class="style3"  align="center">	
							<div id="boxreport_trailerdiv<? echo $running_cnt ?>" onclick="boxreport_trailer('<? echo $sales_rep_login; ?>', '<? echo $row["trailer_no"]; ?>', <? echo $row["id"]; ?>,  <? echo $running_cnt; ?>)"><font color=blue><u><? echo $row["trailer_no"]; ?></u></font></div>
						</td> 
						<td <? if (($row["trailer_no"] == $_REQUEST["trailer"]) && $row["trailer_no"] != "") { echo " bgColor=yellow "; } else { echo " bgColor=#e4e4e4 "; }; ?> class="style3" align="right">
							
						<? 
						$grandtotal += $row["boxqty"] + $row["sumboxqty1"] + $row["sumboxqty2"] + $row["sumboxqty3"];
						echo $row["boxqty"] + $row["sumboxqty1"] + $row["sumboxqty2"] + $row["sumboxqty3"];?>
							
						</td>
					</tr>
					<?
					$running_cnt = $running_cnt + 1;
					$i++;
				}
				?>					
				<tr>
					<td class="style3" colspan="2" align="right">
						<b>TOTAL</b></font>
					</td>
					<td class="style_total" align="right">
						<b><? echo $grandtotal; ?></b></font>
					</td>
					<td class="style3" >
					</td>
				</tr>
				<?
			}
		?>
			</table>
-->
	<!-- New report end  -->
<!-- <table width="80%" cellSpacing="1" cellPadding="1" border="0" >

	<tr align="middle">
		<td colSpan="10" class="style12left">
		 </td>
	</tr>	

	<tr align="middle">
		<td colSpan="10" class="style7">
			<b>Box Report (By Total)</b>
		</td>
	</tr>
	<tr>
		<td style="width: 100" class="style17" align="center">
		<b>BOX DESCRIPTION</b></font></td>
		<td style="width: 40" class="style17" align="center">
		<b>BOX QTY</b></font></td>
	</tr>

	<?
	/*if ($child_comp != ""){
		$query = "SELECT loop_boxes.*, sum(loop_bol_tracking.qty) sumboxqty, sum(loop_bol_tracking.qty)*loop_boxes.bweight AS boxweight FROM loop_transaction_buyer";
		$query .= " LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id " ;
		$query .= " INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id ";
		$query .= " WHERE loop_transaction_buyer.warehouse_id IN ( " . $client_loopid . "," . $child_comp . ") AND ";
		$query .= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "'";
		$query .= " AND loop_transaction_buyer.ignore = 0 GROUP BY loop_bol_tracking.box_id ORDER BY sumboxqty DESC";
	}else{
		$query = "SELECT loop_boxes.*, sum(loop_bol_tracking.qty) sumboxqty, sum(loop_bol_tracking.qty)*loop_boxes.bweight AS boxweight FROM loop_transaction_buyer";
		$query .= " LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id " ;
		$query .= " INNER JOIN loop_boxes ON loop_boxes.id = loop_bol_tracking.box_id ";
		$query .= " WHERE loop_transaction_buyer.warehouse_id = " . $client_loopid . " AND ";
		$query .= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "'";
		$query .= "  AND loop_transaction_buyer.ignore = 0 GROUP BY loop_bol_tracking.box_id ORDER BY sumboxqty DESC";
		
	}
	//AND loop_boxes.isbox LIKE 'Y'
	//echo $query;
	
	$goodtot = 0; $boxweight = 0;
	$res = db_query($query);
	$i = 0;
	while($row = array_shift($res)) {
		if ($row["sumboxqty"] > 0 ) {
			if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
			?>
			<tr class="<?=$rowclr?>">
				  <td class="style12left" align="left">
				  <? if ($row["isbox"]=='Y') { ?>
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $row["blength"];?> <? echo $row["blength_frac"];?> x
				  <? echo $row["bwidth"];?> <? echo $row["bwidth_frac"];?> x 
				  <? echo $row["bdepth"];?> <? echo $row["bdepth_frac"];?>
				  <? } ?>

				  <?
					if ($row["bwall"] > 1)
					{
						echo " " . $row["bwall"] . "-Wall ";
					}
				  ?>
				  <? echo $row["bdescription"];?> 11111111</td>

				  <td class="style12right" ><? echo number_format($row["sumboxqty"],0);?></td>
			</tr>
			<?
			$goodtot += $row["sumboxqty"];
			$boxweight += $row["boxweight"]; 
		}
		$i++;
	}
	
	//For manual box
	if ($child_comp != ""){
		$query = "SELECT loop_bol_tracking.description1, sum(loop_bol_tracking.quantity1) sumboxqty, sum(loop_bol_tracking.quantity1)* weight1 AS boxweight FROM loop_transaction_buyer";
		$query .= " LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id " ;
		$query .= " WHERE loop_transaction_buyer.warehouse_id IN ( " . $client_loopid . "," . $child_comp . ")  AND ";
		$query .= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "'";
		$query .= " AND loop_transaction_buyer.ignore = 0 GROUP BY loop_bol_tracking.box_id ORDER BY sumboxqty DESC";
    }else {
		$query = "SELECT loop_bol_tracking.description1, sum(loop_bol_tracking.quantity1) sumboxqty, sum(loop_bol_tracking.quantity1)*weight1 AS boxweight FROM loop_transaction_buyer";
		$query .= " LEFT JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id " ;
		$query .= " WHERE loop_transaction_buyer.warehouse_id = " . $client_loopid . " AND ";
		$query .= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' AND DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "'";
		$query .= " AND loop_transaction_buyer.ignore = 0 GROUP BY loop_bol_tracking.box_id ORDER BY sumboxqty DESC";
	}
	$res = db_query($query);
	$i = 0;
	while($row = array_shift($res)) {
		if ($row["sumboxqty"] > 0 ) {
			if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
			?>
			<tr class="<?=$rowclr?>">
				<td class="style12left" align="left">
				  <? echo $row["description1"];?> 22222222</td>

				<td class="style12right" ><? echo number_format($row["sumboxqty"],0);?></td>
			</tr>
			
			<?
			$goodtot += $row["sumboxqty"];
			$boxweight += $row["boxweight"]; 
		}
		$i++;
	}	
	
	//For manual box
	if ($child_comp != ""){
		$query = "Select loop_bol_tracking.description2, sum(loop_bol_tracking.quantity2) sumboxqty, sum(loop_bol_tracking.quantity2)*weight2 AS boxweight from loop_transaction_buyer";
		$query .= " left JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id " ;
		$query .= " where loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp . ") AND ";
		$query .= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' and DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "'";
		$query .= " and loop_transaction_buyer.ignore = 0 GROUP BY loop_bol_tracking.box_id ORDER BY sumboxqty DESC";
	}else{
		$query = "Select loop_bol_tracking.description2, sum(loop_bol_tracking.quantity2) sumboxqty, sum(loop_bol_tracking.quantity2)*weight2 AS boxweight from loop_transaction_buyer";
		$query .= " left JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id " ;
		$query .= " where loop_transaction_buyer.warehouse_id = " . $client_loopid . " AND ";
		$query .= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' and DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "'";
		$query .= " and loop_transaction_buyer.ignore = 0 GROUP BY loop_bol_tracking.box_id ORDER BY sumboxqty DESC";
	}
	//echo $query;
	$res = db_query($query);
	$i = 0;
	while($row = array_shift($res))
	{
		if ($row["sumboxqty"] > 0 ) 
		{
			if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
		?>
	 <tr class="<?=$rowclr?>">
		  <td class="style12left" align="left">
		  <? echo $row["description2"];?> 33333333333333</td>

		  <td class="style12right" ><? echo number_format($row["sumboxqty"],0);?></td>
	  </tr>
			
	<?
		$goodtot += $row["sumboxqty"];
		$boxweight += $row["boxweight"]; 
		}
		$i++;
	}	

	//For manual box
	if ($child_comp != ""){
		$query = "Select loop_bol_tracking.description3, sum(loop_bol_tracking.quantity3) sumboxqty, sum(loop_bol_tracking.quantity3)*weight3 AS boxweight from loop_transaction_buyer";
		$query .= " left JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id " ;
		$query .= " where loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp . ") AND ";
		$query .= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' and DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "'";
		$query .= " and loop_transaction_buyer.ignore = 0 GROUP BY loop_bol_tracking.box_id ORDER BY sumboxqty DESC";
	}else{
		$query = "Select loop_bol_tracking.description3, sum(loop_bol_tracking.quantity3) sumboxqty, sum(loop_bol_tracking.quantity3)*weight3 AS boxweight from loop_transaction_buyer";
		$query .= " left JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id " ;
		$query .= " where loop_transaction_buyer.warehouse_id = " . $client_loopid . " AND ";
		$query .= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' and DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "'";
		$query .= " and loop_transaction_buyer.ignore = 0 GROUP BY loop_bol_tracking.box_id ORDER BY sumboxqty DESC";
	}
	$res = db_query($query);
	$i = 0;
	while($row = array_shift($res))
	{
		if ($row["sumboxqty"] > 0 ) 
		{
			if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
		?>
	 <tr class="<?=$rowclr?>">
		  <td class="style12left" align="left">
		  <? echo $row["description3"];?> 44444444444</td>

		  <td class="style12right" ><? echo number_format($row["sumboxqty"],0);?></td>
	  </tr>
			
	<?
		$goodtot += $row["sumboxqty"];
		$boxweight += $row["boxweight"]; 
		}
		$i++;
	}	*/	
	?>
	<tr>
	  <td bgColor="#e4e4e4" class="style12right" >
	  <strong>BOX TOTAL</strong></td>
	  <td bgColor="#e4e4e4" class="style_total" ><strong><? echo number_format($goodtot, 0);?></strong></td>
	</tr>

	<? $trees = ($boxweight / 2000) * 17; ?>

	<tr>
	  <td colspan="2" >
		<font size=2>
			<img src="images/trees.jpg"> Did you know you would have to cut down <b><?= number_format($trees,2); ?></b> trees to make as many boxes as you rescued?
		</font>
	  </td>
	</tr>
		
	
</table>

<br/> -->

<!-- Box Order Report by Trailer-->
<!-- <table cellSpacing="1" cellPadding="1" width="80%" border="0">
	<tr align="middle">
		<td colSpan="3" class="style12left">
		*Please click a trailer number to view details
		</td>
	</tr>	
	<tr align="middle">
		<td colSpan="3" class="style7">
		<b>Box Order Report (By Trailer)</b></td>
	</tr>
	<tr>
		<td class="style17" align="center">
		<b>DATE SHIPPED</b></font></td>
		<td class="style17" align="center">
		<b>TRAILER #</b></font></td>
		<td align="middle" class="style16" align="center">
		<b>BOX QTY</b></td>		
	</tr>		

	
	<?

	if ($child_comp != ""){
		$query = "SELECT loop_transaction_buyer.id, loop_bol_tracking.bol_pickupdate, loop_bol_tracking.trailer_no, sum(loop_bol_tracking.qty) boxqty, sum(loop_bol_tracking.quantity1) sumboxqty1, sum(loop_bol_tracking.quantity2) sumboxqty2, sum(loop_bol_tracking.quantity3) sumboxqty3 FROM loop_transaction_buyer INNER JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id ";
		$query.= " WHERE loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp . ")  and ";
		$query.= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' and DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' and loop_transaction_buyer.ignore = 0 ";
		$query.= " group by loop_transaction_buyer.id ORDER BY loop_bol_tracking.bol_pickupdate DESC";
	}else{
		$query = "SELECT loop_transaction_buyer.id, loop_bol_tracking.bol_pickupdate, loop_bol_tracking.trailer_no, sum(loop_bol_tracking.qty) boxqty, sum(loop_bol_tracking.quantity1) sumboxqty1, sum(loop_bol_tracking.quantity2) sumboxqty2, sum(loop_bol_tracking.quantity3) sumboxqty3 FROM loop_transaction_buyer INNER JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id ";
		$query.= " WHERE loop_transaction_buyer.warehouse_id = ". $client_loopid  ." and ";
		$query.= " DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $start_date . "' and DATE_FORMAT(STR_TO_DATE(bol_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') <='" . $end_date . "' and loop_transaction_buyer.ignore = 0 ";
		$query.= " group by loop_transaction_buyer.id ORDER BY loop_bol_tracking.bol_pickupdate DESC";
	}
	//echo $query;
	//$so_view_qry = "SELECT * from loop_bol_files WHERE trans_rec_id LIKE '" . $_REQUEST["rec_id"] . "' AND bol_shipped = 1 ORDER BY id DESC";
	//$so_view_res = db_query($so_view_qry );

	$grandtotal = 0;
	$running_cnt = 0;
	$res = db_query($query);
	$i = 0;
	while($row = array_shift($res))
	{
		if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
	?>
		<tr vAlign="center" class="<?=$rowclr?>">
			<td <? if (($row["trailer_no"] == $_REQUEST["trailer"]) && $row["trailer_no"] != "") { echo " bgColor=yellow "; } else { echo " bgColor=#e4e4e4 "; }; ?> class="style3"  align="center">
				<? echo date('m-d-Y', strtotime($row["bol_pickupdate"])); ?></td>
			 <td <? if (($row["trailer_no"] == $_REQUEST["trailer"]) && $row["trailer_no"] != "") { echo " bgColor=yellow "; } else { echo " bgColor=#e4e4e4 "; }; ?> class="style3"  align="center">	
				<div id="boxreport_trailerdiv<? echo $running_cnt ?>" onclick="boxreport_trailer('<? echo $sales_rep_login; ?>', '<? echo $row["trailer_no"]; ?>', <? echo $row["id"]; ?>,  <? echo $running_cnt; ?>)"><font color=blue><u><? echo $row["trailer_no"]; ?></u></font></div>
			</td> 
			<td <? if (($row["trailer_no"] == $_REQUEST["trailer"]) && $row["trailer_no"] != "") { echo " bgColor=yellow "; } else { echo " bgColor=#e4e4e4 "; }; ?> class="style3" align="right">
				
			<? 
			$grandtotal += $row["boxqty"] + $row["sumboxqty1"] + $row["sumboxqty2"] + $row["sumboxqty3"];
			echo $row["boxqty"] + $row["sumboxqty1"] + $row["sumboxqty2"] + $row["sumboxqty3"];?>
				
			</td>
		</tr>
	<?
		$running_cnt = $running_cnt + 1;
		$i++;
	}
	?>	
	
		<tr>
			<td class="style3" colspan="2" align="right">
				<b>TOTAL</b></font>
			</td>
			<td class="style_total" align="right">
				<b><? echo $grandtotal; ?></b></font>
			</td>
			<td class="style3" >
			</td>
		</tr>
</table> -->