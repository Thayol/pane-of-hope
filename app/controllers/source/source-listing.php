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

$source_query = Source::select();

$page_count = ceil($source_query->count() / $page_size);

if (!empty($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $page_count)
{
    $page = intval($_GET["page"]);
}

$offset = ($page - 1) * $page_size;

$source_query = $source_query->limit($page_size)->offset($offset);
$source_alias_query = SourceAlias::select()->in("source_id", $source_query->pluck("id"));

$sources = array();
foreach ($source_query->all() as $source)
{
    $sources[$source->id] = $source;
}

$source_aliases = array();
foreach ($source_alias_query->all() as $alias)
{
    $source_aliases[$alias->source_id][] = $alias;
}

foreach ($source_aliases as $source_id => $aliases)
{
    $sources[$source_id]->set_aliases($aliases);
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
    $aliases = array_map(
        fn($alias) => $alias->alias,
        $source->aliases()
    );
    $aliases_concat = "<span>" . implode("</span><br><span>", $aliases) . "</span>";

    echo '<tr>';
    echo "<td>{$source->id}</td>";
    echo "<td><a href=\"" . Routes::get_action_url("source", "id={$source->id}") . "\">{$source->title}</a></td>";
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