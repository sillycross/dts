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
//function strpos_code_block($code, $find, $code_type = T_STRING, $strpos_type=0){
//	$ret = false;
//	$ret2 = NULL;
//	$offset = 0;
//	$tokens = token_get_all($code);
//	foreach($tokens as $token) {
//		$r = false;
//		$token_str = NULL;
//		$token_type = -1;
//		
//		$token_str = is_array($token) ? $token[1] : $token;
//		$token_type = is_array($token) ? $token[0] : NULL;
//
//		if($token_str){
//			if(!is_array($find)){
//				$find = array($find => $code_type);
//			}
//			foreach($find as $find_str => $find_type){
//				if($find_type !== $token_type) continue;
//				if(!$strpos_type) {
//					$r0 = strpos($token_str, $find_str);
//					if(false === $r0) continue;
//					if(false === $r) {
//						$r = $r0;
//						$ret2 = $find_str;
//					}
//					elseif(false !== $r0) {
//						$r = min($r, $r0);
//						if($r == $r0) $ret2 = $find_str;
//					}
//				}elseif(1==$strpos_type) {
//					$r0 = strripos($token_str, $find_str);	
//					if(false === $r0) continue;
//					if(false === $r) {
//						$r = $r0;	
//						$ret2 = $find_str;
//					}
//					elseif(false !== $r0) {
//						$r = max($r, $r0);
//						if($r == $r0) $ret2 = $find_str;
//					}
//				}
//			}
//		}
//		if($r !== false){
//			$ret = $offset + $r;
//			if(!$strpos_type)	break;
//		}
//		$offset += is_array($token) ? strlen($token[1]) : strlen($token);
//	}
//	return array($ret, $ret2);
//}

//在特定类型代码出现位置处分割字符串
//function str_split_code_block($code, $find, $code_type = T_STRING, $strpos_type=0){
//	list($r, $r2) = strpos_code_block($code, $find, $code_type, $strpos_type);
//	if(false===$r) return array($code, NULL, NULL);
//	$behind = substr($code, 0, $r);
//	$ahead = substr($code, $r);
//	return array($behind, $ahead, $r2);
//}

//获取一个文件里所有函数信息
//返回数组，键名为函数名，键值为数组，包含vars=>变量字符串，及contents=>函数内容字符串，及import_module=>载入模块字符串
function analyze_function_info($code, $filename){
	$ret = array();
	$tokens = token_get_all($code);
	$func_name = '';
	$func_state = 0;//函数定义状态，0为定义范围外，1为监测到function语句，2为括号内部，3为花括号内部
	$import_module_state = 0;//import_module状态，0为定义范围外，1为检测到import_module但没进括号，2为括号内
	$brace_nest = 0;
	$temp_brace_nest = 0;
	$offset = 0;
  foreach($tokens as $token) {
  	$token_str = is_array($token) ? $token[1] : $token;
		$token_type = is_array($token) ? $token[0] : NULL;
		if(!empty($func_name) && !isset($ret[$func_name])) 
			$ret[$func_name] = array('vars' => '', 'contents' => '', 'imported_modules' => '', 'filename' => $filename, 'file_contents_start' => 0, 'file_contents_end' => 0);
  	if(2 == $func_state){
  		$ret[$func_name]['vars'] .= $token_str; 
  	}
  	elseif(3 == $func_state){
  		$ret[$func_name]['contents'] .= $token_str; 
  		if(2 == $import_module_state) {
  			$ret[$func_name]['imported_modules'] .= $token_str;
  		}
  	}
  	if(T_FUNCTION == $token_type){
  		$func_state = 1;
  		$temp_brace_nest = $brace_nest;
  	}elseif(T_STRING == $token_type){
  		if(1 == $func_state){//前面有function语句的情况下，提取函数名
  			$func_name = strtolower($token_str);
  		}elseif($temp_brace_nest+1===$brace_nest && 0 == $import_module_state && 'import_module' === $token_str){//第一层遇到import_module的情况下，准备提取括号内模块名
				$import_module_state = 1;
			}
  	}elseif('(' == $token_str && NULL === $token_type){
  		if(1 == $func_state)
  			$func_state = 2;
  		if(1 == $import_module_state) 
  			$import_module_state = 2;
  	}elseif(')' == $token_str && NULL === $token_type){
  		if(2 == $func_state){
  			$func_state = 1;
  			$ret[$func_name]['vars'] = substr($ret[$func_name]['vars'], 0, -1);
  		}
  		if(2 == $import_module_state) {
  			$import_module_state = 0;
  			$ret[$func_name]['imported_modules'] = substr($ret[$func_name]['imported_modules'], 0, -1);
  		}
  	}elseif(('{' == $token_str && NULL === $token_type) || T_CURLY_OPEN == $token_type){ //T_CURLY_OPEN指的是字符串里的前花括号
  		$brace_nest ++;
  		if(1 == $func_state){
  			$func_state = 3;
  			$ret[$func_name]['file_contents_start'] = $offset;
  		}
  	}elseif('}' == $token_str && NULL === $token_type){
  		$brace_nest --;
  		if($brace_nest == $temp_brace_nest && 3 == $func_state){
  			$func_state = 0;
  			$ret[$func_name]['contents'] = substr($ret[$func_name]['contents'], 0, -1);
  			$ret[$func_name]['file_contents_end'] = $offset;
  		}
  	}
  	$offset += strlen($token_str);
  }
  return $ret;
}

