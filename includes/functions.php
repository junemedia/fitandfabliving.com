<?php

/**
* Define the cache functions.
*/


function getCache($cacheFile,$cacheLife){
    // Get the cache
    if(file_exists($cacheFile) && ((time() - fileatime($cacheFile)) < $cacheLife)){
        // Cache exist and we will load the cache
        // ini_set('memory_limit','1024M');
        $content = include $cacheFile;
        $content = unserialize($content);
        return $content;
    }else{
        return false;
    }
}

function saveCache($cacheFile, $content){
    // Let's serialize the vars first
    $content = serialize($content);
    // Delete the old cache
    if(file_exists($cacheFile))unlink($cacheFile);
    // Save the cache
    $content = var_export($content, true);
    $content = '<?php return '.$content.'; ?>';
    $cacheSave = file_put_contents($cacheFile, $content);
}


function getplaintextintrofromhtml($html, $numchars) {
	//Remove "{source}" tags
	$start = strpos($html,'{source}');
	if($start)
	{
		$end = strpos($html,'{/source}');
		$length = $end + 9 - $start;
		$html = substr_replace($html,'',$start,$length);
	}
	
    // Remove the HTML tags
    $html = strip_tags($html);
	
    // Convert HTML entities to single characters
    $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');	
	
    // Make the string the desired number of characters
    // Note that substr is not good as it counts by bytes and not characters
    $html = mb_substr($html, 0, $numchars, 'UTF-8');

    // Add an elipsis
    $html .= "…";

    return $html;
}



function saveSqlLog($time,$sql, $link){
    // Save the sql logs
    
    // We don't have to save the sql query that runs more than 0.1 seconds
    if($time < 0.1) return true;
    
    $query = "INSERT INTO `log_sql` 
            (`id` ,`time` ,`runtime` ,`qs`) VALUES (
            NULL , NOW(), '$time', \"" . $sql . "\")";
    //$query = addslashes($query);
    //echo $query . "<hr>";
    $r = mysqli_query($link, $query);
    return $r;    
}
?>