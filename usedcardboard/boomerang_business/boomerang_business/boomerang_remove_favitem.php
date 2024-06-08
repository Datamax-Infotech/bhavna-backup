<? 
if (isset($_COOKIE['loginin'])) {
}else {
	require ("inc/header_session_client.php");
}

require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";

$res = db_query("UPDATE boomerange_inventory_gaylords_favorite SET fav_status = 0 WHERE id =".$_REQUEST['favItemId'], db());
echo "true";
?>