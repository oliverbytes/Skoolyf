<?php 

require_once("header.php"); 

$currentFile = str_replace(".php","", pathinfo($_SERVER['PHP_SELF'])['basename']);

$batchUsers = BatchUser::getUsersInBatch($_GET['batchid']);

?>

<div class="container-fixed" >
  <div class="row-fixed" >
    <div class="span12 offset1">
      <?php

      foreach ($batchUsers as $batchuser) 
      {
        $user = User::get_by_id($batchuser->userid);

        echo 
        '
          <div class="span2 mygridbox">
            <img class="mygridimage" src="'.$user->picture().'" />
            <p class="mygridname span2">'.$user->get_full_name().'</p>
            <p class="mygridmotto">"'.$user->moto.'"</p>
            <p><a class="btn pull-bottom" href="profile.php?id='.$user->id.'">View Profile &raquo;</a></p>
          </div>
        ';
      }

      ?>
    </div><!--/span-->
  </div><!--/row-->
  
<?php require_once("footer.php"); ?>