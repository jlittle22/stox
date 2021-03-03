<?php
    require "change_user_cash.php";
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

    $ticker = $_POST['ticker'];
    $name = $_POST['name'];
    $quantity = (int)$_POST['quan'];
    if (empty($_POST['quan']) || $quantity <= 0) {
        $_SESSION['message'] = "Cannot buy a non-positive number of shares.";
        header("Location: dashboard.php");
        exit;
    }
    $api_resp = get_quote($ticker);
    $quote = json_decode($api_resp)->{"Global Quote"};
	if ($quote == new stdClass()) {
		$_SESSION['message'] = "Sorry - something went wrong" .
                               " during the API request. We're" .
                               " limited to 500 requests per day.";
		header("Location: dashboard.php");
        exit;
	}
	$price = (float)$quote->{"05. price"};

	$cost = $price * $quantity;

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

    // get current amount of cash
    $cash_sql = "SELECT cash FROM user_credentials WHERE username='$un'";
    $cash_res = $conn->query($cash_sql);
    if ($cash_res != TRUE) {
        $_SESSION['message'] = "Failed to get user information.";
        mysqli_close($conn);
        header("Location: dashboard.php");    
    }

    $cash_row = mysqli_fetch_assoc($cash_res);

    $cash = $cash_row['cash'];

    if ($cash - $cost < 0) {
        $_SESSION['message'] = "You can't spend more money than you have.";
        mysqli_close($conn);
        header("Location: dashboard.php");
        exit;
    }

	
	$sql = "INSERT INTO user_stocks (user, ticker, name, num_shares, buy_price) VALUES ('$un', '$ticker', '$name', '$quantity', '$price')";

	if (!($conn->query($sql) == TRUE && add_cash($un, -1 * $cost, $conn) == TRUE)) {
		$_SESSION['message'] = "Failed to purchase $quantity shares of $ticker " .
                               "for \$$cost.";
	}

    mysqli_close($conn);
    
    header("Location: dashboard.php");
?>
