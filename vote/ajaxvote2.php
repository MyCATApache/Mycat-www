 <?php
     require_once ('./api/OperatorFileText.php');
     require_once ('./globalVar.php');
     header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
	 
	 $backValue=$_POST['trans_data'];
	 $fileUtil = new file();
	 
	 $ipFile = $app_path . "/ipFile.ptp";
	 $content = $fileUtil->readfromfile($ipFile);
	 
	//echo isset($_COOKIE[$cip]) ;
	//echo "<br>";
	//echo stripos($content, $rip);
	//echo "<br>";
	//echo  $rip;
	
		
	// 判断用户是否重复提交
	if(isset($_COOKIE[$cip]) || stripos($content, $rip))
    {
		echo "1";
	}
	else
	{
	    echo 0;
		//echo strnatcmp($backValue, "1004");
		//echo "<br/>";
		
		 for($i=0; $i<5; $i++){
			for($j=0;$j<3;$j++){
			   if ($vote_arr[$i][$j] == $backValue){
				   $vote_arr[$i][2] = $vote_arr[$i][2] + 1;
			   }
			}
		}
		
		

		 $newFile = $app_path . "/vote_rslt.ptp";
		 $oldFile = $app_path . "/vote_rslt_old.ptp";
		 
		 $fileUtil->copyFile($newFile, $oldFile);
		 $fileUtil->writetofile($newFile, json_encode($vote_arr));
		 
		 $fileUtil->writetofile($ipFile, $content . $rip . ';', true);
		 
		 $expire = time() + 86400 * 365; // 设置24小时的有效期
		 setcookie($cip, true, $expire); 
	}
	
     
 ?>