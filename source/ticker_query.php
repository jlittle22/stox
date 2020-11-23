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

if (empty($input)) {
	header("Location: dashboard.php");
	exit;
}

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
	$api_resp = get_quote($ticker);
	$quote = json_decode($api_resp)->{"Global Quote"};
	if ($quote == new stdClass()) {
		continue;
	}
	$price = $quote->{"05. price"};
    $price = number_format((float)$price, 2, '.', '');
    $change = (float)$quote->{"09. change"};
    $color = "var(--green1);";
    if ($change < 0) {
        $color = "var(--red);";
    }
	$html = "<div class='result' onclick='open_buy_view(this);'>
                	<div class='ticker'>$ticker</div>
                	<div class='company'>$name</div>
                	<div class='price' style='color: $color'>\$$price</div>
                	<div class='modal_back'>
                    	<div class='modal_content'>
                            <div class='modal_data'>
                    			<!-- Put details about the stock in this block -->
                    		</div>
                    		<form class='stock_purchase' action='buy.php' method='post'>
                    		    <input type='hidden' name='ticker' value='$ticker'>
                    		    <input type='hideen' name='name' value='$name'>
                    			<input
                    			    style='color: black;'
                                    type='text'
                                    class='quan'
                                    name='quan' 
                                    placeholder='0' 
                                    onblur='this.placeholder = \"0\"'
                                    onfocus='this.placeholder =\"\"' 
                                >
                    			<input 
                    			    style='border: 1px solid var(--green1);'
                    			    type='submit' 
                    			    value='BUY'
                    			>
                    		</form>
                    		<button 
                    		    class='close' 
                    		    onclick='close_buy_view();event.stopPropagation()'
                    		>
                    	        X
                    	    </button>
                    	</div>
                    </div>
            </div>";

    array_push($html_arr, $html);
}


    $_SESSION["ticker_res"] = $html_arr;

    header("Location: dashboard.php");

?>