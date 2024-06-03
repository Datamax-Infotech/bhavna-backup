<? 
require ("inc/header_session.php");
?>

<?
require ("inc/databaseb2b.php");


function add_date($givendate,$day) {
      $cd = strtotime($givendate);
      $newdate = date('Y-m-d', mktime(date('m',$cd), date('d',$cd)+$day, date('Y',$cd)));
      return $newdate;
}

?>
<html>
<head>
<title>Manage Companies</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript">
function show_ot(val)
{
	if(val == "Other")
	{
		document.getElementById('ot').style.display = 'block';
	}
	else
	{
		document.getElementById('ot').style.display = 'none';
	}
}
</script>
</head>

<body bgcolor="#FFFFFF" text="#333333" link="#333333" vlink="#666666" alink="#333333">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr valign="top">
		<td align="left" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td background="images/boxbackleft.jpg" width="1">&nbsp;</td>
				<td background="images/boxbackground.jpg" align="center" valign="middle"><font face="Arial, Helvetica, sans-serif" size="1" color="#1E5B96"><a href="home.asp">HOME</a> - </font><font face="Arial, Helvetica, sans-serif" size="1" color="1E5B96">PIPELINE </font> | <a href="logout.asp"><font face="Arial, Helvetica, sans-serif" size="1" color="1E5B96">LOGOUT</font></a></td>
				<td width="1"><img src="images/boxclosedbarright.jpg" width="2" height="21"></td>
			</tr>
		</table></td>
	</tr>
</table><br>
<?
$dt_view_qry = "SELECT * FROM status";
$dt_view_res = mysql_query($dt_view_qry,db_b2b() );
while ($row = mysql_fetch_array($dt_view_res)) {
?>
<table width="98%" border="0" cellspacing="1" cellpadding="1">
<tr align="center">
<td colspan="12" bgcolor="#FFCCCC"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><?=$row["name"];?></b></font></td>
</tr>
<tr>
<td width="5%" valign="middle" align="center" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"></font></td>
<td width="8%" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="managecompanies.asp?sk=dt&so=<?=$so?>">DATE</a><img src="images/arrowdown.jpg" width="9" height="8"> </font></td>
<td width="8%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="managecompanies.asp?sk=age&so=<?=$so?>">AGE</a></font></td>
<td width="10%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="managecompanies.asp?sk=contact&so=<?=$so?>">CONTACT</a></font></td>
<td width="21%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="managecompanies.asp?sk=cname&so=<?=$so?>">COMPANY NAME</a></font></td>
<td width="3%" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="managecompanies.asp?sk=city&so=<?=$so?>">CITY</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="managecompanies.asp?sk=state&so=<?=$so?>">STATE</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="managecompanies.asp?sk=zip&so=<?=$so?>">ZIP</a></font></td>
<td width="5%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="managecompanies.asp?sk=qty&so=<?=$so?>">QUANTITY</a></font></td>
<td width="15%" bgcolor="#D9F2FF" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">BOX DIM</font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="managecompanies.asp?sk=lc&so=<?=$so?>">Next Step</a></font></td>
<td bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="managecompanies.asp?sk=lc&so=<?=$so?>">Last Communication</a></font></td>
</tr>
<?


$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI,  boxesrequested.LengthInch AS LI,  boxesrequested.WidthInch AS WI,  boxesrequested.DepthInch AS DI,  boxesrequested.cubicFeet AS CF,   boxesrequested.qty_for_box AS A from companyInfo INNER JOIN boxesrequested ON companyInfo.id = boxesrequested.CompanyID Where companyInfo.status =" . $row["id"];

if ($_Request["gc"] == 1) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Need Boxes'";
}
if ($_Request["gc"] == 2) {
	$x = $x . " AND companyInfo.haveNeed LIKE 'Have Boxes'";
}

//if (keyword <> "" {
//	x = x & " AND ( "
//	For i=1 to uBound(arrFields)
//		if (i=1 {
//		ELSE
//			x = x & " OR "
//		END IF
//		x = x & " " & arrFields(i) & " LIKE '%" & keyword & "%'"
//	Next
//	x = x & " )"
//END IF


if ($_Request["so"] != "") {
	if ($_Request["so"] == "A") {
		$sord = " ASC";
	} Else {
		$sord = " DESC";
	}
} ELSE {
	$sord = " DESC";
}

if ($_Request["sk"] != "" )
{
	if ($_Request["sk"] == "dt") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_Request["sk"] == "age") {
		$skey = " ORDER BY companyInfo.dateCreated";
	} elseif ($_Request["sk"] == "contact") {
		$skey = " ORDER BY companyInfo.contact";
	} elseif ($_Request["sk"] == "cname") {
		$skey = " ORDER BY companyInfo.company";
	} elseif ($_Request["sk"] == "city") {
		$skey = " ORDER BY companyInfo.city";
	} elseif ($_Request["sk"] == "state") {
		$skey = " ORDER BY companyInfo.state";
	} elseif ($_Request["sk"] == "zip") {
		$skey = " ORDER BY companyInfo.zip";
	} elseif ($_Request["sk"] == "qty") {
		$skey = " ORDER BY companyInfo.company";
	} elseif ($_Request["sk"] == "lc") {
		$skey = " ORDER BY companyInfo.company";
	}
}
Else
{
	$skey = " ORDER BY dateCreated";
}
$x = $x . " GROUP BY companyInfo.id " . $skey . $sord;

echo $x;
$data_res = mysql_query($x,db_b2b() );
while ($data = mysql_fetch_array($data_res)) {

?>
<tr valign="middle">
<td width="5%" valign="middle" align="center" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
<a href="viewcompany.php?ID=<?=$data["I"]?>"?>"><img src="images/magglass.jpg" align="absmiddle" border="0"><?$x;?></a></font></td>
<?//  $strng = "1/1/2000 + " . $data["D"] . " - " . $data["DL"]; 
?>
<td width="8%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"></font></td>
<td width="8%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=add_date('2000-1-1',($data["DI"]-36526));
?> Days</font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["C"]?></font></td>
<td width="21%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["CO"]?></font></td>
<td width="3%" bgcolor="#E4E4E4"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["PH"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["CI"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["ST"]?></font></td>
<td width="10%" bgcolor="#E4E4E4" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["ZI"]?></font></td>
<td width="5%" bgcolor="#E4E4E4" align="left"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["A"]?>
<? if ($data["LI"] > 0 ) { ?>
<td width="15%" bgcolor="#E4E4E4" align="left"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?=$data["LI"]?> x <?=$data["WI"]?> x <?=$data["DI"]?></font></td>
<? } else { ?>
<td width="15%" bgcolor="#E4E4E4" align="left"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">&nbsp;</font></td>
<? } ?>



</tr>

<?
}
echo "</table><p></p>";
}
?>
</body>
</html>
