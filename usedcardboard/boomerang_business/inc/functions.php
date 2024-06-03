<?php


function redirect($a)
	{
	if (!headers_sent()){   
        header('Location: ' . $a); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"". $a. "\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $a . "\" />";
        echo "</noscript>"; exit;
}
}


function feed($a,$b,$c)
{

$qry = "INSERT INTO loop_feed SET message = '" . mysql_real_escape_string($a) . "', created_by = " . $b . ", created_for = " . $c;
$res_newtrans = mysql_query($qry,db() );
return $res_newtrans;
}

function comment($ac,$b,$c)
{

$qry = "INSERT INTO loop_feed_comments SET cmessage = '" . $ac . "', messageid = " . $b . ", employeeid = " . $c;
$res_newtrans = mysql_query($qry,db() );
}


function showfeed($limit)
{

?>

<script type="text/javascript" src="wz_tooltip.js"></script>
<table>
	<tr><td>
	<?
	$sql_pic = "SELECT * FROM loop_employees WHERE id = " . $_COOKIE["employeeid"];
$result_pic = mysql_query($sql_pic,db() );
$pic = mysql_fetch_array($result_pic);
?>
 <img src='images/employees/<?=$pic["employeepic"]; ?>'></td><td> </td><td>
	<form method=post action="feedadd.php">
	<input type="text" name="message" value="What's on your mind?" size=50></form></td></tr>
	<tr><td><br></td></tr>
<?
$sql = "SELECT *, loop_feed.id AS LFID FROM loop_feed LEFT JOIN loop_employees ON loop_feed.created_by = loop_employees.id ORDER BY dt DESC LIMIT " . $limit;
if ($limit==-1) {
$sql = "SELECT *, loop_feed.id AS LFID FROM loop_feed LEFT JOIN loop_employees ON loop_feed.created_by = loop_employees.id WHERE dt >= '" . date("Y-m-d") . " 00:00:00' ORDER BY dt DESC ";
}
$result = mysql_query($sql,db() );
while ($dresult = mysql_fetch_array($result)) {
if ($dresult["employeepic"] != "") {
echo "<tr><td><font size=1><img src='images/employees/".$dresult["employeepic"]."'></td>"; } else {
echo "<tr><td><font size=1><img src='images/employees/loops.jpg'></td>"; }
?>
<td width =60><font size=1><?=timestamp_to_datetime($dresult["dt"]);?></td>
<td width =600><font size=2><?=$dresult["message"];?><br>
<font size=1>
<?
$sql_like_all = "SELECT * FROM loop_feed_like LEFT JOIN loop_employees ON loop_feed_like.employeeid = loop_employees.id WHERE messageid = " . $dresult["LFID"];
$result_like_all = mysql_query($sql_like_all,db() );
$likecount = mysql_num_rows($result_like_all);

$sql_like = "SELECT * FROM loop_feed_like WHERE messageid = " . $dresult["LFID"] . " AND employeeid = " . $_COOKIE["employeeid"];
$result_like = mysql_query($sql_like,db() );
$ilikecount = mysql_num_rows($result_like);
$ilikeid = mysql_fetch_array($result_like);
if ($ilikecount > 0) { 
echo "<b>".$likecount."</b> ";?>
<img onmouseover="Tip('<?
while ($like_names = mysql_fetch_array($result_like_all)) {
echo $like_names["name"]."<br>";
}
?>like this.')" onmouseout="UnTip()" src="images/thumbsup.jpg">
<?
}
if ($ilikecount == 0 ) {
?>
<a href="like.php?status=like&messageid=<?=$dresult["LFID"];?>">Like</a>
<? } else { ?>
<a href="like.php?status=unlike&likeid=<?=$ilikeid["id"];?>">Unlike</a>
<? } ?>
 <a href="?commentid=<?=$dresult["LFID"];?>">Comment</a></font>
</td></font></tr>
<?
$sqlc = "SELECT *, loop_feed_comments.id AS LFID FROM loop_feed_comments LEFT JOIN loop_employees ON loop_feed_comments.employeeid = loop_employees.id WHERE loop_feed_comments.messageid = " . $dresult["LFID"] . " ORDER BY cdt ASC";
$resultc = mysql_query($sqlc,db() );
while ($cresult = mysql_fetch_array($resultc)) {
if ($cresult["employeepic"] != "") {
echo "<tr><td><font size=1><img src='images/employees/".$cresult["employeepic"]."'></td>"; } else {
echo "<tr><td><font size=1><img src='images/employees/loops.jpg'></td>"; }
?>
<td width =60><font size=1><?=timestamp_to_datetime($cresult["cdt"]);?></td>
<td width =600><font size=2><?=$cresult["cmessage"];?></td></tr>
<?
}
if ($_REQUEST["commentid"] == $dresult["LFID"]) {
 echo "<tr><td>";

$sql_pic = "SELECT * FROM loop_employees WHERE id = " . $_COOKIE["employeeid"];
$result_pic = mysql_query($sql_pic,db() );
$pic = mysql_fetch_array($result_pic);
?>
 <img src='images/employees/<?=$pic["employeepic"]; ?>'>
 <? 
 echo "</td><td> </td><td><form method=post action='feedaddcomment.php'><input type='hidden' value='" . $dresult["LFID"]  . "' name='messageid'><input type=text name='cmessage' size=70 maxlength=300><input type=submit value='comment'></td></tr></form>";
}
 ?>
<tr><td><br></td></tr>
<? } ?>
</table>

<?
}

function huntvalleywarehouse()
	{
	if (!headers_sent()){    
        header('Location: http://www.usedcardboardboxes.com/ucbloop/huntvalleywarehouse_14159265358979.php'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"http://www.usedcardboardboxes.com/ucbloop/huntvalleywarehouse_14159265358979.php\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=http://www.usedcardboardboxes.com/ucbloop/huntvalleywarehouse_14159265358979.php\" />";
        echo "</noscript>"; exit;
}
}

function huntvalleywarehousepage()
	{
		return "http://www.usedcardboardboxes.com/ucbloop/huntvalleywarehouse_14159265358979.php";
	}


function hannibalwarehouse()
	{
	if (!headers_sent()){    
        header('Location: http://www.usedcardboardboxes.com/ucbloop/hannibalwarehouse_141592653.php'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"http://www.usedcardboardboxes.com/ucbloop/hannibalwarehouse_141592653.php\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=http://www.usedcardboardboxes.com/ucbloop/hannibalwarehouse_141592653.php\" />";
        echo "</noscript>"; exit;
}
}

function hannibalwarehousepage()
	{
	return "http://www.usedcardboardboxes.com/ucbloop/hannibalwarehouse_141592653.php";
		
	}

function addtreecounter($pounds)
	{
	$tree_query = mysql_query("SELECT * FROM tree_counter_b2b", db());
	$tree = mysql_fetch_array($tree_query);
	
	$count = $tree["trees_saved"];

	$new_total = $count + $pounds * 0.0085;
	exit;
	}


function update_box_values($box_id)
{
	$bv_query = mysql_query("SELECT * FROM loop_boxes_values WHERE id = " . $box_id, db());
	$cv = mysql_fetch_array($bv_query);
	
	$value = $cv["value"];

	$bv_view_qry = "SELECT id, box_value, operator, factor, bweight, secformula_operator, secformula_factor FROM loop_boxes WHERE box_value = " . $box_id;
	$bv_view_res = mysql_query($bv_view_qry,db() );
	while ($bv = mysql_fetch_array($bv_view_res)) {
	
		if ($bv["operator"] == "+") { 
			if ($bv["secformula_operator"] != "" and $bv["secformula_factor"] != "") {
				$bq = "UPDATE loop_boxes SET boxgoodvalue = " . ((($value + $bv["factor"])/2000) * $bv["bweight"]). $bv["secformula_operator"] . $bv["secformula_factor"] . " WHERE id = " . $bv["id"]; 
			}else {
				$bq = "UPDATE loop_boxes SET boxgoodvalue = " . (($value + $bv["factor"])/2000) * $bv["bweight"] . " WHERE id = " . $bv["id"]; 
			}
		}
		if ($bv["operator"] == "-") { 
			if ($bv["secformula_operator"] != "" and $bv["secformula_factor"] != "") {
				$bq = "UPDATE loop_boxes SET boxgoodvalue = " . ((($value - $bv["factor"])/2000) * $bv["bweight"]). $bv["secformula_operator"] . $bv["secformula_factor"] . " WHERE id = " . $bv["id"]; 
			}else {
				$bq = "UPDATE loop_boxes SET boxgoodvalue = " . (($value - $bv["factor"])/2000) * $bv["bweight"] . " WHERE id = " . $bv["id"]; 
			}
		}
		if ($bv["operator"] == "*") { 
			if ($bv["secformula_operator"] != "" and $bv["secformula_factor"] != "") {
				$bq = "UPDATE loop_boxes SET boxgoodvalue = " . ((($value/2000) * $bv["bweight"] * $bv["factor"])). $bv["secformula_operator"] . $bv["secformula_factor"] . " WHERE id = " . $bv["id"]; 
			}else {
				$bq = "UPDATE loop_boxes SET boxgoodvalue = " . (($value/2000) * $bv["bweight"] * $bv["factor"]) . " WHERE id = " . $bv["id"]; 
			}
		}
		
		if ($bv["operator"] == "/") { 
			if ($bv["secformula_operator"] != "" and $bv["secformula_factor"] != "") {
				$bq = "UPDATE loop_boxes SET boxgoodvalue = " . (($value/2000) * $bv["bweight"] / $bv["factor"]). $bv["secformula_operator"] . $bv["secformula_factor"] . " WHERE id = " . $bv["id"]; 
			}else {
				$bq = "UPDATE loop_boxes SET boxgoodvalue = " . (($value/2000) * $bv["bweight"] / $bv["factor"]) . " WHERE id = " . $bv["id"]; 
			}
		}

		mysql_query($bq,db() );

	}

}

function getVendorName($id)
	{

	if ($id > 0) {
	$bv_query = mysql_query("SELECT * FROM vendors WHERE id = " . $id, db_b2b());
	$cv = mysql_fetch_array($bv_query);
	
	return $cv["Name"];
	} else {
	return "";
	}

	}


function timestamp_to_date($d)
	{

	$da = explode(" ",$d);
	$dp = explode("-", $da[0]);
	return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
	}

function timestamp_to_datetime($d)
	{

	$da = explode(" ",$d);
	$dp = explode("-", $da[0]);
	$dh = explode(":", $da[1]);
	
	$x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];

	if ($dh[0] == 12) {
		$x = $x . " " . ($dh[0] - 0) . ":" . $dh[1] . "PM CT";
	} elseif ($dh[0] == 0) {
		$x = $x . " 12:" . $dh[1] . "AM CT";
	}
	elseif ($dh[0] > 12) {
	$x = $x . " " . ($dh[0] - 12) . ":" . $dh[1] . "PM CT";
	} else {
	$x = $x . " " . ($dh[0] ) . ":" . $dh[1] . "AM CT";
	}
	
	return $x;
	}


	
	function sendemail_attachment($files, $path, $mailto, $scc, $sbcc, $from_mail, $from_name, $replyto, $subject, $message) {    $uid = md5(uniqid(time()));    $header = "From: ".$from_name." <".$from_mail.">\r\n";	$header.= "Cc: " . $scc . "\r\n";	$header.= "Bcc: " . $sbcc. "\r\n";    	if (count($files) > 0)	{		$header .= "MIME-Version: 1.0\r\n";		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";		$header .= "This is a multi-part message in MIME format.\r\n";		$header .= "--".$uid."\r\n";	}		$header .= "Content-type:text/html; charset=iso-8859-1\r\n";	$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";	$header .= $message."\r\n\r\n";		for($x=0;$x<count($files);$x++)	{		$file_size = filesize($path.$files[$x]);		$handle = fopen($path.$files[$x], "rb");		$content = fread($handle, $file_size);		fclose($handle);		$content = chunk_split(base64_encode($content));		$header .= "--".$uid."\r\n";		$header .= "Content-Type: application/octet-stream; name=\"". $files[$x] ."\"\r\n"; 		$header .= "Content-Transfer-Encoding: base64\r\n";		$header .= "Content-Disposition: attachment; filename=\"". $files[$x] ."\"\r\n\r\n";		$header .= $content."\r\n\r\n";		$header .= "\n\n";	}		if (count($files) > 0)	{		$header .= "--".$uid."--";	}    if (mail($mailto, $subject, "", $header)) {       return "emailsend";    } else {       return "emailerror";    }	}	
	
function showarrays($p)
	{
	$z = "";
	$count = count($p);
for ($i = 0; $i < $count - 1; $i++) {
    $z .= $p[$i] . ", ";
}
	$z .=  $p[$i];
	return $z;
	}


function showStatuses($arrVal, $eid, $limit)
{

$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  " ) ORDER BY sort_order";
$dt_view_res = mysql_query($dt_view_qry,db_b2b() );
while ($row = mysql_fetch_array($dt_view_res)) {

 
$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND from companyInfo Where companyInfo.status =" . $row["id"] . " AND ( companyInfo.assignedto = " . $eid . " OR companyInfo.viewable1=" . $eid . " OR companyInfo.viewable2=" . $eid . " OR companyInfo.viewable3=" . $eid . " OR companyInfo.viewable4=" . $eid . ")";

 

if ($_REQUEST["gc"] == 1) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Need Boxes'";
}
if ($_REQUEST["gc"] == 2) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Have Boxes'";
}

if ($_REQUEST["so"] != "") {
	if ($_REQUEST["so"] == "A") {
		$sord = " ASC";
	} Else {
		$sord = " DESC";
	}
} ELSE {
	$sord = " DESC";
}

if ($_REQUEST["sk"] != "" )
{
	if ($_REQUEST["sk"] == "dt") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_REQUEST["sk"] == "age") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_REQUEST["sk"] == "contact") {
		$skey = " ORDER BY companyInfo.contact";
	} elseif ($_REQUEST["sk"] == "cname") {
		$skey = " ORDER BY companyInfo.company";
	} elseif ($_REQUEST["sk"] == "city") {
		$skey = " ORDER BY companyInfo.city";
	} elseif ($_REQUEST["sk"] == "state") {
		$skey = " ORDER BY companyInfo.state";
	} elseif ($_REQUEST["sk"] == "zip") {
		$skey = " ORDER BY companyInfo.zip";
	} elseif ($_REQUEST["sk"] == "qty") {
		$skey = " ORDER BY companyInfo.company";
	} elseif ($_REQUEST["sk"] == "lc") {
		$skey = " ORDER BY companyInfo.company";
	}
}
Else
{
	$skey = " ORDER BY dateCreated";
}
$x = $x . " GROUP BY companyInfo.id " . $skey . $sord . " ";
if ($limit > 0 )
{
	$xL = $x . " LIMIT 0, " . $limit;
	$data_res = mysql_query($xL,db_b2b() );
	$data_res_No_Limit = mysql_query($x,db_b2b() );
	$show = "" . $limit;
} else
{

	$data_res = mysql_query($x,db_b2b() );
	$data_res_No_Limit = mysql_query($x,db_b2b() );
	$show = "All";
}
//echo $x;
?>
<table width="1300" border="0" cellspacing="1" cellpadding="1">
<tr align="center">
<td colspan="13" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><?=$row["name"] . " - Total Records: " . mysql_num_rows($data_res_No_Limit) . " - Showing: "; ?>
 <? if ($show < 100000) {
 echo $show;?> <a href="showall.php?status=<?=$row["id"];?>" target=_blank>Show All</a>
 <? } else { ?>All<? } ?>
 </b></font></td>
