<?php
function db(): void{
	$dbuser		= "usedcard_prod"; 
	$dbserver	= "92.204.132.4"; 
	$dbpass		= "WowNoIts@Attac#45421"; 
	$dbname		= "usedcard_production";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}
	
function db_b2b(): void
{
	$dbuser		= "usedcard_b2buser"; 
	$dbserver	= "92.204.132.4"; 
	$dbpass		= "GodSave@isDo%1414"; 
	$dbname		= "usedcard_b2b";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_email(): void
{
	$dbuser		= "usedcard_ucbmail"; 
	$dbserver	= "92.204.132.4"; 
	$dbpass		= "NewEmalPwd@141#Chk"; 
	$dbname		= "usedcard_ucbmail";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_b2c_email_new(): void
{
	$dbuser		= "usedcard_b2c_mail"; 
	$dbserver	= "92.204.132.4"; 
	$dbpass		= "~ZMIW.Z%y)#]"; 
	$dbname		= "usedcard_b2c_email";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

?>
