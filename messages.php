<?php 

require_once("header.php"); 

if($session->is_logged_in())
{
  $user = User::get_by_id($session->user_id);
}
else
{
  header("location: index.php?negative");
}

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);
$pageURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>

<div class="container-fluid">
  <div class="row-fluid">

    <div class="span10 offset2">
      <div class="span9 box">

        <p>
          <span class="myname">Messages</span>
        </p>

        <div class="row-fluid">

          <div class="span4 boxcontent">

            <div class="btn-group">
              <button class="btn btn-mini"><i class="icon-envelope"></i> Unread</button>
              <button class="btn btn-mini"><i class="icon-ok"></i> Read</button>
              <button class="btn btn-mini"><i class="icon-trash"></i> Deleted</button>
              <button class="btn btn-mini"><i class="icon-pencil"></i> New</button>
            </div>            

            <br /><br />
            <table class="table table-striped" data-provides="rowlink">
              <tr>
                <td>
                  <a href="#">
                    <p><img src="public/img/logo.png" style="height:30px;" /> Oliver Martinez</p>
                    <p><span class="messagetext">
                      This is my message to you. This is my message to you. 
                      This is my message to you. This is my message to you. 
                    </span></p>
                    <p><span class="messagedate pull-right">October 3, 1992</span></p>
                  </a>
                <td>
              </tr>
              <tr>
                <td>
                  <a href="#">
                    <p><img src="public/img/logo.png" style="height:30px;" /> Oliver Martinez</p>
                    <p><span class="messagetext">
                      This is my message to you. This is my message to you. 
                      This is my message to you. This is my message to you. 
                    </span></p>
                    <p><span class="messagedate pull-right">October 3, 1992</span></p>
                  </a>
                <td>
              </tr>
              <tr>
                <td>
                  <a href="#">
                    <p><img src="public/img/logo.png" style="height:30px;" /> Oliver Martinez</p>
                    <p><span class="messagetext">
                      This is my message to you. This is my message to you. 
                      This is my message to you. This is my message to you. 
                    </span></p>
                    <p><span class="messagedate pull-right">October 3, 1992</span></p>
                  </a>
                <td>
              </tr>
            </table>

          </div>

          <div class="span8 boxcontent">
            <span class="myheader">Conversations</span>
            <div class="well well-small">
              I'm in a well
              <p><span class="messagedate pull-right">October 3, 1992</span></p>
            </div>
            <div class="well well-small">
              I'm in a well, I'm in a well, I'm in a well, I'm in a well, 
              I'm in a well, I'm in a well, I'm in a well, I'm in a well, 
              I'm in a well, I'm in a well, I'm in a well, I'm in a well, 
              I'm in a well, I'm in a well, 
              <p><span class="messagedate pull-right">October 3, 1992</span></p>
            </div>
            <div class="well well-small">
              I'm in a well
              <p><span class="messagedate pull-right">October 3, 1992</span></p>
            </div>
            <div class="well well-small">
              I'm in a well, I'm in a well, I'm in a well, I'm in a well, 
              I'm in a well, I'm in a well, I'm in a well, I'm in a well, 
              I'm in a well, I'm in a well, I'm in a well, I'm in a well, 
              I'm in a well, I'm in a well, 
              <p><span class="messagedate pull-right">October 3, 1992</span></p>
            </div>
          </div>

          <div class="span8 boxcontent">
            <textarea rows="3" style="width:97%;" placeholder="Your message here"></textarea>
            <button id="btnsend" name="btnsend" class="button button-pill button-flat-primary">Send</button>
            <button id="btndelete" name="btndelete" class="button button-pill button-flat-caution">Delete</button>
          </div>

        </div>
      </div>

    </div><!--MAIN SPAN10-->
  </div><!--/row-->
<?php require_once("footer.php"); ?>