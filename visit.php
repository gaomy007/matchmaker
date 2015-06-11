<?php
/*
  UserCake Version: 2.0.2
  http://usercake.com
 */

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

if (!isUserLoggedIn()) {
  header("Location: index.php");
  die();
}
$user_auth = isset($_GET['uid']);
if (!$user_auth) {
  echo "no uid provided";
  die();
}
$uid = $_GET['uid'];
$loggedInUser = $_SESSION["userCakeUser"];
$ma = new matchMachine();
// echo "<br><br>";
//var_dump($ma);
$ma->iniForPeerMatch($uid, $loggedInUser->user_id);
$isMatch = $ma->peerMatchForAuth();

if (!$isMatch)
  header("Location: index.php");
$ma->peerMatchForScore();
//echo $ma->current_archive->profile->address;
require_once("models/header.php");
?>
<body>

  <div class="container" id="low-container">
    <div class="row">
      <div class="col-sm-2  sidebar">
        <?php include("left-nav.php"); ?>
      </div> 

      
      <div class="col-sm-10 ">
     <fieldset class="scheduler-border">
       <legend class="scheduler-border">Personal Information</legend>
      <div class="col-sm-6">
        <?php
        $ma->current_archive->showInVisit("left", $ma->current_archive->score);
        ?>



<!--<script type="text/javascript" src="./blab_im/bar.php"></script>-->
      </div>
   
    
          <div class="col-sm-6">
        <?php
      
        
        $ma->visitor->showInVisit("right", $ma->current_archive->rscore);
        ?>
    
    
      </div>
                <script>
          $(document).ready(function() {

            $('#submit').click(function() {

              $.post("sendPM.php", $("#mycontactform").serialize(), function(response) {
                $('#success').html(response);
//$('#success').hide('slow');
              });
              return false;

            });

          });
        </script>
        <div class="col-lg-8 col-md-offset-1">
<br>
        <form action="" method="post" id="mycontactform" class="form-horizontal">
          <fieldset class="scheduler-border">
            <legend class="scheduler-border">Send a Message</legend>
          <input type="hidden" type="text" name="namefrom" id="name" value="<?php echo $ma->visitor->profile->displayName; ?>"/>
          <input  type="hidden" type="text" name="nameto" id="name" value="<?php echo $ma->current_archive->profile->displayName; ?>"/>
          <input  type="hidden" type="text" name="emailfrom" id="email" value="<?php echo $ma->visitor->profile->email; ?>" />
           <input  type="hidden"  type="text" name="emailto" id="email" value="<?php echo $ma->current_archive->profile->email; ?>" />
           <div class="form-group">
             <label for="subject" class="col-sm-2 control-label">Subject</label>
             <div class="col-sm-6">
            <input type="text" name="subject" id="subject" />
            </div>
          </div>
           
           <div class="form-group">
            
               <label for="message" class="col-sm-2 control-label">Content</label>
               <div id="success" style="color:red;"></div>
                <div class="col-sm-8">
            <textarea name="message" id="message" rows="5" cols="50">Hi <?php echo $ma->current_archive->profile->displayName; ?>: </textarea ><br />
          </div>
           </div>
           
           <div class="form-group">
             <div class="col-sm-offset-4 col-sm-2">
            <input class="btn btn-success" type="button" value="send" id="submit" />
           </div>
           </div>    
          
          </fieldset>
        </form>
          
        </div>
        </fieldset>
     </div> 
    </div>
  </div>  
<?php
require_once("footer.php");
?>



