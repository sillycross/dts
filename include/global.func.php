<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

require GAME_ROOT.'./include/roommng/roommng.config.php';

register_shutdown_function('global_shutdown_function');

//----------------------------------------
//              底层机制函数
//----------------------------------------
    
function gameerrorhandler($code, $msg, $file, $line){
	global $errorinfo;
	if(!$errorinfo){return;}
	if($code == 2){$emessage = '<b style="color:#ff0">Warning</b> ';}
	elseif($code == 4){$emessage = '<b style="color:#f00">Parse</b> ';}
	elseif($code == 8){$emessage = '<b>Notice</b> ';}
	elseif($code == 256){$emessage = '<b>User Error</b> ';}
	elseif($code == 512){$emessage = '<b>User Warning</b> ';}
	elseif($code == 1024){$emessage = '<b>User Notice</b> ';}
	else{$emessage = '<b style="color:#f00>Fatal error</b> ';}
	$emessage .= "($code): $msg in $file on line $line";
//	if ($code == 2){
//		$d = debug_backtrace();
//		$emessage .= serialize($d);
//	} 
	if ($code == 1024 && $file=='/srv/http/dts-test/command.php' && function_exists('__SOCKET_WARNLOG__')) 
		__SOCKER__WARNLOG__($emessage);
	if(isset($GLOBALS['error'])){
		$GLOBALS['error'] .= '<br>'.$emessage;
	}else{
		$GLOBALS['error'] = $emessage;
	}
	return true;
}

function gexit($message = '',$file = '', $line = 0) {
	global $charset,$gtitle,$extrahead,$allowcsscache,$errorinfo;
	defined('STYLEID') || define('STYLEID', '1');
	defined('TEMPLATEID') || define('TEMPLATEID', '1');
	defined('TPLDIR') || define('TPLDIR', './templates/default');
	
	if (defined('IN_DAEMON'))
	{
		if (defined('GEXIT_RETURN_JSON'))
		{
			$gamedata['url'] = 'error.php';
			$gamedata['errormsg'] = $message;
			//ob_clean();
			throw new Exception( gencode($gamedata) );
		}
		else
		{
			global $bbsurl,$homepage;
			ob_clean();
			throw new Exception( include template('error'));
		}
	}
	else
	{
		if (defined('GEXIT_RETURN_JSON'))
		{
			$gamedata['url'] = 'error.php';
			$gamedata['errormsg'] = $message;
			ob_clean();
			echo gencode($gamedata);
			exit();
		}
		else
		{
			global $bbsurl,$homepage;
			ob_clean();
			include template('error');
			exit();
		}
	}
}

function output($content = '') {
	//if(!$content){$content = ob_get_contents();}
	//ob_end_clean();
	//$GLOBALS['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
	//echo $content;
	ob_end_flush();
}

function url_dir(){
	global $server_address;
	if(!$server_address) include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
	return $server_address.'/';
	//return 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/')+1);
}

//正常执行时，自动清空玩家锁与用户锁
function global_shutdown_function(){
	$e = error_get_last();
	if(!$e) {
		//清除玩家锁
		if(function_exists('\player\release_player_lock_from_pool'))
			\player\release_player_lock_from_pool();
		//清除用户锁
		if(function_exists('release_user_lock_from_pool'))
			release_user_lock_from_pool();
	}
}

//循环判断锁是否存在，如果存在则挂起10毫秒之后继续判定，直到时间耗尽，起到阻塞作用
//返回true表示锁存在，false表示锁不存在或者超时
//如果加了$timeout，会阻塞到时间耗尽或者锁释放为止。$timeout时间是毫秒
//如果加了$key，会检测锁文件内容，如果跟$key对应，则认为锁不存在
function check_lock($dirname, $filename, $timeout=0, $key='')
{
	$sleept = $etime = 0;
	$res = file_exists($dirname.$filename);
	if($res) {
		//如果有文件，那么超时时间从文件建立时开始计算
		$etime = time() - filemtime($dirname.$filename);
	}
	$timeout = max(0, $timeout-$etime*1000);
	
	while($res){
		usleep(10000);
		$sleept += 10000;
		if($sleept > $timeout*1000) break;
		$res = file_exists($dirname.$filename);
		if($key && $res) {
			if(trim(file_get_contents($dirname.$filename)) == $key) {
				$res = false;
				break;
			}
		}
	}
	return $res;
}

