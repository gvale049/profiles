<?php
    session_start();
    require_once "pdo.php";

    if ( ! isset($_SESSION['name']) ) {
        // kill access if not logged in
        die("ACCESS DENIED");
    }

    if ( isset($_POST['cancel']) ) {
        $_SESSION['cancel'] = $_POST['cancel'];
        header("location: index.php");
        return;
    } 
    
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
        echo "Have a good day!";
        if (strlen($_POST['first_name']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: add.php");
            return;
        }
        elseif (strlen($_POST['last_name']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: add.php");
            return;
        }      
        elseif (strlen($_POST['email']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: add.php");
            return;
        }
         
        elseif (strlen($_POST['headline']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: add.php");
            return;
        }
        elseif (strlen($_POST['summary']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: add.php");
            return;
        }
        elseif ( strlen($_POST['email']) > 1 && ! strpos($_POST['email'], '@') ) {   
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("Not a valid e-mail")."</p>\n";
            header('Location: add.php');
            return;
        } 
        else {
            $stmt = $pdo->prepare('INSERT INTO Profile
                    (user_id, first_name, last_name, email, headline, summary)
                    VALUES (:uid, :fn, :ln, :em, :he, :su)');

            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => htmlentities($_POST['first_name']),
                ':ln' => htmlentities($_POST['last_name']),
                ':em' => htmlentities($_POST['email']),
                ':he' => htmlentities($_POST['headline']),
                ':su' => htmlentities($_POST['summary']))
            );
            
            $_SESSION['message'] = '<p style="color: green;">'.htmlentities("Added")."</p>\n";
            header('Location: index.php');
            return;
        }           
    }
?>

<!DOCTYPE html>
<html>
<head>
   <title> Gabriel Valencia - add page </title>
</head>
<body>
<div class="container"> 
    <?php 
        require_once("bootstrap.php"); 
        echo "<h1> Adding Profile for ".$_SESSION['name']."</h1>"; 
        
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : false;
        if ($error !== false) {
            echo($error);
            unset($_SESSION['error']);
        }
    ?>

    <form method="post">
        <p> First Name:
            <input type="text" name="first_name" size="60"/></p>
        <p> Last Name: 
            <input type="text" name="last_name" size="60"/></p>
        <p> Email:
            <input type="text" name="email" size="30"/></p>
        <p> Headline:<br/>
            <input type="text" name="headline" size="80"/></p>
        <p> Summary: </br>
            <textarea name="summary" rows="8" cols="80"></textarea></p>
        <p> Position: <input type="submit" id="addPos" value="+"></p>
        <div id="position_fields"></div>
        <p>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
    <script>
        countPos = 0;

        $(document).ready(function(){
            window.console && console.log('Document ready called');
            $('#addPos').click(function(event){
                // http://api.jquery.com/event.preventdefault/
                event.preventDefault();
                if (countPos >= 9) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position "+ countPos);
                $('#position_fields').append(
                    '<div id="position'+countPos+'"> \
                    <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
                    <input type="button" value="-" \
                        onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
                        <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
                    </div>'
                );
            });
        });
    </script>
</div>
</body>
</html>
