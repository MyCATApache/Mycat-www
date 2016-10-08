<?php
require_once ('./globalVar.php');
echo get_ip_place();

require_once ('./api/OperatorFileText.php');

 $fileUtil = new file();
	 
$ipFile = $app_path . "/ipFile.ptp";

 $fileUtil->writetofile($ipFile, 'aaa;aaaaa' . ';', true);
	 
 ?>