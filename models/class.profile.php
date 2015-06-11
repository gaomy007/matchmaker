<?php

/*
  UserCake Version: 2.0.2
  http://usercake.com
 */

class profile {

  public $uid = NULL;
  public $gender = NULL;
  public $age = NULL;
  public $income = NULL;
  public $relationship = NULL;
  public $haskid = NULL;
  public $wantkid = NULL;
  public $height = NULL;
  public $education = NULL;
  public $smoke = NULL;
  public $drink = NULL;
  public $ethnicity = NULL;
  public $bodytype = NULL;
  public $pid = NULL;
  public $displayName = NULL;
  public $address = NULL;
  public $photo = NULL;
  public $old_photo = NULL;
  public $useOldPhoto=NULL;
  public $latitude=NULL;
  public $longitude=NULL;
  public $city=NULL;
  public $email=NULL;
  
  public function test() {
    $loggedInUser = $_SESSION["userCakeUser"];
    echo $loggedInUser->hasProfile();
  }

  public function addtodb() {
    global $mysqli;
    if(!$this->useOldPhoto){
         $this->delOldPhoto();
    }
     $this->setXY();
     if($this->latitude==NULL||$this->longitude==NULL)
     {
       $this->latitude=0;
       $this->longitude=0;
     }
    $loggedInUser = $_SESSION["userCakeUser"];
    $stmt = $mysqli->prepare("INSERT INTO profile(
                              gender,
                              age,
                              income,
                              relationship,
                              has_kid,
                              height,
                              education,
                              smoke,
                              drink,
                              want_kid,
                              ethnicity,
                              bodytype,
                              address,
                              photo,
                              latitude,
                              longitude,
                              city
                              )
					VALUES (
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
          ?,
          ?,
          ?,
          ?,
          ?,
          ?,
          ?,
          ?,
          ?
					)");

    $stmt->bind_param("iiiiidiiiiiissdds", $this->gender, $this->age, $this->income, $this->relationship, $this->haskid, $this->height, $this->education, $this->smoke, $this->drink, $this->wantkid, $this->ethnicity, $this->bodytype, $this->address, $this->photo,  $this->latitude,  $this->longitude,  $this->city);
    $stmt->execute();
    $stmt->close();
    $pid = $mysqli->insert_id;
   // echo "<br>insert pid:".$this->height."<br>";
    $loggedInUser->updatePid($pid);
  }
  public function setXY(){
    
    
    if($this->address==NULL)
    {
     $this->latitude=NULL;
     $this->longitude=NULL;
     return ;
    }
    
    $array = lookup($this->address);
    if($array!=NULL) {
    
    //var_dump($array["longitude"]);
     $this->longitude=$array['latitude'];
     $this->latitude=$array['longitude'];
     $this->city=$array['city'];
    }
    
  }

