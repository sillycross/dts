<?php
//移动指针$i2跳过字符串中的空部分，如果$i2移到了末尾（$content整个为空）则返回0，否则返回1
function skip_whitespace(&$content,&$i2)
{
	$i=$i2;
	while ($i<strlen($content) && ctype_space($content[$i])) $i++;
	if ($i>=strlen($content)) return 0;
	$i2=$i; return 1;
}

//判断$content里是否存在$word。如果存在，$i2前移$word长度。会自动跳过空字符，除非$no_skip_prefix_whitespace开启
function check_word(&$content, &$i2, $word, $no_skip_prefix_whitespace = 0)
{
	$i=&$i2;
	if (!$no_skip_prefix_whitespace) if (!skip_whitespace($content,$i)) return 0;
	if (substr($content,$i,strlen($word))!=$word) return 0;
	$i+=strlen($word);
	$i2=$i; return 1;
}

function strip_comments($code){
	return strip_tokens($code);
}

//删掉函数里的特定源码字符，来自网络，修了个BUG
function strip_tokens($code) {
  $args = func_get_args();
  $arg_count = count($args);  
  // if no tokens to strip have been specified then strip comments by default
  if( $arg_count === 1 ) {
    $args[1] = T_COMMENT;
    $args[2] = T_DOC_COMMENT;
  }
  $arg_count = count($args);
  // build a keyed array of tokens to strip
  for( $i = 1; $i < $arg_count; ++$i )
    $strip[ $args[$i] ] = true;
  // set a keyed array of newline characters used to preserve line numbering  
  $newlines = array("\n" => true, "\r" => true);
  $tokens = token_get_all($code);
  reset($tokens);
  $return = '';
  $token = current($tokens);
  while( $token ) {
    if( !is_array($token) )
      $return.= $token;
    elseif(    !isset($strip[ $token[0] ]) )
      $return.= $token[1];
    else { 
      // return only the token's newline characters to preserve line numbering
      for( $i = 0, $token_length = strlen($token[1]); $i < $token_length; ++$i )
        if( isset($newlines[ $token[1][$i] ]) )
          $return.= $token[1][$i];
    }
    $token = next($tokens);
  } // while more tokens
  return $return;
}


//跳过行首注释
//不再需要了
//function skip_beginning_comment(&$content, &$i2, &$ret)
//{
//	$i=$i2;
//	//从上一行尾开始判断，如果非空字符之后直接有单行注释符号，则让$i2前移一整行，并返回这行内容。
//	if ((check_word($content,$i,"\r",1) || check_word($content,$i,"\n",1)) && (check_word($content,$i,'//') || check_word($content,$i,'#'))) {
//		$content2 = substr($content,$i);
//		$i3 = strpos($content2, "\r");
//		if($i3 === false) $i3 = strpos($content2, "\n");
//		if($i3) {
//			$ret = substr($content, $i2, $i-$i2+$i3);
//			$i2 = $i+$i3;
//			return 1;
//		}		
//	}
//	return 0;
//}

