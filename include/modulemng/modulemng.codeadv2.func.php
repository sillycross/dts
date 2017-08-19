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
	global $___MOD_CODE_COMBINE;
	if($___MOD_CODE_COMBINE){
		$i2=$i;
		$ret = '';
	}else{
		$funcname = parse_get_funcname($content,$i2);
		$i2=$i;	
		$ret = get_magic_content(strtolower($funcname), $modid);
		//测试
		//统计函数调用个数
		//$ret='global $___TEMP_CALLS_COUNT; $___TEMP_CALLS_COUNT[\''.$funcname.'\']=1; '.$ret;
		$ret=str_replace("\n",' ',$ret);
	}
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

//妈的php简直神经病
function left_brace_check($token_str, $token_type)
{
	$ret = false;
	if(('{' == $token_str && NULL === $token_type) || T_CURLY_OPEN == $token_type || T_DOLLAR_OPEN_CURLY_BRACES == $token_type)
		$ret = true;
	return $ret;
}

//返回以offset为键名，包含brace、paren信息的增强型$tokens
//paren：本级圆括号前导符的类型、类型2和键名
//loop：本级for foreach while等循环的类型、键名和是否有大括号
function token_get_all_adv($code){
	$ret = array();
	$offset = 0;
	$brace_list = $paren_list = array();
	$last_nw_token = $last_nw_token_2 = $last_cp_token = NULL;
	$tokens = token_get_all($code);
	foreach($tokens as $token){
		$token_str = is_array( $token ) ? $token[1] : $token;
		$token_type = is_array( $token ) ? $token[0] : NULL;
		if(')' == $token_str && NULL === $token_type){
  		array_pop($paren_list);
  	}elseif('}' == $token_str && NULL === $token_type){
  		array_pop($brace_list);
  	}
		$ret[$offset] = array(
  		'type' => $token_type,
  		'str' => $token_str,
  		'paren' => sizeof($paren_list),
  		'brace' => sizeof($brace_list),
  		'last_nw_token' => $last_nw_token,
  		'last_nw_token_2' => $last_nw_token_2,
  		'last_cp_token' => $last_cp_token
  	);
  	if('(' == $token_str && NULL === $token_type){
  		$paren_list[] = $last_nw_token;
  	}elseif(left_brace_check($token_str, $token_type)){
  		$brace_list[] = $last_cp_token;
  	}
  	if(T_WHITESPACE !== $token_type){
			$last_nw_token_2 = $last_nw_token;
			$last_nw_token = $offset;
		}
		if(in_array($token_type, array(T_IF, T_ELSE, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_DO))){
			$last_cp_token = $offset;
		}
		$offset += strlen($token_str);
	}
	return $ret;
}

//获取一个文件里所有函数信息
//返回数组，键名为函数名，键值为数组，包含vars=>变量字符串，及contents=>函数内容字符串，及import_module=>载入模块字符串
function analyze_function_info($subject, $filename){
	$ret = array();
	$tokens = token_get_all_adv($subject);
	$tmp_func_list = array();
	$imported_module_paren = NULL;
	foreach($tokens as $token){
		if(!empty($tmp_func_list) && is_array($tmp_func_list[sizeof($tmp_func_list)-1])){
			$func_brace = $tmp_func_list[sizeof($tmp_func_list)-1][0];
			$func_paren = $tmp_func_list[sizeof($tmp_func_list)-1][1];
			$func_name = $tmp_func_list[sizeof($tmp_func_list)-1][2];
			
			if(!isset($ret[$func_name])) {//初始化$ret内容。每个函数所处的filename是在这里定义的
				$ret[$func_name] = array('vars' => '', 'contents' => '', 'filename' => $filename);
			}
			if($func_brace < $token['brace']) {//大括号层数比函数定义时要大，则认为在函数内部，记录函数内容
				$ret[$func_name]['contents'] .= $token['str'];
	  	} elseif($func_brace >= $token['brace']) {
	  		if('}' == $token['str'] && NULL == $token['type']) {//回到函数定义时的大括号层数时，认为跳出了函数
	  			array_pop($tmp_func_list);
	  		}elseif($func_paren < $token['paren']) {//圆括号层数大于函数定义时，认为在函数参数括号内
	  			$ret[$func_name]['vars'] .= $token['str'];
	  		}
	  	}
		}
		if(T_STRING == $token['type'] && $token['last_nw_token']){
			$last_nw_token = $tokens[$token['last_nw_token']];
			if(T_FUNCTION == $last_nw_token['type']
			&& empty($tmp_func_list)) {//闭包请褪裙
				$tmp_func_list[] = array($token['brace'], $token['paren'], $token['str']);
			}
		}
	}
  return $ret;
}

