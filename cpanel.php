<?php 

require_once("header.php"); 

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

$sound = (isset($_GET['success']) ? "positive" : "");

if($session->is_logged_in())
{
  $user = User::get_by_id($session->user_id);

  if($user->enabled == DISABLED)
  {
    header("location: index.php?disabled");
  }
  else
  {
    $schoolsIAdminCount  = count(SchoolUser::getAdminSchools($session->user_id));
    $batchsIAdminCount   = count(BatchUser::getAdminBatchs($session->user_id));
    $sectionsIAdminCount = count(SectionUser::getAdminSections($session->user_id));

    $iAdminSomething = false;

    if(($schoolsIAdminCount + $batchsIAdminCount + $sectionsIAdminCount) > 0 || $user->is_super_admin())
    {
      $iAdminSomething = true;
    }
  }
}
else
{
  header("location: index.php?negative");
}

?>

<div class="container-fluid">
  <div class="row-fluid">
    <ul class="nav nav-tabs">
      <?php if($user->is_super_admin()){ echo '<li><a id="userstab" href="#users" data-toggle="tab">Students</a></li>'; } ?>
      <li><a id="schoolstab" href="#schools" data-toggle="tab">Schools</a></li>
      <li><a id="schooluserstab" href="#schoolusers" data-toggle="tab">School Users</a></li>
      <li class="active"><a id="batchstab" href="#batchs" data-toggle="tab">Batchs</a></li>
      <li><a id="batchuserstab" href="#batchusers" data-toggle="tab">Batch Students</a></li>
      <li><a id="sectionstab" href="#sections" data-toggle="tab">Sections</a></li>
      <li><a id="sectionuserstab" href="#sectionusers" data-toggle="tab">Section Students</a></li>
      <?php if($user->is_super_admin()){ echo '<li><a id="logstab" href="#logs" data-toggle="tab">Logs</a></li>'; } ?>
    </ul>
    
    <div class="tab-content">
      <div class="tab-pane" id="users"><?php require_once("public/grids/users.php"); ?></div>
      <div class="tab-pane" id="schools"><?php require_once("public/grids/schools.php"); ?></div>
      <div class="tab-pane" id="schoolusers"><?php require_once("public/grids/schoolusers.php"); ?></div>
      <div class="tab-pane active" id="batchs"><?php require_once("public/grids/batchs.php"); ?></div>
      <div class="tab-pane" id="batchusers"><?php require_once("public/grids/batchusers.php"); ?></div>
      <div class="tab-pane" id="sections"><?php require_once("public/grids/sections.php"); ?></div>
      <div class="tab-pane" id="sectionusers"><?php require_once("public/grids/sectionusers.php"); ?></div>
      <div class="tab-pane" id="logs"><?php require_once("public/grids/logs.php"); ?></div>
    </div>

  </div><!--/row-->

  <script>

    $("#userstab").click(function()
    {
      $("#grid_users").trigger("reloadGrid");
    });

    $("#schoolstab").click(function()
    {
      $("#grid_schools").trigger("reloadGrid");
    });

    $("#schooluserstab").click(function()
    {
      $("#grid_schoolusers").trigger("reloadGrid");
    });

    $("#batchstab").click(function()
    {
      $("#grid_batchs").trigger("reloadGrid");
    });

    $("#batchuserstab").click(function()
    {
      $("#grid_batchusers").trigger("reloadGrid");
    });

    $("#sectionstab").click(function()
    {
      $("#grid_sections").trigger("reloadGrid");
    });

    $("#sectionuserstab").click(function()
    {
      $("#grid_sectionusers").trigger("reloadGrid");
    });

    $("#logstab").click(function()
    {
      $("#grid_logs").trigger("reloadGrid");
    });

  </script>
  
<?php require_once("footer.php"); ?>