<!DOCTYPE html>
<html>
<head>
	<title>Untitled</title>
</head>
<body>
	<?php
	require("mainfunctions/database.php");
	require("mainfunctions/general-functions.php");
	db();
	$id = decrypt_url($_GET['id']);
	$sql = "SELECT * FROM ucb_contact WHERE id = $id";
	$result = db_query($sql);
	if ($myrow = array_shift($result)) {
		do {
			$type_id = $myrow["type_id"];
			$first_name = $myrow["first_name"];
			$last_name = $myrow["last_name"];
			$title = $myrow["title"];
			$company = $myrow["company"];
			$industry = $myrow["industry"];
			$address1 = $myrow["address1"];
			$address2 = $myrow["address2"];
			$city = $myrow["city"];
			$state = $myrow["state"];
			$zip = $myrow["zip"];
			$phone1 = $myrow["phone1"];
			$phone2 = $myrow["phone2"];
			$email = $myrow["email"];
			$website = $myrow["website"];
			$order_no = $myrow["order_no"];
			$choose = $myrow["choose"];
			$ccheck = $myrow["ccheck"];
			$infomation = $myrow["infomation"];
			$help = $myrow["help"];
			$experience = $myrow["experience"];
			$mail_lists = $myrow["mail_lists"];
			$comments = $myrow["comments"];
			$sel_service = $myrow["sel_service"];
			$experiance = $myrow["experiance"];
			$is_export = $myrow["is_export"];
			$added_on = $myrow["added_on"];
			$have_permission = $myrow["have_permission"];
			echo "<BR>TYPE_ID:  $type_id";
			echo "<BR>FIRST_NAME:  $first_name";
			echo "<BR>LAST_NAME:  $last_name";
			echo "<BR>TITLE:  $title";
			echo "<BR>COMPANY:  $company";
			echo "<BR>INDUSTRY:  $industry";
			echo "<BR>ADDRESS1:  $address1";
			echo "<BR>ADDRESS2:  $address2";
			echo "<BR>CITY:  $city";
			echo "<BR>STATE:  $state";
			echo "<BR>ZIP:  $zip";
			echo "<BR>PHONE1:  $phone1";
			echo "<BR>PHONE2:  $phone2";
			echo "<BR>EMAIL:  $email";
			echo "<BR>WEBSITE:  $website";
			echo "<BR>ORDER_NO:  $order_no";
			echo "<BR>CHOOSE:  $choose";
			echo "<BR>CCHECK:  $ccheck";
			echo "<BR>INFOMATION:  $infomation";
			echo "<BR>HELP:  $help";
			echo "<BR>EXPERIENCE:  $experience";
			echo "<BR>MAIL_LISTS:  $mail_lists";
			echo "<BR>COMMENTS:  $comments";
			echo "<BR>SEL_SERVICE:  $sel_service";
			echo "<BR>EXPERIANCE:  $experiance";
			echo "<BR>IS_EXPORT:  $is_export";
			echo "<BR>ADDED_ON:  $added_on";
			echo "<BR>HAVE_PERMISSION:  $have_permission";
		} while ($myrow = array_shift($result));
	}
	?>
</body>

</html>