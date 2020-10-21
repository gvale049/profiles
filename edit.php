<?php
    session_start();
    require_once "pdo.php";

    // Demand a GET parameter
    if ( ! isset($_SESSION['name'])) {
        die('ACCESS DENIED');
    }

    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }

    if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) 
    && isset($_POST["headline"]) && isset($_POST["summary"]) && isset($_POST['profile_id'])) {
        echo $_POST['first_name']."Test";
        if (strlen($_POST['first_name']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: edit.php?profile_id=".$_POST['profile_id']);
            return;
        }
        elseif (strlen($_POST['last_name']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: edit.php?profile_id=".$_POST['profile_id']);
            return;
        }
        elseif (strlen($_POST['email']) < 1 && strpos($_POST['email'], '@')) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("Not a valid email")."</p>\n";
            header("Location: edit.php?profile_id=".$_POST['progile_id']);
            return;
        }
        elseif (! strpos($_POST['email'], '@')) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("Not a valid email")."</p>\n";
            header("Location: edit.php?profile_id=".$_POST['profile_id']);
            return;
        }
        elseif (strlen($_POST['headline']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: edit.php?profile_id=".$_POST['profile_id']);
            return;
        }
        elseif (strlen($_POST['summary']) < 1) {
            $_SESSION['error'] = '<p style="color: red;">'.htmlentities("All values are required")."</p>\n";
            header("Location: edit.php?profile_id=".$_POST['profile_id']);
            return;
        }
          
        $sql = "UPDATE Profile SET first_name = :first_name,
        last_name = :last_name, email = :email, headline = :headline,
        summary = :summary
        WHERE profile_id = :profile_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':first_name' => htmlentities($_POST['first_name']),
            ':last_name' => htmlentities($_POST['last_name']),
            ':email' => htmlentities($_POST['email']),
            ':headline' => htmlentities($_POST['headline']),
            ':summary' => htmlentities($_POST['summary']),
            ':profile_id' => htmlentities($_GET['profile_id'])));
        $_SESSION['message'] = '<p style="color: green;">'.htmlentities("Record Updated")."</p>\n";
        header( 'Location: index.php' );
        return;   

    }
    echo "<h1>Editing Profile for " . htmlentities($_SESSION['name']) . "</h1>\n";
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : false;
    
    if ($error !== false) {
        echo $error;
        unset($_SESSION['error']);
    }

    $stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id=:xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ( $rows === false ) {
        $_SESSION['error'] = '<p style="color: red;">'.htmlentities("Bad value for profile_id")."</p>\n";
        header( 'Location: index.php' ) ;
        return;
    }
    foreach($rows as $row) {
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $headline = $row['headline'];
        $summary = $row['summary'];
        $profile_id = $row['profile_id'];
    }

?>
<!DOCTYPE html>
<html>
<head>
<title>Gabriel Valencia - Edit Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $first_name ?>"></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $last_name ?>"></p>
<p>Email:
<input type="text" name="email" size="80" value="<?= $email ?>"></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= $headline ?>"></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80" value=><?= $summary ?></textarea>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<p><input type="submit" value="Save"/>
<input type="submit" name="cancel" value="Cancel"></p>
</form>
</div>        
</body>
</html>