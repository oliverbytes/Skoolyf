<?php

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

require_once("header.php"); 

if(!$session->is_logged_in())
{
  header("location: index.php?negative");
}
else
{
  $user = User::get_by_id($session->user_id);

  if($user->enabled == DISABLED)
  {
    header("location: index.php?disabled");
  }
}

if(isset($_GET['id']))
{
	$batch = Batch::get_by_id($_GET['id']);
}
else
{
	header("location: index.php?negative");
}

$enableEditing = true;

if($batch->published == 1)
{
  header("location: batch.php?id=".$batch->id);
}
else
{
	if(strtotime(date("Y-m-d")) > strtotime($batch->pubdate))
	{
		$enableEditing = false;
	}
}

echo "<input id='batchid' type='hidden'  value='".$batch->id."'>";

?>
<script> var lastClickedPage = ""; </script>

<div id="pageExplorer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 1000px; margin-left: -500px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Open a Page</h3>
  </div>
  <div class="modal-body">
  	<select name="schoolselect" id="schoolselect">
	  <?php

	  $schooladmins = SchoolUser::getAdminSchools($session->user_id);

	  if(count($schooladmins) > 0)
	  {
		foreach ($schooladmins as $schooladmin) 
		{
			echo "<option value='".$schooladmin->schoolid."'>".School::get_by_id($schooladmin->schoolid)->name."</option>";
		}
	  }
	  else
	  {
		echo "<option value='0'>NO SCHOOLS YET</option>";
	  }

	  ?>
	</select>

	<select name="batchselect" id="batchselect">
	  <?php

	  if(count($schooladmins) > 0)
	  {
	  	$onlyschool = School::get_by_id($schooladmins[0]->schoolid);
	  	$batchadmins = BatchUser::getAdminBatchs($session->user_id, $onlyschool->id);

		  if(count($batchadmins) > 0)
		  {
		  	foreach ($batchadmins as $batchadmin) 
			{
				$batchselect = Batch::get_by_id($batchadmin->batchid);

				echo "<option value='".$batchadmin->batchid."'>".$batchselect->fromyear."-".($batchselect->fromyear + 1)."</option>";
			}
		  }
		  else
		  {
		  	echo "<option value='0'>NO BATCHS YET</option>";
		  }
	  }

	  ?>
	</select>

  	<br/>

    <div class="accordion" id="accordion2">
	  <div class="accordion-group" id="pagescontent">

	  </div>
	</div>

  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" id="btnopen">Open</button>
  </div>
</div>

<style>

	.pageToOpen:first-letter
	{
	    visibility: hidden;
	}

</style>

