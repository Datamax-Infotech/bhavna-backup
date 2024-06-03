<?php
function db(): mysqli{
	$dbuser		= "loopsusr_proddbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "5!M-1a@coIOR"; 
	$dbname		= "loopsusr_production";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	return $$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}
	
function db_b2b(): mysqli
{
	$dbuser		= "loopsusr_b2bdbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= '_z91dGa2#fNt'; 
	$dbname		= "loopsusr_b2b";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	return $$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_email(): mysqli
{
	$dbuser		= "usedcard_ucbmail"; 
	$dbserver	= "localhost"; 
	$dbpass		= "NewEmalPwd@141#Chk"; 
	$dbname		= "usedcard_ucbmail";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	return $$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_b2c_email_new(): mysqli
{
	$dbuser		= "loopsusr_b2cemluser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "AZvp_qhR-dkm"; 
	$dbname		= "loopsusr_b2c_email";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	return $$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

?>
