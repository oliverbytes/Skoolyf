<?php 

require_once("header.php"); 

if(!$session->is_logged_in())
{
  header("location: index.php?negative");
}
else
{
  $user = User::get_by_id($session->user_id);

  if($user->enabled == DISABLED)
  {
    header("location: index.php?disabled");
  }
}

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

if(isset($_POST['updatesubmit']))
{
  if( 
      $_POST["comments"]  != ""
    )
  {
    $user->comments   = $_POST["comments"];
    $user->update();

    $log = new Log($user->id, $clientip, "WEB", "UPDATED USER: ".$user->id); $log->create();
    header("location: settings.php");
  }
  else
  {
    $log = new Log($user->id, $clientip, "WEB", "UPDATE USER NOT FILLED"); $log->create();
    $message = "All fields are required.";
  } 
}

?>

<div class="container-fluid">
<div class="row-fluid">
  <div class="span1"></div>
  <div class="span5">
    <form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
      <fieldset>
      <legend>
        Settings
      </legend>

      <!-- Button -->
      <div class="control-group">
        <label class="control-label" for="comments">Facebook Comments</label>
        <div class="controls">
          <input type="hidden" name="comments" value="<?php if($user->comments==1){echo'1';}else{echo '0';} ?>" id="btn-input" />
          <div class="btn-group" data-toggle="buttons-radio">
            <button type="button" value="1" id="btn-enabled" class="btn <?php if($user->comments==1){echo'active';} ?>">Enabled</button>
            <button type="button" value="0" id="btn-disabled" class="btn <?php if($user->comments==0){echo'active';} ?>">Disabled</button>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="updatesubmit"></label>
        <div class="controls">
          <button id="updatesubmit" name="updatesubmit" class="button button-pill button-flat-primary">Save</button>
        </div>
      </div>

      </fieldset>
      </form>
  </div>
</div><!--/row-->
<script>

  var btns = ['btn-enabled', 'btn-disabled'];
  var input = document.getElementById('btn-input');

  for(var i = 0; i < btns.length; i++) 
  {
    document.getElementById(btns[i]).addEventListener('click', function() 
    {
      input.value = this.value;
    });
  }

</script>
      
<?php require_once("footer.php"); ?>