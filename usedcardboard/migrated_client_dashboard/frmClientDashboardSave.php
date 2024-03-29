<?php
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
db();
?>
<?php
/***********************************Customer user list*********************************/
if (isset($_REQUEST['clientdash_username_edit']) || isset($_REQUEST['clientdash_flg'])) {

	if ($_REQUEST["clientdash_flg"] == "on") {
		$clientdash_flg = 1;
	} else {
		$clientdash_flg = 0;
	}

	$strQuery = "Update clientdashboard_usermaster set user_name = '" . $_REQUEST["clientdash_username_edit"] . "' , password = '" . $_REQUEST["clientdash_pwd_edit"] . "', client_email = '" . $_REQUEST["clientdash_username_edit"] . "', activate_deactivate = " . $clientdash_flg . " where loginid = " . $_REQUEST["loginid"];
	//echo $strQuery;
	$result = db_query($strQuery);
}
/***************************************Update Details****************************/
//if(isset($_REQUEST['clientdash_acc_owner']) || isset($_REQUEST['tollfree_no']) || isset($_REQUEST['office_no'])){
$uploadfilenm = "";
if (!empty($_FILES["companylogo"]["name"])) {
	$filetype = "jpg,jpeg,gif,png,PNG,JPG,JPEG";
	$allow_ext = explode(",", $filetype);
	if ($_FILES["companylogo"]["error"] > 0) {
		echo "Return Code: " . $_FILES["companylogo"]["error"] . "<br />";
	} else {
		echo $_FILES["companylogo"]["name"] . "<br>";
		$ext = pathinfo($_FILES["companylogo"]["name"], PATHINFO_EXTENSION);
		if (in_array(strtolower($ext), $allow_ext)) {
			move_uploaded_file($_FILES["companylogo"]["tmp_name"], "clientdashboard_images/" . $_FILES["companylogo"]["name"]);
			$uploadfilenm = $_FILES["companylogo"]["name"];

			$strQuery = "Update clientdashboard_details set logo_image = '" . $uploadfilenm . "' where companyid = " . $_REQUEST["clientdash_edituser_details_id"];
			//echo "<br /> strQuery1 - ".$strQuery;
			$result = db_query($strQuery);
		} else {
			echo "<font color=red>" . $_FILES["companylogo"]["name"] . " file not uploaded, this file type is restricted.</font>";
			echo "<script>alert('" . $_FILES["companylogo"]["name"] . " file not uploaded, this file type is restricted.');</script>";
			exit;
		}
	}
}


/******************************************Section list start******************************/
if (isset($_REQUEST['buyer_seller_flg'])) {
	$qry = "SELECT clientdashboard_section_details.section_id FROM clientdashboard_section_details INNER JOIN clientdashboard_section_master ON clientdashboard_section_master.section_id = clientdashboard_section_details.section_id WHERE companyid = " . $_REQUEST['ID'] . " AND buyer_seller = " . $_REQUEST['buyer_seller_flg'];
	$res = db_query($qry);
	$arrSectionListID = array(); 
	while ($fetch_data = array_shift($res)) {
		$arrSectionListID[] = $fetch_data['section_id'];
	}
	//echo "<pre>"; print_r($arrSectionListID); echo "</pre>";
	foreach ($arrSectionListID as $arrSectionListIDK => $arrSectionListIDV) {
		if ($_REQUEST["clientdash_sec_flg" . $arrSectionListIDV] == "on") {
			$clientdash_flg_sec = 1;
		} else {
			$clientdash_flg_sec = 0;
		}
		$strQuery = "UPDATE clientdashboard_section_details SET activate_deactivate = " . $clientdash_flg_sec . " WHERE companyid = " . $_REQUEST['ID'] . " AND section_id = " . $arrSectionListIDV;
		//echo "<br /> Section list strQuery - ".$strQuery;
		$result = db_query($strQuery);/**/
	}
	foreach ($arrSectionListID as $arrSectionListIDK => $arrSectionListIDV) {
		$qry_2 = "SELECT section_col_id FROM clientdashboard_section_col_master WHERE section_id = " . $arrSectionListIDV . " ORDER BY section_col_id";
		$res_2 = db_query($qry_2);
		$reccnt = tep_db_num_rows($res_2);
		if ($reccnt > 0) {
			$arrSectionListColumnId = array();
			while ($fetch_data_2 = array_shift($res_2)) {
				$arrSectionListColumnId[] = $fetch_data_2['section_col_id'];
			}
			//echo "<pre>"; print_r($arrSectionListColumnId); echo "</pre>";
			foreach ($arrSectionListColumnId as $arrSectionListColumnIdK => $arrSectionListColumnIdV) {
				if ($_REQUEST["clientdash_sec_col_flg" . $arrSectionListColumnIdV] == "on") {
					$clientdash_flg_sec = 1;
				} else {
					$clientdash_flg_sec = 0;
				}

				$strQuery = "UPDATE clientdashboard_section_col_details SET displayflg = " . $clientdash_flg_sec . " WHERE companyid = " . $_REQUEST['ID'] . " AND section_col_id = " . $arrSectionListColumnIdV;
				//echo "<br /> Section list column strQuery - ".$strQuery;
				$result = db_query($strQuery);
			}
		}
	}
}
/******************************************Section list ends**********************/
?>
<?php
echo "<script type=\"text/javascript\">";
echo "window.location.href=\"clientdashboard_setup.php?ID=" . encrypt_url($_REQUEST['ID']) . "\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" .encrypt_url($_REQUEST['ID']) . "\" />";
echo "</noscript>";
exit;
?>