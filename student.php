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

echo "<input id='studentid' type='hidden'  value='".$_GET['id']."'>";

if(isset($_GET['id']))
{
  $user = User::get_by_id($_GET['id']);
  $batchusers = BatchUser::getBatchsImIn($user->id);

  $sectionsImIn = SectionUser::getSectionsImIn($user->id);

  $sectionIDsImIn = array();

  if(count($sectionsImIn) > 0)
  {
    foreach ($sectionsImIn as $sectionuser) 
    {
      array_push($sectionIDsImIn, $sectionuser->sectionid);
    }
  }

  $mates = SectionUser::getUsersInMultipleSections($sectionIDsImIn);

  if($session->is_logged_in())
  {
    if(!User::get_by_id($session->user_id)->is_super_admin())
    {
      if($user->pending == 1 || $user->enabled == 0)
      {
        header("location: index.php?negative");
      }
    }
  }
  else
  {
    if($user->pending == 1 || $user->enabled == 0)
      {
        header("location: index.php?negative");
      }
  }
}
else
{
  header("location: index.php?negative");
}

$orgbyuser = SchoolUser::getSchoolsImIn($user->id);

$ids = array();

foreach ($orgbyuser as $item) 
{
  array_push($ids, $item->schoolid);
}

$matesCount = count(SchoolUser::getUsersInMultipleSchools($ids));

$friendsCount = count(Friend::getFriends($user->id, ""));

