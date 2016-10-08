<?php

require_once ('./api/OperatorVotingDB.php');
require_once ('./api/OperatorFileText.php');
require_once ('./globalVar.php');

//$cip = get_ip_place_md5();
//setcookie($cip, true, 1); 

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="./css/style.css">
        <!--link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine" -->
        <script type="text/javascript" src="./my.js"></script>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/json2.min.js"></script>
	
	<title>Mycat开源之星投票</title>
	
	<link rel="stylesheet" id="sytle-css" href="css/userinfo.css" type="text/css" media="all" >
	
<script language="javascript">

//如果是AJAX,则使用responseText来接受数据
var str='<?=$str?>';
var jip='<?=$cip?>';

//将PHP传来的字符串还原成数组
var vote_opts = JSON.parse(str);

var vote_opts2 = {0:{id:"1001", title:"PHP",num:0},
1:{id:"1002", title:"Ruby",num:0},
2:{id:"1003", title:"Java",num:0},
3:{id:"1004", title:"ASP",num:0},
4:{id:"1005", title:"Perl",num:0},
5:{id:"1006", title:"ColdFusion",num:0}};

$(document).ready(function() {
    var obj = $("#question");

	// 投票项排序
	var vote_opts2 = vote_opts;
    for (var i = 0; i < vote_opts.length; i++)
    {
		for (var j = i; j < vote_opts.length; j++)
		{
			if (vote_opts[i][2] > vote_opts[j][2])
			{
				var temp=vote_opts[i];
                vote_opts[i]=vote_opts[j];
                vote_opts[j]=temp;
			}
		}
	}
	
     // 加载投票项
    for (var idx in vote_opts)
    {
	    var domDiv = document.createElement("div");
		
		//$(domDiv).html('<span>' + vote_opts[idx][2] + '</span><a href="">投票</a><label style="background-color: #0099cc;color: #ffffff;margin: 0;" onclick="location.href=\'' + vote_opts[idx][3] + '\'">' + vote_opts[idx][1] + '</label>');
		
		$(domDiv).html('<span>' + vote_opts[idx][2] + '</span>'+
		 '<a href="" class="vflag">投票</a>' + 
		 '<a style="background-color: #0099cc;color: #ffffff;margin: 0;" href="#' + vote_opts[idx][3] + '">' + vote_opts[idx][1] + '</a>');
		var widthPX = 350 + 5 * vote_opts[idx][2] + "px";
		$(domDiv).css({width:widthPX});
		$(domDiv).attr('id', idx);
		
        obj.after(domDiv);
    }

    $("#container div a[class=vflag]").click(function() {
		//ajaxVoteOpt(vid);
		var cookieName = '<?=$cip?>';
		var cookie_val = getCookie(cookieName);
		if (cookie_val == 1){
		    alert("请不要重复投票!");
			return;
		}
		
		// 投票值加1
		var vid = $(this).parent().attr("id");
		if (!vid) return;
		vote_opts[vid][2] = vote_opts[vid][2] + 1;
		
        //ajaxVote(JSON.stringify(vote_opts[vid][0]), this);
		ajaxVote(vote_opts[vid][0], this);

        return false;
    });


function getCookie(cookie_name)
{
var allcookies = document.cookie;
var cookie_pos = allcookies.indexOf(cookie_name);   //索引的长度
// 如果找到了索引，就代表cookie存在，
// 反之，就说明不存在。
if (cookie_pos != -1){
	// 把cookie_pos放在值的开始，只要给值加1即可。
	cookie_pos += cookie_name.length + 1;      //这里我自己试过，容易出问题，所以请大家参考的时候自己好好研究一下。。。
	var cookie_end = allcookies.indexOf(";", cookie_pos);
	if (cookie_end == -1)
	{
	cookie_end = allcookies.length;
	}
	var value = unescape(allcookies.substring(cookie_pos, cookie_end)); //这里就可以得到你想要的cookie的值了。。。
}
return value;
}


    function ajaxVote(my_data, obj){
            // my_data=escape(my_data) + "";//编码，防止汉字乱码
             $.ajax({
                 url: "ajaxvote2.php",
                 type: "POST",
                 data:{trans_data:my_data},
                 //dataType: "json",
                 error: function(){
                     alert('Error loading XML document');
                 },
                 success: function(data,status){//如果调用php成功
                    // alert(unescape(data));//解码，显示汉字
					// alert('sucess!');
					if (data == ' 1')
					{
						alert('请不要重复投票!');
					}
					else{
						$(obj).parent().animate({
						   width: '+=5px'
						}, 500);

						$(obj).prev().html(parseInt($(obj).prev().html()) + 1);
					}
                 }
             });
        }
});

