<?php


    
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

if (!isUserLoggedIn()) {
  header("Location: index.php");
  die();
}
    // Set the userid to 2 for testing purposes... you should have your own usersystem, so this should contain the userid
    $loggedInUser = $_SESSION["userCakeUser"];
    $userid=$loggedInUser->user_id;
    // initiate a new pm class
    $pm = new cpm($userid);
    
    // check if a new message had been send
    if(isset($_POST['newmessage'])) {
        // check if there is an error while sending the message (beware, the input hasn't been checked, you should never trust users input!)
        if($pm->sendmessage($_POST['to'],$_POST['subject'],$_POST['message'])) {
            // Tell the user it was successful
            echo "Message successfully sent!";
        } else {
            // Tell user something went wrong it the return was false
            echo "Error, couldn't send PM. Maybe wrong user.";
        }
    }
    
    // check if a message had been deleted
    if(isset($_POST['delete'])) {
        // check if there is an error during deletion of the message
        if($pm->deleted($_POST['did'])) {
            echo "Message successfully deleted!";
        } else {
            echo "Error, couldn't delete PM!";
        }
    }
 require_once("models/header.php");   
?>
<body>
  <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
          <div class="site-heading">
            <h1>See the person matching you!</h1>
            <hr class="small">
            <span class="subheading"></span>

          </div>
        </div>
      </div>
    </div>

  </header>
  <div class="container">
    <div class="row">
      <div class="col-sm-3 col-md-2 sidebar">
        <?php include("left-nav.php"); ?>
      </div> 
      
  <div class="col-lg-9">     
<?php
// In this switch we check what page has to be loaded, this way we just load the messages we want using numbers from 0 to 3 (0 is standart, so we don't need to type this)
if(isset($_GET['p'])) {
    switch($_GET['p']) {
        // get all new / unread messages
        case 'new': $pm->getmessages(); break;
        // get all send messages
        case 'send': $pm->getmessages(2); break;
        // get all read messages
        case 'read': $pm->getmessages(1); break;
        // get all deleted messages
        case 'deleted': $pm->getmessages(3); break;
        // get a specific message
        case 'view': $pm->getmessage($_GET['mid']); break;
        // get all new / unread messages
        default: $pm->getmessages(); break;
    }
} else {
    // get all new / unread messages
    $pm->getmessages();
}
// Standard links
?>
    <div class="btn-group">
<a  class="btn btn-success" href='<?php echo $_SERVER['PHP_SELF']; ?>?p=new'>New Messages</a>
<a  class="btn btn-primary" href='<?php echo $_SERVER['PHP_SELF']; ?>?p=read'>All Messages</a>
<a  class="btn btn-info" href='<?php echo $_SERVER['PHP_SELF']; ?>?p=send'>Sent Messages</a>
<a  class="btn btn-warning" href='<?php echo $_SERVER['PHP_SELF']; ?>?p=deleted'>Deleted Messages</a>
</div>
    
    <br>
     <br>
      <br>
