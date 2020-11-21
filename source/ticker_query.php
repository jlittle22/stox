<?php

session_start();

function get_quote($ticker) {
	$curl = curl_init();

    $input = curl_escape($curl, $ticker);

    curl_setopt_array($curl, [
	    CURLOPT_URL => "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=$input&apikey=W7H5Z6GSOUUIEI6Q",
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_FOLLOWLOCATION => true,
	    CURLOPT_ENCODING => "",
	    CURLOPT_MAXREDIRS => 10,
	    CURLOPT_TIMEOUT => 30,
	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_CUSTOMREQUEST => "GET",
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return $response; 
}

$curl = curl_init();

$input = curl_escape($curl,$_GET["ticker_query"]);

curl_setopt_array($curl, [
	CURLOPT_URL => "https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=$input&apikey=W7H5Z6GSOUUIEI6Q",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$json = json_decode($response, true);

$objectsArray = $json["bestMatches"];


$html_arr = array();
foreach ($objectsArray as $obj) {
	$ticker = $obj["1. symbol"];
	$name = $obj["2. name"];
	if (strlen($name) > 25) {
	    $name = substr($name, 0, 20) . " ...";
	}
	$quote = json_decode(get_quote($ticker))->{"Global Quote"};
	$price = $quote->{"05. price"};
    $price = number_format((float)$price, 2, '.', '');
    $change = (float)$quote->{"09. change"};
    $color = "var(--green1);";
    if ($change < 0) {
        $color = "var(--red);";
    }
	$html = "<div class='result'>
                	<div class='ticker'>$ticker</div>
                	<div class='company'>$name</div>
                	<div class='price' style='color: $color'>\$$price</div>
            </div>";
    array_push($html_arr, $html);
}


    $_SESSION["ticker_res"] = $html_arr;

    header("Location: dashboard.php");

?>