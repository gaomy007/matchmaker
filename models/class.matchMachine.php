<?php

class matchMachine {

  public $current_archive = NULL;
  public $querytxt = NULL;
  public $visitor = NULL;
  public $archives = NULL;

  function ini() {
    $this->current_archive = new archive();
    $this->current_archive->ini();

    // var_dump($this->current_winfo);
  }

  function iniForPeerMatch($cuid, $vuid) {
    $this->current_archive = new archive();
    $this->current_archive->iniByUid($cuid);
    $this->visitor = new archive();
    $this->visitor->iniByUid($vuid);
    // var_dump($this->current_winfo);
  }

  function query_replace($tag) {

    $search = "#({" . $tag . "}).*?({/" . $tag . "})#";

    $this->querytxt = preg_replace($search, "", $this->querytxt);
  }

  function tag_remove($tag) {

    $start = "{" . $tag . "}";
    $end = "{/" . $tag . "}";
    $this->querytxt = str_replace($start, "", $this->querytxt);
    $this->querytxt = str_replace($end, "", $this->querytxt);
  }

  function test() {

    $this->querytxt = file_get_contents("querytemp/a.txt");
    // echo $this->querytxt;
  }

  function mutualMatch() {
    global $mysqli;
    $loggedInUser = $_SESSION["userCakeUser"];
    $uid = NULL;
    $uids = array();
    $archives = array();


    //mandatory type
    if ($this->current_archive->rprofile->mtype != 2) {
      //ready the query template.
      $this->querytxt = file_get_contents("querytemp/matchSingle.txt");
      $this->querytxt = str_replace("\n", "", $this->querytxt);


      //modify the template base on the winfo,get the single match result from db. 
      foreach ($this->current_archive->winfo as $type => $value) {
        // echo "$type=>$value" . "<br>";
        if ($type == "wid")
          continue;
        if ($value == 1) {
          $this->tag_remove($type);
        }
        else {
          $this->query_replace($type);
        }
      }

      $this->querytxt = str_replace("{id}", $loggedInUser->user_id, $this->querytxt);

      // echo $this->querytxt;
      $stmt = $mysqli->prepare($this->querytxt);
      $stmt->execute();
      $stmt->bind_result(
          $uid
      );
      while ($stmt->fetch()) {
        $uids[] = $uid;
      }

      $stmt->close();
      //var_dump($uids);
      $number1 = count($uids);

      for ($i = 0; $i < $number1; $i++)
        $archives[] = new archive();

      for ($i = 0; $i < $number1; $i++)
        $archives[$i]->iniByUid($uids[$i]);

      
      for ($i = 0; $i < $number1; $i++) {
        if (!$this->multipleItemMatch($archives[$i], $this->current_archive)){
        unset($archives[$i]);       
        }
      }

      
      
      $archives = array_values($archives);
      //finsh the one side match.
      //match back!

      $number2 = count($archives);
     

      //match back type 1!!!
      for ($i = 0; $i < $number2; $i++) {
        if (!$this->peerMatch($this->current_archive, $archives[$i])){
          unset($archives[$i]);;
        }
      }
      $archives = array_values($archives);


      //score after match back.
      foreach ($archives as $a) {
        
          if ($a->rprofile->mtype == 2) {
             $scorePerItem=  $this->getTotalItem($a->rprofile->winfo);
             $scorePerItem=1/$scorePerItem;
          $scoreM = 0;
          $scoreM+= $this->singleItemsMatchForTypeTwo($this->current_archive, $a);
          $scoreM+=$this->multipleItemMatchForTypeTwo($this->current_archive, $a);
          $a->rscore = round($scoreM * $scorePerItem * 100);
          
           $score = $this->score($a, $this->current_archive);
           $a->score = $score;
        }
        else{
        $score = $this->score($a, $this->current_archive);
        $rscore = $this->score($this->current_archive, $a);
        $a->score = $score;
        $a->rscore = $rscore;
        //echo "<br>score: ".$a->score."<br>";
        //  echo "<br>rscore: ".$a->rscore."<br>";
        }
        
      }

      $_SESSION["archives"] = $archives;
      $this->archives = $archives;
    } //end of type 1
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //score type
    /*
     * 
     * 
     * type 2
     * 
     */
    if ($this->current_archive->rprofile->mtype == 2) {
      $total_item = 0;
      $mInSingle = 0;
      $mInMulti = 0;
      $row_array = array();
      $user_score = $this->current_archive->rprofile->score;
      $Multi_array = array("relationship", "ethnicity", "bodytype");

      //prepare score
      foreach ($this->current_archive->winfo as $type => $value) {
        if ($type == "wid")
          continue;
        if ($value == 1) {

          $total_item = $total_item + 2;
          if (in_array($type, $Multi_array))
              
            $mInMulti++;
          else
            $mInSingle++;
        }
        else
          $total_item++;
      }
      
     // var_dump($total_item);

      //  var_dump($this->current_archive->winfo);
      $scorePerItem = 1 / $total_item;
      $itemInMulti = (3 - $mInMulti) + 2 * $mInMulti;
      $scoreInMulti = $itemInMulti * $scorePerItem;
      $scoreInSingle = 1 - $scoreInMulti;

      //use it to screen user's single item score.
      //!!     $MinScoreInSingle= ($this->current_archive->rprofile->score/100)-$scoreInMulti;
      //do single item match, get array.
      foreach ($this->current_archive->winfo as $type => $value) {
        if ($type == "wid")
          continue;
        //   echo "<br>type:$type<br>";
        //prepare query
        $this->querytxt = file_get_contents("querytemp/mtype/$type.txt");
        $this->querytxt = str_replace("\n", "", $this->querytxt);
        $this->querytxt = str_replace("{id}", $loggedInUser->user_id, $this->querytxt);

        $stmt = $mysqli->prepare($this->querytxt);
        $stmt->execute();
        $stmt->bind_result(
            $uid
        );
        if ($value == 1) {
          while ($stmt->fetch()) {
            $row_array[] = $uid;
            $row_array[] = $uid;
            //     echo "<br>in f $uid: M hit $type<br>";    
          }
        }
        else {
          while ($stmt->fetch()) {
            $row_array[] = $uid;
        //     echo "<br>in f $uid : nM hit $type<br>";                 
          }
        }




        $stmt->close();
      }

      $uid_frequency = array_count_values($row_array);


      $i = 0;
      foreach ($uid_frequency as $u => $f) {

        if ($f * $scorePerItem >= $user_score / 100) {
          $archives[] = new archive();
          $archives[$i]->iniByUid($u);
     //     echo "<br>". $archives[$i]->profile->displayName."'s  f is". $f."<br>"; 

          $archives[$i]->score = round($f * $scorePerItem * 100);
          
          
          
        //  echo "<br>(by F)target ". $archives[$i]->profile->displayName."     in type 2"." score: ".$archives[$i]->score."/<br>";
          $i++;
          
         
        }
      }


      /*
       * 
       * match back for type 2, record score.
       */
      $number2 = count($archives);
      //echo "number after one-side match:" . $number2 . "<br>";
      for ($i = 0; $i < $number2; $i++) {
        //echo "<br>i users to Match Back:" . $archives[$i]->profile->displayName . "<br>";
        if (!$this->peerMatch($this->current_archive, $archives[$i]))
          unset($archives[$i]);
      }
      $archives = array_values($archives);

      
      //for score and rescore
      foreach ($archives as $a) {
        if ($a->rprofile->mtype == 2) {

          
          $score = 0;
          $score+= $this->singleItemsMatchForTypeTwo($this->current_archive, $a);
          $score+=$this->multipleItemMatchForTypeTwo($this->current_archive, $a);
          $a->rscore = round($score * $scorePerItem * 100);
        }
        
        // candidate is type 1;
        else {
          $rscore = $this->score($this->current_archive, $a);
          $a->rscore = $rscore;
     //     echo"<br>user ".$this->current_archive->profile->displayName."get score:$rscore  from".$a->profile->displayName."<br>";
        }
      }

      $_SESSION["archives"] = $archives;
      $this->archives = $archives;
    }// end of type 2
  }