</tr>
<? if (($row["id"] != 24 AND $row["id"] != 46 AND $row["id"] != 50 AND $row["id"] != 49 AND $row["id"] != 43 AND $row["id"] != 44) OR $limit == 100000 ) { ?>
<tr>
<td width="5%" valign="middle" align="center" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"></font></td>
<td width="8%" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="manageCompanies.php?sk=dt&so=<?=$so?>">DATE</a><img src="images/arrowdown.jpg" width="9" height="8"> </font></td>
<td width="8%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="manageCompanies.php?sk=age&so=<?=$so?>">AGE</a></font></td>
<td width="10%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="manageCompanies.php?sk=contact&so=<?=$so?>">CONTACT</a></font></td>
<td width="21%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="manageCompanies.php?sk=cname&so=<?=$so?>">COMPANY NAME</a></font></td>
<td width="3%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="manageCompanies.php?sk=city&so=<?=$so?>">CITY</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="manageCompanies.php?sk=state&so=<?=$so?>">STATE</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="manageCompanies.php?sk=zip&so=<?=$so?>">ZIP</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="manageCompanies.php?sk=lc&so=<?=$so?>">Next Step</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="manageCompanies.php?sk=lc&so=<?=$so?>">Last Communication</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Next Communication</font></td>
</tr>
<?
 while ($data = mysql_fetch_array($data_res)) {

?>
<tr valign="middle">
<td width="3%" valign="middle" align="center" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
<a href="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$data["I"]?>"><img src="images/magglass.jpg" align="absmiddle" border="0"><?$x;?></a></font></td>

<td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?= $data["D"]; 
?></font></td>
<td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=date_diff_new($data["D"], "NOW");
?> Days</font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["C"]?></font></td>
<td width="21%" bgcolor="#E4E4E4"><a href="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$data["I"]?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["CO"]?></font></a></td>
<td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["PH"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["CI"]?></font></td>
<td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["ST"]?></font></td>
<td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["ZI"]?></font></td>
<td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["NS"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?if ($data["LD"]!="") echo date('m/d/Y',strtotime($data["LD"]));?>
<td width="10%" <? if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <? } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000" <? } else { ?> bgcolor="#E4E4E4"  <? } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?>
</font></td>


</tr>

<?
} // of the inactive or reactive if
ob_flush();
}
echo "</table><p></p>";
}

}


function showStatuses_withpara($arrVal, $eid, $limit, $callingpage)
{

if ($_REQUEST["so"] == "A") {
	$so = "D"; 
} 	
else {	
	$so = "A";
}

if ($_REQUEST["sk"] != "" )
{
	if ($eid > 0) {
		$tmp_sortorder = "";
		if ($_REQUEST["sk"] == "dt") {
			$tmp_sortorder = "dateCreated";
		} elseif ($_REQUEST["sk"] == "age") {
			$tmp_sortorder = "dateCreated";
		} elseif ($_REQUEST["sk"] == "cname") {
			$tmp_sortorder = "company";
		} elseif ($_REQUEST["sk"] == "qty") {
			$tmp_sortorder = "company";
		} elseif ($_REQUEST["sk"] == "nname") {
			$tmp_sortorder = "nickname";
		} elseif ($_REQUEST["sk"] == "nd") {
			$tmp_sortorder = "next_date";
		} elseif ($_REQUEST["sk"] == "ns") {
			$tmp_sortorder = "next_step";
		} elseif ($_REQUEST["sk"] == "lc") {
			$tmp_sortorder = "company";
		}else{ 
			$tmp_sortorder = $_REQUEST["sk"]; 
		}
		
		if ($so == "A") {
			$tmp_sort = "D"; 
		} 	
		else {	
			$tmp_sort = "A";
		}
		$sql_qry = "update employees set sort_fieldname = '". $tmp_sortorder."', sort_order='".$tmp_sort."' where employeeID = " . $eid ;
		mysql_query($sql_qry,db_b2b() );
	}
	
	if ($_REQUEST["sk"] == "dt") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_REQUEST["sk"] == "age") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_REQUEST["sk"] == "contact") {
		$skey = " ORDER BY companyInfo.contact";
	} elseif ($_REQUEST["sk"] == "cname") {
		$skey = " ORDER BY companyInfo.company";
	} elseif ($_REQUEST["sk"] == "nname") {
		$skey = " ORDER BY companyInfo.nickname";
	} elseif ($_REQUEST["sk"] == "city") {
		$skey = " ORDER BY companyInfo.city";
	} elseif ($_REQUEST["sk"] == "state") {
		$skey = " ORDER BY companyInfo.state";
	} elseif ($_REQUEST["sk"] == "zip") {
		$skey = " ORDER BY companyInfo.zip";
	} elseif ($_REQUEST["sk"] == "nd") {
		$skey = " ORDER BY companyInfo.next_date";
	} elseif ($_REQUEST["sk"] == "ns") {
		$skey = " ORDER BY companyInfo.next_step";
	} elseif ($_REQUEST["sk"] == "lc") {
		$skey = " ORDER BY companyInfo.last_date";
	}
}
else
{
	if ($eid > 0) {
		$sql_qry = "Select sort_fieldname from employees where employeeID = " . $eid .  "";
		$dt_view_res = mysql_query($sql_qry,db_b2b() );
		while ($row = mysql_fetch_array($dt_view_res)) {
			if ($row["sort_fieldname"] != "") {
				$skey = " ORDER BY companyInfo.". $row["sort_fieldname"] ;
			} else {
				$skey = " ORDER BY companyInfo.dateCreated " ;
			}
		}
	}else {
		$skey = " ORDER BY companyInfo.dateCreated " ;
	}
}

$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  " ) ORDER BY sort_order";
$dt_view_res = mysql_query($dt_view_qry,db_b2b() );
while ($row = mysql_fetch_array($dt_view_res)) {
 
$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND from companyInfo Where companyInfo.status =" . $row["id"] . " AND ( companyInfo.assignedto = " . $eid . " OR companyInfo.viewable1=" . $eid . " OR companyInfo.viewable2=" . $eid . " OR companyInfo.viewable3=" . $eid . " OR companyInfo.viewable4=" . $eid . ")";

if ($_REQUEST["gc"] == 1) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Need Boxes'";
}
if ($_REQUEST["gc"] == 2) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Have Boxes'";
}

if ($_REQUEST["so"] != "") {
	if ($_REQUEST["so"] == "A") {
		$sord = " ASC";
	} Else {
		$sord = " DESC";
	}
} ELSE {
	$sord = " DESC";
}


$x = $x . " GROUP BY companyInfo.id " . $skey . $sord . " ";
if ($limit > 0 )
{
	$xL = $x . " LIMIT 0, " . $limit;
	$data_res = mysql_query($xL,db_b2b() );
	$data_res_No_Limit = mysql_query($x,db_b2b() );
	$show = "" . $limit;
} else
{

	$data_res = mysql_query($x,db_b2b() );
	$data_res_No_Limit = mysql_query($x,db_b2b() );
	$show = "All";
}
echo $x;
?>
<table width="1300" border="0" cellspacing="1" cellpadding="1">
<tr align="center">
<td colspan="13" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><?=$row["name"] . " - Total Records: " . mysql_num_rows($data_res_No_Limit) . " - Showing: "; ?>
 <? if ($show < 100000) {
 echo $show;?> <a href="showall.php?status=<?=$row["id"];?>" target=_blank>Show All</a>
 <? } else { ?>All<? } ?>
 </b></font></td>
</tr>
<? if (($row["id"] != 24 AND $row["id"] != 46 AND $row["id"] != 50 AND $row["id"] != 49 AND $row["id"] != 43 AND $row["id"] != 44) OR $limit == 100000 ) { ?>
<tr>
<td width="5%" valign="middle" align="center" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"></font></td>
<td width="8%" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?=$callingpage?>?sk=dt&so=<?=$so?>">DATE</a><img src="images/arrowdown.jpg" width="9" height="8"> </font></td>
<td width="8%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?=$callingpage?>?sk=age&so=<?=$so?>">AGE</a></font></td>
<td width="10%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?=$callingpage?>?sk=contact&so=<?=$so?>">CONTACT</a></font></td>
<td width="21%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?=$callingpage?>?sk=cname&so=<?=$so?>">COMPANY NAME</a></font></td>
<td width="3%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?=$callingpage?>?sk=city&so=<?=$so?>">CITY</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?=$callingpage?>?sk=state&so=<?=$so?>">STATE</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?=$callingpage?>?sk=zip&so=<?=$so?>">ZIP</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?=$callingpage?>?sk=lc&so=<?=$so?>">Next Step</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?=$callingpage?>?sk=lc&so=<?=$so?>">Last Communication</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Next Communication</font></td>
</tr>
<?
 while ($data = mysql_fetch_array($data_res)) {

?>
<tr valign="middle">
<td width="3%" valign="middle" align="center" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
<a href="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$data["I"]?>"><img src="images/magglass.jpg" align="absmiddle" border="0"><?$x;?></a></font></td>

<td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?= $data["D"]; 
?></font></td>
<td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=date_diff_new($data["D"], "NOW");
?> Days</font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["C"]?></font></td>
<td width="21%" bgcolor="#E4E4E4"><a href="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$data["I"]?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["CO"]?></font></a></td>
<td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["PH"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["CI"]?></font></td>
<td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["ST"]?></font></td>
<td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["ZI"]?></font></td>
<td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["NS"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?if ($data["LD"]!="") echo date('m/d/Y',strtotime($data["LD"]));?>
<td width="10%" <? if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <? } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000" <? } else { ?> bgcolor="#E4E4E4"  <? } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?>
</font></td>


</tr>

<?
} // of the inactive or reactive if
ob_flush();
}
echo "</table><p></p>";
}

}


