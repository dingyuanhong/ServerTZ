<?php

// 计时
function microtime_float()
{
	$mtime = microtime();
	$mtime = explode(' ', $mtime);
	return $mtime[1] + $mtime[0];
}

//单位转换
function formatsize($size)
{
	$danwei=array(' B ',' K ',' M ',' G ',' T ');
	$allsize=array();
	$i=0;
	for($i = 0; $i <5; $i++)
	{
		if(floor($size/pow(1024,$i))==0){break;}
	}

	for($l = $i-1; $l >=0; $l--)
	{
		$allsize1[$l]=floor($size/pow(1024,$l));
		$allsize[$l]=$allsize1[$l]-$allsize1[$l+1]*1024;
	}

	$len=count($allsize);

	for($j = $len-1; $j >=0; $j--)
	{
		$fsize=$fsize.$allsize[$j].$danwei[$j];
	}
	return $fsize;
}

#系统CPU使用率
function GetCoreInformation()
{
	$data = file('/proc/stat');
	$cores = array();
	foreach( $data as $line ) {
		if( preg_match('/^cpu[0-9]/', $line) )
		{
			$info = explode(' ', $line);
			$cores[]=array('user'=>$info[1],'nice'=>$info[2],'sys' => $info[3],'idle'=>$info[4],'iowait'=>$info[5],'irq' => $info[6],'softirq' => $info[7]);
		}
	}
	return $cores;
}

#当前CPU使用率
function GetCpuPercentages($stat1, $stat2)
{
	if(count($stat1)!==count($stat2)){
		return;
	}
	$cpus=array();
	for( $i = 0, $l = count($stat1); $i < $l; $i++) {
		$dif = array();
		#用户态运行时间
		$dif['user'] = $stat2[$i]['user'] - $stat1[$i]['user'];
		#
		$dif['nice'] = $stat2[$i]['nice'] - $stat1[$i]['nice'];
		#核心态运行时间
		$dif['sys'] = $stat2[$i]['sys'] - $stat1[$i]['sys'];
		#空闲时间
		$dif['idle'] = $stat2[$i]['idle'] - $stat1[$i]['idle'];
		#io等待时间
		$dif['iowait'] = $stat2[$i]['iowait'] - $stat1[$i]['iowait'];
		#硬中断时间
		$dif['irq'] = $stat2[$i]['irq'] - $stat1[$i]['irq'];
		#软终端时间
		$dif['softirq'] = $stat2[$i]['softirq'] - $stat1[$i]['softirq'];
		#运行总时间
		$total = array_sum($dif);
		$cpu = array();
		#计算百分比
		foreach($dif as $x=>$y)
			$cpu[$x] = round($y / $total * 100, 2);
		$cpus['cpu' . $i] = $cpu;
	}
	return $cpus;
}

#服务器参数
function GetServerConfigure()
{
	$Configure = (object)array();
	#用户
	$Configure->User = @get_current_user();
	#域名
	$Configure->Domain = $_SERVER['SERVER_NAME'];
	#服务器地址
	if('/'==DIRECTORY_SEPARATOR){
		$Configure->IP = $_SERVER['SERVER_ADDR'];
	}else{
		$Configure->IP = @gethostbyname($_SERVER['SERVER_NAME']);
	}
	#端口
	$Configure->Port = $_SERVER['SERVER_PORT'];
	#访问者地址
	$Configure->RemoteIP = $_SERVER['REMOTE_ADDR'];
	#服务器标示
	if($sysInfo['win_n'] != ''){
		$Configure->ServerMark=$sysInfo['win_n'];
	}else{
		$Configure->ServerMark=@php_uname();
	}
	#网站root目录
	$Configure->Root =  $_SERVER['DOCUMENT_ROOT']?str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']):str_replace('\\','/',dirname(__FILE__));

	#服务器解译引擎
	$Configure->Engine = $_SERVER['SERVER_SOFTWARE'];
	$Configure->Language = getenv("HTTP_ACCEPT_LANGUAGE");
	#脚本目录
	$Configure->ScriptPath=str_replace('\\','/',__FILE__)?str_replace('\\','/',__FILE__):$_SERVER['SCRIPT_FILENAME'];
	#服务管理员邮箱
	$Configure->ServerAdmin=$_SERVER['SERVER_ADMIN'];

	$os = explode(" ", php_uname());
	#服务器主机名
	if('/'==DIRECTORY_SEPARATOR ){
		$Configure->Localdomain=$os[1];
	}else{
		$Configure->Localdomain=$os[2];
	}
	#服务器操作系统
	$Configure->OS=$os[0];
	if('/'==DIRECTORY_SEPARATOR){
		$Configure->OSVersion=$os[2];
	}else{
		$Configure->OSVersion=$os[1];
	}

	return $Configure;
}

