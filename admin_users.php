<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Forms posted
if(!empty($_POST))
{
	$deletions = $_POST['delete'];
	if ($deletion_count = deleteUsers($deletions)){
		$successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
	}
	else {
		$errors[] = lang("SQL_ERROR");
	}
}

$userData = fetchAllUsers(); //Fetch information for all users

require_once("models/header.php");

?>
<body>

  <div class="container"  id="low-container">
    <div class="row">
      <div class="col-sm-2  sidebar">
        <?php include("left-nav.php"); ?>
      </div> 
 <div class="col-sm-10">
        
  <?php


echo resultBlock($errors,$successes);

echo "
<form name='adminUsers' action='".$_SERVER['PHP_SELF']."' method='post'>
<table class='admin'>
<tr>
<th>Delete</th><th style='padding-left: 20px;'>Username</th><th  style='padding-left: 20px;'>Display Name</th><th  style='padding-left: 20px;'>Last Sign In</th>
</tr>";

//Cycle through users
foreach ($userData as $v1) {
	echo "
	<tr>
	<td><input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'></td>
	<td  style='padding-left: 20px;'><a href='admin_user.php?id=".$v1['id']."'>".$v1['user_name']."</a></td>
	<td style='padding-left: 20px;'>".$v1['display_name']."</td>
	<!--<td style='padding-left: 20px;'>".$v1['title']."</td>-->
	<td style='padding-left: 20px;'>
	";
	
	//Interprety last login
	if ($v1['last_sign_in_stamp'] == '0'){
		echo "Never";	
	}
	else {
		echo date("j M, Y", $v1['last_sign_in_stamp']);
	}
	echo "
	</td>
	</tr>";
}

echo "
</table>
<input type='submit' name='Submit' value='Delete' class='btn btn-success'/>
</form>
";

?>
</div>
      
         </div>
  </div>  
  
  <?php
require_once("footer.php");
?>