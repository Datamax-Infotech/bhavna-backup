<?php
/*
Page Name: UCBZeroWaste_Vendors_AP_AR_Excel.php
Page created By: Amarendra Singh
Page created On: 28-03-2023
Last Modified On: 
Last Modified By: Amarendra Singh
Change History:
Date           By            Description
================================================================================================
28-03-23      Amarendra     This file is create to export the result in excel file to download.
================================================================================================
*/

session_start();

require_once ("mainfunctions/database.php");
require_once ("mainfunctions/general-functions.php");

	$sep = "\t"; 
    $filename = "UCBZeroWaste_Vendors_AP_AR_Excel_" . date('Y-m-d') . ".xls"; 

	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");
	
    $result = $_SESSION['exportArray'];  
	if ($_REQUEST["from"] == "AR"){
		echo "Sr. No." . "\t" . "Vendor Name" . "\t" . "Vendor A/P Contact" . "\t" . "UCBZeroWaste Client Name" . "\t" . 
			"Service Month" . "\t" . "Invoice Number" . "\t" . "Scan of Invoice" . "\t" . "Invoice Date" . "\t" . "Invoice Due Date" . "\t" . "Invoice Age" . "\t" . 
			"Invoice Amount" . "\t" . "Vendor Payment Method to UCBZeroWaste" . "\t" . "Has UCBZW Received the Rebate?" . "\t" . "Log Notes Date " . "\t" . "Log Notes" . "\t";
	}else{

		echo "Sr. No." . "\t" . "Vendor Name" . "\t" . "Vendor A/P Contact" . "\t" . "UCBZeroWaste Client Name" . "\t" . 
			"Service Month" . "\t" . "Invoice Number" . "\t" . "Scan of Invoice" . "\t" . "Invoice Date" . "\t" . "Invoice Due Date" . "\t" . "Invoice Age" . "\t" . 
			"Invoice Amount" . "\t" . "Vendor Preferred Payment" . "\t" . "Has UCBZW Paid the Invoice" . "\t" . "Log Notes Date " . "\t" . "Log Notes" . "\t";
	}		
	echo "\n";
	$strforexcel ="";
	 
    $srno =1; 

    while($row = array_shift($result)){ 
        
		$strforexcel =  $srno  . $sep;
		$strforexcel .= $row['vendor_name'] . $sep;
		$strforexcel .= $row['vendor_ap_contact'] . $sep;
		$strforexcel .= $row['nickname'] . $sep;
		$strforexcel .= date("M Y", strtotime($row['invoice_date'])) . $sep;
		$strforexcel .= $row['invoice_number'] . $sep;
		$strforexcel .= $row['scan_report'] . $sep;
		$strforexcel .= date("m/d/Y", strtotime($row['new_invoice_date'])) . $sep;
		$strforexcel .= $row['invoice_due_date'] . $sep;
		$strforexcel .= $row['invoice_age'] . $sep;
		$strforexcel .= $row['amt'] . $sep;
		$strforexcel .= $row['vendor_preferred_payment_by'] . $sep;
		$strforexcel .= (($row['made_payment'] == 1)? "Yes" : "No") . $sep;
		$strforexcel .= $row['water_transaction_log_notes_dt'] . $sep;
		$strforexcel .= $row['log_notes'] . $sep;
		$strforexcel = preg_replace("/\r\n|\n\r|\n|\r/", " ", $strforexcel);
		$strforexcel .= "\t";
		echo(trim($strforexcel));
		echo "\n";
		
        $srno++;
    } 
?>