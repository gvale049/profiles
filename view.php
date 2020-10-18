<?php
session_start();
require_once "pdo.php";

if ( isset($_GET) ) {
    $profile_id = $_GET['profile_id'];
    $stmt = $pdo->query("SELECT first_name, last_name, email, headline, summary 
                        FROM Profile WHERE profile_id = $profile_id");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Gabriel Valencia - view page</title>
    <?php require_once('bootstrap.php'); ?>
</head>
<body>
<div class="container">
    <h1>Profile Information</h1>
    <?php

    if (isset($rows['first_name'])) {
        echo "<p>Empty</p>";
    }
    else {
        foreach ( $rows as $row ) {
            
            echo('<p> First Name: '.$row['first_name'].'</p>');
            echo('<p> Last Name: '.$row['last_name']. '</p>');
            echo('<p> Email: '.$row['email'].'</p>');
            echo('<p> Headline: <br>'.$row['headline'].'</p>');
            echo('<p> Summary: <br>'.$row['summary'].'</p>');
        }
    }
    ?>
    <a href="index.php">Done</a>
</div>
</body>
</html>