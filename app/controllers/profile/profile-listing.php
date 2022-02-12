<html>
<head>
<?php
require _WEBROOT_ . "/app/views/global/head.php";
?>
</head>
<body>
<?php

$context_nav_buttons["Listing"] = "users";

if ($session_authenticated)
{
    $context_nav_buttons["My profile"] = "profile";
}

require _WEBROOT_ . "/app/views/global/header.php";
?>
<main class="main">
<?php
$page = 1; // if not set
$page_count = 1; // default fallback
$page_size = Config::$listing_page_size;

$account_query = Account::select();
$page_count = ceil($account_query->count() / $page_size);

if (!empty($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $page_count)
{
    $page = intval($_GET["page"]);
}

$offset = ($page - 1) * $page_size;

$account_query = $account_query->limit($page_size)->offset($offset);

$accounts = $account_query->list()
?>

<?php if (empty($accounts)): ?>
<p>There are no profiles in the database. (Or there is a database error.)</p>
<?php else: ?>

<table class="table-wide thead-separator alternating-rows">
    <thead>
        <tr>
            <td>ID</td>
            <td>Displayname</td>
            <?php if ($session_is_admin): ?>
            <td>Username</td>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($accounts as $account)
{
    $account_url = Routes::get_action_url("profile", "id={$account->id}");

    echo '<tr>';
    echo "<td>{$account->id}</td>";
    echo "<td><a href=\"{$account_url}\">{$account->displayname}</a></td>";
    if ($session_is_admin)
    {
        echo "<td>#{$account->username}</td>";
    }
    echo '</tr>';
}
echo "</tbody></table>";

echo "<nav>Page: ";
for ($i = $page - Config::$max_seek_page_numbers; $i <= $page + Config::$max_seek_page_numbers; $i++)
{
    if ($i > 0 && $i <= $page_count)
    {
        $url = Routes::get_action_url($action, "page={$i}");
        $class = "nav-button";
        if ($i == $page)
        {
            $class .= " nav-button-current";
        }

        echo "<a class=\"{$class}\" href=\"{$url}\">{$i}</a> ";
    }
}
echo "</nav>";

endif; ?>

</main>
<?php
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>