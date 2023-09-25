
<?php

include "getparam.php";

$id = getparam("id", 666);

// $url = 'https://api.playground.klarna.com/payments/v1/sessions';

$url = 'https://api.klarna.com/checkout/v3/orders';

$ol = [];
$ol[] = [
    "name" => "Kurspaket Ett",
    "quantity" => 1,
    "unit_price" => 200,
    "tax_rate" => 2500,
    "total_amount" => 200,
    "total_tax_amount" => 40,
];

$mu = [
    "terms"         =>  "https://mind2excellence.se/klarna/php/terms.php?id=" . $id,
    "checkout"      =>  "https://mind2excellence.se/klarna/php/checkout.php?id=" . $id,
    "confirmation"  =>  "https://mind2excellence.se/klarna/php/confirmation.php?id=" . $id,
    "push"          =>  "https://mind2excellence.se/klarna/php/push.php",
];

$data = [
    'intent' => 'buy',
    'purchase_country' => 'SE',
    "purchase_currency" => "SEK",
    "locale" => "sv-SE",
    "order_amount" => '200',
    "order_tax_amount" => '40',
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

$res = json_decode($result);

if (isset($res->error_code)) {
	echo $res->error_code;
}
else if(isset($res->html_snippet)) {
	echo $res->html_snippet;
}
else
	var_dump($result);
	
?>

