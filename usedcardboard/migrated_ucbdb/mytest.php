<?php 
if(count($_POST))
{
	foreach($process as $process_id)
	{
		echo "Order ID :".${"order_id_".$process_id}.'<br>';
		echo "Amount :".${"amount_".$process_id}.'<br>';
		echo "Process ID :".$process_id.'<br>';
		echo "<hr width='100%'>";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Test</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
 
<body>
<form method="POST">
<table width="600" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>Order Number </td>
    <td>Item</td>
    <td>Amount</td>
    <td>Checked</td>
  </tr>
  <tr>
	<input type="hidden" name="order_id_1" value="12345">
    <td>12345</td>
    <td>Shirt</td>
    <td><input name="amount_1" type="text"  value="10" size="10" maxlength="10"></td>
    <td><input name="process[]" type="checkbox"  value="1"></td>
  </tr>
  <tr>
	<input type="hidden" name="order_id_2" value="12345">
    <td>12345</td>
    <td>Sweater</td>
    <td><input name="amount_2" type="text"  value="15" size="10" maxlength="10"></td>
    <td><input name="process[]" type="checkbox"  value="2"></td>
  </tr>
  <tr>
   	<input type="hidden" name="order_id_3" value="15558">
    <td>15558</td>
    <td>Pants</td>
    <td><input name="amount_3" type="text"  value="20" size="10" maxlength="10"></td>
    <td><input name="process[]" type="checkbox"  value="3"></td>
  </tr>
  <tr>
	<input type="hidden" name="order_id_4" value="15559">
    <td>15559</td>
    <td>Shirt</td>
    <td><input name="amount_4" type="text"  value="10" size="10" maxlength="10"></td>
    <td><input name="process[]" type="checkbox"  value="4"></td>
  </tr>
</table>
<p>
  <input type="submit" name="Submit" value="Submit">
</p>
</form>
</body>
</html>
 
