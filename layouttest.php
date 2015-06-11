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

require_once("models/header.php");

if (!empty($_POST)) {
  $errors = array();


//requirement
  $mtype = $_POST["mtype"];
  
    if (isset($_POST["score"]))
     $score = $_POST["score"];
  else {
    
    $score = NULL;
  }
  
  
$rgender = $_POST["rgender"];
  if (isset($_POST["agefrom"]))
    $agefrom = trim($_POST["agefrom"]);
  else {
    $errors[] = lang("PROFILE_INVALID_AGE");
    $agefrom = NULL;
  }

  if (isset($_POST["ageto"]))
    $ageto = trim($_POST["ageto"]);
  else {
    $errors[] = lang("PROFILE_INVALID_AGE");
    $ageto = NULL;
  }

  if (isset($_POST["rincome"]))
    $rincome = trim($_POST["rincome"]);
  else {
    $errors[] = lang("PROFILE_INVALID_AGE");
    $rincome = NULL;
  }

  if (isset($_POST["rhaskid"]))
    $rhaskid = trim($_POST["rhaskid"]);
  else {
    $rhaskid = NULL;
  }
  if (isset($_POST["rwantkid"]))
    $rwantkid = trim($_POST["rwantkid"]);
  else {
    $rwantkid = NULL;
  }

  if (isset($_POST["heightfrom"]))
    $heightfrom = trim($_POST["heightfrom"]);
  else {
    $errors[] = lang("PROFILE_INVALID_AGE");
    $heightfrom = NULL;
  }

  if (isset($_POST["heightto"]))
    $heightto = trim($_POST["heightto"]);
  else {
    $errors[] = lang("PROFILE_INVALID_AGE");
    $heightto = NULL;
  }


  if (isset($_POST["reducation"]))
    $reducation = trim($_POST["reducation"]);
  else {
    $reducation = NULL;
  }

  if (isset($_POST["rsmoke"]))
    $rsmoke = trim($_POST["rsmoke"]);
  else {
    $rsmoke = NULL;
  }

  if (isset($_POST["rdrink"]))
    $rdrink = trim($_POST["rdrink"]);
  else {
    $rdrink = NULL;
  }

  if (isset($_POST["rethnicity"]))
    $rethnicity = $_POST["rethnicity"];
  else {
    $rethnicity = NULL;
  }

  if (isset($_POST["rbodytype"]))
    $rbodytype = $_POST["rbodytype"];
  else {
    $rbodytype = NULL;
  }

  if (isset($_POST["rrelationship"]))
    $rrelationship = $_POST["rrelationship"];
  else {
    $rrelationship = NULL;
  }

  if (isset($_POST["winfo"]))
    $winfo = $_POST["winfo"];
  else {
    $winfo = NULL;
    }
    
   
  

  /* check the input.
    if (minMaxRange(5, 25, $username)) {
    $errors[] = lang("ACCOUNT_USER_CHAR_LIMIT", array(5, 25));
    }
    if (!ctype_alnum($username)) {
    $errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
    }
    if (minMaxRange(5, 25, $displayname)) {
    $errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT", array(5, 25));
    }
    if (!ctype_alnum($displayname)) {
    $errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
    }
   */
  //End data validation
  if (count($errors) == 0) {
    //Construct a user object
  }


  $rprofile = new rprofile();
  $rprofile->uid = $loggedInUser->user_id;
  $rprofile->mtype = $mtype;
   $rprofile->score = $score;
  $rprofile->gender = $rgender;
  $rprofile->agefrom = $agefrom;
  $rprofile->ageto = $ageto;
  $rprofile->income = $rincome;
  $rprofile->relationship = $rrelationship;
  $rprofile->haskid = $rhaskid;
  $rprofile->wantkid = $rwantkid;
  $rprofile->heightfrom = $heightfrom;
  $rprofile->heightto = $heightto;
  $rprofile->education = $reducation;
  $rprofile->smoke = $rsmoke;
  $rprofile->drink = $rdrink;
  $rprofile->ethnicity = $rethnicity;
  $rprofile->bodytype = $rbodytype;
  $rprofile->winfo = $winfo;

  $rprofile->clearTables();
  $rprofile->addtodb();
  $rprofile->addEthnicity();
  $rprofile->addBodytype();
  $rprofile->addRelationship();
}

$old_rprofile = new Rprofile();
$old_rprofile->ini();
//var_dump($old_rprofile->winfo);
?>


<body>


  <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
          <div class="site-heading">
            <h1>Building a profile for match!</h1>
            <hr class="small">
            <span class="subheading"></span>

          </div>
        </div>
      </div>
    </div>
  </header>

  

<div class="container">
    <div class="control-group">
        <p class="pull-left">
            <label class="control-label" for="Languages">Lingue</label>
        </p>
        <div class="controls">
            <div class="btn-group btn-group-horizontal" data-toggle="buttons-checkbox">
                <input type="checkbox" value="1" id="Languages_0" name="Languages" checked
                />
                <label class="btn btn-info btn-small active" for="Languages_0">Italiano</label>
                <input type="checkbox" value="2" id="Languages_1" name="Languages"
                checked />
                <label class="btn btn-info btn-small active" for="Languages_1">Francese</label>
                <input type="checkbox" value="3" id="Languages_2" name="Languages"
                checked />
                <label class="btn btn-info btn-small active" for="Languages_2">Inglese</label>/></div>
        </div>
        <div class="controls">
            <div class="btn-group btn-group-horizontal" data-toggle="buttons-checkbox">
                <input type="checkbox" value="4" id="Languages_3" name="Languages" />
                <label class="btn btn-info btn-small " for="Languages_3">Spagnolo</label>
                <input type="checkbox" value="5" id="Languages_4" name="Languages"
                />
                <label class="btn btn-info btn-small " for="Languages_4">Tedesco</label>
                <input type="checkbox" value="6" id="Languages_5" name="Languages"
                />
                <label class="btn btn-info btn-small " for="Languages_5">Portoghese</label>
            </div>
        </div>
        <div class="controls">
            <div class="btn-group btn-group-horizontal" data-toggle="buttons-checkbox">
                <input type="checkbox" value="7" id="Languages_6" name="Languages" />
                <label class="btn btn-info btn-small " for="Languages_6">Bulgaro</label>
                <input type="checkbox" value="8" id="Languages_7" name="Languages"
                />
                <label class="btn btn-info btn-small " for="Languages_7">Lettone</label>
            </div>
        </div>
    </div>
</div>
  <?php
  require_once("footer.php");
  ?>


