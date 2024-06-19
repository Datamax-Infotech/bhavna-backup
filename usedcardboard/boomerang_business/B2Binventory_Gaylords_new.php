<?
/*
File Name: inventory_v1_new.php
Page created By: Ashiq
Page created On: 15-02-2024 (Dublicated From Product Page)
Last Modified On: 
Last Modified By: Ashiq
Change History:
Date           By            Description
===============================================================================================================
15-02-2024      Ashiq     This file is created for the inventory Supplier product view.
							  
							
===============================================================================================================
*/
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");
$shown_in_client_flg = 0; $client_companyid = 0; 
$user_id = isset($_REQUEST['loginid']) && $_REQUEST['loginid']!="" ? $_REQUEST['loginid'] : "";

if ($_REQUEST["shown_in_client_flg"] == 1)
{
	$shown_in_client_flg = 1;
	$shown_in_client_flg_width = "";
	$client_companyid = $_REQUEST["client_companyid"];
	
	db_b2b();
	$sql = "SELECT shipAddress, shipAddress2, shipCity, shipState, shipZip FROM companyInfo WHERE ID = '" . $client_companyid . "'";
	$shipAddress = $shipAddress2 = $shipCity = $shipState = $shipZip = "";
	$result = db_query($sql, array("i"), array($client_companyid));
	while ($res_data = array_shift($result)) {
		$shipAddress  = $res_data["shipAddress"];
		$shipAddress2 = $res_data["shipAddress2"];
		$shipCity     = $res_data["shipCity"];
		$shipState    = $res_data["shipState"];
		$shipZip      = $res_data["shipZip"];
	}
	db();
}else{
	require ("inc/header_session.php");
	$shown_in_client_flg_width = "width:75%;";
}
?>
<html>
<head>
	<title>B2B Inventory: Used Gaylord Totes</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="tagfiles/solnew.css">
    <link rel='stylesheet' type='text/css' href='css/bomerang_style.css'>
	
	
    <script>
		function display_preoder_sel(tmpcnt, reccnt, box_id, wid) {
			if (reccnt > 0 ) {
			
				//if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') 
				//{ document.getElementById('inventory_preord_top_' + tmpcnt).style.display='none'; } else {
				//document.getElementById('inventory_preord_top_' + tmpcnt).style.display='table-row'; }
			
				if (document.getElementById('inventory_preord_middle_div_' + tmpcnt).style.display == 'inline') 
				{ document.getElementById('inventory_preord_middle_div_' + tmpcnt).style.display='none'; } else {
					document.getElementById('inventory_preord_middle_div_' + tmpcnt).style.display='inline'; }

				document.getElementById("inventory_preord_middle_div_"+tmpcnt).innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 				
			
				if (window.XMLHttpRequest)
				{
				xmlhttp=new XMLHttpRequest();
				}
				else
				{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
					document.getElementById("inventory_preord_middle_div_"+tmpcnt).innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET","inventory_preorder_childtable.php?box_id=" +box_id+"&wid="+wid,true);
				xmlhttp.send();
			}
		}
	
		function change_addressbook() {
			//echo "<option value='".$user_address['id'] . "|" . str_replace("'", "\'" ,$user_address['addressline1']) . "|" . str_replace("'", "\'" ,$user_address['addressline2']) . "|" . $user_address['city'] . "|" . $user_address['state'] . "|" . $user_address['zip'] ."'>".$address."</option>";
			if (document.getElementById("address_book").value != "")
			{
				let address_book_str = document.getElementById("address_book").value;
				let address_book_arr = address_book_str.split("|");
				
				document.getElementById("txtaddress").value = address_book_arr[1];
				document.getElementById("txtaddress2").value = address_book_arr[2];
				document.getElementById("txtcity").value = address_book_arr[3];
				document.getElementById("txtstate").value = address_book_arr[4];
				document.getElementById("txtzipcode").value = address_book_arr[5];
				
				show_inventories('', 1, 1); 
				functionLoaded = false; 
				show_inventories('2');
			}
		}
	
	</script>
</head>

<?
if ($shown_in_client_flg == 0){
	include("inc/header.php");
?>	
	<br>
	<br>
	<script type="text/javascript" src="wz_tooltip.js"></script>
<?}

db();
?>

	<style>
		body
		{
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
			font-size: 13px;
		}
		.fav-added{
			color: #5CB726;
		}
		.fav-removed{
			color: #FFF;
		}
	</style>


<div class="clearfix"></div>

<div id="loader" class="overlay d-none" >
	<div class="d-flex justify-content-center">
		<div class="spinner-border" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
</div>                    

<div class="main-section mb-5 pb-5">
	<div class="container-fluid">
        <div class="dashboard_heading" style="font-size: 24px;font-family: 'Titillium Web', sans-serif;font-weight: 600;padding: 1% 0;">B2B Inventory: Used Gaylord Totes</div>
		
		<div class="products_div_main_urgent">
			<div class="result_products_urgent" id="result_products_urgent">
			</div>
			<br>
		</div>
		
        <div class="row">
            <div class="col-md-2 inventory-filter">
                <div class="col-md-12 filter-box1">
                    <div class="row">
                        <div class="col-md-12 p-0" id="your-selections">
                        <p><b>YOUR SELECTIONS</b></p>
                        <div id="selections"></div>
                        <a class="map_link float-right" href="#" id="clear_all">Clear All</a>
                        </div>
                        <form id="filter_form" class="w-100">	
						<?php if(isset($_REQUEST['loginid']) && $_REQUEST['loginid'] != ""){ 
							db();
							$user_address_qry = db_query("SELECT * FROM boomerang_user_addresses WHERE user_id = '".$user_id."' ORDER BY mark_default DESC");
							$no_of_add = tep_db_num_rows($user_address_qry);
							if($no_of_add > 0){
							?>
							<input type="hidden" name="loginid" value="<?php echo $_REQUEST['loginid']; ?>"/>
							<div class="form-group">
								<label class="font-weight-bold">Address Book</label>
								<select name="address_book" id="address_book" onchange="change_addressbook()">
									<?php
										while($user_address = array_shift($user_address_qry)){
											if ($no_of_add == 1 || $user_address['mark_default'] == 1){
												$shipAddress = $user_address['addressline1'];
												$shipAddress2 = $user_address['addressline2'];
												$shipCity = $user_address['city'];
												$shipState = $user_address['state'];
												$shipZip = $user_address['zip'];
											}
											$address = $user_address['addressline1'].", ".$user_address['city'].", ".$user_address['state'].", ".$user_address['zip'];
											$address = substr($address, 0, 60);
											$address = $address."...";
											echo "<option value='".$user_address['id'] . "|" . str_replace("'", "\'" ,$user_address['addressline1']) . "|" . str_replace("'", "\'" ,$user_address['addressline2']) . "|" . $user_address['city'] . "|" . $user_address['state'] . "|" . $user_address['zip'] ."'>".$address."</option>";
										}
									?>
								</select>
							</div>
						<?php }
						} ?>
						<div class="form-group">
                            <p id="enter_address_text" class="text-primary text-center m-0 flex-grow-1 ml-3" style="cursor:pointer"><u>Enter Full Adddress</u></p>

                            <div id="full_address_div" class="d-none">
                                <label class="font-weight-bold">Address</label>
                                <input type="text" class="w-100" name="txtaddress" id="txtaddress" value="<? echo $shipAddress;?>"/>
                                <label class="font-weight-bold">Address 2</label>
                                <input type="text" class="w-100" name="txtaddress2" id="txtaddress2" value="<? echo $shipAddress2;?>"/>
                                <label class="font-weight-bold">City</label>
                                <input type="text" class="w-100" name="txtcity" id="txtcity" value="<? echo $shipCity;?>"/>
                                <label class="font-weight-bold">State</label>
                                <select class="w-100" name="txtstate" id="txtstate">
                                    <option value="">Select State</option>
                                    <?
                                    $tableedit  = "SELECT * FROM zones where zone_country_id in (223,38,37) ORDER BY zone_country_id desc, zone_name";
                                    $dt_view_res = db_query($tableedit,db_b2b() );
                                    while ($row = array_shift($dt_view_res)) {
                                    ?>
                                    <option 
                                    <? 
                                    if ((trim($_REQUEST["txtstate"]) == trim($row["zone_code"])) || (trim($shipState) == trim($row["zone_code"])) || (trim($_REQUEST["txtstate"]) == trim($row["zone_name"])) || (trim($shipState) == trim($row["zone_name"])))
									echo " selected ";
                                    ?> value="<?=trim($row["zone_code"])?>" display_val="<?=trim($row["zone_code"])?>">
                                    <?=$row["zone_name"]?>
                                    (<?=$row["zone_code"]?>)
                                    </option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </div>

                            <label class="d-flex font-weight-bold">Zip Code</label>
							
                            <input type="text" class="w-100" name="txtzipcode" id="txtzipcode" value="<? echo $shipZip;?>" />
                            <div class="text-center">
                                <button type="button" class="btn apply-filter btn-sm" id="clear-deliverydata" onclick="clear_filter('deliverydata')">Clear Zipcode</button>
                                <button type="button" class="btn apply-filter btn-sm" id="apply-deliverydata" onclick="show_inventories('', 1, 1); functionLoaded = false; show_inventories('2');"> Apply Zipcode</button>
                            </div>
                        </div>				
                        <div class="form-group">
                            <label class="font-weight-bold">Warehouse</label><br>
                            <select name="warehouse" id="warehouse" onchange="change_warehouse()">
                                <option value="all" display_val="All" selected>All</option>
                                <?
								if ($shown_in_client_flg == 1){
									echo '<option value="238" display_val="Direct Ship">Direct Ship</option>';

									$warehouse_get_query = db_query("SELECT id, warehouse_name, warehouse_city, warehouse_state FROM loop_warehouse WHERE rec_type = 'Sorting' AND Active = 1 and id <> 238 ORDER BY warehouse_city", db());
									while($warehouse = array_shift($warehouse_get_query))
									{
										$name = $warehouse['warehouse_city'] . ", " . $warehouse['warehouse_state'];
										$id = $warehouse['id'];
									?>
										<option value="<?=$id?>" display_val="<?= $name ?>"><?= $name ?></option>
									<?	} 
								}else {
									$warehouse_get_query = db_query("SELECT id, warehouse_name, warehouse_city, warehouse_state FROM loop_warehouse WHERE rec_type = 'Sorting' AND Active = 1 ORDER BY warehouse_name", db());
									while($warehouse = array_shift($warehouse_get_query))
									{
										$name = $warehouse['warehouse_name'];
										$id = $warehouse['id'];
									?>
										<option value="<?=$id?>" display_val="<?= $name ?>"><?= $name ?></option>
									<?	} 
								}									
								?>
                            </select>
                        </div>

						<? if ($shown_in_client_flg == 1){ ?>
						<? } else { ?>

							<div class="form-group">
								<label class="font-weight-bold">Timing</label><br>
								<select name="timing" id="timing" onchange="change_timing()">
									<option value="4" display_val="Ready Now" >Ready Now</option>
									<option value="5" display_val="Can ship in 2 weeks">Can ship in 2 weeks</option>
									<option value="10" display_val="Can ship in 4 weeks">Can ship in 4 weeks</option>
									<option value="7" display_val="Can ship this month">Can ship this month</option>
									<option value="6" display_val="Ready to ship whenever" selected>Ready to ship whenever</option>
									<option value="9" display_val="Enter ship by date">Enter ship by date</option>
								</select>
								<div class="mt-2 d-none" id="timing_date_div">
								<input type="date" class="form-control  form-control-sm" name="timing_date" id="timing_date"/>
								<p id="timing_date_error" class="text-danger d-none"><small>Please Select Date</small></p>
								<div class="text-center">
								<button type="button" class="btn apply-filter btn-sm" id="apply-timimg" onclick="show_inventories('',1)"> Load</button>
								</div>
								</div>
							</div>
						<? } ?>

						<? if ($shown_in_client_flg == 0){ ?>
							<div class="form-group ">
								<label class="font-weight-bold">Gaylord Type</label><br>
								<input class="gaylord_box_nonucb" id="gaylord_box_nonucb" type="checkbox" display_val="Gaylord (UCB)" value="GaylordUCB" name="include_presold_and_loops[]" onchange="show_inventories('',1)" checked /> UCB </br>
								
								<input class="gaylord_box_ucb" id="gaylord_box_ucb" type="checkbox" display_val="Gaylord (Non-UCB)" value="Gaylord" name="include_presold_and_loops[]" onchange="show_inventories('',1)" checked /> Non-UCB </br>
								
								<input class="gaylord_box_presold" id="gaylord_box_presold" type="checkbox" display_val="Gaylord (Presold)" value="PresoldGaylord" name="include_presold_and_loops[]" onchange="show_inventories('',1)"/> Presold </br>
								
								<input class="gaylord_box_loop" id="gaylord_box_loop" type="checkbox" display_val="Gaylord (Loop)" value="Loop" name="include_presold_and_loops[]" onchange="show_inventories('',1)"/> Loops </br>
							</div>
						<? } ?>

						<? if ($shown_in_client_flg == 0){ ?>
							<div class="form-group">
								<label class="font-weight-bold">Status</label><br>
								<input class="active_available" type="checkbox" display_val="Active (Available)" value="1" name="status[]" id="active_available" onchange="show_inventories('',1)" checked /> Active (Available) </br>
								<input class="potential_to_get" type="checkbox" display_val="Potential to Get" value="2" name="status[]" id="potential_to_get" onchange="show_inventories('',1)"/> Potential to Get </br>
								<input class="inactive_unavailable" type="checkbox" display_val="Inactive (Unavailable, Can't sell)" value="3" name="status[]" id="inactive_unavailable" onchange="show_inventories('',1)"/> Inactive (Unavailable, Can't sell) 
							</div>
						<? } ?>

						<? if ($shown_in_client_flg == 1){ ?>
							<div class="form-group">
								<input class="include_FTL_Rdy_Now_Only" type="checkbox" display_val="FTL Rdy Now Only" value="1" name="include_FTL_Rdy_Now_Only" id="include_FTL_Rdy_Now_Only" onchange="show_inventories('',1)"/> Full Truckloads Only
							</div>
							<div class="form-group">
								<input class="ltl_allowed" type="checkbox" display_val="LTL Allowed" value="1" name="ltl_allowed" id="ltl_allowed" onchange="show_inventories('',1)"/> LTL Allowed
							</div>
							<div class="form-group">
								<input class="customer_pickup_allowed" type="checkbox" display_val="Customer Pickup Allowed" value="1" name="customer_pickup_allowed" id="customer_pickup_allowed" onchange="show_inventories('',1)"/> Customer Pickup Allowed
							</div>
						<? } else { ?>
							<div class="form-group">
								<input class="include_FTL_Rdy_Now_Only" type="checkbox" display_val="FTL Rdy Now Only" value="1" name="include_FTL_Rdy_Now_Only" id="include_FTL_Rdy_Now_Only" onchange="show_inventories('',1)"/> FTL Rdy Now Only
							</div>
							<div class="form-group">
								<input class="include_sold_out_items" type="checkbox" display_val="Include Sold Out Items" value="1" name="include_sold_out_items" id="include_sold_out_items" onchange="show_inventories('',1)"/> Include Sold Out Items
							</div>

							<div class="form-group">
								<input class="ltl_allowed" type="checkbox" display_val="LTL Allowed" value="1" name="ltl_allowed" id="ltl_allowed" onchange="show_inventories('',1)"/> LTL Allowed?
							</div>
							<div class="form-group">
								<input class="customer_pickup_allowed" type="checkbox" display_val="Customer Pickup Allowed" value="1" name="customer_pickup_allowed" id="customer_pickup_allowed" onchange="show_inventories('',1)"/> Customer Pickup Allowed?
							</div>
							<div class="form-group">
								<input class="urgent_clearance" type="checkbox" display_val="Urgent/Clearance" value="1" name="urgent_clearance" id="urgent_clearance" onchange="show_inventories('',1)"/> Urgent/Clearance
							</div>
						<? }  ?>

                       

                    </div>
                </div>
                <div class="col-md-12 filter-box2">
                    <div class="row flex-column">
                        <?php
                            $box_type=isset($_REQUEST['box_type']) && $_REQUEST['box_type']!="" ? $_REQUEST['box_type'] : "Gaylord";
                            $box_subtype=isset($_REQUEST['box_subtype']) && $_REQUEST['box_subtype']!="" ? $_REQUEST['box_subtype'] : "all";
                        ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Price (ea)</label>
                            <table class="form-table">
                                <tr>
                                    <td>
                                        <div class="input-group-addon">
                                            <span>$</span>
                                            <input type="text" name="min_price_each"  class="w-100" value="0.00" id="price_each-from" onchange="(function(el){el.value=parseFloat(el.value).toFixed(2);})(this); show_inventories('',1)"/>
                                        </div>
                                    </td>
                                    <td>&nbsp;-&nbsp;</td>
                                    <td>
                                        <div class="input-group-addon">
                                            <span>$</span>
                                            <input type="text" name="max_price_each"  class="w-100" value="99.99" id="price_each-to" onchange="(function(el){el.value=parseFloat(el.value).toFixed(2);})(this); show_inventories('',1)" />
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Height (in)</label>
                            <table class="form-table">
                                <tr>
                                    <td><input name="min_height" type="number" class="w-100" value="0" id="height-from" onchange="show_inventories('',1)"/></td>
                                    <td>&nbsp;-&nbsp;</td>
                                    <td><input name="max_height" type="number" class="w-100" value="99" id="height-to" onchange="show_inventories('',1)"/></td>
                                </tr>
                            </table>
                            <!--<div class="row">
                                <div class="col-sm-5 form-group">
                                    <input name="min_height" type="number" class="w-100" value="0" min="0" max="99" id="height-from" onchange="show_inventories('',1)"/>
                                </div>
                                <div class="col-sm-2">
                                    -
                                </div>
                                <div class="col-sm-5 form-group">
                                    <input name="max_height" type="number" class="w-100" value="99" min="0" max="99" id="height-to" onchange="show_inventories('',1)"/>
                                </div>
                            </div>-->
                        </div>

						<? if ($shown_in_client_flg == 0){ ?>
                            <div class="form-group p-0">
                                <label class="font-weight-bold">Sub Type</label><br>
                                <div class="d-block">
                                    <input class="hpt-41" type="checkbox" display_val="HPT-41" value="1" name="type[]" id="hpt-41" onchange="show_inventories('',1)"/> HPT-41
                                </div>
                                <div class="d-block">
                                    <input class="resin" type="checkbox" display_val="Resin" value="2" name="type[]" id="resin" onchange="show_inventories('',1)"/> Resin
                                </div>
                                <div class="d-block">
                                    <input class="produce" type="checkbox" display_val="Produce" value="3" name="type[]" id="produce" onchange="show_inventories('',1)"/> Produce
                                </div>
                                <div class="d-block">
                                    <input class="melon_short" type="checkbox" display_val="Melon/Short" value="4" name="type[]" id="melon_short" onchange="show_inventories('',1)"/> Melon/Short
                                </div>
                            </div>
						<? } ?>		


                            <div class="form-group p-0">
                                <label class="font-weight-bold">Uniformity</label><br>
                                <div class="d-block">
                                    <input class="uniform" type="checkbox" display_val="Uniform" value="1" name="uniformity[]" id="uniform" onchange="show_inventories('',1)"/> Uniform
                                </div>
                                <div class="d-block">
                                    <input class="mixed" type="checkbox" display_val="Mixed" value="2" name="uniformity[]" id="mixed" onchange="show_inventories('',1)"/> Mixed
                                </div>
                            </div>

                            <div class="form-group p-0">
                                <label class="font-weight-bold">Shape</label><br>
                                <div class="d-block">
                                    <input class="rectangular" type="checkbox" display_val="Rectangular" value="1" name="shape[]" id="rectangular" onchange="show_inventories('',1)"/> Rectangular
                                </div>
                                <div class="d-block">
                                    <input class="octagonal" type="checkbox" display_val="Octagonal" value="2" name="shape[]" id="octagonal" onchange="show_inventories('',1)"/> Octagonal
                                </div>
                            </div>
                            
                            <div class="form-group p-0">
                                <label class="font-weight-bold">Strength</label><br>
                                <div class="d-block">
                                    <input class="one_two_ply" type="checkbox" display_val="1-2ply" value="1" name="wall_thickness[]" id="one_two_ply" onchange="show_inventories('',1)"/> Light Duty: 1-2ply
                                </div>
                                <div class="d-block">
                                    <input class="three_four_ply" type="checkbox" display_val="3-4ply" value="2" name="wall_thickness[]" id="three_four_ply" onchange="show_inventories('',1)"/> Standard Duty: 3-4ply                                
                                </div>
                                <div class="d-block">
                                    <input class="five_plus_ply" type="checkbox" display_val="5ply" value="3" name="wall_thickness[]" id="five_plus_ply" onchange="show_inventories('',1)"/> Heavy Duty: 5ply+
                                </div>
                            </div>

                            <div class="form-group p-0">
                                <label class="font-weight-bold">Top</label><br>
                                <div class="d-block">
                                    <input class="top_full_flaps" type="checkbox" display_val="Full Flaps" value="4" name="top[]" id="top_full_flaps" onchange="show_inventories('',1)"/> Full Flaps
                                </div>
                                <div class="d-block">
                                    <input class="partial_flaps" type="checkbox" display_val="Partial Flaps" value="3" name="top[]" id="partial_flaps" onchange="show_inventories('',1)"/> Partial Flaps                             
                                </div>
                                <div class="d-block">
                                    <input class="removable_lid" type="checkbox" display_val="Removable Lid " value="2" name="top[]" id="removable_lid" onchange="show_inventories('',1)"/> Removable Lid 
                                </div>
                                <div class="d-block">
                                    <input class="no_top" type="checkbox" display_val="No Top" value="1" name="top[]" id="no_top" onchange="show_inventories('',1)"/> No Top 
                                </div>
                            </div>

                            <div class="form-group p-0">
                                <label class="font-weight-bold">Bottom</label><br>
                                <div class="d-block">
                                    <input class="bottom_full_flaps" type="checkbox" display_val="Full Flaps" value="4" name="bottom[]" id="bottom_full_flaps" onchange="show_inventories('',1)"/> Full Flaps
                                </div>
                                <div class="d-block">
                                    <input class="partial_flaps_slipsheet" type="checkbox" display_val="Partial Flaps w/ Slipsheet" value="5" name="bottom[]" id="partial_flaps_slipsheet" onchange="show_inventories('',1)"/> Partial Flaps w/ Slipsheet                             
                                </div>
                                <div class="d-block">
                                    <input class="no_top" type="checkbox" display_val="Partial Flaps w/o Slipsheet" value="2" name="bottom[]" id="no_top" onchange="show_inventories('',1)"/> Partial Flaps w/o Slipsheet 
                                </div>
                                <div class="d-block">
                                    <input class="removable_tray" type="checkbox" display_val="Removable Tray " value="3" name="bottom[]" id="removable_tray" onchange="show_inventories('',1)"/> Removable Tray 
                                </div>
                            </div>

                            <div class="form-group p-0">
                                <label class="font-weight-bold">Vents</label><br>
                                <div class="d-block">
                                    <input class="vents_yes" type="checkbox" display_val="Yes" value="1" name="vents[]" id="vents_yes" onchange="show_inventories('',1)"/> Yes
                                </div>
                                <div class="d-block">
                                    <input class="vents_no" type="checkbox" display_val="No" value="2" name="vents[]" id="vents_no" onchange="show_inventories('',1)"/> No                             
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Grade</label><br>
                                <div class="d-block">
                                    <input class="grade_A" type="checkbox" display_val="A" value="1" name="grade[]" id="grade_A" onchange="show_inventories('',1)"/> A
                                </div>
                                <div class="d-block">
                                    <input class="grade_B" type="checkbox" display_val="B" value="2" name="grade[]" id="grade_B" onchange="show_inventories('',1)"/> B                             
                                </div>
                                <div class="d-block">
                                    <input class="grade_C" type="checkbox" display_val="C" value="3" name="grade[]" id="grade_C" onchange="show_inventories('',1)"/> C                             
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Printing</label><br>
                                <div class="d-block">
                                    <input class="printing" type="checkbox" display_val="Printing" value="1" name="printing[]" id="printing" onchange="show_inventories('',1)"/> Printing
                                </div>
                                <div class="d-block">
                                    <input class="plain" type="checkbox" display_val="Plain" value="2" name="printing[]" id="plain" onchange="show_inventories('',1)"/> Plain                             
                                </div>
                            </div>

						<? if ($shown_in_client_flg == 0){ ?>
                            <div class="form-group">
                                <label class="font-weight-bold">Tags</label><br>
                                
                                <select name="box_tags[]" multiple="multiple" id="box_tags">
                                    <?
                                    $warehouse_get_query = db_query("select * from loop_inv_tags order by id asc", db());
                                    while($warehouse = array_shift($warehouse_get_query))
                                    {
                                        $name = $warehouse['tags'];
                                        $id = $warehouse['id'];
                                    ?>
                                        <option value="<?=$id?>" display_val="<?= $name ?>"><?= $name ?></option>
                                <?	} ?>
                                </select>
                                <button type="button" class="btn apply-filter btn-sm" id="box_tags_apply_filter">Apply tag filter</button>
                            </div>
						<? } ?>
						
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="row m-0 total_value_table">
					<? 
					$style_str = "width:25%";
					if ($shown_in_client_flg == 1){ 
						$style_str = "width:18%";
					}
					?>
                    <table class="table table-sm table-bordered text-center" style="<? echo $style_str;?>">
                        <thead>
                            <tr>
								<? if ($shown_in_client_flg == 1){ ?>
									<th id="heading_total" class="text-center">Qty Avail Total</th>
									<th id="heading_truckload" class="text-center">Qty Avail Truckloads</th>
								<? } else { ?>
									<th id="heading_total_SKU" class="text-center">Total SKUs</th>
									<th id="heading_total" class="text-center">Avail Total</th>
									<th id="heading_truckload" class="text-center">Avail Truckloads</th>
									<th id="heading_frequency" class="text-center">Frequency (mo)</th>
									<th id="heading_frequency_ftl" class="text-center">Frequency FTL(mo)</th>
									<!-- <th class="text-center">Loads Available After PO</th>
									<th class="text-center">Frequency (Loads/Mo)</th> -->
								<? }  ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
								<? if ($shown_in_client_flg == 1){ ?>
									<td id="sub_table_total_value">0</td>
									<td id="sub_table_truckload">0</td>
								<? } else { ?>
									<td id="total_SKU">0</td>
									<td id="sub_table_total_value">0</td>
									<td id="sub_table_truckload">0</td>
									<td id="sub_table_frequency">0</td>
									<td id="sub_table_frequency_ftl">0</td>
									<!-- <td id="load_av_after_po">0</td>
									<td id="frequency">0</td> -->
								<? } ?>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end align-items-end m-0" style="width:75%">
                        <div class="col-md-1 p-0">
                            <!-- <select class="bg-light w-100" id="available" name="available" onchange="show_inventories('',1)">
                                <option value="quantities"> Quantities </option>
                                <option value="available" selected>Available</option>
                                <option value="actual">Actual</option>
                                <option value="frequency">Frequency</option>
                            </select> -->
                            <input type="hidden" id="active_page_id" value="1"/>
							<input type="hidden" id="shown_in_client_flg" value="<? echo $shown_in_client_flg;?>"/>
                        </div>
                        <!-- <div class="col-md-1 p-0 ml-3">
                            <select class="bg-light w-100" id="list_by_item" onchange="change_list_by_item()">
                                <option value="groupby"> Group by </option>
                                <option value="list-by-item" selected>List by item</option>
                                <option value="group-by-location">Group by Location</option>
                            </select>
                            <input type="hidden" id="active_page_id" value="1"/>
                        </div> -->
                        <div class="col-md-3 p-0 ml-3">
                            <select class="bg-light w-100" id="sort_by" onchange="change_sort_by()">
                                <option value=""> Sort By </option>
                                <option value="low-high" selected>Price: Low-High</option>
                                <option value="high-low">Price: High-Low</option>
                                <option value="nearest">Distance: Nearest</option>
                                <option value="furthest">Distance: Furthest</option>
                                <option value="freq-most-least">Frequnecy: Most-Least</option>
                                <option value="freq-least-most">Frequency: Least-Most</option>
                                <option value="qty-most-least">Quantity: Most-Least</option>
                                <option value="qty-least-most">Quantity: Least-Most</option>
                                <option value="leadtime-soonest-latest">Lead Time (FTL): Soonest-Latest</option>
                                <option value="leadtime-latest-soonest">Lead Time (FTL): Latest-Soonest</option>
                                <option value="height-short-tall">Height: Short-Tall</option>
                                <option value="height-tall-short">Height: Tall-Short</option>
                                <option value="cu-small-big">Cu.Ft: Small-Big</option>
                                <option value="cu-big-small">Cu.Ft: Big-Small</option>
                            </select>
                            <input type="hidden" id="active_page_id" value="1"/>
                        </div>
                        <div class="col-md-1 p-0 ml-1 text-center">
                            <input type="hidden" id="view_type" value="table_view"/>
                            <button style="font-size:10px;padding:5px" class="btn btn-light btn-active-view btn-sm" id="table-view-button" onclick="change_view_type('table_view')"><i class="fa fa-align-justify"></i></button>
                            <button style="font-size:12px;padding:5px" class="btn btn-light btn-sm" id="list-view-button" onclick="change_view_type('list_view')"><i class="fa fa-th-large"></i></button>
                            <!-- <button class="btn btn-light btn-sm" id="grid-view-button" onclick="change_view_type('grid_view')"><i class="fa fa-th-large"></i></button> -->
                        </div>
                </div>
                <div class="products_div_main">
                    <div class="result_products" id="result_products">
                    </div>


                </div>
                <!-- <div class="col-md-12 mt-4 pagination d-none justify-content-center">
            
                </div> -->
            </div>
        </div>
</div>
<!-- Modal -->
<div class="modal fade" id="available_loads_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Available Load Ship Dates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="ship_data">
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="scripts/jquery-3.7.1.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="scripts/boomerang.js"></script>
<script type="text/javascript" src="tagfiles/solnew.js"></script>
<script>
	var selection_obj={};
	var default_sel_gaylord=true;
	var default_sel_timing=true;
	var default_sel_warehouse=true;
	var default_sel_tags = true;
	
	function products_loading(){
		$('#loader').removeClass('d-none');
		//$('html, body').animate({scrollTop:0},500);
		$('#result_products').addClass('d-none');
		$('.pagination').addClass('d-none');
	}
	
	function products_loaded(){
		$('#loader').addClass('d-none');
		$('#result_products').removeClass('d-none')
		$('.pagination').removeClass('d-none');
	}
	function change_type(){
		default_sel_gaylord=false;
		show_inventories('',1);
	}
	function change_sort_by(){
		var sort_by=$('#sort_by').val();
		if(sort_by=="nearest" || sort_by=="furthest"){
			if($('#txtzipcode').val()==""){
				var alert_msg= sort_by=="nearest" ? 'Nearest' : 'furthest';
				alert("Enter Zipcode For "+alert_msg+" Products");
				$('#txtzipcode').focus();
				//$('#txtzipcode').attr("zip_for_sorting",1)
			}else{
				show_inventories('',1);
			}
		}else{
			show_inventories('',1);
		}
	}
	$('#txtzipcode').change(function(){
		var sort_by=$('#sort_by').val();
		if($('#txtzipcode').val()!="" && (sort_by=="nearest" || sort_by=="furthest")){
			show_inventories('',1);
		}
	})
	function clear_filter(clear_filter_of){
        if(clear_filter_of=='deliverydata'){
			$("input[name='txtaddress']").val("");
			$("input[name='txtaddress2']").val("");
			$("input[name='txtcity']").val("");
			$("input[name='txtstate']").val("");
			$("input[name='txtcountry']").val("");
			$("input[name='txtzipcode']").val("");
			$("#apply-deliverydata").css('width', '100%');
			$("#clear-deliverydata").css('display','none');
			selection_obj.deliverydata={};
		}
		show_inventories("",1);
	}

	
	function change_view_type(view_type){
		if(view_type=="list_view"){
			$("#products-table-view").css("display","none");
			$("#products-list-view").css("display","block");
			$('#list-view-button').addClass('btn-active-view');
			$('#table-view-button').removeClass('btn-active-view');
		}else{
			$("#products-list-view").css("display","none");
			$("#products-table-view").css("display","block");
			$('#table-view-button').addClass('btn-active-view');
			$('#list-view-button').removeClass('btn-active-view');
		}
		$('#view_type').val(view_type);
		show_inventories();
	}
	
	function change_timing(){
		default_sel_timing=false;
		var timing=$("select[name='timing']").val();
		if(timing==9){
			$('#timing_date_div').removeClass('d-none');
		}else{
			$('#timing_date_div').addClass('d-none');
			show_inventories("",1);
		}
	}

	function change_warehouse(){
		default_sel_warehouse=false;
		var warehouse=$("select[name='warehouse']").val();
		//if(warehouse!='all'){
			show_inventories("",1);
		//}
	}

    $('#apply-deliverydata').on('click', function(){
        if($('#txtzipcode').val() == ''){
            alert('Please Enter zipcode');
        }
    })

    var box_tag = [];
    $("#box_tags_apply_filter").click(function(){
        $.each($("#box_tags option:selected"), function(){            
            box_tag.push($(this).val());
        });  
        show_inventories("",1);  
    });
	
	let functionLoaded = false;
	
	var totalPages=0;
	var numberOfItems = 0;
    var currentPage;
	function show_inventories(reset_form="", filter="", show_urgent_box = 2){

		if ($('#loader').hasClass('d-none')) {
		}else{
			if (functionLoaded != false){
				alert("Please wait as page is loading.");
				return false;
			}
		}
			
		products_loading();
		
		var no_data=false;
		if(filter==1){
			$("#active_page_id").val(1);
		}
		if(reset_form==1){
			selection_str="";
		}
		var active_page_id = $("#active_page_id").val();
		var view_type = $("#view_type").val();
		var shown_in_client_flg = $("#shown_in_client_flg").val();
		
		selection_str="";
		var box_type= "Gaylord"; 
		var box_subtype= "all";

		var sort_by=$("#sort_by").val();
		var available= ""; //$("#available").val();
		var list_by_item=$("#list_by_item").val();

        var txtaddress=$("input[name='txtaddress']").val();
		var txtaddress2=$("input[name='txtaddress2']").val();
		var txtcity=$("input[name='txtcity']").val();
		var txtstate=$("select[name='txtstate']").find(':selected').attr('display_val');
		var txtcountry=$("select[name='txtcountry']").find(':selected').attr('display_val');
		var txtzipcode=$("input[name='txtzipcode']").val();

        if($("input[name='txtzipcode']").val() != "" && (reset_form == 2 || show_urgent_box == 1)){
            $("#sort_by").val('nearest');
            sort_by = 'nearest';
        }

        if($("input[name='txtzipcode']").val() == "" && show_urgent_box == 1){
            $("#sort_by").val('qty-most-least');
            sort_by = 'qty-most-least';
        }

		var warehouse=$("select[name='warehouse']").val();
		var warehouse_display_val=$("select[name='warehouse']").find(':selected').attr('display_val');
		if(default_sel_warehouse || warehouse==""){	
			selection_obj.warehouse={};
		}else if(warehouse != "all"){
			selection_obj.warehouse={'input_type':'select', 'input_name':'warehouse', data:warehouse, 'display_val':warehouse_display_val};
		}

		var timing = "";
		var display_val = "";
		if ($('#timing').length) {
			timing = $("select[name='timing']").val();
			display_val = $("select[name='timing']").find(':selected').attr('display_val');
		}else{
			timing = "6";
			display_val = "6";
			selection_obj.timing={'input_type':'select', 'input_name':'timing', data:timing, 'display_val':display_val};
		}
		var selected_date="";
		if(default_sel_timing || timing==""){	
			selection_obj.timing={};
		}else if(timing != ""){
			alert("test 3");
			if(timing==9){
				var selected_date=$('#timing_date').val();
				if(selected_date==""){
					$('#timing_date_error').removeClass('d-none');
                    alert('Please enter ship by date timimg');
					// return false;
				}else{
					$('#timing_date_error').addClass('d-none');
					selection_obj.timing={'input_type':'select', 'input_name':'timing', data:timing, 'display_val':display_val, selected_date};
				}
			}else{
				selection_obj.timing={'input_type':'select', 'input_name':'timing', data:timing, 'display_val':display_val};
			}	
		}

        var include_FTL_Rdy_Now_Only="";
		if($('#include_FTL_Rdy_Now_Only').is(':checked')){
			include_FTL_Rdy_Now_Only=$("#include_FTL_Rdy_Now_Only").val();
			var classname=$("#include_FTL_Rdy_Now_Only").attr('class');
			var display_val=$("#include_FTL_Rdy_Now_Only").attr('display_val');
			selection_obj.include_FTL_Rdy_Now_Only={'input_type':'checkbox', 'input_name':'include_FTL_Rdy_Now_Only', 'classname':classname, data:include_FTL_Rdy_Now_Only, 'display_val':display_val};
		}else{
			selection_obj.include_FTL_Rdy_Now_Only={};
		}

		var include_sold_out_items="";
		if($('#include_sold_out_items').is(':checked')){
			include_sold_out_items=$("#include_sold_out_items").val();
			var classname=$("#include_sold_out_items").attr('class');
			var display_val=$("#include_sold_out_items").attr('display_val');
			selection_obj.include_sold_out_items={'input_type':'checkbox', 'input_name':'include_sold_out_items', 'classname':classname, data:include_sold_out_items, 'display_val':display_val};
		}else{
			selection_obj.include_sold_out_items={};
		}

		var ltl_allowed="";
		if($('#ltl_allowed').is(':checked')){
			ltl_allowed=$("#ltl_allowed").val();
			var classname=$("#ltl_allowed").attr('class');
			var display_val=$("#ltl_allowed").attr('display_val');
			selection_obj.ltl_allowed={'input_type':'checkbox', 'input_name':'ltl_allowed', 'classname':classname, data:ltl_allowed, 'display_val':display_val};
		}else{
			selection_obj.ltl_allowed={};
		}
		var customer_pickup_allowed="";
		if($('#customer_pickup_allowed').is(':checked')){
			customer_pickup_allowed=$("#customer_pickup_allowed").val();
			var classname=$("#customer_pickup_allowed").attr('class');
			var display_val=$("#customer_pickup_allowed").attr('display_val');
			selection_obj.customer_pickup_allowed={'input_type':'checkbox', 'input_name':'customer_pickup_allowed', 'classname':classname, data:customer_pickup_allowed, display_val:display_val};
		}else{
			selection_obj.customer_pickup_allowed={};
		}
		var urgent_clearance="";
		if($('#urgent_clearance').is(':checked')){
			urgent_clearance=$("#urgent_clearance").val();
			var classname=$("#urgent_clearance").attr('class');
			var display_val=$("#urgent_clearance").attr('display_val');
			selection_obj.urgent_clearance={'input_type':'checkbox', 'input_name':'urgent_clearance', 'classname':classname, data:urgent_clearance, display_val:display_val};
		}else{
			selection_obj.urgent_clearance={};
		}

        var min_price_each = $("input[name='min_price_each']").val().replace("$", "");
		var max_price_each = $("input[name='max_price_each']").val().replace("$", "");
		if(min_price_each!=0.00 || max_price_each!=99.99){
			selection_obj.price_each={'input_type':'text', 'input_name':'price_each', data:[min_price_each, max_price_each]};
		}else{
			selection_obj.price_each={};
		}

		var min_height=$("input[name='min_height']").val();
		var max_height=$("input[name='max_height']").val();
		if(min_height!=0 || max_height!=99){	
			selection_obj.height={'input_type':'text', 'input_name':'height', data:[min_height, max_height]};
		}else{
			selection_obj.height={};
		}

		var wall_thickness=[]; 
		var all_thickness_data=[];
		if($("input:checkbox[name='wall_thickness[]']").filter(':checked').length>0){
			$("input[name='wall_thickness[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				wall_thickness.push({'values':$(this).val(), 'classname':classname, display_val});
				all_thickness_data.push($(this).val());
			});
			selection_obj.wall_thickness={'input_type':'checkbox', 'input_name':'wall_thickness', data:wall_thickness};
		}else{
			selection_obj.wall_thickness={};
		}

		var type=[]; 
		var all_type_data=[];
		if($("input:checkbox[name='type[]']").filter(':checked').length>0){
			$("input[name='type[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				type.push({'values':$(this).val(), 'classname':classname, display_val});
				all_type_data.push($(this).val());
			});
			selection_obj.type={'input_type':'checkbox', 'input_name':'type', data:type};
		}else{
			selection_obj.type={};
		}

		var uniformity=[]; 
		var all_uniformity_data=[];
		if($("input:checkbox[name='uniformity[]']").filter(':checked').length>0){
			$("input[name='uniformity[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				uniformity.push({'values':$(this).val(), 'classname':classname, display_val});
				all_uniformity_data.push($(this).val());
			});
			selection_obj.uniformity={'input_uniformity':'checkbox', 'input_name':'uniformity', data:uniformity};
		}else{
			selection_obj.uniformity={};
		}

		var shape=[]; 
		var all_shape_data=[];
		if($("input:checkbox[name='shape[]']").filter(':checked').length>0){
			$("input[name='shape[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				shape.push({'values':$(this).val(), 'classname':classname, display_val});
				all_shape_data.push($(this).val());
			});
			selection_obj.shape={'input_type':'checkbox', 'input_name':'shape', data:shape};
		}else{
			selection_obj.shape={};
		}

		var all_top_data=[];
		var top=[]; 
		if($("input:checkbox[name='top[]']").filter(':checked').length>0){
			$("input[name='top[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				top.push({'values':$(this).val(), 'classname':classname, display_val});
				all_top_data.push($(this).val());
				
			});
			selection_obj.top={'input_type':'checkbox', 'input_name':'top', data:top};
		}else{
			selection_obj.top={};
		}

		var bottom=[]; 
		var all_bottom_data=[];
		if($("input:checkbox[name='bottom[]']").filter(':checked').length>0){
			$("input[name='bottom[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				bottom.push({'values':$(this).val(), 'classname':classname, display_val});
				all_bottom_data.push($(this).val());				
			});
			selection_obj.bottom={'input_type':'checkbox', 'input_name':'bottom', data:bottom};
		}else{
			selection_obj.bottom={};
		}

		var vents=[]; 
		var all_vents_data=[];
		if($("input:checkbox[name='vents[]']").filter(':checked').length>0){
			$("input[name='vents[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				vents.push({'values':$(this).val(), 'classname':classname, display_val});
				all_vents_data.push($(this).val());				
			});
			selection_obj.vents={'input_type':'checkbox', 'input_name':'vents', data:vents};
		}else{
			selection_obj.vents={};
		}

		var grade=[]; 
		var all_grade_data=[];
		if($("input:checkbox[name='grade[]']").filter(':checked').length>0){
			$("input[name='grade[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				grade.push({'values':$(this).val(), 'classname':classname, display_val});
				all_grade_data.push($(this).val());				
			});
			selection_obj.grade={'input_type':'checkbox', 'input_name':'grade', data:grade};
		}else{
			selection_obj.grade={};
		}

		var include_presold_and_loops=[]; 
		var all_include_presold_and_loops_data=[];
		if($("input:checkbox[name='include_presold_and_loops[]']").filter(':checked').length>0){
			$("input[name='include_presold_and_loops[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				include_presold_and_loops.push({'values':$(this).val(), 'classname':classname, display_val});
				all_include_presold_and_loops_data.push($(this).val());				
			});
			selection_obj.include_presold_and_loops={'input_type':'checkbox', 'input_name':'include_presold_and_loops', data:include_presold_and_loops};
		}else{
			selection_obj.include_presold_and_loops={};
		}

		var status=[]; 
		var all_status_data=[];
		if($("input:checkbox[name='status[]']").filter(':checked').length>0){
			$("input[name='status[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				status.push({'values':$(this).val(), 'classname':classname, display_val});
				all_status_data.push($(this).val());				
			});
			selection_obj.status={'input_type':'checkbox', 'input_name':'status', data:status};
		}else{
			selection_obj.status={};
		}

        var printing=[]; 
		var all_printing_data=[];
		if($("input:checkbox[name='printing[]']").filter(':checked').length>0){
			$("input[name='printing[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				printing.push({'values':$(this).val(), 'classname':classname, display_val});
				all_printing_data.push($(this).val());				
			});
			selection_obj.printing={'input_type':'checkbox', 'input_name':'printing', data:printing};
		}else{
			selection_obj.printing={};
		}

        var box_tags_display_val=$("select[id='box_tags']").find(':selected').attr('display_val');
		//alert(box_tags_display_val);
		//if (box_tag.length > 0){
		if (box_tags_display_val){
			selection_obj.box_tags={'input_type':'select', 'input_name':'box_tags', data:box_tags, 'display_val':box_tags_display_val};
		}else{
			box_tag = [];
			selection_obj.box_tags={};
		}

		$.each(selection_obj, function(k,v){
			if(Object.keys(v).length!=0){
				if(v.input_name=="include_sold_out_items" ||  v.input_name=="include_FTL_Rdy_Now_Only" || v.input_name=="ltl_allowed" || v.input_name=="customer_pickup_allowed" || v.input_name=="urgent_clearance"){
					selection_str+="<p class='added_selection' classname='"+v.classname+"' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span> "+v.display_val +" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}else if(v.input_name=="warehouse"){
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}else if(v.input_name=="box_tags"){
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>Box Tags</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}else if(v.input_name=="timing"){
					if(v.data==9){
						selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+"( "+v.selected_date+" ) </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}else{
					    selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}
                }else if(v.input_name=="wall_thickness" || v.input_name=="type" || v.input_name=="uniformity" || v.input_name=="shape" || v.input_name=="top" || v.input_name=="bottom" || v.input_name=="grade" || v.input_name=="include_presold_and_loops" || v.input_name=="status"){
					for(var x=0; x<v.data.length; x++){
						selection_str+="<p class='added_selection' classname='"+v.data[x].classname+"' respective_input='"+v.input_name+"' respective_type='checkbox'><span> "+ v.data[x].display_val +" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}
                }else if(v.input_name=="printing"){
					for(var x=0; x<v.data.length; x++){
						selection_str+="<p class='added_selection' classname='"+v.data[x].classname+"' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span> "+v.data[x].display_val +" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}
				}else{
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span class='text-capitalize'>"+v.input_name+"</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}
			}
		});
		
		$('#selections').html(selection_str);
		if(selection_str!=""){
			$('#your-selections').css('display',"block");
		}else{
			$('#your-selections').css('display',"none");
		}
		var user_id = `<?php echo $user_id; ?>`;
        var data={show_urgent_box, box_type,view_type,active_page_id, box_subtype,sort_by,available,list_by_item,txtaddress,txtaddress2, txtcity, txtstate, txtcountry, txtzipcode,warehouse,timing,selected_date,include_sold_out_items, include_FTL_Rdy_Now_Only,all_include_presold_and_loops_data,all_status_data,ltl_allowed,customer_pickup_allowed,urgent_clearance,min_height,max_height,min_price_each, max_price_each,all_thickness_data,all_type_data,all_uniformity_data,all_shape_data,all_top_data,all_bottom_data,all_vents_data,all_grade_data,all_printing_data,box_tag, shown_in_client_flg, user_id};

		// console.log(data);
		$.ajax({
			url:'product_result_new.php',
			type:'get',
			data:data,
			datatype:'json',
			success:function(response){
				console.log(response);
				result_str="";
				var all_data=JSON.parse(response);
				var result=all_data.data;
				var no_of_pages=all_data.no_of_pages;
				numberOfItems = all_data.total_items;
				limitPerPage = 15;
				totalPages = Math.ceil(numberOfItems / limitPerPage);
				if( !$.isArray(result) ||  result.length ==0 ) {
					result_str +='<div class="col-md-12 mt-5 alert alert-danger">';
					result_str +='<h6 class="mb-0">No Data Available </h6>';
					result_str +='</div>';
					no_data=true;
				}else{
                    var sub_table_total_value = 0;
                    var sub_table_truckload = 0;
                    var sub_table_frequency = 0;
                    var sub_table_frequency_ftl = 0;
					var default_img="images/boomerang/default_product.jpg";
					if(view_type=="list_view"){
						result_str +='<div id="products-list-view" class="mt-3 m-auto">';
						$.each(result,function(index,res){
                            sub_table_total_value += parseInt(res['qtynumbervalue'].replace(/[^0-9]/g, ""));
                            sub_table_truckload += parseInt(res['percent_per_load'].replace(/[^0-9]/g, ""));
                            sub_table_frequency += parseFloat(res['frequency_sort']);
                            sub_table_frequency_ftl += parseFloat(res['frequency_ftl']);
							result_str +='<div class="col-md-12 product-box-list thikness-5">';
							result_str +='<div class="row align-items-center">';
							result_str +='<div class="col-md-2">';
							var prod_src=res['img']==""? default_img :  "../boxpics_thumbnail/"+res['img'];
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'"><img src="'+prod_src+'" class="img-fluid product-img"/></a>';
							result_str +='</div>';
							result_str +=`<div class="col-md-8 product-description-list" style="background-color : ${res['td_bg'] == 'yellow' ? '#ffff0070' : res['td_bg']};">`;
							result_str +='<div class="col-md-12 product-additional-info">';
							result_str +='<div class="row">';
							result_str +='<div class="col-md-8 p-0">';
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'"><h5>'+res["description"]+'</h5></a>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="images/boomerang/icon_status.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body"><p><span class="product_desc">Status: '+res["status"]+'</p></div>';
							result_str +='</div>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="images/boomerang/icon_Location.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body"><span class="product_desc">Ship From: </span> '+res["ship_from"]+'</div>';
							result_str +='</div>';
                            result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_Dimensions.png" alt="Generic placeholder image">';
							var system_description=res['system_description'];
							result_str +='<div class="media-body"><p><span class="product_desc">Description: </span>'+system_description+'</p></div>';
							result_str +='</div>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_notes.png" alt="Generic placeholder image">';
							var flyer_notes=res['flyer_notes'];
							result_str +='<div class="media-body"><p><span class="product_desc">Flyer Notes: </span> '+flyer_notes+'</p></div>';
							result_str +='</div>';
							// result_str +='<div class="media">';
							// result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_notes.png" alt="Generic placeholder image">';
							// result_str +='<div class="media-body"><p><span class="product_desc">Lead time of FTL: </span> '+res['lead_time_of_FTL']+'</p></div>';
							// result_str +='</div>';
							result_str +='</div>';
							result_str +='<div class="col-md-4">';
							// if($('#txtzipcode').val()==""){
							// 	result_str +='<p class="my-1"><span class="highlight-detail-list" onclick="get_miles_away(); return false;">Add zip for mi away</span></p>';
							// }else{
							// 	result_str +='<p class="my-1"><span class="highlight-detail-list">'+res["distance"]+' mi away</span></p>';
							// }
                            result_str +='<p class="my-1 text-center">'+res["distance"]+'</p>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_truck.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body"><p><span class="product_desc">Can Ship LTL? </span> '+res["ltl"]+'</p></div>';
							result_str +='</div>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="images/boomerang/can_customer_pickup.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body"><p><span class="product_desc">Can Customer Pickup? </span> '+res["customer_pickup"]+'</p></div>';
							result_str +='</div>';
                            result_str +='<div class="media flex-column">';
							result_str +='<p class="text-center w-100"><a class="product_link available_loads" data-toggle="modal" data-target="#available_loads_modal" loop_id="'+res.loop_id+'">'+res["loads"]+'</a></p>';
							result_str +='<p class="text-center w-100">First Load Can Ship In <br> '+res["first_load_can_ship_in"]+'</p>';
							result_str +='</div>';
							result_str +='</div>';
							result_str +='</div></div></div>';
							result_str +='<div class="col-md-2 align-self-end p-0">';
							result_str +='<div class="load_ship">';
                            var delivery_cal_para=res['b2b_id']+" , '"+res['txtaddress']+"' , '"+res['txtaddress2']+"' , '"+res['txtcity']+"' , '"+res['txtstate']+"' , '"+res['txtcountry']+"' , '"+res['txtzipcode']+"' , "+res['minfob'];
							result_str +='<h5 class="font-weight-bold m-0" id="cal_delh4'+res['b2b_id']+'">'+res["price"]+' </h5><div id="cal_del'+res['b2b_id']+'"><a class="product_link" href="#" onclick="calculate_delivery('+delivery_cal_para+'); return false;">Calculate Delivery</a></div>';
							result_str +='</div>';
                            result_str +='<div class="load_ship mt-2">';
							result_str +='<ul class="pl-1 text-left m-0" style="list-style-type: none;">';
							result_str +=`<li><b>Quantity </b> : ${res['colorvalueQty']}</li>`;
							result_str +='<li><b>Lead Time </b> : '+res['lead_time_of_FTL']+'</li>';
							result_str +='<li><b>% of Load</b> : '+res['percent_per_load']+'%</li>';
							result_str +='</ul>';
							result_str +='</div>';
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'&checkout=1" class="btn btn-cart">Buy Now</a>';
							
							result_str +='</div></div></div>';

						});
						result_str +='</div>';
					}else{
						var shown_in_client_flg_width = <?php echo json_encode($shown_in_client_flg_width); ?>;
						var shown_in_client_flg = <?php echo json_encode($shown_in_client_flg); ?>;
						
						if (show_urgent_box == 1){
							result_str += '<div class="dashboard_heading" style="text-align: center; font-size: 16px;font-family: Titillium Web, sans-serif;font-weight:600;">B2B Inventory: Used Gaylord Totes (Urgent Boxes)</div>';
							result_str += '<div id="products-table-view">';
						}else{
							result_str +='<div id="products-table-view" class="mt-3">';
						}
						result_str +='<table style="' + shown_in_client_flg_width + '" cellSpacing="1" cellPadding="1" border="0">';
						result_str += '<thead><tr>';
						
								if (shown_in_client_flg == 1) {								
									result_str += `<th style="width:5%;">Favorite</th>`;
								}
							result_str += `
								<th>B2B ID</th>
								<th>Height</th>
								<th>Walls</th>
								<th>Bottom</th>
								<th style='background-color: white;'>&nbsp;</th>
								<th>Qty Avail <br>NOW</th>
								<th>% of FTL</th>
								<th>Lead Time</th>
								<th>FTL Qty</th>`;
								if (shown_in_client_flg == 0) {								
									result_str += `<th>Lead Time<br>for FTL</th>`;
								}									
								result_str += `<th>Miles Away</th>
								<th>Price</th>`;
								if (shown_in_client_flg == 0) {								
									result_str += `<th>Delivery</th>`;
								}else{
									result_str += `<th>FTL Delivery</th>`;
								}									
								result_str += `<th>View Item</th>
							</tr>
						</thead>
						<tbody>`;
						
					sub_table_total_value = 0; row_alternate_cnt = 0;
					$.each(result,function(index,res){
						perofftl = "";
						if (res['percent_per_load'] > 0){
							perofftl = res['percent_per_load'];
						}
						
                        sub_table_total_value = sub_table_total_value + parseFloat(res['qtynumbervalue']);
						
                        sub_table_truckload += parseFloat(res['percent_per_load']);
                        sub_table_frequency += parseFloat(res['frequency_sort']);
                        sub_table_frequency_ftl += parseFloat(res['frequency_ftl']);
                        var delivery_cal_para = res['b2b_id']+" , '"+res['txtaddress']+"' , '"+res['txtaddress2']+"' , '"+res['txtcity']+"' , '"+res['txtstate']+"' , '"+res['txtcountry']+"' , '"+res['txtzipcode']+"' , "+res['minfob'];
						
						if (row_alternate_cnt == 0){
							row_alternate_color = "#e4e4e4";
							row_alternate_cnt = 1;
						}else{
							row_alternate_color = "#f4f4f4";
							row_alternate_cnt = 0;
						}
						
						td_bg_color = "";
						if (res["td_bg"] == "green"){
							td_bg_color = " color: green;";
						}
						if (shown_in_client_flg == 0) {
							if (res["td_bg"] != ""){
								row_alternate_color = "yellow";
							}
						}
	
						var minfobtxt = "";
						if ((res["minfob"] == 0)){
							minfobtxt = "Inquire";
						}else{
							minfobtxt = "$" + res["minfob"];
						}
						
						
						var favorite = res['favorite'];
						result_str +=`<tr>`;	
										if (shown_in_client_flg == 1) {
						result_str += `		<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">
										<span onclick="change_fav_status(this, ${res['companyID']},${res['b2b_id']},${favorite},<?php echo $user_id; ?>)">`;	
						result_str += favorite == 1 ? '<i class="fa fa-heart fav-added"></i>' : '<i class="fa fa-heart fav-removed"></i>';
						result_str +=`</span></td>`;
										}
						
						result_str += `
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['b2b_id']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['boxheight']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['boxwall']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['box_desc_bottom']}</td>
										
										<td style="background-color:white;" >&nbsp;</td>`;
										
										if (shown_in_client_flg == 1) {
						result_str += `		<td style="background-color:${row_alternate_color}; ${td_bg_color}" bgColor="${res['td_bg']}" id="after_po${index}">${res['colorvalueQty']}</td>`;
										}else{
						result_str += `		<td style="background-color:${row_alternate_color}; ${td_bg_color}" bgColor="${res['td_bg']}" id="after_po${index}"><a href="javascript:void(0)" onclick="display_preoder_sel(${index}, ${res['qtynumbervalue']}, ${res['loop_id']}, ${res['box_warehouse_id']})"><u>${res['colorvalueQty']}</u></a></td>`;
										}
										
                        result_str += `	<td style="background-color:${row_alternate_color}; ${td_bg_color}" bgColor="${res['td_bg']}">${perofftl}</td>
										<td style="background-color:${row_alternate_color}; ${res['td_leadtime_bg_color']}" bgColor="${res['td_bg']}">${res['box_lead_time']}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res["ftl_qty"].toLocaleString('en-US')}</td>`;

										if (shown_in_client_flg == 0) {
						result_str += `		<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res['lead_time_of_FTL']}</td>`;
										}
										
						result_str += `	<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${res["distance"]}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">${minfobtxt}</td>
										<td style="background-color:${row_alternate_color}" bgColor="${res['td_bg']}">
											<div class="font-weight-bold m-0" id="cal_delh4${res['b2b_id']}${show_urgent_box}"></div>										
											<a class="text-primary" href="#" id="cal_del${res['b2b_id']}${show_urgent_box}" onclick="calculate_delivery(${delivery_cal_para}, ${show_urgent_box}); return false;"><u>Calculate</u></a>
										</td>`;

										if (shown_in_client_flg == 1) {
						result_str += `		<td style="background-color:${row_alternate_color}" class="text-center" bgColor="${res['td_bg']}"><a class="text-primary" href="https://b2b.usedcardboardboxes.com/index_new.php?id=${res['loop_id_encrypt_str']}&uid=<?php echo encrypt_password($user_id);?>" target="_blank"><u>View Item</u></a></td>`;
										}else{
						result_str += `		<td style="background-color:${row_alternate_color}" class="text-center" bgColor="${res['td_bg']}"><a class="text-primary" onmouseover="Tip('${res['description_hover_notes']}')"  onmouseout="UnTip()" target="_blank" href="manage_box_b2bloop_new.php?id=${res['loop_id']}&proc=View&"><u>View Item</u></a></td>`;
										}
						result_str += `</tr>`;

                        if(res['qtynumbervalue'] > 0){
                                result_str +=`<tr >
												<td colspan="15">
												<div id="inventory_preord_middle_div_${index}"></div>		
											</td>
										</tr>`;
						}

					});
					result_str +='</tbody>';
					result_str +='</div>';
				};
			}

				if (show_urgent_box == 1){
					$('#result_products_urgent').html(result_str);
				}else{
					$('#result_products').html(result_str);
				}
			
				if (shown_in_client_flg == 1) {
					
					if (sub_table_total_value){
						$('#sub_table_total_value').html(sub_table_total_value.toLocaleString('en-US'));
					}else{
						$('#sub_table_total_value').html("0");
					}

					if (sub_table_truckload){
						var truckloadValue = sub_table_truckload;
						$('#sub_table_truckload').html(truckloadValue.toFixed(2));
					}else{
						$('#sub_table_truckload').html("");
					}

				}else{
					$('#total_SKU').html(result.length);

					if (sub_table_truckload){
						var truckloadValue = sub_table_truckload;
						$('#sub_table_truckload').html(truckloadValue.toFixed(2));
					}else{
						$('#sub_table_truckload').html("");
					}

					if (sub_table_total_value){
						$('#sub_table_total_value').html(sub_table_total_value.toLocaleString('en-US'));
					}else{
						$('#sub_table_total_value').html("0");
					}

					if (sub_table_frequency && sub_table_frequency > 0){
						$('#sub_table_frequency').html(sub_table_frequency.toLocaleString('en-US'));
					}else{
						$('#sub_table_frequency').html("0");
					}

					if (sub_table_frequency_ftl > 0){
						$('#sub_table_frequency_ftl').html(sub_table_frequency_ftl.toFixed(2));
					}else{
						$('#sub_table_frequency_ftl').html("0");
					}
				}					
			},
			complete: function () {
				if(no_data==false){
					$('#txtzipcode').removeAttr("zip_for_sorting");
					if (show_urgent_box != 1){
						products_loaded();
					}
				}else{
					if (show_urgent_box != 1){
						products_loaded();
					}
				}
				
			},
		})
		
		 functionLoaded = true;
	}
	
	var shown_in_client_flg = <?php echo json_encode($shown_in_client_flg); ?>;

	if (shown_in_client_flg == 1 && $('#txtzipcode').val() != "") {
		show_inventories("", 1, 1);
		
		functionLoaded = false;

		show_inventories('2');
	}else{
		
		show_inventories("", 1, 1);
		
		functionLoaded = false;
		
		show_inventories("", 1, 2);
	}

	$(document).on('mouseenter','.product-box-grid',function(){
		$(this).find('.fixed_height_description').addClass('d-none');
		$(this).find('.complete_description').removeClass('d-none');
		$(this).find('.fixed_height_flyer_note').addClass('d-none');
		$(this).find('.complete_flyernote').removeClass('d-none');
	})
	$(document).on('mouseleave','.product-box-grid',function(){
		$(this).find('.fixed_height_description').removeClass('d-none');
		$(this).find('.complete_description').addClass('d-none');
		$(this).find('.fixed_height_flyer_note').removeClass('d-none');
		$(this).find('.complete_flyernote').addClass('d-none');
	})

	$(document).on('click','.remove_selection', function(){
		var remove_val_of=$(this).parents('.added_selection').attr('respective_input');
		var input_type=$(this).parents('.added_selection').attr('respective_type');
		if(input_type=="text"){
			if(remove_val_of == "height"){
				$("input[name='min_height']").val(0);
				$("input[name='max_height']").val(99);
            }else if(remove_val_of == "price_each"){
				$("input[name='min_price_each']").val("0.00");
				$("input[name='max_price_each']").val("99.99");
			}else {
				$("#"+remove_val_of).val("");
			}
		}else if(input_type=="select"){
			if(remove_val_of=="box_type"){
				$("#"+remove_val_of).val("Gaylord");
				default_sel_gaylord=true;
			}else if(remove_val_of=="timing"){
				$("#"+remove_val_of).val(4);
				default_sel_timing=true;
			}else if(remove_val_of=="warehouse"){
				$("#"+remove_val_of).val('all');
				default_sel_warehouse=true;
			}else if(remove_val_of=="box_tags"){
				$("#"+remove_val_of).val('remove');
				default_sel_tags=true;
			}else{
				$("#"+remove_val_of).val("");
			}
		}else if(input_type=="checkbox"){
			var classname=$(this).parents('.added_selection').attr('classname');
			$("."+classname).prop("checked", false );
		}	
		$(this).parents('.added_selection').css('display','none');
		show_inventories("",1);
	})
	
	$("#clear_all").click(function(){
		$("#filter_form")[0].reset();
		show_inventories(1,1);
		return false;
	});

	function get_miles_away()
	{
		$('#txtzipcode').focus();
	}


    function calculate_delivery(inv_b2b_id, txtaddress, txtaddress2, txtcity, txtstate, txtcountry, txtzipcode, minfob, show_urgent_box){
		if ( $('#txtaddress').val()=="" || $('#txtcity').val()=="" || $('#txtstate').val()=="" || $('#txtzipcode').val()==""){
			alert("Enter the Delivery address to calculate the delivery.")
			$('#txtaddress').focus();
		}else{
			$.ajax({
				url:'uber_freight_matching_tool_v3.php',
				type:'get',
				data:"inv_b2b_id="+inv_b2b_id+"&inclient=1&b2binvflg=yes&txtaddress="+txtaddress+"&txtaddress2="+txtaddress2+"&txtcity="+txtcity+"&txtstate="+txtstate+"&txtcountry="+txtcountry+"&txtzipcode="+txtzipcode+"&minfob="+minfob,
				datatype:'text',
				beforeSend: function () {
					$('#cal_delh4'+inv_b2b_id+show_urgent_box).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
				},
				success:function(res){
					$('#cal_del'+inv_b2b_id+show_urgent_box).css('display','none');
					$('#cal_delh4'+inv_b2b_id+show_urgent_box).html(res);
				},	
			})
		}
	}

	$(document).on('click','.available_loads', function(){
		var loop_id=$(this).attr('loop_id');
		$.ajax({
				url:'show_available_load_data.php',
				type:'get',
				data:{loop_id},
				datatype:'json',
				beforeSend: function () {
					$("#ship_data").html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
				},
				success:function(response){
					var res=JSON.parse(response);
					if(res.count>0){
						var table_data="<table class='table table-bordered'><thead><tr><th>Sr. no.</th><th>Available Load Ship Date</th></tr></thead>";
						$.each(res.data,function(i,d){
							table_data+="<tr>";	
							table_data+="<td>"+(i+1)+"</td>";	
							table_data+="<td>"+d.load_available_date+"</td>";	
							table_data+="</tr>";	
						})
						table_data+="</table>";
						$("#ship_data").html(table_data);
					}else{
						$("#ship_data").html('No data for this inventory item at this time.');
					}
				},	
			})
	});

	$(document).on('click','#enter_address_text', function(){
		if($('#full_address_div').hasClass('d-none')){
			$('#full_address_div').removeClass('d-none');
			$(this).text('Hide Address');
			$('#clear-deliverydata').text('Clear Address');
			$('#apply-deliverydata').text('Apply Address');

		}else{
			$('#full_address_div').addClass('d-none');
			$(this).text('Enter Full Adddress');
			$('#clear-deliverydata').text('Clear Zipcode');
			$('#apply-deliverydata').text('Apply Zipcode');
		}
	})

    $(function() {
        $('#box_tags').searchableOptionList();
    }); 


	function change_fav_status(currentEle,companyID, b2b_id, fav_status, user_id){
		$.ajax({
			url:'product_result_new.php',
			type:'get',
			data:{companyID, b2b_id, fav_status, user_id:`<?php echo $user_id?>`,change_fav_status:1 },
			datatype:'json',
			success:function(response){
				//console.log(response);
				
				if(response == 1){
					$(currentEle).html('<i class="fa fa-heart fav-added"></i>');
				}else if(response == 0){
					$(currentEle).html('<i class="fa fa-heart fav-removed"></i>');
				}else{
					alert('Something went wrong, try again');
				}
			}
		})
	}

</script>

</html>