#linux 系统上线时间
function GetServerReal()
{
	date_default_timezone_set('Asia/Shanghai');//此句用于消除时间差

	$str = '';
	if (false === ($str = @file("/proc/uptime"))) return false;
        $str = explode(" ", implode("", $str));
        $str = trim($str[0]);
        $min = $str / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
	$sec = floor($str % 60);

	$sysInfo['uptime'] = '';
        if (intval($days) !== 0) $sysInfo['uptime'] = $days."天";
        if (intval($hours) !== 0) $sysInfo['uptime'] .= $hours."小时";
        if(intval($min) !== 0) $sysInfo['uptime'] .= $min."分钟";
	$sysInfo['uptime'] .= $sec."秒";

	$Configure = (object)array();
	$Configure->uptime = $sysInfo['uptime']; //在线时间
	$Configure->stime = date('Y-m-d H:i:s'); //系统当前时间

	return $Configure;
}

#cpu硬件信息
function GetCPUHardWare()
{
	$Configure = (object)array();

	if (false === ($str = @file("/proc/cpuinfo"))) return false;
	$str = implode("", $str);
	@preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
	@preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
	@preg_match_all("/cache\s+size\s{0,}\:+\s{0,}(([\d\.]+)\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
	@preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);

	if (false !== is_array($model[1]))
	{
		$res['num'] = sizeof($model[1]);
		if($res['num']==1)
			$x1 = '';
		else
			$x1 = ' ×'.$res['num'];
		$cache[1][0] = ' | 二级缓存:'.$cache[1][0];
		$bogomips[1][0] = ' | Bogomips:'.$bogomips[1][0];

		#名称
		$res['model'] = $model[1][0];
		#频率
		$res['mhz']  = $mhz[1][0];
		#缓存
		$res['cache']  = $cache[1][1];
		#bogomips
		$res['bogomips']  = $bogomips[1][1];
	}
	$Configure = $res;

	return $Configure;
}

#cpu使用率
function GetCPU()
{
	$stat1 = GetCoreInformation();
	$Configure = (object)array();
	$Configure->cpu = $stat1;
	#当前时间
	$Configure->time = strtotime(date('Y-m-d H:i:s'));
	return $Configure;
}

#获取1秒的CPU状态
function GetCPU_OneSecond()
{
	$stat1 = GetCoreInformation();
	sleep(1);
	// usleep(1000);
	$stat2 = GetCoreInformation();
	$data = GetCpuPercentages($stat1, $stat2);
	return $data;
}

#硬盘
function GetHardDisk()
{
	$Configure = (object)array();
	//硬盘
	$dt = round(@disk_total_space(".")/(1024*1024*1024),3); //总
	$df = round(@disk_free_space(".")/(1024*1024*1024),3); //可用
	$du = $dt-$df; //已用
	$hdPercent = (floatval($dt)!=0)?round($du/$dt*100,2):0;

	#单位G
	$Configure->total = $dt;
	$Configure->usable = $df;
	$Configure->used = $du;
	$Configure->percent = $hdPercent;

	return $Configure;
}