//通过preg_match初判，然后通过token验证
//$reg_patt为正则表达式，$tok_patt为$reg_patt每个子模式所对应的解析器代号，可以用数组来表示or关系
function token_match($reg_patt, $tok_patt, $code){
	$ret = array();
	$tokens = token_get_all($code);
	if(sizeof($tokens) <= 1) return false;
	$reg_count = preg_match_all($reg_patt, $code, $reg_matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
	if(!$reg_count) return false;
	foreach($reg_matches as $rmval){//每一个匹配结果
		$match_flag = true;
		foreach($rmval as $rno => $rarr){//每一个子模式
			if(!$rno) continue;
			list($rstr, $roff) = $rarr;
			$o_token = token_get_single($roff, $tokens);
			$tok_type = $tok_patt[$rno-1];
			//$tok_patt支持数组，此时认为是or关系
			if(!is_array($tok_type)) $tok_type = array($tok_type);
			$match_flag = false;
			foreach($tok_type as $tt){//每一个解析器代号
				//解析器代号为'*'的时候忽略这个代号
				if('*' === $tt || (is_string($o_token) && NULL===$tt) || (is_array($o_token) && $o_token[0] === $tt)){
					$match_flag = true;
					break;
				}
			}
			//有任何不匹配时直接continue上一层
			if(!$match_flag) continue 2;
		}
		//都匹配则记录这一匹配结果
		$ret[] = $rmval;
	}
	return $ret;
}

//基于token_match的替换
function token_replace($reg_patt, $tok_patt, $replacement, $code, $limit = -1){
	$ret = array();
	$token_match_ret = token_match($reg_patt, $tok_patt, $code);
	if($limit > 0) $token_match_ret = array_slice($token_match_ret, 0, $limit);
	//第一遍，根据offset切割字符串，如果直接替换会导致offset失效错位
	$offset0 = 0;
	foreach($token_match_ret as $tval){
		$offset1 = $tval[0][1];
		$ret[] = substr($code, $offset0, $offset1-$offset0);
		$offset0 = $offset1;
	}
	$ret[] = substr($code, $offset1);
	
	//第二遍，对每段进行替换之后拼接
	foreach($token_match_ret as $tkey => $tval){
		$ret[$tkey + 1] = preg_replace($reg_patt, $replacement, $ret[$tkey + 1], 1);
	}
	$ret = implode('',$ret);
	return $ret;
}

//返回从$offset所在的$token
function token_get_single($offset, $tokens){
	$len = 0;
	foreach($tokens as $tval){
		$len += strlen(is_array($tval) ? $tval[1] : $tval);
		if($offset <= $len - 1) return $tval;
	}
	return $tval;
}

//判定两个offset位置是不是在同一层
//完全没用到，可以先注释掉了
//function token_is_same_nest($offset1, $offset2, $tokens){
//	$paren_nest = $brace_nest = 0;
//	$tmp_paren_nest_1 = $tmp_paren_nest_2 = $tmp_brace_nest_1 = $tmp_brace_nest_2 = NULL;
//	$len = 0;
//	if($offset2 < $offset1) {
//		$o = $offset2; $offset2 = $offset1; $offset1 = $o;
//	}
//	$flag = true;
//	foreach($tokens as $token){
//		$token_str = is_array($token) ? $token[1] : $token;
//		$token_type = is_array($token) ? $token[0] : NULL;
//		if($offset2 <= $len) {//到达$offset2
//			$tmp_paren_nest_2 = $paren_nest;
//			$tmp_brace_nest_2 = $brace_nest;
//			break;
//		} else {//到达$offset1
//			if(NULL === $tmp_paren_nest_1) $tmp_paren_nest_1 = $paren_nest;
//			if(NULL === $tmp_brace_nest_1) $tmp_brace_nest_1 = $brace_nest;
//		}
//		$len += strlen(is_array($token) ? $token[1] : $token);
//		if('(' == $token_str && NULL === $token_type){
//			$paren_nest ++;
//  	}elseif(')' == $token_str && NULL === $token_type){
//  		$paren_nest --;
//  	}elseif(('{' == $token_str && NULL === $token_type) || T_CURLY_OPEN == $token_type){ //T_CURLY_OPEN指的是字符串里的前花括号
//  		$brace_nest ++;
//  	}elseif('}' == $token_str && NULL === $token_type){
//  		$brace_nest --;
//  	}
//	}
//	if($tmp_paren_nest_1 != $tmp_paren_nest_2 || $tmp_brace_nest_1 != $tmp_brace_nest_2 )
//		$flag = false;
//	echo $tmp_paren_nest_1.' '.$tmp_paren_nest_2.';'.$tmp_brace_nest_1 .' '. $tmp_brace_nest_2;
//	return $flag;
//}

//用$replacement（代码块字符串）替换$subject里的第一个$chprocess()
//并非简单替换，要在$chprocess()所在行的前一行先给$ret赋值，然后用$ret_varname替换$chprocess()
//为了正常运行，需要先给单行代码块加花括号
function merge_replace_chprocess($ret_varname, $replacement, $subject){
	$subject = '<?php '.$subject;
	
	$reg_ret = token_match('/(\$chprocess)/s', array(T_VARIABLE), $subject);
	//无$chprocess直接返回
	if(!$reg_ret) return $subject;
	//按$chprocess出现的位置分成两段
	$subject_behind_chp = substr($subject, 0, $reg_ret[0][0][1]);
	$subject_ahead_chp = substr($subject, $reg_ret[0][0][1]);
	
	//从前段末尾开始向前逐个$token判定
	$offset = strlen($subject_behind_chp);
	$tokens = token_get_all($subject_behind_chp);
	$paren_nest = 0;
	$min_paren_nest = 0;
	$colon_flag = 0;
	$brace_insert_offset = NULL;
	$code_insert_offset = 0;
	while($offset >= 0) {
		$token = token_get_single($offset-1, $tokens);
		$token_str = is_array( $token ) ? $token[1] : $token;
		$token_type = is_array( $token ) ? $token[0] : NULL;
		//有冒号但是前面不是括号、空白或者操作符时，认为冒号不是控制流程替代语法
		if($paren_nest == $min_paren_nest && $colon_flag && !in_array($token_type, array(NULL, T_WHITESPACE, T_IF, T_ELSE, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_DO))){
			$colon_flag = 0;
		}
		//插入位置判定
		elseif($paren_nest == $min_paren_nest && in_array($token_type, array(T_IF, T_ELSE, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_DO))){
			if($colon_flag) {//控制流程替代语法，则不需要加大括号
				$brace_insert_offset = NULL;
				$code_insert_offset = $offset;
			}else{//否则需要加前大括号，判定是否需要标记并跳出
				//if elseif for foreach while这几个，大括号插入位置已被括号记录，只需跳出
				//while不用考虑do...while...因为已经被分号排除
				if(in_array($token_type, array(T_IF, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE))){
				}
				//else do这两个，就地插入大括号
				elseif(in_array($token_type, array(T_ELSE, T_DO))){
					$brace_insert_offset = $offset;
				}
			}
			break;
		}
		//遇到前括号，减少层数，并记录最小层数
		elseif('(' == $token_str && NULL === $token_type){
			$paren_nest --;
			if($paren_nest < $min_paren_nest) $min_paren_nest = $paren_nest;
		}
		//遇到后括号，如果层数达到最小，则记录大括号插入位置
		elseif(')' == $token_str && NULL === $token_type){
			if($min_paren_nest == $paren_nest) $brace_insert_offset = $offset;
			$paren_nest ++;
		}
		//遇到冒号，标记$colon_flag，待后续处理
		elseif($paren_nest == $min_paren_nest && ':' == $token_str && NULL === $token_type){
			$colon_flag = 1;
		}
		//遇到分号或大括号，不需要插入大括号，代码就地插入
		elseif($paren_nest == $min_paren_nest && in_array($token_str, array(';','{','}')) && NULL === $token_type){
			$brace_insert_offset = NULL;
			$code_insert_offset = $offset;
			break;
		}
		elseif(T_OPEN_TAG == $token_type){
			$brace_insert_offset = NULL;
			$code_insert_offset = $offset;
			break;
		}
		$offset -= strlen ($token_str);
	}
	
	//为单行代码追加大括号和插入代码块
	if($brace_insert_offset){
		$subject_behind_chp = substr($subject_behind_chp, 0, $brace_insert_offset) . '{' . $replacement. substr($subject_behind_chp, $brace_insert_offset);
		$subject_ahead_chp = preg_replace('/\$chprocess(.*?);/s', $ret_varname.';}', $subject_ahead_chp, 1);
	}else{
		$subject_behind_chp = substr($subject_behind_chp, 0, $code_insert_offset) . $replacement. substr($subject_behind_chp, $code_insert_offset);
		$subject_ahead_chp = preg_replace('/\$chprocess(.*?);/s', $ret_varname.';', $subject_ahead_chp, 1);
	}
	return str_replace('<?php ', '', $subject_behind_chp).$subject_ahead_chp;
}

function merge_contents_write($modid, $tplfile, $objfile){
//	global $___TEMP_final_func_contents, $___TEMP_func_contents;
//	//注意这里用的是$content不带s，代码历史原因懒得统一了
//	$content=file_get_contents($tplfile);
//	//不直接str_replace是因为怕某两个函数内容完全相同的时候会导致各种问题
//	foreach($___TEMP_func_contents[$modid] as $funckey => $funccont){
//		if($tplfile !== $funccont['filename']) continue;
//		if(strpos($content, $funccont[]) !==false){
//			$content = str_replace(, $___TEMP_final_func_contents[$modid][$funckey], $content)
//		}
//	}
//	$ret[$func_name]['file_contents_end'] = $offset;
//	writeover($objfile, $content);
}

//ADV2合并代码主函数
function merge_contents_calc($modid)
{
	global $___TEMP_func_contents;
	global $___TEMP_final_func_contents;//二维数组 modid=>函数名=>字符串
	global $___TEMP_last_ret_varname;//键名是函数名
	global $___TEMP_stored_func_contents;//键名是函数名，键值是执行到任意时刻那个函数所存下来的内容
	global $___TEMP_node_func_modname;//键名是函数名，键值是modname
	global $___TEMP_defined_funclist;
	global $___TEMP_modfuncs;
	global $modn, $___TEMP_flipped_modn;
	
	$___TEMP_final_func_contents[$modid] = array();
	
	foreach ($___TEMP_defined_funclist[$modid] as $key)//注意执行顺序和继承顺序完全不是一个概念
	{
		$key = strtolower(substr($key,strpos($key,'\\',0)+1));
		if('parse_news' !== $key) continue;
		//无父函数且无子函数的函数直接跳过
		if(empty($___TEMP_modfuncs[$modid][$key]['parent']) && !isset($___TEMP_stored_func_contents[$key])) continue;
		
		$contents = $___TEMP_func_contents[$modid][$key]['contents'];
		//先干掉eval字符串避免误处理
		$contents = str_replace('if (eval(__MAGIC__)) return $___RET_VALUE;', '<<<<<<EVAL>>>>>>', $contents);
//		preg_match('/\s*?if\s*?\(eval\(__MAGIC__\)\)\s*?return\s*?\$___RET_VALUE;\s*?$/s', $contents, $contents_evacode_temp);
//		$contents_evacode_temp = $contents_evacode_temp[0];
//		$contents = str_replace($contents_evacode_temp, '', $contents);
		
		//整体装进一个do...while(0)结构，方便用break模拟return
		$contents = 'do{'.$contents.'}while(0);';
		
		//取得preparse()记录的函数信息
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
			//清空暂存内容
			$tmp_stored_contents = $___TEMP_stored_func_contents[$key];
			$___TEMP_stored_func_contents[$key] = '';
			
			if(!empty($tmp_stored_contents)){
				//import_module()处理
				//把父函数第一层定义的模块名全部从暂存内容处理掉
				//如果父函数有定义则用父函数的import_modules模块名，否则（根函数）用本函数的
				$im_diff_str = !empty($parent_func_contents['imported_modules']) ? $parent_func_contents['imported_modules'] : $___TEMP_func_contents[$modid][$key]['imported_modules'];
				$im_diff_arr = array();
				foreach(explode(',', $im_diff_str) as $val){
					$im_diff_arr[] = trim(str_replace("'",'',$val));
				}
				foreach($im_diff_arr as $pim_val){
					$tmp_stored_contents = preg_replace('/import_module\((.*?),*?'."'".$pim_val."'".'(.*?)\)/s', 'import_module($1$2)', $tmp_stored_contents);
				}
				$tmp_stored_contents = preg_replace('/import_module\(\s*?,(.*?)\)/s', 'import_module($1)', $tmp_stored_contents);
				$tmp_stored_contents = preg_replace('/import_module\(\s*?\);/s', '', $tmp_stored_contents);
			
				//如果有暂存内容，则先用暂存内容替换节点内容里的$chprocess
				//节点函数有两个以上$chprocess的情况下，不存在暂存内容
				$contents = merge_replace_chprocess($___TEMP_last_ret_varname[$key], $tmp_stored_contents, $contents);
				unset($tmp_stored_contents);
			}
			
			//将本函数内容里的$chprocess替换为上一个节点
			$replacement = isset($___TEMP_node_func_modname[$key]) ? '\\'.$___TEMP_node_func_modname[$key].'\\'.$key : '';
			$contents = str_replace('$chprocess', $replacement, $contents);
			//消灭嵌套return造成的首尾相连赋值
			//不能消灭，虽然看着愚蠢，但是这就是尾递归展开导致
//			$i=0;
//			while($i<1000 && preg_match('/\$(\S+)\s*?=\s*?\$(\S+)\s*?;\s*?\$(\S+)\s*?=\s*?\$(\S+)\s*?;/s', $contents, $matches)){
//				if($matches[1] == $matches[4]) {
//					$contents = preg_replace('/\$'.$matches[1].'\s*?=\s*?\$'.$matches[2].'\s*?;\s*?\$'.$matches[3].'\s*?=\s*?\$'.$matches[4].'\s*?;/s', '$'.$matches[3].' = $'.$matches[2].';', $contents);
//				}
//				$i++;
//			}
			//将本mod记录为节点
			$___TEMP_node_func_modname[$key] = $modn[$modid];
			
			
			
			//最后把开头的eval字符串加回来
			$contents = str_replace('<<<<<<EVAL>>>>>>', 'if (eval(__MAGIC__)) return $___RET_VALUE;', $contents);
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
			//挺重要的，return是一种break，不能简单抹掉
			$ret_varname = '$'.$modn[$modid].'_'.$key.'_ret';
			$contents = preg_replace('/return\s*?;/s', $ret_varname." = NULL; break; ", $contents);
			$contents = preg_replace('/return\s*?(.*?);/s', $ret_varname." = $1; break; ", $contents);
			
			//$contents里的$chprocess(*)换成$$ret_varname，在暂存内容的前一行插入$contents
			
			//彻底去除eval字符串
			$contents = str_replace('<<<<<<EVAL>>>>>>', '', $contents);
			
			//$___TEMP_stored_func_contents没内容时直接暂存本函数内容
			//也即本函数有2个以上的$chprocess
			if(empty($___TEMP_stored_func_contents[$key])) {
				$___TEMP_stored_func_contents[$key] = $contents;
			//$contents里只有1个$chprocess，先用$sfc替换本函数$chprocess，再将本函数全部暂存
			} else {
				$___TEMP_stored_func_contents[$key] = merge_replace_chprocess($___TEMP_last_ret_varname[$key], $___TEMP_stored_func_contents[$key], $contents);
			}
			
			//本函数内容清空，只留跳转
			$contents = $func_info['evacode'];
			
			//记录这一个函数的返回值名称
			$___TEMP_last_ret_varname[$key] = $ret_varname;
			
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
	if(isset($___TEMP_func_contents[$modid])) $___TEMP_func_contents[$modid] = array_merge($___TEMP_func_contents[$modid], analyze_function_info(file_get_contents($tplfile)));
	else $___TEMP_func_contents[$modid] = analyze_function_info(file_get_contents($tplfile));
}

/* End of file modulemng.codeadv2.func.php */
/* Location: /include/modulemng/modulemng.codeadv2.func.php */