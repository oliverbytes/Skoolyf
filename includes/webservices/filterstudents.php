<?php 

require_once("../initialize.php");

$input = $_GET['input'];

$html = "";

$filteredstudents = User::search($input);

if(count($filteredstudents) > 0)
{
	foreach ($filteredstudents as $object) 
	{
		$html .= '<tr>';
		$html .= '	<td><img style="height:40px;" src="data:image/jpeg;base64, '.$object->picture.' " /></td>';
		$html .= '  <td><a href="student.php?id='.$object->id.'">'.$object->get_full_name().'</a></td>';

		if(isset($_GET['schoolid']))
		{
			$school = School::get_by_id($_GET['schoolid']);

			$theuser = SchoolUser::getUser($object->id, $school->id);

			if($theuser)
			{
				if($theuser->pending == 1)
				{
					$html .= '  <td><button class="btn-small button-flat-primary disabled">Already Pending</button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-action disabled">Member</button></td>';
				}
			}
			else
			{
				$html .= '<td><button class="btn-small button-flat-primary btninvite">Invite<span hidden>'.$object->id.'</span></button></td>';
			}
		}
		else if(isset($_GET['batchid']))
		{
			$batch = Batch::get_by_id($_GET['batchid']);

			$theuser = BatchUser::getUser($object->id, $batch->id);

			if($theuser)
			{
				if($theuser->pending == 1)
				{
					$html .= '<td><button class="btn-small button-flat-primary disabled">Already Pending</button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-action disabled">Member</button></td>';
				}
			}
			else
			{
				$html .= '<td><button class="btn-small button-flat-primary btninvite">Invite<span hidden>'.$object->id.'</span></button></td>';
			}
		}
		else if(isset($_GET['sectionid']))
		{
			$section = Section::get_by_id($_GET['sectionid']);

			$theuser = SectionUser::getUser($object->id, $section->id);

			if($theuser)
			{
				if($theuser->pending == 1)
				{
					$html .= '  <td><button class="btn-small button-flat-primary disabled">Already Pending</button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-action disabled">Member</button></td>';
				}
			}
			else
			{
				$html .= '<td><button class="btn-small button-flat-primary btninvite">Invite<span hidden>'.$object->id.'</span></button></td>';
			}
		}
		else if(isset($_GET['clubid']))
		{
			$club = Club::get_by_id($_GET['clubid']);

			$theuser = ClubUser::getUser($object->id, $club->id);

			if($theuser)
			{
				if($theuser->pending == 1)
				{
					$html .= '  <td><button class="btn-small button-flat-primary disabled">Already Pending</button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-action disabled">Member</button></td>';
				}
			}
			else
			{
				$html .= '<td><button class="btn-small button-flat-primary btninvite">Invite<span hidden>'.$object->id.'</span></button></td>';
			}
		}
		else if(isset($_GET['groupid']))
		{
			$group = Group::get_by_id($_GET['groupid']);

			$theuser = GroupUser::getUser($object->id, $group->id);

			if($theuser)
			{
				if($theuser->pending == 1)
				{
					$html .= '  <td><button class="btn-small button-flat-primary disabled">Already Pending</button></td>';
				}
				else
				{
					$html .= '<td><button class="btn-small button-flat-action disabled">Member</button></td>';
				}
			}
			else
			{
				$html .= '<td><button class="btn-small button-flat-primary btninvite">Invite<span hidden>'.$object->id.'</span></button></td>';
			}
		}
		
		$html .= '</tr>';
	}
}
else
{
	$html .= '<tr>';
	$html .= ' <td><img src="" style="height:40px;" /></td>';
	$html .= '  <td>NO RESULTS</td>';
	$html .= '  <td></td>';
	$html .= '</tr>';
}


echo $html;

?>