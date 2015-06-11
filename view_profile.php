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

  if (isset($_POST["height"])){
  $height = trim($_POST["height"]);
  }
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
  //$profile->photo = $photo;
  
  //handle photo    
  //user upload new photo
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

}

$old_profile = new Profile();
$old_profile->ini();

//var_dump($old_rprofile->winfo);
?>


<body>



  <div class="container"  id="low-container">
    <div class="row">
      <div class="col-sm-2  sidebar">
        <?php include("left-nav.php"); ?>
      </div>

      <!--right side-->
    <div class="col-sm-10">

        <form class="form-horizontal"  action='<?php echo $_SERVER['PHP_SELF']; ?>' enctype="multipart/form-data" method='post'>
               <fieldset class="scheduler-border">
       <legend class="scheduler-border">Your Profile</legend>
          
             
              
              <table class="table borderless ">

              <tbody>
                <tr> <td colspan="2"><div class="sec-block-tt clearfix" style="padding-left: 0px;"><span>Basic Information</span></div></td> </tr>
                <tr>
          
                <td>Photo:</td>
                <td>
                  <div>  <?php echo getImageXY($old_profile->photo);?></div>
                 <div ><input type='file' onchange="readURL(this);"  name="photo" /></div>

               </td>
                
           
                 </tr>
                 
                 <tr>
           
                <td>Gender</td>
                <td>
                  <select name="gender" id="input01">
                    <option value="1" <?php if ($old_profile->gender == 1) echo 'selected'; ?>>Male</option>
                    <option value="2" <?php if ($old_profile->gender == 2) echo 'selected'; ?>>Female</option>
                  </select> 
                </td>
               

                </tr>
                
              <tr>
                <td>Age</td>
                
                <td>
                  <input type="text" name='age' class="input-xlarge" id="input02"
                         value="<?php if (!$old_profile->age == NULL) echo $old_profile->age; ?>"
                         >
                </td>
           
                
              </tr>
              
              <tr>
                <td>Height</td>
                <td>
                  <input type="text" name='height' class="input-xlarge" id="input07"
                         value="<?php if (!$old_profile->height == NULL) echo $old_profile->height; ?>"  
                         >
                  
                </td>
               
              </tr>
              
             <tr>
                <td>Ethnicity</td>
                <td>
                  <select name="ethnicity" id="input11">
                    <option value ="1" <?php if ($old_profile->ethnicity == 1) echo 'selected'; ?>>Asian</option>
                    <option value ="2" <?php if ($old_profile->ethnicity == 2) echo 'selected'; ?>>Latino / Hispanic</option>
                    <option value ="3" <?php if ($old_profile->ethnicity == 3) echo 'selected'; ?>>Black / African descent</option>
                    <option value ="4" <?php if ($old_profile->ethnicity == 4) echo 'selected'; ?>>White / Caucasian</option>
                    <option value ="5" <?php if ($old_profile->ethnicity == 5) echo 'selected'; ?>>Indian</option>
                    <option value ="6" <?php if ($old_profile->ethnicity == 6) echo 'selected'; ?>>Native American</option>
                  </select>
                </td>
                
              </tr>
                            
              <tr>
                <td>Body Type</td>
                <td>
                  <select name="bodytype" id="input12">
                    <option value ="1" <?php if ($old_profile->bodytype == 1) echo 'selected'; ?>>Slender</option>
                    <option value ="2" <?php if ($old_profile->bodytype == 2) echo 'selected'; ?>>About average</option>
                    <option value ="3" <?php if ($old_profile->bodytype == 3) echo 'selected'; ?>>Athletic and toned</option>
                    <option value ="4" <?php if ($old_profile->bodytype == 4) echo 'selected'; ?>>A few extra pounds</option>
                    <option value ="5" <?php if ($old_profile->bodytype == 5) echo 'selected'; ?>>Stocky</option>
                  </select>
                </td>
                
              </tr>
              
              <tr>
                <td><div class="row">
                    <div class="col-sm-5" style="padding-top: 10px;padding-right: 0px; width: 65px;">Address</div> 
                    <div class="col-sm-1" style="padding-left: 0px; padding-top: 3px;">
                      <!--<span class="quest-icon-blank quest-icon" id='example'></span>-->
       <script>
