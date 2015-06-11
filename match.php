<?php
/*
  UserCake Version: 2.0.2
  http://usercake.com
 */


require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

if (!isUserLoggedIn()) {
  header("Location: index.php");
  die();
}

$ma = new matchMachine();
// echo "<br><br>";
//var_dump($ma);
$ma->ini();
$ma->mutualMatch();
$la = $ma->current_archive->profile->latitude;
$lo = $ma->current_archive->profile->longitude;
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Match Maker</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/clean-blog.css" rel="stylesheet">
    <link href="css/zoosk.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link class="jsbin" href="js/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script class="jsbin" src="js/jquery.min.js"></script>
    <script class="jsbin" src="js/jquery-ui.js"></script>
    <script class="jsbin" src="js/custom.js"></script>
    <script class="jsbin" src="models/funcs.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript">
      //<![CDATA[

      var customIcons = {
        restaurant: {
          icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
        },
        bar: {
          icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
        }
      };

      function load() {
        var map = new google.maps.Map(document.getElementById("map"), {
          center: new google.maps.LatLng(<?php echo $la; ?>,<?php echo $lo; ?>),
          zoom: 11,
          mapTypeId: 'roadmap'
        });
        var infoWindow = new google.maps.InfoWindow;


        downloadUrl("createXML.php", function(data) {
          var xml = data.responseXML;
          var markers = xml.documentElement.getElementsByTagName("marker");
          for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute("name");
            var address = markers[i].getAttribute("address");
            var type = markers[i].getAttribute("type");
            var photo = markers[i].getAttribute("photo");
            var uid = markers[i].getAttribute("uid");
            var city = markers[i].getAttribute("city");
            var a = '<a href="visit.php?uid=' + uid + '"><font size="3" color="#2244CC">visit</font></a>';
            var point = new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("la")),
                    parseFloat(markers[i].getAttribute("lo")));
            var html = '<img src="' + photo + '" width="100" height="100" />' + "<br/><b>" + name + '&nbsp;&nbsp;</b> ' + a + " <br/>" + city;
            var icon = customIcons[type] || {};
            var marker = new google.maps.Marker({
              map: map,
              position: point,
              icon: icon.icon
            });
            bindInfoWindow(marker, map, infoWindow, html);
          }
        });
        
      }

      function bindInfoWindow(marker, map, infoWindow, html) {
        google.maps.event.addListener(marker, 'click', function() {
          infoWindow.setContent(html);
          infoWindow.open(map, marker);
        });
      }

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {
      }

      //]]>

    </script>

  </head>
  <body onload='load()'>
    <div id="zplusframe" class="frame frame-mega">
      <div class="frame-header-wrapper" style="position: relative; height: 42px;;margin-bottom: 5px;">
        <div style="position: fixed; width: 1060px; height: 42px; top: 0px;"> 
          <div>

          </div>
          <header class="frame-header" style="height: 42px;">
            <a href="index.php">
              <h1 class="logo">
                Match Maker
              </h1>
            </a>
            <div>

              <nav class="user-control-nav" >
                <ul class="block-list">
                  <li class="user-control-item">
                    <a id="qa-navigation-home-link" class="home-link" href="index.php">Home</a></li>
                  <li class="separator">•</li>
                  <li class="user-control-item">
                    <a id="qa-navigation-home-link" class="home-link" href="about.php">About Us</a></li>
                  <li class="separator">•</li>
                  <li class="user-control-item">
                    <a id="qa-navigation-home-link" class="home-link" href="contact.php">Contact</a></li>
                  <li class="separator">•</li> 
                  <?php
                  if (!isUserLoggedIn()) {
                    echo '
               <li class="user-control-item">
                  <a id="qa-navigation-home-link" class="home-link" href="register.php">Not a Member?</a></li>
                    <li class="separator">•</li>
                   <li class="user-control-item">
                   <a id="qa-navigation-home-link" class="home-link" href="login.php">Log In</a></li>
                       <li class="separator">•</li>
                    ';
                  }
                  else {
                    echo '
                         <li class="user-control-item">
                  <a id="qa-navigation-home-link" class="home-link" href="match.php">Find Match</a></li>
                  <li class="separator">•</li>
                             <li class="user-control-item">
                  <a id="qa-navigation-home-link" class="home-link" href="account.php">Hi! <font color="#FFFACD">' . $loggedInUser->displayname . '</font></a></li>
                   <li class="separator">•</li>

                         <li class="user-control-item">
                  <a id="qa-navigation-home-link" class="home-link" href="logout.php">Log out</a></li>
                ';
                  }
                  ?>
                </ul>
              </nav>

            </div>
          </header>
        </div>
      </div>