  //$archive_profile is the archive of the person will be check.
  //$archive_rprofile is the archive of the person checking the other.
  function peerMatch($archive_profile, $archive_rprofile) {

    //echo "<br>var-dump from match back-rpro:";
    // var_dump($archive_rprofile->rprofile);
    //mtype==2
    if ($archive_rprofile->rprofile->mtype == 2) {
      $score = 0;
      $item = $this->getTotalItem($archive_rprofile->winfo);
      $scorePerItem = 1 / $item;
    //  echo "<br>before 1,2, item=".$item."<br>";
      $score+= $this->singleItemsMatchForTypeTwo($archive_profile, $archive_rprofile);


      $score+=$this->multipleItemMatchForTypeTwo($archive_profile, $archive_rprofile);
    

    // echo "<br>".$archive_profile->profile->displayName." get ".($score * $scorePerItem*100)." from ".$archive_rprofile->profile->displayName."<br>";
    // echo "<br>".$archive_rprofile->profile->displayName." 's "." inputted score is ".$archive_rprofile->rprofile->score."<br>";
     
     
   //  echo "<br>".($score * $scorePerItem *100)."<br>";
     //echo "<br>".$archive_rprofile->rprofile->score."<br>";
     
    //  if ($score * $scorePerItem*100 >= $archive_rprofile->rprofile->score)
     //   echo "<br>got score > require<br>";
      
     
     if ($score * $scorePerItem *100>= $archive_rprofile->rprofile->score)
        return true;
      else
        return false;
    
      
    }

    // mtype !=2 (1,NULL)
    if (!$this->singleItemsMatch($archive_profile, $archive_rprofile))
      return false;

    if (!$this->multipleItemMatch($archive_profile, $archive_rprofile))
      return false;

    return true;
  }

