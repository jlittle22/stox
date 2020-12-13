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
    if (empty($_POST['quan']) || $quantity <= 0) {
        $_SESSION['message'] = "Cannot sell a non-positive number of shares.";
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
    $username = "id14883417_root";
    $password = "DBpass333!!!";
    $dbname = "id14883417_final";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $_SESSION['message'] = "Failed to connect to MySQL database.";
        header("Location: dashboard.php");
    }

    $un = $conn->real_escape_string($_SESSION["user"]);
	
    // Sum to get total number of shares
    $sum_sql = "SELECT SUM(num_shares), SUM(buy_price * num_shares) FROM user_stocks WHERE user='$un' AND ticker='$ticker'";

    $sum_res = $conn->query($sum_sql);
    if ($sum_res != TRUE) {
        $_SESSION['message'] = "Failed to get number of shares" .
                               " and total cost from database.";
        mysqli_close($conn);
        header("Location: dashboard.php");
    }
   
    $row = mysqli_fetch_assoc($sum_res);
    $total_shares = (int)$row["SUM(num_shares)"];
    $total_cost = (float)$row["SUM(buy_price * num_shares)"];


    // Remove the quantity from number of shares
    $post_sale_shares = $total_shares - $quantity;

    // validate that it's non negative
    if ($post_sale_shares < 0) {
        $_SESSION['message'] = "You can't sell more shares than you own.";
        mysqli_close($conn);
        header("Location: dashboard.php");
    }

    // Delete all rows in DB containing user, ticker.
    $del_sql = "DELETE FROM user_stocks WHERE user='$un' AND ticker='$ticker'";
    if ($conn->query($del_sql) != TRUE) {
        $_SESSION['message'] = "Failed to remove solds shares from database." .
                               " No money was added to your account.";
        mysqli_close($conn);
        header("Location: dashboard.php");
    }

    // Insert a single row containing user, ticker, new total quan, and avg price
    if ($post_sale_shares > 0) {
        $avg_price = $total_cost / (float)$total_shares;
        $insert_sql = "INSERT INTO user_stocks (user, ticker, name, num_shares, buy_price) VALUES ('$un', '$ticker', '$name','$post_sale_shares', '$avg_price')";

        if ($conn->query($insert_sql) != TRUE) {
            $_SESSION['message'] = "Failed to update stocks on your account." .
                                   " Contact system administrator.";
            mysqli_close($conn);
            header("Location: dashboard.php");
        }
    }

	if (!add_cash($un, $cost, $conn)) {
            $_SESSION['message'] = "Failed to add money to your account." .
                                   " Contact system administrator.";
	}

    mysqli_close($conn);
    
    header("Location: dashboard.php");
?>
