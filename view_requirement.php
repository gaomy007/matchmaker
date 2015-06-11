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
  
if($mtype==1)$score=1;
if($mtype==2)$score=80;
if($mtype==3){$mtype=2;$score=50;} 
  
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



  

  <div class="container"  id="low-container">
    <div class="row">
      <div class="col-sm-2  sidebar">
        <?php include("left-nav.php"); ?>
      </div>

      <!--right side-->
      <div class="col-sm-10">
        <div class="tab-sec" id="scan-acc-set" style="display:block">
        <form class="form-horizontal"  action='<?php echo $_SERVER['PHP_SELF']; ?>' enctype="multipart/form-data" method='post'>
         <fieldset class="scheduler-border">
       <legend class="scheduler-border">Your Search Requirement</legend>
          
              
               <table class="table borderless" >
 
              <tbody>
                <tr> <td colspan="3"></td> </tr>
               <tr> <td colspan="3"><div class="sec-block-tt clearfix" style="padding-left: 0px;"><span>Match Method</span></div></td> </tr>
                <tr>
                  
                  <td><div class="row">
                    <div class="col-sm-5" style="padding-top: 10px;padding-right: 0px;width: 125px;">Matching Method</div> 
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
   data-content="<b>Show results with all important fields satisfied</b>: &nbsp;Only the user who satisfying all your important fields will present to you on the match result, or are eligble the view your information.<br><br>
   <b>Show results with excellent match score</b>: <br>&nbsp;Only the user who have excellent match score will present to you on the match result. The score is incremental by each satisfied field, and important field gives more scores.<br><br>  
   <b>Show results with good match score</b>: <br>&nbsp;Only the user who have excellent match score will present to you on the match result.
   " 
   
   data-original-title="<h1>Show matching result:</h1>"></a>
                    </div>
                  </div></td>
                  
                  <td style="vertical-align: middle;width: 255.33333337306976px;" >  
               <select name="mtype" id="input014"  onchange="disable(this.selectedIndex);" class="span4" style="width: 250.33333337306976px;">
                    <option value="1"<?php if ($old_rprofile->score == 1) echo 'selected'; ?>  >all important fields satisfied</option>
                    <option value="2" <?php if ($old_rprofile->score == 80) echo 'selected'; ?>> excellent match result</option>
                    <option value="3" <?php if ($old_rprofile->score == 50) echo 'selected'; ?>>good match result</option>
               </select> 
                  </td>
                  
              
          </tr>
             
         <tr> <td colspan="3"><div class="sec-block-tt clearfix" style="padding-left: 0px;"><span>Basic Requirement</span><div style="float:right">select the checkbox to make the field important</div></div></td> </tr>
              <tr>

                  <td>Gender</td>
                  <td >          
                   
                   <select name="rgender" id="input01">
                    <option value="1"<?php if ($old_rprofile->gender == 1) echo 'selected'; ?> >Male</option>
                    <option value="2" <?php if ($old_rprofile->gender == 2) echo 'selected'; ?>>Female</option>
                  </select> 
                      
                      
                  </td>
                  <td>&nbsp;</td>
                </tr>
                
                <tr>
              
                  <td>Age Range</td>
                  <td>
                    
                      
                      <input type="text" name='agefrom' class="input-xlarge" id="input02" placehoder='from'
                         value="<?php if (!$old_rprofile->agefrom == NULL) echo $old_rprofile->agefrom; ?>">
                      <br>
                      to
                      <br>
                     
                     <input type="text" name='ageto' class="input-xlarge" id="input02" placehoder='to'
                         value="<?php if (!$old_rprofile->ageto == NULL) echo $old_rprofile->ageto; ?>">
                     
                  
                  </td>
                  <td >
                     <input type="checkbox" name="winfo[]" value="1" 
                         <?php if ($old_rprofile->winfo['age']) echo 'checked'; ?>>
                  </td>
                </tr>
                
                 <tr>
 
                  <td style="width: 200px;">Height Range(e.g 5.11 for 5 feet 11 inches):</td>
                  <td> 
                    <input type="text" name='heightfrom' class="input-xlarge" id="input02" placeholder='from'
                         value="<?php if (!$old_rprofile->heightfrom == NULL) echo $old_rprofile->heightfrom; ?>"  
                         >
                      <br>
                      to
                      <br>
                  <input type="text" name='heightto' class="input-xlarge" id="input02" placeholder='to'
                         value="<?php if (!$old_rprofile->heightto == NULL) echo $old_rprofile->heightto; ?>" 
                         >
                  </td>
                  <td><input type="checkbox" name="winfo[]" value="6" 
                       <?php if ($old_rprofile->winfo['height']) echo 'checked'; ?>>
                  </td>
                </tr>  
                
               <tr>
                  <td>Ethnicity</td>
                  <td>   <div class="controls">

                  <input type="checkbox" name="rethnicity[]" value="1" <?php if (!$old_rprofile->ethnicity == NULL && in_array(1, $old_rprofile->ethnicity)) echo 'checked'; ?>>Asian<br>
                  <input type="checkbox" name="rethnicity[]" value="2" <?php if (!$old_rprofile->ethnicity == NULL && in_array(2, $old_rprofile->ethnicity)) echo 'checked'; ?>>Latino / Hispanic <br>
                  <input type="checkbox" name="rethnicity[]" value="3" <?php if (!$old_rprofile->ethnicity == NULL && in_array(3, $old_rprofile->ethnicity)) echo 'checked'; ?>>Black / African descent<br>
                  <input type="checkbox" name="rethnicity[]" value="4" <?php if (!$old_rprofile->ethnicity == NULL && in_array(4, $old_rprofile->ethnicity)) echo 'checked'; ?>>White / Caucasian<br>
                  <input type="checkbox" name="rethnicity[]" value="5" <?php if (!$old_rprofile->ethnicity == NULL && in_array(5, $old_rprofile->ethnicity)) echo 'checked'; ?>>Indian<br>
                  <input type="checkbox" name="rethnicity[]" value="6" <?php if (!$old_rprofile->ethnicity == NULL && in_array(6, $old_rprofile->ethnicity)) echo 'checked'; ?>>Native American<br>

                </div>
                  </td>
                  <td><input type="checkbox" name="winfo[]" value="10" 
                       <?php if ($old_rprofile->winfo['ethnicity']) echo 'checked'; ?>>
                  </td>
                </tr>
                
                 <tr>
                  <td>Body Type</td>
                  <td>
                  <div class="controls">

                  <input type="checkbox" name="rbodytype[]" value="1" <?php if (!$old_rprofile->bodytype == NULL && in_array(1, $old_rprofile->bodytype)) echo 'checked'; ?>>Slender<br>
                  <input type="checkbox" name="rbodytype[]" value="2" <?php if (!$old_rprofile->bodytype == NULL && in_array(2, $old_rprofile->bodytype)) echo 'checked'; ?>>About average<br>
                  <input type="checkbox" name="rbodytype[]" value="3" <?php if (!$old_rprofile->bodytype == NULL && in_array(3, $old_rprofile->bodytype)) echo 'checked'; ?>>Athletic and toned<br>
                  <input type="checkbox" name="rbodytype[]" value="4" <?php if (!$old_rprofile->bodytype == NULL && in_array(4, $old_rprofile->bodytype)) echo 'checked'; ?>>A few extra pounds<br>
                  <input type="checkbox" name="rbodytype[]" value="5" <?php if (!$old_rprofile->bodytype == NULL && in_array(5, $old_rprofile->bodytype)) echo 'checked'; ?>>Stocky<br>

                  </div>
                  </td>
                  <td><input type="checkbox" name="winfo[]" value="11" 
                       <?php if ($old_rprofile->winfo['bodytype']) echo 'checked'; ?>>
                  </td>
                </tr>
               <tr> <td colspan="3"><div class="sec-block-tt clearfix" style="padding-left: 0px;"><span>Background Requirement</span></div></td> </tr>
              <tr>
              
                  <td>Income</td>
                  <td>                  <input type="text" name='rincome' class="input-xlarge" id="input03"
                         value="<?php if (!$old_rprofile->income == NULL) echo $old_rprofile->income; ?>"
                         >
                  </td>
                  <td>                <input type="checkbox" name="winfo[]" value="2" 
                       <?php if ($old_rprofile->winfo['income']) echo 'checked'; ?>>
               </td>
                </tr>
               
                <tr>
          
                  <td>Current Relationship</td>
                  <td>               
                    
                    <div >

                  <input type="checkbox" name="rrelationship[]" value="1" <?php if (!$old_rprofile->relationship == NULL && in_array(1, $old_rprofile->relationship)) echo 'checked'; ?>>Never Married<br>
                  <input type="checkbox" name="rrelationship[]" value="2" <?php if (!$old_rprofile->relationship == NULL && in_array(2, $old_rprofile->relationship)) echo 'checked'; ?>>Currently Separated<br>
                  <input type="checkbox" name="rrelationship[]" value="3" <?php if (!$old_rprofile->relationship == NULL && in_array(3, $old_rprofile->relationship)) echo 'checked'; ?>>Divorced<br>
                  <input type="checkbox" name="rrelationship[]" value="4" <?php if (!$old_rprofile->relationship == NULL && in_array(4, $old_rprofile->relationship)) echo 'checked'; ?>>Widow / Widower<br>

                </div></td>
                  <td><input type="checkbox" name="winfo[]" value="3" 
                       <?php if ($old_rprofile->winfo['relationship']) echo 'checked'; ?>></td>
                </tr>
                     
                
                
                
                <tr>
         
                  <td>Has kids?</td>
                  <td>              
                    
                 <div>
                  <select name="rhaskid" id="input05">
                    <option value ="1" <?php if ($old_rprofile->haskid == 1) echo 'selected'; ?>>No</option>
                    <option value ="2"<?php if ($old_rprofile->haskid == 2) echo 'selected'; ?>>Yes</option>
                  </select>
                </div>
                  </td>
                  <td><input type="checkbox" name="winfo[]" value="4" 
                       <?php if ($old_rprofile->winfo['haskid']) echo 'checked'; ?>>
                  </td>
                </tr>
                            
                
                <tr>

                  <td>Want kids?</td>
                  <td>              <select name="rwantkid" id="input06">
                    <option value ="1" <?php if ($old_rprofile->wantkid == 1) echo 'selected'; ?>>No</option>
                    <option value ="2" <?php if ($old_rprofile->wantkid == 2) echo 'selected'; ?>>Yes</option>
                  </select>
                  </td>
                  <td>                <input type="checkbox" name="winfo[]" value="5" 
                       <?php if ($old_rprofile->winfo['wantkid']) echo 'checked'; ?>>
                  </td>
                </tr>
                           
                

                  
                
                <tr>
               
                  <td>Education</td>
                  <td>  
                    
                   <select name="reducation" id="input08">
                    <option value ="1" <?php if ($old_rprofile->education == 1) echo 'selected'; ?>>High School or above</option>
                    <option value ="2" <?php if ($old_rprofile->education == 2) echo 'selected'; ?>>Bachelor Degree or above</option>
                    <option value ="3" <?php if ($old_rprofile->education == 3) echo 'selected'; ?>>Master Degree or above</option>
                    <option value ="4" <?php if ($old_rprofile->education == 4) echo 'selected'; ?>>PHD and above</option>
                  </select>
                  </td>
                  <td><input type="checkbox" name="winfo[]" value="7" 
                       <?php if ($old_rprofile->winfo['education']) echo 'checked'; ?>>
                  </td>
                </tr>
                        
                  <tr> <td colspan="3"><div class="sec-block-tt clearfix" style="padding-left: 0px;"><span>Habit</span></div></td> </tr>
                <tr>
           
                  <td>Can he/she smoke?</td>
                  <td>
                    <select name="rsmoke" id="input09">
                    <option value ="1"  <?php if ($old_rprofile->smoke == 1) echo 'selected'; ?>>No</option>
                    <option value ="2" <?php if ($old_rprofile->smoke == 2) echo 'selected'; ?>>Yes</option>
                  </select>
                  </td>
                  <td><input type="checkbox" name="winfo[]" value="8" 
                       <?php if ($old_rprofile->winfo['smoke']) echo 'checked'; ?>>
                  </td>
                </tr>
                            
                <tr>
             
                  <td>Can he/she drink regularly?</td>
                  <td><select name="rdrink" id="input10">
                    <option value ="1" <?php if ($old_rprofile->drink == 1) echo 'selected'; ?> >No</option>
                    <option value ="2" <?php if ($old_rprofile->drink == 2) echo 'selected'; ?>>Yes</option>
                  </select>
                  </td>
                  <td><input type="checkbox" name="winfo[]" value="9" 
                       <?php if ($old_rprofile->winfo['drink']) echo 'checked'; ?>>
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
  </div>
  <?php
  require_once("footer.php");
  ?>


