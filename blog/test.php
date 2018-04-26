<?php

echo date('Y-m-d H:i:s',time());
echo '<hr/>';
$res = mysql_connect('101.200.190.17','zeroboy','2514782544sbsyg');
var_dump($res);
echo '<hr/>'; 
phpinfo();