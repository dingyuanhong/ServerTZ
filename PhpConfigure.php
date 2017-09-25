<?php

#获取参数配置
function GetCFG($varName)
{
	switch($result = get_cfg_var($varName))
	{
		case 0:
			return '×';
		break;
		case 1:
			return '√';
		break;
		default:
			return $result;
		break;
	}
}

// 检测函数支持
function isfunction($funName = '')
{
	if (!$funName || trim($funName) == '' || preg_match('~[^a-z0-9\_]+~i', $funName, $tmp)) return 'error';
	return (false !== function_exists($funName)) ? '√' : '×';
}

#获取php配置
function GetPHPConfigure()
{
	$Configure = (object)array();

	#脚本文件名称
	$phpSelf = $_SERVER[PHP_SELF] ? $_SERVER[PHP_SELF] : $_SERVER[SCRIPT_NAME];
	#禁用函数列表
	$disable_functions=get_cfg_var("disable_functions");
	$default_functions=get_defined_functions();

	#检查phpinfo是否被禁用
	$Configure->phpinfo = (false!==eregi("phpinfo",$disable_functions))? '×':'√';
	#php版本
	$Configure->php_version = PHP_VERSION;
	#运行方式
	$Configure->runtype = strtoupper(php_sapi_name());

	#脚本最大内存
	$Configure->memory_limit = GetCFG("memory_limit");
	#安全模式
	$Configure->safe_mode = GetCFG("safe_mode");
	#POST方法提交最大限制
	$Configure->post_max_size =  GetCFG("post_max_size");
	#上传文件最大限制
	$Configure->upload_max_filesize =  GetCFG("upload_max_filesize");
	#浮点型数据显示的有效位数
	$Configure->precision =  GetCFG("precision");
	#脚本超时时间
	$Configure->max_execution_time =  GetCFG("max_execution_time");
	#socket超时时间
	$Configure->default_socket_timeout =  GetCFG("default_socket_timeout");
	#PHP页面根目录
	$Configure->doc_root =  GetCFG("doc_root");
	#用户根目录
	$Configure->user_dir =  GetCFG("user_dir");
	#dl()函数
	$Configure->enable_dl =  GetCFG("enable_dl");
	#指定包含文件目录
	$Configure->include_path =  GetCFG("include_path");
	#显示错误信息
	$Configure->display_errors =  GetCFG("display_errors");
	#自定义全局变量
	$Configure->register_globals =  GetCFG("register_globals");
	#数据反斜杠转义
	$Configure->magic_quotes_gpc =  GetCFG("magic_quotes_gpc");
	#"<?...?\>"短标签
	$Configure->short_open_tag =  GetCFG("short_open_tag");
	#"<% %>"ASP风格标记
	$Configure->asp_tags =  GetCFG("asp_tags");
	#忽略重复错误信息
	$Configure->ignore_repeated_errors =  GetCFG("ignore_repeated_errors");
	#忽略重复的错误源
	$Configure->ignore_repeated_source =  GetCFG("ignore_repeated_source");
	#报告内存泄漏
	$Configure->report_memleaks =  GetCFG("report_memleaks");
	#自动字符串转义
	$Configure->auto_magic_quotes_gpc =  GetCFG("magic_quotes_gpc");
	#外部字符串自动转义
	$Configure->magic_quotes_runtime =  GetCFG("magic_quotes_runtime");
	#打开远程文件
	$Configure->allow_url_fopen =  GetCFG("allow_url_fopen");
	#声明argv和argc变量
	$Configure->register_argc_argv =  GetCFG("register_argc_argv");

	#Cookie 支持
	$Configure->cookie =  isset($_COOKIE)?'√' : '×';
	#拼写检查
	$Configure->aspell_check_raw = isfunction("aspell_check_raw");
	#高精度数学运算
	$Configure->BCMath =  isfunction("bcadd");
	#PREL相容语法
	$Configure->preg_match =  isfunction("preg_match");
	#PDF文档支持
	$Configure->pdf_close =  isfunction("pdf_close");
	#SNMP网络管理协议
	$Configure->snmpget =  isfunction("snmpget");
	#VMailMgr邮件处理
	$Configure->vm_adduser =  isfunction("vm_adduser");
	#Curl支持
	$Configure->curl_init =  isfunction("curl_init");
	#SMTP支持
	$Configure->SMTP =  get_cfg_var("SMTP")?'√' : '×';
	#SMTP地址
	$Configure->SMTP_Addr =  get_cfg_var("SMTP")?get_cfg_var("SMTP"):'×';

	#默认支持函数
	$Configure->default_functions = empty($default_functions)?'×' : '√';
	#被禁用的函数
	$Configure->disable_finctions = empty($disable_finctions)?'×' : '√';

	return $Configure;
}

