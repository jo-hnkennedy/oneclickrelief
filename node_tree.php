<?php

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
	"AWSAccessKeyId" => "AKIAI3TEYGLHORQ27CHQ",
	"AssociateTag" => "oneclickrelie-20",
	"ItemId" => $_GET["ASIN"],
	"IdType" => "ASIN",
	"ResponseGroup" => "BrowseNodes"
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

header('Content-Type: application/json');
die ($request_url);

$respXML = file_get_contents($request_url);

$xml = simplexml_load_string($respXML);

$json = json_encode($xml);

print var_dump($json->Items);
function object2array($object) { return @json_decode(@json_encode($object),1);  }
$nodes = object2array($xml->Items->Item->BrowseNodes);

$output_arr = [];
$output_arr["asin"] = (string) array_shift($nodes['BrowseNode'])['Name'];
//echo json_encode($xml);
?>