//用于判定/生成锁
//如果文件存在，生成并返回true
//如果文件已经存在且跟$key不对应，返回false
function create_lock($dirname, $filename, $key='')
{
	dir_init($dirname);
	if(!file_exists($dirname.$filename)) {
		if($key) file_put_contents($dirname.$filename, $key);
		else touch($dirname.$filename);
		return true;
	}else{
		if($key && $key == trim(file_get_contents($dirname.$filename))) {
			touch($dirname.$filename);
			return true;
		}else return false;
	}
}

//清除生成的锁
//远程锁最多有效10秒
function release_lock($dirname, $filename, $key='')
{
	dir_init($dirname);
	if(file_exists($dirname.$filename)) {
		$cont = trim(file_get_contents($dirname.$filename));
		if(empty($cont) || $key == $cont || time() - filemtime($dirname.$filename) > 10) unlink($dirname.$filename);
	}		
}

//----------------------------------------
//              输入输出函数
//----------------------------------------

function gstrfilter($str) {
	if(is_array($str)) {
		foreach($str as $key => $val) {
			$str[gstrfilter($key)] = gstrfilter($val);
		}
	} else {		
		if(!empty($GLOBALS['magic_quotes_gpc'])) {
			$str = stripslashes($str);
		}
		$str = str_replace("'","",$str);//屏蔽单引号'
		$str = str_replace("\\","",$str);//屏蔽反斜杠/
		$str = htmlspecialchars($str,ENT_COMPAT);//转义html特殊字符，即"<>&
		$str = str_replace("___","",$str);//屏蔽连续的三个下划线，由于模块化用到了这些变量。防止注入
	}
	return $str;
}

function language($file, $templateid = 0, $tpldir = '') {
	if(!$templateid) $templateid = TEMPLATEID;
	if(TEMPLATEID == $templateid || !$tpldir) $tpldir = TPLDIR;
	$languagepack = GAME_ROOT.'./'.$tpldir.'/'.$file.'.lang.php';
	if(file_exists($languagepack)) {
		return $languagepack;
	} elseif($templateid != 1 || $tpldir != './templates/default') {
		return language($file, 1, './templates/default');
	} else {
		return FALSE;
	}
}

function dump_template($file, $templateid = 0){
	extract($GLOBALS);
	ob_start();
	include template($file, $templateid);
	$ret = ob_get_contents();
	ob_end_clean();
	return $ret;
}

function template($file, $templateid = NULL) {
	global $tplrefresh, $u_templateid;
	
	$templateid = $templateid ? $templateid : ($u_templateid ? $u_templateid : TEMPLATEID);
	$tpldir = 1!=$templateid ? str_replace('default',$templateid,TPLDIR) : TPLDIR;//其他主题模板文件夹名改为数字编号

	if (substr($file,0,4)=='MOD_') $file=__MODULE_GET_TEMPLATE__($file);
	if (strpos($file,'/')===false)
	{
		$tplfile = GAME_ROOT.'./'.$tpldir.'/'.$file.'.htm';
		if(!file_exists($tplfile)) {//文件不存在则沿用默认TEMPLATEID的
			$templateid = TEMPLATEID;
			$tpldir = TPLDIR;
			$tplfile = GAME_ROOT.'./'.$tpldir.'/'.$file.'.htm';
		}
		
		$objfile = GAME_ROOT.'./gamedata/templates/'.$templateid.'_'.$file.'.tpl.php';
	}
	else  
	{
		global $___MOD_CODE_ADV2;
		if ($___MOD_CODE_ADV2) 	//写死吧…… 无所谓了 //不明白这里是在干啥，算了……
		{
			$file = str_replace('include/modules','gamedata/run',$file);
			if (substr($file, -4) != '.adv') $file .= '.adv';
		}
		$tplfile = $file.'.'.$templateid.'.htm';
		if(!file_exists($tplfile)){
			$templateid = TEMPLATEID;
			$tpldir = TPLDIR;
			$tplfile = $file.'.htm';
		}
		$xdname=dirname($file); 
		$xdname=substr($xdname,strlen(GAME_ROOT));
		if (strpos($xdname,'./include/modules/')===0)
			$xdname=substr($xdname,strlen('./include/modules/'));
		else  $xdname=substr($xdname,strlen('./gamedata/run/'));
		$xdname=str_replace('/','_',$xdname);
		$xdname=str_replace("\\",'_',$xdname);//for windows
		$xbname=basename($file);
		$objfile = GAME_ROOT.'./gamedata/templates/'.$templateid.'_mod_'.$xdname.'_'.$xbname.'.tpl.php';
	}
	global $___TEMP_template_force_refresh;
	if($tplrefresh == 1 || (isset($___TEMP_template_force_refresh) && $___TEMP_template_force_refresh==1)) {
		if ((!file_exists($objfile) || filemtime($tplfile) > filemtime($objfile)) || (isset($___TEMP_template_force_refresh) && $___TEMP_template_force_refresh==1)) {
			require_once GAME_ROOT.'./include/template.func.php';
			parse_template($tplfile, $objfile, $templateid, $tpldir);
		}
	}
	return $objfile;
}

