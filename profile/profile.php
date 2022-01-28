<?php
	$profile_found = false;
	$custom_request = false;
	
	if (!empty($_GET["u"]))
	{
		$custom_request = true;
		$temp_id = $_GET["u"];
		if (filter_var($temp_id, FILTER_VALIDATE_INT) !== false)
		{
			$profile_id = intval($temp_id);
		}
		
		if ($profile_id > 0)
		{
			$db = db_connect();
			$profile_query = $db->query("SELECT id, username, displayname, email, permission_level FROM users WHERE id={$profile_id} ORDER BY id ASC;");
			
			if ($profile_query->num_rows == 1)
			{
				$profile_arr = $profile_query->fetch_assoc();
				
				$profile_found = true;
				$profile_displayname = $profile_arr["displayname"];
				$profile_email = $profile_arr["email"];
				$profile_username = $profile_arr["username"];
				$profile_userid = $profile_arr["id"];
				$profile_is_admin = $profile_arr["permission_level"] >= 40 ? true : false;
			}
		}
	}
	else
	{
		if ($session_authenticated)
		{
			$profile_found = true;
			$profile_displayname = $session_displayname;
			$profile_email = $session_email;
			$profile_username = $session_username;
			$profile_userid = $session_userid;
			$profile_is_admin = $session_is_admin;
		}
	}
	
	if (!$profile_found)
	{
		if (!$custom_request)
		{
			header('Location: ' . $absolute_prefix . '/login/');
			exit(0);
		}
	}
	

$context_nav_buttons["Listing"] = "users";
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
<?php if ($profile_found): ?>
<h2><?php 
	echo $profile_displayname;
	if ($profile_is_admin)
	{
		echo " <small>(Administrator)</small>";
	}
?></h2>
<p>User ID: #<?= $profile_userid ?></p>
	<?php if (!$custom_request): ?>
	<p>Username: <?= $profile_username ?></p>
	<p>E-mail: <?= $profile_email ?></p>
	<?php endif; ?>
<?php else: ?>
<div class="notice notice-error">User not found.</div>
<p>Go to the <a href="../">home</a> page.</p>
<?php endif; ?>
</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>