<?php 

require_once("includes/initialize.php");

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

if(isset($_GET['batchid']))
{
  $batch    = Batch::get_by_id($_GET['batchid']);
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

      foreach ($sections as $section) 
      {
        echo '<div class="span12"></div>';
        echo '  <div class="span12 thebox">';
        echo '      <p class="ybname2">'.$section->name.'</p>';
        echo '      <img class="img-polaroid" src="data:image/jpeg;base64, '.$section->picture.'"/>';
        //echo '      <img class="img-polaroid" src="http://flyingmeat.s3.amazonaws.com/acorn4/images/Acorn256.png"/>';
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
          if($sectionuser->pending == 0 && $sectionuser->enabled == 1)
          {
            $user = User::get_by_id($sectionuser->userid);

            $achievements     = Achievement::get($user->id, "user", $batch->id);
            $clubusers        = ClubUser::getClubsImIn($user->id);
            $groupusers       = GroupUser::getGroupsImIn($user->id);
            
            if($user->pending == 0 && $user->enabled == 1)
            {
              echo '<div class="span12"></div>';
              echo '  <div class="span12 mygridbox">';
              echo '    <div class="span4">';
              echo '      <img class="img-polaroid img-circle yearbookimage ybimage" src="data:image/jpeg;base64, '.$user->picture.'"/>';
              echo '    </div>';
              echo '    <div class="span8">';
              echo '      <p class="ybname">'.$user->get_full_name().'</p>';
              echo '      <p class="ybmotto">';
              echo '        '.$user->moto.'';
              echo '      </p>';
              echo '      <p><a href="student.php?id='.$user->id.'">view profile</a></p> ';
              echo '      ';

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