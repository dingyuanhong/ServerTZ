<?php

@header("content-Type: text/html; charset=utf-8"); //语言强制

//整数运算能力测试

function test_int()
{
	$timeStart = gettimeofday();
	for($i = 0; $i < 3000000; $i++)
	{
		$t = 1+1;
	}
	$timeEnd = gettimeofday();
	$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
	$time = round($time, 3)."秒";
	return $time;
}

//浮点运算能力测试
function test_float()
{
	//得到圆周率值
	$t = pi();
	$timeStart = gettimeofday();
	for($i = 0; $i < 3000000; $i++)
	{
		//开平方
		sqrt($t);
	}
	$timeEnd = gettimeofday();
	$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
	$time = round($time, 3)."秒";
	return $time;
}

//IO能力测试
function test_io()
{
	$fp = @fopen(PHPSELF, "r");
	$timeStart = gettimeofday();
	for($i = 0; $i < 10000; $i++)
	{
		@fread($fp, 10240);
		@rewind($fp);
	}
	$timeEnd = gettimeofday();
	@fclose($fp);
	$time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
	$time = round($time, 3)."秒";
	return($time);
}

if($_POST['act'] == "int" || $_GET['act']=="int")
{
	$valInt = test_int();
	echo $valInt;
}
elseif($_POST['act'] == "float" || $_GET['act']=="float")
{
	$valFloat = test_float();
	echo $valFloat;
}
elseif($_POST['act'] == "io" || $_GET['act']=="io")
{
	$valIo = test_io();
	echo $valIo;
}
//网速测试-开始
elseif($_POST['act']=="net" || $_GET['act']=="net")
{
?>
<script language="javascript" type="text/javascript">
	var acd1;
	acd1 = new Date();
	acd1ok=acd1.getTime();
</script>
<?php
	for($i=1;$i<=30*1000;$i++)
	{
		echo "<!--567890#########1#########2#########3#########4#########5#########6#########7#########8#########01234--->";
	}
?>
<script language="javascript" type="text/javascript">
	var acd2;
	acd2 = new Date();
	acd2ok=acd2.getTime();
	window.location = '?speed=' +(acd2ok-acd1ok)+'#w_networkspeed';
</script>
<?php
}
//网速测试-结束
function GetSpeed($micsecond)
{
	//网络速度测试
	$kb = 30*100;
	if($micsecond == "0"){
		$speed = 0;
	}elseif($micsecond > 0){
		$speed=round($kb/($micsecond/1000),2); //下载速度：$speed kb/s
	}else {
		return '未探测';
	}

	if(preg_match("/[^\d-., ]/",$speed))
	{
		$speed = 0;
	}

	if($speed / 1000 > 1)
	{
		$speed = round($speed / 1000,2);
		$speed .= 'MBPS';
	}else {
		$speed .= 'KBPS';
	}
	return $speed;
}

if(isset($_POST['speed']) || isset($_GET['speed']) )
{
	if(isset($_POST['speed']))
	{
		$micsecond = $_POST['speed'];
	}
	elseif(isset($_GET['speed']) )
	{
		$micsecond = $_GET['speed'];
	}

	echo GetSpeed($micsecond);
	echo '<div><a href="?act=net" style="width=20px;height=20px;">网络测试</a></div>';
}

?>
