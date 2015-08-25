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

?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span1"></div>
    <div class="span9">
      <form id="theform" class="form-horizontal" method="post" action="#" enctype="multipart/form-data">
        <fieldset>
        <legend>
          Create School
          &nbsp;
        </legend>

        <div class="control-group">
          <a class="tooltip" href="#" data-toggle="tooltip" title="first tooltip">hover over me</a>
          <label class="control-label" for="moto">School Logo <span class="label label-info">200x200</span></label>
          <div class="controls">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-new thumbnail" style="width: 200px; height: 200px;"><img src="public/img/profile.png" /></div>
              <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
              <div>
                <span class="btn btn-file">
                  <span class="fileupload-new">Select image</span>
                  <span class="fileupload-exists">Change</span>
                  <input name="MAX_FILE_SIZE" hidden value="2097152" />
                  <input name="logo" type="file" />
                </span>
                <a class="mytooltip" data-toggle="tooltip" data-placement="right" 
                  title=
                  "
                    OPTIONAL: extensions allowed: JPEG/JPG and PNG
                    , Up to 2MB, Recommended size: 200x200
                  ">
                  <span class="label label">?</span>
                </a>
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
              </div>
            </div>
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
          <label class="control-label" for="name">Name</label>
          <div class="controls">
            <input id="name" name="name" type="text" placeholder="name" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="address">Address</label>
          <div class="controls">
            <input id="address" name="address" type="text" placeholder="address" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="about">About</label>
          <div class="controls">                     
            <textarea id="about" name="about" class="span8" style="width:900px; height:200px"></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="history">History</label>
          <div class="controls">                     
            <textarea id="history" name="history" class="span8" style="width:900px; height:200px"></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="visionmission">Vision & Mission</label>
          <div class="controls">                     
            <textarea id="visionmission" name="visionmission" class="span8" style="width:900px; height:200px"></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="corevalues">Core Values</label>
          <div class="controls">                     
            <textarea id="corevalues" name="corevalues" class="span8" style="width:900px; height:200px"></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="file">Supporting Documents</label>
          <div class="controls">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="input-append">
                <div class="uneditable-input span2"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
              </div>
              <a class="mytooltip" data-toggle="tooltip" data-placement="right" 
                  title=
                  "
                    REQUIRED: In ZIP Format: Mission & Vision, Principal's Name Contact Info, Your Scanned ID
                  ">
                  <span class="label label-warning">?</span>
                </a>
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
            <button id="btncreate" name="btncreate" class="btn btn-primary">Create</button>
          </div>
        </div>

        <input type="hidden" name="what" value="school" />

        </fieldset>
        </form>
    </div>
    <div class="span1"></div>
  </div><!--/row-->

<script>

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