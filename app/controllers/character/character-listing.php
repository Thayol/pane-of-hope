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
    $context_nav_buttons["New"] = "character-new";
}

require _WEBROOT_ . "/app/views/global/header.php";
?>
<main class="main">
<?php
$page = 1; // if not set
$page_count = 1; // default fallback
$page_size = Config::$listing_page_size;

$characters = Query::new(Character::class);
$page_count = ceil($characters->count() / $page_size);

if (!empty($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $page_count)
{
    $page = intval($_GET["page"]);
}

$offset = ($page - 1) * $page_size;

$character_query = Query::new(Character::class)->limit($page_size)->offset($offset);
$connector_query = Query::new(CharacterSourceConnector::class)->in("character_id", $character_query->pluck("id"));
$source_query = Query::new(Source::class)->in("id", $connector_query->pluck("source_id"));

$characters = array();
foreach ($character_query->all() as $character)
{
    $characters[$character->id] = $character;
}
$sources = array();
foreach ($source_query->all() as $source)
{
    $sources[$source->id] = $source;
}
$sources_by_character_id = array();
foreach ($connector_query->all() as $conn)
{
    $sources_by_character_id[$conn->character_id][] = $sources[$conn->source_id];
}
foreach ($sources_by_character_id as $character_id => $sources)
{
    $characters[$character_id]->set_sources($sources);
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
    $name = $character->name;

    $source_links = array_map(
        fn($source) => "<span><a href=\"" . Routes::get_action_url("source", "id={$source->id}") . "\">{$source->title}</a></span>",
        $character->sources()
    );
    $source_links = implode("<br>", $source_links);

    if ($character->original_name != null)
    {
        $name .= " ($character->original_name)";
    }

    $gender = character_list_gender_tag($character->gender);

    $character_url = Routes::get_action_url("character") . "?id={$character->id}";

    echo '<tr>';
    echo "<td>{$character->id}</td>";
    echo "<td><a href=\"{$character_url}\">{$character->name}</a></td>";
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