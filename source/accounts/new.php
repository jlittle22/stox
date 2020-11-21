<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="site.css"/>
	<title>Register | Stox</title>

</head>
<body class="background">
        <div class="page_cont">
	    <h1>Stox</h1>
        <h2>Register</h2>
        <div class="form_container">
            <form class="user_cred" action="register.php" method="POST">
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
                        echo "<div class='error'>" . $_SESSION["failure"] . "</p>\n";
                    }
            	?>
                <a style="text-align: center;" href="login.php">
                    <p>Already have an account? Login here.</p>
                </a>
                <input type="submit" value="Register"/>
            </form>
        </div>
    </div>
</body>
</html>

<?php unset($_SESSION["failure"]); ?>