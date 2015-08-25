<?php 

require_once("header.php"); 

if(isset($_GET['id']))
{
  $object = Batch::get_by_id($_GET['id']);

  if($batch == false || $batch == null || $batch == "")
  {
    header("location: index.php");
  }
  else
  {
    $school = School::get_by_id($object->schoolid);
    //$batchname = $school->name." ".$object->get_batchyear();
    $batchname = $object->get_batchyear();
  }
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
  $user = User::get_by_id($session->user_id);

  if($user->enabled == DISABLED)
  {
    header("location: index.php?disabled");
  }

  if(
    !BatchUser::amIAdmin($session->user_id, $object->id) && 
    !SchoolUser::amIAdmin($session->user_id, $object->schoolid) && 
    !$user->is_super_admin()
    )
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
    <div class="span9">
      <form id="theform" class="form-horizontal" method="post" action="#" enctype="multipart/form-data">
        <fieldset>
        <legend>
          Update Batch: <?php echo $batchname; ?>
        </legend>

        <!-- <div class="control-group">
          <label class="control-label" for="name">School</label>
          <div class="controls">
            <select name="schoolselect" id="schoolselect">
              <?php

              // $schoolusers = SchoolUser::getAdminSchools($session->user_id);

              // if(count($schoolusers) > 0)
              // {
              //   foreach ($schoolusers as $schooluser) 
              //   {
              //     if($schooluser->schoolid == $object->schoolid)
              //     {
              //       echo "<option value='".$schooluser->schoolid."' selected>".School::get_by_id($schooluser->schoolid)->name."</option>";
              //     }
              //     else
              //     {
              //       echo "<option value='".$schooluser->schoolid."'>".School::get_by_id($schooluser->schoolid)->name."</option>";
              //     }
              //   }
              // }
              // else
              // {
              //   echo "<option value='0'>NO SCHOOLS YET</option>";
              // }

              ?>
            </select>
          </div>
        </div> -->

        <div class="control-group">
          <label class="control-label" for="fromyear">From Year</label>
          <div class="controls">
            <select id="fromyear" name="fromyear">
              <?php 

              for($i = 1900; $i <= date('Y') + 5; $i++)
              {
                if($i == $object->fromyear)
                {
                  echo "<option value='".$i."' selected>".$i."</option>";
                }
                else
                {
                  echo "<option value='".$i."'>".$i."</option>";
                }
              }

              ?>
            </select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="toyear">To Year</label>
          <div class="controls">
            <input id="toyear" name="toyear" type="text" value="1900" class="input-xlarge" disabled>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="about">About</label>
          <div class="controls">                     
            <textarea id="about" name="about" class="span8" style="width:900px; height:200px"><?php echo $object->about; ?></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="moto">Cover Photo</label>
          <div class="controls">
            <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 800px; height: 300px;">
              <img src='data:image/jpeg;base64, <?php echo $object->picture; ?>' />
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

        <?php if($object->published == 0 && strtotime(date("Y-m-d")) < strtotime($object->pubdate)){ ?>

        <div class="control-group">
          <label class="control-label" for="pubdate">Yearbook's Publishing Date</label>
          <div class="controls">
            <div class="input-append date" id="dp3" data-date="<?php echo $object->pubdate; ?>" data-date-format="yyyy-mm-dd">
              <input name="pubdate" value="<?php echo $object->pubdate; ?>" class="span7" size="56" type="text">
              <span class="add-on"><i class="icon-th"></i></span>
            </div>
          </div>
        </div>

        <?php } ?>

        <div class="control-group">
          <label class="control-label" for="comments">SkooLyf Comments</label>
          <div class="controls">
            <input type="hidden" name="comments" value="<?php echo $object->comments; ?>" id="btn-input4" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled4" class="btn <?php if($object->comments==1){echo'active';} ?>">Enabled</button>
              <button type="button" value="0" id="btn-disabled4" class="btn <?php if($object->comments==0){echo'active';} ?>">Disabled</button>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="fbcomments">Facebook Comments</label>
          <div class="controls">
            <input type="hidden" name="fbcomments" value="<?php if($object->fbcomments==1){echo'1';}else{echo '0';} ?>" id="btn-input3" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled3" class="btn <?php if($object->fbcomments==1){echo'active';} ?>">Enabled</button>
              <button type="button" value="0" id="btn-disabled3" class="btn <?php if($object->fbcomments==0){echo'active';} ?>">Disabled</button>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="enabled2">Access</label>
          <div class="controls">
            <input type="hidden" name="enabled" value="<?php if($object->enabled==1){echo'1';}else{echo '0';} ?>" id="btn-input2" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled2" class="btn <?php if($object->enabled==1){echo'active';} ?>">Enabled</button>
              <button type="button" value="0" id="btn-disabled2" class="btn <?php if($object->enabled==0){echo'active';} ?>">Disabled</button>
            </div>
          </div>
        </div>

        <input type="hidden" name="batchid" value="<?php echo $object->id; ?>" />

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
    <div class="span1"></div>
  </div><!--/row-->
  <script>

  $('.date').datepicker();

    function updateDate()
    {
      var fromyear = parseInt($('#fromyear').val());
      var toyear = fromyear + 1;
      $('#toyear').val(toyear);
    }

    updateDate();

    $('#fromyear').change(function() 
    {
      updateDate();
    });

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
          url: 'includes/webservices/updatebatch.php',
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

      for(var i = 0; i < btns2.length; i++) 
      {
        document.getElementById(btns2[i]).addEventListener('click', function() 
        {
          input2.value = this.value;
        });
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