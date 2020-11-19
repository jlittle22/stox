<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
</head>
<body>
	<h1>Register</h1>
    <form action="register.php" method="POST">
    	<input type="text" name="username" placeholder="username" maxlength="15">
    	<?php 
            if(isset($_SESSION["failure"])) {
                echo "<p>" . $_SESSION["failure"] . "</p>";
            }
    	?>
    	<input type="password" name="password" placeholder="password" maxlength="15">
    	<input type="submit">
    </form>
    <a href="login.php">Already have an account? Login here.</a>
</body>
</html>

<?php unset($_SESSION["failure"]); ?>