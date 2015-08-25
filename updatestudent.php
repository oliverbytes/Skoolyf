<?php 

require_once("header.php"); 

if(isset($_GET['id']))
{
  $object = User::get_by_id($_GET['id']);
}
else
{
  header("location: index.php?negative");
}

if(!$session->is_logged_in())
{
  header("location: index.php?negative");
}
else
{
  $loggeduser = User::get_by_id($session->user_id);

  if($loggeduser->enabled == DISABLED)
  {
    header("location: index.php?disabled");
  }

  if($object->id != $session->user_id && !$loggeduser->is_super_admin())
  {
    header("location: index.php?negative");
  }
}

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

?>

<div class="container-fluid">
<div class="row-fluid">
  <div class="span1"></div>
  <div class="span5">
    <form id="theform" class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
      <fieldset>
      <legend>
        Student Profile
      </legend>

      <div class="control-group">
        <label class="control-label" for="moto">Profile Photo <span class="label label-info">200x200</span></label>
        <div class="controls">
          <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 200px; height: 200px;">
              <img src='data:image/jpeg;base64, <?php echo $object->picture; ?>' />
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
            <div>
              <span class="btn btn-file">
                <span class="fileupload-new">Select image</span>
                <span class="fileupload-exists">Change</span>
                <input name="MAX_FILE_SIZE" hidden value="2097152" />
                <input name="picture" type="file" />
              </span>
              <button class="btn fileupload-exists" data-dismiss="fileupload">Remove</button>
              <a class="mytooltip" data-toggle="tooltip" data-placement="right" 
                  title=
                  "
                    OPTIONAL: extensions allowed: JPEG/JPG and PNG
                    , Up to 2MB, Recommended size: 200x200
                  ">
                  <span class="label label">?</span>
                </a>
            </div>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="moto">Profile Cover Photo <span class="label label-info">800x300</span></label>
        <div class="controls">
          <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 800px; height: 300px;">
              <img src='data:image/jpeg;base64, <?php echo $object->cover; ?>' />
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 800px; max-height: 300px; line-height: 20px;"></div>
            <div>
              <span class="btn btn-file">
                <span class="fileupload-new">Select image</span>
                <span class="fileupload-exists">Change</span>
                <input name="MAX_FILE_SIZE" hidden value="2097152" />
                <input name="cover" type="file" />
              </span>
              <button class="btn fileupload-exists" data-dismiss="fileupload">Remove</button>
              <a class="mytooltip" data-toggle="tooltip" data-placement="right" 
                  title=
                  "
                    OPTIONAL: extensions allowed: JPEG/JPG and PNG
                    , Up to 2MB, Recommended size: 800x300
                  ">
                  <span class="label label">?</span>
                </a>
            </div>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="firstname">First Name</label>
        <div class="controls">
          <input value="<?php echo $object->firstname; ?>" id="firstname" name="firstname" type="text" placeholder="First Name" class="input-xlarge">
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="middlename">Middle Name</label>
        <div class="controls">
          <input value="<?php echo $object->middlename; ?>"  id="middlename" name="middlename" type="text" placeholder="Middle Name" class="input-xlarge">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="lastname">Last Name</label>
        <div class="controls">
          <input value="<?php echo $object->lastname; ?>" id="lastname" name="lastname" type="text" placeholder="Last Name" class="input-xlarge">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="comments">Gender</label>
        <div class="controls">
          <input type="hidden" name="gender" value="<?php echo $object->gender; ?>" id="btn-input5" />
          <div class="btn-group" data-toggle="buttons-radio">
            <button type="button" value="1" id="btn-enabled5" class="btn <?php if($object->gender==1){echo'active';} ?>">Female</button>
            <button type="button" value="0" id="btn-disabled5" class="btn <?php if($object->gender==0){echo'active';} ?>">Male</button>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="address">Address</label>
        <div class="controls">
          <input value="<?php echo $object->address; ?>" id="address" name="address" type="text" placeholder="address" class="input-xlarge">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="moto">Motto</label>
        <div class="controls">
          <!-- <input value="<?php echo $object->moto; ?>" id="moto" name="moto" type="text" placeholder="moto" class="input-xlarge"> -->
          <textarea placeholder="moto" id="moto" name="moto" class="span8" style="width:285px; height:100px"><?php echo $object->moto; ?></textarea>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="moto">Birth Date</label>
        <div class="controls">
          <div class="input-append date" id="dp3" data-date="<?php echo $object->birthdate; ?>" data-date-format="yyyy-mm-dd">
            <input name="birthdate" value="<?php echo $object->birthdate; ?>" class="span7" size="56" type="text">
            <span class="add-on"><i class="icon-th"></i></span>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="number">Contact Number</label>
        <div class="controls">
          <input value="<?php echo $object->number; ?>" id="number" name="number" type="text" placeholder="contact number" class="input-xlarge">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="username">Username</label>
        <div class="controls">
          <input value="<?php echo $object->username; ?>" id="username" name="username" type="text" placeholder="username" class="input-xlarge">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="password">Password</label>
        <div class="controls">
          <input value="<?php echo $object->password; ?>" id="password" name="password" type="password" placeholder="password" value="" class="input-xlarge span3">
          <button class="btn btn-small" onclick="generate(); return false;">Generate</button>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="email">Email</label>
        <div class="controls">
          <input value="<?php echo $object->email; ?>" id="email" name="email" type="email" placeholder="email" class="input-xlarge">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="comments">SkooLyf Comments</label>
        <div class="controls">
          <input type="hidden" name="comments" value="<?php echo $object->comments; ?>" id="btn-input3" />
          <div class="btn-group" data-toggle="buttons-radio">
            <button type="button" value="1" id="btn-enabled3" class="btn <?php if($object->comments==1){echo'active';} ?>">Enabled</button>
            <button type="button" value="0" id="btn-disabled3" class="btn <?php if($object->comments==0){echo'active';} ?>">Disabled</button>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="fbcomments">Facebook Comments</label>
        <div class="controls">
          <input type="hidden" name="fbcomments" value="<?php echo $object->fbcomments; ?>" id="btn-input4" />
          <div class="btn-group" data-toggle="buttons-radio">
            <button type="button" value="1" id="btn-enabled4" class="btn <?php if($object->fbcomments==1){echo'active';} ?>">Enabled</button>
            <button type="button" value="0" id="btn-disabled4" class="btn <?php if($object->fbcomments==0){echo'active';} ?>">Disabled</button>
          </div>
        </div>
      </div>

      <input type="hidden" name="enabled" value="<?php echo $object->enabled; ?>" id="btn-input2" />
      <?php if($loggeduser->is_super_admin() && $object->id != $loggeduser->id) { ?>
      <div class="control-group">
        <label class="control-label" for="enabled2">Access</label>
        <div class="controls">
          <div class="btn-group" data-toggle="buttons-radio">
            <button type="button" value="1" id="btn-enabled2" class="btn <?php if($object->enabled==1){echo'active';} ?>">Enabled</button>
            <button type="button" value="0" id="btn-disabled2" class="btn <?php if($object->enabled==0){echo'active';} ?>">Disabled</button>
          </div>
        </div>
      </div>
      <?php } ?>

      <!-- Button -->
     <input type="hidden" name="userid" value="<?php echo $object->id; ?>" />

      <!-- Button -->
      <div class="control-group">
        <label class="control-label" for="btnsave"></label>
        <div class="controls">
          <button id="btnsave" name="btnsave" class="btn btn-primary">Save</button>
        </div>
      </div>

      </fieldset>
      </form>
  </div>