#获取php配置字段名称
function GetPHPConfigureName($type)
{
	include 'PhpConfigureName.php';
	if(in_array($type,$PhpConfigureNameTable))
	{
		return $PhpConfigureNameTable[$type];
	}else {
		return '';
	}
}

#php组件支持
function GetPHPAssembly()
{
	$Configure = (object)array();
	#FTP支持
	$Configure->ftp_login = isfunction("ftp_login");
	#XML解析支持
	$Configure->xml_set_object = isfunction("xml_set_object");
	#Session支持
	$Configure->session_start = isfunction("session_start");
	#Socket支持
	$Configure->socket_accept = isfunction("socket_accept");
	#Calendar支持
	$Configure->cal_days_in_month = isfunction("cal_days_in_month");
	#允许URL打开文件
	$Configure->allow_url_fopen = GetCFG("allow_url_fopen");
	#GD库支持
	if(function_exists(gd_info)) {
		$gd_info = @gd_info();
		$Configure->gd = $gd_info["GD Version"];
	}else{
		$Configure->gd = '×';
	}
	#压缩文件支持
	$Configure->gzclose = isfunction("gzclose");
	#IMAP电子邮件系统函数库
	$Configure->imap_close = isfunction("imap_close");
	#历法运算函数库
	$Configure->JDToGregorian = isfunction("JDToGregorian");
	#正则表达式函数库
	$Configure->preg_match = isfunction("preg_match");
	#WDDX支持
	$Configure->wddx_add_vars = isfunction("wddx_add_vars");
	#Iconv编码转换
	$Configure->iconv = isfunction("iconv");
	#mbstring
	$Configure->mb_eregi = isfunction("mb_eregi");
	#高精度数学运算
	$Configure->bcadd = isfunction("bcadd");
	#LDAP目录协议
	$Configure->ldap_close = isfunction("ldap_close");
	#MCrypt加密处理
	$Configure->mcrypt_cbc = isfunction("mcrypt_cbc");
	#哈稀计算
	$Configure->mhash_count = isfunction("mhash_count");

	return $Configure;
}

#三方支持
function GetPHPtrd()
{
	$Configure = (object)array();
	#Zend版本
	$zend_version = zend_version();
	if(empty($zend_version)){
		$Configure->zend_version = '×';
	}else{
		$Configure->zend_version = $zend_version;
	}

	#ZendGuardLoader[启用]
	$PHP_VERSION = PHP_VERSION;
	$PHP_VERSION = substr($PHP_VERSION,2,1);

	if($PHP_VERSION > 2){
		$Configure->zend_name = 'ZendGuardLoader[启用]';
		$Configure->zend_enable = get_cfg_var("zend_loader.enable")?'√':'×';
	} else{
		$Configure->zend_name = 'Zend Optimizer';
		if(function_exists('zend_optimizer_version')){
			$Configure->zend_enable = zend_optimizer_version();
		}
		else{
			$Configure->zend_enable = (get_cfg_var("zend_optimizer.optimization_level")
			||get_cfg_var("zend_extension_manager.optimizer_ts")
			||get_cfg_var("zend.ze1_compatibility_mode")
			||get_cfg_var("zend_extension_ts"))
			?'√':'×';
		}
	}

	#eAccelerator
	if((phpversion('eAccelerator'))!=''){
		$Configure->eAccelerator = phpversion('eAccelerator');
	}else{
		$Configure->eAccelerator = '×';
	}
	#ioncube
	if(extension_loaded('ionCube Loader')){
		$ys = ioncube_loader_iversion();
		$gm = ".".(int)substr($ys,3,2);
		$Configure->ioncube = ionCube_Loader_version().$gm;

	}else{
		$Configure->ioncube = '×';
	};
	#XCache
	if((phpversion('XCache'))!=''){
		$Configure->XCache = phpversion('XCache');
	}else{
		$Configure->XCache = '×';
	};
	#APC
	if((phpversion('APC'))!=''){
		$Configure->APC = phpversion('APC');
	}else{
		$Configure->APC = '×';
	 }

	return $Configure;
}

