<?php
    require "change_user_cash.php";
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

    $ticker = $_POST['ticker'];
    $name = $_POST['name'];
    $quantity = (int)$_POST['quan'];
    $api_resp = get_quote($ticker);
    $quote = json_decode($api_resp)->{"Global Quote"};
	if ($quote == new stdClass()) {
		// handle this case - probably set HTML to failure message 
		$_SESSION['message'] = "Probably out of API requests for today";
		header("Location: dashboard.php");
	}
	$price = (float)$quote->{"05. price"};

	$cost = $price * $quantity;

    $servername = "localhost";
    $username = "id14883417_root";
    $password = "DBpass333!!!";
    $dbname = "id14883417_final";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $un = $conn->real_escape_string($_SESSION["user"]);
	
	$sql = "INSERT INTO user_stocks (user, ticker, name, num_shares, buy_price) VALUES ('$un', '$ticker', '$name', '$quantity', '$price')";

	if (add_cash($un, -1 * $cost, $conn) == TRUE && $conn->query($sql) == TRUE) {
		$_SESSION['message'] = "Successfully purchased $quantity shares of $ticker " .
		                       "for \$$cost.";
	} else {
		$_SESSION['message'] = "Failed to purchase $quantity shares of $ticker " .
		                       "for \$$cost.";
	}

    mysqli_close($conn);
    
    header("Location: dashboard.php");
?>
