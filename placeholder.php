<?php
    $dbhandle = new PDO("sqlite:scrabble.sqlite") or die("Failed to open DB");
    if (!$dbhandle) die ($error);
    
    
    function generate_rack($n){
        $tileBag = "AAAAAAAAABBCCDDDDEEEEEEEEEEEEFFGGGHHIIIIIIIIIJKLLLLMMNNNNNNOOOOOOOOPPQRRRRRRSSSSTTTTTTUUUUVVWWXYYZ";
        $rack_letters = substr(str_shuffle($tileBag), 0, $n);
  
        $temp = str_split($rack_letters);
        sort($temp);
        return implode($temp);
    };
    
    
    $myrack = generate_rack(7);
    $racks = [];
    for($i = 0; $i < pow(2, strlen($myrack)); $i++){
	    $ans = "";
    	for($j = 0; $j < strlen($myrack); $j++){
		    //if the jth digit of i is 1 then include letter
		    if (($i >> $j) % 2) {
		     $ans .= $myrack[$j];
		    }
	    }
	    if (strlen($ans) > 1){
  	        $racks[] = $ans;	
	   }
    }
    $racks = array_unique($racks);
    // print_r($racks);
    echo json_encode($racks);
    
    
    //echo generate_rack(7);
    
?>