#数据库
function GetPHPDB()
{
	$Configure = (object)array();
	#MySQL
	$Configure->mysql = isfunction("mysql_close");
	if(function_exists("mysql_get_server_info")) {
            $s = @mysql_get_server_info();
	    $Configure->mysql_version = $s ? $s:'';
	    $Configure->mysql_client_version = @mysql_get_client_info();
        }

	#odbc
	$Configure->odbc_close = isfunction("odbc_close");
	#oracle
	$Configure->ora_close = isfunction("ora_close");
	#SQL Server
	$Configure->mssql_close = isfunction("mssql_close");
	#dBASE
	$Configure->dbase_close = isfunction("dbase_close");
	#MSQL
	$Configure->msql_close = isfunction("msql_close");
	#sqlite3
	if(extension_loaded('sqlite3')) {
		$sqliteVer = SQLite3::version();
		$Configure->SQLite =  '√';
		$Configure->SQLiteVersion = $sqliteVer[versionString];
	}else {
		$Configure->SQLite =  isfunction("sqlite_close");
		if(isfunction("sqlite_close") == '√') {
			$Configure->SQLiteVersion = @sqlite_libversion();
		}
	}
	#hyperwave
	$Configure->Hyperwave = isfunction("hw_close");
	#PostgreSQL
	$Configure->PostgreSQL = isfunction("pg_close");
	#informix
	$Configure->Informix = isfunction("ifx_close");
	//dba
	$Configure->DBA = isfunction("dba_close");
	#dbm
	$Configure->DBM = isfunction("dbmclose");
	#filepro
	$Configure->FilePro = isfunction("filepro_fieldcount");
	#syBase
	$Configure->SyBase = isfunction("sybase_close");

	return $Configure;
}

