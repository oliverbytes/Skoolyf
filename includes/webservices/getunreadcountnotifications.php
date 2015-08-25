<?php

require_once("../initialize.php");

$html = "";

if($session->is_logged_in())
{
	if(isset($_GET['touserid']))
	{
		$unreadnotifications = Notification::get_unread($session->user_id);

		if(count($unreadnotifications))
		{
			$html .= '<span class="label label-important">';
			$html .= count($unreadnotifications);
			$html .= '</span>';
		}
	}
	else
	{
		$html = "error";
	}
}

echo $html;

?>