<?php

if('127.0.0.1'!=real_ip()) {
	echo '不支持在非开发环境下运行！';
	die();
}

function real_ip()  
{  
	static $realip = NULL;  
	if ($realip !== NULL)  
	{  
		return $realip;  
	}  
	if (isset($_SERVER))  
	{  
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))  
		{  
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
			/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */ 
			foreach ($arr AS $ip)  
			{  
				$ip = trim($ip);  
				if ($ip != 'unknown')  
				{  
					$realip = $ip;  
					break;  
				}  
			}  
		}  
		elseif (isset($_SERVER['HTTP_CLIENT_IP']))  
		{  
			$realip = $_SERVER['HTTP_CLIENT_IP'];  
		} 
		# If a site is proxied through CloudFLare, we use the CloudFLare value here to grab the original IP of the user.
		elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']))  
		{  
			$realip = $_SERVER['HTTP_CF_CONNECTING_IP'];  
		} 
		elseif (isset($_SERVER['REMOTE_ADDR']))  
		{  
			$realip = $_SERVER['REMOTE_ADDR'];  
		}  
		else 
		{  
			$realip = '0.0.0.0';  
		}
	}
	elseif (getenv('HTTP_X_FORWARDED_FOR'))  
	{  
		$realip = getenv('HTTP_X_FORWARDED_FOR');  
	}  
	elseif (getenv('HTTP_CLIENT_IP'))  
	{  
		$realip = getenv('HTTP_CLIENT_IP');  
	}  
	else 
	{  
		$realip = getenv('REMOTE_ADDR');  
	}  
	  
	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);  
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';  
//	global $cuser;
//	if($cuser == 'Yoshiko' || $cuser == 'Yoshiko_G'){$realip = '70.54.1.30';}
	return $realip;  
} 

function encode_achievements($aarr, $old_version=0)
{
	if(!$old_version){
		return gencode($aarr);
	}else{
		return encode_achievements_o($aarr);
	}
}

//传入users表的数组，进行成就解码
//自动识别新旧成就，如果只有旧的则识别旧的，否则只识别新的 
function decode_achievements($udata)
{
	if(!empty($udata['u_achievements'])) {
		return gdecode($udata['u_achievements'], 1);
	}else{
		return decode_achievements_o($udata['n_achievements']);
	}
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

if(!empty($_GET['method']) && !empty($_GET['trans'])) {
	if('gencode' == $_GET['method']) {
		$show = gencode($_GET['trans']);
	}
	elseif('gdecode' == $_GET['method']) {
		$show = gdecode($_GET['trans']);
	}
}
if(empty($show)) {
	$show = '请输入要转换的字符串';
}

?>
<form id="form">
	<fieldset>
		转换结果：<input type="text" size="60" value="<?php echo $show;?>" name="trans"/><br />
		<input type="hidden" id="method" name="method" value="gencode" />
		<input type="button" value="编码" onclick="document.getElementById('method').value='gencode';document.getElementById('form').submit();"/>
		<input type="button" value="解码" onclick="document.getElementById('method').value='gdecode';document.getElementById('form').submit();"/>
	</fieldset>
</form>