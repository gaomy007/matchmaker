<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

if (!isUserLoggedIn()) {
  header("Location: index.php");
  die();
}

    
require_once("models/header.php");
?>
<body>

  <div class="container"  id="low-container">
    <div class="row">
      <div class="col-sm-2  sidebar">
        <?php include("left-nav.php"); ?>
      </div> 

      <?php
      
      
      // Set the userid to 2 for testing purposes... you should have your own usersystem, so this should contain the userid


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
      
      ?>
      <div class="col-sm-9 ">



  <fieldset class="scheduler-border">
       <legend class="scheduler-border">Personal Message</legend>
        <?php
    //view one message
        if (isset($_GET['action']) && isset($_GET['mid']) && $_GET['action'] == "view") {
          $mid = $_GET['mid'];
          $result = $pm->getmessage($mid);

          if ($result)
            
            
            if ($userid == $pm->messages[0]['toid'] && !$pm->messages[0]['to_viewed']) {
              // set the messages flag to viewed
              $pm->viewed($pm->messages[0]['id']);
            }
            
          ?>
          <table class='table'>

            
            <?php if($userid!=$pm->messages[0]['fromid']){
              
            ?>
            <tr>
              <td><b>From:</b></td>
              <td><?php echo $pm->messages[0]['from']; ?></td>
              <td colspan="2"></td>
            </tr>
          <?php
            }          else{
              
             ?>
                        <tr>
              <td><b>To:</b></td>
              <td><?php echo $pm->messages[0]['to']; ?></td>
              <td colspan="2"></td>
            </tr>
            
            <?php
            
          }
        
          ?>
          
          
          
          
          
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
        
          <?php if($userid!=$pm->messages[0]['fromid']){
              
            ?>
          <div class="row">
            <div class="col-lg-2  ">
              <form name='reply' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                <input type='hidden' name='rfrom' value='<?php echo $pm->messages[0]['from']; ?>' />
                <input type='hidden' name='rsubject' value='Re: <?php echo $pm->messages[0]['title']; ?>' />
                <input type='hidden' name='rmessage' value='<?php echo  "\n\n------------------------------------------". "\n".$pm->messages[0]['from']."(wrote on ".$pm->messages[0]['created']."):\n".$pm->messages[0]['message']; ?>' />
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
              
            ?>
        
        
        
        
        
        
        
        
          <?php
        }


        if (isset($_POST['reply'])) {
          ?>



          <div class="row">
            <br>
            <form class="form-horizontal" role="form" name="new" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">


              <div class="form-group">
                <label for="inputTo" class="col-sm-2 control-label">To</label>
                <div class="col-sm-4">
                  <input type='text'  name='to' class="form-control" id="inputTo" value='<?php echo $_POST['rfrom']; ?>' >
                </div>
              </div>


              <div class="form-group">    
                <label for="inputSub" class="col-sm-2 control-label">Subject</label>
                <div class="col-sm-4">
                  <input type='text'   name='subject' class="form-control" id="inputSub" value='<?php echo $_POST['rsubject']; ?>' >
                </div>
              </div>   
              
              
              
              
              <div class="form-group">    
                <label for="inputMes" class="col-sm-2 control-label">Message</label>
                <div class="col-sm-8">
                  
              
                  <textarea class="form-control"  id="inputMes" name='message'  rows="6"><?php
                if (isset($_POST['reply'])) {
                  echo $_POST['rmessage'];
                }
                ?></textarea>               
                
                
                </div>
              </div>   

            <div class='col-sm-offset-4'> 
              <input type='submit' name='newmessage' value='Send' class='btn btn-success'/>
              
              </div> 
              
            </form>
          </div> 

    <?php
  }








//view all message.
  if ((!isset($_GET['action'])&&!isset($_POST['reply']))||(isset($_GET['action']) && $_GET['action'] == "viewAll")) {
    ?>
    <table class="table" >
      <thead>
        <tr>
          <th>Title</th>
          <th>From</th>
          <th>Date</th>
        </tr>
      </thead>
      <?php
      // read new 
      $pm->getmessages();
      if (count($pm->messages)) {
        // message loop



        for ($i = 0; $i < count($pm->messages); $i++) {
          ?>
          <tr>
            <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?action=view&mid=<?php echo $pm->messages[$i]['id']; ?>'>
      <?php echo $pm->messages[$i]['title'] ?>
              </a>
              <FONT COLOR='red' FACE="Geneva, Arial" SIZE=3>(new)</FONT>
            </td>
            <td><?php echo $pm->messages[$i]['from']; ?></td>

            <td><?php echo $pm->messages[$i]['created']; ?></td>
          </tr>
          <?php
        }
      }

      //read old.
      $pm->clear();
      $pm->getmessages(1);
      if (count($pm->messages)) {
        // message loop
        for ($i = 0; $i < count($pm->messages); $i++) {
          ?>
          <tr>

            <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?action=view&mid=<?php echo $pm->messages[$i]['id']; ?>'>
      <?php echo $pm->messages[$i]['title'] ?>
              </a>
            </td>

            <td><?php echo $pm->messages[$i]['from']; ?></td>
            <td><?php echo $pm->messages[$i]['created']; ?></td>
          </tr>
          <?php
        }
       
      }
       ?>
      </table>
  <?php
    }
    ?>

 
        
        <?php
        
        //read all sent messages.
        if (isset($_GET['action']) && $_GET['action'] == "sent"){
          
          
          $pm->getmessages(2);
          
              if(count($pm->messages)) {
           ?>

<table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>To</th>
        
        <th>Status</th>
        <th>Date</th>
        </tr>
    </thead>
    <?php
            for($i=0;$i<count($pm->messages);$i++) {
                ?>
                <tr>
                    
                    <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?action=view&mid=<?php echo $pm->messages[$i]['id']; ?>'>
                      <?php echo $pm->messages[$i]['title'] ?>
                      </a>
                    </td>
                    <td><?php echo $pm->messages[$i]['to']; ?></td>
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
          
        }

          
          
          
  
          
          ?>
        
                <?php
        
        //read all deleted messages.
        if (isset($_GET['action']) && $_GET['action'] == "deleted"){
          
          
          $pm->getmessages(3);
                     ?>

<table class="table">
 <table class='table'>
    <thead>
      <tr>
        <th>Title</th>
        <th>From</th>
        <th>Date</th>
        </tr>
    </thead>
    <?php
              if(count($pm->messages)) {

            for($i=0;$i<count($pm->messages);$i++) {
                ?>
                <tr>
                    
                    <td><a href='<?php echo $_SERVER['PHP_SELF']; ?>?action=view&mid=<?php echo $pm->messages[$i]['id']; ?>'><?php echo $pm->messages[$i]['title'] ?></a></td>
                    <td><?php echo $pm->messages[$i]['from']; ?></td>
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
          
        }?>

    </fieldset>
 
</div>
</div>   
</div>
<?php
require_once("footer.php");
?>