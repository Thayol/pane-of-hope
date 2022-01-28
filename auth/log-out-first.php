<?php if (!empty($session_username)): ?>
<p>You are logged in as <b><?= $session_username ?></b>.</p>
<p><a href="<?= action_to_link("logout") ?>">Log out</a> first to access this page.</p>
<?php endif; ?>