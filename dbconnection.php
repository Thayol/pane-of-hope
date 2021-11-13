<?php

$db = new mysqli($mysql_addr, $mysql_user, $mysql_pass, $mysql_db);

if ($db->connect_error)
{
	echo "There was a problem while contacting the database.";
	// http_response_code(500);
	exit(0);
}