#内存
function GetMemory()
{
	if (false === ($str = @file("/proc/meminfo"))) return false;
	$str = implode("", $str);
	preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
	preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);

	$res['memTotal'] = round($buf[1][0]/1024, 2);
	$res['memFree'] = round($buf[2][0]/1024, 2);
	$res['memUsed'] = $res['memTotal']-$res['memFree'];
	$res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;

	$res['memCached'] = round($buf[3][0]/1024, 2);
	$res['memBuffers'] = round($buffers[1][0]/1024, 2);
	$res['memCachedPercent'] = (floatval($res['memCached'])!=0)?round($res['memCached']/$res['memTotal']*100,2):0; //Cached内存使用率

	$res['memRealUsed'] = $res['memTotal'] - $res['memFree'] - $res['memCached'] - $res['memBuffers']; //真实内存使用
	$res['memRealFree'] = $res['memTotal'] - $res['memRealUsed']; //真实空闲
	$res['memRealPercent'] = (floatval($res['memTotal'])!=0)?round($res['memRealUsed']/$res['memTotal']*100,2):0; //真实内存使用率

	$res['swapTotal'] = round($buf[4][0]/1024, 2);
	$res['swapFree'] = round($buf[5][0]/1024, 2);
	$res['swapUsed'] = round($res['swapTotal']-$res['swapFree'], 2);
	$res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;

	$sysInfo = $res;

	//判断内存如果小于1G，就显示M，否则显示G单位
	if($sysInfo['memTotal']<1024)
	{
		#物理内存
		$mem->total = $sysInfo['memTotal']." M";
		$mem->used = $sysInfo['memUsed']." M";
		$mem->free = $sysInfo['memFree']." M";
		$mem->percent = $sysInfo['memPercent']; //内存总使用率

		#缓存
		$cache->used = $sysInfo['memCached']." M";	//cache化内存
		$cache->buf = $sysInfo['memBuffers']." M";	//缓冲
		$cache->percent = $sysInfo['memCachedPercent']; //cache内存使用率

		#交换缓冲区
		$swap->total = $sysInfo['swapTotal']." M";
		$swap->used = $sysInfo['swapUsed']." M";
		$swap->free = $sysInfo['swapFree']." M";
		$swap->percent = $sysInfo['swapPercent'];

		#真实内存
		$real->used = $sysInfo['memRealUsed']." M"; //真实内存使用
		$real->free = $sysInfo['memRealFree']." M"; //真实内存空闲
		$real->percent = $sysInfo['memRealPercent']; //真实内存使用比率


	}
	else
	{
		#物理内存
		$mem->total = round($sysInfo['memTotal']/1024,3)." G";
		$mem->used = round($sysInfo['memUsed']/1024,3)." G";
		$mem->free = round($sysInfo['memFree']/1024,3)." G";
		$mem->percent = $sysInfo['memPercent']; //内存总使用率

		#缓存
		$cache->used = round($sysInfo['memCached']/1024,3)." G";
		$cache->buf = round($sysInfo['memBuffers']/1024,3)." G";
		$cache->percent = $sysInfo['memCachedPercent']; //cache内存使用率

		#交换缓冲区
		$swap->total = round($sysInfo['swapTotal']/1024,3)." G";
		$swap->used = round($sysInfo['swapUsed']/1024,3)." G";
		$swap->free = round($sysInfo['swapFree']/1024,3)." G";
		$swap->percent = $sysInfo['swapPercent'];

		#真实内存
		$real->used = round($sysInfo['memRealUsed']/1024,3)." G"; //真实内存使用
		$real->free = round($sysInfo['memRealFree']/1024,3)." G"; //真实内存空闲
		$real->percent = $sysInfo['memRealPercent']; //真实内存使用比率
	}

	$Configure = (object)array();

	$Configure->mem = $mem;
	$Configure->cache = $cache;
	$Configure->swap = $swap;
	$Configure->real = $real;
	return $Configure;
}

