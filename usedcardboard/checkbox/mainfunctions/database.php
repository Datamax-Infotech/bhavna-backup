<?

function db()
{
	$dbuser		= "ucbdata_ucbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "g#WTUeu-B8Pt"; 
	$dbname		= "ucbdata_usedcard_production";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	/*if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}
	echo "Connected successfully";
	*/
}
	
function db_b2b()
{
	$dbuser		= "ucbdata_ucbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "g#WTUeu-B8Pt"; 
	$dbname		= "ucbdata_usedcard_b2b";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_email_com()
{
	$dbuser		= "ucbdata_ucbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "g#WTUeu-B8Pt"; 
	$dbname		= "ucbdata_ucbmail";
	
	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_email()
{
	$dbuser		= "ucbdata_ucbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "g#WTUeu-B8Pt"; 
	$dbname		= "ucbdata_ucbmail";
	
	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_water_inbox_email()
{
	$dbuser		= "ucbdata_ucbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "g#WTUeu-B8Pt"; 
	$dbname		= "ucbdata_water_inbox_email";
	
	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_b2c_email_new()
{
	$dbuser		= "ucbdata_ucbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "g#WTUeu-B8Pt"; 
	$dbname		= "usedcard_b2c_email";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
}

function db_project_mgmt()
{
	$dbuser		= "ucbdata_ucbuser"; 
	$dbserver	= "localhost"; 
	$dbpass		= "g#WTUeu-B8Pt"; 
	$dbname		= "ucbdata_project_mgmt";

	//CONNECTION STRING
	$con_db = 'db_link';
	global $$con_db;
	$$con_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if ($$con_db ->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}
	//echo "Connected successfully";

}
?>