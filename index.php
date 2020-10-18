<!DOCTYPE htmld
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

        if (isset($_SESSION['user_id'])) {
            
            echo "<p> <a href=".'logout.php'."> Logout</a> </p>";

            $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM Profile");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( isset($rows['first_name'])) {
                echo("<p> No rows found </p>");
            }
            else {
                echo('<table border="1">'."\n");
                foreach($rows as $row) {
                    $name = ($row['first_name']." ".$row['last_name']);
                    echo("<tr><td>");
                    echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$name.'</a>');
                    echo("</td><td>");
                    echo($row['headline']);
                    echo("</td><td>");
                    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
                    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
                }
                echo('</table>'."\n");
            }
            echo "<p> <a href=".'add.php'."> Add New Entry</a> </p>";

        } else {

            echo "<p> <a href=".'login.php'."> Please Log in</a> </p>";

            $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM Profile");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ( isset($rows['first_name'])) {
                echo("<p> No rows found </p>");
            }
            else {
                echo('<table border="1">'."\n");
                foreach($rows as $row) {
                    $name = ($row['first_name']." ".$row['last_name']);
                    echo("<tr><td>");
                    echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$name.'</a>');
                    echo("</td><td>");
                    echo($row['headline']);
                }
                echo('</table>'."\n");
            }
        }
        $message = isset($_SESSION['message']) ? $_SESSION['message'] : false;
        if ( $message !== false) {
            echo $message;
            unset($_SESSION['message']);
        }

        if ( isset($_SESSION['user_id']) ) {

        }
      
    ?>
    
    <p><b>Note:</b> Your implementation should retain data across multiple logout/login sessions. 
    This sample implementation clears all its data periodically - which you should not do in your implementation.</p>
</div>
    
</body>
</hml>
