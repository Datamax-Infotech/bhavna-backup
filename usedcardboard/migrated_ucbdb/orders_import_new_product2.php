<?php 
// orders_import_new_product2.php
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

if (isset($_GET["submit"])){
	$sqltablename = "SELECT tablename FROM ucbdb_warehouse WHERE id= ". $_GET["warehouseid"]; 
	$tablenamearray = db_query($sqltablename,db() );
	$tablename = $tablenamearray[0]['tablename'];
	$sqlinst = "INSERT INTO ".$tablename."( `warehouse_id`, `orders_id`, `product_id`, `kit_id`,";
	$sqlinst .= "`module_id`, `module_name`, `description`, `weight`, `length1`, `width`, `height`, `reference`, `kits_name`,";
	$sqlinst .= "`shipping_name`, `shipping_attention`, `shipping_street1`, `shipping_street2`, `shipping_city`,";
	$sqlinst .= "`shipping_state`, `shipping_zip`, `ups_shipping_release`, `phone`, `email`)";
	$sqlinst .= "VALUES (".$_GET["warehouseid"].",".$_GET["id"].",".$_GET["product_id"].",".$_GET["kit_id"].",";
	$sqlinst .= $_GET["productmoduleid"].",'".$_GET["productmodule"]."','".$_GET["productdescription"]."','";
	$sqlinst .= $_GET["boxweight"]."','".$_GET["boxlength"]."','".$_GET["boxwidth"]."','".$_GET["boxheight"]."','";
	$sqlinst .= $_GET["reference"]."','".$_GET["kitsname"]."','".$_GET["shipname"]."','".$_GET["shipcompany"]."','";
	$sqlinst .= $_GET["shipstreet1"]."','".$_GET["shipstreet2"]."','".$_GET["shipcity"]."','".$_GET["shipstate"]."','";
	$sqlinst .= $_GET["shipzip"]."','".$_GET["shiprelease"]."','".$_GET["custphone"]."','".$_GET["custemail"]."')";

	$instresult = db_query($sqlinst,db());
	$insertid = tep_db_insert_id();

	if ($insertid != ""){	
		echo "One product added";
	}else{
		echo "Error in Import.";
	}
	
}

?>