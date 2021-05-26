<?php
require_once "pdo.php";
session_start();
?>
<html>
<head><title>Wei Xiang Ooi CRUD</title></head><body>
<h1>Welcome to the Automobiles Database</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

$stmt = $pdo->query("SELECT * FROM autos");
$rows = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_SESSION['name'])){
	if ($rows === false){
		echo "<p>No rows found.</p>";
	} else {
		echo "<table border='1'>";
		echo "<tr><th>Make</th>";
		echo " <th>Model</th>";
		echo " <th>Year</th>";
		echo " <th>Mileage</th>";
		echo " <th>Action</th>";
		echo " </tr>";
		while ($stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr><td>";
			echo(htmlentities($row['make']));
			echo("</td><td>");
			echo(htmlentities($row['model']));
			echo("</td><td>");
			echo(htmlentities($row['year']));
			echo("</td><td>");
			echo(htmlentities($row['mileage']));
			echo("</td><td>");
			echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
			echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
			echo("</td></tr>\n");
		}
		echo "</table>";
	} 
	echo '<p><a href="add.php">Add New Entry</a></p>';
	echo '<p><a href="logout.php">Logout</a></p>';
} else {
	echo '<p><a href="login.php">Please log in</a></p>';
	echo '<p>Attempt to <a href="add.php">add data</a> without logging in</p>';
}
?>
</body>
</html>