?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=406156986170730";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="container-fluid">
  <div class="row-fluid">
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
              <?php echo "<img class='img-polaroid' style='width:98%;' src='data:image/jpeg;base64, ".$user->cover."' />"; ?>
            </div>
          </div>

          <div class="carousel-caption">
            <?php echo "<img class='img-polaroid img-circle' src='data:image/jpeg;base64, ".$user->picture."' />"; ?>
          </div>

          <!-- <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
          <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a> -->
        </div>
        <!-- /Carousel -->
        <table data-provides="rowlink">
          <tr><td><span class="myname"><?php echo $user->get_full_name(); ?></span></td></tr>
          <tr><td class="myheader">Motto</td></tr>
          <tr><td class="mymotto">"<?php echo $user->moto; ?>"</td></tr>
          <tr><td></td></tr>
        </table>
        <br />

        <a href="#matesBox" role="button" class="btn-mini pull-right" data-toggle="modal">
          <i class="icon-large icon-user"></i> <?php echo $matesCount; ?> Mates
        </a>

        <a href="#friendsBox" role="button" class="btn-mini pull-right" data-toggle="modal" onclick="loadFriends(); return false;">
          <i class="icon-large icon-user"></i> <?php echo $friendsCount; ?> Friends
        </a>

        <?php 

        if($session->is_logged_in())
        {
          if($session->user_id != $user->id)
          {
            $friendship = Friend::getFriendship($session->user_id, $user->id);

            if($friendship && $friendship->pending == 0)
            {
              echo'
                <button class="btn-mini btn-link pull-right btnremovefriendship"> 
                  Un Friend<span hidden>'.$user->id.'</span>
                </button>
                ';
            }
            else if($friendship && $friendship->pending == 1 && $friendship->userid == $session->user_id)
            {
              echo'
                <button class="btn-mini btn-link pull-right btnremovefriendship"> 
                  Cancel Friend Request<span hidden>'.$user->id.'</span>
                </button>
                ';
            }
            else if($friendship && $friendship->pending == 1 && $friendship->touserid == $session->user_id)
            {
              echo'
                <button class="btn-mini btn-link pull-right btnremovefriendship"> 
                  Decline Request<span hidden>'.$user->id.'</span>
                </button>
                ';
            }
            else
            {
              echo'
                <button class="btn-mini btn-link pull-right btnaddfriend"> 
                  Add Friend<span hidden>'.$user->id.'</span>
                </button>
                ';
            }

            echo 
            '
              <div class="btn-group pull-right">
                <a id="btninvite" class="btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
                  <i class="icon-large icon-circle-arrow-down"></i> Invite <i class="icon-caret-down"></i>
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
            ';

            $schoolsImIn  = SchoolUser::getSchoolsImIn($session->user_id);
            $bathsImIn    = BatchUser::getBatchsImIn($session->user_id);
            $sectionsImIn = SectionUser::getSectionsImIn($session->user_id);

            if($loggeduser->is_super_admin())
            {
              if(count($schoolsImIn) > 0)
              {
                echo '<li class="divider"> schools</li>';

                foreach ($schoolsImIn as $theuser) 
                {
                  $school = School::get_by_id($theuser->schoolid);

                  if(!$school)
                  {
                    $theuser->delete();
                  }

                  echo '<li><a href="#" onclick="invite(\'schoolid\', '.$school->id.'); return false;">'.$school->name.'</a></li>';
                }
              }
            }

            if(count($bathsImIn) > 0)
            {
              echo '<li class="divider"> batchs</li>';

              foreach ($bathsImIn as $theuser) 
              {
                $batch = Batch::get_by_id($theuser->batchid);

                if(!$batch)
                {
                  $theuser->delete();
                }

                $school = School::get_by_id($batch->schoolid);

                echo '<li><a href="#" onclick="invite(\'batchid\', '.$batch->id.'); return false;">'.$school->name.' - '.$batch->get_batchyear().'</a></li>';
              }
            }

            if(count($sectionsImIn) > 0)
            {
              echo '<li class="divider"> sections</li>';

              foreach ($sectionsImIn as $theuser) 
              {
                $section = Section::get_by_id($theuser->sectionid);

                if(!$section)
                {
                  $theuser->delete();
                }

                $school = School::get_by_id($section->schoolid);
                $batch = Batch::get_by_id($section->batchid);

                echo '<li><a href="#" onclick="invite(\'sectionid\', '.$section->id.'); return false;">'.$school->name.' - '.$batch->get_batchyear().' - '.$section->name.'</a></li>';
              }
            }

            echo
            '
                </ul>
            </div>
            ';
          }
          
          if($loggeduser->is_super_admin() || $loggeduser->id == $user->id) 
          { 
             echo 
            '
              <a href="updatestudent.php?id='.$user->id.'" class="btn-mini pull-right">
                <i class="icon-large icon-pencil"></i> Edit
              </a>
            ';
          }
        }

        ?>

      </div>

      <div class="span9 box">

        <div class="row-fluid">

          <div class="span4 boxcontent">
            <span class="myheader2">Contact Info</span>
            <table data-provides="rowlink">
              <tr><td class="myheader">Email</td></tr>
              <tr><td>
                <a href="mailto:<?php echo $user->email; ?>?Subject=Hello%20Skoolyf%20User" target="_top"><?php echo $user->email; ?></a>
              </td></tr>
              <tr><td class="myheader">Phone #</td></tr>
              <tr><td><?php echo $user->number; ?></td></tr>
              <tr><td class="myheader">Address</td></tr>
              <tr><td><?php echo $user->address; ?></td></tr>
            </table>
          </div>

          <div class="span4 boxcontent">
            <span class="myheader2">Graduated At</span>
            <table>
              <tr><td class="myheader">Batchs</td></tr>
              <?php

              if(count($batchusers) > 0)
              {
                foreach ($batchusers as $batchuser) 
                {
                  $batch  = Batch::get_by_id($batchuser->batchid);
                  $school = School::get_by_id($batch->schoolid);
 
                  if($batch->pending == 0 && $batch->enabled == 1 && $school->pending == 0 && $school->enabled == 1)
                  {
                    echo "<tr><td><a href='batch.php?id=".$batch->id."'>".$school->name." ".$batch->get_batchyear()."</a></td></tr>";
                  } 
                }
              }

              ?>
            </table>
          </div>

          <div class="span4 boxcontent">
            <span class="myheader2">Other</span>
            <table data-provides="rowlink">
              <tr><td class="myheader">Jobs</td></tr>
              <?php

              $jobs = Job::get($user->id);

              if(count($jobs) > 0)
              {
                foreach ($jobs as $job) 
                {
                  echo "<tr><td>".$job->role." at ".$job->company." from ".$job->fromdate." to ".($job->present == 1 ? 'Present' : $job->todate)."</a></td></tr>";
                }
              }

              ?>
            </table>
          </div>

        </div>

      </div>

      <div class="span9 box">
        <ul class="nav nav-tabs">
          <li class="active"><a  href="#commentstab" data-toggle="tab">Comments</a></li>
          <li><a href="#fbcomments" data-toggle="tab">Facebook Comments</a></li>
        </ul>
        
        <div class="tab-content">
          <div class="tab-pane active" id="commentstab">
            <div id="comments">
              
            </div>
            <?php 

              if($user->comments == 1)
              {
                echo
                '
                <div class="controls">                     
                  <textarea id="comment" name="comment" class="span12"></textarea>
                  <button id="btnpost" class="btn btn-mini btn-primary pull-right">Post</button>
                </div>
                ';
              }

            ?>
          </div>
          <div class="tab-pane" id="fbcomments">
            <?php 

              if($user->fbcomments == 1)
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

  <div id="matesBox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top:-40px;">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <p><h3 id="myModalLabel">Mates</h3></p>

      <div class="input-append">
        <input id="matefilter" type="text" class="span2" placeholder="search by name">
        <input id="filterby" type="hidden" value="schoolmates" /> 
        <button id="btnschoolmates" id="btn-enabled" class="btn btn-small active">School Mates</button>
        <button id="btnbatchmates" id="btn-disabled" class="btn btn-small">Batch Mates</button>
        <button id="btnsectionmates" id="btn-disabled" class="btn btn-small">Section Mates</button>
        <button id='btnrefreshmates' class='btn btn-small'><i class="icon-large icon-refresh"></i></button>
      </div>

    </div>
    <div class="modal-body">
      <p id="matesearchindicator" class="label pull-right hide"><i>Loading...</i></p>
      <table class="table">
        <tbody id="matestable">

        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
  </div>

  <div id="friendsBox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top:-40px;">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <p><h3 id="myModalLabel">Friends</h3></p>


      <div class="input-prepend input-append">
        <span class="add-on">Search</span>
        <input id="friendfilter" type="text" class="span2" placeholder="by name">
        <button id='btnrefreshfriends' class='btn'><i class="icon-large icon-refresh"></i></button>
      </div>

    </div>
    <div class="modal-body">
      <p id="friendsearchindicator" class="label pull-right hide"><i>Loading...</i></p>
      <table class="table">
        <tbody id="friendstable">

        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
  </div>

  <script>

  function invite(org, orgid)
  {

    $("#btninvite").text("Processing");
    $("#btninvite").attr("disabled", "disabled");

    $.ajax(
    {
      type: 'GET',
      url: 'includes/webservices/invite.php?'+org+'='+orgid+'&userid='+$("#studentid").val(),
      success: function(result) 
      {
        if(result == "success")
        {
          showToast("Successfully Invited", "success");   
        }
        else
        {
          $("#btninvite").text("Invite");
          $("#btninvite").removeAttr("disabled");

          bootbox.alert(result);
        }
      }
    });
  }

  $(function () 
  {

    $('#friendsBox').on('shown', function() 
    {
      loadFriends();
    });

    $('#matesBox').on('shown', function() 
    {
      loadMates();
    });

    $("#btnrefreshfriends").click(function()
    {
      loadFriends();
    });

    $("#btnrefreshmates").click(function()
    {
      loadMates();
    });

    function loadComments()
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/getcomments.php?itemid='+$("#studentid").val()+'&itemtype=user',
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
        data: {comment: thecomment, itemid: $("#studentid").val(), itemtype: "user"},
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

    $(document).on("click", ".btnaddfriend", function()
    {
      var touserid = $(this).find("span").text();

      addFriend(touserid, $(this));

      $(this).text("Processing");
      $(this).attr("disabled", "disabled");

    });

    $(document).on("click", ".btnremovefriendship", function()
    {
      var touserid = $(this).find("span").text();

      cancelFriendship(touserid, $(this));

      $(this).text("Processing");
      $(this).attr("disabled", "disabled");

    });

    function loadFriends()
    {
      $("#friendsearchindicator").removeClass("hide");

      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/filterfriends.php?studentid='+$("#studentid").val()+'&input=' + $("#friendfilter").val(),
        success: function(result)
        {
          $("#friendstable").html(result);
          $("#friendsearchindicator").addClass("hide");
        }
      });
    }

    function loadMates()
    {
      $("#matesearchindicator").removeClass("hide");

      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/filtermates.php?studentid='+$("#studentid").val()+'&input=' + $("#matefilter").val()+'&filterby=' + $("#filterby").val(),
        success: function(result)
        {
          $("#matestable").html(result);
          $("#matesearchindicator").addClass("hide");
        }
      });
    }

    $("#matefilter").keyup(function()
    {
      loadMates(); 
    });

    $("#friendfilter").keyup(function()
    {
      loadFriends();
    });

    function redrawButtons()
    {
      $("#btnschoolmates").removeClass("active");
      $("#btnbatchmates").removeClass("active");
      $("#btnsectionmates").removeClass("active");
    }

    $("#btnschoolmates").click(function()
    {
      redrawButtons();
      $(this).addClass("active");
      $("#filterby").val("schoolmates");
      loadMates(); 
    });

    $("#btnbatchmates").click(function()
    {
      redrawButtons();
      $(this).addClass("active");
      $("#filterby").val("batchmates");
      loadMates(); 
    });

    $("#btnsectionmates").click(function()
    {
      redrawButtons();
      $(this).addClass("active");
      $("#filterby").val("sectionmates");
      loadMates(); 
    });

    function addFriend(touserid, element)
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/addfriend.php?touserid='+touserid,
        success: function(result)
        {
          if(result == "success")
          {
            element.text("Cancel Friend Request");
            element.removeAttr("disabled");

            showToast("Friend Request Sent", "success");
            loadFriends();
            loadMates();
          }
          else
          {
            bootbox.alert("ERROR");
          }
        }
      });
    }

    function cancelFriendship(touserid, element)
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/cancelfriendship.php?touserid='+touserid,
        success: function(result)
        {
          if(result == "success")
          {
            element.text("Add Friend");
            element.removeAttr("disabled");

            showToast("Canceled", "success");
            loadFriends();
            loadMates();
          }
          else
          {
            bootbox.alert("ERROR");
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

  </script>

<?php require_once("footer.php"); ?>