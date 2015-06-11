<?php
function rString($n=5){

     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $n; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;



}

function query_replace($tag, $replace,$querytxt) {
    //global $querytxt;
    $search = "#({" . $tag . ".*?}).*?({/" . $tag . "})#";
    $querytxt = preg_replace($search, $replace, $querytxt);
return $querytxt;
    
}


?>
