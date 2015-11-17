<?php

$files = shell_exec('ls');
$files = explode("\n", $files);

foreach ($files as $var) {
   echo $var;
   echo '<br />';
}

?>
