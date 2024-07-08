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

function db_email_com()
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

function db_email()
{
	$dbuser		= "usedcard_ucbmail"; 
	$dbserver	= "107.180.114.22"; 
	$dbpass		= "Yell@owgapto655"; 
	$dbname		= "usedcard_ucbmail";
	
	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_water_inbox_email()
{
	$dbuser		= "usedcardboardbox_water_inbox_usr"; 
	$dbserver	= "localhost"; 
	$dbpass		= "NewParty@TO65Hk"; 
	$dbname		= "usedcardboardbox_water_inbox_email";
	
	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_b2c_email_new()
{
	$dbuser		= "usedcard_b2c_mail"; 
	$dbserver	= "localhost"; 
	$dbpass		= "~ZMIW.Z%y)#]"; 
	$dbname		= "usedcard_b2c_email";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_project_mgmt()
{
	$dbuser		= "usedcardboardbox_prj_mgmt_user"; 
	$dbserver	= "localhost"; 
	$dbpass		= "pLmV7@Omx)fm"; 
	$dbname		= "usedcardboardbox_project_mgmt";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	/*if ($$con_db ->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}else{
	echo "Connected successfully";
	}
	*/
}
?>