  function peerMatchForAuth() {

    $archive_rprofile = $this->current_archive;
    $archive_profile = $this->visitor;
    //echo "<br>var-dump from match back-rpro:";
    //var_dump($rprofile);

    if ($archive_rprofile->rprofile->mtype == 2) {
      $score = 0;
      $item = $this->getTotalItem($archive_rprofile->winfo);
      $scorePerItem = 1 / $item;
      $score+= $this->singleItemsMatchForTypeTwo($archive_profile, $archive_rprofile);

      $score+=$this->multipleItemMatchForTypeTwo($archive_profile, $archive_rprofile);
     
      //set score for visit(score for profile by vistor's requirement)
      $archive_rprofile->rscore=$score * $scorePerItem*100;
      
      

      if ($score * $scorePerItem*100 >= $archive_rprofile->rprofile->score)
        return true;
      else
        return false;
    }
    else {


      if (!$this->singleItemsMatch($archive_profile, $archive_rprofile))
        return false;

      if (!$this->multipleItemMatch($archive_profile, $archive_rprofile))
        return false;

       
      $archive_rprofile->rscore=$this->score($archive_profile, $archive_rprofile) ;
      return true;
    }
  }
  
    function peerMatchForScore() {

    $archive_rprofile = $this->visitor;
    $archive_profile =$this->current_archive ;
    //echo "<br>var-dump from match back-rpro:";
    //var_dump($rprofile);

    if ($archive_rprofile->rprofile->mtype == 2) {
      $score = 0;
      $item = $this->getTotalItem($archive_rprofile->winfo);
      $scorePerItem = 1 / $item;
      $score+= $this->singleItemsMatchForTypeTwo($archive_profile, $archive_rprofile);

      $score+=$this->multipleItemMatchForTypeTwo($archive_profile, $archive_rprofile);
     
      //set score for visit(score for profile by vistor's requirement)
      $archive_profile->score=$score * $scorePerItem*100;
      
      

      if ($score * $scorePerItem*100 >= $archive_rprofile->rprofile->score)
        return true;
      else
        return false;
    }
    else {


      if (!$this->singleItemsMatch($archive_profile, $archive_rprofile))
        return false;

      if (!$this->multipleItemMatch($archive_profile, $archive_rprofile))
        return false;

       
      $archive_profile->score=$this->score($archive_profile, $archive_rprofile) ;
      return true;
    }
  }