function showStatusesDashboard($arrVal, $eid, $limit, $period)
{

if ($_REQUEST["so"] == "A") {
	$so = "D"; 
} 	
else {	
	$so = "A";
}

if ($_REQUEST["sk"] != "" )
{
	if ($eid > 0) {
		$tmp_sortorder = "";
		if ($_REQUEST["sk"] == "dt") {
			$tmp_sortorder = "companyInfo.dateCreated";
		} elseif ($_REQUEST["sk"] == "age") {
			$tmp_sortorder = "companyInfo.dateCreated";
		} elseif ($_REQUEST["sk"] == "cname") {
			$tmp_sortorder = "companyInfo.company";
		} elseif ($_REQUEST["sk"] == "qty") {
			$tmp_sortorder = "companyInfo.company";
		} elseif ($_REQUEST["sk"] == "nname") {
			$tmp_sortorder = "companyInfo.nickname";
		} elseif ($_REQUEST["sk"] == "nd") {
			$tmp_sortorder = "companyInfo.next_date";
		} elseif ($_REQUEST["sk"] == "ns") {
			$tmp_sortorder = "companyInfo.next_step";
		} elseif ($_REQUEST["sk"] == "ei") {
			$tmp_sortorder = "employees.initials";
		} elseif ($_REQUEST["sk"] == "lc") {
			$tmp_sortorder = "companyInfo.company";
		}else{ 
			$tmp_sortorder = "companyInfo." . $_REQUEST["sk"]; 
		}
		
		if ($so == "A") {
			$tmp_sort = "D"; 
		} 	
		else {	
			$tmp_sort = "A";
		}
		$sql_qry = "update employees set sort_fieldname = '". $tmp_sortorder."', sort_order='".$tmp_sort."' where employeeID = " . $eid ;
		mysql_query($sql_qry,db_b2b() );
	}
	
	if ($_REQUEST["sk"] == "dt") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_REQUEST["sk"] == "age") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_REQUEST["sk"] == "contact") {
		$skey = " ORDER BY companyInfo.contact";
	} elseif ($_REQUEST["sk"] == "cname") {
		$skey = " ORDER BY companyInfo.company";
	} elseif ($_REQUEST["sk"] == "nname") {
		$skey = " ORDER BY companyInfo.nickname";
	} elseif ($_REQUEST["sk"] == "city") {
		$skey = " ORDER BY companyInfo.city";
	} elseif ($_REQUEST["sk"] == "state") {
		$skey = " ORDER BY companyInfo.state";
	} elseif ($_REQUEST["sk"] == "zip") {
		$skey = " ORDER BY companyInfo.zip";
	} elseif ($_REQUEST["sk"] == "nd") {
		$skey = " ORDER BY companyInfo.next_date";
	} elseif ($_REQUEST["sk"] == "ns") {
		$skey = " ORDER BY companyInfo.next_step";
	} elseif ($_REQUEST["sk"] == "ei") {
		$skey = " ORDER BY employees.initials";
	} elseif ($_REQUEST["sk"] == "lc") {
		$skey = " ORDER BY companyInfo.last_date";
	}

	if ($_REQUEST["so"] != "") {
		if ($_REQUEST["so"] == "A") {
			$sord = " ASC";
		} Else {
			$sord = " DESC";
		}
	} ELSE {
		$sord = " DESC";
	}
}
else
{
	if ($eid > 0) {
		$sql_qry = "Select sort_fieldname, sort_order from employees where employeeID = " . $eid .  "";
		$dt_view_res = mysql_query($sql_qry,db_b2b() );
		while ($row = mysql_fetch_array($dt_view_res)) {
			if ($row["sort_fieldname"] != "") {
				if ($row["sort_order"] == "A") {
					$sord = " ASC";
				} Else {
					$sord = " DESC";
				}
				$skey = " ORDER BY ". $row["sort_fieldname"];
			} else {
				$skey = " ORDER BY companyInfo.dateCreated " ;
				$sord = " DESC"; 
			}
		}
	}else {
		$skey = " ORDER BY companyInfo.dateCreated " ;
		$sord = " DESC"; 
	}
}

	$flag_assignto_viewby = 0; //= 1 means in assignto mode and = 0 means in assign to and viewable mode
	$sql = "SELECT flag_assignto_viewby FROM employees where employeeID = ". $eid;
	$result = mysql_query($sql,db_b2b() );
	while ($myrowsel = mysql_fetch_array($result)) {
		$flag_assignto_viewby = $myrowsel["flag_assignto_viewby"];
	}
	
	$flag_assignto_viewby_str = "";
	if ($flag_assignto_viewby == 0) {
		$flag_assignto_viewby_str = " OR companyInfo.viewable1=" . $eid . " OR companyInfo.viewable2=" . $eid . " OR companyInfo.viewable3=" . $eid . " OR companyInfo.viewable4=" . $eid . " ";
	}

$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  " ) ORDER BY sort_order";
$dt_view_res = mysql_query($dt_view_qry,db_b2b() );
while ($row = mysql_fetch_array($dt_view_res)) {
 
$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.status =" . $row["id"];


if ($_REQUEST["show"] != "search" AND $row["id"] != 58) {
$x = $x . " AND ( companyInfo.assignedto = " . $eid . $flag_assignto_viewby_str . ")";
 }

if ($_REQUEST["show"] == "unassigned") {
	$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID  Where companyInfo.status =" . $row["id"] . " AND  companyInfo.assignedto = 0 AND companyInfo.company != 'v' ";

}

if ($_REQUEST["gc"] == 1) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Need Boxes'";
}
if ($_REQUEST["gc"] == 2) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Have Boxes'";
}

if ($period != "all") {
	if ($period == "today") {
		$x = $x . " AND companyInfo.next_date = CURDATE() ";
	}
	if ($period == "upcoming") {
		$x = $x . " AND (companyInfo.next_date > '" . date('Y-m-d') . "' and companyInfo.next_date <= '" . date('Y-m-d', strtotime("+7 days")) . "')";
	}
	if ($period == "lastweek") {
		$x = $x . " AND (companyInfo.next_date <= '" . date('Y-m-d') . "' and companyInfo.next_date >= '" . date('Y-m-d', strtotime("-7 days")) . "')";
	}
	if ($period == "old") {
		$x = $x . " AND companyInfo.next_date < CURDATE() AND companyInfo.next_date > '1900-01-01'";
	}
	if ($period == "none") {
		$x = $x . " AND companyInfo.next_date IS NULL";
	}

}

if ($_REQUEST["show"] == "search") {
$arrFields = array("contact","contactTitle","contact2","contactTitle2","company","industry","address","address2","city","state","zip","country","phone","fax","email","website","order_no","choose","ccheck","billing_first_name","billing_last_name","billing_address1","billing_address2","billing_city","billing_state","billing_zip","billing_question","information","help","experience","mail_lists","card_owner","shipContact","shipTitle","shipAddress","shipAddress2","shipCity","shipState","shipZip","shipPhone","status","status2","haveNeed","notes","dateCreated","dateLastAccessed","poNumber","terms","rep","TBD","shipDate","via","quoteNote","howHear","pickupDay","pickupWeek","lastPickup","req_type","vendor","int_notes","green_initiative", "nickname", "next_step");

$st = explode(' ',$_REQUEST["searchterm"]); 


	$x = $x . " AND ( ";

	foreach ($st as $sti) {
	$i = 1;
	$x = $x . " ( ";
	foreach ($arrFields as $nm) {

		if ($i == 1 ) { ; }
		 ELSE {
			$x = $x . " OR ";
		}
		$x = $x . " companyInfo." . $nm . " LIKE '%" . $sti . "%'";
	$i++;
	}	
	$x = $x . " ) " . $_REQUEST["andor"] . " ";
	}

	if ($_REQUEST["andor"] == "AND") {
	$x = $x . " TRUE ) ";
	} else {
	$x = $x . " FALSE ) ";
	}

if ($_REQUEST["state"] != "ALL")
	$x = $x . " AND companyInfo.state LIKE '" . $_REQUEST["state"] . "' ";

}

$x = $x . " GROUP BY companyInfo.id " . $skey . $sord . " ";
//echo "<br/>" . $x . "<br/><br/>";
if ($limit > 0 )
{
	$xL = $x . " LIMIT 0, " . $limit;
	$data_res = mysql_query($xL,db_b2b() );
	$data_res_No_Limit = mysql_query($x,db_b2b() );
	$show = "" . $limit;
} else
{

	$data_res = mysql_query($x,db_b2b() );
	$data_res_No_Limit = mysql_query($x,db_b2b() );
	$show = "All";
}

if (mysql_num_rows($data_res_No_Limit) > 0) {
?>
<table width="1300" border="0" cellspacing="1" cellpadding="1">
<tr align="center">
<td colspan="14" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><?=$row["name"] . " - Total Records: " . mysql_num_rows($data_res_No_Limit) . " - Showing: "; ?>
 <? if ($limit > 0) {
 if ($limit > mysql_num_rows($data_res_No_Limit)) { echo mysql_num_rows($data_res_No_Limit); } else { echo $limit; }?> <a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=".$_REQUEST["sk"] . "&limit=all&so=" . $_REQUEST["so"] . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] ); ?>">Show All</a>
 <? } else { ?>All<? } ?>
 </b></font></td>
</tr>
<? //if (($row["id"] != 24 AND $row["id"] != 46 AND $row["id"] != 50 AND $row["id"] != 49 AND $row["id"] != 43 AND $row["id"] != 44) OR $limit == 100000 ) { ?>
<? if (1==1 OR $limit == 100000 ) { ?>
<tr>
<td width="5%" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=dt&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">DATE</a> </font></td>
<td width="5%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=age&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">AGE</a></font></td>
<td width="10%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=contact&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CONTACT</a></font></td>
<td width="21%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=cname&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">COMPANY NAME</a></font></td>
<!-- <td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nname&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">NICKNAME</a></font></td> -->
<td width="8%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=city&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CITY</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=state&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">STATE</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=zip&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">ZIP</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ns&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Next Step</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=lc&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Last<br>Communication</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nd&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Next Communication</font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ei&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Assigned To</font></td>
</tr>
<?
 while ($data = mysql_fetch_array($data_res)) {

?>
<tr valign="middle">
<td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?= timestamp_to_datetime($data["D"]); 
?></font></td>
<td width="5%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=date_diff_new($data["D"], "NOW");
?> Days</font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["C"]?></font></td>
<td width="21%" bgcolor="#E4E4E4"><a href="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$data["I"]?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><? if ($data["NN"] != "" ) echo $data["NN"]; else echo $data["CO"]?></font></a></td>
<!-- <td bgcolor="#E4E4E4"><a href="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$data["I"]?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["NN"]?></font></a></td> -->
<td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["PH"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["CI"]?></font></td>
<td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["ST"]?></font></td>
<td width="5%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["ZI"]?></font></td>
<td width="15%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["NS"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?if ($data["LD"]!="") echo date('m/d/Y',strtotime($data["LD"]));?>
<td width="10%" <? if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <? } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000" <? } else { ?> bgcolor="#E4E4E4"  <? } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?>
</font></td>

<td  bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["EI"]?></font></td>

</tr>

<?
} // of the inactive or reactive if
ob_flush();
}
echo "</table><p></p>";
}
}
}


function showSpecialOps($arrVal, $eid, $limit, $period)
{

if ($_REQUEST["so"] == "A") {
	$so = "D"; 
} 	
else {	
	$so = "A";
}

if ($_REQUEST["sk"] != "" )
{
	if ($eid > 0) {
		$tmp_sortorder = "";
		if ($_REQUEST["sk"] == "dt") {
			$tmp_sortorder = "companyInfo.dateCreated";
		} elseif ($_REQUEST["sk"] == "age") {
			$tmp_sortorder = "companyInfo.dateCreated";
		} elseif ($_REQUEST["sk"] == "cname") {
			$tmp_sortorder = "companyInfo.company";
		} elseif ($_REQUEST["sk"] == "qty") {
			$tmp_sortorder = "companyInfo.company";
		} elseif ($_REQUEST["sk"] == "nname") {
			$tmp_sortorder = "companyInfo.nickname";
		} elseif ($_REQUEST["sk"] == "nd") {
			$tmp_sortorder = "companyInfo.next_date";
		} elseif ($_REQUEST["sk"] == "ns") {
			$tmp_sortorder = "companyInfo.next_step";
		} elseif ($_REQUEST["sk"] == "ei") {
			$tmp_sortorder = "employees.initials";
		} elseif ($_REQUEST["sk"] == "lc") {
			$tmp_sortorder = "companyInfo.company";
		}else{ 
			$tmp_sortorder = "companyInfo." . $_REQUEST["sk"]; 
		}
		
		if ($so == "A") {
			$tmp_sort = "D"; 
		} 	
		else {	
			$tmp_sort = "A";
		}
		$sql_qry = "update employees set sort_fieldname = '". $tmp_sortorder."', sort_order='".$tmp_sort."' where employeeID = " . $eid ;
		mysql_query($sql_qry,db_b2b() );
	}
	
	if ($_REQUEST["sk"] == "dt") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_REQUEST["sk"] == "age") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_REQUEST["sk"] == "contact") {
		$skey = " ORDER BY companyInfo.contact";
	} elseif ($_REQUEST["sk"] == "cname") {
		$skey = " ORDER BY companyInfo.company";
	} elseif ($_REQUEST["sk"] == "nname") {
		$skey = " ORDER BY companyInfo.nickname";
	} elseif ($_REQUEST["sk"] == "city") {
		$skey = " ORDER BY companyInfo.city";
	} elseif ($_REQUEST["sk"] == "state") {
		$skey = " ORDER BY companyInfo.state";
	} elseif ($_REQUEST["sk"] == "zip") {
		$skey = " ORDER BY companyInfo.zip";
	} elseif ($_REQUEST["sk"] == "nd") {
		$skey = " ORDER BY companyInfo.next_date";
	} elseif ($_REQUEST["sk"] == "ns") {
		$skey = " ORDER BY companyInfo.next_step";
	} elseif ($_REQUEST["sk"] == "ei") {
		$skey = " ORDER BY employees.initials";
	} elseif ($_REQUEST["sk"] == "lc") {
		$skey = " ORDER BY companyInfo.last_date";
	}

	if ($_REQUEST["so"] != "") {
		if ($_REQUEST["so"] == "A") {
			$sord = " ASC";
		} Else {
			$sord = " DESC";
		}
	} ELSE {
		$sord = " DESC";
	}
}
else
{
	if ($eid > 0) {
		$sql_qry = "Select sort_fieldname, sort_order from employees where employeeID = " . $eid .  "";
		$dt_view_res = mysql_query($sql_qry,db_b2b() );
		while ($row = mysql_fetch_array($dt_view_res)) {
			if ($row["sort_fieldname"] != "") {
				if ($row["sort_order"] == "A") {
					$sord = " ASC";
				} Else {
					$sord = " DESC";
				}
				$skey = " ORDER BY ". $row["sort_fieldname"];
			} else {
				$skey = " ORDER BY companyInfo.dateCreated " ;
				$sord = " DESC"; 
			}
		}
	}else {
		$skey = " ORDER BY companyInfo.dateCreated " ;
		$sord = " DESC"; 
	}
}

