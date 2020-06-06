<?php
    $dbhandle = new PDO("sqlite:scrabble.sqlite") or die("Failed to open DB");
    if (!$dbhandle) die ($error);
    
    
    function generate_rack($n, $dbhandle){
        
        //this returns horrible words
        $tileBag = "AAAAAAAAABBCCDDDDEEEEEEEEEEEEFFGGGHHIIIIIIIIIJKLLLLMMNNNNNNOOOOOOOOPPQRRRRRRSSSSTTTTTTUUUUVVWWXYYZ";
        $rack_letters = substr(str_shuffle($tileBag), 0, $n);
  
        $temp = str_split($rack_letters);
        sort($temp);
        return implode($temp);
        
        /*
        $query = "SELECT rack FROM racks WHERE length=7 ORDER BY RANDOM() LIMIT 1";
        $statement = $dbhandle->prepare($query);
        $statement->execute();
        $res = substr($statement->fetchAll(PDO::FETCH_ASSOC), 0, 7);
        
        $temp = str_split($res);
        sort($temp);
        return implode($temp);
        */
    };
    
    $temp = generate_rack(7, $dbhandle);

    $results = array();
   // array_push($results, $temp);
    
    $racks = [];
    for($i = 0; $i < pow(2, strlen($temp)); $i++){
	    $ans = "";
    	for($j = 0; $j < strlen($temp); $j++){
		    //if the jth digit of i is 1 then include letter
		    if (($i >> $j) % 2) {
		     $ans .= $temp[$j];
		    }
	    }
	    if (strlen($ans) > 2){
  	        $racks[] = $ans;	
	   }
    }
    
    $racks = array_unique($racks);

    for($i = 0; $i < count($racks); $i++){
        $query = "SELECT words FROM racks WHERE rack='$racks[$i]'";
        $statement = $dbhandle->prepare($query);
        $statement->execute();
        array_push($results, $statement->fetchAll(PDO::FETCH_ASSOC));
    }
    

    $results = array_filter($results);
   
   
   /*
   //not working, remove @@s and make new elements
    $results2 = array();
    for($i = 0; $i < count($results); $i++){
        if(strpos($results[$i], '@@')){
            $results2 = explode('@@', $results[$i]);//returns array
            //put the arrays back into individual elements, should only be a few iterations
            for($j = 0; $j < count($results2); $j++){
                array_push($results, $results2[$j]);
            }  
        }
        
    }
    
    $results = array_filter($results);
    
    */
    
    $fixedResults = array();
    for($i = 0; $i < count($results); $i++){
        if(is_null($results[$i])) {
            
        }
        else {
            array_push($fixedResults, $results[$i]);
        }
            
    }
    
    //add full word at end
    $query = "SELECT words FROM racks WHERE rack='$temp'";
    $statement = $dbhandle->prepare($query);
    $statement->execute();
    array_push($fixedResults, $statement->fetchAll(PDO::FETCH_ASSOC));
    
    $fixedResults = array_filter($fixedResults);

    array_push($fixedResults, $temp);
  
    
    echo json_encode($fixedResults);
?>