<?php

include("../../securedata/config_prod.php");  

function string_to_date($a, $b)
	{
		$start = explode("/", $a);
		$start_date = "0000-00-00 00:00:01";
		if ($start[2] != "") {
		if ($start[2] == 11 || $start[2] == 10) $start[2] = "20" . $start["2"];
		$start_date = $start[2] . "-" . $start[0] . "-" . $start[1] . " " . $b;
		}
		return $start_date;
}
?>