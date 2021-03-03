<?php

session_start();

function get_quote($ticker) {
	$curl = curl_init();

    $input = curl_escape($curl, $ticker);

    curl_setopt_array($curl, [
	    CURLOPT_URL => "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=$input&apikey=NOTAREALKEY:)",
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

    $percent_change = (float)$quote->{"10. change percent"};
    $percent_change = number_format($percent_change, 2, '.', '');

    $change = (float)$quote->{"09. change"};
    $color = "var(--green1);";
    if ($change < 0) {
        $color = "var(--red);";
    }

    $change = number_format($change, 2, '.', '');

    $open = (float)$quote->{"02. open"};
    $open = number_format($open, 2, '.', '');

    $high = (float)$quote->{"03. high"};
    $high = number_format($high, 2, '.', '');

    $low = (float)$quote->{"04. low"};
    $low = number_format($low, 2, '.', '');

	$html = "<div class='result' onclick='open_buy_view(this);'>
                    <div class='ticker'>$ticker</div>
                    <div class='company'>$name</div>
                    <div class='price' style='color: $color'>\$$price</div>
                    <div class='modal_back'>
                        <div class='modal_content'>
                            <div class='modal_data long_modal'>
                                <!-- Put details about the stock in this block -->
                                <div class='company_data'>
                                    <!-- FIRST ROW -->
                                    <div class='modal_row' style='height: 80px;'>
                                        <div class='modal_col' style='width: 67%;'>
                                            <div class='company_name'>
                                                <p class='cash_text modal_version'>Company</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $name
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 33%;'>
                                            <div class='company_shares'>
                                                <p class='cash_text modal_version'>Ticker</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $ticker
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SECOND ROW -->
                                    <div class='modal_row' style='height: 134px;'>
                                        <div class='modal_col' style='width: 33%;'>
                                            <div class='modal_box' style='left: 10px;'>
                                                <p class='cash_text modal_version'>Price</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $price
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 34%;'>
                                            <div class='modal_box'>
                                                <p class='cash_text modal_version'>Change ($)</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $change
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 33%;'>
                                            <div class='modal_box' style='right: 10px;'>
                                                <p class='cash_text modal_version'>Change (%)</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $percent_change
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- THIRD ROW -->
                                    <div class='modal_row' style='height: 100px;'>
                                        <div class='modal_col' style='width: 33%;'>
                                            <div class='modal_box' style='left: 10px; bottom: 10px;'>
                                                <p class='cash_text modal_version'>Open</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $open
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 34%;'>
                                            <div class='modal_box' style='bottom: 10px;'>
                                                <p class='cash_text modal_version'>High</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $high
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 33%;'>
                                            <div class='modal_box' style='right: 10px; bottom: 10px;'>
                                                <p class='cash_text modal_version'>Low</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $low
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- SHORT MODAL -->
                            <div class='modal_data small_modal'>
                                <!-- Put details about the stock in this block -->
                                <div class='company_data small_data'>
                                    <!-- FIRST ROW -->
                                    <div class='modal_row' style='height: 85px;'>
                                        <div class='modal_col' style='width: 100%;'>
                                            <div class='modal_box'>
                                                <p class='cash_text modal_version'>Company</p>
                                                <div class='cash_block'>
                                                    <p class='short_modal_text'>
                                                        $name
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <!-- SECOND ROW -->
                                    <div class='modal_row' style='height: 85px;'>
                                        <div class='modal_col' style='width: 100%;'>
                                            <div class='modal_box'>
                                                <p class='cash_text modal_version'>Price</p>
                                                <div class='cash_block'>
                                                    <p class='short_modal_text'>
                                                        $price
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <!-- THIRD ROW -->
                                    <div class='modal_row' style='height: 85px;'>
                                        <div class='modal_col' style='width: 100%;'>
                                            <div class='modal_box'>
                                                <p class='cash_text modal_version'>Ticker</p>
                                                <div class='cash_block'>
                                                    <p class='short_modal_text'>
                                                        $ticker
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <form class='stock_purchase' action='buy.php' method='post'>
                                <input type='hidden' name='ticker' value='$ticker'>
                                <input type='hidden' name='name' value='$name'>
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
                                    class='form_sub'
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