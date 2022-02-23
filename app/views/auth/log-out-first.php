<?php if (!empty($session_username)): ?>
<p>You are logged in as <b><?= $session_username ?></b>.</p>
<p><a href="<?= Router::get_url("logout") ?>">Log out</a> first to access this page.</p>
<?php endif; ?>