</script>
	
	<style type="text/css">
        #sidebar { width: 190px; position: fixed; left: 27%; top: 90%; margin: 0 0 0 110px; }
    </style>

    <!--[if IE 6]>
	   <style type="text/css">
	       html, body { height: 100%; overflow: auto; }
	       #sidebar { position: absolute; }
	       #page-wrap { margin-top: -5px; }
	       #ie6-wrap { position: relative; height: 100%; overflow: auto; width: 100%; }
	   </style>
    <![endif]-->
    
    <!--[if IE 7]>
	   <style type="text/css">
	       #sidebar { margin-top: -10px;  }
	       #page-wrap { margin-top: -5px; }
	   </style>
    <![endif]-->
	
	<style type="text/css">
    * {
        font-family: Arial, "Free Sans";
    }
    #container {
        color: #fff;
		background-color: #db7093;
		border-radius: 10px;
    }
    #container #question {
        display: block;
        margin-bottom: 20px;
        padding: 10px;
        font-size: 20px;
		width: 300px;
    }
    #container div {
        background: #0099cc;
        margin-bottom: 15px;
        padding: 10px;
        font-size: 20px;
        color: #ffffff;
        border-left: 20px solid #333;
        width: 250px;
        -webkit-border-radius: 0.5em;
        -moz-border-radius: 0 0.5em 0.5em 0;
        border-radius: 0 1.5em 1.5em 0;
    }
    #container div a {
        border-radius: 0.3em;
        text-decoration: none;
        color: #0099cc;
        padding: 5px 15px;
        background: #333;
        margin: 0 20px;
    }
    #container div a:hover {
        color: #fff;
    }
    #main {
        background: #0099cc;
        margin-top: 0;
        padding: 2px 0 4px 0;
        text-align: center;
    }
    #main a {
        color: #ffffff;
        text-decoration: none;
        font-size: 12px;
        font-weight: bold;
        font-family: Arial;
    }
    #main a:hover {
        text-decoration: underline;
    }
    body {
        margin: 0;
        padding: 0;
		margin-left: auto;
        margin-right: auto;
    }
    #text {
        margin: 0 auto;
        width: 500px;
        font-size: 12px;
        color: #0099cc;
        font-weight: bold;
        text-align: center;
        margin-top: 20px;
    }
	
	
	.usercls{
		border-radius: 10px;
		padding-left: 0px;
		background-color: #db7093; 
		height: 180px;
		padding: 0;
		border-radius: 10px;
	}
	.usercls div {
        margin-top: 10px;
		padding-top: 0;
		margin-bottom: 0px;
        padding-bottom: 0px;
    }
	.usercls img {
        border-radius: 10px 0px 0px 10px;
		height: 180px;
    }
	
	.usercls ul {
            padding-left: 10px;
    }
	.usercls ul li{
            line-height: 2;
    }
	
	.usercls ul li small{
           margin-left: 15px;
    }
a {
    color: black;
}	
a:hover {
	color:#fff;
}	    
</style>
</head>

<body>

    <div id="headerdesc" class="usercls" style="height: 180px;color:#fff;>
      <div style="margin: 0;padding: 5px 0px 0px 0px;width:90%;margin-left:auto;margin-right:auto;    line-height: 1.5;"><div><h2>活动介绍</h2>该投票活动，发起于<strong style="font-size: 18px;"><i>PMO顽石神</i></strong>，时间段从<i style="font-size: 18px;">2016-01-07到2016-02-07</i>，投票者范围包括：Mycat的开源参与者，Mycat的使用者，Mycat的Fans。被投票者主要是，Mycat的代码贡献者，有核心代码的开发，或者系统BUG的维护，或者功能补丁的实现，八仙过海各显神通，志同道合一舟共济。根据投票结果，我们将给予一定的物质奖励，不在物质的贵贱，主要是礼物鹅毛轻，情义泰山重。</div></div>
  </div>
  
  <div id="container">

      <span id="question">Mycat 11.1-12.31 开源之星投票</span>
      <!-- <div><span>0</span><a href="">投票</a>PHP</div>
      <div><span>0</span><a href="">投票</a>Ruby</div>
      <div><span>0</span><a href="">投票</a>Java</div>
      <div><span>0</span><a href="">投票</a>ASP</div>
      <div><span>0</span><a href="">投票</a>Perl</div>
      <div><span>0</span><a href="">投票</a>ColdFusion</div> -->
  </div>
  
  <div id="userinfo1" class="usercls" style="height:250px;color:#fff;">
      <div style="float:left;width: 20%;margin: 0;padding: 0;"><div><h4>作者简介：</h4><br/>2009年前从事系统管理工作，负责某电信项目的系统部署维护工作，2010年后从事JAVA 研发，主要在电信增值业务、互联网电商业务方面，技术面较广，从网络、系统、到JAVA前后端研发、手机APP客户端研发、分布式系统研发都有实际工作经验。希望自己不断提高，有更多的时间和能力为开源项目贡献一份力量。</div></div>
	  <div style="float:left;width: 15%;margin-top:0px;">
	    <div style="font-size: 13px;" >
		     <small ></small><small ><strong>作者：</strong>
