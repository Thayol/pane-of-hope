<?php

require_once __DIR__ . "/../../init.php";

if (!empty($handler))
{
	if (Routes::handler_exists($handler))
	{
		require _WEBROOT_ . "/" . Routes::get_handler($handler);
	}
	else
	{
		require _WEBROOT_ . "/" . Routes::error();
	}
}
else
{
	if (Routes::action_exists($action))
	{
		require _WEBROOT_ . "/" . Routes::get_action($action);
	}
	else
	{
		require _WEBROOT_ . "/" . Routes::error();
	}
}
