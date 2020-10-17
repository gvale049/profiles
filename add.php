<?php
    session_start();
    require_once("pdo.php");

    if ( isset($_POST['cancel']) ) {
        echo "hello";
        $_SESSION['cancel'] = $_POST['cancel'];
        header("location: index.php");
        return;
    }
?>
<!DOCTYPE html>
<html>
<head>
   <title> Gabriel Valencia - add page </title>
   <?php require_once("bootstrap.php"); ?>
</head>
<body>
<div class="container"> 
    <h1> Adding Profile for UMSI </h1>
    <form method="post">
        <p> First Name:
            <input type="text" name="first_name" size="60"/></p>
        <p> Last Name: 
            <input type="text" name="last_name" size="60"/></p>
        <p> Email:
            <input type="text" name="email" size="30/"/></p>
        <p> Headline:<br/>
            <input type="text" name="headline" size="80"/></p>
        <p> Summary: </br>
            <textarea name="summary" rows="8" cols="80"></textarea></p>
        <p>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
</div>
</body>
</html>
