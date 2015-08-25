<?php 

require_once("../initialize.php");

$sectionsselect = null;

if(isset($_GET["batchid"]))
{
	if(is_numeric($_GET["batchid"]))
	{
		$batch = Batch::get_by_id($_GET["batchid"]);

		if
		(
			SchoolUser::amIAdmin($session->user_id, $batch->schoolid) ||
			BatchUser::amIAdmin($session->user_id, $batch->id)
		)
		{
			$sections = Section::get_all_by_batchid($batch->id);

			if(count($sections) > 0)
			{
				foreach ($sections as $section) 
				{
					$sectionsselect .= "<option value='".$section->id."'>".$section->name."</option>";
				}

				if(User::get_by_id($session->user_id)->is_super_admin())
				{
					$sectionsselect .= "<option value='NOTHING'>NOTHING</option>";
				}
			}
			else
			{
				$sectionsselect .= "<option value='0'>NO SECTIONS YET</option>";
			}
		}
		else
		{
			$sectionadmins = SectionUser::getSectionsIAdminInBatch($session->user_id, $_GET["batchid"]);

			if(count($sectionadmins) > 0)
			{
				foreach ($sectionadmins as $sectionadmin) 
				{
					$section = Section::get_by_id($sectionadmin->sectionid);

					$sectionsselect .= "<option value='".$section->id."'>".$section->name."</option>";
				}

				if(User::get_by_id($session->user_id)->is_super_admin())
				{
					$sectionsselect .= "<option value='NOTHING'>NOTHING</option>";
				}
			}
			else
			{
				$sectionsselect .= "<option value='0'>NO SECTIONS YET</option>";
			}
		}
	}
	else
	{
		$sectionadmins = SectionUser::getAdminSections($session->user_id);

		if(count($sectionadmins) > 0)
		{
			foreach ($sectionadmins as $sectionadmin) 
			{
				$section = Section::get_by_id($sectionadmin->sectionid);

				$sectionsselect .= "<option value='".$section->id."'>".$section->name."</option>";
			}

			if(User::get_by_id($session->user_id)->is_super_admin())
			{
				$sectionsselect .= "<option value='NOTHING'>NOTHING</option>";
			}
		}
		else
		{
			$sectionsselect .= "<option value='0'>NO SECTIONS YET</option>";
		}
	}

	if(User::get_by_id($session->user_id)->is_super_admin())
	{
		$sectionsselect = "";
		$sections = Section::get_all();

		if(count($sections) > 0)
		{
			foreach ($sections as $section) 
			{
				$sectionsselect .= "<option value='".$section->id."'>".$section->name."</option>";
			}

			$sectionsselect .= "<option value='NOTHING'>NOTHING</option>";
		}
		else
		{
			$sectionsselect .= "<option value='0'>NO SECTIONS YET</option>";
		}
	}
}

echo $sectionsselect;

?>