$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  " ) ORDER BY sort_order";
$dt_view_res = mysql_query($dt_view_qry,db_b2b() );
while ($row = mysql_fetch_array($dt_view_res)) {
 
$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.status =" . $row["id"];


if ($_REQUEST["show"] != "search" AND $row["id"] != 58) {
$x = $x . " AND ( companyInfo.assignedto = " . $eid . " OR companyInfo.viewable1=" . $eid . " OR companyInfo.viewable2=" . $eid . " OR companyInfo.viewable3=" . $eid . " OR companyInfo.viewable4=" . $eid . ")";
 }

if ($_REQUEST["show"] == "unassigned") {
	$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID  Where companyInfo.status =" . $row["id"] . " AND  companyInfo.assignedto = 0 AND companyInfo.company != 'v' ";

}

if ($_REQUEST["gc"] == 1) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Need Boxes'";
}
if ($_REQUEST["gc"] == 2) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Have Boxes'";
}

if ($period != "all") {
	if ($period == "today") {
		$x = $x . " AND companyInfo.next_date = CURDATE() ";
	}
	if ($period == "upcoming") {
		$x = $x . " AND (companyInfo.next_date >= '" . date('Y-m-d') . "' and companyInfo.next_date <= '" . date('Y-m-d', strtotime("+7 days")) . "')";
	}
	if ($period == "lastweek") {
		$x = $x . " AND (companyInfo.next_date <= '" . date('Y-m-d') . "' and companyInfo.next_date >= '" . date('Y-m-d', strtotime("-7 days")) . "')";
	}
	if ($period == "old") {
		$x = $x . " AND companyInfo.next_date < CURDATE() AND companyInfo.next_date > '1900-01-01'";
	}
	if ($period == "none") {
		$x = $x . " AND companyInfo.next_date IS NULL";
	}

}

if ($_REQUEST["show"] == "search") {
$arrFields = array("contact","contactTitle","contact2","contactTitle2","company","industry","address","address2","city","state","zip","country","phone","fax","email","website","order_no","choose","ccheck","billing_first_name","billing_last_name","billing_address1","billing_address2","billing_city","billing_state","billing_zip","billing_question","information","help","experience","mail_lists","card_owner","shipContact","shipTitle","shipAddress","shipAddress2","shipCity","shipState","shipZip","shipPhone","status","status2","haveNeed","notes","dateCreated","dateLastAccessed","poNumber","terms","rep","TBD","shipDate","via","quoteNote","howHear","pickupDay","pickupWeek","lastPickup","req_type","vendor","int_notes","green_initiative", "nickname", "next_step");

$st = explode(' ',$_REQUEST["searchterm"]); 


	$x = $x . " AND ( ";

	foreach ($st as $sti) {
	$i = 1;
	$x = $x . " ( ";
	foreach ($arrFields as $nm) {

		if ($i == 1 ) { ; }
		 ELSE {
			$x = $x . " OR ";
		}
		$x = $x . " companyInfo." . $nm . " LIKE '%" . $sti . "%'";
	$i++;
	}	
	$x = $x . " ) " . $_REQUEST["andor"] . " ";
	}

	if ($_REQUEST["andor"] == "AND") {
	$x = $x . " TRUE ) ";
	} else {
	$x = $x . " FALSE ) ";
	}

if ($_REQUEST["state"] != "ALL")
	$x = $x . " AND companyInfo.state LIKE '" . $_REQUEST["state"] . "' ";

}

$x = $x . " GROUP BY companyInfo.id " . $skey . $sord . " ";
//echo "<br/>" . $x . "<br/><br/>";
if ($limit > 0 )
{
	$xL = $x . " LIMIT 0, " . $limit;
	$data_res = mysql_query($xL,db_b2b() );
	$data_res_No_Limit = mysql_query($x,db_b2b() );
	$show = "" . $limit;
} else
{

	$data_res = mysql_query($x,db_b2b() );
	$data_res_No_Limit = mysql_query($x,db_b2b() );
	$show = "All";
}

if (mysql_num_rows($data_res_No_Limit) > 0) {
?>
<table width="1100" border="0" cellspacing="1" cellpadding="1">
<tr align="center">
<td colspan="14" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><?=$row["name"] . " - HI Total Records: " . mysql_num_rows($data_res_No_Limit) . " - Showing: "; ?>
 <? if ($limit > 0) {
 if ($limit > mysql_num_rows($data_res_No_Limit)) { echo mysql_num_rows($data_res_No_Limit); } else { echo $limit; }?> <a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=".$_REQUEST["sk"] . "&limit=all&so=" . $_REQUEST["so"] . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] ); ?>">Show All</a>
 <? } else { ?>All<? } ?>
 </b></font></td>
</tr>
<? //if (($row["id"] != 24 AND $row["id"] != 46 AND $row["id"] != 50 AND $row["id"] != 49 AND $row["id"] != 43 AND $row["id"] != 44) OR $limit == 100000 ) { ?>
<? if (1==1 OR $limit == 100000 ) { ?>
<tr>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=contact&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">CONTACT</a></font></td>
<td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=cname&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"] . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"] ); ?>">COMPANY NAME</a></font></td>
<td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nname&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">NICKNAME</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ns&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Next Step</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=lc&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Last</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=nd&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Next</font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF']. "?sk=ei&so=" . $so . "&show=".$_REQUEST["show"]."&statusid=".$_REQUEST["statusid"]  . "&searchterm=".$_REQUEST["searchterm"]."&andor=".$_REQUEST["andor"]."&state=".$_REQUEST["state"]); ?>">Assigned To</font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Update</font></td>
</tr>
<?
 while ($data = mysql_fetch_array($data_res)) {

?>
<tr valign="middle">

<td bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["C"]?></font></td>
<td bgcolor="#E4E4E4"><a href="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$data["I"]?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["CO"]?></font></a></td>
<td bgcolor="#E4E4E4"><a href="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$data["I"]?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["NN"]?></font></a></td>
<td bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><textarea cols=60 rows=7 name=special><?=$data["NS"]?></textarea></font></td>
<td bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?if ($data["LD"]!="") echo date('m/d/Y',strtotime($data["LD"]));?>
<td <? if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <? } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000" <? } else { ?> bgcolor="#E4E4E4"  <? } ?> align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?if ($data["ND"]!="") echo date('m/d/Y',strtotime($data["ND"]));?>
</font></td>

<td  bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["EI"]?></font></td>
<td  bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><input type=submit value="update"></font></td>

</tr>

<?
} // of the inactive or reactive if
ob_flush();
}
echo "</table><p></p>";
}
}
}

function showLoops()
{
//////////////////////////////////////



$searchcrit = $_REQUEST["searchterm"];
$pagenorecords = 20;  // This is the page size.  I used same size as in DASH
	//IF NO PAGE
	if ($page == 0) {
	$myrecstart = 0;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}



if ($searchcrit == "") {
$flag = "all";
$sql = "SELECT * FROM loop_warehouse";
$sqlcount = "select count(*) as reccount from loop_warehouse";
} else {

$sqlcount = "select count(*) as reccount from loop_warehouse WHERE (";
$sql = "SELECT * FROM loop_warehouse WHERE (
 company_name like '%$searchcrit%' OR 
 warehouse_name like '%$searchcrit%' OR 
 warehouse_city like '%$searchcrit%' OR 
 warehouse_state like '%$searchcrit%' OR 
 warehouse_contact like '%$searchcrit%' )

		";


} 



	echo "<br><TABLE WIDTH='780'>";
	echo "	<tr align='middle'><td colspan='8' class='style24' style='height: 16px'><strong>Search Results</strong></td></tr>";
	echo "	<TR>";
echo "		<TD bgColor='#e4e4e4' class='style12' >Company Name</TD>"; echo "		<TD bgColor='#e4e4e4' class='style12' >Nickname</TD>"; echo "		<TD bgColor='#e4e4e4' class='style12' >Buyer / Seller</TD>"; echo "		<TD bgColor=#e4e4e4 class=style12 >Contact Name</TD>"; echo "		<TD bgColor=#e4e4e4 class=style12 >City</TD>"; echo "		<TD bgColor=#e4e4e4 class=style12 >State</TD>"; echo "		<TD bgColor=#e4e4e4 class=style12 >Last Activity Date</TD>"; echo "		<TD bgColor=#e4e4e4 class=style12 >Active Transactions</TD>"; 
echo "\n\n		</TR>";