<div class="container-fluid">
  <div class="row-fluid">
  	<div class="span2 well" style="position:fixed; height:90%; overflow:scroll;">
  		<a href="yearbook.php?id=<?php echo $batch->id; ?>" class="btn btn-mini btn-info">Preview Yearbook</a><br /><br />

  		<?php if($batch->published == 1){ ?>
  			<button id="downloadyearbook" class="btn btn-mini btn-success" > View / Download Yearbook</button>
  		<?php } ?>

  		<?php if($batch->published == 0){ ?>
  			<button id="publishyearbook" class="btn btn-mini btn-success" > Publish Yearbook</button>
  		<?php } ?>
  		
  		<!-- <button onclick="showhtml();" class="btn btn-mini btn-success">Show HTML</button> -->
  		<span class="nav-header" id="test">Yearbook Pages</span>
  		<ol id="pagesToOpen" class="nav nav-list pagesol">
		  <?php

			$pages_folder = 'public/schools/'.$batch->schoolid.'/yearbooks/'.$batch->id.'/pages/';
			$pages = glob($pages_folder.'*.*', GLOB_NOSORT );

			if(count($pages))
			{
				$index = 0;

				foreach ($pages as $page) 
				{
					$index++;

					if($index == 1)
					{
						echo '<li class="active"><a class="pageToOpen" href="#">'.basename($page).'</a></li>';
					}
					else
					{
						echo '<li><a class="pageToOpen" href="#">'.basename($page).'</a></li>';
					}
				}
			}
			else
			{
				echo '<li><a href="#">NO FILES YET</a></li>';
			}

			echo "<input type='hidden' id='pagesfolder' value='".$pages_folder."' />";
			echo "<input type='hidden' id='pagescount' value='".count($pages)."' />";

		  ?>
		</ol>
	</div>

	

	<div class="span10"  style="margin-left: 200px; position:fixed;">
    	<form method="post">
    		<?php if($enableEditing) { ?>
		    <textarea name="content" id="content" style="width:100%" class="mceEditor"></textarea><br />
		    <?php } ?>
		    <?php if(!$enableEditing) { ?>
		    	Deadline is Reached. Editor is Enabled. You can now publish your Yearbook.
		    <?php } ?>
		</form>
	</div>

	

  </div><!--/row-->

  <div id="processingmodal" class="modal hide fade" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <h3 id="myModalLabel">Converting Yearbook as PDF</h3>
    </div>
    <div class="modal-body">
      <div id="yearbookloading" class="span5">
      <div class="progress progress-striped active">
      	<div class="bar bar-success" style="width: 100%;"></div>
      </div>
    </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    </div>
  </div>

  <script>

  	var invalidFileNameMessage = "The page name must not be blank, no special characters except a hyphen - or an underscore _ and include a .html extension.";

  	var html = "";

  	$("#publishyearbook").click(function()
	{
		$('#processingmodal').modal('show');

		html = "";
		var pagespath = $("#pagesfolder").val();
		var pagescount = $("#pagescount").val();

		var counter = 0;

		$("#pagesToOpen").find('li').each(function()
		{
            var page = pagespath + $(this).text();

            if(page.indexOf(".php") <= 0)
      		{
		        $.get(page, function(content) 
				{
				    html += "<br />" + content;

				    counter++;
				    if(counter == pagescount)
				    {
				    	$('#processingmodal').modal('hide');

				    	$.post('includes/webservices/htmltopdf.php', {thehtml:html, path: pagespath}, function(result)
				    	{
				    		$('#processingmodal').modal('hide');
						  	//bootbox.alert(result);
						  	window.location.href = "batch.php?id=<?php echo $batch->id; ?>";
						});
				    }
				});
		    }
		    else // students php
		    {
		        $.get("getstudentsgrid2.php?batchid="+$("#batchid").val()+"&path="+$("#pagesfolder").val(), function(content) 
				{
				    html += "<br />" + content;

				    counter++;
				    if(counter == pagescount)
				    {
				    	$.post('includes/webservices/htmltopdf.php', {thehtml:html, path: pagespath}, function(result)
				    	{
				    		$('#processingmodal').modal('hide');
						  	//bootbox.alert(result);
						  	window.location.href = "batch.php?id=<?php echo $batch->id; ?>";
						});
				    }
				});
		    }
        });
	});

	function showhtml()
	{
		bootbox.alert(html);
	}

	function load(pagefile)
  	{
  		if(pagefile.indexOf('.students.php') == -1)
        {
          	updateOpenedFile(pagefile);

			$.get(pagefile, function(content) 
			{
			    tinyMCE.activeEditor.setContent(content);
			});
        }
        else
        {
          bootbox.alert("Sorry, this file can't be previewed in the editor.");
        }
  	}

	$(function() 
	{
		$("ol.pagesol").sortable
		(
			{ 
				group: 'simple_with_animation', 
				onDrop: function  (item, targetContainer, _super) 
				{
				    var clonedItem = $('<li/>').css({height: 0});
				    var draggedElement = item.before(clonedItem);
				    item.before(clonedItem);
				    clonedItem.animate({'height': item.height()});
				    
				    item.animate(clonedItem.position(), function() 
				    {
				      clonedItem.detach();
				      _super(item);
				    });

				    renameAllPages();
				}
			}
		)
	});

	function renameAllPages()
	{
		var pagesArray 	= new Array();
		var index 		= 0;

		$('#pagesToOpen li').map(function(i,n) 
		{
			index++;

		    if($(n).text() != "")
		    {
		    	var pageObject = new Object();
			    pageObject.pageNumber = index;
			    pageObject.pageFileName = $(n).text();
			    pagesArray.push(pageObject);
		    }
		});

		$.ajax(
		{
			type: 'POST',
			url: 'includes/webservices/renamepages.php?batchid=<?php echo $batch->id; ?>',
			data: {pages : JSON.stringify(pagesArray)},
			success: function(result) 
			{
				// show toast
				showToast("Successfully Reordered.", "success");
			}
		});
	}

  	$.ajax(
	{
		type: 'GET',
		url: 'includes/webservices/get_first_page.php',
		data: {batchid : "<?php echo $batch->id ?>", schoolid : "<?php echo $batch->schoolid ?>"},
		success: function(result) 
		{
			load(result);
			updateOpenedFile(result);
		}
	});

	function updateOpenedFile(file)
	{
		lastClickedPage = file;
		$("#openedfile").text(baseName(lastClickedPage) + ".html");
	}

	function baseName(str)
	{
	   var base = new String(str).substring(str.lastIndexOf('/') + 1); 
	    if(base.lastIndexOf(".") != -1)       
	       base = base.substring(0, base.lastIndexOf("."));
	   return base;
	}

	function deletePage(pageName)
  	{
  		bootbox.confirm("Delete Page: <b><i>"+pageName+"</i></b> ?", function(result) 
  		{
		  if (result == true)
		  {
		  	$.ajax(
			{
				type: 'POST',
				url: 'includes/webservices/deletepage.php?batchid=<?php echo $batch->id; ?>',
				data: {page : pageName},
				success: function(result)
				{
					if(result == "success")
					{
						playSound("positive");
						bootbox.alert("Successfully Deleted Page: " + pageName);
						window.location.reload();
					}
					else
					{
						playSound("negative");
						bootbox.alert("Failed to Delete: " + pageName + "<br />ERROR: "+result);
					}
				}
			});
		  }
		}); 
  	}

	function rename(pageName)
  	{
  		bootbox.prompt("Rename: " + pageName.substring(2), function(result) 
  		{                
		  if (result !== null)
		  {
		  	if(result != "" && result.indexOf(".html") !== -1 && isValidFileName(result))
		  	{
			    $.ajax(
				{
					type: 'POST',
					url: 'includes/webservices/renamepage.php?batchid=<?php echo $batch->id; ?>',
					data: {oldpagename : pageName, newpagename: result.replace(".html","")},
					success: function(result)
					{
						if(result == "success")
						{
							playSound("positive");
							bootbox.alert("Successfully Renamed.");
							window.location.reload();
						}
					}
				});
			}
			else
		  	{
			  	playSound("negative");
			  	bootbox.alert(invalidFileNameMessage);
		  	}
		  }
		});
  	}

	function newpage()
  	{
  		bootbox.prompt("New Page Title ex: frontpage.html", function(result) 
  		{                
		  if (result !== null)
		  {
		  	if(result != "" && result.indexOf(".html") !== -1 && isValidFileName(result))
		  	{
			  	$.ajax(
				{
					type: 'POST',
					url: 'includes/webservices/newpage.php?batchid=<?php echo $batch->id; ?>',
					data: {newpagename: result},
					success: function(result)
					{
						if(result == "success")
						{
							playSound("positive");
							bootbox.alert("Successfully Created Page");
							window.location.reload();
						}
						else
						{
							playSound("negative");
							bootbox.alert("Failed Creating Page: <br/><br/>ERROR = " + result);
						}
					}
				});
		  	}
		  	else
		  	{
		  		playSound("negative");
			  	bootbox.alert(invalidFileNameMessage);
		  	}
		  }
		});
  	}

  	function save()
  	{
		$.ajax(
		{
			type: 'POST',
			url: 'includes/webservices/save.php',
			data: {content : tinyMCE.activeEditor.getContent(), whatpage: lastClickedPage},
			success: function(result) 
			{
				if(result == "success")
				{
					showToast("Successfully Saved.", "success");
				}
			}
		});
  	}

  	function duplicate(page)
  	{
		bootbox.prompt("Duplicate: " + page.substring(2), function(result) 
  		{                
		  if (result !== null)
		  {
		  	if(result != "" && result.indexOf(".html") !== -1 && isValidFileName(result))
		  	{
			    $.ajax(
				{
					type: 'POST',
					url: 'includes/webservices/duplicate.php?batchid=<?php echo $batch->id; ?>',
					data: {whatpage: page, duplicatedname: result},
					success: function(result)
					{
						if(result == "success")
						{
							bootbox.alert("Successfully Duplicated Page: " + page);
							window.location.reload();
						}
						else
						{
							bootbox.alert("Page: " + result + " already exists. Please try another name.");
						}
					}
				});
			}
			else
		  	{
			  	playSound("negative");
			  	bootbox.alert(invalidFileNameMessage);
		  	}
		  }
		});
  	}

  	function duplicateContext(page)
  	{
  		duplicate(page);
  	}

  	function loadSchoolSelect()
  	{
  		var schoolidPARAM = parseInt($("#schoolselect").val());

		$.ajax(
		{
			type: 'GET',
			url: 'includes/webservices/get_batch_i_admin_in_school.php',
			data: {schoolid: schoolidPARAM},
			success: function(result) 
			{
				$("#batchselect").html(result);
			}
		});

		var batchidPARAM = parseInt($("#batchselect").val());

		$.ajax(
		{
			type: 'GET',
			url: 'includes/webservices/get_pages_by_batchid.php',
			data: {batchid: batchidPARAM, schoolid: schoolidPARAM},
			success: function(result) 
			{
				$("#pageselect").html(result);
			}
		});
  	}

  	function loadBatchSelect()
  	{
  		var schoolidPARAM = parseInt($("#schoolselect").val());
		var batchidPARAM = parseInt($("#batchselect").val());

		$.ajax(
		{
			type: 'GET',
			url: 'includes/webservices/get_pages_by_batchid.php',
			data: {batchid: batchidPARAM, schoolid: schoolidPARAM},
			success: function(result) 
			{
				$("#pagescontent").html(result);
			}
		});
  	}

  	$(".pageToOpen").click(function()
	{
		load("<?php echo $pages_folder; ?>" + $(this).text());
		$("#pagesToOpen li").removeClass("active");
		$(this).parent().addClass("active");
	});

  	$("#btnopen").click(function()
	{
		load(lastClickedPage);
	});

  	$("#schoolselect").click(function()
	{
		loadSchoolSelect();
	});

	$("#batchselect").click(function()
	{
		loadBatchSelect();
	});

	var audioElement  = document.createElement('audio');

	function playSound(sound)
	{
	  audioElement.setAttribute('src', "public/sounds/" + sound + ".wav"); $.get();
	  audioElement.play();
	}

	$.toast.config.align = 'right';
	$.toast.config.closeForStickyOnly = false;
	$.toast.config.width  = 200;

	function showToast(message, type)
	{
		var options = 
		{
		  duration: 3000,
		  sticky: false,
		  type: type
		};

		$.toast(message, options);
	} 

	function isValidFileName(str) 
  	{
	   return !/[~`!#$%\^&*+=\[\]\\';,/{}|\\":<>\?]/g.test(str);
	}

  </script>

<?php require_once("footer.php"); ?>