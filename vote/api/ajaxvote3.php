 <?php
     require_once ('./api/OperatorFileText.php');
     require_once ('./globalVar.php');
     header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
	 
	 $cip = get_ip_place_md5();
	 
	 $backValue=$_POST['trans_data'];

     $fileUtil = new file();

     $newFile = $app_path . "/vote_rslt.ptp";
     $oldFile = $app_path . "/vote_rslt_old.ptp";
     $fileUtil->copyFile($newFile, $oldFile);
     $fileUtil->writetofile($newFile, $backValue);
	 
	 $expire = time() + 86400 * 365; // 设置24小时的有效期
	 setcookie($cip, true, $expire); 
	 
	 // 判断用户是否重复提交
	if(isset($_COOKIE[$cip]))
    {
		echo "1";
	}
	else
	{
	 echo "0";
	}
	
     
 ?>