//	echo $sql;
	$result = mysql_query($sql,db() );
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	if ($myrowsel = mysql_fetch_array($result)) {
	$id = $myrowsel["id"];
	




	
	
			do
  			{

  			$id = $myrowsel["id"];
  			

  			switch ($shade)
  			{
  				case "TBL_ROW_DATA_LIGHT":
					$shade = "TBL_ROW_DATA_DRK";
					break;
				case "TBL_ROW_DATA_DRK":
					$shade = "TBL_ROW_DATA_LIGHT";
					break;
				default:
					$shade = "TBL_ROW_DATA_DRK";
					break;
  			}

	echo "<TR>";
			?>
<!--
					<?php $rec_type = $myrowsel["rec_type"]; ?>
					<?php $id = $myrowsel["id"]; ?>
					
			<TD CLASS='<?php echo $shade; ?>'>
				<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&searchcrit=<? echo $searchcrit; ?>&rec_type=<? echo $rec_type; ?>&page=<? if ($page > 0) { $newpage = $page - 1; echo $newpage; } ?><?php echo $pagevars; ?>">View Record</a> 
			</TD>			
-->			
				<?php $warehouse_name = $myrowsel["warehouse_name"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
								<a href="<?php echo "search_results.php"; ?>?id=<?php echo $id; ?>&proc=View&searchcrit=<? echo $searchcrit; ?>&rec_type=<? echo $rec_type; ?>&page=<? if ($page > 0) { $newpage = $page - 1; echo $newpage; } ?><?php echo $pagevars; ?>"><?php echo $myrowsel["company_name"]; ?></a> 
			</TD>
				<?php $bs_status = $myrowsel["bs_status"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>				<?php echo $warehouse_name; ?> 			</TD>						<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $bs_status; ?> 
			</TD>			
				<?php $warehouse_contact = $myrowsel["warehouse_contact"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_contact; ?> 
			</TD>								
				<?php $warehouse_city = $myrowsel["warehouse_city"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_city; ?> 
			</TD>
				<?php $warehouse_state = $myrowsel["warehouse_state"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_state; ?> 
			</TD>
				<?php $last_activity = $myrowsel["last_activity"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $last_activity; ?> 
			</TD>

			<TD CLASS='<?php echo $shade; ?>'> </TD>

		</TR>
<?
			} while ($myrowsel = mysql_fetch_array($result));


	

}


	


$searchcrit = $_REQUEST["searchterm"];
$pagenorecords = 20;  // This is the page size.  I used same size as in DASH
	//IF NO PAGE
	if ($page == 0) {
	$myrecstart = 0;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}




$sql = "SELECT * FROM loop_transaction_buyer WHERE id = '" . $searchcrit . "' OR inv_number = '" . $searchcrit . "'";

$result = mysql_query($sql,db() );

while ($myrowsel = mysql_fetch_array($result)) {
	$wid = $myrowsel["warehouse_id"];
	$transid = $myrowsel["id"];
}


$sql = "SELECT * FROM loop_warehouse WHERE id = " . $wid;


//	echo $sql;
	$result = mysql_query($sql,db() );
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	if ($myrowsel = mysql_fetch_array($result)) {
	$id = $myrowsel["id"];
	




	
	
			do
  			{

  			$id = $myrowsel["id"];
  			

  			switch ($shade)
  			{
  				case "TBL_ROW_DATA_LIGHT":
					$shade = "TBL_ROW_DATA_DRK";
					break;
				case "TBL_ROW_DATA_DRK":
					$shade = "TBL_ROW_DATA_LIGHT";
					break;
				default:
					$shade = "TBL_ROW_DATA_DRK";
					break;
  			}

	echo "<TR>";
			?>
<!--
					<?php $rec_type = $myrowsel["rec_type"]; ?>
					<?php $id = $myrowsel["id"]; ?>
					
			<TD CLASS='<?php echo $shade; ?>'>
				<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&searchcrit=<? echo $searchcrit; ?>&rec_type=<? echo $rec_type; ?>&page=<? if ($page > 0) { $newpage = $page - 1; echo $newpage; } ?><?php echo $pagevars; ?>">View Record</a> 
			</TD>			
-->			
				<?php $warehouse_name = $myrowsel["warehouse_name"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
								<a href="<?php echo "search_results.php"; ?>?id=<?php echo $id; ?>&proc=View&searchcrit=<? echo $searchcrit; ?>&rec_type=<? echo $rec_type; ?>&rec_id=<? echo $transid; ?>&display=buyer_view&page=<? if ($page > 0) { $newpage = $page - 1; echo $newpage; } ?><?php echo $pagevars; ?>"><?php echo $myrowsel["company_name"]; ?></a> 
			</TD>
				<?php $bs_status = $myrowsel["bs_status"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>				<?php echo $warehouse_name; ?> 			</TD>						<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $bs_status; ?> 
			</TD>			
				<?php $warehouse_contact = $myrowsel["warehouse_contact"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_contact; ?> 
			</TD>								
				<?php $warehouse_city = $myrowsel["warehouse_city"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_city; ?> 
			</TD>
				<?php $warehouse_state = $myrowsel["warehouse_state"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $warehouse_state; ?> 
			</TD>
				<?php $last_activity = $myrowsel["last_activity"]; ?>
			<TD CLASS='<?php echo $shade; ?>'>
				<?php echo $last_activity; ?> 
			</TD>

			<TD CLASS='<?php echo $shade; ?>'> </TD>

		</TR>
<?
			} while ($myrowsel = mysql_fetch_array($result));
echo "</TABLE>";

	

}
}

function add_date($givendate,$day) {
      $cd = strtotime($givendate);
      $newdate = date('Y-m-d', mktime(date('m',$cd), date('d',$cd)+$day, date('Y',$cd)));
      return $newdate;
}

function date_diff_new($start, $end="NOW")
{
        $sdate = strtotime($start);
        $edate = strtotime($end);

        $time = $edate - $sdate;
        if($time>=0 && $time<=59) {
                // Seconds
                $timeshift = $time.' seconds ';

        } elseif($time>=60 && $time<=3599) {
                // Minutes + Seconds
                $pmin = ($edate - $sdate) / 60;
                $premin = explode('.', $pmin);
                
                $presec = $pmin-$premin[0];
                $sec = $presec*60;
                
                $timeshift = $premin[0].' min '.round($sec,0).' sec ';

        } elseif($time>=3600 && $time<=86399) {
                // Hours + Minutes
                $phour = ($edate - $sdate) / 3600;
                $prehour = explode('.',$phour);
                
                $premin = $phour-$prehour[0];
                $min = explode('.',$premin*60);
                
                $presec = '0.'.$min[1];
                $sec = $presec*60;

                $timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';

        } elseif($time>=86400) {
                // Days + Hours + Minutes
                $pday = ($edate - $sdate) / 86400;
                $preday = explode('.',$pday);

                $phour = $pday-$preday[0];
                $prehour = explode('.',$phour*24); 

                $premin = ($phour*24)-$prehour[0];
                $min = explode('.',$premin*60);
                
                $presec = '0.'.$min[1];
                $sec = $presec*60;
                
                $timeshift = $preday[0];

        }
        return $timeshift;
}

function useful_links()
{
?>
<table cellSpacing="1" cellPadding="1" border="0" width="500" >
	<tr align="middle">
		<td class="style24" style="height: 16px" >
		<strong>Sales</strong></td>
	</tr>


	
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/report_daily_chart.php">E-Cynthia</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/special_ops_report.php">Special Ops Report</a></td> 
 </tr> 
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/matchGaylordNEW.php">GAYLORD MATCHING TOOL</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_find_buyers.php">FIND COMPANIES THAT BOUGHT A BOX</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_find_quoters.php">FIND COMPANIES THAT YOU QUOTED A BOX</a></td></tr>
      <td bgColor="#e4e4e4" class="style12" ><a href="http://b2b.usedcardboardboxes.com/b2b5/manageCompanies.asp">B2B MANAGE COMPANIES</a></td></tr>
      <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/">LOOPS</a></td></tr>
	  
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://b2b.usedcardboardboxes.com/CL/b2b_gaylord.asp">Gaylord Farming</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://b2b.usedcardboardboxes.com/CL/b2b_pallet.asp">Pallet Box Farming</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/getEmails2.php">B2B Email Extractor</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/gaylordstatus.php">Gaylord Waiting List</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="https://maps.google.com/maps/ms?msid=200896264456963024466.0004c47c2a7b0a8d51fb8&msa=0&ll=49.894634,-95.712891&spn=33.143537,86.572266&iwloc=lyrftr:msid:200896264456963024466.0004c47c2a7b0a8d51fb8,0004c47f23ba253164dff,,,0,-31">UCB Current Gaylord Map</a></td> 
 </tr> 
 	<tr align="middle">
		<td class="style24" style="height: 16px" >
		<strong>System Configuration</strong></td>
	</tr>
      <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/addVendor.php">ADD/EDIT B2B VENDOR</a></td></tr>
      <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/manage_box_b2bloop.php?proc=New&">ADD BOX TO B2B & LOOPS</a></td></tr> 
      <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/addinventory.php">ADD BOX TO B2B ONLY</a></td></tr> 
      <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/manage_box_b2bloop.php?posting=yes">MANAGE B2B/LOOP BOX</a></td></tr> 
	  <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/manage_supplier.php?posting=yes&rec_type=Supplier">Manage Companies that Buy from UCB</a></td></tr>	
	  <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/manage_manufacturer.php?posting=yes&rec_type=Manufacturer">Manage Companies that Sell to UCB</a></td></tr>	
	  <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/manage_sortwh.php?posting=yes&rec_type=Sorting">Manage Sorting Warehouses </a></td></tr>	
	  <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/manage_commodities.php?posting=yes">Manage Commodity Values</a></td></tr>	
	  <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/manage_freightvendor.php?posting=yes">Manage Freight Vendors</a></td></tr>	
	  <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/employee.php?posting=yes">Manage Employees</a></td></tr>
	  	  <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/employee.php?proc=New&">Add New Employee w/ Dashboard</a></td></tr>
 	<tr align="middle">
		<td class="style24" style="height: 16px" >
		<strong>Freight</strong></td>
	</tr>
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/report_freight_broker.php">Freight Broker Report</a></td> 
 </tr> 
 
  	<tr align="middle">
		<td class="style24" style="height: 16px" >
		<strong>B2C</strong></td>
	</tr>
 
 <tr vAlign="center"> <td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbdb/">DASH</a></td></tr>
 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbdb/report_b2c_emails.php">B2C Email Extractor</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/order_source_report.php">Order Source Report – Other</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/report_box_bucks_code.php">Box Bucks Code report</a></td> 
 </tr> 
   	<tr align="middle">
		<td class="style24" style="height: 16px" >
		<strong>HR</strong></td>
	</tr>  <tr vAlign="center">   <td bgColor="#e4e4e4" class="style12" >  <a href="https://docs.google.com/spreadsheet/ccc?key=0AuLZggckxZVUdGJnMGUtVGxOUlV0dDhzSDljTlROb2c&usp=drive_web">Master Roster</a></td>  </tr>   <tr vAlign="center">   <td bgColor="#e4e4e4" class="style12" >  <a href="https://docs.google.com/document/d/1stD9FTo0U9F7WNdFzSuVN4mSUqmZWl7G7-A7ShitiN8/edit?usp=sharing">UCB Resources / Procedures</a></td>  </tr> 
  	<tr align="middle">
		<td class="style24" style="height: 16px" >
		<strong>Other</strong></td>
	</tr> <tr vAlign="center">   <td bgColor="#e4e4e4" class="style12" >  <a href="http://www.usedcardboardboxes.com/ucbloop/report_sop.php">SOPs</a></td>  </tr>  <tr vAlign="center">   <td bgColor="#e4e4e4" class="style12" >  <a href="http://www.usedcardboardboxes.com/ucbloop/demodashboard.php">Demo Dashboard</a></td>  </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://usedcardboardboxeshv.myq-see.com:90/webcamera.html">Hunt Valley Cameras</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://usedcardboardboxesev.myq-see.com:90/webcamera.html">EVV Cameras</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="https://docs.google.com/spreadsheet/ccc?key=0Akv0bNDB5PrkdDlIX2Ztb3Fid2NFUFRCNDVwZXZ1V1E#gid=0">Email Alert List</a></td> 
 </tr> 
 

</table>


<?

}


function useful_links_admin()
{
?>
<table cellSpacing="1" cellPadding="1" border="0" width="500" >
	<tr align="middle">
		<td class="style24" style="height: 16px" >
		<strong>LINKS</strong></td>
	</tr>


	<tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/matchGaylordNEW.php">GAYLORD MATCHING TOOL</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_find_buyers.php">FIND COMPANIES THAT BOUGHT A BOX</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_find_quoters.php">FIND COMPANIES THAT YOU QUOTED A BOX</a></td></tr>
      <td bgColor="#e4e4e4" class="style12" ><a href="http://b2b.usedcardboardboxes.com/b2b5/manageCompanies.asp">B2B MANAGE COMPANIES</a></td></tr>
      <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/">LOOPS</a></td></tr>
      <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbdb/">DASH</a></td></tr>
      <td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/addVendor.php">ADD/EDIT B2B VENDOR</a></td></tr>
      <td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/addinventory.php">ADD B2B BOX</a></td></tr> <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" style="width: 10%"> 
  <a href="http://www.usedcardboardboxes.com/ucbloop/huntvalleywarehouse_141592653.php">Hunt Valley Dashboard</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/demodashboard.php">Demo Dashboard</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://usedcardboardboxeshv.myq-see.com:90/webcamera.html">Hunt Valley Cameras</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://usedcardboardboxesev.myq-see.com:90/webcamera.html">EVV Cameras</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://b2b.usedcardboardboxes.com/CL/b2b_gaylord.asp">Gaylord Farming</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://b2b.usedcardboardboxes.com/CL/b2b_pallet.asp">Pallet Box Farming</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/getEmails2.php">B2B Email Extractor</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbdb/report_b2c_emails.php">B2C Email Extractor</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/report_daily_chart.php">E-Cynthia</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="https://maps.google.com/maps/ms?msid=200896264456963024466.0004c47c2a7b0a8d51fb8&msa=0&ll=49.894634,-95.712891&spn=33.143537,86.572266&iwloc=lyrftr:msid:200896264456963024466.0004c47c2a7b0a8d51fb8,0004c47f23ba253164dff,,,0,-31">UCB Current Gaylord Map</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="https://docs.google.com/spreadsheet/ccc?key=0Akv0bNDB5PrkdDlIX2Ztb3Fid2NFUFRCNDVwZXZ1V1E#gid=0">Email Alert List</a></td> 
 </tr> 
 <tr vAlign="center"> 
  <td bgColor="#e4e4e4" class="style12" >
  <a href="http://www.usedcardboardboxes.com/ucbloop/gaylordstatus.php">Gaylord Waiting List</a></td> 
 </tr> 

      <tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_hourspertrailer.php">HUNT VALLEY HOURS PER TRAILER</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_timeclock.php">TIMECLOCK REPORT</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/huntvalleywarehouse_141592653.php">HUNT VALLEY DASHBOARD</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/dashboard_HF_8628996208.php">HANOVER FOODS DASHBOARD</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_employee_management.php">ADD/DELETE WORKERS</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_find_buyers.php">FIND COMPANIES THAT BOUGHT A BOX</a></td></tr>
	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/report_timeclock.php">TIMECLOCK REPORT</a></td></tr>
 	<tr><td bgColor="#e4e4e4" class="style12" ><a href="http://analytics.google.com">Google Analytics (info@ucb.com/...4032)</a></td></tr>
</table>


<?

}

function showopenquotes($eid)
{
?>
<form  method="post" action="updateQuoteStatus2.php">
	<table >
		<tr>
			<td class="style24" colspan=18 style="height: 16px" align="middle"><strong>OPEN QUOTES</strong></td>		</tr>
		<tr>
			<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Date</strong></td>
			<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
			<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Rep</strong></td>
			<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Type</strong></td>
			<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Amount</strong></td>
			<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Status</strong></td>
		</tr>
		<?

		$dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE (qstatus = 1 OR qstatus = 0) AND (quoteType = 'Quote' OR quoteType = 'Quote Select') AND employees.employeeID = " . $eid;
//echo $dt_view_qry;
		$dt_view_res = mysql_query($dt_view_qry,db_b2b() );
		while ($dt_view_row = mysql_fetch_array($dt_view_res)) {


?>
		<tr>
			
			 <td bgColor="#e4e4e4" class="style12" >
				<?=timestamp_to_date($dt_view_row["quoteDate"]);?>
			</td>
			 <td bgColor="#e4e4e4" class="style12" >
				<a href ="http://www.usedcardboardboxes.com/ucbloop/viewCompany.php?ID=<?=$dt_view_row["companyID"];?>"><?=$dt_view_row["company"];?></a>
			</td>
			 <td bgColor="#e4e4e4" class="style12" >
				<?=$dt_view_row["E"];?>
			</td>
			 <td bgColor="#e4e4e4" class="style12" >
<?
if ($dt_view_row["filename"] != "") { ?>
<a href="http://www.usedcardboardboxes.com/ucbloop/quotes/<?=$dt_view_row["filename"];?>">
<?
} elseif ($dt_view_row["quoteType"] == "Quote") {

?>
<a href="http://www.usedcardboardboxes.com/ucbloop/fullquote.php?ID=<?=$dt_view_row["I"]?>">
<? } elseif ($dt_view_row["quoteType"] == "Quote Select") {

?>
<a href="http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=<?=$dt_view_row["I"]?>">
<? } ?>				<?=$dt_view_row["quoteType"];?></a>
			</td>
			 <td bgColor="#e4e4e4" class="style12" >
				<?=number_format($dt_view_row["quote_total"],2);?>
			</td>
			 <td bgColor="#e4e4e4" class="style12" >

<input type="hidden" name="quote_id[]" value="<?=$dt_view_row["I"]?>">
			   <select size="1" name="quote_status[]">
<?
$box = 0;

$boxSql = "Select * from quote_status Where status=1";
$dt_view_res4 = mysql_query($boxSql,db_b2b() );
while ($objQStatus= mysql_fetch_array($dt_view_res4)) {

	if ($objQStatus["qid"] == $dt_view_res["qstatus"] )
		$strSelected = " selected";
	else
		$strSelected= "";
	
?>

		<option value="<?=$objQStatus["qid"]?>" <?="$strSelected"?>" <?=$strSelected?>><?=$objQStatus["status_name"]?></option>
<?
}
?>
		</select></td>


		</tr>
		<?
		}	//while loop
		?>
	<tr>
			<td class="style24" colspan=18 style="height: 16px" align="middle"> <input type="submit" value="Update" name="B1"></td></tr>
	</table>
</form>
<?
}


function searchbox($url, $eid){
?>

	<!----------------------------------------------------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	<!----------------------------------- SEARCH ---------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	
<form method="get" action="<?=$url?>">
<input type=hidden name="show" value="search">
	Search B2B & Loops: <input type=text name="searchterm" size=40 value="<?=$_REQUEST["searchterm"];?>"> &nbsp; <select name="andor"> <option value="AND">CONTAIN ALL WORDS</option><option <? if ($_REQUEST["andor"] == "OR") echo " selected ";?> value="OR">CONTAIN ANY WORD</option></select> 
<? $state = $_REQUEST["state"]; ?>
in <select name="state" id="state">
	<option value="ALL" <?PHP if($state=="ALL") echo "selected";?>>Any State</option>
	<option value="AL" <?PHP if($state=="AL") echo "selected";?>>Alabama</option>
	<option value="AK" <?PHP if($state=="AK") echo "selected";?>>Alaska</option>
	<option value="AZ" <?PHP if($state=="AZ") echo "selected";?>>Arizona</option>
	<option value="AR" <?PHP if($state=="AR") echo "selected";?>>Arkansas</option>
	<option value="CA" <?PHP if($state=="CA") echo "selected";?>>California</option>
	<option value="CO" <?PHP if($state=="CO") echo "selected";?>>Colorado</option>
	<option value="CT" <?PHP if($state=="CT") echo "selected";?>>Connecticut</option>
	<option value="DE" <?PHP if($state=="DE") echo "selected";?>>Delaware</option>
	<option value="DC" <?PHP if($state=="DC") echo "selected";?>>District of Columbia</option>
	<option value="FL" <?PHP if($state=="FL") echo "selected";?>>Florida</option>
	<option value="GA" <?PHP if($state=="GA") echo "selected";?>>Georgia</option>
	<option value="HI" <?PHP if($state=="HI") echo "selected";?>>Hawaii</option>
	<option value="ID" <?PHP if($state=="ID") echo "selected";?>>Idaho</option>
	<option value="IL" <?PHP if($state=="IL") echo "selected";?>>Illinois</option>
	<option value="IN" <?PHP if($state=="IN") echo "selected";?>>Indiana</option>
	<option value="IA" <?PHP if($state=="IA") echo "selected";?>>Iowa</option>
	<option value="KS" <?PHP if($state=="KS") echo "selected";?>>Kansas</option>
	<option value="KY" <?PHP if($state=="KY") echo "selected";?>>Kentucky</option>
	<option value="LA" <?PHP if($state=="LA") echo "selected";?>>Louisiana</option>
	<option value="ME" <?PHP if($state=="ME") echo "selected";?>>Maine</option>
	<option value="MD" <?PHP if($state=="MD") echo "selected";?>>Maryland</option>
	<option value="MA" <?PHP if($state=="MA") echo "selected";?>>Massachusetts</option>
	<option value="MI" <?PHP if($state=="MI") echo "selected";?>>Michigan</option>
	<option value="MN" <?PHP if($state=="MN") echo "selected";?>>Minnesota</option>
	<option value="MS" <?PHP if($state=="MS") echo "selected";?>>Mississippi</option>
	<option value="MO" <?PHP if($state=="MO") echo "selected";?>>Missouri</option>
	<option value="MT" <?PHP if($state=="MT") echo "selected";?>>Montana</option>
	<option value="NE" <?PHP if($state=="NE") echo "selected";?>>Nebraska</option>
	<option value="NV" <?PHP if($state=="NV") echo "selected";?>>Nevada</option>
	<option value="NH" <?PHP if($state=="NH") echo "selected";?>>New Hampshire</option>
	<option value="NJ" <?PHP if($state=="NJ") echo "selected";?>>New Jersey</option>
	<option value="NM" <?PHP if($state=="NM") echo "selected";?>>New Mexico</option>
	<option value="NY" <?PHP if($state=="NY") echo "selected";?>>New York</option>
	<option value="NC" <?PHP if($state=="NC") echo "selected";?>>North Carolina</option>
	<option value="ND" <?PHP if($state=="ND") echo "selected";?>>North Dakota</option>
	<option value="OH" <?PHP if($state=="OH") echo "selected";?>>Ohio</option>
	<option value="OK" <?PHP if($state=="OK") echo "selected";?>>Oklahoma</option>
	<option value="OR" <?PHP if($state=="OR") echo "selected";?>>Oregon</option>
	<option value="PA" <?PHP if($state=="PA") echo "selected";?>>Pennsylvania</option>
	<option value="RI" <?PHP if($state=="RI") echo "selected";?>>Rhode Island</option>
	<option value="SC" <?PHP if($state=="SC") echo "selected";?>>South Carolina</option>
	<option value="SD" <?PHP if($state=="SD") echo "selected";?>>South Dakota</option>
	<option value="TN" <?PHP if($state=="TN") echo "selected";?>>Tennessee</option>
	<option value="TX" <?PHP if($state=="TX") echo "selected";?>>Texas</option>
	<option value="UT" <?PHP if($state=="UT") echo "selected";?>>Utah</option>
	<option value="VT" <?PHP if($state=="VT") echo "selected";?>>Vermont</option>
	<option value="VA" <?PHP if($state=="VA") echo "selected";?>>Virginia</option>
	<option value="WA" <?PHP if($state=="WA") echo "selected";?>>Washington</option>
	<option value="WV" <?PHP if($state=="WV") echo "selected";?>>West Virginia</option>
	<option value="WI" <?PHP if($state=="WI") echo "selected";?>>Wisconsin</option>
	<option value="WY" <?PHP if($state=="WY") echo "selected";?>>Wyoming</option>
</select>

Include Trash:<input type="checkbox" name="chktrash" id="chktrash" <? if ($_REQUEST["chktrash"] == "on") {echo "checked";}  else {echo "";}?>> <input type=submit value="Search" >
<br/><br/>
	<? 
	$qry_sql = "SELECT flag_assignto_viewby FROM employees WHERE employeeID=" . $eid;
	$view_res = mysql_query($qry_sql ,db_b2b() );
	while ($res= mysql_fetch_array($view_res)) {
		if ($res["flag_assignto_viewby"] == 1 ) {		?>
		<a href="update_viewby_assignflag.php?eid=<?=$eid;?>">Records displayed are 'Assign to you'. Click here to display records 'Assign to you and Viewable by you'.</a>
	<? } else {?>
		<a href="update_viewby_assignflag.php?eid=<?=$eid;?>">Records displayed are 'Assign to you and Viewable by you'. Click here to display records 'Assign to you'.</a>
	<? }
	}
?>	
	</form>

<? }


function showinventory($warehouseid){

echo "<script type=\"text/javascript\">";
echo "function display_preoder() {";
echo " var totcnt = document.getElementById('inventory_preord_totctl').value;";

echo " for (var tmpcnt = 1; tmpcnt < totcnt; tmpcnt++) {";
echo " if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') ";
echo " { document.getElementById('inventory_preord_top_' + tmpcnt).style.display='none'; } else {";
echo "  document.getElementById('inventory_preord_top_' + tmpcnt).style.display='table-row'; } ";

echo " if (document.getElementById('inventory_preord_top2_' + tmpcnt).style.display == 'table-row') ";
echo " { document.getElementById('inventory_preord_top2_' + tmpcnt).style.display='none'; } else {";
echo "  document.getElementById('inventory_preord_top2_' + tmpcnt).style.display='table-row'; } ";

echo " if (document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display == 'table-row') ";
echo " { document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display='none'; } else {";
echo "  document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display='table-row'; } ";

echo " var totcnt_child = document.getElementById('inventory_preord_bottom_hd'+ tmpcnt).value;";

echo " for (var tmpcnt_n = 1; tmpcnt_n < totcnt_child; tmpcnt_n++) {";
echo " if (document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display == 'table-row') ";
echo " { document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display='none'; } else {";
echo "  document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display='table-row'; } ";
echo "}";

echo "}";
echo "}";

echo "function display_preoder_sel(tmpcnt, reccnt) {";
echo "if (reccnt > 0 ) {";
echo " var totcnt = document.getElementById('inventory_preord_totctl').value;";

echo " if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') ";
echo " { document.getElementById('inventory_preord_top_' + tmpcnt).style.display='none'; } else {";
echo "  document.getElementById('inventory_preord_top_' + tmpcnt).style.display='table-row'; } ";

echo " if (document.getElementById('inventory_preord_top2_' + tmpcnt).style.display == 'table-row') ";
echo " { document.getElementById('inventory_preord_top2_' + tmpcnt).style.display='none'; } else {";
echo "  document.getElementById('inventory_preord_top2_' + tmpcnt).style.display='table-row'; } ";

echo " if (document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display == 'table-row') ";
echo " { document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display='none'; } else {";
echo "  document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display='table-row'; } ";

echo " var totcnt_child = document.getElementById('inventory_preord_bottom_hd'+ tmpcnt).value;";

echo " for (var tmpcnt_n = 1; tmpcnt_n < totcnt_child; tmpcnt_n++) {";
echo " if (document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display == 'table-row') ";
echo " { document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display='none'; } else {";
echo "  document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display='table-row'; } ";
echo "}";
echo "}";
echo "}";

echo "</script>";

	$dt_so = "SELECT * , loop_bol_files.bol_shipped AS A, loop_transaction_buyer.id AS I FROM loop_transaction_buyer LEFT JOIN`loop_bol_files`ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE bol_create = 0 ORDER BY loop_bol_files.bol_shipped ASC ";
	$dt_res_so = mysql_query($dt_so,db() );
	$c = 0;
	while ($so_row = mysql_fetch_array($dt_res_so)) {
		if ($so_row["A"] <> 1)
		{
		$dt_so_item = "SELECT * FROM loop_salesorders  WHERE trans_rec_id = " . $so_row["I"] ;
		$dt_res_so_item = mysql_query($dt_so_item,db() );

			while ($so_item_row = mysql_fetch_array($dt_res_so_item)) {

				$inv_array[$so_item_row["location_warehouse_id"]][$so_item_row["box_id"]] += $so_item_row["qty"];
				//echo $c . "<a href=http://www.usedcardboardboxes.com/ucbloop/search_results.php?warehouse_id=" . $so_item_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $so_item_row["warehouse_id"] . "&rec_id=" . $so_row["I"] . "&display=buyer_view>" . $so_item_row["so_date"] . "</a><br>";
				//$c = $c +1;
			}
		}
	}

if ($warehouseid == 0 ) {
	/////////////////////////////////////////// NEW INVENTORY SALES ORDER VALUES

	//print_r($so_item_row);
	?>
	<!--------------------------NEW INVENTORY ---------------------------------------------->
	  <table cellSpacing="1" cellPadding="1" border="0" width="1200" >
		<tr align="middle">
		  <td colspan="12"class="style24" style="height: 16px"><strong>INVENTORY NOTES</strong> <a href="updateinventorynotes.php">Edit</a></td>
		</tr>
		 <tr vAlign="left">
		 <td colspan=12>
		 <?
			$sql = "SELECT * FROM loop_inventory_notes ORDER BY dt DESC LIMIT 0,1";
			$res = mysql_query($sql,db() );
			$row = mysql_fetch_array($res);
			echo $row["notes"];
		 ?>
		 <br/>
		 </td>
		</tr>
	 
		<tr align="middle">
		  <td colspan="12" class="style24" style="height: 16px"><strong>NON INVENTORY TOTES</strong> <a href="gaylordstatus.php">View Waitlist</a></td>
		</tr>
		<tr vAlign="left">	
			<td bgColor="#e4e4e4" class="style12" colspan=2><b>Availability</b>
			</td>  
			<td bgColor="#e4e4e4" class="style12" colspan=2><font size=1><b>Vendor</b></font></td>	  

			<td bgColor="#e4e4e4" class="style12left" colspan=5><b>Description</b></font></td>	  

			<td bgColor="#e4e4e4" class="style12left" ><b>Update</b></td>

			<td colspan="2" bgColor="#e4e4e4"class="style12left" ><b>Notes</b></td>
		</tr>
		 <tr vAlign="left">
		 <td colspan=12>
		 <?
			$x=0;

			$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.gaylord=1 AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 ORDER BY inventory.availability DESC, vendors.name ASC";
			$dt_view_res = mysql_query($sql,db_b2b() );

	while ($inv = mysql_fetch_array($dt_view_res)) {

			$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
			$loop_res = mysql_query($loopsql,db() );

	$loop = mysql_fetch_array($loop_res);

	if ($x==0) {
	$x = 1;
	$bg = "#e4e4e4";
	} else {
	$x = 0;
	$bg = "#f4f4f4";
	}
			$tipStr = "";

			if ($inv["shape_rect"] == "1" )

				$tipStr = $tipStr . " Rec ";	

										

			if ($inv["shape_oct"] == "1" )

				$tipStr = $tipStr . " Oct ";

								

			if ($inv["wall_2"] == "1" )

				$tipStr = $tipStr . " 2W ";	

										

			if ($inv["wall_3"] == "1" )

				$tipStr = $tipStr . " 3W ";		

										

			if ($inv["wall_4"] == "1" )

				$tipStr = $tipStr . " 4W ";	

											

			if ($inv["wall_5"] == "1" )

				$tipStr = $tipStr . " 5W ";	

											

			if ($inv["top_nolid"] == "1" )

				$tipStr = $tipStr . " No Top,";

								

			if ($inv["top_partial"] == "1" )

				$tipStr = $tipStr . " Flange Top, ";

								

			if ($inv["top_full"] == "1" )

				$tipStr = $tipStr . " FFT, ";

								

			if ($inv["top_hinged"] == "1" )

				$tipStr = $tipStr . " Hinge Top, ";

								

			if ($inv["top_remove"] == "1" )

				$tipStr = $tipStr . " Lid Top, ";

					

			if ($inv["bottom_no"] == "1" )

				$tipStr = $tipStr . " No Bottom";

			

			if ($inv["bottom_partial"] == "1" )

				$tipStr = $tipStr . " PB w/o SS";

			

			if ($inv["bottom_partialsheet"] == "1" )

				$tipStr = $tipStr . " PB w/ SS";

			

			if ($inv["bottom_fullflap"] == "1" )

				$tipStr = $tipStr . " FFB";

					

			if ($inv["bottom_interlocking"] == "1" )

				$tipStr = $tipStr . " FB";

					

			if ($inv["bottom_tray"] == "1" )

				$tipStr = $tipStr . " Tray Bottom";

					

			if ($inv["vents_no"] == "1" )

				$tipStr = $tipStr . "";

					

			if ($inv["vents_yes"] == "1" )

				$tipStr = $tipStr . ", Vents";



			?>

		

		<tr vAlign="center">

			<td bgColor="<?=$bg;?>" class="style12" colspan=2>	
			<? if ($inv["availability"] == "3" ) echo "Available Now & Urgent"; ?>
			<? if ($inv["availability"] == "2" ) echo "Available Now"; ?>
			<? if ($inv["availability"] == "1" ) echo "Available Soon"; ?>
			<? if ($inv["availability"] == "-1" ) echo "Presell"; ?>
			<? if ($inv["availability"] == "-2" ) echo "Active by Unavailable"; ?>
			<? if ($inv["availability"] == "-3" ) echo "Potential"; ?>
			</td>  
			<td bgColor="<?=$bg;?>" class="style12" colspan=2><font size=1><? echo $inv["VN"]; ?></a></font></td>	  

			<td bgColor="<?=$bg;?>" class="style12left" colspan=5><font size=1><? echo $inv["L"] . " " . $inv["LF"] . " x " . $inv["W"] . " " . $inv["WF"] . " x " . $inv["D"] . " " . $inv["DF"] . " " . $tipStr; ?> </font></td>	  


			<td bgColor="<?=$bg;?>" class="style12left" ><? if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?></td>
			
			<td colspan="2" bgColor="<?=$bg;?>" class="style12left" ><? if ($loop["id"] > 0) { ?><a href="http://www.usedcardboardboxes.com/ucbloop/manage_box_b2bloop.php?id=<? echo $loop["id"]; ?>&proc=View&" target="_blank"><? echo $inv["N"]; ?></a><? } else { ?><? echo $inv["N"]; ?></a> <? } ?></td>
		<?

		}
			 ?>
			 
	 </tr>
	<?
	}

	if ($warehouseid > 0 ) {
		$bg = "#f4f4f4";
	?>
	
		<table cellSpacing="1" cellPadding="1" border="0" align="center" width="850" >
		 <tr align="middle">
		  <td colspan="12" class="style7" style="height: 16px"><strong>INVENTORY</strong>
		  <a href='#' onclick="display_preoder()">Expand/Collapse</a>
		  </td>
		</tr>
		
		<tr vAlign="center">
		  <td bgColor="#e4e4e4" class="style17" ><strong>Actual</strong></td>
		  <td bgColor="#e4e4e4" class="style17" ><strong>After POs</strong></td>
		  <td bgColor="#e4e4e4" class="style17" ><strong>Last Month Qty</strong></td>
		  <td bgColor="#e4e4e4" class="style17" ><strong>Warehouse</strong></td>
		  <td bgColor="#e4e4e4" class="style17" ><strong>Type</strong></td>	  
		  <td bgColor="#e4e4e4" class="style17" colspan=7><strong>Box Description</strong></td>	  
		</tr>		
	<?
	}else {
		$bg = "#f4f4f4";
	?>
	
		 <tr align="middle">
		  <td colspan="12" class="style24" style="height: 16px"><strong>INVENTORY</strong>
		  <a href='#' onclick="display_preoder()">Expand/Collapse</a>
		  </td>
		</tr>
			
		<tr vAlign="center">
		  <td bgColor="#e4e4e4" class="style12" ><strong>Actual</strong></td>
		  <td bgColor="#e4e4e4" class="style12" ><strong>After POs</strong></td>
		  <td bgColor="#e4e4e4" class="style12" ><strong>Last Month Qty</strong></td>
		  <td bgColor="#e4e4e4" class="style12" ><strong>Warehouse</strong></td>
		  <td bgColor="#e4e4e4" class="style12" ><strong>Type</strong></td>	  
		  <td bgColor="#e4e4e4" class="style12" colspan=7><strong>Box Description</strong></td>	  
		</tr>		
	<?}?>

<?

if ($warehouseid > 0 ) {
	$dt_view_qry = "SELECT loop_boxes.boxes_per_trailer, loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id where loop_warehouse.id = $warehouseid GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth ";
}else {
	$dt_view_qry = "SELECT loop_boxes.boxes_per_trailer, loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth ";
}
$dt_view_res = mysql_query($dt_view_qry,db() );
// $num_rows = mysql_num_rows($dt_view_res);
// if ($num_rows > 0) 
$preordercnt = 1;
while ($dt_view_row = mysql_fetch_array($dt_view_res)) {

	if ($dt_view_row["A"] != 0 OR $dt_view_row["A"] - $inv_array[$dt_view_row["wid"]][$dt_view_row["I"]] !=0 )
	{

		$lastmonth_qry = "SELECT sum(boxgood) as sumboxgood from loop_inventory where box_id = " . $dt_view_row["I"] . " AND boxgood >0 and ";
		$lastmonth_qry .= " UNIX_TIMESTAMP(add_date) >= " .  strtotime('today - 30 days') . " AND UNIX_TIMESTAMP(add_date) <= " . strtotime(date("m/d/Y"))  ; 

		$lastmonth_res = mysql_query($lastmonth_qry,db() );
		$lastmonth_val = 0;
		while ($lastmonth_row = mysql_fetch_array($lastmonth_res)) {
			$lastmonth_val = $lastmonth_row["sumboxgood"];
		}

		$dt_so = "SELECT loop_salesorders.so_date, loop_salesorders.warehouse_id, loop_salesorders.qty AS QTY, loop_warehouse.company_name AS NAME, loop_transaction_buyer.id as transid FROM loop_salesorders ";
		$dt_so .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
		$dt_so .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
		$dt_so .= " WHERE loop_salesorders.box_id = " . $dt_view_row["I"] . " and loop_salesorders.location_warehouse_id= " . $dt_view_row["wid"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id desc";
		
		$dt_res_so = mysql_query($dt_so,db() );
		$reccnt = mysql_num_rows($dt_res_so);

		$preorder_txt = "";
		$preorder_txt2 = "";
		
		if ($reccnt > 0){ 
			$preorder_txt = "<u>";
			$preorder_txt2 = "</u>";
		}
		
		if (($dt_view_row["A"] >= $dt_view_row["boxes_per_trailer"]) && ($dt_view_row["boxes_per_trailer"] > 0)) {
				$bg = "yellow";
		}
		
		if ($dt_view_row["ISBOX"] != 'Y')	
		{
		?>
	
		<tr vAlign="center" >
		  <td bgColor="<?=$bg;?>" class="style12" ><? 
			if ($dt_view_row["A"] < 0) {?>
			<font color="red">	
		  <?  echo $dt_view_row["A"]; ?> </font>	<?	 } else { echo $dt_view_row["A"];  }?>
		  </td>
		  <td bgColor="<?=$bg;?>" class="style12" >
		  <? $actual_po = $dt_view_row["A"] - $inv_array[$dt_view_row["wid"]][$dt_view_row["I"]]; 
		  if ($actual_po < 0) {?>
			<div onclick="display_preoder_sel(<? echo $preordercnt;?>, <? echo $reccnt;?>)" style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial"><font color="blue"><? echo $preorder_txt;?><? 
				  echo $actual_po; ?><? echo $preorder_txt2;?></font></div>
			<?	} else { ?>
			<div onclick="display_preoder_sel(<? echo $preordercnt;?>, <? echo $reccnt;?>)" style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial"><font color="green"><? echo $preorder_txt;?><? 
				  echo $actual_po; 
		  ?></font><? echo $preorder_txt2;?></div> <? } ?></td>
		  <td bgColor="<?=$bg;?>" width='60px' class="style12"><? echo $lastmonth_val; ?></td>	  
		  <td bgColor="<?=$bg;?>" class="style12" ><? echo $dt_view_row["B"]; ?></td>	  
		  <td bgColor="<?=$bg;?>" class="style12" ><? echo $dt_view_row["TYPE"]; ?></td>	  
		  <td bgColor="<?=$bg;?>" class="style12left" colspan="7"><a href="http://www.usedcardboardboxes.com/ucbloop/manage_box_b2bloop.php?id=<?=$dt_view_row["I"];?>&proc=View&"><? echo $dt_view_row["C"]; ?></a></td>	  
		</tr>
		<?
			if ($x==0) {
				$x = 1;
				$bg = "#e4e4e4";
				} else {
				$x = 0;
				$bg = "#f4f4f4";
			}
			
		} else {
		?>
	
			<tr vAlign="center" >
				  <td bgColor="<?=$bg;?>" class="style12" >
				  <? 
					if ($dt_view_row["A"] < 0) {?>
					<a href="report_inventory.php?inventory_id=<? echo $dt_view_row["I"]; ?>&action=run" target="run"><font color="red"><? echo $dt_view_row["A"]; ?></font></a>
					<?	 } else { ?>
					<a href="report_inventory.php?inventory_id=<? echo $dt_view_row["I"]; ?>&action=run" target="run"><font color="green"><? echo $dt_view_row["A"]; ?></font></a>
				   <? }?>
				  </td>
				  <td bgColor="<?=$bg;?>" class="style12" >
					  <? $actual_po = $dt_view_row["A"] - $inv_array[$dt_view_row["wid"]][$dt_view_row["I"]]; 
					  if ($actual_po < 0) {?>
						<div onclick="display_preoder_sel(<? echo $preordercnt;?>, <? echo $reccnt;?>)" style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial"><font color="blue"><? echo $preorder_txt;?><? 
							  echo $actual_po; ?><? echo $preorder_txt2;?></font></div>
						<?	} else { ?>
						<div onclick="display_preoder_sel(<? echo $preordercnt;?>, <? echo $reccnt;?>)" style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial"><font color="green"><? echo $preorder_txt;?><? 
							  echo $actual_po; 
					?></font><? echo $preorder_txt2;?></div> <? } ?>
				   </td>
					<td bgColor="<?=$bg;?>" class="style12"><? echo $lastmonth_val; ?></td>	  
					<td bgColor="<?=$bg;?>" class="style12" ><? echo $dt_view_row["B"]; ?></td>	  
					<td bgColor="<?=$bg;?>" class="style12" ><? echo $dt_view_row["TYPE"]; ?></td>	  
					<td bgColor="<?=$bg;?>" width=40 class="style12left"><? echo $dt_view_row["L"] . " " . $dt_view_row["LF"]; ?></td>
					<td bgColor="<?=$bg;?>" class="style12left"> x </td>
					<td bgColor="<?=$bg;?>" width=40 class="style12left"><? echo $dt_view_row["W"] . " " . $dt_view_row["WF"]; ?></td>
					<td bgColor="<?=$bg;?>" class="style12left"> x </td>
					<td bgColor="<?=$bg;?>" width=40 class="style12left"><? echo $dt_view_row["D"] . " " . $dt_view_row["DF"]; ?></td>

				<? if ( $dt_view_row["WALL"] > 1) { echo "<td width=80 bgColor=\"" . $bg . "\" class=\"style12left\">" . $dt_view_row["WALL"] . "-WALL "; } else { echo "<td width=80 bgColor=\"" . $bg . "\" class=\"style12left\">"; } ?>  <? echo $dt_view_row["ST"]; ?></td>
					<td bgColor="<?=$bg;?>" class="style12left"><a href="http://www.usedcardboardboxes.com/ucbloop/manage_box_b2bloop.php?id=<?=$dt_view_row["I"];?>&proc=View&" target="_blank"><? echo $dt_view_row["C"]; ?></a></td>	  
			</tr>
			<?
			if ($x==0) {
				$x = 1;
				$bg = "#e4e4e4";
				} else {
				$x = 0;
				$bg = "#f4f4f4";
				}
				
				
		}	

		if ($reccnt > 0) {?>			
			<tr id='inventory_preord_top_<? echo $preordercnt;?>' align="middle" style="display:none;">
			  <td>&nbsp;</td>
			  <td colspan="11" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px"><b>Pre-Orders</b></td>
			</tr>
			<tr id='inventory_preord_top2_<? echo $preordercnt;?>' align="middle" style="display:none;">
				<td  >&nbsp;</td>
				<td bgColor='#FAFCDF' class="style12" >Sales Order Qty</td>
				<td bgColor='#FAFCDF' class="style12" >Sales Order Date</td>
				<td bgColor='#FAFCDF' class="style12" >Client</td>
				<td bgColor='#FAFCDF' class="style12" colspan="8">Transaction log</td>
			</tr>
	<?	$preordercnt_child = 1;}
		while ($so_row = mysql_fetch_array($dt_res_so)) {
				$sql_transnotes = "SELECT *, loop_employees.initials AS EI FROM loop_transaction_notes INNER JOIN loop_employees ON loop_transaction_notes.employee_id = loop_employees.id WHERE loop_transaction_notes.company_id = " . $so_row["warehouse_id"] . " AND  loop_transaction_notes.rec_id = " . $so_row["transid"] . " ORDER BY loop_transaction_notes.id DESC limit 1";
				$result_transnotes = mysql_query($sql_transnotes,db() );
				
				$trans_log_notes = ""; $trans_log_emp = ""; $trans_log_dt = "";
				while ($myrowsel_transnotes = mysql_fetch_array($result_transnotes)) {
					$trans_log_notes  = $myrowsel_transnotes["message"];
					$trans_log_emp  = $myrowsel_transnotes["EI"];
					$trans_log_dt  = $myrowsel_transnotes["date"];
				}
				
			?>			
				<tr id='inventory_preord_<? echo $preordercnt;?>_<? echo $preordercnt_child;?>' style="display:none;">
					<td  >&nbsp;</td>
					<td bgColor="<?=$bg;?>" class="style12" ><? echo $so_row["QTY"]; ?></td>
					<td bgColor="<?=$bg;?>" class="style12" ><? echo $so_row["so_date"]; ?></td>
					<td bgColor="<?=$bg;?>" class="style12" ><a href="http://www.usedcardboardboxes.com/ucbloop/search_results.php?warehouse_id=<?=$so_row["warehouse_id"];?>&rec_type=Supplier&proc=View&searchcrit=&id=<?=$so_row["warehouse_id"];?>&rec_id=<?=$so_row["transid"];?>&display=buyer_view"><?=$so_row["NAME"];?></a></td>
					<td bgColor="<?=$bg;?>" colspan="8" style="font-size: xx-small;	font-family: Arial, Helvetica, sans-serif; color: #333333;	text-align:left;"><? echo "Transaction log: " . $trans_log_notes . " (" . $trans_log_dt . "-". $trans_log_emp . ")"; ?></td>
				</tr>
		<?$preordercnt_child = $preordercnt_child + 1;}		
		if ($reccnt > 0) {?>			
			<tr id='inventory_preord_bottom_<? echo $preordercnt;?>' align="middle" style="display:none;">
			  <td colspan="12" style="font-size: xx-small; font-family: Arial, Helvetica, sans-serif; height: 16px">
			  <input type="hidden" id='inventory_preord_bottom_hd<? echo $preordercnt;?>' value="<?=$preordercnt_child;?>"/></td>
			</tr>
		<? 
		$preordercnt = $preordercnt + 1;
		} 

	}
} 
?>	
	
  </table>
  
<?
	echo "<input type='hidden' id='inventory_preord_totctl' value='$preordercnt' />";


}

function showmap(){ ?>
<iframe width="800" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps/ms?ie=UTF8&amp;hl=en&amp;oe=UTF8&amp;msa=0&amp;msid=200896264456963024466.0004c47c2a7b0a8d51fb8&amp;t=h&amp;ll=37.509726,-92.548828&amp;spn=27.770502,70.3125&amp;z=4&amp;output=embed"></iframe><br /><small>View <a href="https://maps.google.com/maps/ms?ie=UTF8&amp;hl=en&amp;oe=UTF8&amp;msa=0&amp;msid=200896264456963024466.0004c47c2a7b0a8d51fb8&amp;t=h&amp;ll=37.509726,-92.548828&amp;spn=27.770502,70.3125&amp;z=4&amp;source=embed" style="color:#0000FF;text-align:left">UCB Current Gaylords &amp; Boxes</a> in a larger map</small>
<? }

function showcontacts($initials){

$con_roblem_you = "SELECT * FROM ucb_contact WHERE employee = '" . $initials . "' AND status = 'Attention' AND type_id NOT LIKE '%mb_rdy%'";
$con_roblem_all = "SELECT * FROM ucb_contact WHERE status = 'Attention' AND type_id NOT LIKE '%mb_rdy%'";
$con_roblem_you_result = mysql_query($con_roblem_you, db());
$con_roblem_all_result = mysql_query($con_roblem_all, db());
$con_roblem_you_result_rows = mysql_num_rows($con_roblem_you_result);
$con_roblem_all_result_rows = mysql_num_rows($con_roblem_all_result);
?>


<table cellSpacing="1" cellPadding="1" border="0" width="700" >
	<tr align="middle">
		<td class="style24" style="height: 16px" colspan="4">
		<strong>CONTACT PROBLEMS <a href="http://www.usedcardboardboxes.com/ucbdb/contact_status_report_employee.php?ty_id=<? echo $_COOKIE['userinitials'] ?>"><?php echo $con_roblem_you_result_rows; ?></a></strong></td>
	</tr>


<?php



$contact_it = "SELECT * FROM ucb_contact WHERE EMPLOYEE LIKE '" . $initials . "' AND status = 'Attention' ORDER BY added_on DESC";
$ship_it_result = mysql_query($contact_it, db());
$ship_it_result_rows = mysql_num_rows($ship_it_result);
?>


	<tr>
		<td  class="style7">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		TYPE</font></td>
<td class="style7">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		COMPANY</font></td>
		<td class="style5" >
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		PHONE</td>
		<td class="style5" >
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		DATE</td>

	</tr>
<?php 
while ($report_data = mysql_fetch_array($ship_it_result)) {
?>
	<tr vAlign="center">
		<td bgColor="#e4e4e4" class="style3" >		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<a href="http://www.usedcardboardboxes.com/ucbdb/contact_status_drill.php?id=<?php echo $report_data["id"]; ?>&proc=View&"><?php echo $report_data["first_name"]; ?> <?php echo $report_data["last_name"]; ?></a></td>
		<td bgColor="#e4e4e4" class="style4">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php echo $report_data["company"]; ?> </font></td>
		<td bgColor="#e4e4e4" class="style4">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php echo $report_data["phone1"]; ?> </font></td>

		<td bgColor="#e4e4e4" style="width: 20%; ">
		
<? 
$dp = $report_data["added_on"];
$order_date = date("F j Y H:i", strtotime($dp)); 
?>


		
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<?php echo $order_date; ?> </font></td>		
	</tr>
<?php } ?>
</table>
<?
}

function FixFilename($strtofix)

{ //THIS FUNCTION ESCAPES SPECIAL CHARACTERS FOR INSERTING INTO SQL


	$strtofix = ereg_replace(  "<", "_", $strtofix );

	$strtofix = ereg_replace(  "'", "_", $strtofix );	

	$strtofix = ereg_replace(  "#", "_", $strtofix );
	
	$strtofix = ereg_replace(  " ", "_", $strtofix );

	$strtofix = ereg_replace(  "(\n)", "<BR>", $strtofix );

	return $strtofix;

}

function get_initials_from_id($id){
/////////////////////////////////////////// GET INITIALS FROM ID

$dt_so = "SELECT * FROM loop_employees WHERE id = " . $id;
$dt_res_so = mysql_query($dt_so,db() );

while ($so_row = mysql_fetch_array($dt_res_so)) {
return $so_row["initials"];
}
}

function getinventory($id){
/////////////////////////////////////////// NEW INVENTORY SALES ORDER VALUES

	if ($id != "") {

		$dt_so_item = "SELECT *, loop_salesorders.location_warehouse_id AS wid FROM loop_salesorders JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id WHERE loop_transaction_buyer.bol_create =0 AND box_id =" . $id ;
		$dt_res_so_item = mysql_query($dt_so_item,db() );

			while ($so_item_row = mysql_fetch_array($dt_res_so_item)) {

				$inv_array[$so_item_row["wid"]][$so_item_row["box_id"]] += $so_item_row["qty"];

				
			}

		//print_r($so_item_row);
		$res = "";
		$dt_view_qry = "SELECT loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id WHERE loop_boxes.id = " . $id . " GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth ";
		//echo $dt_view_qry;
		$dt_view_res = mysql_query($dt_view_qry,db() );
		//echo $st_view_qry;
		 $num_rows = mysql_num_rows($dt_view_res);
		 if ($num_rows > 0) 
			$res .= "<b>UCB Inventory</b><br>Actual&nbsp;After PO<br>";
		
		while ($dt_view_row = mysql_fetch_array($dt_view_res)) {

			if ($dt_view_row["A"] != 0)
			{


				if ($dt_view_row["ISBOX"] != 'Y')	
				{
					if ($dt_view_row["B"] != 'Virtual Inventory') 
					{
						$res .= $dt_view_row["A"] . " - ";
						$res .= $dt_view_row["A"] - $inv_array[$dt_view_row["wid"]][$dt_view_row["I"]]. " &nbsp; ";
						$res .= $dt_view_row["B"] . " ";	  
						$res .= $dt_view_row["TYPE"] . " ";	  
						$res .= " <BR>";
					}
			} else {
					if ($dt_view_row["B"] != 'Virtual Inventory') 
					{
						$res .= $dt_view_row["A"] . " &nbsp; ";
						$res .= $dt_view_row["A"] - $inv_array[$dt_view_row["wid"]][$dt_view_row["I"]] . " ";
						$res .= $dt_view_row["B"] . " ";	  
						$res .= " <BR>";
					}	
				}

				}
			} 
			
		
		$dt_view_qry = "SELECT *, inventory.notes AS N, inventory.date AS DT FROM inventory WHERE inventory.gaylord=1 AND inventory.id = " . get_b2b_box_id($id) . " ORDER BY inventory.availability DESC";
		$dt_view_res = mysql_query($dt_view_qry,db_b2b() );
		//$res .= $dt_view_qry;
		while ($inv = mysql_fetch_array($dt_view_res)) {

			if ($inv["active"] == "A") {
			$res .= "<b>Virtual Inventory: </b><br>";
			if ($inv["availability"] == "0" ) $res .= "Not Available";
			if ($inv["availability"] == "1" ) $res .= "Available Soon";
			if ($inv["availability"] == "2" ) $res .= "Available Now";
			if ($inv["availability"] == "3" )  $res .= "Available & Urgent";
			if ($inv["availability"] == "-4" ) $res .= "Inactive";
			if ($inv["availability"] == "-1" ) $res .= "Presell Available";
			if ($inv["availability"] == "-2" ) $res .= "Active but Unavailable";
			if ($inv["availability"] == "-3" )  $res .= "Potential";
			$res .= "<br> ";
			$res .= $inv["DT"] . "<br> ";
			$res .= $inv["N"];
			} 
		}
		return $res;
	}else { 	  
	
		return "";
	}

}

function get_loop_box_id($b2b_id){
/////////////////////////////////////////// GET INITIALS FROM ID

$dt_so = "SELECT * FROM loop_boxes WHERE b2b_id = " . $b2b_id;
$dt_res_so = mysql_query($dt_so,db() );

while ($so_row = mysql_fetch_array($dt_res_so)) {
return $so_row["id"];
}
}

function get_b2b_box_id($loop_id){
/////////////////////////////////////////// GET INITIALS FROM ID

$dt_so = "SELECT * FROM loop_boxes WHERE id = " . $loop_id;
$dt_res_so = mysql_query($dt_so,db() );

while ($so_row = mysql_fetch_array($dt_res_so)) {
return $so_row["b2b_id"];
}
}
?>