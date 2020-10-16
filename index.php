<!DOCTYPE html>
<html>
    <head>
        <?php require_once "bootstrap.php"; ?>
        <title> Gabriel Valencia - Index page </title>
    </head>
    <body>
        <div class="container">
            <h1>Gabriel Valencia's Resume Registery</h1>
            <?php
                session_start();
                require_once "pdo.php";

                $message = isset($_SESSION['message']) ? $_SESSION['message'] : false;
                if ( $message !== false) {
                    echo $message;
                    unset($_SESSION['message']);
                }


                echo "<p> <a href=".'login.php'."> Please Log in</a> </p>"
            ?>
            
            <p><b>Note:</b> Your implementation should retain data across multiple logout/login sessions. 
            This sample implementation clears all its data periodically - which you should not do in your implementation.</p>
        </div>
        
    </body>
</html>