function content($file = '') {
	ob_clean();
	include template($file);
	$content = ob_get_contents();
	ob_end_clean();
	$GLOBALS['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
	return $content;
}

//兼容IN_DAEMON的cookie设置
function gsetcookie_comp($varname, $value, $life = 0, $prefix = 1)
{
	if (defined('IN_DAEMON'))
	{
		global $___LOCAL_INPUT__VARS__COOKIE_VAR_LIST;
		if(empty($___LOCAL_INPUT__VARS__COOKIE_VAR_LIST) || !is_array($___LOCAL_INPUT__VARS__COOKIE_VAR_LIST)) $___LOCAL_INPUT__VARS__COOKIE_VAR_LIST=Array();
		$___LOCAL_INPUT__VARS__COOKIE_VAR_LIST[$varname] = array(
			'value' => $value,
			'life' => $life,
			'prefix' => $prefix
		);
	}
	else
	{
		gsetcookie($varname, $value, $life, $prefix);
	}
}

function gsetcookie($varname, $value, $life = 0, $prefix = 1) {
	global $tablepre, $gtablepre, $cookiedomain, $cookiepath, $now, $_SERVER;
	if(empty($gtablepre)) include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
	$cname = ($prefix ? (strpos($varname, $gtablepre)!==0 ? $gtablepre : '') : '').$varname;
	$expire = $life ? $now + $life : 0;
	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	$httponly = 'pass' == $varname ? 1 : 0;
	setcookie($cname, $value, $expire, $cookiepath, $cookiedomain, $secure, $httponly);
}

function clearcookies() {
	global $cookiepath, $cookiedomain, $game_uid, $game_user, $game_pw, $game_secques, $adminid, $groupid, $credits;
	dsetcookie('auth', '', -86400 * 365);

	$game_uid = $adminid = $credits = 0;
	$game_user = $game_pw = $game_secques = '';
}

function config($file = '', $cfg = 1) {
	$cfgfile = file_exists(GAME_ROOT."./gamedata/config/{$file}_{$cfg}.php") ? GAME_ROOT."./gamedata/config/{$file}_{$cfg}.php" : GAME_ROOT."./gamedata/config/{$file}_1.php";
	return $cfgfile;
}

function dir_clear($dir) {
	$directory = dir($dir);
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename)) {
			unlink($filename);
		}
	}
	$directory->close();
}

//读取文件
function readover($filename,$method="rb"){
	strpos($filename,'..')!==false && debug_print_backtrace() && exit('Forbidden');
	$handle=fopen($filename,$method);
	if(flock($handle,LOCK_SH)){
		$filedata='';
		while (!feof($handle)) {
   		$filedata .= fread($handle, 8192);
		}
		//$filedata.=fread($handle,filesize($filename));
		fclose($handle);
	} else {exit ('An error occurred when reading file '.$filename.'.');}
	return $filedata;
}

//写入文件
function writeover($filename,$data,$method="rb+",$iflock=1,$check=1,$chmod=1){
	$check && strpos($filename,'..')!==false && debug_print_backtrace() && exit('Forbidden');
	touch($filename);
	$handle=fopen($filename,$method);
	if($iflock){
		if(flock($handle,LOCK_EX)){
			fwrite($handle,$data);
			if($method=="rb+") ftruncate($handle,strlen($data));
			fclose($handle); 
		} else {exit ('An error occurred when writing file '.$filename.'.');}
	} else {
		fwrite($handle,$data);
		if($method=="rb+") ftruncate($handle,strlen($data));
		fclose($handle); 
	}
	$chmod && chmod($filename,0777);
	return;
}

//打开文件，以数组形式返回
function openfile($filename){
	$filedata=readover($filename);
	$filedata=str_replace("\n","\n<:game:>",$filedata);
	$filedb=explode("<:game:>",$filedata);
	$count=count($filedb);
	if($filedb[$count-1]==''||$filedb[$count-1]=="\r"){unset($filedb[$count-1]);}
	if(empty($filedb)){$filedb[0]='';}
	return $filedb;
}

