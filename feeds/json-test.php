<?php

$json = file_get_contents('https://api.launchpad.net/1.0/openstack?ws.op=getMergeProposals&status=Merged');	
var_dump(json_decode($json, true));

?>

