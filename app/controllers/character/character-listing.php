<html>
<head>
<?php
require _WEBROOT_ . "/app/views/global/head.php";
?>
</head>
<body>
<?php

$context_nav_buttons["Listing"] = "characters";

if ($session_is_admin)
{
    $context_nav_buttons["New"] = "character/new";
}

require _WEBROOT_ . "/app/views/global/header.php";
?>
<main class="main">
<?php
$page = 1; // if not set
$page_count = 1; // default fallback
$page_size = Config::$listing_page_size;

$page_count = ceil(Character::count() / $page_size);

if (!empty($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $page_count)
{
    $page = intval($_GET["page"]);
}

$offset = ($page - 1) * $page_size;

$character_query = Character::select()->limit($page_size)->offset($offset);
$connector_query = CharacterSourceConnector::select()->where("character_id IN (?)", $character_query->pluck("id"));

$characters = array();
$sources = array();

foreach ($character_query->each() as $character)
{
    $characters[$character->id] = $character;
}

$source_ids_to_query = $connector_query->pluck("source_id");
if (!empty($source_ids_to_query))
{
    $source_query = Source::select()->where("id IN (?)", $source_ids_to_query);

    foreach (Source::all() as $source)
    {
        $sources[$source->id] = $source;
    }
    $sources_by_character_id = array();
    foreach (CharacterSourceConnector::all() as $conn)
    {
        $sources_by_character_id[$conn->character_id][] = $sources[$conn->source_id];
    }
    foreach ($sources_by_character_id as $character_id => $sources)
    {
        if (in_array($character_id, array_keys($characters)))
        {
            $characters[$character_id]->set_sources($sources);
        }
    }
}

?>

<?php if (empty($characters)): ?>
<p>There are no characters in the database. (Or there is a database error.)</p>
<?php else: ?>

<table class="table-wide thead-separator alternating-rows">
    <thead>
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Source</td>
            <td>Gender</td>
        </tr>
    </thead>
    <tbody>

<?php
function character_list_gender_tag($raw_gender)
{
    if ($raw_gender == 1) 
    {
        return '<span class="gender-female-icon">F</span>';
    }
    
    if ($raw_gender == 2)
    {
        return '<span class="gender-male-icon">M</span>';
    }

    return '<span class="gender-neutral-icon">?</span>';
}

foreach ($characters as $character)
{
    $formatted_sources = "";

    $source_links = array_map(
        fn($source) => "<span><a href=\"" . Router::get_url("source", "id={$source->id}") . "\">{$source->title}</a></span>",
        $character->sources()
    );
    $source_links = implode("<br>", $source_links);

    $gender = character_list_gender_tag($character->gender);

    $character_url = Router::get_url("character", "id={$character->id}");

    echo '<tr>';
    echo "<td>{$character->id}</td>";
    echo "<td><a href=\"{$character_url}\">{$character->pretty_name()}</a></td>";
    echo "<td>{$source_links}</td>";
    echo "<td>{$gender}</td>";
    echo '</tr>';
}
echo "</tbody></table>";

echo "<nav>Page: ";
for ($i = $page - Config::$max_seek_page_numbers; $i <= $page + Config::$max_seek_page_numbers; $i++)
{
    if ($i > 0 && $i <= $page_count)
    {
        $url = Router::get_url(Router::current_route(), "page={$i}");
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