//通过preg_match初判，然后通过token验证
//$reg_patt为正则表达式，$tok_patt为$reg_patt每个子模式所对应的解析器代号，可以用数组来表示or关系
//$code须传入开头带有<?php标识符的代码，$subject无所谓，下同
function token_match($reg_patt, $tok_patt, $code){
	$ret = array();
	$tokens = token_get_all_adv($code);
	if(sizeof($tokens) <= 1) return false;
	$reg_count = preg_match_all($reg_patt, $code, $reg_matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
	if(!$reg_count) return false;
	foreach($reg_matches as $rmval){//每一个匹配结果
		$match_flag = true;
		foreach($rmval as $rno => $rarr){//每一个子模式
			if(!$rno) continue;
			list($rstr, $roff) = $rarr;
			$o_token = token_get_single_adv($roff, $tokens);
			$tok_type = $tok_patt[$rno-1];
			//$tok_patt支持数组，此时认为是or关系
			if(!is_array($tok_type)) $tok_type = array($tok_type);
			$match_flag = false;
			foreach($tok_type as $tt){//每一个解析器代号
				//解析器代号为'*'的时候忽略这个代号
				if('*' == $tt || (is_array($o_token) && $o_token['type'] === $tt)){
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

//返回从$offset所在的$token
function token_get_single_adv($offset, $tokens){
	$tokens_key = array_keys($tokens);
	foreach($tokens_key as $kkey => $kval){
		if($kval > $offset){
			return $tokens[$tokens_key[$kkey-1]];
		}
	}
}

//单行代码加花括号
//输入$reg_pat, $tok_pat，规则同token_match相同
//如果加花括号，则返回花括号及之前、原单行代码、花括号及之后三部分代码
//如果不加花括号，则返回分号及之前、原单行代码、分号之后三部分代码
function merge_add_braces($reg_pat, $tok_pat, $subject){
	if(strpos($subject, '<?php')===false){
		$subject = '<?php '.$subject;
	}
	$reg_ret = token_match($reg_pat, $tok_pat, $subject);
	//无结果直接返回
	if(!$reg_ret) return array(str_replace('<?php ', '', $subject), '', '');
	//按结果出现的位置分成两段
	$subject_behind = substr($subject, 0, $reg_ret[0][0][1]);
	$subject_ahead = substr($subject, $reg_ret[0][0][1]);
	
	//从前段末尾开始向前逐个$token判定
	$offset = strlen($subject_behind);
	$tokens = token_get_all($subject_behind);
	$paren_nest = 0;
	$min_paren_nest = 0;
	$colon_flag = 0;
	$brace_insert_offset = NULL;
	$code_insert_offset = 0;
	//判定前段插入位置
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
	//判定后段分号位置
	$offset = 0;
	$subject_ahead = '<?php '.$subject_ahead;
	$semi_offset = strlen($subject_ahead);
	$tokens = token_get_all($subject_ahead);
	foreach($tokens as $token){
		$token_str = is_array( $token ) ? $token[1] : $token;
		$token_type = is_array( $token ) ? $token[0] : NULL;
		if(';' == $token_str && NULL === $token_type) {
			$semi_offset = $offset;
			break;
		}
		$offset += strlen($token_str);
	}
	
	//生成返回三段
	if($brace_insert_offset){
		$ret_behind = substr($subject_behind, 0, $brace_insert_offset) . '{';
		$ret_middle = substr($subject_behind, $brace_insert_offset). substr($subject_ahead, 0, $semi_offset + 1);
		$ret_ahead = '}' . substr($subject_ahead, $semi_offset + 1);
	}else{
		$ret_behind = substr($subject_behind, 0, $code_insert_offset);
		$ret_middle = substr($subject_behind, $code_insert_offset). substr($subject_ahead, 0, $semi_offset + 1);
		$ret_ahead = substr($subject_ahead, $semi_offset + 1);
	}
	$ret_behind = str_replace('<?php ', '', $ret_behind);
	$ret_middle = str_replace('<?php ', '', $ret_middle);
	$ret_ahead = str_replace('<?php ', '', $ret_ahead);
	return array($ret_behind, $ret_middle, $ret_ahead);
}

//识别并替换第一个特定字符串+正确的前后括号的内容
//返回array('A', '所找元素', 'C', '(括号内容)', 'E')形式的数组
function merge_split_paren($find_str, $find_type, $subject, &$brace_nest=0, &$block_min_brace_nest=0){
	if(strpos($subject, '<?php')===false)
		$tokens = token_get_all('<?php '.$subject);
	else
		$tokens = token_get_all($subject);
	$paren_nest = 0;
	$tmp_paren_nest = 0;
	$block_min_brace_nest = 999;
	$pos_state = 0;
	$ret = array(0=>'', 1=>'', 2=>'', 3=>'', 4=>'');
	foreach($tokens as $token){
		$token_str = is_array( $token ) ? $token[1] : $token;
		$token_type = is_array( $token ) ? $token[0] : NULL;
		if(T_OPEN_TAG == $token_type) continue;
		//元素本身
		if(0 == $pos_state && $token_str == $find_str && $token_type == $find_type) {
			$pos_state = 1;
		}
		//元素之后到前圆括号
		if(1 == $pos_state && ($token_str != $find_str || $token_type != $find_type)){
			$pos_state = 2;
		}
		//前圆括号
		if('(' == $token_str && NULL === $token_type){
			$paren_nest++;
			if(2 == $pos_state){				
				$pos_state = 3;
				$tmp_paren_nest = $paren_nest - 1;
			}
		}
		//后圆括号
		if(')' == $token_str && NULL === $token_type){
			$paren_nest--;
			if(3 == $pos_state && $tmp_paren_nest == $paren_nest){				
				$pos_state = 4;
			}
		}
		if(left_brace_check($token_str, $token_type)){ //php这个判定也是醉
  		$brace_nest ++;
  	}elseif('}' == $token_str && NULL === $token_type){
  		$brace_nest --;
  	}
  	if($brace_nest < $block_min_brace_nest) $block_min_brace_nest = $brace_nest;
  	if(4 == $pos_state){
  		$ret[$pos_state] .= substr($subject, strlen($ret[0].$ret[1].$ret[2].$ret[3].$ret[4]));
  		break;
  	}else{
  		$ret[$pos_state] .= $token_str;
  	}
	}
	if(!empty($ret[4])) {
		$ret[3] .= substr($ret[4], 0, 1);
		$ret[4] = substr($ret[4], 1);
	}
	return $ret;
}

//用$replacement（代码块字符串）替换$subject里的第一个$chprocess()
//并非简单替换，要在$chprocess()所在行的前一行先给$ret赋值，然后用$ret_varname替换$chprocess()
//为了正常运行，需要先给单行代码块加花括号
function merge_replace_chprocess($ret_varname, $replacement, $subject){
	//获得三段
	list($ret_behind, $ret_middle, $ret_ahead) = merge_add_braces('/(\$chprocess)/s', array(T_VARIABLE), $subject);
	//真正插入步骤
	//$ret_middle = $replacement . preg_replace('/\$chprocess(.*?)/s', $ret_varname.';', $ret_middle, 1);
	//不能直接preg_replace，需要判定括号层数！
	//不存在$chprocess直接返回
	if(empty($ret_middle)) return $ret_behind;
	else{
		list($ret_a, $ret_b, $ret_c, $ret_d, $ret_e) = merge_split_paren('$chprocess', T_VARIABLE, $ret_middle);
		$ret_middle = $replacement . $ret_a . $ret_varname . $ret_e;
		return $ret_behind . $ret_middle . $ret_ahead;
	}	
}

//TODO：需要获得break跳出的层数否则毫无意义
//把所有的return换成${$ret_varname}=xxx;break;的形式
function merge_replace_return($ret_varname, $subject){
	if(strpos($subject, '$ret = \'\'')!==false) $flag = 1;
	do{
		list($ret_behind, $ret_middle, $ret_ahead) = merge_add_braces('/(return)/s', array(T_RETURN), $subject);
		$ret_middle = preg_replace('/return\s*?;/s', $ret_varname." = NULL;\r\nbreak; ", $ret_middle);
		$ret_middle = preg_replace('/return\s*?(.*?);/s', $ret_varname." = $1;\r\nbreak; ", $ret_middle);
		$subject = $ret_behind . $ret_middle . $ret_ahead;
	}while(token_match('/(return)/s', array(T_RETURN), '<?php '.$subject));
	return $subject;
}

function merge_contents_write($modid, $tplfile, $objfile){
	global $___TEMP_final_func_contents;
	$contents=file_get_contents($tplfile);
	$tokens = token_get_all($contents);
	//不直接str_replace是因为怕某两个函数内容完全相同的时候会导致各种问题
	$func_name = '';
	$func_state = 0;//函数定义状态，0为定义范围外，1为监测到function语句，2为括号内部，3为花括号内部
	$brace_nest = 0;
	$temp_brace_nest = 0;
	$writing_contents = '';
  foreach($tokens as $token) {
  	$token_str = is_array($token) ? $token[1] : $token;
		$token_type = is_array($token) ? $token[0] : NULL;
		//函数花括号外部或者$___TEMP_final_func_contents里没有这个函数则正常记录
		if( 3 != $func_state || !$func_name || empty($___TEMP_final_func_contents[$modid][$func_name])) {
			$writing_contents .= $token_str;
		}
		//否则大括号内部不记录
  
  	if(T_FUNCTION == $token_type && 0===$func_state){//闭包请退群
  		$func_state = 1;
  		$temp_brace_nest = $brace_nest;
  	}elseif(T_STRING == $token_type){
  		if(1 == $func_state){//前面有function语句的情况下，提取函数名
  			$func_name = strtolower($token_str);
  		}
  	}elseif('(' == $token_str && NULL === $token_type){
  		if(1 == $func_state)
  			$func_state = 2;
  	}elseif(')' == $token_str && NULL === $token_type){
  		if(2 == $func_state){
  			$func_state = 1;
  		}
  	}elseif(left_brace_check($token_str, $token_type)){
  		$brace_nest ++;
  		if(1 == $func_state){
  			$func_state = 3;
  		}
  	}elseif('}' == $token_str && NULL === $token_type){
  		$brace_nest --;
  		if($brace_nest == $temp_brace_nest && 3 == $func_state){
  			if(!empty($___TEMP_final_func_contents[$modid][$func_name])){
	  			$writing_contents .= $___TEMP_final_func_contents[$modid][$func_name]."\r\n\t}";
	  		}
  			$func_name = '';
  			$func_state = 0;
  		}
  	}
  }
	
	writeover($objfile, $writing_contents);
}

//ADV2合并代码主函数
function merge_contents_calc($modid)
{
	global $___TEMP_func_contents;
	global $___TEMP_final_func_contents;//二维数组 modid=>函数名=>字符串
	global $___TEMP_last_ret_varname;//键名是函数名
	global $___TEMP_stored_func_contents;//键名是函数名，键值是执行到任意时刻那个函数所暂存的内容
	global $___TEMP_node_func_modname;//键名是函数名，键值是modname
	global $___TEMP_defined_funclist;
	global $___TEMP_modfuncs;
	global $modn, $___TEMP_flipped_modn;
	
	$___TEMP_final_func_contents[$modid] = array();
	
	//先提取该mod全部函数名
	$tmp_mod_funcname_list = array();
	foreach($___TEMP_defined_funclist[$modid] as $key){
		$tmp_mod_funcname_list[] = strtolower(substr($key,strpos($key,'\\',0)+1));
	}
	//对该mod的每个函数顺序执行整个判定，不涉及文件（内容之前就读到内存里了）
	//注意执行顺序和继承顺序完全不是一个概念
	foreach ($___TEMP_defined_funclist[$modid] as $key)
	{
		$key = strtolower(substr($key,strpos($key,'\\',0)+1));
		//无父函数且无子函数的函数直接跳过
		if(empty($___TEMP_modfuncs[$modid][$key]['parent']) && !isset($___TEMP_stored_func_contents[$key])) continue;
		
		$contents = $___TEMP_func_contents[$modid][$key]['contents'];
		//干掉eval字符串，因为不需要了
		$contents = str_replace('if (eval(__MAGIC__)) return $___RET_VALUE;', '', $contents);
		
		
		$contents = '<?php '.$contents;
		//函数内容里直接引用本模块的函数名需要加上namespace
		foreach($tmp_mod_funcname_list as $cmfn){
			$contents = token_replace('/([^\\\\A-Za-z0-9_])('.$cmfn.')\s*?\(/si', array('*',T_STRING), '$1\\\\'.$modn[$modid].'\\\\$2 (', $contents);
			//if($cmfn == 'attr_dmg_check_not_WPG') writeover('a.txt', $contents."\r\n\r\n", 'ab+');
		}
		//函数里的__DIR__要改为绝对路径
		$contents = token_replace('/(__DIR__)/s', array(T_DIR), '\''.pathinfo($___TEMP_func_contents[$modid][$key]['filename'], PATHINFO_DIRNAME).'\'', $contents);		
		$contents = str_replace('<?php ', '', $contents);
		
		//取得preparse()记录下的函数信息
		$func_info = $___TEMP_modfuncs[$modid][$key];
		if(empty($___TEMP_last_ret_varname[$key])) $___TEMP_last_ret_varname[$key] = 'NULL';
		if(empty($___TEMP_node_func_modname[$key])) $___TEMP_node_func_modname[$key] = '__MODULE_NULLMOD__';
		
		//节点判断步骤
		//判断目的：若无父函数或者父函数里有2个以上的$chprocess，则把暂存的内容全部写在本函数，之后清空暂存内容
		//若父函数里只有1个$chprocess，则本函数可以直接合并进父函数，因而本函数暂存内容，只留跳转
		$node_this = true;
		if($func_info['parent']){
			$parent = explode('\\',$func_info['parent']);
			$parent_modname = $parent[0] ? $parent[0] : $parent[1];
			$parent_func_contents = $___TEMP_func_contents[intval($___TEMP_flipped_modn[$parent_modname])][$key];
			$cc = substr_count($parent_func_contents['contents'], '$chprocess(');
			if($cc <= 1) $node_this = false; 
		}
		
		//进入处理步骤
		if($node_this)//该函数为根函数或者父函数有2个以上的chprocess，则把这个函数作为节点
		{
			if(strpos($___TEMP_stored_func_contents[$key], "\$ret='未知'")!==false) $ffflag = 1;
			$tmp_stored_contents = $___TEMP_stored_func_contents[$key];
			$___TEMP_stored_func_contents[$key] = '';
			if(!empty($tmp_stored_contents)){
				//如果有暂存内容，则先用暂存内容替换节点内容里的$chprocess
				//节点函数有两个以上$chprocess的情况下，不存在暂存内容，所以不用考虑
				$contents = merge_replace_chprocess($___TEMP_last_ret_varname[$key], $tmp_stored_contents, $contents);
				unset($tmp_stored_contents);
			}
			//将本函数内容里的$chprocess替换为上一个节点
			if(isset($___TEMP_node_func_modname[$key]) && '__MODULE_NULLMOD__' != $___TEMP_node_func_modname[$key]){
				$replacement = '\\'.$___TEMP_node_func_modname[$key].'\\'.$key;
			}else{
				$replacement = '__MODULE_NULLFUNCTION__';
			}
			$contents = str_replace('$chprocess', $replacement, $contents);
			
			//import_module()处理
			//每一个大括号分支内去除重复的import_module()
			$tmp_im_subject = $contents;
			$tmp_im_ret = '';
			$im_brace_nest = 0;
			$im_last_brace_nest = 999;
			$im_diff_arr = array();
			do{
				list($ret_a, $ret_b, $ret_c, $ret_d, $ret_e) = merge_split_paren('import_module', T_STRING, $tmp_im_subject, $im_brace_nest, $im_block_min_brace_nest);
				if(!empty($ret_d)){
					if($im_block_min_brace_nest < $im_last_brace_nest){//外层import_module，记录为$im_diff_arr
						foreach(explode(',', substr($ret_d,1,-1)) as $val){
							$im_diff_arr[] = trim($val);
						}
						$tmp_im_ret .= $ret_a . $ret_b . $ret_c . $ret_d;
						$tmp_im_subject = $ret_e;
					}else{
						$tmp_im_parencont = explode(',',substr($ret_d, 1, -1));
						$im = sizeof($tmp_im_parencont);
						for($i=0;$i<$im;$i++){
							$tmp_im_parencont[$i] = trim($tmp_im_parencont[$i]);
						}
						$tmp_im_parencont = array_diff($tmp_im_parencont, $im_diff_arr);
						if(!empty($tmp_im_parencont)){
							$tmp_im_ret .= $ret_a . $ret_b . $ret_c . '('.implode(',', $tmp_im_parencont).')';
							$tmp_im_subject = $ret_e;	
						}else{
							$tmp_im_ret .= $ret_a;
							$tmp_im_subject = $ret_e;
						}		
					}
					$im_last_brace_nest = $im_brace_nest;			
				}else{
					$tmp_im_ret .= $tmp_im_subject;
					$tmp_im_subject = '';
				}
				//$tmp_im_ret .= "/* $im_brace_nest $im_block_min_brace_nest $im_last_brace_nest*/";
			} while (!empty($tmp_im_subject));
			$contents = str_replace('eval();', '', $tmp_im_ret);		
			
			//上一个节点内容前面加入跳转
			$last_modid = intval($___TEMP_flipped_modn[$___TEMP_node_func_modname[$key]]);
			$jump_str = __MAGIC_CODEADV2__;
			preg_match_all("/if\s*?\(.+\)\s*?\{\s*?([\s\S]*)\s*?\}/i",$jump_str,$matches);
			$jump_str = $matches[1][0];
			$jump_str = str_replace('_____TEMPLATE_MAGIC_CODEADV2_INIT_DESIRE_PARENTNAME_____','\''.$modn[$modid].'\\'.$key.'\'',$jump_str);
			$jump_str = str_replace('_____TEMPLATE_MAGIC_CODEADV2_INIT_EVACODE_____',$func_info['evacode'],$jump_str);
			$___TEMP_final_func_contents[$last_modid][$key] 
				= $jump_str	."\r\n\t\t". $___TEMP_final_func_contents[$last_modid][$key];
			
			//将本mod记录为节点
			$___TEMP_node_func_modname[$key] = $modn[$modid];
			
			//最后把开头的eval字符串加回来
			//$contents = str_replace('<<<<<<EVAL>>>>>>', 'if (eval(__MAGIC__)) return $___RET_VALUE;', $contents);
		}
		else//该函数的父函数只有1个chprocess，对该函数的内容处理以后作暂存，清空该函数内容
		{			
			//参数处理
			list($ret_a, $ret_b, $ret_c, $ret_d, $ret_e) = merge_split_paren('$chprocess', T_VARIABLE, $parent_func_contents['contents']);
			//if($key == 'check_corpse_discover') writeover('a.txt', $ret_d.'<br>', 'ab+');
			$parent_chpvars = substr($ret_d, 1, -1);
			$parent_varname_change = '';
			if(!empty($parent_chpvars)){
				$vars_arr = explode(',',$___TEMP_func_contents[$modid][$key]['vars']);
				$parent_chpvars_arr = explode(',',$parent_chpvars);
				$count_vars_arr = count($vars_arr);
				//用子函数的参数作循环判断
				for($i=0; $i < $count_vars_arr; $i++){
					$vars_arr_single = trim($vars_arr[$i]);
					if(strpos($vars_arr_single, '=')!==false){//默认值
						list($vars_arr_varname, $var_arr_default) = explode('=',$vars_arr_single);
						$vars_arr_varname = trim($vars_arr_varname);
						$var_arr_default = trim($var_arr_default);
					}else{
						$vars_arr_varname = trim($vars_arr_single);
						$var_arr_default = NULL;
					}
					if(strpos($vars_arr_varname, '&')===0){//引用
						$ref_flag = 1;
						$vars_arr_varname = trim(substr($vars_arr_varname, 1));
					}else{
						$ref_flag = 0;
					}
					$parent_chpvars_arr_single = isset($parent_chpvars_arr[$i]) ? trim($parent_chpvars_arr[$i]) : NULL;
					//情况1，父函数调用时子函数的变量名与子函数定义的不同
					if(NULL !== $parent_chpvars_arr_single && $parent_chpvars_arr_single != $vars_arr_varname){
						if($ref_flag){
							$parent_varname_change .= $vars_arr_varname . ' = &' . $parent_chpvars_arr_single . '; ';
						}else{
							$parent_varname_change .= $vars_arr_varname . ' = ' . $parent_chpvars_arr_single . '; ';
						}
					//情况2，父函数调用时没有给出这一变量
					}elseif(NULL === $parent_chpvars_arr_single){
						if(NULL !== $var_arr_default){
							$parent_varname_change .= $vars_arr_varname . ' = ' . $var_arr_default . '; ';
						}else{
							//故意产生错误提示
							$parent_varname_change .= $vars_arr_varname . ' = __MISSING_VARIABLE_' . substr($var_arr_default,1) . '; ';
						}
					}
				}
				//writeover('3.txt', var_export($parent_varname_change,1).' '.$modn[$modid]."\r\n",'ab+');
			}
			if($parent_varname_change) $parent_varname_change .= "\r\n";
			$contents = $parent_varname_change . $contents;
			
			//unset所有变量
			//这个可以回头再说
			
			//return处理			
			//整体装进一个do...while(0)结构，方便用break模拟return
			$contents = "\r\n\t\t//========Contents from mod {$modn[$modid]}========\r\n\t\tdo{\r\n\t\t\t".$contents."\t}while(0);";
			//开头初始化$$ret_varname以避免未初始化的notice
			$ret_varname = '$'.$modn[$modid].'_'.$key.'_ret';
			$contents = $ret_varname." = NULL;\r\n".$contents;
			//之后把return换为$$ret_varname并且加break;
			$contents = merge_replace_return($ret_varname, $contents);			
			
			//彻底去除eval字符串
			//$contents = str_replace('<<<<<<EVAL>>>>>>', '', $contents);
			
			//$___TEMP_stored_func_contents没内容时直接暂存本函数内容
			//也即本函数有2个以上的$chprocess
			if(empty($___TEMP_stored_func_contents[$key])) {
				$___TEMP_stored_func_contents[$key] = $contents;
				
			//$contents里只有1个$chprocess，先用$sfc替换本函数$chprocess，再将本函数全部暂存
			} else {
				$___TEMP_stored_func_contents[$key] = merge_replace_chprocess($___TEMP_last_ret_varname[$key], $___TEMP_stored_func_contents[$key], $contents);
			}
			//本函数内容清空，只留跳转
			$contents = "\r\n\t\t".$func_info['evacode'];
			
			//记录这一个函数的返回值名称
			$___TEMP_last_ret_varname[$key] = $ret_varname;
			
		}
		
		$___TEMP_final_func_contents[$modid][$key] = $contents;
	}
}


function parse($modid, $tplfile, $objfile)
{
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
	global $___MOD_CODE_COMBINE;
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
	if($___MOD_CODE_COMBINE){
		if(isset($___TEMP_func_contents[$modid])) $___TEMP_func_contents[$modid] = array_merge($___TEMP_func_contents[$modid], analyze_function_info(file_get_contents($tplfile), $tplfile));
		else $___TEMP_func_contents[$modid] = analyze_function_info(file_get_contents($tplfile), $tplfile);
	}
}

/* End of file modulemng.codeadv2.func.php */
/* Location: /include/modulemng/modulemng.codeadv2.func.php */