<?php 

require_once("../initialize.php");

if(isset($_GET['touserid']))
{
	$friendship = Friend::getFriendship($session->user_id, $_GET['touserid']);
	$friendship->delete();

	echo "success";
}
else
{
	echo "error";
}

?>