<?php
session_start();
require_once "pdo.php";
require_once "util.php";

if ( isset($_GET) ) {
    $profile_id = $_GET['profile_id'];
    $stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id=:xyz");
    $stmt->execute(array(":xyz" => $profile_id));
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
        $educations = loadEdu($pdo, $_GET['profile_id']);
        echo('<p> Education </p> <ul>');
        
        foreach($educations as $education) {
            echo('<li>'.$education['year'].': '.$education['name'].'</li>');
        }

        echo('</ul>');

        $rank = 0;
        $positions = loadPos($pdo, $_GET['profile_id']);
        $pos = 0;
        echo('<p> Position </p> <ul>');
        
        foreach($positions as $position) {
            echo('<li>'.$position['year'].': '.$position['description'].'</li>');
        }
        echo('</ul>');
        echo('<a href="index.php">Done</a>');
    }
    
    ?>
        
</div>
</body>
</html>