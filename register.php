<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

//Prevent the user visiting the logged in page if he/she is already logged in
if (isUserLoggedIn()) {
  header("Location: account.php");
  die();
}
//Forms posted
if (!empty($_POST)) {
  $errors = array();
  $email = trim($_POST["email"]);
  $username = trim($_POST["username"]);
  $displayname = trim($_POST["displayname"]);
  $password = trim($_POST["password"]);
  $confirm_pass = trim($_POST["passwordc"]);
  $captcha = md5($_POST["captcha"]);


  if ($captcha != $_SESSION['captcha']) {
    $errors[] = lang("CAPTCHA_FAIL");
  }
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
  if (minMaxRange(8, 50, $password) && minMaxRange(8, 50, $confirm_pass)) {
    $errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT", array(8, 50));
  }
  else if ($password != $confirm_pass) {
    $errors[] = lang("ACCOUNT_PASS_MISMATCH");
  }
  if (!isValidEmail($email)) {
    $errors[] = lang("ACCOUNT_INVALID_EMAIL");
  }
  //End data validation
  if (count($errors) == 0) {
    //Construct a user object
    $user = new User($username, $displayname, $password, $email);

    //Checking this flag tells us whether there were any errors such as possible data duplication occured
    if (!$user->status) {
      if ($user->username_taken)
        $errors[] = lang("ACCOUNT_USERNAME_IN_USE", array($username));
      if ($user->displayname_taken)
        $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE", array($displayname));
      if ($user->email_taken)
        $errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
    }
    else {
      //Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
      if (!$user->userCakeAddUser()) {
        if ($user->mail_failure)
          $errors[] = lang("MAIL_ERROR");
        if ($user->sql_failure)
          $errors[] = lang("SQL_ERROR");
      }
    }
  }
  if (count($errors) == 0) {
    $successes[] = $user->success;
  }
}

require_once("models/header.php");
?>
<body>


  <div class="container" id="low-container">
    <div class="row">
      <div class='col-sm-2'>


      </div>

      <div class="col-sm-8">

        <br>  <br>  <br> 
        <?php
        echo resultBlock($errors, $successes);
        ?>


        <form name='newUser' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' class="form-horizontal" role="form">
          <fieldset class="scheduler-border">
            <legend class="scheduler-border">Register</legend>


            <div class="form-group">
              <label  class="col-sm-4 control-label">
                User Name:</label>
              <div class="col-sm-5">
              <input type='text' name='username'  class="form-control"/>
                    </div>
            </div>




            <div class="form-group">
              <label  class="col-sm-4 control-label">
                Display Name:</label>

              <div class="col-sm-5">
                <input type='text' name='displayname'  class="form-control"/>

              </div>
            </div>

            <div class="form-group">
              <label  class="col-sm-4 control-label">Password:</label>
              <div class="col-sm-5">

                <input type='password' name='password'  class="form-control"/>
              </div>
            </div>


            <div class="form-group">
              <label  class="col-sm-4 control-label">Confirm:</label>
              <div class="col-sm-5">
                <input type='password' name='passwordc'  class="form-control"/>
              </div>
            </div>


            <div class="form-group">
              <label  class="col-sm-4 control-label">Email:</label>
              <div class="col-sm-5">
                <input type='text' name='email'  class="form-control"/>
              </div>
            </div>


            <div class="form-group">
              <label  class="col-sm-4 control-label">Security Code:</label>
              <div class="col-sm-5">
                <img src='models/captcha.php'>
              </div>
            </div>

            <div class="form-group">
              <label  class="col-sm-4 control-label">Enter Security Code:</label>
              <div class="col-sm-5">
                <input name='captcha' type='text' class="form-control" />
              </div>
            </div>

            <br>
            
           <div class="form-group">
            <div class="col-sm-offset-4 col-sm-5">
              <input class="btn btn-small btn-primary btn-block"  type='submit' value='Register'/>
            </div>
                       </div>
    
            
            
          </fieldset>
        </form>


      </div>
    </div>
  </div>
</body>
</html>