function clear_dir($dirName, $keep_root = 0, $expire = 0)	//递归清空目录
{
	if ($dirName[strlen($dirName)-1]=='/') $dirName=substr($dirName,0,-1);
	if(!file_exists($dirName) || !is_dir($dirName)) return;
	if ($handle=opendir($dirName)) 
	{
		while (($item=readdir($handle))!==false) 
		{
			if ($item!='.' && $item!='..' && $item!='.gitignore') 
			{
				if (is_dir($dirName.'/'.$item)) 
				{
					clear_dir($dirName.'/'.$item,0,$expire);
				} elseif(!$expire || time()-filemtime($dirName.'/'.$item) > $expire) {
					if (!unlink($dirName.'/'.$item))
					{
						//__SOCKET_WARNLOG__("clear_dir错误：无法删除文件。");
					}
				}
			}
		}
		closedir($handle);
		if (!$keep_root)
			if (!rmdir($dirName))
			{
				//__SOCKET_WARNLOG__("clear_dir错误：无法删除目录{$dirName}。");
			}
		
	}
	else
	{
		//__SOCKET_WARNLOG__('clear_dir错误: 进入目录'.$dirname."失败。");
	}
}

function mymkdir($pa)
{
	mkdir($pa); chmod($pa, 0777);
}

function dir_init($pa)
{
	if(!is_dir($pa)) create_dir($pa);
	return $pa;
}

function create_dir($pa)	//建立目录（自动创建不存在的父文件夹），别用父目录符号“../”
{
	strpos($pa,'..')!==false && debug_print_backtrace() && exit('Forbidden');
	while (1)
	{
		if ($pa[strlen($pa)-1]=='/') $pa=substr($pa,0,-1);
		if ($pa=='') return;
		if (basename($pa)=='.') $pa=substr($pa,0,-1); else break;
	}
	$parent=substr($pa,0,-strlen(basename($pa)));
	if (!file_exists($parent) || !is_dir($parent))
	{
		create_dir($parent);
	}
	if (!file_exists($pa) || !is_dir($pa))
	{
		if (file_exists($pa)) unlink($pa);	//如果是文件而不是目录，删掉
		mymkdir($pa);
	}
}

function copy_dir($source, $destination, $filetype='')		//递归复制目录
{   
	dir_init($destination);
	if ($source[strlen($source)-1]=='/') $source=substr($source,0,-1);
	if ($destination[strlen($destination)-1]=='/') $destination=substr($destination,0,-1);
	if ($handle=opendir($source)) 
	{
		while (($entry=readdir($handle))!==false)
		{   
			if( $entry!="." && $entry!=".." && (is_dir($source."/".$entry) || !$filetype || $filetype==pathinfo($entry,PATHINFO_EXTENSION) ) )
			{   
				if(is_dir($source."/".$entry))
				{ 
					copy_dir($source."/".$entry,$destination."/".$entry);
				} 
				else
				{   
					if (!copy($source."/".$entry,$destination."/".$entry))
					{
						echo "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"red\">copy_dir错误</font>：无法复制文件{$source}/{$entry}到{$destination}/{$entry}。<br>";
					}
				}   
			}
		}   
	}   
	else
	{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"red\">copy_dir错误</font>: 进入目录'.$source.'失败。<br>';
	}
}

//多文件压缩合并成单个文件。
//挨个读文件并写入，避免撑满内存
function filelist_achieve($objfile, $filelist)
{
	if(!empty($filelist)){
		file_put_contents($objfile, '');
		foreach($filelist as $fv){
			$filename = pathinfo($fv, PATHINFO_BASENAME);
			//$exname = pathinfo($fv, PATHINFO_EXTENSION);
			$filedata = base64_encode(gzencode(file_get_contents($fv)));
			//结构：文件名;长度;base64内容;
			file_put_contents($objfile, $filename.';'.strlen($filedata).';'.$filedata, FILE_APPEND);
		}
		return $objfile;
	}else{
		return false;
	}
}

