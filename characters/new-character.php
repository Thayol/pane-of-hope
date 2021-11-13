<?php

if (isset($_GET["invalid"]))
{
	$notice_error = "Could not add character.";
}
?>
<html>
<head>
<?php
require __DIR__ . "/../head.php";
?>
</head>
<body>
<?php
require __DIR__ . "/../header.php";
?>
<main class="main">

<?php if ($session_is_admin): ?>
<form class="login-form" action="<?php echo action_to_link("characters"); ?>new-character-handler.php" method="POST">

<h2>New Character</h2>

<label class="input-label">Name:</label>
<input class="input-textbox" type="text" name="name" placeholder="English Name"><br>

<label class="input-label">Original Name:</label>
<input class="input-textbox" type="text" name="original_name" placeholder="Original Name"><br>

<label class="input-label">Gender:</label>
<select class="input-select" name="gender">
	<option value="0">N/A</option>
	<option value="1">Female</option>
	<option value="2">Male</option>
</select><br>

<input class="input-submit" type="submit" value="Create">
</form>
<?php else: ?>
<p>Unauthorized.</p>
<?php endif; ?>

</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>