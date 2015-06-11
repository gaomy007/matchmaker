<?php

/*
  UserCake Version: 2.0.2
  http://usercake.com
 */

class loggedInUser {

  public $email = NULL;
  public $hash_pw = NULL;
  public $user_id = NULL;
  public $profile = NULL;
  public $rprofile = NULL;

  //Simple function to update the last sign in of a user
  public function updateLastSignIn() {
    global $mysqli, $db_table_prefix;
    $time = time();
    $stmt = $mysqli->prepare("UPDATE " . $db_table_prefix . "users
			SET
			last_sign_in_stamp = ?
			WHERE
			id = ?");
    $stmt->bind_param("ii", $time, $this->user_id);
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

  //Return the timestamp when the user registered
  public function signupTimeStamp() {
    global $mysqli, $db_table_prefix;

    $stmt = $mysqli->prepare("SELECT sign_up_stamp
			FROM " . $db_table_prefix . "users
			WHERE id = ?");
    $stmt->bind_param("i", $this->user_id);
    $stmt->execute();
    $stmt->bind_result($timestamp);
    $stmt->fetch();
    $stmt->close();
    return ($timestamp);
  }

  //Update a users password
  public function updatePassword($pass) {
    global $mysqli, $db_table_prefix;
    $secure_pass = generateHash($pass);
    $this->hash_pw = $secure_pass;
    $stmt = $mysqli->prepare("UPDATE " . $db_table_prefix . "users
			SET
			password = ? 
			WHERE
			id = ?");
    $stmt->bind_param("si", $secure_pass, $this->user_id);
    $stmt->execute();
    $stmt->close();
  }

  //Update a users email
  public function updateEmail($email) {
    global $mysqli, $db_table_prefix;
    $this->email = $email;
    $stmt = $mysqli->prepare("UPDATE " . $db_table_prefix . "users
			SET 
			email = ?
			WHERE
			id = ?");
    $stmt->bind_param("si", $email, $this->user_id);
    $stmt->execute();
    $stmt->close();
  }

  public function updatePid($pid) {
    global $mysqli, $db_table_prefix;
    $this->profile = $pid;
    $this->delOldProfile($this->getPid());
    $stmt = $mysqli->prepare("UPDATE " . $db_table_prefix . "users
			SET 
			pid = ?
			WHERE
			id = ?");
    $stmt->bind_param("ii", $pid, $this->user_id);
    $stmt->execute();
    $stmt->close();
  }

  public function updateRpid($rpid) {
    global $mysqli, $db_table_prefix;
    $this->rprofile = $rpid;
    $stmt = $mysqli->prepare("UPDATE " . $db_table_prefix . "users
			SET 
			rpid = ?
			WHERE
			id = ?");
    $stmt->bind_param("ii", $rpid, $this->user_id);
    $stmt->execute();
    $stmt->close();
  }

  public function delOldProfile($pid) {
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare("DELETE FROM profile
			WHERE
			pid = ?");
    $stmt->bind_param("i",$pid);
    $stmt->execute();
    $stmt->close();
  }
  
    public function delOldRprofile($rpid) {
    global $mysqli;
    $stmt = $mysqli->prepare("DELETE FROM rprofile
			WHERE
			rpid = ?");
    $stmt->bind_param("i",$rpid);
    $stmt->execute();
    $stmt->close();
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
  
    public function getRpid() {
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare("SELECT rpid FROM " . $db_table_prefix . "users
     WHERE
		 id = ?");
    $stmt->bind_param("i", $this->user_id);
    $stmt->execute();
    $stmt->bind_result($rpid);
    $stmt->fetch();
    $stmt->close();
    return $rpid;
  }

  //Is a user has a permission
  public function checkPermission($permission) {
    global $mysqli, $db_table_prefix, $master_account;

    //Grant access if master user

    $stmt = $mysqli->prepare("SELECT id 
			FROM " . $db_table_prefix . "user_permission_matches
			WHERE user_id = ?
			AND permission_id = ?
			LIMIT 1
			");
    $access = 0;
    foreach ($permission as $check) {
      if ($access == 0) {
        $stmt->bind_param("ii", $this->user_id, $check);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
          $access = 1;
        }
      }
    }
    if ($access == 1) {
      return true;
    }
    if ($this->user_id == $master_account) {
      return true;
    }
    else {
      return false;
    }
    $stmt->close();
  }

  //Logout
  public function userLogOut() {
    destroySession("userCakeUser");
  }

}

?>