  function singleItemsMatch($archive_profile, $archive_rprofile) {
    // echo "<br>var-dump";
    //var_dump($rprofile->winfo);
    $profile = $archive_profile->profile;
    $rprofile = $archive_rprofile->rprofile;


    if ($rprofile->gender != $profile->gender)
      return false;

    if ($rprofile->winfo["age"] == 1)
      if ($rprofile->agefrom >= $profile->age || $rprofile->ageto <= $profile->age)
        return false;

    if ($rprofile->winfo["income"] == 1)
      if ($rprofile->income > $profile->income)
        return false;

    if ($rprofile->winfo["haskid"] == 1)
      if ($rprofile->haskid != $profile->haskid)
        return false;

    if ($rprofile->winfo["wantkid"] == 1)
      if ($rprofile->wantkid != $profile->wantkid)
        return false;

    if ($rprofile->winfo["height"] == 1)
      if ($rprofile->heightfrom >= $profile->height || $rprofile->heightto <= $profile->height)
        return false;

    if ($rprofile->winfo["education"] == 1)
      if ($rprofile->education > $profile->education)
        return false;

    if ($rprofile->winfo["smoke"] == 1)
      if ($rprofile->smoke != $profile->smoke)
        return false;

    if ($rprofile->winfo["drink"] == 1)
      if ($rprofile->drink != $profile->drink)
        return false;
    return true;
  }

  function singleItemsMatchForTypeTwo($archive_profile, $archive_rprofile) {
    // echo "<br>var-dump";
    //var_dump($rprofile->winfo);
    $profile = $archive_profile->profile;
    $rprofile = $archive_rprofile->rprofile;
    $matched = 0;

    if ($rprofile->gender != $profile->gender)
      return -1;


    if ($rprofile->agefrom <= $profile->age || $rprofile->ageto >= $profile->age){
      
      if ($rprofile->winfo["age"] == 1){
        $matched = $matched + 2;
      //    echo "<br>M hit age<br>";
      }
      else {
        $matched++;
    //       echo "<br>nM hit age<br>";
      }
      
      
      }
    if ($rprofile->income <= $profile->income){
      if ($rprofile->winfo["income"] == 1){
        $matched = $matched + 2;
   //   echo "<br>M hit income<br>";
        
      }
      else {
        $matched++;
     //    echo "<br>nM hit income<br>";
      }
    }

    if ($rprofile->haskid == $profile->haskid){
      if ($rprofile->winfo["haskid"] == 1){
      $matched = $matched + 2;
    //  echo "<br>M hit haskid<br>";
      
      }
      else {
        $matched++;
     //    echo "<br>nM hit haskid<br>";
      }
  }

    if ($rprofile->wantkid == $profile->wantkid){
      if ($rprofile->winfo["wantkid"] == 1){
      $matched = $matched + 2;
    //     echo "<br>M hit wantkid<br>";
      
      }
      else {
        $matched++;
    //     echo "<br>nM hit wantkid<br>";
      }
      
    }

    if ($rprofile->heightfrom <= $profile->height || $rprofile->heightto >= $profile->height){
      if ($rprofile->winfo["height"] == 1){
        $matched = $matched + 2;
    //    echo "<br>M hit height<br>";
        
      }
      else {
        $matched++;
     //     echo "<br>nM hit height<br>"; 
      }
    }


    if ($rprofile->education <= $profile->education){
      if ($rprofile->winfo["education"] == 1){
      $matched = $matched + 2;
   //    echo "<br>M hit education<br>";
      
      }
      else {
        $matched++;
     //    echo "<br>nM hit education<br>";
      }
      
    }


    if ($rprofile->smoke == $profile->smoke){
      if ($rprofile->winfo["smoke"] == 1){
      $matched = $matched + 2;
      
    //   echo "<br>M hit smoke<br>";
      }
      else {
        $matched++;
  //     echo "<br>nM hit smoke<br>";
      }
      
    }


    if ($rprofile->drink == $profile->drink){
      if ($rprofile->winfo["drink"] == 1){
      $matched = $matched + 2;
     //  echo "<br>M hit drink<br>";
      
      }
      else {
        $matched++;
     //    echo "<br>nM hit drink<br>";
      }
      
    }
  //  echo "<br>!!!In singletype2, ZHU is".$archive_rprofile->profile->displayName." get  $matched matches on".$archive_profile->profile->displayName."<br>";

    return $matched;
  }

