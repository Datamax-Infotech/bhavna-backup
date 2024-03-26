<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$greensave_sql		= 	"SELECT tree_counter.trees_saved as saveone, tree_counter_b2b.trees_saved as save2 FROM tree_counter,tree_counter_b2b;";
$greensave_query 	= 	db_query($greensave_sql);
$greensave_result 	= 	array_shift($greensave_query);

$save1 				= 	$greensave_result['saveone'];
$save2 				= 	$greensave_result['save2'];
$dTotalSave			=	$save1 + $save2;
$dTotalSave			=	number_format($dTotalSave, 0);

echo "dTotalSave - " . $dTotalSave;

/*$data = array(
		'product' => array(
			"id" => 6786925822109,                                       
			"title" => "Product-Added-For_tree-counter1",
			"price" => 5059973.00
		)   
	);*/

$data = array(
	'variant' => array(
		"price" => $dTotalSave
	)
);

$data_string = json_encode($data);

$ch_shopify = curl_init('https://0d620914bcbc874e019e70257564829f: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@usedcardboardboxes.myshopify.com/admin/api/2021-07/variants/40250422362269.json');

curl_setopt(
	$ch_shopify,
	CURLOPT_HTTPHEADER,
	array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string)
	)
);

curl_setopt($ch_shopify, CURLOPT_POST, 2);
curl_setopt($ch_shopify, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch_shopify, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch_shopify);

//var_dump($result);

if ($result) {
	$jsonData = json_decode($result, true);

	//print_r($jsonData);

	if ($jsonData["errors"]) {
		echo "There is error <br>";
		print_r($jsonData);
	} else {
		echo "data updated <br>";
	}
}

//exit;

//to get the product data
$data_string = "";
$ch_shopify = curl_init('https://0d620914bcbc874e019e70257564829f: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@usedcardboardboxes.myshopify.com/admin/api/2021-07/products/6786925822109.json');

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
curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch_shopify);

var_dump($result);