<a href="https://github.com/MyCATApache/Mycat-openEP/commits?author=beijingcn" target="_blank">ILEX-beijingcn</a>
</small>
		 </div>
	     <div style=""><strong>任务名称：</strong>
			 <ul>
			  <li>1.ICE-GRID 服务；</li>
			  <li>2.MYCATEP 整合ICEGRID；</li>
			  <li>3.MYCATEP Docker 镜像</li>
			</ul>
		 </div>
	  </div>
	  <div style="float:left;width: 35%;margin-top:0px;">
	     <div style=""><strong>任务成果：</strong>
			 <ul>
			  <li>1.任务1-添加ICEGRID 服务到工程；<small ><strong>历时：</strong>2天</small></li>
			  <li>2.任务2-调整MyCatEp 工程方便部署ICEGRID 服务；<small ><strong>历时：</strong>1周</small></li>
			  <li>3.任务3-制作Docker 镜像<small ><strong>历时：</strong>3天</small></li>
			</ul>
		 </div>
	  </div>
  </div>
  
    <div id="userinfo2" class="usercls" style="height:150px;color:#fff;">
      <div style="float:left;width: 20%;margin: 0;padding: 0;"><div><h4>作者简介：</h4><br/>喜欢仙剑奇侠传,所以用里面的望舒剑作为网名，java程序员，略微懂点 C++/C,喜欢捣鼓新东西，现在在一湖南本土电商企业捣鼓营销中心。</div></div>
	  <div style="float:left;width: 70%;">
	     <div style="font-size: 13px;margin-top: 0px;" >
		      <small ></small><small ><strong>作者：</strong>
<a href="https://github.com/MyCATApache/Mycat-Server/commits?author=fireflyhoo" target="_blank">胡雅辉-fireflyhoo</a>
</small>
		 </div>
		 <div style=""><strong>任务名称：</strong>1.pg native</div>
		 <div style=""><strong>任务成果：</strong>1.pg native-贡献代码<small style="margin-left: 15px;"> <strong>历时：</strong>2一个月</small></div>
	  </div>
  </div>
  
  <div id="userinfo3" class="usercls" style="height:315px;color:#fff;">
      <div style="float:left;width: 20%;margin: 0;padding: 0;"><div><h4>作者简介：</h4><br/>从事软件开发10年，号称CODE、架构、管理、忽悠 等无一不通，北京打拼多年， 2014年9月回到合肥，因为产品的需要2015年10月我们在公司层面正式上线采用了mycat，实用过程中贡献了一点微不足道的的力量... 新的一年期待mycat 越来越强壮，参与的人更多，当然我们会持续提交，让mycat更智能</div></div>
	  <div style="float:left;width: 70%;">
	     <div style="font-size: 13px;margin-top: 0px;" >
		      <small ></small><small ><strong>作者：</strong>
<a href="https://github.com/MyCATApache/Mycat-Server/commits?author=zhuam" target="_blank">合肥-明明Ben-zhuam</a>
</small>
		 </div>
	     <div style=""><strong>任务成果：</strong>
			 <ul>
			  <li>1、支持读服务根据权重值 进行负载均衡；</li>
			  <li>2、新增功能，可设定在写服务挂掉后，读服务依然可用；</li>
			  <li>3、修复执行 DDL 语句，中间件会将结构转为大写的方式执行的BUG；</li>
			  <li>4、新的统计服务服务模块， 含三个维度的信息（1、用户维度， 2、数据表维度， 3、SQL访问维度 （如用户访问情况、高频SQL、慢查询等））</li>
			  <li>5、MySql压缩代码的重构；</li>
			  <li>6、增加账户访问的服务降级功能；</li>
			  <li>7、修复PHP字符集设置错误, 如： set names 'utf8' ；</li>
			  <li>8、新增 HintDataNodeHandler，修复HintSchemaHandler tempSchema 空指针错误 等；</li>
			  <li>9、相应 Show/Reload 指令的新增及重构</li>
			</ul>
		 </div>
	  </div>
  </div>
  <div id="userinfo4" class="usercls" style="height: 390px;color:#fff;">
      <div style="float:left;width: 20%;margin: 0;padding: 0;"><div><h4>作者简介：</h4><br/>大学时，曾经梦想着做大型网络游戏，狂热学习C++，读遍了当时所有的C++名著。然而毕业找工作，却掉入了Java的坑中。工作已6，7年。待过日企，做过web网游，维护过Oracle(10g)，目前从事MySQL DBA工作。技术很杂，几乎成了所谓的“全栈”。目前工作主要还是以维护MySQL为主，偶尔用Java打打酱油。
