<html>
<head>
<?php
require _WEBROOT_ . "/app/views/global/head.php";
?>
</head>
<body>
<?php

$context_nav_buttons["Listing"] = "sources";

if ($session_is_admin)
{
    $context_nav_buttons["New"] = "source-new";
}

require _WEBROOT_ . "/app/views/global/header.php";
?>
<main class="main">
<?php
$page = 1; // if not set
$page_count = 1; // default fallback
$page_size = Config::$listing_page_size;

$db = Database::connect();
$count_result = $db->query("SELECT COUNT(id) as source_count FROM sources;");
if ($count_result->num_rows > 0)
{
    $page_count = ceil($count_result->fetch_assoc()["source_count"] / $page_size);
}

if (!empty($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $page_count)
{
    $page = intval($_GET["page"]);
}

$offset = ($page - 1) * $page_size;
$result = $db->query("SELECT sources.id AS id, sources.title AS title, source_aliases.alias AS alias FROM sources LEFT JOIN source_aliases ON sources.id=source_aliases.source_id ORDER BY sources.id ASC LIMIT {$page_size} OFFSET {$offset};");

$sources = array();
if ($result->num_rows > 0)
{
    while ($source = $result->fetch_assoc())
    {
        $id = $source["id"];
        if (empty($sources[$id]))
        {
            $sources[$id] = $source;
            if (!empty($source["alias"]))
            {
                unset($sources[$id]["alias"]);
                $sources[$id]["aliases"] = array($source["alias"]);
            }
        }
        else
        {
            if (!empty($source["alias"]))
            {
                $sources[$id]["aliases"][] = $source["alias"];
            }
        }
    }
}

?>

<?php if (empty($sources)): ?>
<p>There are no sources in the database. (Or there is a database error.)</p>
<?php else: ?>

<table class="table-wide thead-separator alternating-rows">
    <thead>
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Alternative Titles</td>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($sources as $source)
{
    $id = $source["id"];
    $title = $source["title"];
    $aliases = $source["aliases"] ?? array();
    $aliases_concat = "<span>" . implode("</span><br><span>", $aliases) . "</span>";

    $url = Routes::get_action_url("source") . "?id={$id}";

    echo '<tr>';
    echo "<td>{$id}</td>";
    echo "<td><a href=\"{$url}\">{$title}</a></td>";
    echo "<td>{$aliases_concat}</td>";
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