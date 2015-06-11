<?php
/*
  UserCake Version: 2.0.2
  http://usercake.com
 */

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}
require_once("models/header.php");
?>



  <div class="container" id="low-container">
    <div class="row">
      <div class="col-sm-3 col-md-2 sidebar">
        <?php include("left-nav.php"); ?>
      </div>
      <div class="col-lg-8">
          <span><h1>Welcome Back</h1></span>  
      </div>
    </div>
  </div>
<?php
require_once("footer.php");
?>