曾向Leader-us提过一些关于Mycat的建议，Leader-us忽悠我自己实现，so,加入了Mycat开源大家庭(提交代码不多，提交次数却不断上涨，惭愧...)。
本人热爱开源技术，尤其是MySQL相关技术，喜交流，有志趣相投者，请联系：digdeep@126.com；QQ:1981715364。</div></div>
	  <div style="float:left;width: 70%;">
	     <div style="font-size: 13px;margin-top: 0px;"  >
		     <small ></small><small ><strong>作者：</strong>
<a href="https://github.com/MyCATApache/Mycat-Server/commits?author=digdeep126" target="_blank">yuanfang-digdeep126</a>
</small>
		 </div>
	     <div style=""><strong>任务名称：</strong>
			 <ul>
			  <li>1.全局表一致性检测和SQL拦截改写；<small ><strong>历时：</strong>5天</small></li>
			  <li>2.重构CharsetUtil类，fix了collationIndex到charset的兼容性问题；<small ><strong>历时：</strong>2天</small></li>
			  <li>3.将LocalLoader类中多次读取和build配置文件mycat.xml优化成一次；<small ><strong>历时：</strong>1天</small></li>
			  <li>4.fix 全系列版本sharejoin bug，并且新增支持字符类型作为joinkey；<small ><strong>历时：</strong>2天</small></li>
			  <li>5.新增master/slave注解支持强制走master/slave的功能；<small ><strong>历时：</strong>2天</small></li>
			  <li>6.其它一些小功能和bug修复：<small ><strong>历时：</strong>3天</small>
				  <ul>
					  <li>1)将JDBCDatasource需要的多个数据库驱动配置文件化；</li>
					  <li>2)fix mysql client 中执行 exit 抛出空指针异常的bug；</li>
					  <li>3)fix CharsetUtil中遍历map的bug；</li>
					  <li>4)fix DB密码错误时导致的死循环而内存溢出的bug；</li>
					  <li>5)fix 登录DB失败的连接被放入了连接池的bug；</li>
				  </ul>
              </li>
			  <li><small ><strong>注：</strong>任务2,3,4,5,6都是修复自己发现的bug或问题，不是PMO发布的任务。</small></li>
			</ul>
		 </div>
	  </div>
  </div>
  <div id="userinfo5" class="usercls" style="color:#fff;" >
	  <div style="float:left;width: 20%;margin: 0;padding: 0;"><div><h4>作者简介：</h4><br/>已经做Java十六年了，拿过Oracle OCP。在公司主要负责系统架构，爱好接受新鲜事务，玩过python,ruby,css，js，android，html5。八字，风水，中医也都有研究。
年纪虽长，但入门Mycat算最晚。幸得Leader us的信任，负责Mycat eye的核心开发。希望能在Mycat社区共享自己的一份力。也打个小广告，在上海，有想招人的请联系我，QQ:1935326097</div></div>	  
	  <div style="float:left;width: 15%;margin-top:0px;">
	     <div style="font-size: 13px;" >
		     <small ></small><small ><strong>作者：</strong>
<a href="https://github.com/whyuan1976" target="_blank">上海-袁文华-whyuan1976</a>
</small>
		 </div>
	     <div style=""><strong>任务名称：</strong>
			 <ul>
			  <li>1.OpenEP工程搭建</li>
			  <li>2.ZeroC Ice调用学习；</li>
			  <li>3.Eye网络拓扑图相关调查；</li>
			  <li>4.Eye相关代码学习&整理；</li>
			</ul>
		 </div>
	  </div>
	  <div style="float:left;width: 35%;margin-top:0px;">
	     <div style=""><strong>任务成果：</strong>
			 <ul>
			  <li>1.1.代码问题提交；<small ><strong>历时：</strong>5天</small></li>
			  <li>2.2.暂无；<small ><strong>历时：</strong>3天</small></li>
			  <li>3.Sample提供；<small ><strong>历时：</strong>5天</small></li>
			  <li>4.整理中；<small ><strong>历时：</strong>10天</small></li>
			</ul>
		 </div>
	  </div>
	  
  </div>

</body>
<?php
require ("./footer.htm");
?>
</html>
