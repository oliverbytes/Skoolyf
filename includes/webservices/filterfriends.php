<?php 

require_once("../initialize.php");

$input = $_GET['input'];

$html = "";

$friends = Friend::getFriends($_GET['studentid'], $_GET['input']);
$thestudent = User::get_by_id($_GET['studentid']);

if(count($friends) > 0)
{
	foreach ($friends as $friend) 
	{
		if($thestudent->id != $friend->touserid)
		{
			$userfriend = User::get_by_id($friend->touserid);
		}
		else
		{
			$userfriend = User::get_by_id($friend->userid);
		}

		if(!$userfriend)
		{
			continue;
		}

		if($session->is_logged_in())
		{
			if($session->user_id == $userfriend->id)
			{
				continue;
			}
		}

		$html .= '<tr>';
		$html .= '	<td><img style="height:40px;" src="data:image/jpeg;base64, '.$userfriend->picture.'" /></td>';
		$html .= '  <td><a href="student.php?id='.$userfriend->id.'">'.$userfriend->get_full_name().'</a></td>';

		if($session->is_logged_in())
		{
			$friendship = Friend::getFriendship($session->user_id, $userfriend->id);

			if(!$friendship)
			{
				$html .= '<td><button class="btn-small button-flat-primary btnaddfriend">Add Friend<span hidden>'.$userfriend->id.'</span></button></td>';
			}
			else if($friendship->pending == 1)
			{
				if($session->user_id == $friendship->userid || $friendship->touserid)
				{
					$html .= '<td><button class="btn-small button-flat-highlight btnremovefriendship">Cancel Pending<span hidden>'.$userfriend->id.'</span></button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-primary disabled">Pending<span hidden>'.$userfriend->id.'</span></button></td>';
				}
			}
			else
			{
				if($session->user_id == $friendship->userid || $friendship->touserid)
				{
					$html .= '<td><button class="btn-small button-flat-caution btnremovefriendship">Un Friend<span hidden>'.$userfriend->id.'</span></button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-action">Friends<span hidden>'.$userfriend->id.'</span></button></td>';
				}
			}
		}
		else
		{
			$html .= '	<td><button class="btn-small button-flat-primary disabled">Login to Add Friend<span hidden>'.$userfriend->id.'</span></button></td>';
		}
		
		$html .= '</tr>';
	}
}
else
{
	$html .= '<tr>';
	$html .= ' <td><img src="" style="height:40px;" /></td>';
	$html .= '  <td>no friends</td>';
	$html .= '  <td></td>';
	$html .= '</tr>';
}


echo $html;

?>