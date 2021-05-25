<head><title>Wei Xiang Ooi CRUD</title></head>
<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['name'])) {
    die('ACCESS DENIED');
}

if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {


    // Data validation
    if ( strlen($_POST['model']) < 1 || strlen($_POST['make']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error']="All fields are required";
		header( 'Location: add.php' ) ;
		return;
    }

    if (is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
		$sql = "INSERT INTO autos (make,model,year,mileage)
				  VALUES (:make, :model, :year, :mileage)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':make' => $_POST['make'],
			':model' => $_POST['model'],
			':year' => $_POST['year'],
			':mileage' => $_POST['mileage']));
		$_SESSION['success'] = 'Record added';
		header( 'Location: index.php' ) ;
		return;
	} else {
		if (is_numeric($_POST['year'])){
			$_SESSION['error'] = 'Mileage must be an integer';
		} else {
			$_SESSION['error'] = 'Year must be an integer';	
		}
        header("Location: add.php");
        return;
    }
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>
<p>Add A New User</p>
<form method="post">
<p>Make:
<input type="text" name="make"></p>
<p>Model:
<input type="text" name="model"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<p><input type="submit" value="Add New Entry"/>
<a href="index.php">Cancel</a></p>
</form>