  function multipleItemMatch($archive_profile, $archive_rprofile) {

    $profile = $archive_profile->profile;
    $rprofile = $archive_rprofile->rprofile;

    if ($rprofile->winfo["relationship"] == 1) {
      if (is_array($rprofile->relationship))
        $values = array_values($rprofile->relationship);
      else
        return false;
      if (!in_array($profile->relationship, $values))
        return false;
    }
    // var_dump($this->current_rprofile->ethnicity);
    if ($rprofile->winfo["ethnicity"] == 1) {
      if (is_array($rprofile->ethnicity))
        $values = array_values($rprofile->ethnicity);
      else
        return false;
      if (!in_array($profile->ethnicity, $values))
        return false;
    }

    if ($rprofile->winfo["bodytype"] == 1) {
      if (is_array($rprofile->bodytype))
        $values = array_values($rprofile->bodytype);
      else
        return false;
      if (!in_array($profile->bodytype, $values))
        return false;
    }
    return true;
  }

  function multipleItemMatchForTypeTwo($archive_profile, $archive_rprofile) {

    $profile = $archive_profile->profile;
    $rprofile = $archive_rprofile->rprofile;
    $matched = 0;


    if (is_array($rprofile->relationship)) {
      $values = array_values($rprofile->relationship);

      if (in_array($profile->relationship, $values)) {
        if ($rprofile->winfo["relationship"] == 1) {
          $matched = $matched + 2;
          
      //    echo "<br>M hit relationship<br>";
        }
        else{
     //    echo "<br>nM hit relationship<br>";
          $matched++;
        
        }
      }
    }



    if (is_array($rprofile->ethnicity)) {
      $values = array_values($rprofile->ethnicity);

      if (in_array($profile->ethnicity, $values)) {
        if ($rprofile->winfo["ethnicity"] == 1) {
          $matched = $matched + 2;
     //     echo "<br>M hit ethnicity<br>";
        }
        else{
     //     echo "<br>nM hit ethnicity<br>";
        $matched++;}
      }
    }



    if (is_array($rprofile->bodytype)) {
      $values = array_values($rprofile->bodytype);

      if (in_array($profile->bodytype, $values)) {
        if ($rprofile->winfo["bodytype"] == 1) {
     //     echo "<br>M hit bodytype<br>";
          $matched = $matched + 2;
        }
        else{
     //     echo "<br>nM hit bodytype<br>";
          $matched++;
        
        }
      }
    }


 //echo "<br>!!!In Multitype2, ZHU is".$archive_rprofile->profile->displayName." get  $matched matches on".$archive_profile->profile->displayName.""."<br>";

    return $matched;
  }

