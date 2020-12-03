<?php
    session_start();
    require_once "pdo.php";
    require_once "util.php";

    // Demand a GET parameter
    if ( ! isset($_SESSION['user_id'])) {
        die('ACCESS DENIED');
    }

    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }

    if (! isset($_REQUEST['profile_id']) ) { 
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id=:xyz AND user_id = :uid");
    $stmt->execute(array(":xyz" => $_REQUEST['profile_id'], 
                        ":uid" => $_SESSION['user_id']));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ( $rows === false ) {
        $_SESSION['error'] = "Could not load profile";
        header( 'Location: index.php' ) ;
        return;
    }

    foreach($rows as $row) { 
        $f_n = $row['first_name'];
        $l_n = $row['last_name'];
        $em = $row['email'];
        $hl = $row['headline'];
        $su = $row['summary'];
    }
    
    if (isset($_POST["first_name"]) && isset($_POST["last_name"]) 
    && isset($_POST["email"]) && isset($_POST["headline"]) && 
    isset($_POST["summary"]) && isset($_POST['profile_id'])) {

        $msg = validateProfile();
        if ( is_string($msg) ) {
            $_SESSION['error'] = $msg;
            header("Location: edit.php?profile_id=".$_REQUEST["profile_id"]);
            return;
        } 

        $msg = validatePos();
        if ( is_string($msg) ) {
            $_SESSION['error'] = $msg;
            header("Location: edit.php?profile_id=". $_REQUEST["profile_id"]);
            return;
        }
        
        $sql = "UPDATE Profile SET first_name = :first_name,
        last_name = :last_name, email = :email, headline = :headline,
        summary = :summary
        WHERE profile_id = :profile_id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':first_name' => htmlentities($_POST['first_name']),
            ':last_name' => htmlentities($_POST['last_name']),
            ':email' => htmlentities($_POST['email']),
            ':headline' => htmlentities($_POST['headline']),
            ':summary' => htmlentities($_POST['summary']),
            ':user_id' => htmlentities($_SESSION['user_id']),
            ':profile_id' => htmlentities($_REQUEST['profile_id']))
        );

        $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
        $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

        $rank = 1;
        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            $stmt = $pdo->prepare('INSERT INTO Position
                (profile_id, rank, year, description)
                VALUES ( :pid, :rank, :year, :desc)');

            $stmt->execute(array(
            ':pid' => htmlentities($_REQUEST['profile_id']),
            ':rank' => htmlentities($rank),
            ':year' => htmlentities($year),
            ':desc' => htmlentities($desc))
            );

            $rank++;

        }
 
        $_SESSION['success'] = "Record Updated";
        header( 'Location: index.php' );
        return;   
    }

    $positions = loadPos($pdo, $_REQUEST['profile_id']);

?>
<!DOCTYPE html>
<html>
<head>
<title>Gabriel Valencia - Edit Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Editing Profile for <?=htmlentities($_SESSION['name']); ?></h1>
<?php flashMessages(); ?>
<form method="post" action="edit.php">

<input type="hidden" name="profile_id" value="<?= htmlentities($_GET['profile_id']); ?>"/>
<p>First Name: 
<input type="text" name="first_name" size="60"
value="<?= htmlentities($f_n);?>" /></p>
<p> Last Name: 
<input type="text" name="last_name" size="60"
value="<?= htmlentities($l_n); ?>" /></p>
<p>Email:
<input type="text" name="email" size="30"
value="<?= htmlentities($em); ?>" /> </p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"
value="<?= htmlentities($hl); ?>" /></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80">
<?= htmlentities($su); ?>
</textarea>

<?php

$pos = 0;
echo('<p>Position: <input type="submit" id="addPos" value="+">'."\n");
echo('<div id="position_fields">'."\n");
foreach($positions as $position) {
    $pos++;
    echo('<div id="position'.$pos.'">'."\n");
    echo('<p>Year: <input type="text" name="year'.$pos.'"');
    echo(' value="'.$position['year'].'" />'."\n");
    echo('<input type="button" value="-" ');
    echo('onclick="$(\'#position'.$pos.'\').remove();return false;">'."\n");
    echo("</p>\n");
    echo('<textarea name="desc'.$pos.'" row="8" cols="80">'."\n");
    echo(htmlentities($position['description'])."\n");
    echo("\n</textarea>\n</div>\n");
}
echo("</div></p>\n");
?>

<p>
<input type="submit" value="Save"/>
<input type="submit" name="cancel" value="Cancel"></p>
</p>
</form>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.11.4.js"></script>
<script>
    countPos = <?= $pos ?>;

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
                </div>');
        });

        $('#addEdu').click(function(events) {
            event.preventDefault();
            if (countEdu >= 9) {
                alert("Maximum of 9 education entries exceeded");
                return;
            }
            countEdu++;
            windows.console && console.log("Adding Education " + countEdu);

            // grab some HTML with hotspots and insert into DOM
            var source = $("#edu-template").html();
            $('#edu_fields').append(source.replace(/@COUNT@/g, countEdu));

            // Add the even handler to the new ones
            $('.school').autocomplete({
                source: "school.php"
            });
        });

        $('.school').autocomplete({
            source: "school.php"
        });
    });
</script>

<!-- HTML with subtitution hot spots -->
<script id="edu-template" type="text">
    <div id="edu@COUNT@">
        <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
        <input type="button" value="-" onclick="$('#edu@COUNT@').remove(); return false;"><br>
        <p>School: <input type="text" size"80" name="edu_school@COUNT@" class="school" value"" />
        </p>
    </div>
</script>
</div>        
</body>
</html>