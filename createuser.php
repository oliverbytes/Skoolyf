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

$school = School::get_by_id(CSNTRID);

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

$schoolusers  = SchoolUser::getAdminSchools($session->user_id);
$batchusers   = BatchUser::getAdminBatchs($session->user_id);
$sectionusers = SectionUser::getAdminSections($session->user_id);

if(count($schoolusers) == 0 && count($batchusers) == 0 && count($sectionusers) == 0)
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
          Create Student
        </legend>

        <div class="control-group <?php if(DEFENSEMODE && !$user->is_super_admin()){echo 'hide';} ?>">
          <label class="control-label" for="name">School</label>
          <div class="controls">
            <select name="schoolselect" id="schoolselect">
              
            </select>
            <i id="schoolindicator" class="label hide">Loading...</i>
          </div>
        </div>

        <?php if(count($batchusers) > 0 || count($schoolusers) > 0 || $user->is_super_admin()) { ?>
        <div class="control-group">
          <label class="control-label" for="name">Batch</label>
          <div class="controls">
            <select name="batchselect" id="batchselect">

            </select>
            <i id="batchindicator" class="label hide">Loading...</i>
          </div>
        </div>
        <?php } ?>

        <?php if(count($sectionusers) > 0 || count($batchusers) > 0 || count($schoolusers) > 0 || $user->is_super_admin()) { ?>
        <div class="control-group">
          <label class="control-label" for="name">Section</label>
          <div class="controls">
            <select name="sectionselect" id="sectionselect">

            </select>
            <i id="sectionindicator" class="label hide">Loading...</i>
          </div>
        </div>
        <?php } ?>

        <div class="control-group">
          <label class="control-label" for="moto">Profile Photo</label>
          <div class="controls">
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-new thumbnail" style="width: 200px; height: 200px;"><img src="public/img/profile.png" /></div>
              <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
              <div>
                <span class="btn btn-file">
                  <span class="fileupload-new">Select image</span>
                  <span class="fileupload-exists">Change</span>
                  <input name="MAX_FILE_SIZE" hidden value="2097152" />
                  <input name="picture" type="file" />
                </span>
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
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
          <label class="control-label" for="moto">Profile Cover Photo</label>
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
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
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
          <label class="control-label" for="username">Username</label>
          <div class="controls">
            <input id="username" name="username" type="text" placeholder="username" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="password">Password</label>
          <div class="controls">
            <input id="password" name="password" type="password" placeholder="password" value="" class="input-xlarge span3">
            <a class="btn btn-small" onclick="generate(); return false;">Generate</a>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="email">Email</label>
          <div class="controls">
            <input id="email" name="email" type="email" placeholder="email" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="firstname">First Name</label>
          <div class="controls">
            <input id="firstname" name="firstname" type="text" placeholder="first name" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="middlename">Middle Name</label>
          <div class="controls">
            <input id="middlename" name="middlename" type="text" placeholder="middle name" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="lastname">Last Name</label>
          <div class="controls">
            <input id="lastname" name="lastname" type="text" placeholder="last name" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="gender">Gender</label>
          <div class="controls">
            <input type="hidden" name="gender" value="1" id="btn-input5" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled5" class="btn active">Male</button>
              <button type="button" value="0" id="btn-disabled5" class="btn">Female</button>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="address">Address</label>
          <div class="controls">
            <input id="address" name="address" type="text" placeholder="address" class="input-xlarge">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="moto">Moto</label>
          <div class="controls">
            <textarea id="moto" name="moto" class="span8"  placeholder="motto" style="width:285px; height:100px"></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="moto">Birth Date</label>
          <div class="controls">
            <div class="input-append date" id="dp3" data-date="<?php echo $user->birthdate; ?>" data-date-format="yyyy-mm-dd">
              <input name="birthdate" class="span7" size="63" type="text">
              <span class="add-on"><i class="icon-th"></i></span>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="number">Contact Number</label>
          <div class="controls">
            <input id="number" name="number" type="text" placeholder="contact number" class="input-xlarge">
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
          <label class="control-label" for="comments">SkooLyf Comments</label>
          <div class="controls">
            <input type="hidden" name="comments" value="1" id="btn-input3" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled3" class="btn active">Enabled</button>
              <button type="button" value="0" id="btn-disabled3" class="btn">Disabled</button>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="fbcomments">Facebook Comments</label>
          <div class="controls">
            <input type="hidden" name="fbcomments" value="1" id="btn-input4" />
            <div class="btn-group" data-toggle="buttons-radio">
              <button type="button" value="1" id="btn-enabled4" class="btn active">Enabled</button>
              <button type="button" value="0" id="btn-disabled4" class="btn">Disabled</button>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="btncreate"></label>
          <div class="controls">
            <button <?php echo (count($schoolusers) == 0 && count($batchusers) == 0 && count($sectionusers) == 0 ? "disabled" : "") ?> id="btncreate" name="btncreate" class="btn btn-primary">Create</button>
          </div>
        </div>

        <input type="hidden" name="what" value="user" />

        </fieldset>
        </form>
    </div>
    <div class="span1"></div>
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

  function loadSchoolSelect()
  {
    $("#schoolindicator").removeClass("hide");

    $.ajax(
    {
      type: 'GET',
      url: 'includes/webservices/get_school_i_admin.php',
      success: function(result) 
      {
        $("#schoolselect").html(result);
        $("#schoolindicator").addClass("hide");
        loadBatchSelect();
      }
    });
  }

  loadSchoolSelect();

  function loadBatchSelect()
  {
    $("#batchindicator").removeClass("hide");

    var schoolidPARAM = parseInt($("#schoolselect").val());

    $.ajax(
    {
      type: 'GET',
      url: 'includes/webservices/get_batch_i_admin_in_school.php',
      data: {schoolid: schoolidPARAM},
      success: function(result) 
      {
        $("#batchselect").html(result);
        $("#batchindicator").addClass("hide");
        loadSectionSelect();
      }
    });
  }

  function loadSectionSelect()
  {
    $("#sectionindicator").removeClass("hide");

    var batchidPARAM = parseInt($("#batchselect").val());

    $.ajax(
    {
      type: 'GET',
      url: 'includes/webservices/get_section_i_admin_in_batch.php',
      data: {batchid: batchidPARAM},
      success: function(result) 
      {
        $("#sectionselect").html(result);
        $("#sectionindicator").addClass("hide");
      }
    });
  }

  $("#schoolselect").click(function()
  {
    loadBatchSelect();
  });

  $("#batchselect").click(function()
  {
    loadSectionSelect();
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