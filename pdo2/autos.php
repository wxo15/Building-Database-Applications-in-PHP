<?php

require_once "pdo.php";

$failure = false;
if (isset($_GET['name'])) {
} else {
	die("Name parameter missing");
}
if(isset($_POST['logout'])) {
	header('Location: index.php');
} else {
 
	if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {

		if ($_POST['make'] == "") {
			$failure = "Make is required";
		} elseif (is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
				$stmt = $pdo->prepare('INSERT INTO autos
					(make, year, mileage) VALUES ( :mk, :yr, :mi)');
					
				$stmt->execute(array(
					':mk' => $_POST['make'],
					':yr' => $_POST['year'],
					':mi' => $_POST['mileage'])
				);

				echo "<p style='color: green'>Record inserted</p>";
		} else {
			$failure = "Mileage and year must be numeric";   
		}
	}   
}
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Wei Xiang Ooi</title>
</head>


<body>

<?php
echo "<h1>Tracking Autos for ".$_GET['name']."</h1>";
// Note triple not equals and think how badly double
// not equals would work here...
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="post">
	<p>Make:
		<input name="make">
	</p>
	<p>Year:
		<input size="40" name="year">
	</p>
	<p>Mileage:
		<input size="40" name="mileage">
	</p>
	<p>
		<input type="submit" value="Add" name="Add" />
		<input type="submit" value="logout" name="logout" />
	</p>
</form>
<h2>Automobiles</h2>
<ul>
	<?php
	$statement = $pdo->query("SELECT auto_id, make, year, mileage FROM autos");
	while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
		echo "<li> ";
		echo $row['year']." ";
		echo htmlentities($row['make'])." / ";
		echo $row['mileage'];
		echo "</li>";
	}
	?>
</ul>
</body>
</html>