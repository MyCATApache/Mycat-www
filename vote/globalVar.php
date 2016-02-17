<?php
require_once ('./api/OperatorFileText.php');

// 全局变量
$app_path = getcwd();

global $vote_arr;
global $cip;
global $rip;

$fileUtil = new file();
$newFile = $app_path . "/vote_rslt.ptp";

$fileContent = $fileUtil->readfromfile($newFile);
$fileContent = unescape($fileContent);


if (empty($fileContent)) {
 $vote_arr = array (
  array("1001", "null",0),
  array("1002", "null",0),
  array("1003", "null",0),
  array("1004", "null",0),
  array("1005", "null",0),
  array("1006", "null",0)
  );
}
else{
  $vote_arr = json_decode($fileContent);
}
$rip = get_ip_place();
$cip = get_ip_place_md5();
$str = json_encode($vote_arr);


function unescape($str){
  $ret = '';
  $len = strlen($str);
  for ($i = 0; $i < $len; $i++){
    if ($str[$i] == '%' && $str[$i+1] == 'u'){
      $val = hexdec(substr($str, $i+2, 4));
      if ($val < 0x7f) $ret .= chr($val);
      else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
      else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
      $i += 5;
    }
    else if ($str[$i] == '%'){
      $ret .= urldecode(substr($str, $i, 3));
      $i += 2;
    }
    else $ret .= $str[$i];
  }
  return $ret;
}

function get_ip_place_tmp(){     
  $ip=file_get_contents("http://txt.go.sohu.com/ip/soip");     
  $startIdx = strpos($ip, "window.sohu_user_ip=") + strlen("window.sohu_user_ip=");
  $endIdx = strpos($ip, ";sohu_IP_Loc=");
  return substr($ip, $startIdx, $endIdx - $startIdx);     
}

function get_ip_place(){   
  $ip=false;
  if(!empty($_SERVER["HTTP_CLIENT_IP"])){
    $ip = $_SERVER["HTTP_CLIENT_IP"];
  }
  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
    if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
    for ($i = 0; $i < count($ips); $i++) {
      if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
        $ip = $ips[$i];
        break;
      }
    }
  }
  return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);   
}


function get_ip_place_md5(){     
  $cip = get_ip_place();
  $cip = md5($cip);
  return $cip;	 
}
?>