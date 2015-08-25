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

$batchusers = BatchUser::getBatchsIAdminInSchool($session->user_id, CSNTRID);

$schoolusersX = SchoolUser::getAdminSchools($session->user_id);
$batchusersX   = BatchUser::getAdminBatchs($session->user_id);

if(count($schoolusersX) == 0 && count($batchusersX) == 0)
{
  header("location: index.php?negative");
}

?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span1"></div>
    <div class="span9">
      <form id="theform" class="form-horizontal" method="post" action="#" enctype="multipart/form-data">
        <fieldset>
        <legend>
          Create a Section
        </legend>

        <!-- <div class="control-group">
          <label class="control-label" for="name">School</label>
          <div class="controls">
            <select name="schoolselect" id="schoolselect">
              <?php

              if(count($schoolusers) > 0)
              {
                foreach ($schoolusers as $schooluser) 
                {
                  echo "<option value='".$schooluser->schoolid."'>".School::get_by_id($schooluser->schoolid)->name."</option>";
                }
              }
              else
              {
                echo "<option value='0'>NO SCHOOLS YET</option>";
              }

              ?>
            </select>
          </div>
        </div> -->

        <div class="control-group">
          <label class="control-label" for="name">Batch</label>
          <div class="controls">
            <select name="batchselect" id="batchselect">
              <?php

              // if(count($schoolusers) > 0)
              // {
                if(count($batchusers) > 0)
                {
                  foreach ($batchusers as $batchuser) 
                  {
                    $batch = Batch::get_by_id($batchuser->batchid);

                    echo "<option value='".$batchuser->batchid."'>".$batch->fromyear."-".($batch->fromyear + 1)."</option>";
                  }
                }
                else
                {
                  echo "<option value='0'>NO BATCHS YET</option>";
                }
              //}

              ?>
            </select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="name">Section Name</label>
          <div class="controls">
            <input id="name" name="name" type="text" placeholder="section name" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="about">About</label>
          <div class="controls">                     
            <textarea id="about" name="about" class="span8" style="width:900px; height:200px"></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="about">Adviser's Message</label>
          <div class="controls">                     
            <textarea id="advisermessage" name="advisermessage" class="span8" style="width:900px; height:200px"></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="moto">Cover Photo <span class="label label-info">800x300</span></label>
          <div class="controls">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-new thumbnail" style="width: 800px; height: 300px;"><img src="public/img/cover.png" /></div>
              <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 800px; max-height: 300px; line-height: 20px;"></div>
              <div>
                <span class="btn btn-file">
                  <span class="fileupload-new">Select image</span>
                  <span class="fileupload-exists">Change</span>
                  <input name="MAX_FILE_SIZE" hidden value="2097152" />
                  <input name="cover" type="file" />
                </span>
                <a class="mytooltip" data-toggle="tooltip" data-placement="right" 
                  title=
                  "
                    OPTIONAL: extensions allowed: JPEG/JPG and PNG
                    , Up to 2MB, Recommended size: 800x300
                  ">
                  <span class="label label">?</span>
                </a>
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
              </div>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="comments">SkooLyf Comments</label>
          <div class="controls">
            <input type="hidden" name="comments" value="1" id="btn-input4" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled4" class="btn active">Enabled</button>
              <button type="button" value="0" id="btn-disabled4" class="btn">Disabled</button>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="comments">Facebook Comments</label>
          <div class="controls">
            <input type="hidden" name="comments" value="1" id="btn-input3" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled3" class="btn active">Enabled</button>
              <button type="button" value="0" id="btn-disabled3" class="btn">Disabled</button>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="enabled">Access</label>
          <div class="controls">
            <input type="hidden" name="enabled" value="0" id="btn-input2" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled2" class="btn active">Enabled</button>
              <button type="button" value="0" id="btn-disabled2" class="btn">Disabled</button>
            </div>
          </div>
        </div>
        
        <div class="control-group">
          <label class="control-label" for="btncreate"></label>
          <div class="controls">
            <button <?php echo ((count($batchusers) == 0  && !$user->is_super_admin()) ? "disabled" : "") ?> id="btncreate" name="btncreate" class="btn btn-primary">Create</button>
          </div>
        </div>

        <input type="hidden" name="what" value="section" />

        </fieldset>
        </form>
    </div>
    <div class="span1"></div>
  </div><!--/row-->
<script>

  function loadBatchSelect()
  {
    var schoolidPARAM = parseInt($("#schoolselect").val());

    $.ajax(
    {
      type: 'GET',
      url: 'includes/webservices/get_batch_i_admin_in_school.php',
      data: {schoolid: schoolidPARAM},
      success: function(result) 
      {
        $("#batchselect").html(result);
      }
    });
  }

  $("#schoolselect").click(function()
  {
    loadBatchSelect();
  });

  $(function () 
  { 
    $("#btncreate").click(function()
    {
      var formData = new FormData($("#theform")[0]);

      $("#btncreate").text("Processing");
      $("#btncreate").attr("disabled", "disabled");

      $.ajax(
      {
        type: 'POST',
        url: 'includes/webservices/create.php',
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
            showToast("Successfully Created", "success");
            $("#btncreate").text("Create");
            $("#btncreate").removeAttr("disabled");
            $('#theform')[0].reset();
            loadtable();
          }
          else
          {
            bootbox.alert(result);
            $("#btncreate").text("Create");
            $("#btncreate").removeAttr("disabled");
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
        console.log("progress: " + e.loaded);
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

  });

</script>
    
<?php require_once("footer.php"); ?>