<?php 

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

ob_start();

$message  = "";
$sound    = "";

require_once("includes/initialize.php");

if($session->is_logged_in())
{
  $user = User::get_by_id($session->user_id);

  if($user)
  {
    if($user->is_super_admin())
    {
      $schoolsIAdminCount  = count(School::get_all());
      $batchsIAdminCount   = count(Batch::get_all());
      $sectionsIAdminCount = count(Section::get_all());
    }
    else
    {
      $schoolsIAdminCount  = count(SchoolUser::getAdminSchools($session->user_id));
      $batchsIAdminCount   = count(BatchUser::getAdminBatchs($session->user_id));
      $sectionsIAdminCount = count(SectionUser::getAdminSections($session->user_id));
    }

    $iAdminSomething = false;

    if(($schoolsIAdminCount + $batchsIAdminCount + $sectionsIAdminCount) > 0 || $user->is_super_admin())
    {
      $iAdminSomething = true;
    }
  }
  else
  {
    header("location: public/functions/logout.php");
  }  
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo APP_TITLE; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="skoolyf - Digital Yearbook Builder & Social Network">
    <meta name="author" content="Nemory Development Studios">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <!--SCRIPTS-->
    <script src="public/js/jquery.js"></script>
    <script> $.ajaxSetup({ cache: false }); </script>
    <script src="public/jqueryui/js/jquery-1.9.1.js"></script>
    <script src="public/jqueryui/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="public/js/i18n/grid.locale-en.js"></script>
    <script src="public/js/jquery.jqGrid.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/bootbox.min.js"></script>
    <script src="public/js/bootstrap-datepicker.js"></script>
    <script src="public/js/less.js"></script>
    <script src="public/js/bootstrap-fileupload.min.js"></script>
    <script src="public/js/sortable.js"></script>
    <script src="public/js/contextjs.js"></script>
    <script src="public/js/bootstrap-colorpicker.js"></script>
    <script src="public/js/docs.js"></script>
    <script src="public/js/bootstrap-paginator.min.js"></script>
    <script src="public/js/bootstrap-rowlink.min.js"></script>
    <script src="public/tinymce/tinymce.min.js"></script>
    <script src="public/js/buttons.js"></script>
    <script src="public/js/jquery.toast.min.js"></script>
    <script src="public/js/jquery.countdown.min.js"></script>
    <script>

      var initObject = 
      {
        mode : "specific_textareas",
        editor_selector : "mceEditor",
        theme: "modern",
        plugin_preview_width : "1200",
        plugins: 
        [
            "save advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor"
        ],
        toolbar1: "fontselect |  fontsizeselect | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | media | forecolor backcolor",
        image_advtab: true,
        removed_menuitems: 'newdocument',
        templates: 
        [
            {title: 'Front Page', url: 'public/templates/0.frontpage.html', description: 'Front Page Template'},
            {title: 'Back Page', url: 'public/templates/0.backpage.html', description: 'description'},
            {title: 'With Background', url: 'public/templates/0.withbackground.html', description: 'description'},
            {title: 'With Background Image', url: 'public/templates/0.withbackgroundimage.html', description: 'description'}
        ],
        height: 450,
        setup: function(editor)
        {
          editor.addMenuItem('setbackgroundcolor', 
          {
              text: 'Set Background Color',
              context: 'edit',
              onclick: function() 
              {
                setBackgroundColor();
              }
          });

          editor.addMenuItem('newpage', 
          {
              text: 'New Page',
              context: 'file',
              onclick: function() 
              {
                  newpage();
              }
          });

          editor.addMenuItem('save', 
          {
              text: 'Save',
              context: 'file',
              onclick: function() 
              {
                  save();
              }
          });

          editor.addMenuItem('saveastemplate', 
          {
              text: 'Save as Template',
              context: 'file',
              onclick: function() 
              {
                  saveastemplate();
              }
          });

          editor.addMenuItem('open', 
          {
              text: 'Open',
              context: 'file',
              onclick: function() 
              {
                $('#pageExplorer').modal();
              }
          });

          editor.addMenuItem('duplicate', 
          {
              text: 'Duplicate',
              context: 'file',
              onclick: function() 
              {
                  duplicate(baseName(lastClickedPage));
              }
          });

          editor.addMenuItem('rename', 
          {
              text: 'Rename',
              context: 'file',
              onclick: function() 
              {
                  rename(baseName(lastClickedPage) + ".html");
              }
          });

          editor.addMenuItem('delete', 
          {
              text: 'Delete',
              context: 'file',
              onclick: function() 
              {
                  deletePage(baseName(lastClickedPage) + ".html");
              }
          });
        },
        save_onsavecallback: function()
        {
          save();
        }
      }

      tinymce.init(initObject);

      function setBackgroundColor()
      {
        $( "#dialog" ).dialog({
          modal: true,
          buttons: 
          {
            Apply: function() 
            { 
              
              // initObject.content_css = ;
              // tinymce.init(initObject);
              $( this ).dialog( "close" );
            },
            Cancel: function() 
            { 
              $( this ).dialog( "close" );
            }
          }
        });
      }

    $(function()
    {
      $('#cp2').colorpicker().on('changeColor', function(ev)
      {
        var colorHEX = ev.color.toHex();
        console.log("color: " + colorHEX);
      });

    });

  </script>
  <script> $.ajaxSetup({ cache: false }); </script>
  <!--STYLES-->
    <link rel="stylesheet" href="public/css/bootstrapui/jquery-ui-1.10.0.custom.css" />
    <link href="public/css/ui.jqgrid.css" rel="stylesheet" media="screen" />
    <link href="public/css/bootstrap.css" rel="stylesheet">
    <link href="public/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="public/css/datepicker.css" rel="stylesheet">
    <link href="public/less/datepicker.less" rel="stylesheet/less" />
    <link href="public/css/bootstrap-fileupload.min.css" rel="stylesheet">
    <link href="public/css/bootstrap-colorpicker.css" rel="stylesheet">
    <link href="public/less/bootstrap-colorpicker.less" rel="stylesheet/less" />
    <link rel="icon" type="image/ico" href="public/img/favicon.ico"/>
    <link href="public/css/bootstrap-rowlink.min.css" rel="stylesheet">
    <link href="public/css/profile.css" rel="stylesheet">
    <link href="public/css/mygrid.css" rel="stylesheet">
    <link href="public/css/buttons.css" rel="stylesheet">
    <link href="public/css/index.css" rel="stylesheet">
    <link href="public/css/jquery.toast.min.css" rel="stylesheet">
    <style>

      body 
      {
        background-color: #EDEDED;
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav 
      {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right 
        {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }

      body.dragging, body.dragging * 
      {
        cursor: move !important;
      }

      .dragged 
      {
        position: absolute;
        opacity: 0.5;
        z-index: 2000;
      }

      ol.pagesol li.placeholder 
      {
        position: relative;
        background-color: #E01B5D;
        padding: 1px;
      }
      ol.pagesol li.placeholder:before 
      {
        position: absolute;
      }

      .typeahead_wrapper { display: block; height: 30px; }
      .typeahead_photo { float: left; max-width: 30px; max-height: 30px; margin-right: 5px; }
      .typeahead_labels { float: left; height: 30px; }
      .typeahead_primary { font-size: 15px;}
      .typeahead_secondary { font-size: .8em; margin-top: -5px; }

      ul.typeahead
      {
        width: 300px;
      }

    </style>
  </head>
  <body>
    <div id="dialog" title="Set Background Color" class="hide">
      <input type="text" class="span3" value="rgb(0,194,255,0.78)" id="cp2" data-color-format="rgba" >
    </div>
    <div class="navbar navbar navbar-fixed-top">
      <div class="navbar-inner" class="nav-collapse collapse">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.php"><img style="height: 20px;" src="public/img/logoimage.png"></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <!-- <li id="index"><a href="index.php"><i class="icon-large icon-home"></i> Home</a></li> -->
              <ul class="nav">
                <li id="createdropdown" class="dropdown">
                  <a href="school.php?id=26" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-large icon-home"></i> CSNT-R
                    <b class="caret"></b>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="dropdown-submenu">
                      <a tabindex="-1" href="yearbooks.php?schoolid=<?php echo CSNTRID; ?>">Yearbooks</a>
                      <ul class="dropdown-menu">
                        <?php

                        $objects = Batch::get_all_by_schoolid(CSNTRID);

                        if(count($objects) > 0)
                        {
                          foreach ($objects as $theobject) 
                          {
                            echo 
                            '
                             <li class="dropdown-submenu">
                              <a tabindex="-1" href="yearbook.php?id='.$theobject->id.'">'.$theobject->get_batchyear().'</a>
                              <ul class="dropdown-menu">
                            ';

                            echo '<li><a href="yearbook.php?id='.$theobject->id.'"><i class="icon-large icon-play"></i> View</a></li>';
                            
                            if($session->is_logged_in())
                            {
                              if(SchoolUser::amIAdmin($session->user_id, $theobject->schoolid) || BatchUser::amIAdmin($session->user_id, $theobject->id) || $user->is_super_admin())
                              {
                                echo '<li><a href="updateyearbook.php?id='.$theobject->id.'"><i class="icon-large icon-pencil"></i> Edit</a></li>';
                              }
                            }

                            echo '
                              </ul>
                            </li>';
                          }
                        }
                        else
                        {
                          echo '<li class="disabled"><a href="#">no batches yet</a></li>';
                        }

                        ?>
                      </ul>
                    </li>
                    <li class="dropdown-submenu">
                      <a tabindex="-1" href="batchs.php?schoolid=<?php echo CSNTRID; ?>">Batchs</a>
                      <ul class="dropdown-menu">
                        <?php

                        $objects = Batch::get_all_by_schoolid(CSNTRID);

                        if(count($objects) > 0)
                        {
                          foreach ($objects as $theobject) 
                          {
                            echo 
                            '
                             <li class="dropdown-submenu">
                              <a tabindex="-1" href="batch.php?id='.$theobject->id.'">'.$theobject->get_batchyear().'</a>
                              <ul class="dropdown-menu">
                            ';

                            echo '<li><a href="batch.php?id='.$theobject->id.'"><i class="icon-large icon-play"></i> View</a></li>';
                            
                            if($session->is_logged_in())
                            {
                              if(SchoolUser::amIAdmin($session->user_id, $theobject->schoolid) || BatchUser::amIAdmin($session->user_id, $theobject->id) || $user->is_super_admin())
                              {
                                echo '<li><a href="updatebatch.php?id='.$theobject->id.'"><i class="icon-large icon-pencil"></i> Edit</a></li>';
                              }
                            }

                            echo '
                              </ul>
                            </li>';
                          }
                        }
                        else
                        {
                          echo '<li class="disabled"><a href="#">no batches yet</a></li>';
                        }

                        ?>
                      </ul>
                    </li>
                    <li class="dropdown-submenu">
                      <a tabindex="-1" href="sections.php?schoolid=<?php echo CSNTRID; ?>">Sections</a>
                      <ul class="dropdown-menu">
                        <?php

                        $objects = Section::get_all_by_schoolid(CSNTRID);

                        if(count($objects) > 0)
                        {
                          foreach ($objects as $theobject) 
                          {
                            echo 
                            '
                             <li class="dropdown-submenu">
                              <a tabindex="-1" href="section.php?id='.$theobject->id.'">'.$theobject->name.'</a>
                              <ul class="dropdown-menu">
                            ';

                            echo '<li><a href="section.php?id='.$theobject->id.'"><i class="icon-large icon-play"></i> View</a></li>';
                            
                            if($session->is_logged_in())
                            {
                              if(SchoolUser::amIAdmin($session->user_id, $theobject->schoolid) || BatchUser::amIAdmin($session->user_id, $theobject->batchid) || SectionUser::amIAdmin($session->user_id, $theobject->id) || $user->is_super_admin())
                              {
                                echo '<li><a href="updatesection.php?id='.$theobject->id.'"><i class="icon-large icon-pencil"></i> Edit</a></li>';
                              }
                            }

                            echo '
                              </ul>
                            </li>';
                          }
                        }
                        else
                        {
                          echo '<li class="disabled"><a href="#">no sections yet</a></li>';
                        }

                        ?>
                      </ul>
                    </li>
                    <li class="dropdown-submenu">
                      <a tabindex="-1" href="clubs.php?schoolid=<?php echo CSNTRID; ?>">Clubs</a>
                      <ul class="dropdown-menu">
                        <?php

                        $objects = Club::get_all_by_schoolid(CSNTRID);

                        if(count($objects) > 0)
                        {
                          foreach ($objects as $theobject) 
                          {
                            echo 
                            '
                             <li class="dropdown-submenu">
                              <a tabindex="-1" href="club.php?id='.$theobject->id.'">'.$theobject->name.'</a>
                              <ul class="dropdown-menu">
                            ';

                            echo '<li><a href="club.php?id='.$theobject->id.'"><i class="icon-large icon-play"></i> View</a></li>';
                            
                            if($session->is_logged_in())
                            {
                              if(SchoolUser::amIAdmin($session->user_id, $theobject->schoolid) || ClubUser::amIAdmin($session->user_id, $theobject->id) || $user->is_super_admin())
                              {
                                echo '<li><a href="updateclub.php?id='.$theobject->id.'"><i class="icon-large icon-pencil"></i> Edit</a></li>';
                              }
                            }

                            echo '
                              </ul>
                            </li>';
                          }
                        }
                        else
                        {
                          echo '<li class="disabled"><a href="#">no clubs yet</a></li>';
                        }

                        ?>
                      </ul>
                    </li>
                    <li class="dropdown-submenu">
                      <a tabindex="-1" href="groups.php?schoolid=<?php echo CSNTRID; ?>">Groups</a>
                      <ul class="dropdown-menu">
                        <?php

                        $objects = Group::get_all_by_schoolid(CSNTRID);

                        if(count($objects) > 0)
                        {
                          foreach ($objects as $theobject) 
                          {
                            echo 
                            '
                             <li class="dropdown-submenu">
                              <a tabindex="-1" href="group.php?id='.$theobject->id.'">'.$theobject->name.'</a>
                              <ul class="dropdown-menu">
                            ';

                            echo '<li><a href="group.php?id='.$theobject->id.'"><i class="icon-large icon-play"></i> View</a></li>';
                            
                            if($session->is_logged_in())
                            {
                              if(SchoolUser::amIAdmin($session->user_id, $theobject->schoolid) || GroupUser::amIAdmin($session->user_id, $theobject->id) || $user->is_super_admin())
                              {
                                echo '<li><a href="updategroup.php?id='.$theobject->id.'"><i class="icon-large icon-pencil"></i> Edit</a></li>';
                              }
                            }

                            echo '
                              </ul>
                            </li>';
                          }
                        }
                        else
                        {
                          echo '<li class="disabled"><a href="#">no clubs yet</a></li>';
                        }

                        ?>
                      </ul>
                    </li>
                    <li>
                      <a tabindex="-1" href="students.php?schoolid=<?php echo CSNTRID; ?>">Students</a>
                    </li>
                    <li>
                      <a tabindex="-1" href="school.php?id=<?php echo CSNTRID; ?>">CSNT-R Profile</a>
                    </li>
                  </ul>
                </li>
              </ul>
              <?php 

                if($session->is_logged_in())
                {
                  //===================================== SCHOOLS IM IN =========================================//

                  $schoolsimin = SchoolUser::getSchoolsImIn($session->user_id);

                  $schoolIdsImIn = array();

                  if(count($schoolIdsImIn) > 0)
                  {
                    foreach ($schoolsimin as $schooluser) 
                    {
                      array_push($schoolIdsImIn, $schooluser->schoolid);
                    }
                  }
                  
                  $schoolUsers = SchoolUser::getUsersInMultipleSchools($schoolIdsImIn);

                  if($user->is_super_admin())
                  { 
                    echo '
                    <ul class="nav">
                      <li id="myschoolsdropdown" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                          <i class="icon-large icon-book"></i> Schools ('.count($schoolsimin).')
                          <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                    ';

                    if(count($schoolsimin) > 0)
                    {
                      foreach ($schoolsimin as $schoolimin) 
                      {
                        $school = School::get_by_id($schoolimin->schoolid);

                        echo '
                        <li class="dropdown-submenu">
                          <a tabindex="-1" href="school.php?id='.$school->id.'">'.School::get_by_id($school->id)->name.'</a>
                          <ul class="dropdown-menu">
                            <li><a href="school.php?id='.$school->id.'"><i class="icon-large icon-play"></i> View</a></li>
                        ';

                        if(SchoolUser::amIAdmin($session->user_id, $schoolimin->schoolid) || $user->is_super_admin())
                        {
                          echo '
                            <li><a href="updateschool.php?id='.$school->id.'"><i class="icon-large icon-pencil"></i> Edit</a></li>
                            </ul>
                          </li>
                          ';
                        }
                        else
                        {
                          echo '
                            </ul>
                          </li>
                          ';
                        }
                      }
                    }
                    else
                    {
                      echo '<li class="disabled"><a href="#">No Schools Yet</a></li>';
                    }

                    echo '
                        </ul>
                      </li>
                    </ul>
                    ';
                  }

                //   //===================================== BATCHS IM IN =========================================//

                  //$batchsimin = BatchUser::getBatchsImIn($session->user_id);

                  $batchsinschool = Batch::getBatchsInMultipleSchools($schoolIdsImIn);

                  echo '
                  <ul class="nav">
                    <li id="mybatchsdropdown" class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-large icon-book"></i> Batchs & Yearbooks ('.count($batchsinschool).')
                        <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu">
                  ';

                  if(count($batchsinschool) > 0)
                  {
                    foreach ($batchsinschool as $batch) 
                    {
                      if($batch)
                      {
                        echo '
                        <li class="dropdown-submenu">
                          <a tabindex="-1" href="batch.php?id='.$batch->id.'"> Batch: '.$batch->fromyear.'-'.($batch->fromyear + 1).'</a>
                          <ul class="dropdown-menu">
                            <li><a href="batch.php?id='.$batch->id.'"><i class="icon-large icon-play"></i> View Batch</a></li>
                            <li><a href="yearbook.php?id='.$batch->id.'"><i class="icon-large icon-play"></i> View Yearbook</a></li>
                        ';

                        if(
                          BatchUser::amIAdmin($session->user_id, $batch->id) || 
                          SchoolUser::amIAdmin($session->user_id, $batch->schoolid) || 
                          $user->is_super_admin()
                          )
                        {
                          echo '
                            <li class="divider"></li>
                            <li><a href="updatebatch.php?id='.$batch->id.'"><i class="icon-large icon-pencil"></i> Edit Batch</a></li>
                            <li><a href="editor.php?id='.$batch->id.'"><i class="icon-large icon-pencil"></i> Edit Yearbook</a></li>
                            <li class="divider"></li>';
                        }

                            $sectionsInBatch = Section::get_all_by_batchid($batch->id);

                            if(count($sectionsInBatch) > 0)
                            {
                              foreach ($sectionsInBatch as $section)
                              {
                                echo 
                                '
                                <li class="dropdown-submenu">
                                  <a tabindex="-1" href="section.php?id='.$section->id.'"> '.$section->name.' </a>
                                  <ul class="dropdown-menu">
                                    <li><a href="section.php?id='.$section->id.'"><i class="icon-large icon-play"></i> View</a></li>';

                                if(
                                  SectionUser::amIAdmin($session->user_id, $section->id) || 
                                  BatchUser::amIAdmin($session->user_id, $section->batchid) || 
                                  SchoolUser::amIAdmin($session->user_id, $section->schoolid) || 
                                  $user->is_super_admin()
                                  )
                                {
                                  echo'<li><a href="updatesection.php?id='.$section->id.'"><i class="icon-large icon-pencil"></i> Edit</a></li>';
                                }

                                echo '
                                  </ul>
                                </li>
                                ';
                              }
                            }

                          echo  '
                            </ul>
                          </li>
                          ';
                      }
                    }
                  }
                  else
                  {
                    echo '<li class="disabled"><a>No Batchs Yet</a></li>';
                  }

                  echo '
                      </ul>
                    </li>
                  </ul>
                  ';

                echo '
                  <ul class="nav">
                    <li id="createdropdown" class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-large icon-file"></i> Create
                        <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu">';

                      if($iAdminSomething)
                      {
                        if($user->is_super_admin())
                        {
                          echo '<li><a href="createschool.php">School</a></li>';
                        
                        }
                        if($schoolsIAdminCount > 0 || $user->is_super_admin())
                        {
                          echo '<li><a href="createbatch.php">Batch</a></li>';
                        }

                        if($batchsIAdminCount > 0 || $schoolsIAdminCount > 0 || $user->is_super_admin())
                        {
                          echo '<li><a href="createsection.php">Section</a></li>';
                        }

                        echo '<li><a href="createuser.php">Student</a></li>';
                      }
  
                  echo 
                      '<li><a href="createclub.php">Club</a></li>
                       <li><a href="creategroup.php">Group</a></li>
                      </ul>
                    </li>
                  </ul>
                  ';
                }
                else
                {
                  echo '<li id="registration"><a id="regpopover" class="mytooltip" data-toggle="tooltip" data-placement="bottom" 
                  title="Register now!" href="registration.php">Register</a></li>';
                }

              ?>
              
            </ul>

            <?php 

              if(!$session->is_logged_in())
              { 
                echo '<form id="theform" class="navbar-form pull-right" action="#" method="post">
                        <input class="span2" name="username" id="username" type="text" placeholder="username">
                        <input class="span2" name="password" id="password" type="password" placeholder="password">
                        <button id="btnlogin" class="btn">Login</button>
                      </form>'; 
              }
              else
              {
                echo '<ul class="nav pull-right">  
                        <li class="dropdown">  
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">  
                            <img style="height:20px;" src="data:image/jpeg;base64, '.$user->picture.'" /> '. $user->get_full_name() .'
                            <b class="caret"></b>  
                          </a>  
                          <ul class="dropdown-menu"> ';  

                if($iAdminSomething)
                {
                  echo '<li id="cpanel"><a href="cpanel.php"><i class="icon-large icon-lock"></i> Control Panel</a></li>';
                }

                //<li><a href="messages.php"><i class="icon-large icon-envelope"></i> Messages <span class="badge badge-important">6</span> </a></li>

                echo '      
                            <li><a href="student.php?id='.$session->user_id.'"><i class="icon-large icon-user"></i> Profile</a></li>
                            
                            <li><a href="settings.php"><i class="icon-large icon-cog"></i> Settings</a></li>  
                            <li><a href="public/functions/logout.php"><i class="icon-large icon-off"></i> Logout</a></li>  
                          </ul>  
                        </li>  
                      </ul> 
                    ';
              }

            ?>

            <form class="navbar-search">
              <input id="search" type="text" class="search-query span2" placeholder="Search" data-provide="typeahead" autocomplete="off">
            </form>

            <?php 

            if($session->is_logged_in()) 
            { 

              $notifications = Notification::get($session->user_id);
              $unreadnotifications = Notification::get_unread($session->user_id);

            ?>

            <ul class="nav pull-right">
              <li>
                <a id="btnnotifications" href="#notificationBox" data-toggle="modal">
                  <i class="icon-large icon-bell"></i>
                  <span id="unreadnotificationcount">
                  </span>
                </a>
              </li>
            </ul>

            <?php } ?>

          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div id="notificationBox" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Notifications </h3>
      <button id='btnrefreshnotifications' class='btn btn-info btn-mini pull-right'><i class="icon-large icon-white icon-refresh"></i></button>
    </div>
    <div class="modal-body">

      <p><i id="loadingindicator" class="label pull-right hide">Loading...</i></p>
      
      <table class="table">
        <tbody id="notificationstable">
          
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

      $("#btnrefreshnotifications").click(function()
      {
        loadNotifications();
      });

      $('#notificationBox').on('shown', function() 
      {
        loadNotifications();
      });

      function loadUnreadCountNotifications()
      {
        $.ajax(
        {
          type: 'GET',
          url: 'includes/webservices/getunreadcountnotifications.php?touserid='+$("#userid").val(),
          success: function(result)
          {
            $('#unreadnotificationcount').html(result);
          }
        });
      }

      loadUnreadCountNotifications();

      function loadNotifications()
      {
        $("#loadingindicator").removeClass("hide");

        $.ajax(
        {
          type: 'GET',
          url: 'includes/webservices/getnotifications.php?touserid='+$("#userid").val(),
          success: function(result)
          {
            $('#notificationstable').html(result);
            $("#loadingindicator").addClass("hide");
            loadUnreadCountNotifications();
          }
        });
      }

      $(document).on("click", ".btnaccept", function()
      {
        var itemid = $(this).find(".itemid").text();
        var itemtype = $(this).find(".itemtype").text();
        var fromuserid = $(this).find(".fromuserid").text();
        var notificationid = $(this).find(".notificationid").text();

        accept(itemid, itemtype, fromuserid, notificationid, $(this));

        $(this).text("Processing");
        $(this).attr("disabled", "disabled");

      });

      function accept(itemid, itemtype, touserid, notificationid, element)
      {
        $.ajax(
        {
          type: 'GET',
          url: 'includes/webservices/accept.php?itemid='+itemid+'&itemtype='+itemtype+'&touserid='+touserid+'&notificationid='+notificationid,
          success: function(result)
          {
            if(result == "success")
            {
              showToast("Accepted Request", "success");
              loadNotifications();
              loadUnreadCountNotifications();
            }
            else
            {
              bootbox.alert("ERROR");
            }
          }
        });
      }

      $(document).on("click", ".btndecline", function()
      {
        var itemid = $(this).find(".itemid").text();
        var itemtype = $(this).find(".itemtype").text();
        var fromuserid = $(this).find(".fromuserid").text();
        var notificationid = $(this).find(".notificationid").text();

        decline(itemid, itemtype, fromuserid, notificationid, $(this));

        $(this).text("Processing");
        $(this).attr("disabled", "disabled");

      });

      function decline(itemid, itemtype, touserid, notificationid, element)
      {
        $.ajax(
        {
          type: 'GET',
          url: 'includes/webservices/decline.php?itemid='+itemid+'&itemtype='+itemtype+'&touserid='+touserid+'&notificationid='+notificationid,
          success: function(result)
          {
            if(result == "success")
            {
              showToast("Declined Request", "success");
              loadNotifications();
              loadUnreadCountNotifications();
            }
            else
            {
              bootbox.alert("ERROR");
            }
          }
        });
      }

      $(document).on("click", ".btndelete", function()
      {
        var notificationid = $(this).find(".notificationid").text();

        deletenotification(notificationid, $(this));

        $(this).text("Processing");
        $(this).attr("disabled", "disabled");

      });

      function deletenotification(notificationid, element)
      {
        $.ajax(
        {
          type: 'GET',
          url: 'includes/webservices/delete.php?notificationid='+notificationid,
          success: function(result)
          {
            if(result == "success")
            {
              showToast("Deleted", "success");
              loadNotifications();
              loadUnreadCountNotifications();
            }
            else
            {
              bootbox.alert("ERROR");
            }
          }
        });
      }

      $("#btnlogin").click(function()
      {
        if($("#username").val() != "" && $("#password").val() != "")
        {
          var formData = new FormData($("#theform")[0]);

          $(this).text("Processing");
          $(this).attr("disabled", "disabled");

          console.log("clicked");

          $.ajax(
          {
            type: 'POST',
            url: 'includes/webservices/login.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) 
            {
              if(result == "success")
              {
                showToast("Success! Redirecting...", "success");
                window.location.href = "school.php?id=26";
              }
              else
              {
                $("#btnlogin").text("Login");
                $("#btnlogin").removeAttr("disabled");
                $('#theform')[0].reset();
                bootbox.alert(result);
              }
            }
          });
        }
        else
        {
          bootbox.alert("Please enter a username and a password");
        }

        return false;
      });

    });

    </script>