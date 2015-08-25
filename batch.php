<?php 

require_once("header.php"); 

if($session->is_logged_in())
{
  $loggeduser = User::get_by_id($session->user_id);
}

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);
$pageURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

echo "<input id='batchid' type='hidden'  value='".$_GET['id']."'>";

if(isset($_GET['id']))
{
  $batch      = Batch::get_by_id($_GET['id']);
  $school     = School::get_by_id($batch->schoolid);
  $batchUsers = BatchUser::getUsersInBatch($batch->id);

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
  else
  {
    if($batch->pending == 1 || $batch->enabled == 0)
    {
      header("location: index.php?negative");
    }
  }
}
else
{
  header("location: index.php?negative");
}

?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=406156986170730";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="container-fixed">
  <div class="row-fixed">
    <div class="span10 offset2">
      <div class="span9" hidden></div>
      <div class="span9 box">
        <!-- Carousel -->
        <div id="myCarousel" class="carousel slide">
          <div class="carousel-inner">
            <div class="active item">
              <?php echo "<img class='img-polaroid' style='width:98%;' src='data:image/jpeg;base64, ".$batch->picture."' />"; ?>
            </div>
          </div>
        </div>
        <!-- /Carousel -->

        <span class="myname"><a href="batch.php?id=<?php echo $batch->id; ?>">Batch <?php echo $batch->get_batchyear(); ?></a></span> 
        of <span class="myheader"><a href="school.php?id=<?php echo $school->id; ?>">School <?php echo $school->name; ?></a></span> 

        <a href="#statusesBox" role="button" class="btn-mini pull-right" data-toggle="modal">
          <i class="icon-large icon-comment"></i> Statuses
        </a>

        <?php 

        if($session->is_logged_in())
        {

          echo 
          '
            <a href="#invitationBox" role="button" class="btn-mini pull-right" data-toggle="modal">
              <i class="icon-large icon-comment"></i> Invite Students
            </a>
          ';
        
          if($loggeduser->is_super_admin() || BatchUser::amIAdmin($loggeduser->id, $batch->id)) 
          { 
            echo 
            '
              <a href="updatebatch.php?id='.$batch->id.'" class="btn-mini pull-right">
              <i class="icon-large icon-pencil"></i> Edit
              </a> 
            ';
          } 

          $loggedbatchuser = BatchUser::getUser($session->user_id, $batch->id);

          if($loggedbatchuser == null)
          {
            echo 
            ' 
              <button role="button" class="btn-mini btn-link pull-right" onclick="join(); return false;">
                <i class="icon-large icon-envelope"></i> Join
              </button>
            ';
          }
          else if($loggedbatchuser != null)
          {
            if($loggedbatchuser->pending == 1)
            {
              echo 
              ' 
                <button role="button" class="btn-mini btn-link pull-right" onclick="cancelpending(); return false;">
                  <i class="icon-large icon-envelope"></i> Cancel Pending
                </button>
              ';
            }
            else
            {
              echo 
              ' 
                <button role="button" class="btn-mini btn-link pull-right" onclick="optout(); return false;">
                  <i class="icon-large icon-envelope"></i> Opt Out
                </button>
              ';
            }
          }
        }

        if($batch->comments == 1) 
        { 
          echo 
          '
            <a href="#commentsBox" role="button" class="btn-mini pull-right" data-toggle="modal">
              <i class="icon-large icon-comment"></i> Comments
            </a>
          ';
        }


        ?>

        <?php if($batch->published == 0){ ?>

        <div id="clock" style="margin-top:20px;" class="alert alert-error">
          Publishing Deadline:
          <span id="weeks"></span>      Weeks
          <span id="daysLeft"></span>   Days
          <span id="hours"></span>      Hours
          <span id="minutes"></span>    Minutes and 
          <span id="seconds"></span>    Seconds Left
        </div>

        <?php } ?>

        <?php if($batch->published == 1){ ?>

        <div id="clock" style="margin-top:20px;" class="alert alert-success">
          Yearbook is Published. <a href="public/schools/<?php echo $batch->schoolid; ?>/yearbooks/<?php echo $batch->id; ?>/pages/Yearbook.pdf">View / Download Yearbook</a>
        </div>

        <?php } ?>

      </div>

      <div class="span9 box">
        <div class="row-fluid">
          <div class="span12 boxcontent">
            <ul class="nav nav-tabs">
              <li class="active"><a id="abouttab" href="#about" data-toggle="tab">About</a></li>
              <li><a id="sectionstab" href="#sections" data-toggle="tab">Sections</a></li>
              <!-- <li><a id="groupstab" href="#groups" data-toggle="tab">Groups</a></li> -->
            </ul>
            
            <div class="tab-content">
              <div class="tab-pane active" id="about">
                <?php echo $batch->about; ?>
              </div>
              <div class="tab-pane" id="sections">
                <?php

                $sections = Section::get_all_by_batchid($batch->id);

                if(count($sections) > 0)
                {
                  foreach ($sections as $section)
                  {
                    echo '<ul">';                  
                    echo '  <li><a href="section.php?id='.$section->id.'">'.$section->name.'</a></li>';
                    echo '</ul>';
                  }
                }

                ?>
              </div>
              <!-- <div class="tab-pane" id="groups">
                <?php

                // $groups = Group::get_all_by_batchid($batch->id);

                // if(count($groups) > 0)
                // {
                //   foreach ($groups as $group)
                //   {
                //     echo '<ul">';                  
                //     echo '  <li><a href="group.php?id='.$group->id.'">'.$group->name.'</a></li>';
                //     echo '</ul>';
                //   }
                // }
                
                ?>
              </div> -->
            </div>
          </div>
        </div>
      </div>

      <div class="span9 boxtransparent">
        <div class="row-fixed" >
          <div class="span10" style="margin:0px; padding:0px;">
            <?php

            if(count($batchUsers) > 0)
            {
              foreach ($batchUsers as $batchuser) 
              {
                if($batchuser->pending == 0 && $batchuser->enabled == 1)
                {
                  $user = User::get_by_id($batchuser->userid);

                  echo '<div class="span2 mygridbox">';
                  echo '<img class="img-polaroid img-circle mygridimage" src="data:image/jpeg;base64, '.$user->picture.'" />';
                  echo '<p class="mygridname span2">'.$user->get_full_name().'</p>';

                  if($user->moto != "")
                  {
                    echo '<p class="mygridmotto">"'.$user->moto.'"</p>';
                  }

                  echo '<p><a href="student.php?id='.$user->id.'">view profile</a></p>';
                  echo '</div>';
                }
              }
            }

            ?>
          </div><!--/span-->
        </div><!--/row-->
      </div>

    </div><!--MAIN SPAN10-->
  </div><!--/row-->

  <div id="statusesBox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:800px; margin-left:-400px;">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Statuses</h3>
    </div>
    <div class="modal-body" style="max-height:300px;">
      <div id="statuses">
        
      </div>
    </div>
    <div class="modal-footer">
      <div class="control-group">
        <label class="control-label  pull-left" for="about">New Status <i id="loadindicator" class="label pull-right hide">Loading...</i> </label>
        <div class="controls">                     
          <textarea id="about" name="about" class="span8"></textarea>
          <button id="btnpost" class="btn btn-primary pull-left">Post</button>
        </div>
      </div>
      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
  </div>

  <div id="commentsBox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Comments</h3>
    </div>
    <div class="modal-body">
      <ul class="nav nav-tabs">
        <li class="active"><a  href="#commentstab" data-toggle="tab">Comments</a></li>
        <li><a href="#fbcomments" data-toggle="tab">Facebook Comments</a></li>
      </ul>
      
      <div class="tab-content">
        <div class="tab-pane active" id="commentstab">
          <div id="comments">
            
          </div>
          <?php 

            if($batch->comments == 1)
            {
              echo
              '
              <div class="controls">                     
                <textarea id="comment" name="comment" class="span5"></textarea>
                <button id="btnpost" class="btn btn-mini btn-primary pull-right">Post</button>
              </div>
              ';
            }

          ?>
        </div>
        <div class="tab-pane" id="fbcomments">
          <?php 

            if($batch->fbcomments == 1)
            {
              echo '<div class="fb-comments" data-colorscheme="light" data-width="760" href="'.$pageURL.'"></div>';
            }
            else
            {
              echo "disabled";
            }

          ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
  </div>

  <div id="invitationBox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Invite Students</h3>
      <button id='btnrefresh' class='btn btn-small pull-right'><i class="icon-large icon-refresh"></i></button>
      <p>
        <input id="studentfilter" type="text" class="span2" placeholder="search by name" /> 
        <i id="searchindicator" class="label pull-right hide">Loading...</i>
      </p>
    </div>
    <div class="modal-body">
      <table class="table">
        <tbody id="studentstable">

        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
  </div>

  <script>

  $(function () 
  {

    var count = 0;

    $('div#clock').countdown("<?php echo str_replace('-', '/', $batch->pubdate); ?>", function(event) 
    {
      var $this = $(this);

      switch(event.type) 
      {
        case "seconds":
        case "minutes":
        case "hours":
        case "days":
        case "weeks":
        case "daysLeft":

          $this.find('span#'+event.type).html(event.value);
          break;

        case "finished":

          count++;

          //$(this).hide();

          if("<?php echo $batch->published; ?>" == 0 && count == 2)
          {
            bootbox.alert("Yearbook's Publishing Date Reached. Editing is Locked!");
            //$(this).hide();
            //window.location.reload();
          }
          
          break;
      }
    });

    function loadComments()
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/getcomments.php?itemid='+$("#batchid").val()+'&itemtype=batch',
        success: function(result)
        {
          $("#comments").html(result);
          $("#loadindicator").addClass("hide");
        }
      });
    }

    loadComments();

    $(document).on("click", "#btnpost", function()
    {
      var comment = $("#comment").val();

      addComment(comment, $(this));

      $(this).text("Processing");
      $(this).attr("disabled", "disabled");

    });

    function addComment(thecomment, element)
    {
      $.ajax(
      {
        type: 'POST',
        data: {comment: thecomment, itemid: $("#batchid").val(), itemtype: "batch"},
        url: 'includes/webservices/addcomment.php',
        success: function(result)
        {
          if(result == "success")
          {
            element.text("Post");
            element.removeAttr("disabled");

            showToast("Comment Posted", "success");
            loadComments();
          }
          else
          {
            bootbox.alert("ERROR");
          }
        }
      });
    }

    $('#invitationBox').on('shown', function() 
    {
      loadStudents();
    });

    $('#statusesBox').on('shown', function() 
    {
      loadStatuses();
    });

    $('#commentsBox').on('shown', function() 
    {
      
    });

    $('#photosBox').on('shown', function() 
    {
      
    });

    $("#btnrefresh").click(function()
    {
      loadStudents();
    });

    $("#studentfilter").keyup(function()
    {
      $("#searchindicator").removeClass("hide");
      loadStudents(); 
    });

    $(document).on("click", ".btninvite", function()
    {
      var userid = $(this).find("span").text();
      var batchid = $("#batchid").val();

      invite(userid, batchid, $(this));

      $(this).text("Processing");
      $(this).attr("disabled", "disabled");

    });

    function loadStudents()
    {
      $("#searchindicator").removeClass("hide");
      
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/filterstudents.php?batchid='+$("#batchid").val()+'&input=' + $("#studentfilter").val(),
        success: function(result)
        {
          $("#studentstable").html(result);
          $("#searchindicator").addClass("hide");
        }
      });
    }

    function invite(userid, orgid, element)
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/invite.php?batchid='+orgid+'&userid='+userid,
        success: function(result) 
        {
          if(result == "success")
          {
            showToast("Successfully Invited", "success");
            element.text("Pending");
          }
          else
          {
            bootbox.alert(result);
          }
        }
      });
    }

    function loadStatuses()
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/getstatuses.php?itemid='+$("#batchid").val()+'&itemtype=batch',
        success: function(result)
        {
          $("#statuses").html(result);
          $("#loadindicator").addClass("hide");
        }
      });
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

  });

    function join()
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/join.php?batchid=<?php echo $batch->id; ?>',
        success: function(result) 
        {
          if(result == "success")
          {
            bootbox.alert("<b><i>Join Request Sent</i></b> <br /><br /> Please wait till the admin confirms you.");
            window.location.reload();
          }
          else
          {
            bootbox.alert(result);
          }
        }
      });
    }

    function cancelpending()
    {
      optout();
    }

    function optout()
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/optout.php?batchid=<?php echo $batch->id; ?>',
        success: function(result) 
        {
          if(result == "success")
          {
            bootbox.alert("<b><i>You are now out in the batch.</i></b> <br /><br />");
            window.location.reload();
          }
          else
          {
            bootbox.alert(result);
          }
        }
      });
    }

  </script>

<?php require_once("footer.php"); ?>