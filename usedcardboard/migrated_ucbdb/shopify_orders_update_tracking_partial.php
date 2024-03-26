<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$data_string = "";
function update_shopify_tracking_no_remove_old(int $orders_id, int $shopify_order_no, string $tracking_number_org, int $fulfillments_no, string $carrier_code_org, string $tracking_urls): void
{
	//update the Shopify Tracking number
	if ($shopify_order_no != "") {
		$shopify_fulfillment_id = "";
		$data_string = "";

		$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments.json');
		curl_setopt(
			$ch_shopify,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string)
			)
		);

		//curl_setopt($ch_shopify, CURLOPT_GET, 2);
		curl_setopt($ch_shopify, CURLOPT_HTTPGET, true);
		//curl_setopt($ch_shopify, CURLOPT_HTTPGET, true)
		curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch_shopify);
		if ($result) {
			$jsonData = json_decode($result, true);

			print_r($jsonData);

			$shopify_fulfillment_id_arr = $jsonData["fulfillments"];
			foreach ($shopify_fulfillment_id_arr as $shopify_fulfillment_id_sub) {
				$shopify_fulfillment_id = $shopify_fulfillment_id_sub["id"];
			}
			//echo "shopify_order_fulfillment_id  - " . $shopify_fulfillment_id;

			if ($shopify_fulfillment_id != "") {
				curl_close($ch_shopify);

				$data_string = "";
				$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments/' . $shopify_fulfillment_id . '/cancel.json');
				curl_setopt(
					$ch_shopify,
					CURLOPT_HTTPHEADER,
					array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen($data_string)
					)
				);

				curl_setopt($ch_shopify, CURLOPT_POST, 1);
				curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

				$result = curl_exec($ch_shopify);

				//var_dump($result);
			}
		}

		$data_found = "no";
		$first_order = "no";
		$shopify_order_fulfillment_id = 0;
		$tracking_number_arr = explode(",", $tracking_number_org);
		//$tracking_urls_arr = explode("," , $tracking_urls);

		//print_r($tracking_urls_arr);

		$cnt = 0;
		$data_found = "yes";
		if (strtoupper($carrier_code_org) == "FEDEX") {
			$carrier_code = "FedEx";
		} else if (strtoupper($carrier_code_org) == "UPS") {
			$carrier_code = "UPS";
		} else {
			$carrier_code = $carrier_code_org;
		}

		$data = array("fulfillment" => array(
			"location_id" => 40886534275,
			"tracking_number" => strval($tracking_number_org),
			"tracking_company" => strval($carrier_code),
		));

		//"tracking_urls" => "[" . $tracking_urls . "]"

		$data_string = json_encode($data);

		$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments.json');
		curl_setopt(
			$ch_shopify,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string)
			)
		);

		curl_setopt($ch_shopify, CURLOPT_POST, 2);
		curl_setopt($ch_shopify, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch_shopify);

		if ($result) {
			$jsonData = json_decode($result, true);

			if ($jsonData["errors"]) {
				echo "There is error <br>";
				print_r($jsonData);
			} else {
				echo "data updated <br>";
				db_query("Update orders set updated_tracking_shopify_flg = 1 where orders_id = " . $orders_id);
			}
			//$shopify_order_fulfillment_id = $jsonData["fulfillment"]["id"];	
			//echo "shopify_order_fulfillment_id  - " . $shopify_order_fulfillment_id;
		}

		$cnt = $cnt + 1;
	}
}

$tracking_urls = "'https://www.fedex.com/fedextrack/summary?trknbr=9171444&action=track',";

//update_shopify_tracking_no_org(394075, 3665193566365, 5454444, 'FedEx', $tracking_urls);
update_shopify_tracking_no_remove_old(394075, 3665193566365, "9171444", 3235623698589, 'FedEx', $tracking_urls);
//exit;

$token = "uumpondjxakyfpl9t0p7kqox8jjh95hp";
//$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=3665193566365&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/3665193566365/fulfillments.json');
// Returns the data/output as a string instead of raw data
curl_setopt(
	$ch_shopify,
	CURLOPT_HTTPHEADER,
	array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string)
	)
);

//curl_setopt($ch_shopify, CURLOPT_GET, 1);
curl_setopt($ch_shopify, CURLOPT_HTTPGET, true);
curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch_shopify);

$jsonData = json_decode($result, true);

var_dump($jsonData);

curl_close($ch_shopify);

/*function update_shopify_tracking_no_org(int $orders_id, string $shopify_order_no, string $tracking_number_org, string $carrier_code_org, array $tracking_urls): void
{		//update the Shopify Tracking number
	if ($shopify_order_no != "") {
		$shopify_fulfillment_id = "";
		$data_string = "";
		$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments.json');
		curl_setopt(
			$ch_shopify,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string)
			)
		);
		curl_setopt($ch_shopify, CURLOPT_HTTPGET, true);
		//curl_setopt($ch_shopify, CURLOPT_GET, 1);
		curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch_shopify);
		if ($result) {
			$jsonData = json_decode($result, true);

			//print_r($jsonData);

			$shopify_fulfillment_id = $jsonData["fulfillment"]["id"];
			//echo "shopify_order_fulfillment_id  - " . $shopify_order_fulfillment_id;

			if ($shopify_fulfillment_id != "") {
				$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments/' . $shopify_fulfillment_id . '/cancel.json');
				curl_setopt(
					$ch_shopify,
					CURLOPT_HTTPHEADER,
					array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen($data_string)
					)
				);

				//curl_setopt($ch_shopify, CURLOPT_GET, 1);
				curl_setopt($ch_shopify, CURLOPT_HTTPGET, true);
				curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

				$result = curl_exec($ch_shopify);
			}
		}


		$data_found = "no";
		$first_order = "no";
		$shopify_order_fulfillment_id = 0;
		$tracking_number_arr = explode(",", $tracking_number_org);
		//$tracking_urls_arr = explode("," , $tracking_urls);

		//print_r($tracking_urls_arr);

		$cnt = 0;
		$data_found = "yes";
		if (strtoupper($carrier_code_org) == "FEDEX") {
			$carrier_code = "FedEx";
		} else if (strtoupper($carrier_code_org) == "UPS") {
			$carrier_code = "UPS";
		} else {
			$carrier_code = $carrier_code_org;
		}

		$data = array("fulfillment" => array(
			"location_id" => 40886534275,
			"tracking_number" => strval($tracking_number_org),
			"tracking_company" => strval($carrier_code),
		));
		//"tracking_urls" => "[" . $tracking_urls . "]"

		$data_string = json_encode($data);

		$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments.json');
		curl_setopt(
			$ch_shopify,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string)
			)
		);

		curl_setopt($ch_shopify, CURLOPT_POST, 1);
		curl_setopt($ch_shopify, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch_shopify);

		if ($result) {
			$jsonData = json_decode($result, true);

			//print_r($jsonData);

			if ($jsonData["errors"]) {
				echo "There is error <br>";
				print_r($jsonData);
			} else {
				echo "data updated <br>";
				db_query("Update orders set updated_tracking_shopify_flg = 1 where orders_id = " . $orders_id);
			}
			//$shopify_order_fulfillment_id = $jsonData["fulfillment"]["id"];	
			//echo "shopify_order_fulfillment_id  - " . $shopify_order_fulfillment_id;
		}

		$cnt = $cnt + 1;
	}
}
*/