$(function ()  
{
  $('#example').popover(
  {
     trigger: 'hover',
     html: true,
     placement: 'right',
     content: 'hello world'
  });
});
</script>
                     
<a href="#" id="example" class="quest-icon-blank quest-icon" rel="popover" 
   data-content="We present your proximate location when your information matches that of other users. 
   Only your city name will be publicly visible instead of your full address."
   data-original-title="<h3>Why we need your location?</h3>"></a>
                    </div>
                  </div>
                
                
                
                </td>
                <td>
                  <input type="text" name='address' class="input-xlarge" id="input02"
                         value="<?php if (!$old_profile->address == NULL) echo $old_profile->address; ?>"
                         >
                </td>
                
                
                
              </tr>
              
           <tr> <td colspan="2"><div class="sec-block-tt clearfix" style="padding-left: 0px;"><span>Background</span></div></td> </tr>
              <tr>
                <td>Income</d>
                <td>
                  <input type="text" name='income' class="input-xlarge" id="input03"
                         value="<?php if (!$old_profile->income == NULL) echo $old_profile->income; ?>"
                         >
                 
                  </td>
                  
               
              </tr> 
              
              
              <tr>
                <td>Relationship</td>
                <td>
                  <select name="relationship" id="input04">
                    <option value ="1" <?php if ($old_profile->relationship == 1) echo 'selected'; ?>>Never Married</option>
                    <option value ="2" <?php if ($old_profile->relationship == 2) echo 'selected'; ?>>Currently Separated</option>
                    <option value ="3" <?php if ($old_profile->relationship == 3) echo 'selected'; ?>>Divorced</option>
                    <option value ="4" <?php if ($old_profile->relationship == 4) echo 'selected'; ?>>Widow / Widower</option>
                  </select>         
                </td>
                  
                
              </tr> 
              
              
              <tr>
                <td>Has Kids?</td>
                <td>
                  <select name="haskid" id="input05">
                    <option value ="1" <?php if ($old_profile->haskid == 1) echo 'selected'; ?>>No</option>
                    <option value ="2" <?php if ($old_profile->haskid == 2) echo 'selected'; ?>>Yes</option>
                  </select>
                </td>
                
               
              </tr> 
           
          

              <tr>
                <td>Want kids?</td>
                 <td>
                  <select name="wantkid" id="input06">
                    <option value ="1" <?php if ($old_profile->wantkid == 1) echo 'selected'; ?> >No</option>
                    <option value ="2" <?php if ($old_profile->wantkid == 2) echo 'selected'; ?>>Yes</option>
                  </select>
                 </td>
                
              </tr>
              
              


              <tr>
                <td>Education</td>
                <td>
                  <select name="education" id="input08">
                    <option value ="1" <?php if ($old_profile->education == 1) echo 'selected'; ?> >High School</option>
                    <option value ="2" <?php if ($old_profile->education == 2) echo 'selected'; ?>>Bachelor Degree</option>
                    <option value ="3" <?php if ($old_profile->education == 3) echo 'selected'; ?>>Master Degree</option>
                    <option value ="4" <?php if ($old_profile->education == 4) echo 'selected'; ?>>PHD and above</option>
                  </select>
                </td>
              
              </tr>
 <tr> <td colspan="2"><div class="sec-block-tt clearfix" style="padding-left: 0px;"><span>Lifestyle</span></div></td> <tr>             
              <tr>
                <td>Smoking</td>
                <td>
                  <select name="smoke" id="input09">
                    <option value ="1" <?php if ($old_profile->smoke == 1) echo 'selected'; ?>>No</option>
                    <option value ="2" <?php if ($old_profile->smoke == 2) echo 'selected'; ?>>Yes</option>
                  </select>
                </td>
                
              </tr>
              
              <tr>
                <td>Drinking</td>
                <td>
                  <select name="drink" id="input10">
                    <option value ="1" <?php if ($old_profile->drink == 1) echo 'selected'; ?>>No</option>
                    <option value ="2" <?php if ($old_profile->drink == 2) echo 'selected'; ?>>Yes</option>
                  </select>
                </td>
                            
              </tr>
              
              




        

              </tbody>
</table>   
          
          
          <div class='col-lg-offset-4'>
            <button class="btn btn-primary" type='submit'>submit</button>
          </div>
        </fieldset>      
        </form>


    



    </div>
  </div>
    </div>
  <?php
  require_once("footer.php");
  ?>


