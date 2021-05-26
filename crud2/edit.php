<head><title>Wei Xiang Ooi Edit Page</title></head>
<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

if (isset($_POST['first_name']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])
    && isset($_POST['headline'])) {

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Bad Email';
    } else {

        $sql = "UPDATE Profile SET first_name = :first_name, last_name = :last_name,email=:email,headline=:headline,summary=:summary
            WHERE profile_id = :profile_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
                ':first_name' => $_POST['first_name'],
                ':last_name' => $_POST['last_name'],
                ':email' => $_POST['email'],
                ':headline' => $_POST['headline'],
                ':summary' => $_POST['summary'],
                ':profile_id' => $_GET['profile_id'])
        );
        $_SESSION['success'] = 'Record updated';
        header('Location: index.php');
        return;
    }
}

// Guardian: Make sure that user_id is present
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :pid");
$stmt->execute(array(":pid" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for user_id';
    header('Location: index.php');
    return;
}

$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);
?>
<h1>Edit Profile</h1>
<form method="post">
	<p>First Name:
	<input type="text" name="first_name" size="60" value="<?php echo $first_name ?>"/></p>
	<p>Last Name:
	<input type="text" name="last_name" size="60" value="<?php echo $last_name ?>"/></p>
	<p>Email:
	<input type="text" name="email" size="30" value="<?php echo $email ?>"/></p>
	<p>Headline:<br/>
	<input type="text" name="headline" size="80" value="<?php echo $headline ?>"/></p>
	<p>Summary:<br/>
	<textarea name="summary" rows="8" cols="80"><?php echo $summary ?></textarea></p>
	<p>
	<input type="submit" value="Save">
	<input type="submit" name="cancel" value="Cancel">
	</p>
</form>