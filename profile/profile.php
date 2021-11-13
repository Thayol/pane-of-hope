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
			require "../dbconnection.php";
			$profile_query = $db->query("SELECT id, username, displayname, email FROM users WHERE id={$profile_id} ORDER BY id ASC;");
			
			if ($profile_query->num_rows == 1)
			{
				$profile_arr = $profile_query->fetch_assoc();
				
				$profile_found = true;
				$profile_displayname = $profile_arr["displayname"];
				$profile_email = $profile_arr["email"];
				$profile_username = $profile_arr["username"];
				$profile_userid = $profile_arr["id"];
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
<?php if ($profile_found): ?>
<h2><?php echo $profile_displayname; ?></h2>
<p>User ID: #<?php echo $profile_userid; ?></p>
	<?php if (!$custom_request): ?>
	<p>Username: <?php echo $profile_username; ?></p>
	<p>E-mail: <?php echo $profile_email; ?></p>
	<?php endif; ?>
<?php else: ?>
<div class="notice notice-error">User not found.</div>
<p>Go to the <a href="../">home</a> page.</p>
<?php endif; ?>
</main>
<?php
require "../footer.php";
?>
</body>
</html>