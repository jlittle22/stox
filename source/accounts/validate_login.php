<?php
    session_start();

    $servername = "localhost";
    $username = "u406651462_root";
    $password = "DBpass333!!!";
    $dbname = "u406651462_final";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (strlen($_GET["username"]) == 0) {
      $_SESSION['failure'] = "Enter a username.";
      header("Location: login.php");
      exit;
    }

    if (strlen($_GET["password"]) == 0) {
      $_SESSION['failure'] = "Enter a password.";
      header("Location: login.php");
      exit;
    }


    $un = $conn->real_escape_string($_GET["username"]);
    $pw = hash("sha256", $_GET["password"]);

    $sql = "SELECT * FROM user_credentials WHERE" .
           " username= BINARY '$un' AND password='$pw' ";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
      $_SESSION['logged_in'] = TRUE;
      $_SESSION['user'] = $un;
      $res_row = mysqli_fetch_assoc($result);
      $_SESSION['cash'] = $res_row['cash'];
      $_SESSION['joined'] = $res_row['joined'];
      header("Location: dashboard.php");
    } else {
      $_SESSION['logged_in'] = FALSE;
      $_SESSION['failure'] = "Incorrect username and password combination.";
      header("Location: login.php");
    }


    mysqli_close($conn); 
?>