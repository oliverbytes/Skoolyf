<?php 

require_once("../initialize.php");

$html = "";

$statuses = Status::getStatuses($_GET['itemid'], $_GET['itemtype']);

if(count($statuses) > 0)
{
	foreach ($statuses as $status) 
	{
		$html .= '<div class="well well-small">';
		$html .= $status->status;
		$html .= '	<p><span class="messagedate pull-right">'.$status->date.'</span></p>';
		$html .= '</div>';
	}
}
else
{
	$html .= '<div class="well well-small">';
	$html .= '	No statuses yet';
	$html .= '	<p><span class="messagedate pull-right">October 3, 1992</span></p>';
	$html .= '</div>';
}


echo $html;

?>