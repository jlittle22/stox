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

    $un = $conn->real_escape_string($_POST["username"]);

    $pw = hash("sha256", $_POST["password"]);

    if (strlen($un) > 15) {
        $_SESSION["failure"] = "Username must be less than 15 chars.";
        header("Location: new.php");
        exit;
    }

    if (strlen($un) < 5) {
        $_SESSION["failure"] = "Username must be longer than 5 chars.";
        header("Location: new.php");
        exit;
    }

    if (strlen($_POST["password"]) < 5) {
        $_SESSION["failure"] = "Password must be longer than 5 chars.";
        header("Location: new.php");
        exit;
    }

    $exists = "SELECT * FROM user_credentials WHERE username= BINARY '$un'";
    $res = $conn->query($exists);

    if ($res->num_rows > 0) {
        $_SESSION["failure"] = "Username already in use";
        header("Location: new.php");
        exit;
    }
     
    $date = date("Y-m-d");
    $sql = "INSERT INTO user_credentials (username, password, cash, joined)" . 
           " VALUES ('$un', '$pw', 50000.0, '$date')";

    if ($conn->query($sql) === TRUE) {
      header("Location: login.php");
    } else {
      $_SESSION["failure"] = "Invalid username - password combination.";
      header("Location: new.php");
    }


    mysqli_close($conn); 
?>