function check_import_module($tplfile, &$content, &$i2, &$ret)
{
	$i=$i2;
	if (!check_word($content,$i,'eval',1)) return 0;
	if (!check_word($content,$i,'(')) return 0;
	if (!check_word($content,$i,'import_module')) return 0;
	if (!check_word($content,$i,'(')) return 0;
	if (!skip_whitespace($content,$i)) return 0;
	
	$slist=Array(); $vlist=Array();
	while (1)
	{
		if (!skip_whitespace($content,$i)) return 0;
		if ($content[$i]=='\'' || $content[$i]=='"')
		{
			$ss=''; $i++;
			while ($i<strlen($content) && $content[$i]!='\'' && $content[$i]!='"') { $ss.=$content[$i]; $i++; }
			if ($i>=strlen($content)) return 0;
			$i++;
			if (!skip_whitespace($content,$i)) return 0;
			if ($content[$i]!=',' && $content[$i]!=')') return 0;
			array_push($slist,$ss);
			if ($content[$i]==')') break;
			$i++;
		}
		else  if ($content[$i]=='$')
		{
			$ss='$'; $i++;
			while ($i<strlen($content) && $content[$i]!=',' && $content[$i]!=')' && !ctype_space($content[$i])) 
			{
				$ss.=$content[$i]; $i++;
			}
			if ($i>=strlen($content)) return 0;
			array_push($vlist,$ss);
			if (!skip_whitespace($content,$i)) return 0;
			if ($content[$i]==')') break;
			if ($content[$i]!=',') return 0;
			$i++;
		}
		else  return 0;
	}
	$i++;
	
	if (!check_word($content,$i,')')) return 0;
	if (!check_word($content,$i,';')) return 0;
	
	$i2=$i;
	
	$ret='';
	foreach($vlist as $key) $ret.='eval(import_module('.$key.'));';	//变量名的保留
	foreach($slist as $key) 
	{
		if (strtoupper($key)=='INPUT')	//特殊处理input
		{
			$ret.='eval(import_module(\'input\'));'."\n";
		}
		else
		{
			$c='IMPORT_MODULE_'.strtoupper($key).'_GLOBALS';
			if (!defined($c))
			{
				echo '&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">严重错误！！代码'.$tplfile.'中引用了一个未知模块'.$key.'！<br></font>';
			}
			else
			{
				$ret.=constant($c)."\n";
				if (constant($c)=='') $ret.='__MODULE_NULLFUNCTION__();'."\n";
			}
		}
	}
	//$ret.='global $pstime; $pstime-=microtime(true);'.$ret.'$pstime+=microtime(true);';
	if (substr($tplfile,strlen($tplfile)-3)=='php') $ret='do { '.$ret.' } while (0);';	//template.func有bug，只好htm不加保护了
	$ret=str_replace("\n",' ',$ret);
	return 1;
}

function check_is_func(&$content, $i)
{
	if (!check_word($content,$i,'function ',1)) return '';
	if (!skip_whitespace($content,$i)) return '';
	if (!(('a'<=$content[$i] && $content[$i]<='z') || ('A'<=$content[$i] && $content[$i]<='Z') || $content[$i]=='_')) return 0;
	$str = '';
	while ($i<strlen($content) && (('a'<=$content[$i] && $content[$i]<='z') || ('A'<=$content[$i] && $content[$i]<='Z') || ('0'<=$content[$i] && $content[$i]<='9') || $content[$i]=='_'))
	{
		$str.=$content[$i]; $i++;
	}
	if (!check_word($content,$i,'(')) return '';
	return $str;
}

function parse_get_funcname(&$content, $i2)
{
	while ($i2>=0) 
	{
		$z=check_is_func($content, $i2);
		if ($z!='') return $z;
		$i2--;
	}
	throw new Exception('cannot parse out function name');
}

function get_magic_content($funcname, $modid)
{
	global $___TEMP_modfuncs;
	$str = __MAGIC_CODEADV2__;
	preg_match_all("/if[\s]*\(.+\)[\s]*\{([\s\S]*)\}[\s]*(.+)/i",$str,$matches);
	$str1 = $matches[1][0]; $str2 = $matches[2][0];
	//去掉永远不可能达成的if
	if($___TEMP_modfuncs[$modid][$funcname]['parent']) {
		$str = $str1.$str2;
		$str = str_replace('_____TEMPLATE_MAGIC_CODEADV2_INIT_DESIRE_PARENTNAME_____','\''.$___TEMP_modfuncs[$modid][$funcname]['parent'].'\'',$str);
		$str = str_replace('_____TEMPLATE_MAGIC_CODEADV2_INIT_EVACODE_____',$___TEMP_modfuncs[$modid][$funcname]['evacode'],$str);
		$str = str_replace('_____TEMPLATE_MAGIC_CODEADV2_INIT_CHPROCESS_____','\''.$___TEMP_modfuncs[$modid][$funcname]['chprocess'].'\'',$str);
	}else{
		$str = str_replace('_____TEMPLATE_MAGIC_CODEADV2_INIT_CHPROCESS_____','\''.$___TEMP_modfuncs[$modid][$funcname]['chprocess'].'\'',$str2);
	}
	return $str;
}

