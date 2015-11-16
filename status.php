<?php

echo 'php is installed<br />';
echo shell_exec('service mongod status');
echo '<br />';
echo shell_exec('service apache2 status');
echo '<br />';

?>
