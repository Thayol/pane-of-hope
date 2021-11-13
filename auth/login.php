<?php
$is_invalid = false;
if (!empty($_GET["invalid"]))
{
	$is_invalid = true;
	$invalid_messages = array(
		"unregistered" => "Username is not registered.",
		"wrongpass" => "Wrong password.",
		"username" => "Invalid username.",
		"password" => "Invalid password.",
		"banned" => "You are banned.",
	);
	
	$invalid_message = "Unknown error.";
	if (in_array($_GET["invalid"], array_keys($invalid_messages))) $invalid_message = $invalid_messages[$_GET["invalid"]];
}

$successfully_registered = false;
if (isset($_GET["registered"]))
{
	$successfully_registered = true;
}
?>
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

<?php if ($is_invalid): ?>
<div class="notice notice-error"><?php echo $invalid_message; ?></div>
<?php endif; ?>

<?php if ($successfully_registered): ?>
<div class="notice notice-success">Successfully registered. Log in.</div>
<?php endif; ?>

<?php if ($session_authenticated):
	require "log-out-first.php";
else: ?>
<form class="login-form" action="../auth/login-handler.php" method="POST">

<h2>Log in</h2>

<label class="input-label">Login name:</label>
<input type="text" name="username" pattern="[A-Za-z0-9_\.-]{3,128}" placeholder="username"><br>

<label class="input-label">Password:</label>
<input type="password" name="password" pattern=".{8,4000}" placeholder="password"><br>

<input class="input-submit" type="submit" value="Log in">
</form>
<?php endif; ?>
</main>
<?php
require "../footer.php";
?>
</body>
</html>