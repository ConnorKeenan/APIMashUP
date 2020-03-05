<?php
$master = "Whack it HARDER";

$fh = fopen("testALL.json", 'w')
      or die("Error opening output file");
fwrite($fh, "eqfeed_callback(");
fwrite($fh, json_encode($master));
?>