//在该文件目录展开压缩的文件。
function filelist_unachieve($srcfile)
{
	$srcpath = pathinfo($srcfile, PATHINFO_DIRNAME);
	$ret = 0;
	$offset = $flag1 = $flag2 = 0;
	$str = ''; 
	$file_size = filesize($srcfile);
	while($offset < $file_size){
		if(!$flag1 || !$flag2){
			$cnt = file_get_contents($srcfile,NULL,NULL,$offset,1);
			if(';' == $cnt && $flag1){
				$flag2 = 1;
				$ret = 1;
			} elseif(is_numeric($cnt) && ';' == substr($str,strlen($str)-2,1)) {
				$flag1 = 1;
			}
			$str .= $cnt;
			$offset ++;
		}else{
			$tmp = explode(';', substr($str,0,-1));
			$cntsize = array_pop($tmp);
			$filename = implode(';', $tmp);
			$cnt = mgzdecode(base64_decode(file_get_contents($srcfile,NULL,NULL,$offset,$cntsize)));
			if(!$cnt) $ret = 2;
			file_put_contents($srcpath.'/'.$filename, $cnt);
			$offset += $cntsize;
			$str = '';
			$flag1 = $flag2 = 0;
		}
	}
	return $ret;
}

function fold($objfile, $filelist){
	//return fold_core($objfile, $filelist);
	return filelist_achieve($objfile, $filelist);
}

function unfold($srcfile){
	$tmp = file_get_contents($srcfile,NULL,NULL,0,64);
	//如果前64个字符里没有分号则是旧版
	if(strpos($tmp,';')===false) return unfold_core($srcfile);
	else return filelist_unachieve($srcfile);
}
	
//创建打包文件，使用gencode的旧版本
function fold_core($objfile, $filelist){
	if(!empty($filelist)){
		$filedata = array();
		foreach($filelist as $fv){
			$filename = pathinfo($fv, PATHINFO_BASENAME);
			$exname = pathinfo($fv, PATHINFO_EXTENSION);
			$filedata[$filename] = file_get_contents($fv);
			if(in_array($exname, array('bmp','png','jpg','gif')))
				$filedata[$filename] = base64_encode($filedata[$filename]);
		}
		$filedata = gencode($filedata);
		return file_put_contents($objfile, $filedata);
	}else{
		return false;
	}
}

//在该文件目录展开打包文件，使用gdecode的旧版本
function unfold_core($srcfile){
	$srcpath = pathinfo($srcfile, PATHINFO_DIRNAME);
	$filedata = file_get_contents($srcfile);
	$filedata = gdecode($filedata, 1);
	if($filedata){
		foreach($filedata as $fk => $fv){
			$filename = $srcpath.'/'.$fk;
			$exname = pathinfo($fk, PATHINFO_EXTENSION);
			if(in_array($exname, array('bmp','png','jpg','gif')))
				$fv = base64_decode($fv);
			file_put_contents($filename, $fv);
		}
		return 1;
	}else	return 0;
}

//通过file_get_contents()以post形式向网页发出信息，慢，不建议使用
function file_get_contents_post($url, $post_data=array(), $post_cookie=array(), $timeout=10) {
  $options = array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-type:application/x-www-form-urlencoded',
      'content' => http_build_query($post_data),
      'timeout' => $timeout
    )
  );
  if(!empty($post_cookie)){
  	$options['http']['header'] .= "\r\nCookie: ".http_build_cookiedata($post_cookie);
  }
  $context = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  return $result;
}

//通过curl扩展以post形式向网页发出信息
//如果有cookie修改，则会为全局变量$response_cookies赋值
function curl_post($url, $post_data=array(), $post_cookie=array(), $timeout = 10){
	if($url == '') return false;
	
	$con = curl_init((string)$url);
	if($timeout>=1) {
		curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
	}else{//毫秒级超时
		curl_setopt($con, CURLOPT_NOSIGNAL, 1);
		curl_setopt($con, CURLOPT_TIMEOUT_MS, (int)($timeout*1000));
	}
	
	curl_setopt($con, CURLOPT_POST,true);
	curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($con, CURLOPT_HEADER, true);
	curl_setopt($con, CURLOPT_POSTFIELDS, http_build_query($post_data));
	curl_setopt($con, CURLOPT_COOKIE, http_build_cookiedata($post_cookie));
	
	$ret = curl_exec($con); 
	
	$header_size = curl_getinfo($con, CURLINFO_HEADER_SIZE);
	if(!$header_size) $header_size = 0;
	$header = substr($ret, 0, $header_size);
	$body = substr($ret, $header_size);
	
	//检查是否有cookies修改
	preg_match_all('/^Set-Cookie: (.*?)[;\r\n]/m', $header, $matches);
	if(!empty($matches)) {
		global $response_cookies;
		$response_cookies = array();
		foreach($matches[1] as $mv){
			list($ck, $cv) = explode('=', $mv, 2);
			$response_cookies[$ck] = $cv;
		}
	}
	
	return $body;
}

