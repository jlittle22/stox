<?php
    // Get all stocks owned by user from database
    // user, ticker, quan, buy price
    // for now:
    //    display ticker and (quan * curr_price)

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

    session_start();

    $servername = "localhost";
    $username = "id14883417_root";
    $password = "DBpass333!!!";
    $dbname = "id14883417_final";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $un = $conn->real_escape_string($_SESSION["user"]);

    $sql = "SELECT * FROM user_stocks WHERE user = BINARY'$un'";
    
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
    	// stonks!
        $data_arr = array(); // ["AAPL" => [numshares, name]]
    	while($row = mysqli_fetch_assoc($res)) {
    		$tick = $row['ticker'];
    		$quan = $row['num_shares'];
    		$name = $row['name'];
    		$price = $row['buy_price']; // TODO

    		if (array_key_exists($tick, $data_arr)) {
                $data_arr[$tick][0] += $quan;
    		} else {
    			$data_arr[$tick] = [$quan, $name];
    		}    	    
    	}
        

        $html_arr = array();
    	foreach ($data_arr as $tick => [$quan, $name]) {
            $api_resp = get_quote($tick);
            $quote = json_decode($api_resp)->{"Global Quote"};
	        if ($quote == new stdClass()) {
		        continue;
	        }

	        $price = (float)$quote->{"05. price"};
            $change = (float)$quote->{"09. change"};
            $color = "var(--green1);";
            if ($change < 0) {
                $color = "var(--red);";
            }
            $total =  $quan * $price;
            $total = number_format($total, 2, '.', '');

            $html = "<div class='result' onclick='open_buy_view(this);'>
                	<div class='ticker'>$tick</div>
                	<div class='company'>$name</div>
                	<div class='price' style='color: $color'>\$$total</div>
                	<div class='modal_back'>
                    	<div class='modal_content'>
                            <div class='modal_data'>
                                <p id='no_results'>No Data</p>
                    		</div>
                    		<form class='stock_purchase' action='sell.php' method='post'>
                    		    <input type='hidden' name='ticker' value='$tick'>
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
        $_SESSION['owned_stocks'] = $html_arr;
    } else {
    	// no stonks
    	echo "<p id='no_results'>You don't own any stocks.</p>";
    }

    mysqli_close($conn);

?>