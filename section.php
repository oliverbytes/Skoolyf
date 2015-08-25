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

echo "<input id='sectionid' type='hidden' hidden value='".$_GET['id']."'>";

if(isset($_GET['id']))
{
  $section  = Section::get_by_id($_GET['id']);

  if(!$section)
  {
    header("location: index.php?negative");
  }
  
  $school   = School::get_by_id($section->schoolid);
  $batch    = Batch::get_by_id($section->batchid);

  $sectionUsers = SectionUser::getUsersInSection($section->id);

  if($session->is_logged_in())
  {
    if(!User::get_by_id($session->user_id)->is_super_admin())
    {
      if($section->pending == 1 || $section->enabled == 0)
      {
        header("location: index.php?negative");
      }
    }
  }
  else
  {
    if($section->pending == 1 || $section->enabled == 0)
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
              <?php echo "<img class='img-polaroid' style='width:98%;' src='data:image/jpeg;base64, ".$section->picture."' />"; ?>
            </div>
          </div>
        </div>
        <!-- /Carousel -->

        <span class="myname"><a href="section.php?id=<?php echo $section->id; ?>"><?php echo $section->name; ?></a></span> 
        of <span class="myheader"><a href="school.php?id=<?php echo $school->id; ?>"><?php echo $school->name; ?></a></span> 
        of <span class="myheader"><a href="batch.php?id=<?php echo $batch->id; ?>">Batch <?php echo $batch->get_batchyear(); ?></a></span>
      
        <?php 

        if($session->is_logged_in())
        {
          echo 
          '
            <a href="#invitationBox" role="button" class="btn-mini pull-right" data-toggle="modal">
              <i class="icon-large icon-comment"></i> Invite Students
            </a>
          ';

          if($loggeduser->is_super_admin() || SectionUser::amIAdmin($loggeduser->id, $section->id)) 
          { 
            echo 
            '
              <a href="updatesection.php?id='.$section->id.'" class="btn-mini pull-right">
              <i class="icon-large icon-pencil"></i> Edit
              </a>
            ';
          } 

          $loggedsectionuser = SectionUser::getUser($session->user_id, $section->id);

          if($loggedsectionuser == null)
          {
            echo 
            ' 
              <button role="button" class="btn-mini btn-link pull-right" onclick="join(); return false;">
                <i class="icon-large icon-envelope"></i> Join
              </button>
            ';
          }
          else if($loggedsectionuser != null)
          {
            if($loggedsectionuser->pending == 1)
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

        if($section->comments == 1) 
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
              <li><a id="advisermessagetab" href="#advisermessage" data-toggle="tab">Adviser's Message</a></li>
              <!-- <li><a id="groupstab" href="#groups" data-toggle="tab">Groups</a></li> -->
            </ul>
            
            <div class="tab-content">
              <div class="tab-pane active" id="about">
                <?php echo $section->about; ?>
              </div>
              <div class="tab-pane" id="advisermessage">
                <?php echo $section->advisermessage; ?>
              </div>
              <!-- <div class="tab-pane" id="groups">
                <?php

                // $groups = Group::get_all_by_sectionid($section->id);

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
          <div class="span9" style="margin:0px; padding:0px;">
            <?php

            if(count($sectionUsers) > 0)
            {
              foreach ($sectionUsers as $sectionUser) 
              {
                if($sectionUser->pending == 0 && $sectionUser->enabled == 1)
                {
                  $user = User::get_by_id($sectionUser->userid);
                  
                  if($user->pending == 0 && $user->enabled == 1)
                  {
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
            }

            ?>
          </div><!--/span-->
        </div><!--/row-->
      </div>

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

            if($section->comments == 1)
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

            if($section->fbcomments == 1)
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
    function loadComments()
    {
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/getcomments.php?itemid='+$("#sectionid").val()+'&itemtype=section',
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
        data: {comment: thecomment, itemid: $("#sectionid").val(), itemtype: "section"},
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
      var sectionid = $("#sectionid").val();

      invite(userid, sectionid, $(this));

      $(this).text("Processing");
      $(this).attr("disabled", "disabled");

    });

    function loadStudents()
    {
      $("#searchindicator").removeClass("hide");
      
      $.ajax(
      {
        type: 'GET',
        url: 'includes/webservices/filterstudents.php?sectionid='+$("#sectionid").val()+'&input=' + $("#studentfilter").val(),
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
        url: 'includes/webservices/invite.php?sectionid='+orgid+'&userid='+userid,
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
        url: 'includes/webservices/join.php?sectionid=<?php echo $section->id; ?>',
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
        url: 'includes/webservices/optout.php?sectionid=<?php echo $section->id; ?>',
        success: function(result) 
        {
          if(result == "success")
          {
            bootbox.alert("<b><i>You are now out in the section.</i></b> <br /><br />");
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