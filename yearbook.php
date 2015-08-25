<?php 

require_once("header.php"); 

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

if(isset($_GET['id']))
{
	$batch = Batch::get_by_id($_GET['id']);

  if($session->is_logged_in())
  {
    if(!User::get_by_id($session->user_id)->is_super_admin())
    {
      if($batch->pending == 1 || $batch->enabled == 0)
      {
        header("location: index.php?negative");
      }
    }
  }
}
else
{
	header("location: index.php?negative");
}

$pages_folder = 'public/schools/'.$batch->schoolid.'/yearbooks/'.$batch->id.'/pages/';
$pages = glob($pages_folder.'*html');

if($batch->published == 1)
{
  $enableEditing = false;
    header("location: ".$pages_folder."Yearbook.pdf");
}
else
{
  if(strtotime(date("Y-m-d")) >= strtotime($batch->pubdate))
  {
    $enableEditing = false;
  }
}

// if($batch->published == 1)
// {
//   $enableEditing = false;
//     header("location: batch.php?id=".$batch->id);
// }
// else
// {
//   if(strtotime(date("Y-m-d")) >= strtotime($batch->pubdate))
//   {
//     $enableEditing = false;
//   }
// }

echo "<input id='batchid' type='hidden'  value='".$batch->id."'>";

?>

<div class="container-fluid" >
  <div class="row-fluid" >
    <div class="span2 well" style=" width:200px; position:fixed; height:90%; overflow:scroll;">
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
            echo '<li><a class="pageToOpen yearbookpage" href="#"><span hidden>'.$page.'</span>'.basename($page).'</a></li>';
          }
        }
        else
        {
          echo '<li><a href="#">NO FILES YET</a></li>';
        }

        ?>
      </ol>
    </div>
      <div class="span11">
        <div id="pagination" style="position:fixed; margin-left: 220px; margin-top:-10px;"></div>
        <div id="content" class="well" style="margin-left: 220px; margin-top:30px; background:white;">
          
        </div>

        <div id="yearbookloading" class="span3 well hide" style="position:fixed; margin-left:500px; margin-top:100px; z-index:999;">
          <p class="offset4">Loading...</p>
          <div class="progress progress-striped active">
            <div class="bar" style="width: 100%;"></div>
          </div>
        </div>
        
      </div><!--/span-->
  </div><!--/row-->

  <script>

  $(document).ready(function()
  {

    $('#pagination').bootstrapPaginator
    ({
        currentPage: 1,
        totalPages: "<?php echo count($pages); ?>",
        alignment: "center",
        onPageClicked: function(e, originalEvent, type, page)
        {
           	$.get('includes/webservices/getpageurl.php?pagenumber=' + page + "&batchid=<?php echo $batch->id; ?>", function(pageToLoad) 
           	{
      			  $("#content").load(encodeURI(pageToLoad));
      			});
        }
    });

    $(".yearbookpage").click(function()
    {
      $("#yearbookloading").removeClass("hide");
      $("#content").addClass("hide");

      $( "#pagesToOpen li" ).each(function( index ) 
      {
        $(this).removeClass("active");
      });

      $(this).parent().addClass("active");

      var page = $(this).find("span").text();

      if(page.indexOf(".php") <= 0)
      {
        $("#content").load(page,
        function(response, status, xhr)
        {
          $("#yearbookloading").addClass("hide");
          $("#content").removeClass("hide");
          $("#content").addClass("well");
        });
      }
      else
      {
        $("#content").load("getstudentsgrid.php?batchid="+$("#batchid").val(),
        function(response, status, xhr)
        {
          $("#yearbookloading").addClass("hide");
          $("#content").removeClass("hide");
          $("#content").removeClass("well");
        });
      }

      return false;
    });

    $("#yearbookloading").removeClass("hide");
    $("#content").addClass("hide");
    $("#content").removeClass("well");

    $("#content").load("getstudentsgrid.php?batchid="+$("#batchid").val(), function(response, status, xhr)
    {
      $("#yearbookloading").addClass("hide");
      $("#content").removeClass("hide");
    });

  });

  </script>
  
<?php require_once("footer.php"); ?>