function check_eval_magic($modid, $tplfile, &$content, &$i2, &$ret)
{
	$i=$i2;
	if (!check_word($content,$i,'if',1)) return 0;
	if (!check_word($content,$i,'(')) return 0;
	if (!check_word($content,$i,'eval')) return 0;
	if (!check_word($content,$i,'(')) return 0;
	if (!check_word($content,$i,'__MAGIC__')) return 0;
	if (!check_word($content,$i,')')) return 0;
	if (!check_word($content,$i,')')) return 0;
	if (!check_word($content,$i,'return')) return 0;
	if (!check_word($content,$i,'$___RET_VALUE')) return 0;
	if (!check_word($content,$i,';')) return 0;
	$funcname = parse_get_funcname($content,$i2);
	$i2=$i;
	$ret = get_magic_content(strtolower($funcname), $modid);
	//测试
	//统计函数调用个数
	//$ret='global $___TEMP_CALLS_COUNT; $___TEMP_CALLS_COUNT[\''.$funcname.'\']=1; '.$ret;
	$ret=str_replace("\n",' ',$ret);
	return 1;
}

/*
function check_init_func($modname, $tplfile, &$content, &$i2, &$ret)
{
	$modname=strtoupper($modname);
	$i=$i2;
	if (!check_word($content,$i,'function',1)) return 0;
	if (!check_word($content,$i,'init')) return 0;
	if (!check_word($content,$i,'(')) return 0;
	if (!check_word($content,$i,')')) return 0;
	if (!check_word($content,$i,'{')) return 0;
	$i2=$i;
	$ret = 'function init() { if (defined(\'MOD_'.$modname.'\')) {'.constant('___GLOBAL_'.$modname.'_VARS___').constant('IMPORT_MODULE_'.$modname.'_GLOBALS').'}';
	return 1;
}
*/

//返回特定类型代码出现位置的offset
//$find可以是数组。如果是数组的话，会忽略$code_type参数，而采用数组的键名键值对
//返回数组，第一个值是offset，第二个值是匹配的代码
function strpos_code_block($code, $find, $code_type = T_STRING, $strpos_type=0){
	$ret = false;
	$ret2 = NULL;
	$offset = 0;
	$tokens = token_get_all($code);
	foreach($tokens as $token) {
		$r = false;
		$t = NULL;
		$tt = -1;
		if(is_string($token)){
			$t = $token;
			$tt = NULL;	
		}elseif(is_array($token)){
			$t = $token[1];
			$tt = $token[0];
		}
		if($t){
			if(!is_array($find)){
				$find = array($find => $code_type);
			}
			foreach($find as $f => $ft){
				if($ft !== $tt) continue;
				if(!$strpos_type) {
					$r0 = strpos($t, $f);
					if(false === $r0) continue;
					if(false === $r) {
						$r = $r0;
						$ret2 = $f;
					}
					elseif(false !== $r0) {
						$r = min($r, $r0);
						if($r == $r0) $ret2 = $f;
					}
				}elseif(1==$strpos_type) {
					$r0 = strripos($t, $f);	
					if(false === $r0) continue;
					if(false === $r) {
						$r = $r0;	
						$ret2 = $f;
					}
					elseif(false !== $r0) {
						$r = max($r, $r0);
						if($r == $r0) $ret2 = $f;
					}
				}
			}
		}
		if($r !== false){
			$ret = $offset + $r;
			if(!$strpos_type)	break;
		}
		$offset += is_array($token) ? strlen($token[1]) : strlen($token);
	}
	return array($ret, $ret2);
}

//在特定类型代码出现位置处分割字符串
function str_split_code_block($code, $find, $code_type = T_STRING, $strpos_type=0){
	list($r, $r2) = strpos_code_block($code, $find, $code_type, $strpos_type);
	if(false===$r) return array($code, NULL, NULL);
	$behind = substr($code, 0, $r);
	$ahead = substr($code, $r);
	return array($behind, $ahead, $r2);
}

