<?php 
    function add_cash($username, $amount, $conn) {

        $sql = "UPDATE user_credentials SET cash = cash + $amount WHERE username='$username'";
 
        return $conn->query($sql);
    }
?>