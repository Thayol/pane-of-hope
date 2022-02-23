<?php
    $account = null;
    $custom_request = false;

    if (!empty($_GET["u"]))
    {
        $userid = $_GET["u"];
    }
    else if (!empty($_GET["id"]))
    {
        $userid = $_GET["id"];
    }

    if (!empty($userid))
    {
        $custom_request = true;
        $account = Account::find(Sanitize::id($userid));
    }
    else
    {
        if ($session_authenticated)
        {
            $account = Account::find(Sanitize::id($session_userid));
        }
    }

    if (!$account != null)
    {
        if (!$custom_request)
        {
            header('Location: ' . Router::get_url("login"));
            exit(0);
        }
    }


$context_nav_buttons["Listing"] = "users";
?>
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
<?php if ($account != null): ?>
<h2><?php
    echo $account->displayname;
    if ($account->is_admin())
    {
        echo " <small>(Administrator)</small>";
    }
?></h2>
<p>User ID: #<?= $account->id ?></p>
    <?php if (!$custom_request || $session_is_admin): ?>
    <p>Username: <?= $account->username ?></p>
    <p>E-mail: <?= $account->email ?></p>
    <?php endif; ?>
<?php else: ?>
<div class="notice notice-error">User not found.</div>
<p>Go to the <a href="../">home</a> page.</p>
<?php endif; ?>
</main>
<?php
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>