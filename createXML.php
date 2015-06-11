<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}
if (!isUserLoggedIn()) {
  header("Location: index.php");
  die();
}

if(!isset($_SESSION["archives"]))
{
  echo "no matching info.";
  die();
  
}
header("Content-type: text/xml");

$archives=$_SESSION["archives"];

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each

foreach($archives as $a){
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . parseToXML($a->profile->displayName) . '" ';
  echo 'address="' . parseToXML($a->profile->address) . '" ';
  echo 'la="' . parseToXML($a->profile->latitude) . '" ';
  echo 'lo="' . parseToXML($a->profile->longitude) . '" ';
  echo 'photo="' . $a->profile->photo . '" ';
  echo 'uid="' . $a->profile->uid . '" ';
  echo 'city="' . $a->profile->city . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';

?>