//获取一个文件里所有函数信息
//返回数组，键名为函数名，键值为数组，包含vars=>变量字符串，及content=>数组
//$content数组包含从$chprocess()切开的三部分字符串
function analyze_functions($code){
	$ret = array();
	$tokens = token_get_all($code);
	$func_name = '';
	$func_state = 0;
	$brace_nest = 0;
	$temp_brace_nest = 0;
	//获得vars及content，这里content尚为字符串
  foreach($tokens as $token) {
  	$token_str = is_array($token) ? $token[1] : $token;
  	if(2 == $func_state){
  		$ret[$func_name]['vars'] .= $token_str; 
  	}
  	elseif(3 == $func_state){
  		$ret[$func_name]['contents'] .= $token_str; 
  	}
  	if(T_FUNCTION == $token[0]){
  		$func_state = 1;
  		$temp_brace_nest = $brace_nest;
  	}elseif(T_STRING == $token[0]){
  		if(1 == $func_state)
  			$func_name = strtolower($token[1]);
  	}elseif('(' == $token){
  		if(1 == $func_state)
  			$func_state = 2;
  	}elseif(')' == $token){
  		if(2 == $func_state){
  			$func_state = 1;
  			$ret[$func_name]['vars'] = substr($ret[$func_name]['vars'], 0, -1);
  		}
  	}elseif('{' == $token || T_CURLY_OPEN == $token[0]){ //我想操php编写者的妈妈
  		$brace_nest ++;
  		if(1 == $func_state)
  			$func_state = 3;
  	}elseif('}' == $token){
  		$brace_nest --;
  		if($brace_nest == $temp_brace_nest && 3 == $func_state){
  			$func_state = 0;
  			$ret[$func_name]['contents'] = substr($ret[$func_name]['contents'], 0, -1);
  		}
  	}
  }
  return $ret;
}

//自动给代码加大括号
function merge_parse_brace($code){
	$tokens = token_get_all($code);
	$in_brace_state = 0;//0为不需要加括号，1为需要加前花括号，2为需要加后花括号
	$paren_nest = 0;
	$ret = '';
	foreach($tokens as $offset => $token){
		$token_str = is_array($token) ? $token[1] : $token;
		$token_type = is_array($token) ? $token[0] : NULL;
		$ret .= $token_str;
		
		//else和do语句直接加前花括号
		if(T_ELSE === $token_type || T_DO === $token_type){
			list($s, $o) = merge_parse_seperator_check($tokens, $offset);
			if(0 == $in_brace_state && ';' == $s) {
				$ret .= '{';
				$in_brace_state = 2;
			}
		}
		//if等带圆括号的语句，将$in_brace_state设为1，等圆括号结束再加前花括号
		elseif(T_IF === $token_type || T_ELSEIF === $token_type || T_FOR === $token_type || T_FOREACH === $token_type || T_WHILE === $token_type){
			list($s, $o) = merge_parse_seperator_check($tokens, $offset);
			if(0 == $in_brace_state && ';' == $s) {
				$in_brace_state = 1;
				$paren_nest = 0;
			}
		}
		//遇到前括号，增加层数
		elseif('(' == $token_str && 1 == $in_brace_state){
			$paren_nest ++;
		}
		//遇到后括号则减少层数，层数减到0则补前花括号
		elseif(')' == $token_str && 1 == $in_brace_state){
			$paren_nest --;
			if(!$paren_nest){
				$ret .= '{';
				$in_brace_state = 2;
			}
		}
		//遇到分号则补后花括号
		elseif(';' == $token_str && 2 == $in_brace_state){
			$ret .= '}';
			$in_brace_state = 0;
		}
	}
	
	return $ret;
}

//查找代码块某处之后的第一个分号、花括号或者冒号（流程控制替代语法）
function merge_parse_seperator_check($tokens, $offset){
	$z = sizeof($tokens);
	$r = 0;
	for($i=$offset+1; $i<$z; $i++){
		if(is_string($tokens[$i]) && in_array($tokens[$i], array(';', '{', ':')))
			$r = $tokens[$i];
		if($r) break;
	}
	return array($r, $i);
}