//curl请求启动新的进程，到处都会用，所以放到这里
function curl_new_server($pass, $is_root=0)
{
	$url = url_dir().'command.php';
	$context = array(
		'conn_passwd'=>$pass,
		'command'=>'start',
		'is_root'=>$is_root
	);
	curl_post($url, $context, NULL, 0.1);
}

function http_build_cookiedata($cookie_arr){
	$cookiedata= '';
	if(!empty($cookie_arr)){
		foreach($cookie_arr as $k=> $v){
			$cookiedata.= $k.'='.$v.'; ';//浏览器传cookie时;后有空格
		}
		if(strlen($cookiedata)>0){
			$cookiedata= substr($cookiedata, 0, -2);
		}
	}
	return $cookiedata;
}

//各页面通用接口，转发请求给command
function render_page($page, $extra_context=array()){
	$url = url_dir().'command.php';
	$context = array('page'=>$page);
	foreach($_POST as $pkey => $pval){
		$context[$pkey] = $pval;
	}
	if(!empty($extra_context)){
		$context = array_merge($context, $extra_context);
	}
	$cookies = array();
	foreach($_COOKIE as $ckey => $cval){
		if(render_page_cookie_key_filter($ckey)) $cookies[$ckey] = $cval;
	}
	
	$pageinfo = curl_post($url, $context, $cookies);
	
	global $response_cookies;
	if(!empty($response_cookies)) {
		foreach($response_cookies as $rckey => $rcval){
			gsetcookie($rckey, $rcval);
		}
	}
	
	if(strpos($pageinfo, 'redirect')===0){
		list($null, $url) = explode(':',$pageinfo);
		header('Location: '.$url);
		exit();
	}
	if(strpos($pageinfo,'<head>')===false){
		$d_pageinfo = gdecode($pageinfo,1);
		if(is_array($d_pageinfo) && isset($d_pageinfo['url']) && 'error.php' == $d_pageinfo['url']){
			gexit($d_pageinfo['errormsg'],__file__,__line__);
		}
	}
	return $pageinfo;
}

function render_page_cookie_key_filter($key)
{
	$ret = 0;
	if(strpos($key,'user')!==false || strpos($key,'pass')!==false || strpos($key,'roomid')!==false || strpos($key,'templateid')!==false) $ret = 1;
	return $ret;
}

//获得一个文件夹下的所有特定类型的文件名，返回数组。
function gdir($dir, $tp=''){
	$ret = array();
	if($handle=opendir($dir)){
		while (($fn=readdir($handle))!==false) 
		{
			if($fn!='.' && $fn!='..') {
				if(empty($tp) || (is_dir($dir.'/'.$fn) && 'dir' == $tp) || (!is_dir($dir.'/'.$fn) && $tp == pathinfo($fn, PATHINFO_EXTENSION)) )
					array_push($ret,$fn);
			}
		}
	}else{
		$ret = NULL;
	}
	return $ret;
}

//获得游戏对外url，并且自动加上结尾的/
function gurl(){
	global $gameurl;
	$ret = $gameurl;
	if(substr($gameurl, strlen($gameurl)-1, 1) != '/' ) $ret .= '/';
	return $ret;
}

//md5并取前$i个字符
function md5sub($str, $i){
	return substr(md5($str),0,$i);
}

//----------------------------------------
//              调试函数
//----------------------------------------

function getmicrotime(){
	return microtime(1);
//	list($usec, $sec) = explode(" ",microtime());
//	return ((float)$usec + (float)$sec);
}

function putmicrotime($t_s, $t_e, $file, $info)
{
	$mtime = ($t_e - $t_s)*1000;
	writeover( $file.'.txt',"$info ；执行时间：$mtime 毫秒 \r\n",'ab');
}

function startmicrotime(){
	global $startmicrotime;
	$startmicrotime = getmicrotime();
}

function logmicrotime($info){
	global $startmicrotime;
	$nowmicrotime = getmicrotime();
	putmicrotime($startmicrotime, $nowmicrotime, 'microtimelog', $info);
	$startmicrotime = $nowmicrotime;
}