</div><!--/row-->
<script>

$('.date').datepicker();

function generate()
{
  var keylist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  var password = "";

  for (var i = 0; i < 7; i++)
  {
    password += keylist.charAt(Math.floor(Math.random() * keylist.length));
  }

  bootbox.alert("<i>Copy the Generated Password:</i> <br /><br /> <h1>&nbsp;&nbsp;" + password + "</h1>");
}


$(function () 
{
  $("#btnsave").click(function()
  {
    var formData = new FormData($("#theform")[0]);

    $("#btnsave").text("Saving...");
    $("#btnsave").attr("disabled", "disabled");

    $.ajax(
    {
      type: 'POST',
      url: 'includes/webservices/updateuser.php',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      xhr: function() 
      {
          var myXhr = $.ajaxSettings.xhr();

          if(myXhr.upload)
          {
              myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
          }
          return myXhr;
      },
      success: function(result) 
      {
        if(result == "success")
        {
          showToast("Successfully Saved", "success");
          $("#btnsave").text("Save");
          $("#btnsave").removeAttr("disabled");
        }
        else
        {
          bootbox.alert(result);
          $("#btnsave").text("Save");
          $("#btnsave").removeAttr("disabled");
        }
      }
    });

    return false;
  });

  $(':file').change(function()
  {
      var file = this.files[0];
      name = file.name;
      size = file.size;
      type = file.type;
  });

  function progressHandlingFunction(e)
  {
    if(e.lengthComputable)
    {
      // $('.progress').attr({value:e.loaded,max:e.total});
      console.log("max: "+e.total+", progress: " + e.loaded);
    }
  }

  var btns2 = ['btn-enabled2', 'btn-disabled2'];
  var input2 = document.getElementById('btn-input2');

  if(input2)
  {
    for(var i = 0; i < btns2.length; i++) 
    {
      document.getElementById(btns2[i]).addEventListener('click', function() 
      {
        input2.value = this.value;
      });
    }
  }

  var btns3 = ['btn-enabled3', 'btn-disabled3'];
  var input3 = document.getElementById('btn-input3');

  for(var i = 0; i < btns3.length; i++) 
  {
    document.getElementById(btns3[i]).addEventListener('click', function() 
    {
      input3.value = this.value;
    });
  }

  var btns4 = ['btn-enabled4', 'btn-disabled4'];
  var input4 = document.getElementById('btn-input4');

  for(var i = 0; i < btns4.length; i++) 
  {
    document.getElementById(btns4[i]).addEventListener('click', function() 
    {
      input4.value = this.value;
    });
  }

  var btns5 = ['btn-enabled5', 'btn-disabled5'];
  var input5 = document.getElementById('btn-input5');

  for(var i = 0; i < btns5.length; i++) 
  {
    document.getElementById(btns5[i]).addEventListener('click', function() 
    {
      input5.value = this.value;
    });
  }

});

</script>
      
<?php require_once("footer.php"); ?>