#负载
function GetLoadAvg()
{
	if (false === ($str = @file("/proc/loadavg"))) return false;
	$str = explode(" ", implode("", $str));
	$str = array_chunk($str, 4);
	// $res['loadAvg'] = implode(" ", $str[0]);

	$load = $str[0];	//系统负载
	$Configure = (object)array();
	$Configure->loadAvg = $load;
	return $Configure;
}

#获取网络协议流量
function GetNet()
{
	//网卡流量
	$ret = array();
	$NetOutSpeed = array();
	$NetInputSpeed = array();
	$NetInput = array();
	$NetOut = array();

	$index = 0;
	#流量统计
	if (false !== ($strs = @file("/proc/net/dev")))
	{
		for ($i = 2; $i < count($strs); $i++ )
		{
			preg_match_all( "/[^ \:\f\n\r\t\v]+/", $strs[$i], $info );

			$Receive = (object)array();
			$Receive->bytes = $info[0][1];
			$Receive->packets = $info[0][2];
			$Receive->errs = $info[0][3];
			$Receive->drop = $info[0][4];
			$Receive->fifo = $info[0][5];
			$Receive->frame = $info[0][6];
			$Receive->compressed = $info[0][7];
			$Receive->multicast = $info[0][8];

			$Transmit = (object)array();
			$Transmit->bytes = $info[0][9];
			$Transmit->packets = $info[0][10];
			$Transmit->errs = $info[0][11];
			$Transmit->drop = $info[0][12];
			$Transmit->fifo = $info[0][13];
			$Transmit->frame = $info[0][14];
			$Transmit->compressed = $info[0][15];
			$Transmit->multicast = $info[0][16];

			#名称
			$ret[$index]->name = $info[0][0];
			$ret[$index]->rec = $Receive;
			$ret[$index]->tra = $Transmit;

			$index++;
		}
	}
	return $ret;
}


#获取网络协议流量信息
function GetConnections()
{
	$ret = array();
	#/proc/net/snmp
	if (false !== ($strs = @file("/proc/net/snmp")))
	{
		$index = 0;
		for ($i = 0; $i < count($strs); $i++ )
		{
			preg_match_all( "/[^ \:\f\n\r\t\v]+/", $strs[$i], $infoHeader );
			$i++;

			if($infoHeader[0][0] === 'Icmp') continue;
			if($infoHeader[0][0] === 'IcmpMsg') continue;

			preg_match_all( "/[^ \:\f\n\r\t\v]+/", $strs[$i], $info );

			$size = count($infoHeader[0]);
			$ret[$index]['name'] = $info[0][0];
			for($j = 1 ; $j < $size;$j++)
			{
				$ret[$index][$infoHeader[0][$j]] = $info[0][$j];
			}
			$index++;
		}
	}
	return $ret;
}

#获取网络情况
function GetNetWork()
{
	//网卡流量
	$Configure = (object)array();
	$Configure->net = GetNet();
	$Configure->connections = GetConnections();
	#当前时间
	$Configure->time = strtotime(date('Y-m-d H:i:s'));
	return $Configure;
}

#网卡参数
#需root权限
function GetNetworkCard($name)
{
	$cmd = 'ethtool '. $name;
	$result = exec($cmd,$strs);
	echo $result;
	$ret = (object)array();
	for ($i = 0; $i < count($strs); $i++ )
	{
		preg_match_all( "/[^ \:\f\n\r\t\v]+/", $strs[$i], $info );

		if($info[0][0] == 'Speed')
		{
			$ret->Speed = $info[0][1];
		}else if($info[0][0] == 'Duplex')
		{
			$ret->Duplex = $info[0][1];
		}
	}
	return $ret;
}

