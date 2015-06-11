<?php
/*
  UserCake Version: 2.0.2
  http://usercake.com
 */

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

//Prevent the user visiting the logged in page if he is not logged in
if (!isUserLoggedIn()) {
  header("Location: login.php");
  die();
}

if (!empty($_POST)) {
  $errors = array();
  $successes = array();
  $password = $_POST["password"];
  $password_new = $_POST["passwordc"];
  $password_confirm = $_POST["passwordcheck"];

  $errors = array();
  $email = $_POST["email"];

  //Perform some validation
  //Feel free to edit / change as required
  //Confirm the hashes match before updating a users password
  $entered_pass = generateHash($password, $loggedInUser->hash_pw);

  if (trim($password) == "") {
    $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
  }
  else if ($entered_pass != $loggedInUser->hash_pw) {
    //No match
    $errors[] = lang("ACCOUNT_PASSWORD_INVALID");
  }
  if ($email != $loggedInUser->email) {
    if (trim($email) == "") {
      $errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
    }
    else if (!isValidEmail($email)) {
      $errors[] = lang("ACCOUNT_INVALID_EMAIL");
    }
    else if (emailExists($email)) {
      $errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
    }

    //End data validation
    if (count($errors) == 0) {
      $loggedInUser->updateEmail($email);
      $successes[] = lang("ACCOUNT_EMAIL_UPDATED");
    }
  }

  if ($password_new != "" OR $password_confirm != "") {
    if (trim($password_new) == "") {
      $errors[] = lang("ACCOUNT_SPECIFY_NEW_PASSWORD");
    }
    else if (trim($password_confirm) == "") {
      $errors[] = lang("ACCOUNT_SPECIFY_CONFIRM_PASSWORD");
    }
    else if (minMaxRange(8, 50, $password_new)) {
      $errors[] = lang("ACCOUNT_NEW_PASSWORD_LENGTH", array(8, 50));
    }
    else if ($password_new != $password_confirm) {
      $errors[] = lang("ACCOUNT_PASS_MISMATCH");
    }

    //End data validation
    if (count($errors) == 0) {
      //Also prevent updating if someone attempts to update with the same password
      $entered_pass_new = generateHash($password_new, $loggedInUser->hash_pw);

      if ($entered_pass_new == $loggedInUser->hash_pw) {
        //Don't update, this fool is trying to update with the same password Â¬Â¬
        $errors[] = lang("ACCOUNT_PASSWORD_NOTHING_TO_UPDATE");
      }
      else {
        //This function will create the new hash and update the hash_pw property.
        $loggedInUser->updatePassword($password_new);
        $successes[] = lang("ACCOUNT_PASSWORD_UPDATED");
      }
    }
  }
  if (count($errors) == 0 AND count($successes) == 0) {
    $errors[] = lang("NOTHING_TO_UPDATE");
  }
}
require_once("models/header.php");
?>

<body>




  <div class="container" id="low-container">
    <div class="row">
      <div class="col-sm-2  sidebar">
<?php include("left-nav.php"); ?>
      </div>
      <div class="col-sm-6 col-sm-offset-2">



<br><br>
        <form name='updateAccount' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'  class="form-horizontal" role="form">
      <fieldset class="scheduler-border">
       <legend class="scheduler-border">Modify Account Information</legend>
          
          <div class="form-group">
            <label  class="col-sm-4 control-label">Current Password:</label>
            <div class="col-sm-5">
          <input type="password" name="password" class="form-control" placeholder="" required="">
   </div>
  </div>
          
<div class="form-group">
            <label  class="col-sm-4 control-label">Email:</label>
            <div class="col-sm-5">
            <input type='text' name='email' value='<?php echo $loggedInUser->email; ?>' class="form-control" />
      </div>
  </div>         
<div class="form-group">
            <label  class="col-sm-4 control-label">New Password:</label>
            <div class="col-sm-5">
          <input type="password" name="passwordc" class="form-control" placeholder="New Password" required="">
    </div>
  </div>
          <div class="form-group">
            <label   class="col-sm-4 control-label">Repeat Password:</label>
            <div class="col-sm-5">
          <input type="password" name="passwordcheck" class="form-control" placeholder="Repeat Password" required="">
    </div>
  </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-5">
          <button class="btn btn-small btn-primary btn-block" type="submit">Update</button>
              </div>
  </div>
          </fieldset>
        </form>

      </div>
      
    </div>
  </div>
</body>
</html>

