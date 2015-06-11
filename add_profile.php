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

if(hasPid())
  {
  
  header("Location: view_profile.php");
  die();
}



if (!empty($_POST)) {
  $errors = array();

  //profile
  $gender = $_POST["gender"];

  if (isset($_POST["age"]))
    $age = trim($_POST["age"]);
  else {
    $errors[] = lang("PROFILE_INVALID_AGE");
    $age = NULL;
  }
  if (isset($_POST["income"]))
    $income = trim($_POST["income"]);
  else {
    $errors[] = lang("PROFILE_INVALID_AGE");
    $income = NULL;
  }

  $relationship = $_POST["relationship"];
  $haskid = $_POST["haskid"];
  $wantkid = $_POST["wantkid"];

  if (isset($_POST["height"]))
    $height = trim($_POST["height"]);
  else {
    $errors[] = lang("PROFILE_INVALID_AGE");
    $height = NULL;
  }
  
  if (isset($_POST["address"]))
    $address = trim($_POST["address"]);
  else {
    $address = NULL;
  }
  $education = $_POST["education"];
  $smoke = $_POST["smoke"];
  $drink = $_POST["drink"];
  $ethnicity = $_POST["ethnicity"];
  $bodytype = $_POST["bodytype"];



  if (count($errors) == 0) {
    //Construct a user object
  }
  //add to db
  //echo $loggedInUser->user_id;
  // $loggedInUser->hasProfile();

  $profile = new profile();
  $profile->uid = $loggedInUser->user_id;
  $profile->gender = $gender;
  $profile->age = $age;
  $profile->income = $income;
  $profile->relationship = $relationship;
  $profile->haskid = $haskid;
  $profile->wantkid = $wantkid;
  $profile->height = $height;
  $profile->education = $education;
  $profile->smoke = $smoke;
  $profile->drink = $drink;
  $profile->ethnicity = $ethnicity;
  $profile->bodytype = $bodytype;
  $profile->address = $address;
      if ($_FILES["photo"]["error"]!=4){
      $photoFile = $_FILES["photo"];
      $photoAddress=uploadImg($photoFile);
      $profile->useOldPhoto=false;
    }
    
    // use old photo.
    else {
      $photoAddress=$profile->getOldPhoto();     
      $profile->useOldPhoto=true;
    }
  $profile->photo=$photoAddress;
  $profile->addtodb();
 header("Location: view_profile.php");
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
              <legend>Your Profile</legend>
             <div class="control-group">
                <label class="control-label" for="input01">Photo</label>
                <div class="controls">
                  <div>  <?php echo getImageXY(NULL);?></div>
                 <div ><input type='file' onchange="readURL(this);"  name="photo" /></div>

                </div>
                
              </div>
              
              <div class="control-group">
                <label class="control-label" for="input01">Gender</label>
                <div class="controls">
                  <select name="gender" id="input01">
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                  </select> 
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input02">Age</label>
                <div class="controls">
                  <input type="text" name='age' class="input-xlarge" id="input02">
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="input03">Income</label>
                <div class="controls">
                  <input type="text" name='income' class="input-xlarge" id="input03">
                  <p class="help-block">your general annual income</p> 
                </div>
              </div> 
              <div class="control-group">
                <label class="control-label" for="input04">Relationship</label>
                <div class="controls">
                  <select name="relationship" id="input04">
                    <option value ="1" >Never Married</option>
                    <option value ="2">Currently Separated</option>
                    <option value ="3">Divorced</option>
                    <option value ="4">Widow / Widower</option>
                  </select>         
                </div>
              </div> 
              <div class="control-group">
                <label class="control-label" for="input05">Has Kid?</label>
                <div class="controls">
                  <select name="haskid" id="input05">
                    <option value ="1" >No</option>
                    <option value ="2">Yes</option>
                  </select>
                </div>
              </div> 
              
          </fieldset>
            </div>
           <div class="col-lg-6">
             <fieldset>
               
               
               <br><br>
              <div class="control-group">
                <label class="control-label" for="input06">Want Kid?</label>
                <div class="controls">
                  <select name="wantkid" id="input06">
                    <option value ="1" >No</option>
                    <option value ="2">Yes</option>
                  </select>
                </div>
              </div>
                          
  
               
               
             
              <div class="control-group">
                <label class="control-label" for="input07">Height</label>
                <div class="controls">
                  <input type="text" name='height' class="input-xlarge" id="input07">
                  <p class="help-block">e.g 5.8 for 5 feet 8 inch</p>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input08">Education</label>
                <div class="controls">
                  <select name="education" id="input08">
                    <option value ="1" >High School</option>
                    <option value ="2">Bachelor Degree</option>
                    <option value ="3">Master Degree</option>
                    <option value ="4">PHD and above</option>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input09">Do you smoke?</label>
                <div class="controls">
                  <select name="smoke" id="input09">
                    <option value ="1" >No</option>
                    <option value ="2">Yes</option>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input10">Do you drink a lot?</label>
                <div class="controls">
                  <select name="drink" id="input10">
                    <option value ="1" >No</option>
                    <option value ="2">Yes</option>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input11">Ethnicity</label>
                <div class="controls">
                  <select name="ethnicity" id="input11">
                    <option value ="1" >Asian</option>
                    <option value ="2">Latino / Hispanic</option>
                    <option value ="3">Black / African descent</option>
                    <option value ="4">White / Caucasian</option>
                    <option value ="5">Indian</option>
                    <option value ="6">Native American</option>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input12">Body Type</label>
                <div class="controls">
                  <select name="bodytype" id="input12">
                    <option value ="1" >Slender</option>
                    <option value ="2">About average</option>
                    <option value ="3">Athletic and toned</option>
                    <option value ="4">A few extra pounds</option>
                    <option value ="5">Stocky</option>
                  </select>
                </div>
              </div>
              
                          <div class="control-group">
                <label class="control-label" for="input07">Address</label>
                <div class="controls">
                  <input type="text" name='address' class="input-xlarge" id="input02"><br>
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


