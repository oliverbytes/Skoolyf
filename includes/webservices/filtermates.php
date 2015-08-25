<?php 

require_once("../initialize.php");

$input = $_GET['input'];

$html = "";

$filterby = $_GET['filterby'];

$thestudent = User::get_by_id($_GET['studentid']);

if($filterby == "schoolmates")
{
	$orgbyuser = SchoolUser::getSchoolsImIn($thestudent->id);

	$ids = array();

	foreach ($orgbyuser as $item) 
	{
		array_push($ids, $item->schoolid);
	}

	$mates = SchoolUser::getUsersInMultipleSchoolsSearch($ids, $_GET['input']);
}
else if($filterby == "batchmates")
{
	$orgbyuser = BatchUser::getBatchsImIn($thestudent->id);

	$ids = array();

	foreach ($orgbyuser as $item) 
	{
		array_push($ids, $item->batchid);
	}

	$mates = BatchUser::getUsersInMultipleBatchsSearch($ids, $_GET['input']);
}
else if($filterby == "sectionmates")
{
	$orgbyuser = SectionUser::getSectionsImIn($thestudent->id);

	$ids = array();

	foreach ($orgbyuser as $item) 
	{
		array_push($ids, $item->sectionid);
	}

	$mates = SectionUser::getUsersInMultipleSectionsSearch($ids, $_GET['input']);
}
else if($filterby == "clubmates")
{
	$orgbyuser = ClubUser::getClubsImIn($thestudent->id);

	$ids = array();

	foreach ($orgbyuser as $item) 
	{
		array_push($ids, $item->clubid);
	}

	$mates = ClubUser::getUsersInMultipleClubsSearch($ids, $_GET['input']);
}
else if($filterby == "groupmates")
{
	$orgbyuser = GroupUser::getGroupsImIn($thestudent->id);

	$ids = array();

	foreach ($orgbyuser as $item) 
	{
		array_push($ids, $item->groupid);
	}

	$mates = GroupUser::getUsersInMultipleGroupsSearch($ids, $_GET['input']);
}

if(count($mates) > 0)
{
	foreach ($mates as $mate) 
	{
		$usermate = User::get_by_id($mate->userid);

		if(!$usermate)
		{
			continue;
		}

		if($session->is_logged_in())
		{
			if($thestudent->id == $usermate->id || $session->user_id == $usermate->id)
			{
				continue;
			}
		}

		$html .= '<tr>';
		$html .= '	<td><img style="height:40px;" src="data:image/jpeg;base64, '.$usermate->picture.'" /></td>';
		$html .= '  <td><a href="student.php?id='.$usermate->id.'">'.$usermate->get_full_name().'</a></td>';

		if($session->is_logged_in())
		{
			$mateship = Friend::getFriendship($session->user_id, $usermate->id);

			if(!$mateship)
			{
				$html .= '<td><button class="btn-small button-flat-primary btnaddfriend">Add Friend<span hidden>'.$usermate->id.'</span></button></td>';
			}
			else if($mateship->pending == 1)
			{
				if($session->user_id == $mateship->userid || $mateship->touserid)
				{
					$html .= '<td><button class="btn-small button-flat-highlight btnremovefriendship">Cancel Pending<span hidden>'.$usermate->id.'</span></button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-primary disabled">Pending<span hidden>'.$usermate->id.'</span></button></td>';
				}
			}
			else
			{
				if($session->user_id == $mateship->userid || $mateship->touserid)
				{
					$html .= '<td><button class="btn-small button-flat-caution btnremovefriendship">Un Friend<span hidden>'.$usermate->id.'</span></button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-action">Friends<span hidden>'.$usermate->id.'</span></button></td>';
				}
			}
		}
		else
		{
			$html .= '	<td><button class="btn-small button-flat-primary disabled">Login to Add Friend<span hidden>'.$usermate->id.'</span></button></td>';
		}
		
		$html .= '</tr>';
	}
}
else
{
	$html .= '<tr>';
	$html .= ' <td><img src="" style="height:40px;" /></td>';
	$html .= '  <td>no mates</td>';
	$html .= '  <td></td>';
	$html .= '</tr>';
}


echo $html;

?>