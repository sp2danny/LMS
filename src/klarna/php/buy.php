
<?php

include "getparam.php";
include "db.php";

include_once "../../site/common/debug.php";

$id = getparam("id");
$pid = getparam("prod");

$pr_title = 'fel';
$pr_price = 200;

$query = "SELECT * FROM prod WHERE prod_id=" . $pid;
$res = mysqli_query($emperator, $query);
if ($res) if ($row = mysqli_fetch_array($res)) {
	$pr_title = $row['name'];
	$pr_price = $row['price'];
} else {
	echo "DB error, '" . $query . "' <br> \n";
}

$url = 'https://api.klarna.com/checkout/v3/orders';

$ol = [];
$ol[] = [
    "name" => $pr_title,
    "quantity" => 1,
    "unit_price" => 100 * $pr_price,
    "tax_rate" => 2500,
    "total_amount" => 100 * $pr_price,
    "total_tax_amount" => 20 * $pr_price,
];

$mu = [
    "terms"         =>  "https://mind2excellence.se/klarna/php/terms.php?id=" . $id,
    "checkout"      =>  "https://mind2excellence.se/klarna/php/checkout.php?id=" . $id,
    "confirmation"  =>  "https://mind2excellence.se/klarna/php/confirmation.php?id=" . $id,
    "validation"    =>  "https://mind2excellence.se/klarna/php/validation.php?id=" . $id,
    "push"          =>  "https://mind2excellence.se/klarna/php/push.php?id=" . $id,
];

$data = [
    'intent' => 'buy',
    'purchase_country' => 'SE',
    "purchase_currency" => "SEK",
    "locale" => "sv-SE",
    "order_amount" => 100 * $pr_price,
    "order_tax_amount" => 20 * $pr_price,
    "order_lines" => $ol, // json_encode($ol),
    'merchant_urls' => $mu,
];

$ch = curl_init($url);

$auth = 'K6587255_beba134cc2d5:m1cZyIZvZacAiDFU';

curl_setopt(
    $ch, 
    CURLOPT_HTTPHEADER, 
    array(
        'Content-Type: application/json', // for define content type that is json
        "Authorization: Basic " . base64_encode($auth),
    )
);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$result = curl_exec($ch);

debug_log("klarna json result : " . $result );

$res = json_decode($result);

if (isset($res->error_code)) {
	echo $res->error_code;
    debug_log("klarna error code " . $res->error_code);
}
else if(isset($res->html_snippet)) {
	echo $res->html_snippet;
}
else
	var_dump($result);
	
?>

