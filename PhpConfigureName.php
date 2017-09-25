<?php

@header("content-Type: text/html; charset=utf-8"); //语言强制

$PhpConfigureNameTable= [
'phpinfo' => 'PHP信息',
'version' => 'PHP版本',
'runtype' => 'PHP运行方式',
'memory_limit' => '脚本占用最大内存',
'safe_mode' => 'PHP安全模式',
'post_max_size' => 'POST方法提交最大限制',
'upload_max_filesize' => '上传文件最大限制',
'precision' => '浮点型数据显示的有效位数',
'max_execution_time' => '脚本超时时间',
'default_socket_timeout' => 'socket超时时间',
'doc_root' => 'PHP页面根目录',
'user_dir' => '用户根目录',
'enable_dl' => 'dl()函数',
'include_path' => '指定包含文件目录',
'display_errors' => '显示错误信息',
'register_globals' => '自定义全局变量',
'magic_quotes_gpc' => '数据反斜杠转义',
'short_open_tag' => '"<?...?>"短标签',
'asp_tags' => '"<% %>"ASP风格标记',
'ignore_repeated_errors' => '忽略重复错误信息',
'ignore_repeated_source' => '忽略重复的错误源',
'report_memleaks' => '报告内存泄漏',
'auto_magic_quotes_gpc' => '自动字符串转义',
'magic_quotes_runtime' => '外部字符串自动转义',
'allow_url_fopen' => '打开远程文件',
'register_argc_argv' => '声明argv和argc变量',
'cookie' => 'Cookie 支持',
'aspell_check_raw' => '拼写检查',
'BCMath' => '高精度数学运算',
'preg_match' => 'PREL相容语法',
'pdf_close' => 'PDF文档支持',
'snmpget' => 'SNMP网络管理协议',
'vm_adduser' => 'VMailMgr邮件处理',
'curl_init' => 'Curl支持',
'SMTP' => 'SMTP支持',
'SMTP_Addr' => 'SMTP地址',
'enable_functions' => '默认支持函数',
'disable_functions' => '被禁用的函数'
];

$PhpAssemblyNameTable= [
'ftp_login' => 'FTP支持',
'xml_set_object' => 'XML解析支持',
'session_start' => 'Session支持',
'socket_accept' => 'Socket支持',
'cal_days_in_month' => 'Calendar支持',
'allow_url_fopen' => '允许URL打开文件',
'gd' => 'GD库支持',
'gzclose' => '压缩文件支持',
'imap_close' => 'IMAP电子邮件系统函数库',
'JDToGregorian' => '历法运算函数库',
'preg_match' => '正则表达式函数库',
'wddx_add_vars' => 'WDDX支持',
'iconv' => 'Iconv编码转换',
'mb_eregi' => 'mbstring',
'bcadd' => '高精度数学运算',
'ldap_close' => 'LDAP目录协议',
'mcrypt_cbc' => 'MCrypt加密处理',
'mhash_count' => '哈稀计算'
];

$PhpTRDNameTable= [
'zend_version' => 'Zend版本',
'ZendGuardLoader' => 'ZendGuardLoader[启用]',
'ZendOptimizer' => 'Zend Optimizer',
'eAccelerator' => 'eAccelerator',
'ioncube' => 'ioncube',
'XCache' => 'XCache',
'APC' => 'APC'
];

$PhpDBNameTable= [
'mysql' => 'MySQL 数据库',
'ODBC' => 'ODBC 数据库',
'Oracle' => 'Oracle 数据库',
'mssql_close' => 'SQL Server 数据库',
'dbase_close' => 'dBASE 数据库',
'mSQL' => 'mSQL 数据库',
'SQLite' => 'SQLite 数据库',
'Hyperwave' => 'Hyperwave 数据库',
'PostgreSQL' => 'Postgre SQL 数据库',
'Informix' => 'Informix 数据库',
'DBA' => 'DBA 数据库',
'DBM' => 'DBM 数据库',
'FilePro' => 'FilePro 数据库',
'SyBase' => 'SyBase 数据库'
];

?>
