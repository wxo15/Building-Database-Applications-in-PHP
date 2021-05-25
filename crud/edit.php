<head><title>Wei Xiang Ooi CRUD</title></head>
<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['mileage']) && isset($_POST['year']) ) {

    // Data validation
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1  || strlen($_POST['mileage']) < 1  || strlen($_POST['year']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }

    if ( !is_numeric($_POST['year']) ) {
        $_SESSION['error'] = 'Year must be numeric';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }

    $sql = "UPDATE autos SET make = :make, year = :year, mileage = :mileage, model = :model 
            WHERE autos_id = :autos_id";
            
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':model' => $_POST['model']
    , ':autos_id' => $_POST['autos_id']));
        
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
    }

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing autos_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :id");
$stmt->execute(array(":id" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
?>
<p>Edit User</p>

<form method="post">
    <p>Make:
        <input type="text" name="make" value="<?= $make ?>">
    </p>
    <p>Model:
        <input type="text" name="model" value="<?= $model ?>">
    </p>
    <p>Year:
        <input type="text" name="year" value="<?= $y ?>">
    </p>
    <p>Mileage:
        <input type="text" name="mileage" value="<?= $mileage ?>">
    </p>
    <input type="hidden" name="autos_id" value="<?= $row['autos_id'] ?>">
    <p>
        <input type="submit" value="Save" />
        <a href="index.php">Cancel</a>
    </p>
</form>