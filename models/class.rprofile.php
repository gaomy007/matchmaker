<?php

/*
  UserCake Version: 2.0.2
  http://usercake.com
 */

class rprofile {

  public $uid = NULL;
  public $gender = NULL;
  public $agefrom = NULL;
  public $ageto = NULL;
  public $income = NULL;
  public $relationship = NULL;
  public $haskid = NULL;
  public $wantkid = NULL;
  public $heightfrom = NULL;
  public $heightto = NULL;
  public $education = NULL;
  public $smoke = NULL;
  public $drink = NULL;
  public $ethnicity = NULL;
  public $bodytype = NULL;
  public $rpid = NULL;
  public $winfo = NULL;
  public $wid = NULL;
  public $old_rpid = NULL;
  public $old_wid = NULL;
  public $displayName = NULL;
  public $mtype = NULL;
    public $score = NULL;
  
  public function test() {
    $loggedInUser = $_SESSION["userCakeUser"];
    echo $loggedInUser->hasProfile();
  }

  public function addtodb() {
    global $mysqli;
    $loggedInUser = $_SESSION["userCakeUser"];
    $this->wid = $this->addWeight();
    $stmt = $mysqli->prepare("INSERT INTO rprofile(
                             mtype,
                             score,
                              gender,
                              age_min,
                              age_max,
                              income,
                              has_kid,
                              want_kid,
                              heightfrom,
                              heightto,
                              education,
                              smoke,
                              drink,
                              wid
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
					?)");

    $stmt->bind_param("iiiiiiiiddiiii", $this->mtype,$this->score,$this->gender, $this->agefrom, $this->ageto, $this->income, $this->haskid, $this->wantkid, $this->heightfrom, $this->heightto, $this->education, $this->smoke, $this->drink, $this->wid
    );
    //echo $this->wantkid;
    $stmt->execute();
    $stmt->close();
    $rpid = $mysqli->insert_id;
    $loggedInUser->updateRpid($rpid);
    $this->rpid = $rpid;
    $this->uid = $loggedInUser->user_id;
  }

  public function addEthnicity() {
    global $mysqli;
    if (!$this->ethnicity == NULL) {
      $stmt = $mysqli->prepare("INSERT INTO rpro_eth(
                              rpid,
                              eid
                              )
					VALUES (
					?,
					?
					)");
      foreach ($this->ethnicity as $key => $value) {
        $stmt->bind_param("ii", $this->rpid, $value);
        $stmt->execute();
      }
      $stmt->close();
    }
  }

