<?php if (!empty($session_username)): ?>
<p>You are logged in as <b><?php echo $session_username; ?></b>.</p>
<p><a href="<?php echo action_to_link("logout"); ?>">Log out</a> first to access this page.</p>
<?php endif; ?>