#获取网卡流量
function GetNetPrecent()
{
	//网卡流量
	$Congigure = array();
	$NetOutSpeed = array();
	$NetInputSpeed = array();
	$NetInput = array();
	$NetOut = array();

	$index = 0;
	#流量统计
	if (false !== ($strs = @file("/proc/net/dev")))
	{
		for ($i = 2; $i < count($strs); $i++ )
		{
			preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );

			$NetOutSpeed[$index] = $info[10][0];
			$NetOut[$index]  = formatsize($info[10][0]);
			$NetInputSpeed[$index] = $info[2][0];
			$NetInput[$index] = formatsize($info[2][0]);

			$ret = (object)array();
			#名称
			$ret->name = $info[1][0];

			#入网
			$ret->in = $NetInput[$index];
			#入网实时
			$ret->realin = $NetInputSpeed[$index];
			#出网
			$ret->out = $NetOut[$index];
			#出网实时
			$ret->realout = $NetOutSpeed[$index];

			#获取网卡能力
			$Card = GetNetworkCard($info[1][0]);
			foreach ($Card as $key => $val) {
				$ret->$key = $val;
			}

			$Congigure[$index] = $ret;
			$index++;
		}
	}
	return $Congigure;
}

#获取进程文件数
#只能获取当前进程权限进程
function GetProcessFD($pid)
{
	$cmd = 'ls -l /proc/'.$pid.'/fd | wc -l';
	$total = exec($cmd,$strs);
	$cmd = 'ls -l /proc/'.$pid.'/fd | grep socket | wc -l';
	$socket = exec($cmd,$strs);
	$total = intval($total);
	if ($total > 0)
	{
		$total -= 1;
	}

	$socket = intval($socket);
	$Configure = (object)array();
	$Configure->total = $total;
	$Configure->socket = $socket;
	return $Configure;
}

#网络接口统计
#需root权限
function GetSocketFD()
{
	// $cmd = 'ss -nap';
	$cmd = 'ss -s';
	$total = exec($cmd,$strs);

	$result = (object)array();
	if(count($strs) > 0)
	{
		preg_match_all( "/[^ \f\n\r\t\v\(\)]+/", $strs[0], $info );
		$result->total = intval($info[0][1]);
		$result->kernel = intval($info[0][3]);

		preg_match_all( "/[^ \f\n\r\t\v\(\)\,\:]+/", $strs[1], $info );
		$tcp = (object)array();
		$tcp->total = intval($info[0][1]);
		$tcp->established = intval($info[0][3]);
		$tcp->closed = intval($info[0][5]);
		$tcp->orphaned  = intval($info[0][7]);
		$tcp->synrecv  = intval($info[0][9]);
		$tcp->timewait  = $info[0][11];
		$tcp->ports = intval($info[0][13]);
		$result->tcp = $tcp;

		for($j = 5; $j < count($strs); $j++)
		{
			preg_match_all( "/[^ \f\n\r\t\v\(\)\,\:]+/", $strs[$j], $info );
			if(count($info[0]) == 0){
				continue;
			}
			$ret = (object)array();
			$name = $info[0][0];
			$ret->name = $name;
			$ret->total = $info[0][1];
			$ret->ipv4 = $info[0][2];
			$ret->ipv6 = $info[0][3];
			$result->$name =  $ret;
		}
	}

	return $result;
}

#文件句柄数
function GetProcessInfo()
{
	//网卡流量
	$Configure = array();
	$ret =  exec('ps -aux',$strs);
	try{
		$index = 0;
		for ($i = 0; $i < count($strs); $i++ )
		{
			preg_match_all( "/[^ \f\n\r\t\v]+/", $strs[$i], $info );
			$pid = $info[0][1];
			if($pid == 'PID') continue;

			$pid = intval($pid);

			$process = (object)array();
			$process->user = $info[0][0];
			$process->pid = $pid;
			$process->cpu = $info[0][2]; //进程占用的CPU百分比
			$process->mem = $info[0][3]; //占用内存的百分比
			$process->vsz = $info[0][4]; //該进程使用的虚拟內存量（KB）
			$process->rss = $info[0][5]; //該進程占用的固定內存量（KB）（驻留中页的数量）
			$process->stat = $info[0][7]; //進程的狀態
			$process->start = $info[0][8]; //該進程被觸發启动时间
			$process->time = $info[0][9];  //該进程實際使用CPU運行的时间
			$process->name=$info[0][10];
			$size = count($info[0]);
			$param = '';
			if($size > 11)
			{
				for($j = 11; $j < $size;$j++)
				{
					if($param != '')
					{
						$param  =$param . ' ';
					}
					$param =$param . $info[0][$j];
				}
			}
			if ($param != ''){
				$process->param = $param;
			}

			$fd = GetProcessFD($pid);
			$process->fd = $fd;

			$Configure[$index] = $process;
			$index++;
		}
	}catch(Exception $e)
	{
		echo 'Message: ' .$e->getMessage();
	}
	return $Configure;
}