//用$replacement（代码块字符串）替换$subject里的第一个$chprocess()
//并非简单替换，要在$chprocess()所在行的前一行先给$ret赋值，然后用$ret_varname替换$chprocess()
//为了正常运行，会自动给单行代码块加花括号
function merge_replace_chprocess($ret_varname, $replacement, $subject){
	$subject = '<?php '.$subject;
	//自动加花括号
	$subject = merge_parse_brace($subject);
	//按$chprocess出现的位置分成两段
	list($subject_behind_chp, $subject_ahead_chp) = str_split_code_block($subject, '$chprocess', T_VARIABLE);
	//无$chprocess直接返回
	if(!$subject_ahead_chp) return;
	//然后寻找前段代码里四种能够正确插入代码块的分隔符最后一次出现的位置，并分为两段
	$i = 0;
	do{
		$pattern = array(';' => NULL, '{' => NULL, '}' => NULL, ':' => NULL);
		list($subject_behind_delim, $subject_ahead_delim, $delim) = str_split_code_block($subject_behind_chp, $pattern, NULL, 1);
		if(!$delim) break;//如果找不到分隔符，直接跳出，可以插在开头
		if(':' != $delim){//前三种的情况下直接可以插入
			break;
		} else {//冒号的情况下，要确定是流程控制替代语法才可以插入
			$pattern = array('if' => T_IF, 'else' => T_ELSE, 'elseif' => T_ELSEIF, 'case' => T_CASE, 'default' => T_DEFAULT, 'while' => T_WHILE, 'for' => T_FOR, 'foreach' => T_FOREACH);
			list($subject_behind_cstructure, $subject_ahead_cstructure, $t_cstructure) = str_split_code_block($subject_behind_delim.$delim, $pattern, NULL, 1);
			$flag = false;
			if(NULL !== $t_cstructure && (preg_match('/'.$t_cstructure.'\s*\(.+\)\s*:/s', $subject_ahead_cstructure) || preg_match('/'.$t_cstructure.'\s*:/s', $subject_ahead_cstructure))){
				break;
			}else{//否则放弃这个位置
				$subject_ahead_chp = $subject_ahead_delim . $subject_ahead_chp;
				$subject_behind_chp = $subject_behind_delim;
			}
		}
		$i++;
	} while ($i<100);
	if($i >= 100) die('merge replacing error!');
	$subject_behind_delim = substr($subject_behind_delim, 6);
	if(!$delim){
		$subject_behind_delim = $replacement . $subject_behind_delim;
	} else {
		$subject_ahead_delim = preg_replace('/['.$delim.'](\s*?)/s', $delim."$1".$replacement."$1", $subject_ahead_delim, 1);
	}
	$subject_ahead_chp = preg_replace('/\$chprocess\(.*?\)/s', $ret_varname, $subject_ahead_chp, 1);
	
	return $subject_behind_delim . $subject_ahead_delim . $subject_ahead_chp;
}