  //$archive_profile is the person being tested, taking the score.
  function score($archive_profile, $archive_rprofile) {

    $sin_score = $this->singleItemsScore($archive_profile, $archive_rprofile);
    $mul_score = $this->multipleItemScore($archive_profile, $archive_rprofile);
    $score = $sin_score + $mul_score;
    $return_score = round(($score / 11) * 100);

    return $return_score;
  }

//$archive_profile is the person being tested, taking the score.
  function singleItemsScore($archive_profile, $archive_rprofile) {
    // echo "<br>var-dump";
    //var_dump($rprofile->winfo);
    $profile = $archive_profile->profile;
    $rprofile = $archive_rprofile->rprofile;
    $score = 0;

   
      if ($rprofile->agefrom <= $profile->age && $rprofile->ageto >= $profile->age)
        $score++;


    
      if ($rprofile->income <= $profile->income)
        $score++;

 
      if ($rprofile->haskid == $profile->haskid)
        $score++;


      if ($rprofile->wantkid == $profile->wantkid)
        $score++;


      if ($rprofile->heightfrom <= $profile->height && $rprofile->heightto >= $profile->height)
        $score++;


      if ($rprofile->education <= $profile->education)
        $score++;


      if ($rprofile->smoke == $profile->smoke)
        $score++;


      if ($rprofile->drink == $profile->drink)
        $score++;

    return $score;
  }

  function multipleItemScore($archive_profile, $archive_rprofile) {

    $profile = $archive_profile->profile;
    $rprofile = $archive_rprofile->rprofile;
    $score = 0;


      if (is_array($rprofile->relationship)) {
        $values = array_values($rprofile->relationship);
        if (in_array($profile->relationship, $values))
          $score++;
      }
    


    // var_dump($this->current_rprofile->ethnicity);
   
      if (is_array($rprofile->ethnicity)) {
        $values = array_values($rprofile->ethnicity);
        if (in_array($profile->ethnicity, $values))
          $score++;
      }
      // echo "<br>in score eth<br>";
   

   
      if (is_array($rprofile->bodytype)) {
        $values = array_values($rprofile->bodytype);
        if (in_array($profile->bodytype, $values))
          $score++;
     
      // echo "<br>in score body<br>";
    }

    return $score;
  }

  function getTotalItem($winfo) {
    $total_item = 0;
    foreach ($winfo as $type => $value) {
      if ($type == "wid")
        continue;
      if ($value == 1) 

        $total_item = $total_item + 2;
      
      else
        $total_item++;
    }
   return $total_item;
    }



function mysort($field){
  
  switch ($field){
  case "score": usort($this->archives, array($this, "cmpscore")); break;
  case "rscore":usort($this->archives, array($this, "cmprscore"));break;
  case "distance":usort($this->archives, array($this, "cmpdistance")); break;
    
  default: break;
  }
  
}

function cmpscore($a, $b)
{
     if ($a->score == $b->score) {
        return 0;
    }
    return ($a->score < $b->score) ? 1 : -1;
}
function cmprscore($a, $b)
{
     if ($a->rscore == $b->rscore) {
        return 0;
    }
    return ($a->rscore < $b->rscore) ? 1 : -11;
}

function cmpdistance($a, $b)
{  
  $ox=$this->current_archive->profile->latitude;
  $oy=$this->current_archive->profile->longitude;
  $ax=$a->profile->latitude;
  $ay=$a->profile->longitude;
  $bx=$b->profile->latitude;
  $by=$b->profile->longitude;  
  
  $doa=($ax-$ox)*($ax-$ox)+($ay-$oy)*($ay-$oy);
  $dob=($bx-$ox)*($bx-$ox)+($by-$oy)*($by-$oy);
  if ($doa == $dob) {
        return 0;
    }
    return ($dob < $doa) ? 1 : -1;
}



  }
?>
