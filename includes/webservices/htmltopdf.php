<?php 

require_once("../initialize.php");

if(
	isset($_POST['thehtml']) && $_POST['thehtml'] != '' &&
	isset($_POST['path']) && $_POST['path'] != ''
	)
{
	//if(file_exists('../../'.$_POST['path'].'Yearbook.pdf'))
	//{ 
	   //unlink('../../'.$_POST['path'].'Yearbook.pdf');
	//}

	phptopdf_html($_POST['thehtml'], '../../'.$_POST['path'], 'Yearbook.pdf');
	echo "<a href='".$_POST['path']."Yearbook.pdf' download='".$_POST['path']."Yearbook.pdf'>Download</a>";
	echo " or <a href='".$_POST['path']."Yearbook.pdf'>View</a> PDF'd Yearbook";
}
else
{
	echo "error";
}

?>