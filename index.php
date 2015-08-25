<?php 

require_once("header.php"); 

$pathinfo = pathinfo($_SERVER["PHP_SELF"]);
$basename = $pathinfo["basename"];
$currentFile = str_replace(".php","", $basename);

$sound = (isset($_GET['positive']) ? "positive" : "");
$sound = (isset($_GET['negative']) ? "negative" : $sound);

$disabled = (isset($_GET['disabled']) ? "disabled" : "");

if($disabled)
{
  $message = "Sorry. Your account is disabled by the admin for some reason.";
}

header("location: school.php?id=26");

?>


  <div class="mybanner span12 offset3">
    <img src="public/img/skoolyfbanner.png" alt="">
  </div>

    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="span4">
          <img class="img-circle" data-src="holder.js/140x140">
          <h2>Digital Yearbook</h2>
          <p>
          	Create stunning yearbooks with ease using our powerful creation tools and design tools.
          </p>
          <span class="button-wrap"><a href="#" class="button button-circle button-flat-primary">More Info</a></span>
        </div><!-- /.span4 -->
        <div class="span4">
          <img class="img-circle" data-src="holder.js/140x140">
          <h2>Digital School</h2>
          <p>Creating yearbooks automatically builds up your digital school. </p>
          <span class="button-wrap"><a href="#" class="button button-circle button-flat-caution">More Info</a></span>
        </div><!-- /.span4 -->
        <div class="span4">
          <img class="img-circle" data-src="holder.js/140x140">
          <h2>Digital Community</h2>
          <p>Write testominals, send a message or chat with your schoolmates and classmates.</p>
          <span class="button-wrap"><a href="#" class="button button-circle button-flat-action">More Info</a></span>
        </div><!-- /.span4 -->
      </div><!-- /.row -->


      <!-- START THE FEATURETTES -->

      <hr class="featurette-divider">

      <div class="featurette">
        <img class="featurette-image pull-right" src="public/img/featured.png">
        <h2 class="featurette-heading">Ambot ano nadi ibutang <span class="muted">Testing lang ni.</span></h2>
        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
      </div>

      <hr class="featurette-divider">

      <div class="featurette">
        <img class="featurette-image pull-left" src="public/img/featured.png">
        <h2 class="featurette-heading">Kag diri pagid ano pagd nadi<span class="muted">Testing lang ni.</span></h2>
        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
      </div>

      <hr class="featurette-divider">

      <div class="featurette">
        <img class="featurette-image pull-right" src="public/img/featured.png">
        <h2 class="featurette-heading">Ambot ano pagid nadi ibutang<span class="muted">Testing lang ni.</span></h2>
        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
      </div>

      <hr class="featurette-divider">

      <!-- /END THE FEATURETTES -->


      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2013 Nemory Development Studios. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>

    </div><!-- /.container -->
  
<?php require_once("footer.php"); ?>