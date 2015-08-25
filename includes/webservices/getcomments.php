<?php 

require_once("../initialize.php");

$html = "";

$objects = Comment::get_all_comments($_GET['itemid'], $_GET['itemtype']);

if(count($objects) > 0)
{
	foreach ($objects as $object) 
	{
		$user = User::get_by_id($object->userid);

		$html .= '<div class="well well-small">';
		$html .= "<img class='img-polaroid' style='width:25px;' src='data:image/jpeg;base64, ".$user->picture."' />";
		$html .= $object->comment;
		$html .= '	<p><span class="messagedate pull-right">'.$object->date.'</span></p>';
		$html .= '</div>';
	}
}

echo $html;

?>