<style type="text/css">
.page{padding:2px;font-weight:bolder;font-size:12px;}
.page a{border:1px solid #ccc;padding:0 5px 0 5px;margin:2px;text-decoration:none;color:#333;}
.page span{padding:0 5px 0 5px;margin:2px;background:#09f;color:#fff;border:1px solid #09c;}
</style>

      <div class="container" id="low-container">
        <div class="row">


          <div class="col-sm-2  sidebar">
            <?php include("left-nav.php"); ?>
          </div> 



          <div class="col-sm-10">
            <br>
            <fieldset class="scheduler-border">
              <legend class="scheduler-border">Match Result</legend>
              <div class="col-lg-7">

                <div class="form-group">
                  <label  class="col-lg-3 control-label" style="
    padding-right: 0px;
    padding-left: 0px;
    height: 25px;
    margin-top: 10px;
    width: 70px;">Sort by:</label>
                  <div class="col-sm-6">


                    <select  onChange="window.location.href = this.value"  class="form-control small">
                      <option value='match.php?sort=score' <?php if (!isset($_GET["sort"]) || $_GET["sort"] == "score") echo "selected" ?>>Highest Score</option>
                      <option value='match.php?sort=rscore'<?php if (isset($_GET["sort"]) && $_GET["sort"] == "rscore") echo "selected" ?>>Highest Reverse Score</option>
                      <option value='match.php?sort=distance'<?php if (isset($_GET["sort"]) && $_GET["sort"] == "distance") echo "selected" ?>>Nearest Distance</option>
                    </select>


                  </div>
                </div>
                <br>  <br>   
                <?php
                if (is_array($ma->archives) && count($ma->archives) > 0) {
                  


// sort the result array
                  if (isset($_GET["sort"]))
                    $ma->mysort($_GET["sort"]);
                  else {
                    $ma->mysort("score");
                  }
                  
   //pager work
$phpfile = 'match.php';
$page= isset($_GET['page'])?$_GET['page']:1;
$counts = count($ma->archives) ;
$mode= isset($_GET['sort'])?$_GET['sort']:"score";
$getpageinfo = page($page,$counts,$phpfile,5,5,$mode);  

$from= ($page-1)*5;
$to=$from+5;
if($to>$counts){
  $to=$counts;
}

        //show users as the page number
              for($i=$from;$i<$to;$i++) {
                    $a=$ma->archives[$i];
                    $r_score = $ma->score($ma->current_archive, $a);
                    $a->showInMatch($r_score);
                  }
                  
                  //
                }
                
                
                else {
                  echo "There is not Matched User for you!";
                }
                
                ?>
                
                                  <?php
                   if (is_array($ma->archives) && count($ma->archives) > 0) {
                     ?>
                   
                <div class="col-lg-3 col-lg-offset-3">
                  <?php
                echo $getpageinfo['pagecode'];
                     
               ?>
                    
                </div>
              <div class="col-lg-5">
             

                <div id="map" style="width: 300px; height: 300px"></div>


              </div>
                
                               <?php
                   }
                     ?>
            </fieldset>
          </div>




        </div>
      </div>  


      <?php
      require_once("footer.php");
      ?>



