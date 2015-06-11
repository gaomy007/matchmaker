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
if(hasRpid())
  {
  header("Location: view_requirement.php");
  die();
}


if (!empty($_POST)) {
  $errors = array();


//requirement
  $rgender = $_POST["rgender"];
  $mtype = $_POST["mtype"];
  
  
    if (isset($_POST["score"]))
    $score = $_POST["score"];
  else {
    $score = NULL;
  }
  
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
  header("Location: view_requirement.php");
}

require_once("models/header.php");
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
    <div class="row">
      <div class="col-sm-3 col-md-2 sidebar">
        <?php include("left-nav.php"); ?>
      </div>

      <!--right side-->
      <div class="col-lg-8">
        <form class="form-horizontal"  action='<?php echo $_SERVER['PHP_SELF']; ?>' enctype="multipart/form-data" method='post'>
          <div class="col-lg-6">
            <fieldset>
              <legend>Your Search Requirement</legend>
              
             <div class="control-group">
                <label class="control-label" for="input014">Match Type</label>
                <div class="controls">
                  <select name="mtype" id="input014" onchange="disable(this.selectedIndex);">
                    <option value="1">Mandatory field is Necessary</option>
                    <option value="2">Mandatory field take more scores.</option>
                  </select> 
                </div>
              </div>
              <br>
              
             <div class="control-group" id="score" <?php if ($old_rprofile->mtype == 1) echo 'style="display:none"'; ?>>
                <label class="control-label" for="input015">Score</label>
                <div class="controls">
                  <select name="score" id="input015">
                    <option value="10">10%</option>
                    <option value="20">20%</option>                    
                    <option value="30">30%</option>
                    <option value="40">40%</option>                    
                    <option value="50">50%</option>
                    <option value="60">60%</option>                    
                    <option value="70">70%</option>
                    <option value="80">80%</option>                    
                    <option value="90">90%</option>
                    <option value="100">100%</option>
                  </select> 
                </div>
              </div>
              <br>
              
              <div class="control-group">
                <label class="control-label" for="input01">Gender</label>
                <div class="controls">
                  <select name="rgender" id="input01">
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                  </select> 
                </div>
              </div>
              <br>
              <div class="control-group">
                <label class="controls" for="input02">Age</label>
                <div class="controls">
                  <label class="control-label" for="input02">from</label>
                                    <input type="checkbox" name="winfo[]" value="1">
                  Check to Mandatory<br>
                  <input type="text" name='agefrom' class="input-xlarge" id="input02"><br>
                  <label class="control-label" for="input02">to&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <input type="text" name='ageto' class="input-xlarge" id="input02">
                </div>
              </div>
              <br>
              <div class="control-group">
                <label class="control-label" for="input03">Income</label>
                                <input type="checkbox" name="winfo[]" value="2">
                <div class="controls">
                  <input type="text" name='rincome' class="input-xlarge" id="input03">
                  <p class="help-block">required general annual income</p> 
                </div>
              </div> 
              <div class="control-group">
                <label class="control-label" for="input04">Relationship</label>
                <input type="checkbox" name="winfo[]" value="3">
                <div class="controls">
                  <input type="checkbox" name="rrelationship[]" value="1" >Never Married<br>
                  <input type="checkbox" name="rrelationship[]" value="2" >Currently Separated<br>
                  <input type="checkbox" name="rrelationship[]" value="3" >Divorced<br>
                  <input type="checkbox" name="rrelationship[]" value="4" >Widow / Widower<br>      
                </div>
              </div> 
              <div class="control-group">
                <label class="control-label" for="input05">Has Kid?</label>
                <input type="checkbox" name="winfo[]" value="4">
                <div class="controls">
                  <select name="rhaskid" id="input05">
                    <option value ="1" >No</option>
                    <option value ="2">Yes</option>
                  </select>
                </div>
              </div> 
 
            
            
            </fieldset>
            </div>
           <div class="col-lg-6">
          <fieldset>
            
            <br>
            <div class="control-group">
                <label class="control-label" for="input06">Want Kid?</label>
                <input type="checkbox" name="winfo[]" value="5">
                <div class="controls">
                  <select name="rwantkid" id="input06">
                    <option value ="1" >No</option>
                    <option value ="2">Yes</option>
                  </select>
                </div>
              </div>
              <br>
              <div class="control-group">
                <label class="control-label" for="input07">Height</label>
                <div class="controls">
                  <label class="control-label" for="input07">from</label>
                  <input type="checkbox" name="winfo[]" value="6">
                  <input type="text" name='heightfrom' class="input-xlarge" id="input02"><br>
                  <label class="control-label" for="input07">to&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <input type="text" name='heightto' class="input-xlarge" id="input02">
                </div>
              </div>
 
              <br>
              <div class="control-group">
                <label class="control-label" for="input08">Education</label>
                <input type="checkbox" name="winfo[]" value="7">
                <div class="controls">
                  <select name="reducation" id="input08">
                    <option value ="1" >High School or above</option>
                    <option value ="2">Bachelor Degree or above</option>
                    <option value ="3">Master Degree or above</option>
                    <option value ="4">PHD and above</option>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input09">Do you smoke?</label>
                <input type="checkbox" name="winfo[]" value="8">
                <div class="controls">
                  <select name="rsmoke" id="input09">
                    <option value ="1" >No</option>
                    <option value ="2">Yes</option>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input10">Do you drink a lot?</label>
                <input type="checkbox" name="winfo[]" value="9">
                <div class="controls">
                  <select name="rdrink" id="input10">
                    <option value ="1" >No</option>
                    <option value ="2">Yes</option>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input11">Ethnicity</label>
                <input type="checkbox" name="winfo[]" value="10">
                <div class="controls">

                  <input type="checkbox" name="rethnicity[]" value="1">Asian<br>
                  <input type="checkbox" name="rethnicity[]" value="2">Latino / Hispanic <br>
                  <input type="checkbox" name="rethnicity[]" value="3">Black / African descent<br>
                  <input type="checkbox" name="rethnicity[]" value="4">White / Caucasian<br>
                  <input type="checkbox" name="rethnicity[]" value="5">Indian<br>
                  <input type="checkbox" name="rethnicity[]" value="6">Native American<br>

                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input12">Body Type</label>
                <input type="checkbox" name="winfo[]" value="11">
                <div class="controls">

                  <input type="checkbox" name="rbodytype[]" value="1">Slender<br>
                  <input type="checkbox" name="rbodytype[]" value="2">About average<br>
                  <input type="checkbox" name="rbodytype[]" value="3">Athletic and toned<br>
                  <input type="checkbox" name="rbodytype[]" value="4">A few extra pounds<br>
                  <input type="checkbox" name="rbodytype[]" value="5">Stocky<br>

                </div>
              </div>
            </fieldset>
          </div>
          <div class='col-lg-offset-4'>
            <button class="btn btn-primary" type='submit'>submit</button>
          </div>
        </form>


      </div>



    </div>
  </div>
  <?php
  require_once("footer.php");
  ?>


