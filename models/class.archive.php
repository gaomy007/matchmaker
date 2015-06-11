<?php

class archive {

  public $profile = NULL;
  public $rprofile = NULL;
  public $winfo = NULL;
  public $city = NULL;
  public $score = 0;
  public $rscore = 0;
  

  public function iniByUid($uid) {
    $this->profile = new profile();
    $this->rprofile = new rprofile();
    $this->profile->iniByUid($uid);
    $this->rprofile->iniByUid($uid);
    $this->winfo = $this->rprofile->winfo;
    $this->city = $this->profile->city;
  }

  public function ini() {

    $this->profile = new profile();
    $this->rprofile = new rprofile();
    $this->profile->ini();
    $this->rprofile->ini();
    $this->winfo = $this->rprofile->winfo;
    $this->city = $this->profile->city;
  }

  public function showInMatch() {
    ?>



    <div class="col-lg-12" style='margin-bottom: 20px;border-bottom: groove;padding-bottom: 15px;'>

   

      
        <div class='col-lg-4' style="padding-left: 0px;">
        
            <a href="visit.php?uid=<?php echo $this->profile->uid; ?>" ><img  src="<?php echo $this->profile->photo; ?>" width="150" height="150" ></a> 
          </div>

         <div class='col-lg-7'>

            <div class='col-lg-12'>

              <div class="details-unit">
            <h2 class="username"style="margin-bottom: 0px;">
              <a href="visit.php?uid=<?php echo $this->profile->uid; ?>" ><?php echo $this->profile->displayName; ?> </a>
            </h2>
            <span class="mini-note"><?php echo $this->profile->age; ?>, <?php echo $this->profile->city; ?></span>
          </div>
           </div>


              <div class='col-lg-12'>
                  <small>
                    <br> Score: <b><?php echo $this->score; ?>%</b> </small>
              </div> 

              <div class='col-lg-12'>
                  <small>Reverse Score: <b><?php echo $this->rscore; ?>%</b></small>
              </div>

              <div class='col-lg-12'>
                  <small>Current Status: <?php echo $this->profile->getRelationshipName($this->profile->relationship); ?></small>
              </div>
              <div class='col-lg-12'>
                  <small>Ethnicity: <?php echo $this->profile->getEthName($this->profile->ethnicity); ?></small>
              </div>

            </div>

  


      
      </div>          


 




    <?php
  }

  
  
  public function showInVisit($side,$rscore=0) {

    $profile = $this->profile;
    ?>



<table class='table-striped table-bordered'> 

  <tr>
    <td colspan="2" >
    <img id="result-thumb"  src="<?php echo $profile->photo; ?>" width="200" height="200" style='margin-left: 0px;' >
    </td>
  </tr>
  
    <tr>
    <td colspan="2">
    <h4><?php echo $profile->displayName; ?> </h4>
   </td>
    </tr>



   <tr>
    <td>
    <?php echo "City: </td><td>" . $profile->city; ?>
   </td>
    </tr>
    
    <!-- price and availability -->
    <tr ><td>Gender:</td> <td><?php echo $profile->getGender($profile->gender); ?></td> </tr>
    <tr ><td>age: </td> <td><?php echo $profile->age; ?> </td> </tr>
    <tr ><td>income around: </td> <td><?php echo $profile->income; ?> </td> </tr>
    <tr ><td>relationship: </td> <td><?php echo $profile->getRelationshipName($profile->relationship); ?> </td> </tr>
    <tr ><td>Height: </td> <td><?php echo $profile->getHeight($profile->height) ?> </td> </tr>
    <tr ><td>Education: </td> <td><?php echo $profile->getEduName($profile->education); ?> </td> </tr>
    <tr ><td>If smoke? </td> <td><?php echo $profile->getYesNo($profile->smoke); ?> </td> </tr>
    <tr ><td>If drink? </td> <td><?php echo $profile->getYesNo($profile->drink); ?> </td> </tr>
    <tr ><td>Ethnicity: </td> <td><?php echo $profile->getEthName($profile->ethnicity); ?> </td> </tr>
    <tr ><td>Body Type: </td> <td><?php echo $profile->getBodyType($profile->bodytype); ?> </td> </tr>
    <?php
    if($side=="right")
      echo "<tr ><td>Reverse Score:</td><td><b>".round ($rscore)."%</b></d> </tr>";
     if($side=="left")
      echo "<tr ><td>Score:</td><td><b>".round ($rscore)."%</b></d> </tr>";
    
    ?>




</table>


    <?php
  }

}
?>