function merge_contents_calc($modid)
{
	global $___TEMP_func_contents;
	global $___TEMP_final_func_contents;
	global $___TEMP_last_ret_varname;
	global $___TEMP_stored_func_contents;//键名是函数名，键值是执行到任意时刻那个函数所存下来的内容
	global $___TEMP_node_func_modname;//键名是函数名，键值是modname
	global $___TEMP_defined_funclist;
	global $___TEMP_modfuncs;
	global $modn, $___TEMP_flipped_modn;
	
	$___TEMP_final_func_contents[$modid] = array();
	
	foreach ($___TEMP_defined_funclist[$modid] as $key)//注意执行顺序和继承顺序完全不是一个概念
	{
		if(strpos($key,'parse_news')===false) continue;
		
		$key = strtolower(substr($key,strpos($key,'\\',0)+1));
		$contents = $___TEMP_func_contents[$modid][$key]['contents'];
		//先干掉eval字符串避免误处理
		preg_match('/\s*if \(eval\(__MAGIC__\)\) return \$___RET_VALUE;\s*[\r\n]/s', $contents, $contents_evacode_temp);
		$contents_evacode_temp = $contents_evacode_temp[0];
		$contents = str_replace($contents_evacode_temp, '', $contents);
		
		$func_info = $___TEMP_modfuncs[$modid][$key];
		if(empty($___TEMP_last_ret_varname[$key])) $___TEMP_last_ret_varname[$key] = 'NULL';
		if(empty($___TEMP_node_func_modname[$key])) $___TEMP_node_func_modname[$key] = '__MODULE_NULLFUNCTION__';
		//节点判断步骤
		$node_this = true;//若无父函数或者父函数里有2个以上的$chprocess，则把暂存的内容全部写在本函数，之后清空暂存内容
		if($func_info['parent']){
			$parent = explode('\\',$func_info['parent']);
			$parent_modname = $parent[0] ? $parent[0] : $parent[1];
			//获得父函数的内容，以进行下一步判定
			$parent_func_contents = $___TEMP_func_contents[$___TEMP_flipped_modn[$parent_modname]][$key];
			$cc = substr_count($parent_func_contents['contents'], '$chprocess(');
			if($cc <= 1) $node_this = false; //若父函数里只有1个$chprocess，则本函数可以直接合并进父函数，因而本函数暂存内容，只留跳转
		}
		
		//进入处理步骤
		if($node_this)//该函数为根函数或者父函数有2个以上的chprocess，则把这个mod的函数作为节点
		{
			//如果有暂存内容，则先用暂存内容替换节点内容里的$chprocess
			//节点函数有两个以上$chprocess的情况下，不存在暂存内容
			if(!empty($___TEMP_stored_func_contents[$key])){
				$contents = merge_replace_chprocess($___TEMP_last_ret_varname[$key], $___TEMP_stored_func_contents[$key], $contents);
				//清空暂存内容
				$___TEMP_stored_func_contents[$key] = '';
			}
			//将本函数内容里的$chprocess替换为上一个节点
			$replacement = isset($___TEMP_node_func_modname[$key]) ? '\\'.$___TEMP_node_func_modname[$key].'\\'.$key : '';
			$contents = str_replace('$chprocess', $replacement, $contents);
			//消灭嵌套return造成的首尾相连赋值
			$i=0;
			while($i<1000 && preg_match('/\$(\S+)\s*=\s*\$(\S+)\s*;\s*\$(\S+)\s*=\s*\$(\S+)\s*;/s', $contents, $matches)){
				if($matches[1] == $matches[4]) {
					$contents = preg_replace('/\$'.$matches[1].'\s*=\s*\$'.$matches[2].'\s*;\s*\$'.$matches[3].'\s*=\s*\$'.$matches[4].'\s*;/s', '$'.$matches[3].' = $'.$matches[2].';', $contents);
				}
				$i++;
			}
			//将本mod记录为节点
			$___TEMP_node_func_modname[$key] = $modn[$modid];
			//最后把开头的eval字符串加回来
			$contents = $contents_evacode_temp . $contents;
			
			//import_module()处理
			//代码块里的似乎不好判断
//			$wc_vars_existing_list = array();
//			preg_match_all('/import_module((.*?)\)/s', $node_contents, $wc_vars, PREG_SET_ORDER);
//			for($i=0;$i<count($wc_vars);$i++){
//				$wc_vars_s_arr = explode($wc_vars[$i]);
//				$wc_vars_s_arr = array_diff($wc_vars_s_arr, $wc_vars_existing_list);
//				$wc_vars_existing_list = array_merge($wc_vars_existing_list, $wc_vars_s_arr);
//			}
			
		}
		else//该函数的父函数只有1个chprocess，对该函数的内容处理以后作暂存，清空该函数内容
		{
			//变量处理
			preg_match('/\$chprocess\((.*?)\)/s', $parent_func_contents, $parent_chpvars);
			$parent_varname_change = '';
			if($parent_chpvars[1]){
				$vars_arr = explode(',',$___TEMP_func_contents[$modid][$key]['vars']);
				$parent_chpvars_arr = explode(',',$parent_chpvars[1]);
				for($i=0; $i<count($parent_chpvars_arr); $i++){
					$vars_arr_s = trim(explode('=',$vars_arr[$i])[0]);
					$parent_chpvars_arr_s = trim($parent_chpvars_arr[$i]);
					if($vars_arr_s !== $parent_chpvars_arr_s){
						$parent_varname_change .= $vars_arr_s . ' = ' . $parent_chpvars_arr_s . '; ';
					}
				}
			}
			if($parent_varname_change) $parent_varname_change .= "\r\n";
			$contents = $parent_varname_change . $contents;
			
			//unset所有变量
			//这个可以回头再说
			
			//return处理
			$ret_varname = '$'.$modn[$modid].'_'.$key.'_ret';
			$contents = preg_replace('/return\s*;/s', $ret_varname." = NULL;", $contents);
			$contents = preg_replace('/return\s*(.*?);/s', $ret_varname." = $1;", $contents);
			
			//$contents里的$chprocess(*)换成$$ret_varname，在暂存内容的前一行插入$contents
			
			//$___TEMP_stored_func_contents没内容时直接暂存本函数内容
			//也即本函数有2个以上的$chprocess
			if(empty($___TEMP_stored_func_contents[$key])) {
				$___TEMP_stored_func_contents[$key] = $contents;
			//$contents里只有1个$chprocess，先用$sfc替换本函数$chprocess，再将本函数全部暂存
			} else {
				$___TEMP_stored_func_contents[$key] = merge_replace_chprocess($___TEMP_last_ret_varname[$key], $___TEMP_stored_func_contents[$key], $contents);
				//writeover('d.txt',$___TEMP_stored_func_contents[$key]);
			}
			
			//本函数内容清空，只留跳转
			$contents = $func_info['evacode'];
			
			//记录这一个函数的返回值名称
			$___TEMP_last_ret_varname[$key] = $ret_varname;
			//writeover('d.txt',$___TEMP_func_contents[$modid][$key]['contents']);
		}
		$___TEMP_final_func_contents[$modid][$key] = $contents;
	}
}


