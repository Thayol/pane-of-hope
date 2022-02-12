<?php

if ($session_is_admin)
{
    if ($_FILES["uploadfile"]["error"] === 0)
    {
        $character_id = $_POST["id"];
        $file_extension = strtolower(pathinfo($_FILES["uploadfile"]["name"], PATHINFO_EXTENSION));
        $temp_file = $_FILES["uploadfile"]["tmp_name"];
        $size = $_FILES["uploadfile"]["size"];
        $type = $_FILES["uploadfile"]["type"];
        if (strpos($type, "image") === false)
        {
            header('Location: ' . Routes::get_action_url("character-upload", "id={$id}&invalid=not_image"));
            exit(0);
        }

        if (!($size > 0 && $size <= Config_Uploads::$max_file_size))
        {
            header('Location: ' . Routes::get_action_url("character-upload", "id={$id}&invalid=size"));
            exit(0);
        }

        if (!in_array($file_extension, Config_Uploads::$allowed_image_extensions))
        {
            echo "Only .png accepted for now, sorry.";
        }

        $new_image_name = $character_id . '-' . md5_file($temp_file) . '.' . $file_extension;
        $image_path_full = Config_Uploads::$character_images_path . "/" . $new_image_name;
        $image_path_absolute = Config_Uploads::$character_images_path_absolute . "/" . $new_image_name;
        move_uploaded_file($temp_file, $image_path_full);

        if (CharacterImage::insert()->values([ $character_id, $image_path_absolute ])->commit() !== false)
        {
            header('Location: ' . Routes::get_action_url("character", "id={$character_id}&uploaded"));
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
