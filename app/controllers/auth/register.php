<html>
<head>
<?php
require _WEBROOT_ . "/app/views/global/head.php";
?>
</head>
<body>
<?php
require _WEBROOT_ . "/app/views/global/header.php";
?>
<main class="main">

<?php
if (!empty($_GET["invalid"]))
{
	$invalid_messages = array(
		"username" => "Invalid username.",
		"displayname" => "Invalid displayname.",
		"password" => "Invalid password.",
		"email" => "Invalid e-mail address.",
		"password2" => "Passwords do not match.",
		"registered" => "Username is already taken.",
	);
	
	$notice_error = "Unknown error.";
	if (in_array($_GET["invalid"], array_keys($invalid_messages))) $notice_error = $invalid_messages[$_GET["invalid"]];
}

require _WEBROOT_ . "/app/views/global/notice.php";
?>

<?php if ($session_authenticated):
	require __DIR__ . "/log-out-first.php";
else: ?>
<form class="login-form" action="<?= Routes::get_handler_url("register") ?>" method="POST">

<h2>Register</h2>

<label class="input-label">Login name:</label>
<input class="input-textbox" type="text" name="username" pattern="[A-Za-z0-9_\.-]{3,128}" placeholder="username"><br>

<label class="input-label">E-mail:</label>
<input class="input-textbox" type="email" name="email" placeholder="e-mail address"><br>

<label class="input-label">Password:</label>
<input class="input-textbox" type="password" name="password" pattern=".{8,4000}" placeholder="password"><br>

<label class="input-label">Password Again:</label>
<input class="input-textbox" type="password" name="password2" pattern=".{8,4000}" placeholder="password again"><br>

<label class="input-label">Display name:</label>
<input class="input-textbox" type="text" name="displayname" pattern="[A-Za-z0-9_\. -]{1,16}" placeholder="display name"><br>

<input class="input-submit" type="submit" value="Register">
</form>
<?php endif; ?>
</main>
<?php
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>