#php信息
if ($_GET['act'] == "phpinfo")
{
	@header("content-Type: text/html; charset=utf-8"); //语言强制

	phpinfo();
	exit();
}
#编译模块
else if($_GET['act'] == "extensions")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	$Extensions=get_loaded_extensions();
	echo json_encode($Extensions);
}
#支持函数
else if($_GET['act'] == "default_functions")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制

	$Functions=get_defined_functions();
	// echo json_encode($Functions);
	print_r($Functions);
}
#禁用函数
else if($_GET['act'] == "disable_finctions")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制

	$disableFunctions=get_cfg_var("disable_functions");
	// echo json_encode($disableFunctions);
	// echo htmlspecialchars($strRet);
	print_r($disableFunctions);
}
#configure名称
else if($_GET['act'] == "configurename")
{
	@header("content-Type: text/html; charset=utf-8"); //语言强制
	include 'PhpConfigureName.php';
	$strRet = json_encode($PhpConfigureNameTable);
	// echo strlen($strRet);
	echo htmlspecialchars($strRet);
}
else if($_GET['act'] == "configure")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	$strRet = json_encode(GetPHPConfigure());
	// echo htmlspecialchars($strRet);
	echo $strRet;
}
else if($_GET['act'] == "assembly")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	$strRet = json_encode(GetPHPAssembly());
	// echo htmlspecialchars($strRet);
	echo $strRet;
}
else if($_GET['act'] == "trd")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	$strRet = json_encode(GetPHPtrd());
	// echo htmlspecialchars($strRet);
	echo $strRet;
}
else if($_GET['act'] == "db")
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	$strRet = json_encode(GetPHPDB());
	// echo htmlspecialchars($strRet);
	echo $strRet;
}
#函数检测
elseif ($_GET['act'] == 'functionTest')
{
	@header("content-Type: text/html; charset=utf-8"); //语言强制
	$funcName = $_GET['funcName'];
	$funRet = "函数".$funcName."支持状况检测结果：".isfunction($funcName);
	echo htmlspecialchars($funRet);
}
elseif ($_GET['act'] == 'functionTest' || $_POST['act'] == 'functionTest')
{
	@header("content-Type: text/html; charset=utf-8"); //语言强制
	$funcName = $_POST['funcName'];
	$funRet = "函数".$funcName."支持状况检测结果：".isfunction($funcName);
	echo htmlspecialchars($funRet);
}
//MySQL检测
elseif ($_GET['act'] == 'sqlTest')
{
	@header("content-Type: text/html; charset=utf-8"); //语言强制
	$host = isset($_GET['host']) ? trim($_GET['host']) : '';
	$port = isset($_GET['port']) ? (int) $_GET['port'] : '';
	$login = isset($_GET['login']) ? trim($_GET['login']) : '';
	$password = isset($_GET['password']) ? trim($_GET['password']) : '';
	$host = preg_match('~[^a-z0-9\-\.]+~i', $host) ? '' : $host;
	$port = intval($port) ? intval($port) : '';
	$login = preg_match('~[^a-z0-9\_\-]+~i', $login) ? '' : htmlspecialchars($login);
	$password = is_string($password) ? htmlspecialchars($password) : '';

	if(function_exists("mysql_close")==1) {
		$link = @mysql_connect($host.":".$port,$login,$password);
		if ($link){
			echo "连接到MySql数据库正常！";
		} else {
			echo "无法连接到MySql数据库！";
		}
	} else {
		echo "服务器不支持MySQL数据库！";
	}
}
else if ($_POST['act'] == 'sqlTest')
{
	@header("content-Type: text/html; charset=utf-8"); //语言强制
	$host = isset($_POST['host']) ? trim($_POST['host']) : '';
	$port = isset($_POST['port']) ? (int) $_POST['port'] : '';
	$login = isset($_POST['login']) ? trim($_POST['login']) : '';
	$password = isset($_POST['password']) ? trim($_POST['password']) : '';
	$host = preg_match('~[^a-z0-9\-\.]+~i', $host) ? '' : $host;
	$port = intval($port) ? intval($port) : '';
	$login = preg_match('~[^a-z0-9\_\-]+~i', $login) ? '' : htmlspecialchars($login);
	$password = is_string($password) ? htmlspecialchars($password) : '';

	if(function_exists("mysql_close")==1) {
		$link = @mysql_connect($host.":".$port,$login,$password);
		if ($link){
			echo "连接到MySql数据库正常！";
		} else {
			echo "无法连接到MySql数据库！";
		}
	} else {
		echo "服务器不支持MySQL数据库！";
	}
}
#邮件检测
elseif ($_GET['act'] == 'mailTest')
{
	@header("content-Type: text/html; charset=utf-8"); //语言强制
	$mailAddr = $_GET["mailAddr"];
	$mailRet = "邮件发送检测结果：发送";
	if($_SERVER['SERVER_PORT']==80){
		$mailContent = "http://".$_SERVER['SERVER_NAME'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
	}
	else{
		$mailContent = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
	}
	$mailRet .= (false !== @mail($mailAddr, $mailContent, "This is a test mail!")) ? "完成":"失败";

	echo htmlspecialchars($mailRet);
}
elseif ($_POST['act'] == 'mailTest')
{
	@header("content-Type: text/html; charset=utf-8"); //语言强制
	$mailAddr = $_POST["mailAddr"];
	$mailRet = "邮件发送检测结果：发送";
	if($_SERVER['SERVER_PORT']==80){
		$mailContent = "http://".$_SERVER['SERVER_NAME'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
	}
	else{
		$mailContent = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
	}
	$mailRet .= (false !== @mail($mailAddr, $mailContent, "This is a test mail!")) ? "完成":"失败";

	echo htmlspecialchars($mailRet);
}
else
{
	@header("content-Type: text/json; charset=utf-8"); //语言强制
	echo json_encode(GetPHPConfigure());
}
?>
