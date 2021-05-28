<head>
<title>Wei Xiang Ooi Edit Page</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
</head>
<?php
	require_once "pdo.php";
	session_start();

	if (!isset($_SESSION['name'])) {
		die('Not logged in');
	}
	
	function validatePos() {
		for($i=1; $i<=9; $i++) {
			if ( ! isset($_POST['year'.$i]) ) continue;
			if ( ! isset($_POST['desc'.$i]) ) continue;

			$year = $_POST['year'.$i];
			$desc = $_POST['desc'.$i];

			if ( strlen($year) == 0 || strlen($desc) == 0 ) {
				return "All fields are required";
			}

			if ( !is_numeric($year) ) {
				return "Position year must be numeric";
			}
		}
		return true;
	}
	

	if (isset($_POST['first_name']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])
		&& isset($_POST['headline'])) {

		if (strpos($_POST['email'], '@') === false) {
			$_SESSION['error'] = 'Bad Email';
		} elseif (validatePos() != true){
			$_SESSION['error'] = validatePos();
		} else {
			$stmt = $pdo->prepare("UPDATE Profile SET first_name = :first_name, last_name = :last_name,email=:email,headline=:headline,summary=:summary
				WHERE profile_id = :profile_id");
			$stmt->execute(array(
					':first_name' => $_POST['first_name'],
					':last_name' => $_POST['last_name'],
					':email' => $_POST['email'],
					':headline' => $_POST['headline'],
					':summary' => $_POST['summary'],
					':profile_id' => $_GET['profile_id'])
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
				':pid' => $_REQUEST['profile_id'],
				':rank' => $rank,
				':year' => $year,
				':desc' => $desc)
				);
				$rank++;
			}
  
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
	
	$stmt2 = $pdo->prepare("SELECT * FROM Position where profile_id = :pid");
	$stmt2->execute(array(":pid" => $_GET['profile_id']));
	$rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

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
	<p>Position: <input type="submit" id="addPos" value="+">
    <div id="position_fields">
        <?php
            $rank = 1;
            foreach ($rows2 as $row) {
                echo "<div id=\"position" . $rank . "\">
				<p>Year: <input type=\"text\" name=\"year1\" value=\"".$row['year']."\">
				<input type=\"button\" value=\"-\" onclick=\"$('#position". $rank ."').remove();return false;\"></p>
				<textarea name=\"desc". $rank ."\"').\" rows=\"8\" cols=\"80\">".$row['description']."</textarea>
				</div>";
                $rank++;
            } ?>
        </div>
        <input type="submit" value="Save">
        <input type="submit" name="cancel" value="Cancel">
    </p>
</form>
<script>
	countPos = 0;

	$(document).ready(function () {
		window.console && console.log('Document ready called');
		$('#addPos').click(function (event) {
			event.preventDefault();
			if (countPos >= 9) {
				alert("Maximum of nine position entries exceeded");
				return;
			}
			countPos++;
			window.console && console.log("Adding position " + countPos);
			$('#position_fields').append(
				'<div id="position' + countPos + '"> \
	<p>Year: <input type="text" name="year' + countPos + '" value="" /> \
	<input type="button" value="-" \
		onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
	<textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
	</div>');
		});
	});
</script>