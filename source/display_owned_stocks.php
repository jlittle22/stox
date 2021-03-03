<?php
    // Get all stocks owned by user from database
    // user, ticker, quan, buy price
    // for now:
    //    display ticker and (quan * curr_price)

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

    session_start();

    $servername = "localhost";
    $username = "u406651462_root";
    $password = "DBpass333!!!";
    $dbname = "u406651462_final";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $_SESSION['message'] = "Failed to connect to MySQL database.";
        header("Location: dashboard.php");
    }

    $un = $conn->real_escape_string($_SESSION["user"]);

    $sql = "SELECT * FROM user_stocks WHERE user = BINARY'$un' AND num_shares > 0";
    
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
    	// stonks!
        $data_arr = array(); // ["AAPL" => [numshares, name, total_buy_cost]]
    	while($row = mysqli_fetch_assoc($res)) {
    		$tick = $row['ticker'];
    		$quan = $row['num_shares'];
    		$name = $row['name'];
    		$buy_price = $row['buy_price'];

    		if (array_key_exists($tick, $data_arr)) {
                $data_arr[$tick][0] += $quan;
                $data_arr[$tick][2] += $buy_price * $quan;
    		} else {
    			$data_arr[$tick] = [$quan, $name, $buy_price * $quan];
    		}    	    
    	}
        

        $html_arr = array();
        $total_worth = 0;
    	foreach ($data_arr as $tick => [$quan, $name, $total_buy_cost]) {
            $api_resp = get_quote($tick);
            $quote = json_decode($api_resp)->{"Global Quote"};
	        if ($quote == new stdClass()) {
		        continue;
	        }

	        $price = (float)$quote->{"05. price"};
            $change = (float)$quote->{"09. change"};



            $percent_change = (float)$quote->{"10. change percent"};
            if ($percent_change > 0) {
                $percent_change = "+" . number_format($percent_change, 2, '.', '');
                $modal_change = "+" . number_format($change, 2, '.', '');
            } else {
                $percent_change = number_format($percent_change, 2, '.', '');
                $modal_change = number_format($change, 2, '.', '');
            }

            $open = (float)$quote->{"02. open"};
            $open = number_format($open, 2, '.', '');

            $high = (float)$quote->{"03. high"};
            $high = number_format($high, 2, '.', '');

            $low = (float)$quote->{"04. low"};
            $low = number_format($low, 2, '.', '');

            $color = "var(--green1);";
            if ($change < 0) {
                $color = "var(--red);";
            }
            $change = number_format($change, 2, '.', '');

            $total =  $quan * $price;
            $total_worth += $total;
            $total = number_format($total, 2, '.', '');

            $avg_buy = $total_buy_cost / $quan;
            $net_cash = ($price - $avg_buy) * $quan;
            $net_percent = ($net_cash / $total_buy_cost) * 100;

            $price = number_format($price, 2, '.', '');
            $avg_buy = number_format($avg_buy, 2, '.', '');
            if ($net_cash > 0) {
                $net_cash = "+" . number_format($net_cash, 2, '.', '');
                $net_percent = "+" . number_format($net_percent, 2, '.', '');
            } else {
                $net_cash = number_format($net_cash, 2, '.', '');
                $net_percent = number_format($net_percent, 2, '.', '');
            }
            
            $html = "<div class='result' onclick='open_buy_view(this);'>
                    <div class='ticker'>$tick</div>
                    <div class='company'>$name</div>
                    <div class='price' style='color: $color'>$price</div>
                    <div class='modal_back'>
                        <div class='modal_content'>
                            <div class='modal_data long_modal'>
                                <div class='company_data'>
                                    <!-- FIRST ROW -->
                                    <div class='modal_row' style='height: 80px;'>
                                        <div class='modal_col' style='width: 66%;'>
                                            <div class='company_name'>
                                                <p class='cash_text modal_version'>Company</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $name
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 34%;'>
                                            <div class='company_shares'>
                                                <p class='cash_text modal_version'>Shares</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $quan
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SECOND ROW -->
                                    <div class='modal_row' style='height: 70px;'>
                                        <div class='modal_col' style='width: 25%;'>
                                            <div class='modal_box' style='left: 10px;'>
                                                <p class='cash_text modal_version'>Ticker</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $tick
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 25%;'>
                                            <div class='modal_box'>
                                                <p class='cash_text modal_version'>Price</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $price
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 25%;'>
                                            <div class='modal_box'>
                                                <p class='cash_text modal_version'>Change ($)</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $modal_change
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 25%;'>
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
                                    <div class='modal_row' style='height: 70px;'>
                                        <div class='modal_col' style='width: 33%;'>
                                            <div class='modal_box' style='left: 10px'>
                                                <p class='cash_text modal_version'>Open</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $open
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 34%;'>
                                            <div class='modal_box'>
                                                <p class='cash_text modal_version'>High</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $high
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 33%;'>
                                            <div class='modal_box' style='right: 10px;'>
                                                <p class='cash_text modal_version'>Low</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $low
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FOURTH ROW -->
                                    <div class='modal_row' style='height: 94px;'>
                                        <div class='modal_col' style='width: 25%;'>
                                            <div class='modal_box' style='left: 10px; bottom: 10px;'>
                                                <p class='cash_text modal_version'>Equity</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $total
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 25%;'>
                                            <div class='modal_box' style='bottom: 10px;'>
                                                <p class='cash_text modal_version'>Avg. Price</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $avg_buy
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 25%;'>
                                            <div class='modal_box' style='bottom: 10px;'>
                                                <p class='cash_text modal_version'>Gain ($)</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $net_cash
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='modal_col' style='width: 25%;'>
                                            <div class='modal_box' style='bottom: 10px; right: 10px;'>
                                                <p class='cash_text modal_version'>Gain (%)</p>
                                                <div class='cash_block'>
                                                    <p class='modal_text'>
                                                        $net_percent
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- SMALL MODAL -->
                            <div class='modal_data small_modal'>
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
                                                <p class='cash_text modal_version'>Shares</p>
                                                <div class='cash_block'>
                                                    <p class='short_modal_text'>
                                                        $quan
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <!-- THIRD ROW -->
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

                                </div>
                            </div>
                            <form class='stock_purchase' action='sell.php' method='post'>
                                <input type='hidden' name='ticker' value='$tick'>
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
                                    value='SELL'
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
            echo $html;
    	}
        echo "GRABME" . number_format($total_worth, 2, '.', '');
        $_SESSION['owned_stocks'] = $html_arr;
        $_SESSION['total_worth'] = $total_worth;
    } else {
    	// no stonks
    	echo "<p class='no_results'>You don't own any stocks.</p>";
    }

    mysqli_close($conn);

?>