<?php
$source_found = false;
$source = array();

if (!empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
	if ($id > 0)
	{
		$db = Database::connect();
		$result = $db->query("SELECT * FROM sources WHERE id={$id} ORDER BY id ASC;");
		if ($result->num_rows == 1)
		{
			$source_found = true;
			$source_temp = $result->fetch_assoc();
	
			$source["title"] = $source_temp["title"];
	
			$source["aliases"] = array();
	
			$db = Database::connect();
			$result = $db->query("SELECT * FROM source_aliases WHERE source_id={$id} ORDER BY id ASC;");
			if ($result->num_rows > 0)
			{
				while ($alias = $result->fetch_assoc())
				{
					$source["aliases"][] = $alias["alias"];
				}
			}

			$source["characters"] = array();

			$db = Database::connect();
			$result = $db->query("SELECT * FROM conn_character_source AS conn INNER JOIN characters AS ch ON conn.character_id=ch.id WHERE conn.source_id={$id} ORDER BY ch.name ASC;");
			if ($result->num_rows > 0)
			{
				while ($conn = $result->fetch_assoc())
				{
					$displayed_name = $conn["name"];
					if (!empty($conn["original_name"]))
					{
						$displayed_name .= " ({$conn["original_name"]})";
					}

					$source["characters"][$conn["character_id"]] = $displayed_name;
				}
			}
		}
	}
}

$context_nav_buttons["Listing"] = "sources";

if ($session_is_admin)
{
	$context_nav_buttons["New"] = "source-new";
	if ($source_found)
	{
		$context_nav_buttons["Edit"] = "source-edit?id={$id}";
		$context_nav_buttons["Manage characters"] = "source-set-characters?id={$id}";
	}
}

if (isset($_GET["edited"]))
{
	$notice_success = "Source edited.";
}
else if (isset($_GET["created"]))
{
	$notice_success = "Source created.";
}
else if (isset($_GET["characters_updated"]))
{
	$notice_success = "Characters updated.";
}

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
<?php require _WEBROOT_ . "/app/views/global/notice.php"; ?>
<?php if (!$source_found): ?>
<p>Source not found.</p>
<?php else: ?>

<h2><?= $source["title"] ?></h2>
<?php foreach ($source["aliases"] as $alias): ?>
<p><?= $alias ?></P>
<?php endforeach; ?>

<?php if (!empty($source["characters"])): ?>
<div>
<p>Characters:</p>
<ul>
<?php
	foreach ($source["characters"] as $character_id => $character_name):
	$url = Routes::get_action_url("character") . "?id={$character_id}";
?>
<li><a href="<?= $url ?>"><?= $character_name ?></a></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<?php endif; ?>
</main>
<?php
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>