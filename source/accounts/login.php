<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login | Stox</title>
    <link rel="stylesheet" href="site.css"/>
    <style type="text/css">
        #test {
        	display: block;
        	width: 300px;
        	border: 2px solid red;
        	margin: auto;
        }
    </style>
</head>
<body class="background">
	<div class="page_cont">
	    <h1>Stox</h1>
        <h2>Login</h2>
        <div class="form_container">
            <form class="user_cred" action="validate_login.php" method="GET">
            	<input 
            	    type="text" 
            	    name="username" 
            	    placeholder="username" 
            	    onblur="this.placeholder = 'username'"
            	    onfocus="this.placeholder =''" 
            	    maxlength="15"
            	>
            	<input 
            	    type="password" 
            	    name="password" 
            	    placeholder="password" 
            	    onblur="this.placeholder = 'password'"
            	    onfocus="this.placeholder =''" 
            	    maxlength="15"
            	>
            	<?php 
                    if(isset($_SESSION["failure"])) {
                        echo "<div class='error'>" . $_SESSION["failure"] . "</div>\n";
                    }
            	?>
            	<a style="text-align: center; cursor: pointer;" href="new.php">
        	        <p>Need an account? Register here.</p>
                </a>
                <input type="submit" value="Login" />
            </form>
        </div>

    </div>

    
</body>
</html>
<?php unset($_SESSION["failure"]); ?>
