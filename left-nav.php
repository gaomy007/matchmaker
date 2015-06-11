<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}
$isAdmin=false;
if(isUserLoggedIn()) {
$loggedInUser = $_SESSION["userCakeUser"];
$isAdmin=$loggedInUser->checkPermission(array(2));
}

if(isUserLoggedIn()&&!$isAdmin) {
 $cu = $_SESSION["userCakeUser"];
 $archive_sidebar=new archive();
 $archive_sidebar->iniByUid($cu->user_id);
 
?>

<div class="col nav-col" style="position: static; height: 444px;">
  <div style="width: 170px; height: 444px; position: absolute; top: 0px;">
    
      <nav class="main-nav">
        <header class="media-unit actionable goog-control" style="-webkit-user-select: none; background-color: #efe7a3;">
          <div class="image-unit">
            <img alt="<?php echo $archive_sidebar->profile->displayName?>" src="<?php echo $archive_sidebar->profile->photo?>">
          </div>
          <div class="details-unit">
            <h2 class="username"><?php echo $archive_sidebar->profile->displayName?></h2>
            <span class="mini-note"><?php echo $archive_sidebar->profile->age?>, <?php echo $archive_sidebar->profile->city?></span>
          </div>
        </header>
        <div class="nav-body">
 
          <ul>
          <?php 

{

  $current_page=  basename($_SERVER['PHP_SELF']);
  // for user setting.php
   if ($current_page=="user_settings.php") {
	echo '<li class="main-nav-carousel link selected" tabindex="0" style="-webkit-user-select: none;">
    <a href="user_settings.php"><span aria-hidden="true" class="icon-carousel icon-font"></span>
    User Settings
    <span class="counter" style="display: none;">0</span></a>
    </li>';
   }
   else
     echo '<li class="main-nav-carousel link" tabindex="0" style="-webkit-user-select: none;">
    <a href="user_settings.php"><span aria-hidden="true" class="icon-carousel icon-font"></span>
    User Settings
    <span class="counter" style="display: none;">0</span></a>
    </li>';
   
   
  if($loggedInUser->getPid()==NULL){
    if($loggedInUser->getRpid()==NULL){
      
      if($current_page=="view_profile.php"){
    echo '<li class="main-nav-connections link selected" tabindex="0" style="-webkit-user-select: none;">
      <a href="view_profile.php">
      <span aria-hidden="true" class="icon-connections icon-font"></span>
      Profile
      <span class="counter" style="display: none;">0</span></a></li>';
      }
      else {
          echo '<li class="main-nav-connections link" tabindex="0" style="-webkit-user-select: none;">
      <a href="view_profile.php">
      <span aria-hidden="true" class="icon-connections icon-font"></span>
      Profile
      <span class="counter" style="display: none;">0</span></a></li>';
      }
      
     
            if($current_page=="view_requirement.php"){
    echo '<li class="main-nav-views link selected" tabindex="0" style="-webkit-user-select: none;">
         <a href="view_requirement.php">
          <span aria-hidden="true" class="icon-views icon-font"></span>
          Search Requirement
          <span class="counter" style="display: none;">0</span></a></li>';
      }
      else {
          echo '<li class="main-nav-views link" tabindex="0" style="-webkit-user-select: none;">
         <a href="view_requirement.php">
          <span aria-hidden="true" class="icon-views icon-font"></span>
          Search Requirement
          <span class="counter" style="display: none;">0</span></a></li>';
      }
    
     
     
      }
    else
    {
                if($current_page=="view_profile.php"){
    echo '<li class="main-nav-connections link selected" tabindex="0" style="-webkit-user-select: none;">
      <a href="view_profile.php">
      <span aria-hidden="true" class="icon-connections icon-font"></span>
      Profile
      <span class="counter" style="display: none;">0</span></a></li>';
      }
      else {
          echo '<li class="main-nav-connections link " tabindex="0" style="-webkit-user-select: none;">
      <a href="view_profile.php">
      <span aria-hidden="true" class="icon-connections icon-font"></span>
      Profile
      <span class="counter" style="display: none;">0</span></a></li>';
     
          }
      
                    if($current_page=="view_requirement.php"){
    echo'<li class="main-nav-views link selected" tabindex="0" style="-webkit-user-select: none;">
         <a href="view_requirement.php">
          <span aria-hidden="true" class="icon-views icon-font"></span>
         Search Requirement
          <span class="counter" style="display: none;">0</span></a></li>';
      }
      else {
          echo '<li class="main-nav-views link " tabindex="0" style="-webkit-user-select: none;">
         <a href="view_requirement.php">
          <span aria-hidden="true" class="icon-views icon-font"></span>
          Search Requirement
          <span class="counter" style="display: none;">0</span></a></li>';
      }
      
    }
  }
  else {
    if($loggedInUser->getRpid()==NULL){
      
      
                    if($current_page=="view_profile.php"){
    echo '<li class="main-nav-connections link selected" tabindex="0" style="-webkit-user-select: none;">
      <a href="view_profile.php">
      <span aria-hidden="true" class="icon-connections icon-font"></span>
      Profile
      <span class="counter" style="display: none;">0</span></a></li>';
      }
      else {
          echo '<li class="main-nav-connections link" tabindex="0" style="-webkit-user-select: none;">
      <a href="view_profile.php">
      <span aria-hidden="true" class="icon-connections icon-font"></span>
      Profile
      <span class="counter" style="display: none;">0</span></a></li>';
      }
      
                        
      if($current_page=="view_requirement.php"){
    echo'<li class="main-nav-views link selected" tabindex="0" style="-webkit-user-select: none;">
         <a href="view_requirement.php">
          <span aria-hidden="true" class="icon-views icon-font"></span>
          Search Requirement
          <span class="counter" style="display: none;">0</span></a></li>';
      }
      else {
          echo '<li class="main-nav-views link" tabindex="0" style="-webkit-user-select: none;">
         <a href="view_requirement.php">
          <span aria-hidden="true" class="icon-views icon-font"></span>
         Search Requirement
          <span class="counter" style="display: none;">0</span></a></li>';
      }
    
     
     
    }
    else
    {
                        if($current_page=="view_profile.php"){
    echo '<li class="main-nav-connections link selected" tabindex="0" style="-webkit-user-select: none;">
      <a href="view_profile.php">
      <span aria-hidden="true" class="icon-connections icon-font"></span>
      Profile
      <span class="counter" style="display: none;">0</span></a></li>';
      }
      else {
          echo '<li class="main-nav-connections link" tabindex="0" style="-webkit-user-select: none;">
      <a href="view_profile.php">
      <span aria-hidden="true" class="icon-connections icon-font"></span>
      Profile
      <span class="counter" style="display: none;">0</span></a></li>';
      }
                         if($current_page=="view_requirement.php"){
    echo '<li class="main-nav-views link selected" tabindex="0" style="-webkit-user-select: none;">
         <a href="view_requirement.php">
          <span aria-hidden="true" class="icon-views icon-font"></span>
          Search Requirement
          <span class="counter" style="display: none;">0</span></a></li>';
      }
      else {
          echo '<li class="main-nav-views link" tabindex="0" style="-webkit-user-select: none;">
         <a href="view_requirement.php">
          <span aria-hidden="true" class="icon-views icon-font"></span>
           Search Requirement
          <span class="counter" style="display: none;">0</span></a></li>';
      }
      
    }   
  }
  
  
  
  
  

$userid = $loggedInUser->user_id;
// initiate a new pm class
$pm = new cpm($userid);
        if (isset($_GET['action']) && isset($_GET['mid']) && $_GET['action'] == "view") {
          $mid = $_GET['mid'];
          $result = $pm->getmessage($mid);

          if ($result)
            if ($userid == $pm->messages[0]['toid'] && !$pm->messages[0]['to_viewed']) {
              // set the messages flag to viewed
              $pm->viewed($pm->messages[0]['id']);
            }
          $pm->clear();
        }
        
        ?>
   



  <li class="main-nav-online link <?php if (($current_page=="view_pm.php"&&!isset($_GET['action'])&&!isset($_POST['reply']))||(isset($_GET['action']) && $_GET['action']=="viewAll")) echo "selected";?>" tabindex="0" style="-webkit-user-select: none; " >
    <a  href='<?php echo "view_pm.php"; ?>?action=viewAll'>
    <span aria-hidden="true" class="icon-onlinenow icon-font"></span>
      Inbox
    <span class="badge"><?php echo "".$pm->getUnreadedNumber(); ?></span>
    <span class="counter" style="display: none;">0</span>
    </a>
  </li>
  
  <li class="main-nav-messages link <?php if ($current_page=="view_pm.php"&&isset($_GET['action'])&&$_GET['action']=="sent") echo "selected";?>" tabindex="0" style="-webkit-user-select: none;">
          <a  href='<?php echo "view_pm.php"; ?>?action=sent'>
            <span aria-hidden="true" class="icon-messages icon-font"></span>
            Sent Messages
    <span class="counter" style="display: none;">0</span>
          <span class="counter" style="display: none;">0</span>
          </a>
  </li>    
  
  
  <li class="main-nav-subscribe link link-hover <?php if ($current_page=="view_pm.php"&&isset($_GET['action'])&&$_GET['action']=="deleted") echo "selected";?>"  tabindex="0" style="-webkit-user-select: none;">
    <a   href='<?php echo "view_pm.php"; ?>?action=deleted'>
      <span aria-hidden="true" class="icon-subscribe icon-font"></span>      
    Deleted Messages
    <span class="counter" style="display: none;">0</span>
    </a>
  </li>
 

</ul>


</div>
        </nav>
    </div>
  </div>
	<?php
  }
          
          
          
}
     //     echo "<div class='col nav-col' style='position: static; height: 444px;'>
  //<div style='width: 170px; height: 444px; position: absolute; top: 0px;'> <div><nav class='main-nav'><div class='nav-body'><ul>";
   

//Links for logged in user

	if (isUserLoggedIn()&&$isAdmin){
	echo "
    <ul class='list-group'>  
	<!--<li class='list-group-item'><a href='admin_configuration.php'>Website Configuration</a></li>-->
	<li class='list-group-item'> <a href='admin_users.php'>Manage Users</a></li>
	<!--<li class='list-group-item'><a href='admin_permissions.php'>Set Permissions</a></li>-->
	<!--<li class='list-group-item'><a href='admin_pages.php'>Page Accessibility</a></li>-->";
  echo "</ul>";
	}
  
//Links for users not logged in
 if (!isUserLoggedIn()){
	echo "
    <ul class='list-group'>  
	<li class='list-group-item'><a href='forgot-password.php'>Forgot Password</a></li>";
	if ($emailActivation)
	{
	echo "<li class='list-group-item'><a href='resend-activation.php'>Resend Activation Email</a></li>";
	}
  echo "</ul>";

}



