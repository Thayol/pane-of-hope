<?php
function action_to_link($action = "") : string
{
	global $absolute_prefix;
	
	$absolute = '/';
	if (!empty($action))
	{
		$absolute .= $action . '/';
	}
	
	return $absolute_prefix . $absolute;
}