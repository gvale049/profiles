<?php
    $dsn = "mysql:host=localhost;port=3306;dbname=misc";
    $username = "gabriel07@hotmail.co.uk";
    $password = "12345";

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>