<?php
class file {
    function file() {
        //die("Class file can not instantiated!");
    }
    //创建目录
    function forcemkdir($path){
        if(!file_exists($path)){
            file::forcemkdir(dirname($path));
            mkdir($path,0777);
        }
    }
    //检测文件是否存在
    function iswriteable($file){
        $writeable=0;
        if(is_dir($file)){
            $dir=$file;
            if($fp=@fopen("$dir/test.txt",'w')){
                @fclose($fp);
                @unlink("$dir/test.txt");
                $writeable=1;
            }
        }else{
            if($fp=@fopen($file,'a+')){
                @fclose($fp);
                $writeable=1;
            }
        }
        return $writeable;
    }
	// 复制文件,并且覆盖旧文件
    function copyFile($file1, $file2){
        if (file_exists($file2)){
		    unlink($file2);
		}
        if (file_exists($file1)){
			if (copy($file1,$file2)) //把原文件重新命名
			{
				return true;
			}
	    }
        return false;
    }
	
    //删除当前目录下的文件或目录
    function cleardir($dir,$forceclear=false) {
        if(!is_dir($dir)){
            return;
        }
        $directory=dir($dir);
        while($entry=$directory->read()){
            $filename=$dir.'/'.$entry;
            if(is_file($filename)){
                @unlink($filename);
            }elseif(is_dir($filename)&$forceclear&$entry!='.'&$entry!='..'){
                chmod($filename,0777);
                file::cleardir($filename,$forceclear);
                rmdir($filename);
            }
        }
        $directory->close();
    }
    //删除当前目录及目录下的文件
    function removedir($dir){
        if (is_dir($dir) && !is_link($dir)){
            if ($dh=opendir($dir)){
                while (($sf= readdir($dh))!== false){
                    if('.'==$sf || '..'==$sf){
                        continue;
                    }
                    file::removedir($dir.'/'.$sf);
                }
                closedir($dh);
            }
            return rmdir($dir);
        }
        return @unlink($dir);
    }
    //复制文件
    function copydir($srcdir, $dstdir) {
        if(!is_dir($dstdir)) mkdir($dstdir);
        if($curdir = opendir($srcdir)) {
            while($file = readdir($curdir)) {
                if($file != '.' && $file != '..') {
                    $srcfile = $srcdir . '/' . $file;
                    $dstfile = $dstdir . '/' . $file;
                    if(is_file($srcfile)) {
                        copy($srcfile, $dstfile);
                    }
                    else if(is_dir($srcfile)) {
                        file::copydir($srcfile, $dstfile);
                    }
                }
            }
            closedir($curdir);
        }
    }
    //读取文件
    function readfromfile($filename) {
        if ($fp=@fopen($filename,'rb')) {
            if(PHP_VERSION >='4.3.0' && function_exists('file_get_contents')){
                return file_get_contents($filename);
            }else{
                flock($fp,LOCK_EX);
                $data=fread($fp,filesize($filename));
                flock($fp,LOCK_UN);
                fclose($fp);
                return $data;
            }
        }else{
            return '';
        }
    }
    //写入文件
    function writetofile($filename,$data, $isappend=false){
        if($fp=@fopen($filename,'wb')){
            if (PHP_VERSION >='4.3.0' && function_exists('file_put_contents')) {
				
				return @file_put_contents($filename, $data, FILE_APPEND);
				if (!$isappend)
				{
					return @file_put_contents($filename,$data);
				}
				else{
					return @file_put_contents($filename,$data, FILE_APPEND);
				}
                
            }else{
                flock($fp, LOCK_EX);
                $bytes=fwrite($fp, $data);
                flock($fp,LOCK_UN);
                fclose($fp);
                return $bytes;
            }
        }else{
            return '';
        }
    }
    //上传文件
    function uploadfile($attachment,$target,$maxsize=1024,$is_image=1){
        $result=array ('result'=>false,'msg'=>'upload mistake');
        if($is_image){
            $attach=$attachment;
            $filesize=$attach['size']/1024;
            if(0==$filesize){
                $result['msg'] = '上传错误';
                return $result;
            }
            if(substr($attach['type'],0,6)!='image/'){
                $result['msg'] ='格式错误';
                return $result;
            }
            if($filesize>$maxsize){
                $result['msg'] ='文件过大';
                return $result;
            }
        }else{
            $attach['tmp_name']=$attachment;
        }
        $filedir=dirname($target);
        file::forcemkdir($filedir);
        if(@copy($attach['tmp_name'],$target) || @move_uploaded_file($attach['tmp_name'],$target)){
            $result['result']=true;
            $result['msg'] ='上传成功';
        }
        if(!$result['result'] && @is_readable($attach['tmp_name'])){
            @$fp = fopen($attach['tmp_name'], 'rb');
            @flock($fp, 2);
            @$attachedfile = fread($fp, $attach['size']);
            @fclose($fp);
            @$fp = fopen($target, 'wb');
            @flock($fp,2);
            if(@fwrite($fp, $attachedfile)) {
                @unlink($attach['tmp_name']);
                $result['result']=true;
                $result['msg']= '上传失败';
            }
            @fclose($fp);
        }
        return $result;
    }
    function hheader($string, $replace = true, $http_response_code = 0){
        $string = str_replace(array("\r", "\n"), array('', ''), $string);
        if(emptyempty($http_response_code) || PHP_VERSION <'4.3'){
            @header($string, $replace);
        }else{
            @header($string, $replace, $http_response_code);
        }
        if(preg_match('/^\s*location:/is', $string)){
            exit();
        }
    }
    //下载文件
    function downloadfile($filepath,$filename=''){
        global $encoding;
        if(!file_exists($filepath)){
            return 1;
        }
        if(''==$filename){
            $tem=explode('/',$filepath);
            $num=count($tem)-1;
            $filename=$tem[$num];
            $filetype=substr($filepath,strrpos($filepath,".")+1);
        }else{
            $filetype=substr($filename,strrpos($filename,".")+1);
        }
        $filename ='"'.(strtolower($encoding) == 'utf-8' && !(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') === FALSE) ? urlencode($filename) : $filename).'"';
        $filesize = filesize($filepath);
        $dateline=time();
        file::hheader('date: '.gmdate('d, d m y h:i:s', $dateline).' gmt');
        file::hheader('last-modified: '.gmdate('d, d m y h:i:s', $dateline).' gmt');
        file::hheader('content-encoding: none');
        file::hheader('content-disposition: attachment; filename='.$filename);
        file::hheader('content-type: '.$filetype);
        file::hheader('content-length: '.$filesize);
        file::hheader('accept-ranges: bytes');
        if(!@emptyempty($_SERVER['HTTP_RANGE'])) {
            list($range) = explode('-',(str_replace('bytes=', '', $_SERVER['HTTP_RANGE'])));
            $rangesize = ($filesize - $range) > 0 ?  ($filesize - $range) : 0;
            file::hheader('content-length: '.$rangesize);
            file::hheader('http/1.1 206 partial content');
            file::hheader('content-range: bytes='.$range.'-'.($filesize-1).'/'.($filesize));
        }
        if($fp = @fopen($filepath, 'rb')) {
            @fseek($fp, $range);
            echo fread($fp, filesize($filepath));
        }
        fclose($fp);
        flush();
        ob_flush();
    }
    //返回文件类型
    function extname($filename){
        $pathinfo=pathinfo($filename);
        return strtolower($pathinfo['extension']);
    }
    function createaccessfile($path){
        if(!file_exists($path.'index.htm')){
            $content=' ';
            file::writetofile($path.'index.htm',$content);
        }
        if(!file_exists($path.'.htaccess')){
            $content='Deny from all';
            file::writetofile($path.'.htaccess',$content);
        }
    }
    //返回文件大小
    function getdirsize($filedir){
        $handle=opendir($filedir);
        while($filename=readdir($handle)){
            if ('.' != $filename && '..' != $filename){
                $totalsize += is_dir($filedir.'/'.$filename) ? file::getdirsize($filedir.'/'.$filename) : (int)filesize($filedir.'/'.$filename);
            }
        }
        return $totalsize;
    }
}
?>