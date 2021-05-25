<head>
<title>Wei Xiang Ooi Login Page</title>
</head>

<?php // Do not put any HTML above this line
//require_once "pdo.php";
session_start();
/* if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
} 
*/
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

//$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required.";
		header("Location: login.php");
		return;
    } elseif (strpos($_POST['email'], '@') == false) {
        $_SESSION['error'] = "Email must have an at-sign (@).";
		header("Location: login.php");
		return;		
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash) {
			error_log("Login success ".$_POST['email']);
			$_SESSION['success']= "Login success ";
			$_SESSION['name'] = $_POST['email'];
			header('Location: index.php');
            return;
        } else {
			error_log("Login fail ".$_POST['email']." $check");
			$_SESSION['error']= "Incorrect password";
			header('Location: login.php');
			return;
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>

<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error']) ) {
	echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
	unset($_SESSION['error']);
}
?>
<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>
</div>
</body>
