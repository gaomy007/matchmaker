<?php

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}
require_once("models/header.php");
if(!isUserLoggedIn()) { header("Location: index.php"); die(); }


if(!empty($_POST))
{
	$errors = array();
  
	$gender = $_POST["gender"];
	$age = trim($_POST["age"]);
  $income = trim($_POST["income"]);
  $relationship = $_POST["relationship"];
  $haskid = $_POST["haskid"];
  $wantkid = $_POST["wantkid"];
  $height = trim($_POST["height"]);
  $education = $_POST["education"];
  $smoke = $_POST["smoke"];
  $drink = $_POST["drink"];
  $ethnicity = $_POST["ethnicity"];
  $bodytype = $_POST["bodytype"];
  
  $rgender = $_POST["gender"];
	$agefrom = trim($_POST["agefrom"]);
  $ageto = trim($_POST["ageto"]);
  $rincome = trim($_POST["rincome"]);
  $rrelationship = $_POST["rrelationship"];
  $rhaskid = $_POST["rhaskid"];
  $rwantkid = $_POST["rwantkid"];
  $heightfrom = trim($_POST["heightfrom"]);
  $heightto = trim($_POST["heightto"]);
  $reducation = $_POST["reducation"];
  $rsmoke = $_POST["rsmoke"];
  $rdrink = $_POST["rdrink"];
  $rethnicity = $_POST["rethnicity"];
  $rbodytype = $_POST["rbodytype"];

  var_dump($rethnicity);
  foreach($rethnicity as $key=>$value){
    echo $key.' '.$value.'\n';
    
  }
	if(minMaxRange(5,25,$username))
	{
		$errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($username)){
		$errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
	}
	if(minMaxRange(5,25,$displayname))
	{
		$errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($displayname)){
		$errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
	}
	if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
	{
		$errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
	}
	else if($password != $confirm_pass)
	{
		$errors[] = lang("ACCOUNT_PASS_MISMATCH");
	}
	if(!isValidEmail($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
	//End data validation
	if(count($errors) == 0)
	{	
		//Construct a user object
		$user = new User($username,$displayname,$password,$email);
		
		//Checking this flag tells us whether there were any errors such as possible data duplication occured
		if(!$user->status)
		{
			if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
			if($user->displayname_taken) $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
			if($user->email_taken) 	  $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));		
		}
		else
		{
			//Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
			if(!$user->userCakeAddUser())
			{
				if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
				if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
			}
		}
	}
	if(count($errors) == 0) {
		$successes[] = $user->success;
	}
}

?>
