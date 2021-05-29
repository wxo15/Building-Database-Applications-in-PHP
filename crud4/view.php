<?php
    session_start();
    require_once "pdo.php";
	require_once "util.php";
    
	
	if (!isset($_GET['profile_id'])){
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }
	if($_GET["profile_id"] == ""){
		$_SESSION["error"] = "Could not load profile";
		header("Location: index.php");
		return;
	}
    $stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :pid");
    $stmt->execute(array(":pid" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt2 = $pdo->prepare("SELECT * FROM Position where profile_id = :pid");
	$stmt2->execute(array(":pid" => $_GET['profile_id']));
	$rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
	$positions = loadEduOrPos($pdo, $_GET["profile_id"], "position");
	// Load up the education rows
	$educations = loadEduOrPos($pdo, $_GET["profile_id"], "education");

?>


<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>    
<title>Wei Xiang Ooi View Page</title>

</head>
<<body>
<div class="container">
<h1>Profile information</h1>
<?php
echo "<p>First Name: ".htmlentities($row['first_name'])."</p>";
echo "<p>Last Name: ".htmlentities($row['last_name'])."</p>";
echo "<p>Email: ".htmlentities($row['email'])."</p>";
echo "<p>Headline: ".htmlentities($row['headline'])."</p>";
echo "<p>Summary: ".htmlentities($row['summary'])."</p>";
echo "<p>Position: <br/><ul>";
foreach ($educations as $row2) {
	$institution_id = $row["institution_id"];

	$stmt = $pdo -> prepare("SELECT name FROM institution WHERE institution_id = :institution_id");
	$stmt->execute(array(":institution_id" => $row["institution_id"]));
	$rowInst = $stmt -> fetch(PDO::FETCH_ASSOC);
	echo ("<li>" . htmlentities($row["year"]) . " : " . htmlentities($rowInst["name"]) . "</li>\n");
}
echo "</ul></p>";
echo "<p>Education: <br/><ul>";
foreach ($positions as $row2) {
	echo "<li>".htmlentities($row2['year'].":".htmlentities($row2['description'])."</li>");
}
echo "</ul></p>";
echo "<a href='index.php'>Done</a>";
?>
</div>
</body>
</html>