#系统限制信息
function GetUlimit()
{
	#$cmd = 'ulimit -a';
	#$total = exec($cmd,$strs);

	#PID最大数
	$cmd = 'cat /proc/sys/kernel/pid_max';
	$total = exec($cmd,$strs);
	$pid_max = intval($total);

	#进程最大数
	$cmd = 'ulimit -u';
	$total = exec($cmd,$strs);
	$process_max = intval($total);

	#线程栈大小
	$cmd = 'ulimit -s';
	$total = exec($cmd,$strs);
	$thread_stack_max = intval($total)*1024;

	#内核可分配最大文件描述符
	$cmd = 'cat /proc/sys/fs/file-max';
	$total = exec($cmd,$strs);
	$core_file_max = intval($total);
	
	#内核当前分配的文件描述符
	$cmd = 'cat /proc/sys/fs/file-nr';
	$total = exec($cmd,$strs);
	$arr = explode(" ", $total);
	$allicated_file = intval($arr[0]);
	$allocated_unused_file = intval($arr[1]);
	$allocated_max_file = intval($arr[2]);
	
	#单进程最大文件数
	$cmd = 'cat /proc/sys/fs/nr_open';
	$total = exec($cmd,$strs);
	$process_file_max = intval($total);

	#单进程最大文件数实际值
	$cmd = 'ulimit -Hn'; #硬
	$total = exec($cmd,$strs);
	$process_file_hard_max = intval($total);

	$cmd = 'ulimit -n'; #软
	$total = exec($cmd,$strs);
	$process_file_soft_max = intval($total);

	$Configure = (object)array();
	$Configure->pid_max = $pid_max;
	$Configure->process_max = $process_max;
	$Configure->thread_stack_max = $thread_stack_max;
	$Configure->file_max_core = $core_file_max;
	$Configure->file_max_process = $process_file_max;
	$Configure->file_max_process_hard = $process_file_hard_max;
	$Configure->file_max_process_soft = $process_file_soft_max;
	$Configure->file_allocated = $allicated_file;
	$Configure->file_allocated_unused = $allocated_unused_file;
	return $Configure;
}

function decodeUnicode($str)
{
	return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
	create_function(
		'$matches',
		'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
	),
	$str);
}

#异常
function myException($exception)
{
	echo $exception->getMessage();
}

set_exception_handler('myException');

if(function_exists('ob_gzhandler'))
{
	ob_start('ob_gzhandler');
}else{
	ob_start();
}

#php信息
if ($_GET['act'] == "configure")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetServerConfigure());
}
else if($_GET['act'] == "serverReal")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetServerReal());
}
else if($_GET['act'] == "net")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetNetPrecent());
}
else if($_GET['act'] == "cpu_hardware")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetCPUHardWare());
}
else if($_GET['act'] == "cpu_used")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetCoreInformation());
}
else if($_GET['act'] == "harddisk")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetHardDisk());
}
else if($_GET['act'] == "memory")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetMemory());
}
else if($_GET['act'] == "loagavg")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetLoadAvg());
}
else if($_GET['act'] == "connections")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetConnections());
}
else if($_GET['act'] == "process")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetProcessInfo());
}
else if($_GET['act'] == "ulimit")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetUlimit());
}
else if($_GET['act'] == "socket")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetSocketFD());
}
else{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	$strRet=json_encode(GetProcessInfo());
	echo $strRet;
	// echo strlen($strRet);
	// echo decodeUnicode($strRet);
}

?>