function get_script_runtime($pagestartime)
{
	$pageendtime = microtime(true);
	//$p_starttime = explode(" ",$pagestartime);
	//$p_endtime = explode(" ",$pageendtime);
	//$p_totaltime = $p_endtime[0]-$p_starttime[0]+$p_endtime[1]-$p_starttime[1];
	$timecost = sprintf("%.2f",$pageendtime - $pagestartime); 
	return $timecost;
}

function gwrite_var($file, $var, $method='rb+')
{
	writeover($file, var_export($var,1), $method);
}

function check_alnumudline($key)
{
	$key=(string)$key;
	for ($i=0; $i<strlen($key); $i++)
	{
		if (!(('a'<=$key[$i] && $key[$i]<='z') || ('A'<=$key[$i] && $key[$i]<='Z') || $key[$i]=='_' || ('0'<=$key[$i] && $key[$i]<='9')))
			return false;
	}
	return true;
}

function token_get_all_dic($code){
	$r = token_get_all($code);
	for($i=0;$i<sizeof($r);$i++){
		if(is_array($r[$i])){
			$r[$i][0] = token_name($r[$i][0]);
		}
	}
	return $r;
}

//----------------------------------------
//              变量处理
//----------------------------------------

function swap(&$a, &$b)
{
	$c=$a; $a=$b; $b=$c;
	//PHP7了，可以用太空船运算符了
}

//----------------------------------------
//              数学运算
//----------------------------------------

function full_combination($a, $min) {
	$r = array();
	$n = count($a);
	if($n >= $min){
		for($i=$min;$i<=$n;$i++){
			$r = array_merge($r, combination($a, $i));
		}
	}
	return $r;
} 

function combination($a, $m) {  
  $r = array();  
  $n = count($a);  
  if ($m <= 0 || $m > $n) {  
    return $r;  
  }
  for ($i=0; $i<$n; $i++) {  
    $t = array($a[$i]);  
    if ($m == 1) {  
      $r[] = $t;  
    } else {  
      $b = array_slice($a, $i+1);  
      $c = combination($b, $m-1);  
      foreach ($c as $v) {  
        $r[] = array_merge($t, $v);  
      }  
    }  
  }  
  return $r;  
} 

function seconds2hms($seconds){
	list($d, $h, $m, $s) = explode(' ', gmstrftime('%j %H %M %S', $seconds));
	$d=(int)$d - 1;
	$h = (int)$h; $m = (int)$m; $s = (int)$s;
	$ret = '';
	if($d) $ret .= $d.'天';
	if($h) $ret .= $h.'小时';
	if($m) $ret .= $m.'分钟';
	if($s) $ret .= $s.'秒';
	
	return $ret;
}

//----------------------------------------
//              数组运算
//----------------------------------------

function array_clone($a){//数组浅拷贝，该死的传引用
	$r = array();
	if(is_array($a)){
		foreach($a as $key => $val){
			$r[$key] = $val;
		}
	}
	return $r;
}

//----------------------------------------
//              字符串处理
//----------------------------------------

//把一个非负整数用64进制编码/解码
function base64_char_decode($c)
{
	if ('a'<=$c && $c<='z') return ord($c)-ord('a');
	if ('A'<=$c && $c<='Z') return ord($c)-ord('A')+26;
	if ('0'<=$c && $c<='9') return ord($c)-ord('0')+52;
	if ($c=='+') return 62;
	if ($c=='-') return 63;
	return 0;
}
	
function base64_char_encode($c)
{
	if ($c>=0)
	{
		if ($c<=25) return chr(ord('a')+$c);
		if ($c<=51) return chr(ord('A')+$c-26);
		if ($c<=61) return chr(ord('0')+$c-52);
		if ($c==62) return '+';
		if ($c==63) return '-';
	}
	return ' ';
}

function base64_encode_number($val, $len)
{
	$ret='';
	for ($i=0; $i<$len; $i++)
	{
		$ret=base64_char_encode($val%64).$ret;
		$val=(int)floor($val/64);
	}
	return $ret;
}

function base64_decode_number($val)
{
	$ret=0;
	for ($i=0; $i<strlen($val); $i++)
	{
		$ret=$ret*64+base64_char_decode($val[$i]);
	}
	return $ret;
}

function mgzdecode($data)
{
	return gzinflate(substr($data,10,-8));
}

//数组压缩转化为纯字母数字
function gencode($para){
	return base64_encode(gzencode(json_encode($para)));
}

