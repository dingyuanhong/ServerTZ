<?php

$CPUInformationName=[
'user' => '用户态运行时间',
'nice' => 'nice负的进程运行时间',
'sys' => '核心态运行时间',
'idle' => '空闲时间',
'iowait' => 'IO等待时间',
'irq' => '硬中断时间',
'softirq' => '软中断时间'
];

$ServerConfigureName=[
'User' => '当前用户',
'Domain' => '域名',
'IP' => '服务器IP',
'Port' => '服务器端口',
'RemoteIP' => '访问者IP',
'ServerMark' => '标示',
'Root' => 'root目录',
'Engine' => '解释引擎',
'Language' => '语言',
'ScriptPath' => '探针路径',
'ServerAdmin' => '管理员邮箱',
'Localdomain' => '主机名',
'OSVersion' => '操作系统'
];

$ServerRealName=[
'uptime' => '上线时间',
'stime' => '系统当前时间'
];

$CPUHardWareName = [
'num' => '数量',
'model' => 'CPU型号',
'mhz' => '平率',
'cache' => '二级缓存',
'bogomips' => 'Bogomips'
];

$HardDiskName = [
'total' => '总空间',
'usable' => '空闲',
'used' => '已用',
'percent' => '使用率'
];

$MemoryName = [
'total' => '总大小',
'used' => '已用',
'free' => '释放',
'percent' => '使用率',
'buf' => 'Buffers缓冲'
];

$LoadAvgName = [
'loadAvg' => '系统平均负载'
];

$NetName = [
'name' => '名称',
'in' => '入网总流量',
'realin' => '实时入网流量',
'out' => '出网总流量',
'realout' => '实时出网流量',

'bytes' => '字节量',
'packets' => '收发包数',
'errs' => '错误包数',
'drop' => '丢包数',
'fifo' => '管道包数',
'frame' => '帧数',
'compressed' => '压缩包数',
'multicast' => '多播包数'
];

#http://blog.sina.com.cn/s/blog_560e31000101507h.html
$ConnectionsName = [
'Forwarding' => 'IP网关标示',
'DefaultTTL' => '默认TTL',
'InReceives' => '接收包总数',
'InHdrErrors' => '接收IP头出错丢弃包数',
'InAddrErrors' => '接收无效地址丢弃包数',
'ForwDatagrams' => '接收转发包数',
'InUnknownProtos' => '接收不明协议包数',
'InDiscards' => '接收并丢弃包数',
'InDelivers' => '接收并成功上传包数',
'OutRequests' => '发送到IP层请求包总数',
'OutDiscards' => '发送后丢弃的IP包数',
'OutNoRoutes' => '无路由而丢弃的IP包数',
'ReasmTimeout' => '包组装段最大等待时间',
'ReasmReqds' => '已接收IP包组装段数',
'ReasmOKs' => '成功组装的IP包数',
'ReasmFails' => '未成功组装的IP包数',
'FragOKs' => '成功分段的IP包数',
'FragFails' => '未成功分段的IP包数',
'FragCreates' => '请求分段的IP包数',

'RtoAlgorithm' => '计算超时的算法',
'RtoMin' => '允许最小重传TCP超时值',
'RtoMax' => '允许最大重传TCP超时值',
'MaxConn' => 'TCP流数量上限(默认-1)',
'ActiveOpens' => '主动连接数(connect)',
'PassiveOpens' => '被动连接数(listen)', //240秒增量
'AttemptFails' => '连接失败数',
'EstabResets' => '连接重置数',
'CurrEstab' => '当前连接数',
'InSegs' => '接收分片数',
'OutSegs' => '发送分片数',
'RetransSegs' => '重传分片数',  //TCP重传率 =重传分片数 / 发送分片数
'InErrs' => '接收错误数',
'OutRsts' => 'RST数',
// 'InCsumErrors' => 'checksum错误包数量',

'InDatagrams' => '收包数', //240秒增量
'NoPorts' => '未知端口收包数',
'InErrors' => '收包错误数',
'OutDatagrams' => '发包数',
'RcvbufErrors' => '接收缓冲溢出包数',
'SndbufErrors' => '发送缓冲溢出包数',
'InCsumErrors' => 'checksum错误包数量'
];



?>
