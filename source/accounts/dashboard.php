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
			width: 45%;
        }

		input[type=submit] {
			height: 40px;
			width: 15%;
		}

		.stock_list {
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

		.result:hover {
			cursor: pointer;
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

        .modal_back {
        	cursor: default;
        	display: none;
        	z-index: 1;
        	position: fixed;
        	left: 0;
        	top: 0;
        	width: 100%;
        	height: 100%;
        	background-color: rgba(0, 0, 0, 0.4);
        }

        .modal_content {
        	display: block;
        	position: relative;
        	z-index: 2;
            width: 50vw;
            height: 50vh;
            margin: 25vh 25vw;
            background-color: var(--white);
            border-radius: 5px;
        }

        .modal_data {
        	z-index: 3;
        	display: block;
        	position: absolute;
        	left: 10px;
        	top: 10px;
        	right: 10px;
        	bottom: 60px;
        	background-color: var(--green1);
        	border-radius: 5px;
        	overflow-y: scroll;
        }

        .stock_purchase {
        	z-index: 3;
        	display: block;
        	position: absolute;
        	left: 10px;
        	right: 10px;
        	bottom: 10px;
        }

        .close {
        	cursor: pointer;
        	display: inline-block;
        	z-index: 4;
        	position: absolute;
        	font-family: brass;
        	border: none;
        	color: var(--red);
        	background-color: var(--white)
        	height: 40px;
        	font-size: 40px;
        	right: 10px;
        	bottom: 0px;
        }

        #no_results {
        	text-align: center;
        	font-size: 25px;
        	color: rgba(0, 0, 0, 0.2);
        }

        h2 {
        	display: block;
        	position: absolute;
        	margin: 0;
        	top: 10px;
        	left: 10px;
        	right: 10px;
	        font-size: 3vw;

        }

	</style>
    <script 
        type="text/javascript" 
        src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"
    ></script>
    <script type="text/javascript">
    	function open_buy_view(elem) {
            elem.getElementsByClassName("modal_back")[0].style.display = "block";
    	}

    	function close_buy_view() {
    		var modals = document.getElementsByClassName("modal_back");
            for (var i = 0; i < modals.length; i++) {
            	modals[i].style.display = "none";
            }
    	}
 
        $.ajax({
                type: "GET",
                url: "display_owned_stocks.php",
                success: function(data) {
                    console.log(data);
                    $('#owned_stocks').html(data);
                }
        });

    </script>
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
    		<div class="stock_list">                
                <?php
                    if (!empty($_SESSION["ticker_res"])) {
                        foreach($_SESSION["ticker_res"] as $html) {
                    	    echo $html;
                        }
                    } else {
                    	echo "<p id='no_results'>No search results.</p>";
                    }
                ?>
    	    </div>
    	</div>
    	<div class="col">
    		<h2>Your Stocks</h2>
    		<div class="stock_list" id="owned_stocks">
    		</div>
    	</div>
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

<?php
    unset($_SESSION['owned_stocks']);
?>