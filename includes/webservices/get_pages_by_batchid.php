<?php 

require_once("../initialize.php");

$accordion = "";

if(isset($_GET["batchid"]))
{
	$school = School::get_by_id($_GET["schoolid"]);
	$batch = Batch::get_by_id($_GET["batchid"]);

	$pages_folder = '../../public/schools/'.$school->id.'/yearbooks/'.$batch->id.'/pages/';
  	$pages = glob($pages_folder.'*html');

  	$pages_folder2 = 'public/schools/'.$school->id.'/yearbooks/'.$batch->id.'/pages/';

  	if(count($pages) > 0)
  	{
  		$index = 0;

  		foreach($pages as $page)
		{ 
			$index++;

			$page_filename = basename($page);

			ob_start();
			include($pages_folder.$page_filename);
			$output = ob_get_clean();

			$accordion .= '

			<div class="accordion-heading">
			  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#accordionID'.$index.'">
			    <span class="page_filename">'.$page_filename.'</span>
			  </a>
			</div>
			<div id="accordionID'.$index.'" class="accordion-body collapse">
			  <div class="accordion-inner">
			  	'.$output.'
			  </div>
			</div>

			';
		}

		echo '

		<script>

		$(".page_filename").click(function()
	  	{
	  		var pagefile = "'.$pages_folder2.'" + $(this).text();

	  		lastClickedPage = pagefile;

	  		load(pagefile);

	  		return false;
	  	});

		</script>

		';
  	}

	echo $accordion;
}
else
{
	echo "error";
}

?>