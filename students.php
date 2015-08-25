<?php 

require_once("header.php"); 

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

$sound = (isset($_GET['positive']) ? "positive" : "");
$sound = (isset($_GET['negative']) ? "negative" : $sound);

$batchsInSchool = BatchUser::getBatchsInSchool(CSNTRID);

$usersinbatch = array();
$batchsIds = array();

if(count($batchsInSchool) > 0)
{
  foreach ($batchsInSchool as $batchuser) 
  {
    array_push($batchsIds, $batchuser->batchid);
  }
}

if(isset($_GET['sectionid']))
{
  $theusers = SectionUser::getUsersInSection($_GET['sectionid']);
}
else if(isset($_GET['batchid']))
{
  $theusers = BatchUser::getUsersInBatch($_GET['batchid']);
}
else if(isset($_GET['schoolid']))
{
  $theusers = SchoolUser::getUsersInSchool($_GET['schoolid']);
}
else
{
  $theusers = SchoolUser::getUsersInSchool(CSNTRID);
}

?>

<div class="container-fixed" >
  <div class="span12 offset1">
    <div class="btn-group" style="margin-bottom: 20px">
      <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <?php if(count($batchsInSchool) == 0){ echo "Nothing Yet"; }else{ echo "View By"; } ?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <?php

            if(count($batchsInSchool) > 0)
            {
              echo '<li><a href="students.php?schoolid='.CSNTRID.'">Whole School</a></li>';

              foreach ($batchsInSchool as $batchuser)
              {
                $batch = Batch::get_by_id($batchuser->batchid);
                $school = School::get_by_id($batch->schoolid);

                echo 
                '
                <li class="dropdown-submenu">
                  <a tabindex="-1" href="students.php?batchid='.$batch->id.'">'.$batch->get_batchyear().'</a>
                  <ul class="dropdown-menu">
                ';

                $sectionsinbatch = Section::get_all_by_batchid($batch->id);

                if(count($sectionsinbatch) > 0)
                {
                  foreach ($sectionsinbatch as $section)
                  {
                    echo '<li><a href="students.php?sectionid='.$section->id.'">'.$section->name.'</a></li>';
                  }
                }
                else
                {
                  echo '<li>no sections yet</li>';
                }

                echo 
                '
                  </ul>
                </li>
                ';
              }
            }

          ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="row-fixed" >
    <div class="span12 offset1">
      <?php

      foreach ($theusers as $batchuser) 
      {
        if($batchuser->pending == 0 && $batchuser->enabled == 1)
        {
          $user = User::get_by_id($batchuser->userid);
          
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

      ?>
    </div><!--/span-->
  </div><!--/row-->
  
<?php require_once("footer.php"); ?>