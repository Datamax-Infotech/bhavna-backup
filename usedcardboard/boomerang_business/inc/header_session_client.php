<? 
if(!$_COOKIE['loginid']){
	//header("location:index.php");
	
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"index.php\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\" />";
	echo "</noscript>"; exit;
}
?>