//gencode函数的逆运算
function gdecode($para, $assoc = false){
	$assoc = $assoc ? true : false;
	if (!$para) return array();
	else return json_decode(mgzdecode(base64_decode($para)),$assoc);
}

//字符串中段省略，取头部+尾部1字符
function middle_abbr($str,$len1,$len2=1,$elli='...') {
	$str = (string)$str;
	$len1 = (int)$len1; $len2 = (int)$len2;
	return mb_substr($str,0,$len1).$elli.mb_substr($str,-$len2,$len2);
}

//mb_strlen()兼容替代函数，直接照抄的网络
if ( !function_exists('mb_strlen') ) {
	function mb_strlen ($text, $encode='UTF-8') {
		if ($encode=='UTF-8') {
			return preg_match_all('%(?:
			[\x09\x0A\x0D\x20-\x7E]           # ASCII
			| [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
			|  \xE0[\xA0-\xBF][\x80-\xBF]       # excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
			|  \xED[\x80-\x9F][\x80-\xBF]       # excluding surrogates
			|  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
			|  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
			)%xs',$text,$out);
		}else{
			return strlen($text);
		}
	}
}

//mb_substr()兼容替代函数，直接照抄的网络
if (!function_exists('mb_substr')) {
	function mb_substr($str, $start, $len = '', $encoding='UTF-8'){
		$limit = strlen($str);

		for ($s = 0; $start > 0;--$start) {// found the real start
			if ($s >= $limit)
			break;

			if ($str[$s] <= "\x7F")
			++$s;
			else {
				++$s; // skip length

				while ($str[$s] >= "\x80" && $str[$s] <= "\xBF")
				++$s;
			}
		}

		if ($len == '')
		return substr($str, $s);
		else
		for ($e = $s; $len > 0; --$len) {//found the real end
			if ($e >= $limit)
			break;

			if ($str[$e] <= "\x7F")
			++$e;
			else {
				++$e;//skip length

				while ($str[$e] >= "\x80" && $str[$e] <= "\xBF" && $e < $limit)
				++$e;
			}
		}

		return substr($str, $s, $e - $s);
	}
}

//----------------------------------------
//              重要游戏功能
//----------------------------------------

function init_dbstuff(){
	include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
	$default_database = PHP_VERSION >= 7.0 ? 'mysqli' : 'mysql';
	$db_class_file = GAME_ROOT.'./include/db/db_'.$database.'.class.php';
	$db_default_class_file = GAME_ROOT.'./include/db/db_'.$default_database.'.class.php';
	if(file_exists($db_class_file)) include_once $db_class_file;
	elseif(file_exists($db_default_class_file)) include_once $db_default_class_file;
	else die('Cannot find db_class file!');
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	return $db;
}

function check_authority()
{
	global $gtablepre;
	include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
	include_once GAME_ROOT.'./include/user.func.php';
	$_COOKIE=gstrfilter($_COOKIE);
	$cuser=$_COOKIE[$gtablepre.'user'];
	$cpass=$_COOKIE[$gtablepre.'pass'];
	global $db; $db = init_dbstuff(); 
	$udata = fetch_udata_by_username($cuser);
	if(empty($udata)) { echo "<span><font color=\"red\">Cookie无效，请登录。</font></span><br>"; die(); }
	if(!pass_compare($udata['username'],$cpass,$udata['password'])) { echo "<span><font color=\"red\">密码错误，请重新登录并重试。</font></span><br>"; die(); }
	elseif(($udata['groupid'] < 9)&&($cuser!==$gamefounder)) { echo "<span><font color=\"red\">要求至少9权限。</font></span><br>"; die(); }
}

//因为调用次数太多，懒得一个一个改了
function save_gameinfo() {	
	\sys\save_gameinfo();
}

function addnews($t = 0, $n = '',$a='',$b='',$c = '', $d = '', $e = '') {
	\sys\addnews($t, $n,$a,$b,$c, $d, $e);
}

function getchat($last,$team='',$chatpid=0,$limit=0) {
	return \sys\getchat($last,$team,$chatpid,$limit);
}

function systemputchat($time,$type,$msg = ''){
	\sys\systemputchat($time,$type,$msg );
}

function gversion_compare($v1, $v2){
	preg_match('/\d*\.\d*\.\d*/s', $v1, $matches);
	$v1e = $matches[0];
	preg_match('/\d*\.\d*\.\d*/s', $v2, $matches);
	$v2e = $matches[0];
	return version_compare($v1e, $v2e);
}

/* End of file global.func.php */
/* Location: /include/global.func.php */