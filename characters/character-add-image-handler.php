<?php
require __DIR__ . "/../session.php";
require __DIR__ . "/../settings.php";
require __DIR__ . "/../functions.php";

if ($session_is_admin)
{
	if ($_FILES["uploadfile"]["error"] === 0)
	{
		$id = $_POST["id"];
		$file_extension = strtolower(pathinfo($_FILES["uploadfile"]["name"], PATHINFO_EXTENSION));
		$temp_file = $_FILES["uploadfile"]["tmp_name"];
		$size = $_FILES["uploadfile"]["size"];
		$type = $_FILES["uploadfile"]["type"];
		if (strpos($type, "image") === false)
		{
			header('Location: ' . action_to_link("character-upload", "id={$id}&invalid=not_image"));
			exit(0);
		}
		
		if (!($size > 0 && $size <= $max_file_upload_size))
		{
			header('Location: ' . action_to_link("character-upload", "id={$id}&invalid=size"));
			exit(0);
		}
		
		if (!in_array($file_extension, $allowed_image_extensions))
		{
			echo "Only .png accepted for now, sorry.";
		}
		
		$new_image_name = $id . '-' . md5_file($temp_file) . '.' . $file_extension;
		$image_path_full = $character_images_path . $new_image_name;
		$image_path_absolute = $character_images_path_absolute . $new_image_name;
		move_uploaded_file($temp_file, $image_path_full);
		
		$sql = "INSERT INTO character_images (character_id, path) VALUES ({$id}, '{$image_path_absolute}');";
		$result = db_query($sql);
		if ($result === true)
		{
			header('Location: ' . action_to_link("character", "id={$id}&uploaded"));
		}
		else
		{
			echo "DATABASE ERROR<pre>";
			echo $sql . "\n";
			print_r($db);
			var_dump($result);
		}
	}
	else
	{
		header("Content-Type: application/json");
		$_FILES["message"] = "There was an error while uploading. Contact an administrator.";
		$_FILES["details"] = $_FILES["uploadfile"];
		unset($_FILES["uploadfile"]);
		echo json_encode($_FILES);
	}
}
else
{
	header("Content-Type: application/json");
	echo json_encode([ "status" => "unauthorized" ]);
}
