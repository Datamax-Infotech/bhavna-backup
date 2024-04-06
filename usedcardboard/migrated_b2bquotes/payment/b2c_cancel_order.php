<?php 
if(session_id() == ''){
    session_start();
}
	require ("../inc/header_session.php");
	require ("../mainfunctions/database.php");
	require ("../mainfunctions/general-functions.php");
	require ("../../../securedata/main-enc-class.php");

	db();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<script src="scripts/jquery-2.1.4.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
	<script src="scripts/jquery.cookie.js"></script>
	
	<script src="https://sandbox-assets.secure.checkout.visa.com/checkout-widget/resources/js/integration/v1/sdk.js"></script>
	<script src="https://includestest.ccdc02.com/cardinalcruise/v1/songbird.js"></script>
	<script src="https://jstest.authorize.net/v1/Accept.js"></script>
	<script src="https://jstest.authorize.net/v3/acceptUI.js"></script>
		
	<link rel="stylesheet" type="text/css" href="../stylesheet.css">

<title>B2C Void Transaction</title>	

</head>

<body >

<div align="center">
	
	<?php 
		/*db_b2b();
		
		$redirect_pg = " selected ";
		$sql = "Select company, loopid from companyInfo Where ID = " . $_REQUEST["selected_id"];
		$dt_view_res = db_query($sql);
		while ($row = array_shift($dt_view_res)) 
		{
			$company_nm = $row["company"];
			$warehouse_id = $row["loopid"];
		}
		*/
	?>
	  
<?php 
		$auth_trans_id = $_REQUEST["auth_trans_id"];
		
		$post_url = "https://secure.authorize.net/gateway/transact.dll";
		$post_values = array();
		$post_values = array(
			// the API Login ID and Transaction Key must be replaced with valid values
			//"x_login"			=> "6Dz9Bf6Fm",
			//"x_tran_key"		=> "64W8Z4U3j6xkz98r",
			
			"x_login"			=> "4Rw6UcB57",
			"x_tran_key"		=> "8c6KJ9862eJs2jBx",

			"x_version"			=> "3.1",
			"x_delim_data"		=> "TRUE",
			"x_delim_char"		=> "|",
			"x_relay_response"	=> "FALSE",

			"x_type"			=> "Void",
			"x_trans_id"		=> $auth_trans_id
			// Additional fields can be added here as outlined in the AIM integration
			// guide at: http://developer.authorize.net
		);
	
		// This section takes the input fields and converts them to the proper format
		// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
		$post_string = "";
		foreach( $post_values as $key => $value )
			{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
		$post_string = rtrim( $post_string, "& " );

		$request = curl_init($post_url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$post_response = curl_exec($request); // execute curl post and store results in $post_response

		curl_close ($request); // close curl object

		// This line takes the response and breaks it into an array using the specified delimiting character
		$response_array = explode($post_values["x_delim_char"],$post_response);

		//var_dump($post_response);

		// The results are output to the screen in the form of an html numbered list.
		echo "<div id='result_div'>";
		if ($response_array[0] == 1) {
			$payment_status = "approved";
			echo "<font size='6'>Transaction has been voided.</font><br>Following is the response from Authorize net:<br>" . $response_array[3] . "<br>Transaction ID: " . $response_array[6]; 
		}else{
			$payment_status = "declined";
			echo "<font color='red' size='6'>Transaction not voided.</font><br><br>Following is the response from Authorize net:<br>" . $response_array[3] . "<br>"; 
		}
		echo "</div>";
		
	?>

</div>

</html>