function parse($modid, $tplfile, $objfile)
{
	//global $___MOD_SRV;
	$content=file_get_contents($tplfile);
	$result='';
	$i=0;
	while ($i<strlen($content))	//千万别分多次parse………… 分多次parse后面的文件会特别长，超慢
	{
		$s=='';
//		if (skip_beginning_comment($content, $i, $s))
//		{
//			$result.=$s;
//		}
//		else
		if (check_eval_magic($modid, $tplfile, $content, $i, $s))
		{
			$result.=$s; 
		}
		elseif (check_import_module($tplfile, $content, $i, $s))
		{
			$result.=$s; 
		}
		else  
		{
			$result.=$content[$i]; $i++;
		}
	}
	writeover($objfile, $result);
}

function preparse($modid, $tplfile)
{
	global $___TEMP_defined_funclist;
	global $___TEMP_modfuncs; //$___TEMP_modfuncs=Array();
	global $___TEMP_func_contents;
	foreach ($___TEMP_defined_funclist[$modid] as $key)
	{
		$key();
		global $___TEMP_DESIRE_PARENTNAME,$___TEMP_EVACODE,$___TEMP_CHPROCESS;
		$___TEMP_modfuncs[$modid][strtolower(substr($key,strpos($key,'\\',0)+1))]=Array(
			'parent' => $___TEMP_DESIRE_PARENTNAME,
			'evacode' => $___TEMP_EVACODE,
			'chprocess' => $___TEMP_CHPROCESS,
		);
	}
	if(isset($___TEMP_func_contents[$modid])) $___TEMP_func_contents[$modid] = array_merge($___TEMP_func_contents[$modid], analyze_functions(file_get_contents($tplfile)));
	else $___TEMP_func_contents[$modid] = analyze_functions(file_get_contents($tplfile));
	
}

/* End of file modulemng.codeadv2.func.php */
/* Location: /include/modulemng/modulemng.codeadv2.func.php */