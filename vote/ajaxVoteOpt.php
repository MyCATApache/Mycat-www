 <?php
     require_once ('./api/OperatorFileText.php');
     require_once ('./globalVar.php');
     header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
     $backValue=$_POST['trans_data'];
     $vote_arr[$backValue][2] = $vote_arr[$backValue][2] + 1;

     //$_SERVER["vote_arr"] = $vote_arr;
 ?>