<?php

function to_base64($num)
{
	$str='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-';
	$ret='';
	for ($i=1; $i<=4; $i++)
	{
		$ret=$str[$num%64].$ret;
		$num=floor($num/64); $num=(int)$num;
	}
	return $ret;
}

function parse_codeadv3(&$content)
{
	$content=str_replace('<br />',"\n",$content);
	
	$content=substr($content,strlen('<code><span style="color: #000000">'."\n"),-strlen('</span>'."\n".'</code>'));
	
	global $___TEMP_codeadv3, $___TEMP_codeadv3_v, $___TEMP_codeadv3_c;
	
	$i=0; $ret='';
	while ($i<strlen($content))
	{
		$con='';
		while ($i<strlen($content) && substr($content,$i,strlen('<span'))!='<span')
		{
			$con.=$content[$i]; $i++;
		}
		
		if (strlen($con)>=7)
		{
			$conmd5=md5($con);
			if (!isset($___TEMP_codeadv3[$conmd5]))
			{
				$___TEMP_codeadv3_c++; $___TEMP_codeadv3[$conmd5]=$___TEMP_codeadv3_c;
				$_con=$con;
				$_con=str_replace('&nbsp;',' ',$_con);	
				$_con=html_entity_decode($_con);
				$___TEMP_codeadv3_v[to_base64($___TEMP_codeadv3_c)]=$_con;
			}
			$val=to_base64($___TEMP_codeadv3[$conmd5]);
			$ret.='<?php if (!defined(\'GEXIT_RETURN_JSON\') || !empty($GLOBALS[\'___tmp_disable_codeadv3\'])) { ?>';
			$ret.=$con;
			$ret.='<?php } else { echo \'___'.$val.'\'; } ?>';
		}
		else  $ret.=$con;
		
		if ($i<strlen($content) && substr($content,$i,strlen('<span'))=='<span')
		{
			while ($content[$i]!='>') $i++;
			$i++;
			$j=strpos($content,'</span>',$i);
			$con=substr($content,$i,$j-$i);
			
			$i=$j+strlen('</span>');
			$ret.=$con;
		}
	}
	//$ret=mb_convert_encoding($ret,'utf8');
	$ret=str_replace('&nbsp;',' ',$ret);	//奇怪的&nbsp;编码
	$ret=html_entity_decode($ret);
	return $ret;
}

/* End of file modulemng.codeadv3.func.php */
/* Location: /include/modulemng/modulemng.codeadv3.func.php */