<?php 
    session_start();
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == FALSE) {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Private Content</title>
</head>
<body>

    <h1>Private Content</h1>
    <h2>You're logged in as <?php echo $_SESSION['user']; ?></h2>
    <a href="logout.php">log out</a>
</body>
</html>