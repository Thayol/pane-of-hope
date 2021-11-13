<html>
<head>
<?php
require "../head.php";
?>
</head>
<body>
<?php
require "../header.php";
?>
<main class="main">

<?php
$is_invalid = false;
if (!empty($_GET["invalid"]))
{
	$is_invalid = true;
	$invalid_messages = array(
		"username" => "Invalid username.",
		"displayname" => "Invalid displayname.",
		"password" => "Invalid password.",
		"email" => "Invalid e-mail address.",
		"password2" => "Passwords do not match.",
		"registered" => "Username is already taken.",
	);
	
	$invalid_message = "Unknown error.";
	if (in_array($_GET["invalid"], array_keys($invalid_messages))) $invalid_message = $invalid_messages[$_GET["invalid"]];
}

if ($is_invalid): ?>
<div class="notice notice-error"><?php echo $invalid_message; ?></div>
<?php endif; ?>

<?php if ($session_authenticated):
	require "log-out-first.php";
else: ?>
<form class="login-form" action="../auth/register-handler.php" method="POST">

<h2>Register</h2>

<label class="input-label">Login name:</label>
<input type="text" name="username" pattern="[A-Za-z0-9_\.-]{3,128}" placeholder="username"><br>

<label class="input-label">E-mail:</label>
<input type="email" name="email" placeholder="e-mail address"><br>

<label class="input-label">Password:</label>
<input type="password" name="password" pattern=".{8,4000}" placeholder="password"><br>

<label class="input-label">Password Again:</label>
<input type="password" name="password2" pattern=".{8,4000}" placeholder="password again"><br>

<label class="input-label">Display name:</label>
<input type="text" name="displayname" pattern="[A-Za-z0-9_\. -]{1,16}" placeholder="display name"><br>

<input class="input-submit" type="submit" value="Register">
</form>
<?php endif; ?>
</main>
<?php
require "../footer.php";
?>
</body>
</html>