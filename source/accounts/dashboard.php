<?php 
    session_start();
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == FALSE) {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="site.css"/>
	<title>Dashboard | Stox</title>
	<style type="text/css">
		.logout {
			position: absolute;
			bottom: 10px;
			width: 100%;
			display: block;
		    text-align: center;
		    margin-left: auto;
		    margin-right: auto;
		}

		.dashboard_item {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			display: flex;
			justify-content: flex-start;
		}

		form { 
            width: 100%;
		}


        ::placeholder {
			color: rgba(0, 0, 0, 0.2);
        }

        input[type=text] {
			margin: 0 10px;
        }

		input[type=submit] {
			height: 40px;
			width: 15%;
		}

		#search_results {
			position: absolute;
			display: block;
			background-color: rgba(0, 0, 0, 0.1);
			border-radius: 5px;
            top: 60px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            overflow-y: auto;
		}

		.result {
			display: flex;
			justify-content: flex-start;
			position: relative;
			left: 10px;
			right: 0px;
			background-color: rgba(0, 0, 0, 0.2);
			margin: 10px 20px 0 0; 
			border-radius: 2px;
			height: 6vh;
		}

		.ticker {
			display: inline-block;
			min-width: 6vw;
			padding: 0 10px;
			height: 4vh;
			line-height: 4vh;
			background-color: var(--white);
			border-radius: 2px;
			text-align: center;
			color: var(--green1);
			font-size: 20px;
			font-family: brass;
			margin: 1vh 0 1vh 10px;
		}

		.company {
			display: inline-block;
			float: left;
			height: 4vh;
			font-family: brass;
			font-size: 2vw;
			color: var(--white);
			margin: 1vh 0 1vh 10px;
		}
  
        .price {
        	display: inline-block;
        	position: absolute;
			height: 4vh;
			line-height: 4vh;
			background-color: var(--white);
			padding: 0 4px;
			border-radius: 2px;
			text-align: center;
			color: var(--red);
			font-size: 20px;
			font-family: brass;
			margin: 1vh 0 1vh 0px;
			right: 10px;
        }

	</style>
</head>
<body class="background">
    <h1 style="margin: 0;">Stox</h1>

    <div class="row">
    	<div class="col">
    		<div class="dashboard_item">
    			<form action="ticker_query.php" method="GET">
    				<input 
    				    type="text" 
    				    name="ticker_query"
    				    placeholder="AAPL" 
    				    onblur="this.placeholder = 'AAPL'"
            	        onfocus="this.placeholder =''" 
    				>
    				<input type="submit" name="ticker_submit" value="Search">
    			</form>
    		</div> 
    		<div id="search_results">
                <?php
                    if (isset($_SESSION["ticker_res"])) {
                        foreach($_SESSION["ticker_res"] as $html) {
                    	    echo $html;
                        }
                    }
                ?>
    	    </div>
    	</div>
    	<div class="col">Sell Stocks</div>
    </div>

    <div class="row">
    	<div class="col">Account Info</div>
    	<div class="col">Finance News</div>
    </div>



    <div class="logout">
        <a href="logout.php">
    	    <p>Log out.</p>
        </a>
    </div>
</body>
</html>