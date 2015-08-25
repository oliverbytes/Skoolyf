<?php 

require_once("../initialize.php");

$batchsselect = null;

if(isset($_GET["schoolid"]))
{
	if(SchoolUser::amIAdmin($session->user_id, $_GET["schoolid"]))
	{
		$batchadmins = BatchUser::getBatchsImIn($session->user_id);
	}
	else
	{
		$batchadmins = BatchUser::getBatchsIAdminInSchool($session->user_id, $_GET["schoolid"]);
	}

	if(count($batchadmins) > 0)
	{
		foreach ($batchadmins as $batchadmin) 
		{
			$batch = Batch::get_by_id($batchadmin->batchid);

			$batchsselect .= "<option value='".$batch->id."'>".$batch->fromyear."-".($batch->fromyear + 1)."</option>";
		}

		if(User::get_by_id($session->user_id)->is_super_admin())
		{
			$batchsselect .= "<option value='NOTHING'>NOTHING</option>";
		}
	}
	else
	{
		$batchsselect .= "<option value='0'>NO BATCHS YET</option>";
	}

	if(User::get_by_id($session->user_id)->is_super_admin())
	{
		$batchsselect = "";
		$batchs = Batch::get_all();

		if(count($batchs) > 0)
		{
			foreach ($batchs as $batch) 
			{
				$batchsselect .= "<option value='".$batch->id."'>".$batch->fromyear."-".($batch->fromyear + 1)."</option>";
			}

			$batchsselect .= "<option value='NOTHING'>NOTHING</option>";
		}
		else
		{
			$batchsselect .= "<option value='0'>NO BATCHS YET</option>";
		}
	}

	echo $batchsselect;
}
else
{
	echo "error";
}

?>