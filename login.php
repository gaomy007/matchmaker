<?php
/*
  UserCake Version: 2.0.2
  http://usercake.com
 */

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

//Prevent the user visiting the logged in page if he/she is already logged in
if (isUserLoggedIn()) {
  header("Location: index.php");
  die();
}

//Forms posted
if (!empty($_POST)) {
  $errors = array();
  $username = sanitize(trim($_POST["username"]));
  $password = trim($_POST["password"]);

  //Perform some validation
  //Feel free to edit / change as required
  if ($username == "") {
    $errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
  }
  if ($password == "") {
    $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
  }

  if (count($errors) == 0) {
    //A security note here, never tell the user which credential was incorrect
    if (!usernameExists($username)) {
      $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
    }
    else {
      $userdetails = fetchUserDetails($username);
      //See if the user's account is activated
      if ($userdetails["active"] == 0) {
        $errors[] = lang("ACCOUNT_INACTIVE");
      }
      else {
        //Hash the password and use the salt from the database to compare the password.
        $entered_pass = generateHash($password, $userdetails["password"]);

        if ($entered_pass != $userdetails["password"]) {
          //Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
          $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
        }
        else {
          //Passwords match! we're good to go'
          //Construct a new logged in user object
          //Transfer some db data to the session object
          $loggedInUser = new loggedInUser();
          $loggedInUser->email = $userdetails["email"];
          $loggedInUser->user_id = $userdetails["id"];
          $loggedInUser->hash_pw = $userdetails["password"];
          $loggedInUser->title = $userdetails["title"];
          $loggedInUser->displayname = $userdetails["display_name"];
          $loggedInUser->username = $userdetails["user_name"];

          //Update last sign in
          $loggedInUser->updateLastSignIn();
          $_SESSION["userCakeUser"] = $loggedInUser;

          //Redirect to user account page
          header("Location: account.php");
          die();
        }
      }
    }
  }
}

require_once("models/header.php");
?>
<body>





  <div class="container" id="low-container">
    <div class="row">
 <div class="col-sm-3 col-md-2 sidebar">
<?php include("left-nav.php"); ?>
</div>
      <div class="col-lg-4 col-lg-offset-2">
        <form name='login' class="form-signin" action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
          <h1 class="form-signin-heading text-muted">Login In</h1>

          <input class="form-control" placeholder="User Name" required="" autofocus="" type='text' name='username' />
          <input type="password" name="password" class="form-control" placeholder="Password" required="">
          <label>&nbsp;</label>
          <button class="btn btn-lg btn-primary btn-block" type="submit">
            Login In
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php
  require_once("footer.php");
  ?>



