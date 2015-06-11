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

    <!-- Custom CSS-->
    <link href="css/clean-blog.css" rel="stylesheet">
    <link href="css/zoosk.css" rel="stylesheet">   
   <link href="css/rr.css" rel="stylesheet">   

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
    <script class="jsbin" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>


<!--<script type="text/javascript" src=" ./blab_im/bar.php"></script>-->

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
          center: new google.maps.LatLng(37.70307900, -122.47545200),
          zoom: 13,
          mapTypeId: 'roadmap'
        });
        var infoWindow = new google.maps.InfoWindow;

        // Change this depending on the name of your PHP file
        downloadUrl("createXML.php", function(data) {
          var xml = data.responseXML;
          var markers = xml.documentElement.getElementsByTagName("marker");
          for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute("name");
            var address = markers[i].getAttribute("address");
            var type = markers[i].getAttribute("type");
            var point = new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("la")),
                    parseFloat(markers[i].getAttribute("lo")));
            var html = "<b>" + name + "</b> <br/>" + address;
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
  <body 
  <?php
  if ($_SERVER["REQUEST_URI"] == "/cake/match.php")
    echo "onload='load()'";
  ?>>
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

            <nav class="user-control-nav">
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
 $isAdmin=false;
if(isUserLoggedIn()) {
$loggedInUser = $_SESSION["userCakeUser"];
$isAdmin=$loggedInUser->checkPermission(array(2));
}
 
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
            if(!$isAdmin){
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
            else{
              
               echo '

      
                             <li class="user-control-item">
                  <a id="qa-navigation-home-link" class="home-link" href="account.php">Hi! <font color="#FFFACD">' . $loggedInUser->displayname . '</font></a></li>
                   <li class="separator">•</li>

                         <li class="user-control-item">
                  <a id="qa-navigation-home-link" class="home-link" href="logout.php">Log out</a></li>
                ';
              
              
              
            }
            
          }
          ?>
              </ul>
            </nav>
            
          </div>
        </header>
      </div>
    </div>
