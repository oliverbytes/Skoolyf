<?php 

require_once("../initialize.php");

$batch = Batch::get_by_id($_GET['id']);
$batch->published = 1;
$batch->update();

header("location: ../../yearbook.php?id=".$batch->id)

?>