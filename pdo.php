<?php
    $dsn = "mysql:host=localhost;port=8889;dbname=misc";
    $username = "gvale049";
    $password = "12345";

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>