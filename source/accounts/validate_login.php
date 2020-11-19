<?php
    session_start();

    $servername = "localhost";
    $username = "id14883417_root";
    $password = "DBpass333!!!";
    $dbname = "id14883417_final";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $un = $conn->real_escape_string($_GET["username"]);
    $pw = hash("sha256", $_GET["password"]);

    $sql = "SELECT * FROM user_credentials WHERE" .
           " username= BINARY '$un' AND password='$pw' ";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
      $_SESSION['logged_in'] = TRUE;
      $_SESSION['user'] = $un;
      header("Location: private_content.php");
    } else {
      $_SESSION['logged_in'] = FALSE;
      header("Location: login.php");
    }


    mysqli_close($conn); 
?>