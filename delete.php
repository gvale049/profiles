<?php
require_once "pdo.php";
require_once "bootstrap.php";
session_start();

if (! isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

if (isset($_POST['cancel'])) {
    $_SESSION['cancel'] = $_POST['cancel'];
    header("location: index.php");
    return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id=:xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row) {
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $profile_id = $row['profile_id'];
}

if(isset($_POST['delete']) && isset($_POST['profile_id'])) {
    $sql = "DELETE FROM Profile WHERE profile_id=:prof";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':prof' => $profile_id));

    $_SESSION['message'] = '<p style="color: green;">'.htmlentities("Record Deleted")."</p>\n";
    header( 'Location: index.php' );
    return;

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Gabriel Valencia - Delete Page</title>
</head>

<body>
<div class="container">
<h1> Deleting profile</h1>
<?php echo "<p>First Name: ".$first_name."</p>";
echo "<p>Last Name: ".$last_name."</p>";?>

<form method="post">
    <input type="hidden" name="profile_id" value="  <? $profile_id ?>">
    <p><input type="submit" name="delete" value="Delete">
    <input type="submit" name="cancel" value="Cancel"></p> 
</form>
</div>
</body>
</html>
