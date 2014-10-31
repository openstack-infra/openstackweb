<?php

function GetContentBetween($StringToParse,$StartMarker,$EndMarker) {

	// Look to see if our beginning marker is in the string	
	$StartMarkerPosition = strpos($StringToParse, $StartMarker);
	// Look to see if our end marker is in the string	
	$EndMarkerPosition = strpos($StringToParse, $EndMarker);
	
	// If we found both markers, proceed to truncate the string to just the content
	// between the markers
	if ($StartMarkerPosition === false && $EndMarkerPosition === false) {
	
		$Results = false;
	
	} else {
		
		$StartMarkerPosition = $StartMarkerPosition + strlen($StartMarker);
		$EndMarkerPosition = $EndMarkerPosition - $StartMarkerPosition;
		$Results = substr($StringToParse,$StartMarkerPosition,$EndMarkerPosition);
	}
	
	return $Results;

}


$url = file_get_contents('http://localhost:8888/feeds/developer-activity.php');
	
$url = strip_tags($url, '<a>');
	
echo GetContentBetween($url,'Author:','Date:');

?>

