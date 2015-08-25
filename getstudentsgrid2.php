<?php 

require_once("includes/initialize.php");

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

$path = $_GET['path'];

if(isset($_GET['batchid']))
{
  $batch    = Batch::get_by_id($_GET['batchid']);
  $batch->published = 1;
  $batch->picture  = base64_decode($batch->picture);
  $batch->update();
  $sections = Section::get_all_by_batchid($batch->id);
}

?>

<style>

    .ybimage
    {
      margin-top: 16px;
      margin-left: 16px;
    }

    .ybname
    {
      font-size:40px; 
      width:95%; 
      padding:20px; 
      padding-top: 90px;
     /* padding-top: 60px;*/
      padding-left:0px; 
      margin:0px;
      font-family: 'Roboto Thin', tahoma;
      line-height: 40px;
    }

    .ybname2
    {
      font-size:40px; 
      width:95%; 
      padding:20px; 
     /* padding-top: 60px;*/
      padding-left:0px; 
      margin:0px;
      font-family: 'Roboto Thin', tahoma;
      line-height: 40px;
    }

    .ybmotto
    {
      font-size:18px; 
      padding-bottom:10px; 
      width:95%; 
      margin:0px;
      font-family: 'Roboto Light', tahoma;
    }

    .thebox
    {
       background-color:white;
       padding: 20px;
       margin: 2px;
       border: 1px solid #DEDEDE;
       color: gray;
       border-radius: 5px;
    }

</style>

<div class="container-fixed" >
  <div class="row-fixed" >
    <div class="span12">
      <?php

      $counter = 0;

      foreach ($sections as $section) 
      {
        $counter++;
        $filename = $counter;

        echo '<div class="span12"></div>';
        echo '  <div class="span12 thebox">';
        echo '      <p class="ybname2">'.$section->name.'</p>';

        file_put_contents($path."images/".$filename.".jpg", base64_decode($section->picture));

        echo '  <img class="img-polaroid" src="http://skoolyf.kellyescape.com/'.$path.'images/'.$filename.'.jpg" />';
        echo '  </div>';
        echo '  <div class="span12 thebox">';
        echo '      <p class="ybname2">Adviser Message</p>';
        echo '      <p class="advisermessage">';
        echo '        '.$section->advisermessage.'';
        echo '      </p>';
        echo '  </div>';

        $sectionusers = SectionUser::getUsersInSection($section->id);

        foreach ($sectionusers as $sectionuser) 
        {
          $counter++;
          $filename = $counter;

          if($sectionuser->pending == 0 && $sectionuser->enabled == 1)
          {
            $user = User::get_by_id($sectionuser->userid);

            $achievements     = Achievement::get($user->id, "user", $batch->id);
            $clubusers        = ClubUser::getClubsImIn($user->id);
            $groupusers       = GroupUser::getGroupsImIn($user->id);
            $comments         = Comment::get_all_comments($user->id, "user");
            
            if($user->pending == 0 && $user->enabled == 1)
            {
              echo '<div class="span12"></div>';
              echo '  <div class="span12 mygridbox">';
              echo '    <div class="span4">';
              
              file_put_contents($path."images/".$filename."xx.jpg", base64_decode($user->picture));

              echo '  <img class="img-polaroid img-circle yearbookimage ybimage" src="http://skoolyf.kellyescape.com/'.$path.'images/'.$filename.'xx.jpg" />';

              //echo '      <img class="img-polaroid img-circle yearbookimage ybimage" src="data:image/jpeg;base64, '.$user->picture.'"/>';
              echo '    </div>';
              echo '    <div class="span8">';
              echo '      <p class="ybname">'.$user->get_full_name().'</p>';
              echo '      <p class="ybmotto">';
              echo '        '.$user->moto.'';
              echo '      </p>';
              echo '      <p class="ybmotto">';
              echo '        '.$user->address.'';
              echo '      </p>';
              echo '      <p class="ybmotto">';
              echo '        '.$user->email.'';
              echo '      </p>';
              echo '      <p class="ybmotto">';
              echo '        '.$user->number.'';
              echo '      </p>';
              //echo '      <p><a href="student.php?id='.$user->id.'">view profile</a></p> ';
              echo '      ';

              if(count($comments) > 0)
              {
                //echo '        <p>';
                echo '          Comments: ';
                //echo '          <ul>';

                foreach ($comments as $comment) 
                {
                  echo '            '.$comment->comment.', ';
                }

                echo '          <br />';
                //echo '        </div>';
              }

              if(count($achievements) > 0)
              {
                //echo '        <p>';
                echo '          Achievements: ';
                //echo '          <ul>';

                foreach ($achievements as $achievement) 
                {
                  echo '            '.$achievement->name.', ';
                }

                echo '          <br />';
                //echo '        </div>';
              }

              if(count($clubusers) > 0)
              {
                //echo '        <p>';
                echo '          Clubs: ';
                //echo '          <ul>';

                foreach ($clubusers as $clubuser) 
                {
                  $club = Club::get_by_id($clubuser->clubid);

                  echo '            <a href="club.php?id='.$club->id.'">'.$club->name.'</a>, ';
                }

                echo '          <br />';
                //echo '        </div>';
              }

              if(count($groupusers) > 0)
              {
                //echo '        <br />';
                echo '          Groups: ';
                //echo '          <ul>';

                foreach ($groupusers as $groupuser) 
                {
                  $group = Group::get_by_id($groupuser->groupid);
                  
                  echo '            <a href="group.php?id='.$group->id.'">'.$group->name.'</a>, ';
                }

                //echo '          </p>';
                //echo '        </div>';
              }

              echo '      ';
              echo '    </div>';
              echo '  </div>';
            }
          } 
        }
      }

      ?>
    </div><!--/span-->
  </div><!--/row-->