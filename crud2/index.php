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

$stmt = $pdo->query("SELECT profile_id, first_name,last_name , headline from Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($rows === false){
	echo "<p>No rows found.</p>";
} else {
	echo "<table border='1'>";
	echo "<tr><th>Name</th>";
	echo " <th>Headline</th>";
	if (isset($_SESSION['name'])){
		echo " <th>Action</th>";
	}
	echo " </tr>";
	foreach ($rows as $row) {
		echo "<tr><td>";
		echo("<a href='view.php?profile_id=".$row['profile_id']."'>".$row['first_name']." ".$row['last_name']. "</a>");
		echo("</td><td>");
		echo(htmlentities($row['headline']));
		echo("</td>");
		if (isset($_SESSION['name'])){
			echo('<td><a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
			echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a></td>');
		}
		echo("</tr>\n");
	}
	echo "</table>";
} 


if (isset($_SESSION['name'])) {
	echo '<p><a href="add.php">Add New Entry</a></p>';
	echo '<p><a href="logout.php">Logout</a></p>';
} else {
	echo '<p><a href="login.php">Please log in</a></p>';
	echo '<p>Attempt to <a href="add.php">add data</a> without logging in</p>';
}
?>
</body>
</html>