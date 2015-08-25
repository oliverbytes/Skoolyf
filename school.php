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

echo "<input id='schoolid' type='hidden' hidden value='".$_GET['id']."'>";

if(isset($_GET['id']))
{
  $school       = School::get_by_id($_GET['id']);

  if(!$school)
  {
    header("location: index.php?negative");
  }
  
  $schoolUsers  = SchoolUser::getStudentsInSchool($school->id);

  if($session->is_logged_in())
  {
    if(!User::get_by_id($session->user_id)->is_super_admin())
    {
      if($school->pending == 1 || $school->enabled == 0)
      {
        header("location: index.php?negative");
      }
    }
  }
  else
  {
    if($school->pending == 1 || $school->enabled == 0)
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
          <!-- <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
          </ol> -->

          <div class="carousel-inner">
            <div class="active item">
              <?php echo "<img class='img-polaroid' style='width:98%;' src='data:image/jpeg;base64, ".$school->picture."' />"; ?>
            </div>
          </div>

          <div class="carousel-caption">
            <?php echo "<img src='data:image/jpeg;base64, ".$school->logo."' />"; ?>
          </div>

          <!-- <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
          <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a> -->
        </div>
        <!-- /Carousel -->
        <span class="myname"><?php echo $school->name; ?></span>

        <a href="#photosBox" role="button" class="btn-mini pull-right" data-toggle="modal">
          <i class="icon-large icon-camera"></i> Photos
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

          if($loggeduser->is_super_admin() || SchoolUser::amIAdmin($loggeduser->id, $school->id)) 
          { 
            echo 
            '
              <a href="updateschool.php?id='.$school->id.'" class="btn-mini pull-right">
              <i class="icon-large icon-pencil"></i> Edit
              </a>
            ';
          } 

          $loggedschooluser = SchoolUser::getUser($session->user_id, $school->id);

          if($loggedschooluser == null)
          {
            echo 
            ' 
              <button role="button" class="btn-mini btn-link pull-right" onclick="join(); return false;">
                <i class="icon-large icon-envelope"></i> Join
              </button>
            ';
          }
          else if($loggedschooluser != null)
          {
            if($loggedschooluser->pending == 1)
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

        if($school->comments == 1) 
        { 
          echo 
          '
            <a href="#commentsBox" role="button" class="btn-mini pull-right" data-toggle="modal">
              <i class="icon-large icon-comment"></i> Comments
            </a>
          ';
        }

        ?>

      </div>

      <div class="span9 box">
        <div class="row-fluid">
          <div class="span12 boxcontent">
            <ul class="nav nav-tabs">
              <li class="active"><a id="abouttab" href="#about" data-toggle="tab">About</a></li>
              <li><a id="historytab" href="#history" data-toggle="tab">History</a></li>
              <li><a id="visionmissiontab" href="#visionmission" data-toggle="tab">Vision & Mission</a></li>
              <li><a id="corevaluestab" href="#corevalues" data-toggle="tab">Core Values</a></li>
              <li><a id="batchestab" href="#batches" data-toggle="tab">Batches</a></li>
              <li><a id="sectionstab" href="#sections" data-toggle="tab">Sections</a></li>
              <li><a id="clubstab" href="#clubs" data-toggle="tab">Clubs</a></li>
              <li><a id="groupstab" href="#groups" data-toggle="tab">Groups</a></li>
              <li><a id="contactinfotab" href="#contactinfo" data-toggle="tab">Contact Info</a></li>
              <li><a id="newstab" href="#news" data-toggle="tab">News</a></li>
            </ul>
            
            <div class="tab-content">
              <div class="tab-pane active" id="about">
                <?php echo $school->about; ?>
              </div>
              <div class="tab-pane" id="history">
                <?php echo $school->history; ?>
              </div>
              <div class="tab-pane" id="visionmission">
                <?php echo $school->visionmission; ?>
              </div>
              <div class="tab-pane" id="corevalues">
                <?php echo $school->corevalues; ?>
              </div>
              <div class="tab-pane" id="batches" class="row-fixed">
                <?php

                $batches = Batch::get_all_by_schoolid($school->id);

                if(count($batches) > 0)
                {
                  foreach ($batches as $batch)
                  {
                    echo '<ul">';                  
                    echo '  <li><a href="batch.php?id='.$batch->id.'">'.$batch->get_batchyear().'</a></li>';
                    echo '</ul>';
                  }
                }

                ?>
              </div>
              <div class="tab-pane" id="sections">
                <?php

                $sections = Section::get_all_by_schoolid($school->id);

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
              <div class="tab-pane" id="clubs">
                <?php

                $clubs = Club::get_all_by_schoolid($school->id);

                if(count($clubs) > 0)
                {
                  foreach ($clubs as $club)
                  {
                    echo '<ul">';                  
                    echo '  <li><a href="club.php?id='.$club->id.'">'.$club->name.'</a></li>';
                    echo '</ul>';
                  }
                }                  

                ?>
              </div>
              <div class="tab-pane" id="groups">
                <?php

                $groups = Group::get_all_by_schoolid($school->id);

                if(count($groups) > 0)
                {
                  foreach ($groups as $group)
                  {
                    echo '<ul">';                  
                    echo '  <li><a href="group.php?id='.$group->id.'">'.$group->name.'</a></li>';
                    echo '</ul>';
                  }
                }
                
                ?>
              </div>
              <div class="tab-pane" id="contactinfo">
                <table data-provides="rowlink">
                  <tr><td class="myheader">Email</td></tr>
                  <tr><td>
                    <a href="mailto:<?php echo $school->email; ?>?Subject=Hello%20Skoolyf%20User" target="_top">
                      <?php echo $school->email; ?>
                    </a>
                  </td></tr>
                  <tr><td class="myheader">Phone #</td></tr>
                  <tr><td><?php echo $school->number; ?></td></tr>
                  <tr><td class="myheader">Address</td></tr>
                  <tr><td><?php echo $school->address; ?></td></tr>
                </table>
              </div>
              <div class="tab-pane" id="news">
                <?php @readfile('http://output79.rssinclude.com/output?type=php&id=764732&hash=179cdc58d60cb53c27ba0e02b822fa13')?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="span9 boxtransparent">
        <div class="row-fixed" >
          <div class="span9" style="margin:0px; padding:0px;">
            <?php

            if(count($schoolUsers) > 0)
            {
              foreach ($schoolUsers as $schoolUser) 
              {
                if($schoolUser->pending == 0 && $schoolUser->enabled == 1)
                {
                  $user = User::get_by_id($schoolUser->userid);
                  
                  if($user->pending == 0 && $user->enabled == 1)
                  {
                    echo '<div class="span2 mygridbox">';
                    echo '  <img class="img-polaroid img-circle mygridimage" src="data:image/jpeg;base64, '.$user->picture.'" />';
                    echo '  <p class="mygridname span2">'.$user->get_full_name().'</p>';

                    if($user->moto != "")
                    {
                      echo '<p class="mygridmotto">"'.$user->moto.'"</p>';
                    }

                    echo '  <p><a href="student.php?id='.$user->id.'">view profile</a></p>';
                    echo '</div>';
                  }
                } 
              }
            }

            ?>
          </div><!--/span-->
        </div><!--/row-->
      </div>

      <!-- <div class="span9 box">
        <span class="mypost">
          This is my very long post that I really have no idea what to say here and just let me type here okay?
          This is my very long post that I really have no idea what to say here and just let me type here okay?
        </span>
        <div class="mypostmedia span11">
          <img src="image.jpg" style="height:200px;" />
        </div>
        <br /><span class="mypostdatetime pull-right">via <span class="myvia">LinkedIn</span> 24 minutes ago</span>
      </div> -->

      

    </div><!--MAIN SPAN10-->
  </div><!--/row-->

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

            if($school->comments == 1)
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

            if($school->fbcomments == 1)
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

  <div id="photosBox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Photos</h3>
      <button id='btnrefresh' class='btn btn-small pull-right'><i class="icon-large icon-refresh"></i></button>
      <p>
        <input id="studentfilter" type="text" class="span2" placeholder="search by name" /> 
        <i id="searchindicator" class="label pull-right hide">Loading...</i>
      </p>
    </div>
    <div class="modal-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Invite</th>
          </tr>
        </thead>
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
    function loadComments()
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/getcomments.php?itemid='+$("#schoolid").val()+'&itemtype=school',
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
        data: {comment: thecomment, itemid: $("#schoolid").val(), itemtype: "school"},
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
      var schoolid = $("#schoolid").val();

      invite(userid, schoolid, $(this));

      $(this).text("Processing");
      $(this).attr("disabled", "disabled");

    });

    function loadStudents()
    {
      $("#searchindicator").removeClass("hide");

      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/filterstudents.php?schoolid='+$("#schoolid").val()+'&input=' + $("#studentfilter").val(),
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
        url: 'includes/webservices/invite.php?schoolid='+orgid+'&userid='+userid,
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
        url: 'includes/webservices/join.php?schoolid=<?php echo $school->id; ?>',
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
        url: 'includes/webservices/optout.php?schoolid=<?php echo $school->id; ?>',
        success: function(result) 
        {
          if(result == "success")
          {
            bootbox.alert("<b><i>You are now out in the school.</i></b> <br /><br />");
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