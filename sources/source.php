<?php
$source_found = false;
$source = array();

if (!empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
	if ($id > 0)
	{
		$db = db_connect();
		$result = $db->query("SELECT * FROM sources WHERE id={$id} ORDER BY id ASC;");
		if ($result->num_rows == 1)
		{
			$source_found = true;
			$source_temp = $result->fetch_assoc();
			
			$source["title"] = $source_temp["title"];
			
			$source["aliases"] = array();
			
			$db = db_connect();
			$result = $db->query("SELECT * FROM source_aliases WHERE source_id={$id} ORDER BY id ASC;");
			if ($result->num_rows > 0)
			{
				while ($alias = $result->fetch_assoc())
				{
					$source["aliases"][] = $alias["alias"];
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

?>
<html>
<head>
<?php
require __DIR__ . "/../head.php";
?>
</head>
<body>
<?php
require __DIR__ . "/../header.php";
?>
<main class="main">
<?php require __DIR__ . "/../notice.php"; ?>
<?php if (!$source_found): ?>
<p>Source not found.</p>
<?php else: ?>
<h2><?= $source["title"] ?></h2>
<?php foreach ($source["aliases"] as $alias): ?>
<p><?= $alias ?></P>
<?php endforeach; ?>
<?php endif; ?>
</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>