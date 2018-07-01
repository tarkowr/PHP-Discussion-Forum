<?php 
    include 'connect.php';
    doDB();

    session_destroy();
    header("Location: userLogin.html");
    exit;
?>