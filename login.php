<?php

require_once("pdo.php");
require_once("util.php");

session_start();

unset($_SESSION['name']);
unset($_SESSION['user_id']);
unset($_SESSION['success']);

$salt = 'XyZzy12*_';

if (isset($_POST['cancel'])) {
    $_SESSION['cancel'] = $_POST['cancel'];
    header("location: index.php");
    return;
}

if (isset( $_POST['email']) && isset($_POST['pass'])) {
    
    $check = hash('md5', $salt.$_POST['pass']);
    
    $stmt = $pdo->prepare('SELECT user_id, name FROM users
                            WHERE email = :em AND password = :pw');
    $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];

        header("Location: index.php");
        // error_log location: /Applications/Ampps/apache/logs/error_log
        error_log("Login fail ".$_SESSION['error']." $check");
        return;
    }

    if ($row === false) {
        $_SESSION['error'] = "Incorrect Password";
        
        header("Location: login.php");
        // error_log location: /Applications/Ampps/apache/logs/error_log
        error_log("Login fail ".$_SESSION['error']." $check");
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gabriel Valencia - login page</title>
    <?php require_once "bootstrap.php" ?>
</head>
<body>
<div class="container">
    <h1> Please Log In</h1>
    <?php
    /*
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : false;

    if ( $error !== false ) {
            echo($error);
            unset($_SESSION['error']);
    }
    */
    flashMessages();
    ?>
    
    <form method="POST" action="login.php">
        <label for="email">Email</label>
        <input type="text" name="email" id="email"><br/>
        <label for="id_1723">Password</label>
        <input type="password" name="pass" id="id_1723"><br/>
        <input type="submit" onclick="return doValidate();" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <script>
        //function will validate the email and password
        function doValidate() {
            console.log('Validating...');
            try {
                addr = document.getElementById('email').value;
                pw = document.getElementById('id_1723').value;
                console.log("Validating addr="+addr+" pw="+pw);
                if ( addr == null || addr == "" || pw == null || pw == "") {
                    alert("Both fields must be filled out");
                    return false;
                }
                if ( addr.indexOf('@') == -1 ) {
                    alert(" Invalid email address");
                    return false;
                }
                return true;
            } catch(e) {
                return false;
            }
            return false;
        }
    </script>
</div>
</body>
</html>