<?php
// if it's the standart startpage or the page new, then show all new messages
if(!isset($_POST['reply'])&&(!isset($_GET['p']) || $_GET['p'] == 'new')) {
?>
<table class="table" >
    <thead>
      <tr>
        <th>From</th>
        <th>Title</th>
        <th>Date</th>
        </tr>
    </thead>
    <?php
        // If there are messages, show them
        if(count($pm->messages)) {
            // message loop
            for($i=0;$i<count($pm->messages);$i++) {
                ?>
                <tr>
                    <td><?php echo $pm->messages[$i]['from']; ?></td>
                    <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?p=view&mid=<?php echo $pm->messages[$i]['id']; ?>'><?php echo $pm->messages[$i]['title'] ?></a></td>
                    <td><?php echo $pm->messages[$i]['created']; ?></td>
                </tr>
                <?php
            }
        } else {
            // else... tell the user that there are no new messages
            echo "<tr><td colspan='3'><strong>No new messages found</strong></td></tr>";
        }
    ?>
</table>
<?php
// check if the user wants send messages
} elseif(!isset($_POST['reply'])&&$_GET['p'] == 'send') {
?>

<table class="table">
    <thead>
      <tr>
        <th>To</th>
        <th>Title</th>
        <th>Status</th>
        <th>Date</th>
        </tr>
    </thead>
    <?php
        // if there are messages, show them
        if(count($pm->messages)) {
            // message loop
            for($i=0;$i<count($pm->messages);$i++) {
                ?>
                <tr>
                    <td><?php echo $pm->messages[$i]['to']; ?></td>
                    <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?p=view&mid=<?php echo $pm->messages[$i]['id']; ?>'><?php echo $pm->messages[$i]['title'] ?></a></td>
                    <td>
                    <?php  
                        // If a message is deleted and not viewed
                        if($pm->messages[$i]['to_deleted'] && !$pm->messages[$i]['to_viewed']) {
                            echo "Deleted without reading";
                        // if a message got deleted AND viewed
                        } elseif($pm->messages[$i]['to_deleted'] && $pm->messages[$i]['to_viewed']) {
                            echo "Deleted after reading";
                        // if a message got not deleted but viewed
                        } elseif(!$pm->messages[$i]['to_deleted'] && $pm->messages[$i]['to_viewed']) {
                            echo "Read";
                        } else {
                        // not viewed and not deleted
                            echo "Not read yet";
                        }
                    ?>
                    </td>
                    <td><?php echo $pm->messages[$i]['created']; ?></td>
                </tr>
                <?php
            }
        } else {
            // else... tell the user that there are no new messages
            echo "<tr><td colspan='4'><strong>No send messages found</strong></td></tr>";
        }
    ?>
</table>

<?php
// check if the user wants the read messages
} elseif($_GET['p'] == 'read') {
?>
    <table class='table'>
    <thead>
      <tr>
        <th>From</th>
        <th>Title</th>
        <th>Date</th>
        </tr>
    </thead>
    <?php
        // if there are messages, show them
        if(count($pm->messages)) {
            // message loop
            for($i=0;$i<count($pm->messages);$i++) {
                ?>
                <tr>
                    <td><?php echo $pm->messages[$i]['from']; ?></td>
                    <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?p=view&mid=<?php echo $pm->messages[$i]['id']; ?>'><?php echo $pm->messages[$i]['title'] ?></a></td>
                    <td><?php echo $pm->messages[$i]['to_vdate']; ?></td>
                </tr>
                <?php
            }
        } else {
            // else... tell the user that there are no new messages
            echo "<tr><td colspan='4'><strong>No read messages found</strong></td></tr>";
        }
    ?>
    </table>

<?php
// check if the user wants the deleted messages
} elseif($_GET['p'] == 'deleted') {
?>
    <table class='table'>
    <thead>
      <tr>
        <th>From</th>
        <th>Title</th>
        <th>Date</th>
        </tr>
    </thead>
    <?php
        // if there are messages, show them
        if(count($pm->messages)) {
            // message loop
            for($i=0;$i<count($pm->messages);$i++) {
                ?>
                <tr>
                    <td><?php echo $pm->messages[$i]['from']; ?></td>
                    <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?p=view&mid=<?php echo $pm->messages[$i]['id']; ?>'><?php echo $pm->messages[$i]['title'] ?></a></td>
                    <td><?php echo $pm->messages[$i]['to_ddate']; ?></td>
                </tr>
                <?php
            }
        } else {
            // else... tell the user that there are no new messages
            echo "<tr><td colspan='4'><strong>No deleted messages found</strong></td></tr>";
        }
    ?>
</table>
<?php
// if the user wants a detail view and the message id is set...
} elseif($_GET['p'] == 'view' && isset($_GET['mid'])) {
    // if the users id is the recipients id and the message hadn't been viewed yet
    if($userid == $pm->messages[0]['toid'] && !$pm->messages[0]['to_viewed']) {
        // set the messages flag to viewed
        $pm->viewed($pm->messages[0]['id']);
    }
?>
    <table class='table'>
      
      <tr>
            <td><b>From:</b></td>
            <td><?php echo $pm->messages[0]['from']; ?></td>
            <td colspan="2"></td>
        </tr>
          
        <tr>
            <td><b>Date:</b></td>
            <td><?php echo $pm->messages[0]['created']; ?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td><b>Subject:</b></td>
            <td colspan="3"><?php echo $pm->messages[0]['title']; ?></td>
        </tr>
        <tr>
            <td colspan="4" border="1"><?php echo $pm->render($pm->messages[0]['message']); ?></td>
        </tr>
    </table>
  <div class="row">
    <div class="col-lg-2  ">
    <form name='reply' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
        <input type='hidden' name='rfrom' value='<?php echo $pm->messages[0]['from']; ?>' />
        <input type='hidden' name='rsubject' value='Re: <?php echo $pm->messages[0]['title']; ?>' />
        <input type='hidden' name='rmessage' value='[quote]<?php echo $pm->messages[0]['message']; ?>[/quote]' />
        <input class='btn btn-success small' type='submit' name='reply' value='Reply' />
    </form>
    </div> 
      <div class="col-lg-2  col-lg-offset-3 ">
    <form name='delete' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
        <input type='hidden' name='did' value='<?php echo $pm->messages[0]['id']; ?>' />
        <input class='btn btn-warning small'  type='submit' name='delete' value='Delete' />
    </form>
         </div> 
     </div> 
<?php
}


if(isset($_POST['reply'])){
?>
      
      
      
      <div class="row">
      <br><br>
<form name="new" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<strong>To:</strong>
<input type='text' name='to' value='<?php if(isset($_POST['reply'])) { echo $_POST['rfrom']; } ?>' />
<strong>Subject:</strong>
<input type='text' name='subject' value='<?php if(isset($_POST['reply'])) { echo $_POST['rsubject']; } ?>' />
<strong>Message:</strong><br  />
<textarea name='message'><?php if(isset($_POST['reply'])) { echo $_POST['rmessage']; } ?></textarea>
<input type='submit' name='newmessage' value='Send' />
</form>
</div> 

  
  </div>
</div>   
    </div>
<?php
}
require_once("footer.php");
?>