  public function delOldPhoto() {
    global $mysqli;
    $photo=NULL;
    $loggedInUser = $_SESSION["userCakeUser"];
    $pid = $this->getPidByUid($loggedInUser->user_id);
    $stmt = $mysqli->prepare("SELECT photo FROM profile
			WHERE
			pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result($photo);
    $stmt->fetch();
    $stmt->close();

    if (!$photo == NULL) {

      if (unlink($photo)) {
        return true;
      }
      else {
        return false;
      }
    }
  }
  
    public function getOldPhoto() {
    global $mysqli;
    $photo=NULL;
    $loggedInUser = $_SESSION["userCakeUser"];
    $pid = $this->getPidByUid($loggedInUser->user_id);
    $stmt = $mysqli->prepare("SELECT photo FROM profile
			WHERE
			pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result($photo);
    $stmt->fetch();
    $stmt->close();

    if (!$photo == NULL) {

     return $photo;
    }
    else {
     return NULL;  
    }
  }
  
  

  public function hasProfile() {
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare("SELECT pid FROM " . $db_table_prefix . "users
			WHERE
			id = ?");
    $stmt->bind_param("i", $this->user_id);
    $stmt->execute();
    $stmt->bind_result($pid);
    $stmt->fetch();
    $stmt->close();
    if ($pid == null)
      return null;
    else
      return true;
  }
  
  public function showPhoto(){
    
    echo getImageXY($this->photo);
    
  }

  //Check profiles
  public function getPid() {
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare("SELECT pid FROM " . $db_table_prefix . "users
     WHERE
		 id = ?");
    $stmt->bind_param("i", $this->user_id);
    $stmt->execute();
    $stmt->bind_result($pid);
    $stmt->fetch();
    $stmt->close();
    return $pid;
  }

  //Check profiles
  public function getUidByPid($pid) {
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare("SELECT id FROM " . $db_table_prefix . "users
     WHERE
		 pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result($uid);
    $stmt->fetch();
    $stmt->close();
    return $uid;
  }

  //create all the profile information of current user
  public function ini() {
    $loggedInUser = $_SESSION["userCakeUser"];
    global $mysqli;

    $pid = $loggedInUser->getPid();
    $stmt = $mysqli->prepare("SELECT * FROM  profile
     WHERE
		 pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result(
        $this->pid, $this->gender, $this->age, $this->income, $this->relationship, $this->haskid, $this->height, $this->education, $this->smoke, $this->drink, $this->wantkid, $this->ethnicity, $this->bodytype, $this->address, $this->photo,  $this->latitude,  $this->longitude,  $this->city
    );
    $stmt->fetch();
    $stmt->close();
    $this->uid = $loggedInUser->user_id;
    $this->email=$loggedInUser->email;
    $this->displayName = $this->getDisplayNameByPid($this->pid);
  }

  public function getDisplayNameByPid($pid) {

    global $mysqli;
    $displayName = NULL;
    $stmt = $mysqli->prepare("SELECT display_name FROM uc_users
     WHERE
		 pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result($displayName);
    $stmt->fetch();
    $stmt->close();
    return $displayName;
  }
  
    public function getEmailByPid($pid) {

    global $mysqli;
    $email = NULL;
    $stmt = $mysqli->prepare("SELECT email FROM uc_users
     WHERE
		 pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();
    return $email;
  }

  public function iniByUid($uid) {
    global $mysqli;

    $pid = $this->getPidByUid($uid);
    $stmt = $mysqli->prepare("SELECT * FROM  profile
     WHERE
		 pid = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result(
        $this->pid, $this->gender, $this->age, $this->income, $this->relationship, $this->haskid, $this->height, $this->education, $this->smoke, $this->drink, $this->wantkid, $this->ethnicity, $this->bodytype, $this->address, $this->photo,$this->latitude,  $this->longitude,  $this->city
    );
    $stmt->fetch();
    $stmt->close();
    $this->uid = $uid;
    $this->displayName = $this->getDisplayNameByPid($this->pid);
    $this->email = $this->getEmailByPid($this->pid);
  }

  public function getPidByUid($uid) {
    global $mysqli;
      $pid=NULL;

    $stmt = $mysqli->prepare("SELECT pid FROM  uc_users
     WHERE
		 id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->bind_result(
        $pid
    );
    $stmt->fetch();
    $stmt->close();
    $this->pid = $pid;
    return $this->pid;
  }
  
  public function getRelationshipName($i){
    $a=array("tell you later","Never Married","Currently Separated","Divorced","Widow / Widower");
    
    if($i==NULL) return $a[0];
    else
    return $a[$i];
    
  }

    public function getYesNo($i){
    $a=array("tell you later","No","Yes");
    
    if($i==NULL) return $a[0];
    else
    return $a[$i];
    
  }
  
    public function getEduName($i){
    $a=array("tell you later","High School","Bachelor Degree","Master Degree","PHD and above");
    
    if($i==NULL) return $a[0];
    else
    return $a[$i];
    
  }
   public function getEthName($i){
    $a=array("tell you later","Asian","Latino / Hispanic","Black / African descent","White / Caucasian","Indian","Native American");
    
    if($i==NULL) return $a[0];
    else
    return $a[$i];
    
  }
     public function getBodyType($i){
    $a=array("tell you later","Slender","About average","Athletic and toned","A few extra pounds","Stocky");
    
    if($i==NULL) return $a[0];
    else
    return $a[$i];
    
  }
  
  public function getHeight($h){
    if($h==NULL) return "tell you later";
    if(floor($h)==$h)
    { $x=explode(".",$h);
      
      return "$x[0] feet 0 inch";
    
    }
    
    $x=explode(".",$h);
    
   return "$x[0] feet $x[1] inch"; 

  }
 public function getGender($i){
   
    if($i==1) return "Male";
    else
      return "Female";

  }
  
  
}

?>
