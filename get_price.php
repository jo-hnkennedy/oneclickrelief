<?php

function roundUpToAny($n,$x=1) {
    return (round($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
}

// Your Access Key ID, as taken from the Your Account page
$access_key_id = "AKIAI3TEYGLHORQ27CHQ";

// Your Secret Key corresponding to the above ID, as taken from the Your Account page
$secret_key = "KGt1oGFOJ2XW7azNd0wnga0CB4IkmiVSn6FTTUvF";

// The region you are interested in
$endpoint = "webservices.amazon.com";

$uri = "/onca/xml";

$params = array(
	"Service" => "AWSECommerceService",
	"Operation" => "ItemLookup",
	"ResponseGroup" => "Offers",
	"AWSAccessKeyId" => "AKIAI3TEYGLHORQ27CHQ",
	"AssociateTag" => "oneclickrelie-20",
	"IdType" => "ASIN",
	"ItemId" => $_GET["ASIN"]
);

// Set current timestamp if not set
if (!isset($params["Timestamp"])) {
    $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
}

// Sort the parameters by key
ksort($params);

$pairs = array();

foreach ($params as $key => $value) {
    array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
}

// Generate the canonical query
$canonical_query_string = join("&", $pairs);

// Generate the string to be signed
$string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

// Generate the signature required by the Product Advertising API
$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $secret_key, true));

// Generate the signed URL
$request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

$respXML = file_get_contents($request_url);

$xml = simplexml_load_string($respXML);

$price = $xml->Items->Item->OfferSummary->LowestNewPrice->Amount;

header('Application type: text/txt');
echo roundUpToAny($price/100.00);


?>
