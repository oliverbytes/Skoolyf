<?php 

	require_once("../initialize.php");

	$batchs = Batch::get_all();

	$batchyears = array();

	foreach ($batchs as $batch) 
	{
		array_push($batchyears, $batch->fromyear."-".($batch->fromyear + 1));
	}

	echo json_encode($batchyears);

?>