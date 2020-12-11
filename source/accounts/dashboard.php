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
			width: 95px;
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
			height: 50px;
		}

		.result:hover {
			cursor: pointer;
		}

		.ticker {
			display: table-cell;
            vertical-align: middle;
			min-width: 6vw;
			padding: 0 10px;
			height: 34px;
			line-height: 34px;
			background-color: var(--white);
			border-radius: 2px;
			text-align: center;
			color: var(--green1);
			font-size: 20px;
			font-family: brass;
			margin: 8px 0 8px 10px;
		}

		.company {
			display: table-cell;
            vertical-align: middle;
			float: left;
			height: 34px;
            line-height: 34px;
			font-family: brass;
			font-size: 2vw;
			color: var(--white);
			margin: 8px 0 8px 10px;
		}
  
        .price {
        	display: table-cell;
            vertical-align: middle;
        	position: absolute;
			height: 34px;
			line-height: 34px;
			background-color: var(--white);
			padding: 0 4px;
			border-radius: 2px;
			text-align: center;
			color: var(--red);
			font-size: 20px;
			font-family: brass;
			margin: 8px 0 8px 0px;
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

        .no_results {
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
	        font-size: 45px;
        }

        #error_modal_back {
            display: block;
        }

        #error_modal_content {
            width: 25vw;
            height: 25vh;
            margin: 37.5vh 37.5vw;
            overflow-y: scroll;
        }

        .error_title {
            position: absolute;
            top: 10px;
            left: 10px;
            height: 35px;
            color: var(--red);
            font-size: 35px;
            margin: 0;
        }

        #exit_close {
            bottom: unset;
            top: 0;
            right: 0;
        }

        .error_msg {
            font-size: 25px;
            color: black;
            position: absolute;
            top: 35px;
            left: 10px;
            right: 10px;
        }

        #account {
            display: block;
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            height: 45px;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        #account_data {
            display: block;
            position: absolute;
            left: 10px;
            bottom: 10px;
            right: 10px;
            top: 65px;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow-y: scroll;
        }

        .scale_row {
            width: 100%;
            height: 95px;
            padding: 10px 0 0 0;
            display: flex;
        }
        
        .scale_col {
            position: relative;
            display: block;
            float: left;
            width: 48%;
            margin: 0 auto;
            height: 95px;
            background-color: rgba(0, 0, 0, .1);
            border-radius: 5px;
        }

        .cash_text {
            font-size: 20px;
            display: inline-block;
            position: absolute;
            margin: 0;
            top: 2px;
            left: 10px;
        }

        .modal_version {
            font-size: 18px;
        }

        .cash_block {
            display: flex;
            position: absolute;
            top: 23px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            align-items:center;
            justify-content:center;
        }

        .data_text {
            display: inline-block;
            text-align: center;
            font-family: brass;
            font-size: 3vw;
            color: var(--dark-green);
        }

        .company_data {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            display: block;
            min-height: 314px;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        
        .small_data {
            min-height: 240px;
        }

        .company_name {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 5px;
            bottom: 0px;
            display: block;
            height: 70px;
            background-color: rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        .company_shares {
            position: absolute;
            top: 10px;
            left: 5px;
            right: 10px;
            bottom: 0px;
            display: block;
            height: 70px;
            background-color: rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        .modal_row {
            position: relative;
            display: flex;
            width: 100%;
            /* parameterized height */
        }

        .modal_col {
            position: relative;
            top: 0;
            bottom: 0;
            display: block;
            height: 100%;
            /* parameterized width */
        }

        .modal_box {
            position: absolute;
            top: 10px;
            left: 5px;
            bottom: 0px;
            right: 5px;
            display: block;
            background-color: rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        .modal_text {
            font-size: 1.5vw;
            color: var(--dark-green);
        }

        .short_modal_text {
            color: var(--dark-green);
            font-size: 25px;
            overflow-x: scroll;
            white-space: nowrap;
        }

        .news_res {
            display: block;
            position: relative;
            left: 10px;
            right: 0px;
            border-radius: 2px;
            background-color: rgba(0, 0, 0, 0.1);
            margin: 10px 20px 10px 0; 
            height: 200px;
        }

        .news_source {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            height: 80px;
            background-color: rgba(0,0,0, 0.2);
            overflow-y: scroll;
            border-radius: 2px;
        }

        .news_desc {
            position: absolute;
            display: table-cell;
            vertical-align: middle;
            top: 100px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background-color: rgba(0, 0, 0, 0.0);
            border-radius: 2px;
        }

        .news_title {
            margin: 0;
            font-size: 22px;
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            color: var(--white);
        }

        .news_comp {
            display: table-cell;
            vertical-align: middle;
            margin: 0;
            position: absolute;
            font-size: 20px;
            top: 55px;
            left: 5px;
            right: 5px;
            bottom:5px;
            color: var(--red);
        }

        .news_text {
            margin: 0;
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            font-size: 17px;
            overflow-y: scroll;
            color: rgba(0, 0, 0, 0.8);
        }
        #short_worth, #short_change, #short_invest, #short_stocks {
            display: none;
        }

        .small_modal {
            display: none;
        }

        .long_modal {
            display: block;
        }
        @media (max-width: 800px) {
            #member_since {
                display: none;
            }

            #short_worth, #short_change, #short_invest, #short_stocks {
                display: inline-block;
            }

            #long_worth, #long_change, #long_invest, #long_stocks {
                display: none;
            }
        }

	</style>
    <script 
        type="text/javascript" 
        src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"
    ></script>
    <script type="text/javascript">
        function resizeElems() {
            let w = $(window).width()
            if (w < 750) {
                $('#search').attr("placeholder", "AAPL");
                $('#search').attr("onblur", "AAPL");
            } else {
                $('#search').attr("placeholder", "Ex: AAPL");
                $('#search').attr("onblur", "Ex: AAPL");
            }
            if (w < 900) {
                var elems = document.getElementsByClassName("company");
                for (var i = 0; i < elems.length; i++) {
                    elems[i].style.display = 'none';
                }
            } else {
                var elems = document.getElementsByClassName("company");
                for (var i = 0; i < elems.length; i++) {
                    elems[i].style.display = 'block';
                }
            }
            if (w < 1040) {
                var elems1 = document.getElementsByClassName("long_modal");
                var elems2 = document.getElementsByClassName("small_modal");
                for (var i = 0; i < elems.length; i++) {
                    elems1[i].style.display = 'none';
                    elems2[i].style.display = 'block';
                }

                var submits = document.getElementsByClassName("form_sub");
                for (var i = 0; i < submits.length; i++) {
                    submits[i].style.width = '60px';
                }

                var quans = document.getElementsByClassName("quan");
                for (var i = 0; i < submits.length; i++) {
                    quans[i].style.width = '40%';
                }
            } else {
                var elems2 = document.getElementsByClassName("long_modal");
                var elems1 = document.getElementsByClassName("small_modal");
                for (var i = 0; i < elems.length; i++) {
                    elems1[i].style.display = 'none';
                    elems2[i].style.display = 'block';
                }

                var submits = document.getElementsByClassName("form_sub");
                for (var i = 0; i < submits.length; i++) {
                    submits[i].style.width = '';
                }

                var quans = document.getElementsByClassName("quan");
                for (var i = 0; i < submits.length; i++) {
                    quans[i].style.width = '';
                }
            }
            if (w < 1270) {
                var elems = document.getElementsByClassName("news_comp");
                for (var i = 0; i < elems.length; i++) {
                    elems[i].style.display = 'none';
                }
            } else {
                var elems = document.getElementsByClassName("news_comp");
                for (var i = 0; i < elems.length; i++) {
                    elems[i].style.display = 'block';
                }
            }
        }

        // set class quan width: 20%
        // set class form_sub width: 30px;

        $(window).resize(function() {
            resizeElems();
        });
        
        window.onload = () => { resizeElems(); };

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
                    data = data.split("GRABME");
                    $('#owned_stocks').html(data[0]);
                    console.log(data[1]);
                    if (typeof data[1] == 'undefined') {
                        data[1] = "0.00";
                    }
                    $('#invest_val').html("$" + data[1]);
                    $.ajax({
                        type: "GET",
                        url: "get_user_info.php",
                        success: function(more_data) {
                            $('#cash_val').html("$" + more_data);
                            let d1 = parseFloat($('#invest_val').html().slice(1));
                            let d2 = parseFloat(more_data);
                            var net = d1 + d2;
                            console.log(d1, d2, net);
                            $('#total_val').html("$" + net.toFixed(2));
                            let percent = ((net - 50000.0) / 50000.0) * 100;
                            $('#percent_val').html((percent > 0 ? "+" : "") + 
                                                   percent.toFixed(2) + "%");
                            resizeElems();
                        }
                    });
                }
        });
        
        $.ajax({
            type: "GET",
            url: "get_news.php",
            success: function(data) {
                $('#news_list').html(data);
            }
        })


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
    				    placeholder="Ex: AAPL" 
    				    onblur="this.placeholder = 'Ex: AAPL'"
            	        onfocus="this.placeholder =''" 
                        id="search"
    				>
    				<input type="submit" name="ticker_submit" value="Search">
    			</form>
    		</div> 
    		<div class="stock_list" id="search_results">              
                <?php
                    if (!empty($_SESSION["ticker_res"])) {
                        foreach($_SESSION["ticker_res"] as $html) {
                    	    echo $html;
                        }
                    } else {
                    	echo "<p class='no_results'>No search results.</p>";
                    }
                ?>
    	    </div>
    	</div>
    	<div class="col">
    		<h2 id="long_stocks">Your Stocks</h2>
            <h2 id="short_stocks">Stocks</h2>
    		<div class="stock_list" id="owned_stocks">
                
                <p class='no_results'>Loading...</p>
    		</div>
    	</div>
    </div>

    <div class="row">
    	<div class="col">
            <div id='account'>
                <h2 style="text-align: left;
                           font-size: 30px;
                           line-height: 30px;" 
                >
                    <?php echo $_SESSION['user']; ?>
                </h2>
                <h2 
                    style="text-align: right;
                           color: rgba(0, 0, 0, 0.1);
                           line-height: 30px; 
                           font-size: 1.5vw;" 
                    id="member_since"
                >
                    Member since <?php echo $_SESSION['joined'] ?>
                </h2>
            </div>
            <div id='account_data'>
                <!-- Other info -->
                <div class='scale_row'>
                    <div class='scale_col'>
                        <p class="cash_text">Cash</p>
                        <div class="cash_block">
                            <div class="data_text" id="cash_val">
                            </div>
                        </div>
                    </div>
                    <div class='scale_col'>
                        <p class="cash_text" id="long_invest">Investments</p>
                        <p class="cash_text" id="short_invest">Invested</p>
                        <div class="cash_block">
                            <div class="data_text" id="invest_val">
                            </div>
                        </div>
                    </div>
                </div>
                <div class='scale_row'>
                    <div class='scale_col'>
                        <p class="cash_text" id="long_worth">Total Worth</p>
                        <p class="cash_text" id="short_worth">Total</p>

                        <div class="cash_block">
                            <div class="data_text" id="total_val">
                            </div>
                        </div>
                    </div>
                    <div class='scale_col'>
                        <p class="cash_text" id="long_change">Percent Change</p>
                        <p class="cash_text" id="short_change">Change</p>
                        <div class="cash_block">
                            <div class="data_text" id="percent_val">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    	<div class="col">
            <h2>News</h2>
            <div class="stock_list" id="news_list">
                <!-- ARTICLES GO HERE -->
            </div>
        </div>
    </div>



    <div class="logout">
        <a href="logout.php">
    	    <p>Log out.</p>
        </a>
    </div>

<?php
    if (isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        $html = "<div id='error_modal_back' class='modal_back'>
                  <div id='error_modal_content' class='modal_content'>
                      <p class='error_title'>Error</p>
                      <button 
                          class='close' 
                          id='exit_close'
                          onclick='close_buy_view();event.stopPropagation()'
                      >
                          X
                      </button>
                      <p id='error_msg' class='error_msg'>
                          $msg
                      </p>
                  </div>
              </div>";
        echo $html;
    }

 
?>

</body>
</html>

<?php
    unset($_SESSION['message']);
    unset($_SESSION['owned_stocks']);
    unset($_SESSION["ticker_res"]);
?>