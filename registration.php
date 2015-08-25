<?php 

require_once("header.php"); 

if($session->is_logged_in())
{
  header("location: index.php?negative");
}

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

if(isset($_POST['registration_submit']))
{
  $resp = recaptcha_check_answer(RECAPTCHA_PRIVATE, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

  if($resp->is_valid)
  {
    if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != "" && $_POST['password'] != "")
    {
      $username_exists  = User::username_exists($_POST['username']);
      $email_exists     = false;

      if(isset($_POST['email']) && $_POST['email'] != "")
      {
        $email_exists = User::email_exists($_POST['email']);
      }

      if($username_exists)
      {
        $sound = "negative";
        $message .= "Sorry, the username: <i><b>".$_POST['username'].'</b></i> is already taken. Please choose a different one.<br />';
      }

      if($email_exists)
      {
        $sound = "negative";
        $message .= "Sorry, the email: <i><b>".$_POST['email'].'</b></i> is already registered.';
      }

      if($message == "")
      {
        $user = new User();
        $user->username   = $_POST['username'];
        $user->password   = $_POST['password'];
        $user->email      = $_POST['email'];
        $user->create();

        $session->login($user);

        $log = new Log($user->id, $clientip, "WEB", "REGISTERED"); $log->create();

        header("location: school.php?id=26");
      }
    }
    else
    {
      $sound = "negative";
      $message = "Please enter a username and a password.";
      $log = new Log(0, $clientip, "WEB", "REGISTER NOT FILLED"); $log->create();
    }
  }
  else
  {
    $sound = "negative";
    $message = "The CAPTCHA entered is invalid. <br/> Please try again.";
    $log = new Log(0, $clientip, "WEB", "REGISTER INVALID CAPTCHA"); $log->create();
  }
}

?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span1"></div>
    <div class="span9">
      <form class="form-horizontal" method="post" action="#" enctype="multipart/form-data">
        <fieldset>
        <legend>
          Registration
        </legend>

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
          <label class="control-label" for="registration_submit">Bot Filter</label>
          <div class="controls">
             <script type="text/javascript">
               var RecaptchaOptions = 
               {
                  theme : 'clean'
               };
             </script>
              <?php

               echo recaptcha_get_html(RECAPTCHA_PUBLIC);

              ?>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="registration_submit"></label>
          <div class="controls">
            <button id="registration_submit" name="registration_submit" class="btn btn-primary">Register</button>
          </div>
        </div>
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

</script>

<?php require_once("footer.php"); ?>