  public function addWeight() {
    global $mysqli;
    if (!$this->winfo == NULL) {
      $arr = array();
      $values = array_values($this->winfo);

      for ($i = 1; $i <= 11; $i++) {
        if (in_array($i, $values))
          $arr[] = 1;
        else
          $arr[] = 0;
      }
      $query = "INSERT INTO weight(
                              age,
                              income,
                              relationship,
                              haskid,
                              wantkid,
                              height,
                              education,
                              smoke,
                              drink,
                              ethnicity,
                              bodytype
                              )
					VALUES (
         $arr[0],
         $arr[1], 
        $arr[2], 
         $arr[3], 
         $arr[4],
        $arr[5],
         $arr[6], 
        $arr[7],
        $arr[8], 
         $arr[9], 
        $arr[10])";

      $mysqli->query($query);

      $wid = $mysqli->insert_id;
    
      // var_dump($this->winfo);
      //echo "<br><br>";
      //echo "in array:".in_array(1,$values)."<br>";
      //echo "<br><br>";
    //  var_dump($wid);

      return $wid;
    }
  }

  public function addRelationship() {
    global $mysqli;
    if (!$this->relationship == NULL) {
      $stmt = $mysqli->prepare("INSERT INTO rpro_rel(
                              rpid,
                              rid
                              )
					VALUES (
					?,
					?
					)");
      foreach ($this->relationship as $key => $value) {
        $stmt->bind_param("ii", $this->rpid, $value);
        $stmt->execute();
      }
      $stmt->close();
    }
  }

  public function addBodytype() {
    global $mysqli;
    if (!$this->bodytype == NULL) {
      $stmt = $mysqli->prepare("INSERT INTO rpro_body(
                              rpid,
                              bid
                              )
					VALUES (
					?,
					?
					)");
      foreach ($this->bodytype as $key => $value) {
        $stmt->bind_param("ii", $this->rpid, $value);
        $stmt->execute();
      }
      $stmt->close();
    }
  }

  public function ini() {
    $loggedInUser = $_SESSION["userCakeUser"];
    global $mysqli;

    $rpid = $loggedInUser->getRpid();
    $stmt = $mysqli->prepare("SELECT * FROM  rprofile
     WHERE
		 rpid = ?");
    $stmt->bind_param("i", $rpid);
    $stmt->execute();
    $stmt->bind_result(
        $this->rpid, 
        $this->gender,
        $this->agefrom, 
        $this->ageto, 
        $this->income, 
        $this->haskid, 
        $this->heightfrom, 
        $this->education,
        $this->smoke,
        $this->drink, 
        $this->heightto, 
        $this->wantkid, 
        $this->wid,
        $this->mtype,
        $this->score
    );
    $stmt->fetch();
    $stmt->close();
    $this->ethnicity = $this->getEthnicity();
    $this->bodytype = $this->getBodytype();
    $this->relationship = $this->getRelationship();
    $this->winfo = $this->getWinfo();
    $this->uid=$loggedInUser->user_id;
    $this->displayName=$this->getDisplayNameByRpid($this->rpid);
  }
  

  public function iniByUid($uid) {
    global $mysqli;

    $rpid = $this->getRpidByUid($uid);
    $stmt = $mysqli->prepare("SELECT * FROM  rprofile
     WHERE
		 rpid = ?");
    $stmt->bind_param("i", $rpid);
    $stmt->execute();
    $stmt->bind_result(
        $this->rpid, 
        $this->gender,
        $this->agefrom, 
        $this->ageto, 
        $this->income, 
        $this->haskid, 
        $this->heightfrom, 
        $this->education,
        $this->smoke,
        $this->drink, 
        $this->heightto, 
        $this->wantkid, 
        $this->wid,
        $this->mtype,
        $this->score
    );
    $stmt->fetch();
    $stmt->close();
    $this->ethnicity = $this->getEthnicity();
    $this->bodytype = $this->getBodytype();
    $this->relationship = $this->getRelationship();
    $this->winfo = $this->getWinfo();
    $this->uid=$uid;
    $this->displayName=$this->getDisplayNameByRpid($this->rpid);
  }
  
    //Check profiles
  public function getRpidByUid($uid) {
    global $mysqli, $db_table_prefix;
    $rpid=Null;
    $stmt = $mysqli->prepare("SELECT rpid FROM " . $db_table_prefix . "users
     WHERE
		 id = ?");
    $stmt->bind_param("i",$uid );
    $stmt->execute();
    $stmt->bind_result($rpid);
    $stmt->fetch();
    $stmt->close();
    $this->rpid=$rpid;
    return $rpid;
  }
  
    public function getDisplayNameByRpid($rpid){
    $displayName=Null;
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT display_name FROM uc_users
     WHERE
		 rpid = ?");
    $stmt->bind_param("i", $rpid);
    $stmt->execute();
    $stmt->bind_result($displayName);
    $stmt->fetch();
    $stmt->close();
    return $displayName;
    
  }
  public function getEthnicity() {
    $rpid = $this->rpid;
    global $mysqli;
    $i = 0;
    $arr = array();

    $stmt = $mysqli->prepare("SELECT eid FROM rpro_eth
					where
          rpid=?");
    $stmt->bind_param("i", $rpid);
    $stmt->execute();
    $stmt->bind_result($id);
    while ($stmt->fetch()) {
      $arr[] = $id;
      $i++;
    }

    $stmt->close();
    if ($i == 0)
      return NULL;
    else
      return $arr;
  }

  public function getWinfo() {
    $wid = $this->wid;
    global $mysqli;


    $query = "SELECT * FROM weight WHERE wid=$wid";

    $result = $mysqli->query($query);

    /* fetch associative array */
    if ($result)
      $arr = $result->fetch_assoc();
    else
      return NULL;

    /* free result set */
    $result->free();


    /* close connection */
    //$mysqli->close();

    if ($arr == NULL)
      return NULL;
    else
      return $arr;
  }

  public function getWid($rpid) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT wid FROM rprofile
					where
          rpid=?");
    $stmt->bind_param("i", $rpid);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();
    if ($id == NULL)
      return NULL;
    else
      return $id;
  }

  public function delWid($wid) {
    global $mysqli;

    $stmt = $mysqli->prepare("Delete FROM weight
					where
          wid=?");
    $stmt->bind_param("i", $wid);
    $stmt->execute();
    $stmt->close();
  }

  public function getRelationship() {
    $rpid = $this->rpid;
    global $mysqli;
    $i = 0;
    $arr = array();

    $stmt = $mysqli->prepare("SELECT rid FROM rpro_rel
					where
          rpid=?");
    $stmt->bind_param("i", $rpid);
    $stmt->execute();
    $stmt->bind_result($id);
    while ($stmt->fetch()) {
      $arr[] = $id;
      $i++;
    }

    $stmt->close();
    if ($i == 0)
      return NULL;
    else
      return $arr;
  }

  public function getBodytype() {
    $rpid = $this->rpid;
    global $mysqli;
    $i = 0;
    $arr = array();

    $stmt = $mysqli->prepare("SELECT bid FROM rpro_body
					where
          rpid=?");
    $stmt->bind_param("i", $rpid);
    $stmt->execute();
    $stmt->bind_result($id);
    while ($stmt->fetch()) {
      $arr[] = $id;
      $i++;
    }

    $stmt->close();
    if ($i == 0)
      return NULL;
    else
      return $arr;
  }

  public function clearTables() {
    $loggedInUser = $_SESSION["userCakeUser"];

    $this->old_rpid = $loggedInUser->getRpid();
    $this->old_wid = $this->getWid($this->old_rpid);

    //clear old table
    $oldRpid = $this->old_rpid;
    if (!$oldRpid == NULL) {
      $this->clearMultiItem($oldRpid, "rpro_eth");
      $this->clearMultiItem($oldRpid, "rpro_body");
      $this->clearMultiItem($oldRpid, "rpro_rel");
      $this->delWid($this->old_wid);
      $loggedInUser->delOldRprofile($oldRpid);
    }
  }

  public function clearMultiItem($oldRpid, $tableName) {
    $loggedInUser = $_SESSION["userCakeUser"];
    global $mysqli;
    $stmt = $mysqli->prepare("DELETE FROM $tableName
			WHERE
			rpid = ?");
    $stmt->bind_param("i", $oldRpid);
    $stmt->execute();
    $stmt->close();
  }

  public function clearWeight($wid) {
    global $mysqli;
    $stmt = $mysqli->prepare("DELETE FROM weight
			WHERE
			wid = ?");
    $stmt->bind_param("i", $wid);
    $stmt->execute();
    $stmt->close();
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

}

?>
