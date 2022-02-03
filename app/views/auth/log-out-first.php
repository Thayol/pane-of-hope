<?php if (!empty($session_username)): ?>
<p>You are logged in as <b><?= $session_username ?></b>.</p>
<p><a href="<?= Routes::get_action_url("logout") ?>">Log out</a> first to access this page.</p>
<?php endif; ?>