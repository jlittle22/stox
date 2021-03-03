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

    $un = $conn->real_escape_string($_SESSION["user"]);

    $sql = "SELECT cash FROM user_credentials WHERE username = BINARY'$un'";
    
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $row = mysqli_fetch_assoc($res);
        echo number_format($row['cash'], 2, '.', '');
    } else {
        echo number_format(0, 2, '.', '');
    }

    mysqli_close($conn);

?>

