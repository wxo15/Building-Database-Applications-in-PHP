<?php
    session_start();
    require_once "pdo.php";
    if (!isset($_GET['profile_id'])){
        $_SESSION['error'] = "Missing autos_id";
        header('Location: index.php');
        return;
    }
    $stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :pid");
    $stmt->execute(array(":pid" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
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
echo "<a href='index.php'>Done</a>";
?>
</div>
</body>
</html>