<?

function db()
{
	$dbuser		= "usedcard_prod"; 
	$dbserver	= "localhost"; 
	$dbpass		= "WowNoIts@Attac#45421"; 
	$dbname		= "usedcard_production";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}
	
function db_b2b()
{
	$dbuser		= "usedcard_b2buser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "GodSave@isDo%1414"; 
	$dbname		= "usedcard_b2b";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_email()
{
	$dbuser		= "usedcard_ucbmail"; 
	$dbserver	= "localhost"; 
	$dbpass		= "NewEmalPwd@141#Chk"; 
	$dbname		= "usedcard_ucbmail";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}
?>
