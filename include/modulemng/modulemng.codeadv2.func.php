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
	//不global没有用到的变量
	//似乎不行，有些地方变量名不是静态
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

//识别3种类型的前花括号token
//妈的php简直神经病
function left_brace_check($token_str, $token_type)
{
	$ret = false;
	if(('{' == $token_str && NULL === $token_type) || T_CURLY_OPEN == $token_type || T_DOLLAR_OPEN_CURLY_BRACES == $token_type)
		$ret = true;
	return $ret;
}

//返回以offset为键名，包含brace、paren信息的增强型$tokens
//paren：所在圆括号层数
//brace：所在大括号层数
//line_start：所在行的开头（注意是代码意义上的行，也即上一个;{}的结尾）
//last_nw_token：前一个非T_WHTIESPACE的元素的偏移量
//last_nw_token_2：往前数第2个非T_WHTIESPACE的元素的偏移量
//last_cp_token：所在流程控制符的偏移量
function token_get_all_adv($code){
	$ret = array();
	$offset = 0;
	//括号和控制类型层数是用数组元素数来计算
	$brace_list = $paren_list = $cp_list = array();
	$last_nw_token = $last_nw_token_2 = $last_cp_token = NULL;
	$line_start = 0;
	$tokens = token_get_all($code);
	foreach($tokens as $token){
		$token_str = is_array( $token ) ? $token[1] : $token;
		$token_type = is_array( $token ) ? $token[0] : NULL;
		//后括号的位置都视为上一级，与前括号一致，所以需要在储存前就减掉括号层数
		if(')' == $token_str && NULL === $token_type){
  		array_pop($paren_list);
  	}elseif('}' == $token_str && NULL === $token_type){
  		array_pop($brace_list);
  	}
		$ret[$offset] = array(
  		'type' => $token_type,
  		'str' => $token_str,
  		'line_start' => $line_start,
  		'paren' => sizeof($paren_list),
  		'brace' => sizeof($brace_list),
  		'last_nw_token' => $last_nw_token,
  		'last_nw_token_2' => $last_nw_token_2,
  		'last_cp_token' => isset($cp_list[sizeof($cp_list)-1]) ? $cp_list[sizeof($cp_list)-1][1] : NULL
  	);
  	//前括号的位置视为上一级，那么在储存之后才增加括号层数
  	if('(' == $token_str && NULL === $token_type){
  		$paren_list[] = $last_nw_token;
  	}elseif(left_brace_check($token_str, $token_type)){
  		//前大括号，对应的控制层数的括号开关修改
  		if(!empty($cp_list) && sizeof($brace_list) == $cp_list[sizeof($cp_list)-1][2]){
  			$cp_list[sizeof($cp_list)-1][0] = true;
  		}
  		$brace_list[] = $last_cp_token;
  		$line_start = $offset + strlen($token_str);
  	}elseif('}' == $token_str && NULL === $token_type){
  		//与括号层数不同，前后括号的控制层数与控制符一致，所以必须在储存之后再增减
  		if(!empty($cp_list) && $cp_list[sizeof($cp_list)-1][0] && sizeof($brace_list) == $cp_list[sizeof($cp_list)-1][2]){
  			array_pop($cp_list);
  			while(!empty($cp_list)){
					$last_cp = array_pop($cp_list);
					if($last_cp[0]){
						$cp_list[] = $last_cp;
						break;
					}
				}
				$line_start = $offset + strlen($token_str);
  		}
  	}elseif(in_array($token_type, array(T_IF, T_ELSE, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_DO, T_CASE, T_DEFAULT))){
			$cp_list[] = array(false, $offset, sizeof($brace_list), sizeof($paren_list));//第一个变量为是不是有大括号，第二个变量是偏移量，第三个偏移量为所在大括号层数
		}elseif(';' == $token_str && NULL === $token_type){
			if(empty($cp_list) || (!empty($cp_list) && sizeof($paren_list) == $cp_list[sizeof($cp_list)-1][3])){
				while(!empty($cp_list)){
					$last_cp = array_pop($cp_list);
					if($last_cp[0]){
						$cp_list[] = $last_cp;
						break;
					}
				}
				$line_start = $offset + strlen($token_str);
			}
		}
  	if(T_WHITESPACE !== $token_type){
			$last_nw_token_2 = $last_nw_token;
			$last_nw_token = $offset;
		}
		$offset += strlen($token_str);
	}
	return $ret;
}

//将函数参数格式化为数组方便判定
function var_str2arr($varstr){
	if(!$varstr || false === strpos($varstr, '$')) return array();
	$ret = array();
	$vararr0 = explode(',', $varstr);
	foreach($vararr0 as $vval){
		$vret = array();
		if(strpos($vval, '=') !== false){
			$vval = explode('=', $vval);
			$vret['name'] = trim($vval[0]);
			$vret['default'] = trim($vval[1]);
		}else{
			$vret['name'] = trim($vval);
		}
		if(strpos($vret['name'], '&') !== false){
			$vret['name'] = trim(str_replace('&', '', $vret['name']));
			$vret['ref'] = true;
		}
		$ret[] = $vret;
	}
	return $ret;
}

//获取一个文件里所有函数信息
//返回数组，键名为函数名，键值为数组，包含vars=>变量字符串，及contents=>函数内容字符串，及import_module=>载入模块字符串，以及文件名
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
			
			//大括号层数比函数定义时要大，则认为在函数内部，记录函数内容
			//不记录注释。因为这里读的是include文件夹下的源文件，注释没有删掉，所以还得再清一遍注释
			if($func_brace < $token['brace'] && T_COMMENT !== $token['type'] && T_DOC_COMMENT !== $token['type']) {
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
				$tmp_func_list[] = array($token['brace'], $token['paren'], strtolower($token['str']));
			}
		}
	}
	//格式化参数
	foreach($ret as &$rval){
		$rval['vars'] = var_str2arr($rval['vars']);
	}
  return $ret;
}

//通过preg_match初判，然后通过token验证
//$reg_patt为正则表达式，$tok_patt为$reg_patt每个子模式所对应的token代号，可以用数组来表示or关系
//$code不会自动增加<?php标识符，$subject无所谓，下同
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
			foreach($tok_type as $tt){//每一个token代号
				//token代号为'*'的时候忽略这个代号
				if('*' == $tt || (is_array($o_token) && $o_token['type'] === $tt)){
					$match_flag = true;
					break;
				}
			}
			//有任何不匹配时直接放弃这个匹配结果，进入下一个匹配结果的判断
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
//输入的$tokens必须是token_get_all_adv()得到的
function token_get_single_adv($offset, $tokens, &$realoffset=0){
	$tokens_key = array_keys($tokens);
	if(NULL===$offset) return NULL;
	do{
		if(isset($tokens[$offset])) {
			$realoffset = $offset;
			return $tokens[$offset];
		}
		$offset--;
	}while($offset >= 0);
}

//给某个特定位置的元素自动加花括号
//如果加花括号，则返回花括号及之前、原单行代码、花括号及之后三部分代码
//如果不加花括号，则返回分号及之前、原单行代码、分号之后三部分代码
function merge_add_braces_core($focus_offset, $code){
	//按结果出现的位置分成两段
	$code_behind = substr($code, 0, $focus_offset);
	$code_ahead = substr($code, $focus_offset);
	
	//前段判定
	$tokens = token_get_all_adv($code);
	$tmp_token = token_get_single_adv($focus_offset , $tokens);
	do{
		$cp_token = token_get_single_adv($tmp_token['last_cp_token'], $tokens, $cp_offset);
		//本行开头在控制符之后，则直接加在本行开头，不需要加大括号
		if(NULL === $tmp_token['last_cp_token'] || $tmp_token['last_cp_token'] < $tmp_token['line_start']) {
			$brace_insert_offset = NULL;
			$code_insert_offset = $tmp_token['line_start'];
			break;
		}
		//case x:和default:直接加在冒号之后，不需要加大括号
		elseif(in_array($cp_token['type'], array(T_CASE, T_DEFAULT))){
			$colon_num = 0;//为了防止case后面带三目运算符，得这么处理
			foreach($tokens as $kkey => $token){
				if($kkey < $cp_offset) continue;
				if('?' == $token['str'] && NULL == $token['type']) {
					$colon_num -= 1;
				}elseif(':' == $token['str'] && NULL == $token['type']){
					$colon_num += 1;
				}
				if($colon_num >= 1){
					$brace_insert_offset = NULL;
					$code_insert_offset = $kkey + 1;
					break 2;
				}
			}
		}
		//否则加在控制符之后
		else{
			//有括号的控制符，判定三种情况：替换对象在括号内、替换对象在括号之后但是替代写法、替换对象在括号之后且不是替代写法
			if(in_array($cp_token['type'], array(T_IF, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE))){
				$tmp_paren_state = 0;
				foreach($tokens as $kkey => $token){
					if($kkey < $cp_offset) continue;
					//替换对象在括号内，将$cp_token迭代之后重新判定
					if(1==$tmp_paren_state && $kkey >= $focus_offset){
						$tmp_token = $cp_token;
						continue 2;
					}
					if(2==$tmp_paren_state) {
						if(':' == $token['str'] && NULL == $token['type']){//控制流程替代写法，不加大括号
							$brace_insert_offset = NULL;
							$code_insert_offset = $kkey + strlen($token['str']);
							break 2;
						}elseif(T_WHITESPACE !== $token['type']){//后圆括号以后遇到非空白字符/冒号则停止判定
							break 2;
						}
					}
					if($cp_token['paren'] == $token['paren'] && '(' == $token['str'] && NULL == $token['type']){
						$tmp_paren_state = 1;
					}elseif($cp_token['paren'] == $token['paren'] && ')' == $token['str'] && NULL == $token['type']){
						$tmp_paren_state = 2;
						$brace_insert_offset = $kkey + strlen($token['str']);
					}
				}
			}else{//没括号
				$brace_insert_offset = $cp_offset + strlen($cp_token['str']);
				break;
			}
		}
	}while(NULL !== $tmp_token['last_cp_token']);
	
	//判定后段分号位置
	$offset = 0;
	$code_ahead = '<?php '.$code_ahead;
	$semi_offset = strlen($code_ahead);
	$tokens = token_get_all($code_ahead);
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
		$ret_behind = substr($code_behind, 0, $brace_insert_offset) . '{';
		$ret_middle = substr($code_behind, $brace_insert_offset). substr($code_ahead, 0, $semi_offset + 1);
		$ret_ahead = '}' . substr($code_ahead, $semi_offset + 1);
	}else{
		$ret_behind = substr($code_behind, 0, $code_insert_offset);
		$ret_middle = substr($code_behind, $code_insert_offset). substr($code_ahead, 0, $semi_offset + 1);
		$ret_ahead = substr($code_ahead, $semi_offset + 1);
	}
	$ret_middle = str_replace('<?php ', '', $ret_middle);
	$ret_ahead = str_replace('<?php ', '', $ret_ahead);
	return array($ret_behind, $ret_middle, $ret_ahead);
}

//单行代码加花括号
//输入$reg_pat, $tok_pat，规则同token_match相同
//如果加花括号，则返回花括号及之前、原单行代码、花括号及之后三部分代码
//如果不加花括号，则返回分号及之前、原单行代码、分号之后三部分代码
//目前变成了一个壳
function merge_add_braces($reg_pat, $tok_pat, $subject, &$reg_offset=NULL){
	$add_open_tag = 0;
	if(strpos($subject, '<?php')===false){
		$subject = '<?php '.$subject;
		$add_open_tag = 1;
	}
	$reg_ret = token_match($reg_pat, $tok_pat, $subject);
	//无结果直接返回
	if(!$reg_ret) {
		if($add_open_tag) {
			$subject = substr($subject, 6);
		}
		return array($subject, '', '');
	}
	
	$reg_offset = $reg_ret[0][0][1];
	
	$ret = merge_add_braces_core($reg_offset, $subject);
	if($add_open_tag) {
		$ret[0] = str_replace('<?php ', '', $ret[0]);
		$reg_offset -= 6;
	}
	return $ret;
}

//判断$offset2是不是与$offset1在同一个控制流程内（也即执行$offset2是不是一定要执行$offset1）
//输入的$tokens必须是token_get_all_adv()得到的
function merge_check_same_cp_tree($offset1, $offset2, $tokens){
	if($offset1 > $offset2){
		$tmp = $offset2; $offset2 = $offset1; $offset1 = $tmp; unset($tmp);
	}
	$t1 = token_get_single_adv($offset1, $tokens);
	$t1_cp_offset = $t1['last_cp_token'];
	if(NULL===$t1_cp_offset) return true; //如果$t1不在控制流程内，那么一定满足
	//如果$t2能追溯到$t1_cp_offset那么两个在同一控制流程，否则不满足
	$t2 = token_get_single_adv($offset2, $tokens);
	$t2_cp_offset = $t2['last_cp_token'];
	do{
		if($t2_cp_offset == $t1_cp_offset) return true;
		if(NULL !== $t2_cp_offset) $t2_cp_offset = $tokens[$t2_cp_offset]['last_cp_token'];
	} while(NULL !== $t2_cp_offset);
	return false;
}

//判断$offset2是不是与$offset1在同一个圆括号层级
//输入的$tokens必须是token_get_all_adv()得到的
//function merge_check_same_paren($offset1, $offset2, $tokens){
//	$t1 = token_get_single_adv($offset1, $tokens);
//	$t2 = token_get_single_adv($offset2, $tokens);
//	return $t1['paren'] === $t2['paren'];
//}

//判断$offset2是不是与$offset1在同一个大括号层级
//输入的$tokens必须是token_get_all_adv()得到的
//function merge_check_same_brace($offset1, $offset2, $tokens){
//	$t1 = token_get_single_adv($offset1, $tokens);
//	$t2 = token_get_single_adv($offset2, $tokens);
//	return $t1['brace'] === $t2['brace'];
//}

//识别第一个特定元素+[可选空白字符]+正确的前后括号的内容，同时还可以返回所找到的那个$token
//返回一个数组，前3个元素分别是所找元素、括号内容起始、括号内容结束的偏移量
function merge_dist_paren_offset($find_str, $find_type, $tokens, &$find_token=NULL){
	$state = 0;
	$paren_nest = NULL;
	$brace_nest = NULL;
	$offsets = array();
	foreach($tokens as $tkey => $token){
		if(0 === $state && $token['str'] === $find_str && $token['type'] === $find_type){
			$state = 1;
			$offsets[0] = $tkey;
			$find_token = $token;
		}elseif(1 === $state){
			if('(' === $token['str'] && NULL === $token['type']){
				$state = 2;
				$paren_nest = $token['paren'];
				$brace_nest = $token['brace'];
				$offsets[1] = $tkey + strlen($token['str']);
			}elseif(T_WHITESPACE !== $token['type']){
				$state = 0;
			}
		}elseif(2 === $state && ')' === $token['str'] && NULL === $token['type'] && $paren_nest == $token['paren'] && $brace_nest == $token['brace']){
			$state = 0;
			$offsets[2] = $tkey;
			break;
		}
	}
	if(isset($offsets[0]) && !isset($offsets[2])){//如果没有闭合，则认为没有找到
		$offsets = array();
	}
	return $offsets;
}

//识别并拆分第一个特定字符串+正确的前后括号的内容，同时还可以返回所找到的那个$token的偏移量
//返回array('A', '所找元素', 'C(', '括号内容', ')E')形式的数组
function merge_split_paren_adv($find_str, $find_type, $subject, &$find_offset=NULL){
	$add_open_tag = 0;
	if(strpos($subject, '<?php')===false){
		$subject = '<?php '.$subject;
		$add_open_tag = 1;
	}
	$tokens = token_get_all_adv($subject);
	$offsets = merge_dist_paren_offset($find_str, $find_type, $tokens, $find_token);
	if(!empty($offsets)){
		$find_offset = $offsets[0];
		$find_str_end_offset = $offsets[0] + strlen($find_token['str']);
		$offset_array = array($offsets[0], $find_str_end_offset, $offsets[1], $offsets[2]);
		$ret = array();
		$tmp_offset = 0;
		foreach($offset_array as $oval){
			$ret[] = substr($subject, $tmp_offset, $oval-$tmp_offset);
			$tmp_offset = $oval;
		}
		$ret[] = substr($subject, $tmp_offset);
	}else{
		$ret = array($subject, '', '', '', '');
	}
	
	if($add_open_tag) {
		$ret[0] = substr($ret[0], 6);
		$find_offset -= 6;
	}
	return $ret;
}

//用$replacement（代码块字符串）替换$subject里的第一个$chprocess()
//并非简单替换，要在$chprocess()所在行的前一行先给$ret赋值，然后用$ret_varname替换$chprocess()
//为了正常运行，需要先给单行代码块加花括号
function merge_replace_chprocess($ret_varname, $replacement, $subject, $modname, $funcname=NULL, $funcvarsarr=Array()){
	//获得三段
	list($ret_behind, $ret_middle, $ret_ahead) = merge_add_braces('/(\$chprocess)/s', array(T_VARIABLE), $subject);
	if(empty($ret_middle)) return $ret_behind;
	
	//提取子函数传参
	list($ret_a, $ret_b, $ret_c, $ret_d, $ret_e) = merge_split_paren_adv('$chprocess', T_VARIABLE, $ret_middle);
	$chp_args_arr = explode(',', $ret_d);
	foreach($chp_args_arr as &$fval){
		$fval = trim($fval);
	}
	//需要在$chprocess()之前暂存变量，执行完后把变量写回来
	//思路：识别出$ret_behind里面的本地变量，之后将$replacement里有同名变量的那些变量暂存，其他变量不管
	$this_im_variables = merge_get_imported_variables($ret_behind);
	$this_gl_variables = merge_get_global_variables($ret_behind);
	$exceptions = array_merge($this_im_variables, $this_gl_variables, $chp_args_arr);
	list($local_variables, $local_variables_ref) = merge_get_local_variables($ret_behind, $exceptions, true);
	list($replacement_variables, $replacement_variables_ref) = merge_get_local_variables($replacement, array(), true);
	foreach($replacement_variables_ref as &$rfval){//$replacement里只判定变量名，无视是否是引用
		if(!in_array($rfval[0], $replacement_variables)) $replacement_variables[] = $rfval[0];
	}
	$vdc_behind = $vdc_ahead = '';
	$dumped_list = array();
	//2023.09.10修改：在开启了$___MOD_CODE_COMBINE之后，需要防止子函数对传入变量修改而污染父函数变量		
	//因此，非引用的传参也必须暂存，但不能unset，另行处理吧
	
	//先判定引用类的传参
	$chp_args_arr_exceptions = Array();
	$c = count($funcvarsarr);
	for($i=0; $i < $c; $i++){
		if(!empty($funcvarsarr[$i]['ref'])) {
			$chp_args_arr_exceptions[] = $funcvarsarr[$i]['name'];
		}
	}
	//然后处理
	foreach($chp_args_arr as $lval){
		if(empty($lval) || in_array($lval, $dumped_list) || in_array($lval, $chp_args_arr_exceptions)) continue;
		$dump_name = '$__VAR_DUMP_MOD_'.$modname.'_VARS_'.substr($lval, 1);
		$vdc_behind .= 'if(isset('.$lval.')) {'.$dump_name.' = '.$lval.'; } else {'.$dump_name.' = NULL;} ';
		$vdc_ahead .= ''.$lval.' = '.$dump_name.'; ';
		$dumped_list[] = $lval;
	}
	//引用变量优先级更高
	foreach($local_variables_ref as $lval){
		//$ori_name = $lval[1];
		$lval = $lval[0];
		if(!in_array($lval, $replacement_variables) || in_array($lval, $dumped_list)) continue;
		$dump_name = '$__VAR_DUMP_MOD_'.$modname.'_VARS_'.substr($lval, 1);
		$vdc_behind .= 'if(isset('.$lval.')) {'.$dump_name.' = &'.$lval.'; unset('.$lval.'); } else {'.$dump_name.' = NULL;}';
		$vdc_ahead .= ''.$lval.' = &'.$dump_name.'; ';
		$dumped_list[] = $lval;
	}
	//通常变量
	foreach($local_variables as $lval){
		if(!in_array($lval, $replacement_variables) || in_array($lval, $dumped_list)) continue;
		$dump_name = '$__VAR_DUMP_MOD_'.$modname.'_VARS_'.substr($lval, 1);
		$vdc_behind .= 'if(isset('.$lval.')) {'.$dump_name.' = '.$lval.'; unset('.$lval.'); } else {'.$dump_name.' = NULL;} ';
		$vdc_ahead .= ''.$lval.' = '.$dump_name.'; ';
		$dumped_list[] = $lval;
	}
	if(!empty($vdc_behind)) $vdc_behind = "\r\n".$vdc_behind;
	if(!empty($vdc_ahead)) $vdc_ahead = "\r\n".$vdc_ahead;
	$replacement = $vdc_behind . $replacement . $vdc_ahead;
	
	//把$chprocess那一行前面可能存在的函数都提取出来
	$tmp_prc_a = $ret_a;
	$tmp_ret_a = '';
	$tmp_callcont = '';
	if(preg_match('/([A-Za-z0-9_\\\\]+?)\s*\(/s', $tmp_prc_a, $tmp_p_funcname)) $tmp_p_funcname = $tmp_p_funcname[1];
	else $tmp_p_funcname=NULL;
	$ii = 0;
	while(!empty($tmp_p_funcname)){
		$tmp_p_funcname0 = $tmp_p_funcname;
		if(strpos($tmp_p_funcname,'\\')!==false){//临时替换反斜杠
			$tmp_p_funcname = str_replace('\\','_',$tmp_p_funcname);
			$tmp_prc_a = str_replace($tmp_p_funcname0,$tmp_p_funcname,$tmp_prc_a);
		}
		list($ret2_a, $ret2_b, $ret2_c, $ret2_d, $ret2_e) = merge_split_paren_adv($tmp_p_funcname, T_STRING, $tmp_prc_a);
		if(!empty($ret2_d) && 'eval' != $tmp_p_funcname){//闭合才算，且排除掉eval
			$tmp_p_funcname_v = '$tmp_'.$tmp_p_funcname;
			if($tmp_p_funcname0 != $tmp_p_funcname){//反斜杠换回来
				$ret2_b = str_replace($tmp_p_funcname,$tmp_p_funcname0,$ret2_b);
			}
			$tmp_callcont .= $tmp_p_funcname_v . ' = ' . $ret2_b . $ret2_c . $ret2_d . ");\r\n";
			$tmp_ret_a .= $ret2_a . $tmp_p_funcname_v;
			$tmp_prc_a = substr($ret2_e, 1);
		}else{
			$tmp_funcname_offset = strpos($tmp_prc_a, $tmp_p_funcname);
			if($tmp_p_funcname0 != $tmp_p_funcname){//反斜杠换回来
				$tmp_prc_a = str_replace($tmp_p_funcname,$tmp_p_funcname0,$tmp_prc_a);
			}
			$tmp_ret_a .= substr($tmp_prc_a, 0, $tmp_funcname_offset + strlen($tmp_p_funcname));
			$tmp_prc_a = substr($tmp_prc_a, $tmp_funcname_offset + strlen($tmp_p_funcname));
		}
		if(preg_match('/([A-Za-z0-9_\\\\]+?)\s*\(/s', $tmp_prc_a, $tmp_p_funcname)) $tmp_p_funcname = $tmp_p_funcname[1];
		else $tmp_p_funcname=NULL;
		$ii++;
		if($ii > 10) {
			
			die($tmp_prc_a.'<br><br><br>'.$tmp_ret_a.'<br><br><br>'.$tmp_p_funcname);
		}
	}
	$replacement = $tmp_callcont . $replacement;
	$ret_a = $tmp_ret_a . $tmp_prc_a;
	
	//真正插入步骤
	//不能直接preg_replace，需要判定括号层数！
	//不存在$chprocess直接返回
	$ret_middle = $replacement . $ret_a . $ret_varname . substr($ret_e,1);
	return $ret_behind . $ret_middle . $ret_ahead;
}

//获得$subject里用import_module()导入的变量名
function merge_get_imported_variables($subject){
	$read_im_subject = $subject;
	$tmp_im_list = array();
	//获得所有import_module的模块名
	do{
		list($ret_a, $ret_b, $ret_c, $ret_d, $ret_e) = merge_split_paren_adv('import_module', T_STRING, $read_im_subject);
		if(!empty($ret_d)){
			$tmp_im_single = explode(',', $ret_d);
			foreach($tmp_im_single as $ival){
				$ival_str = trim($ival);
				if(!in_array($ival_str, $tmp_im_list))
					$tmp_im_list[] = $ival_str;
			}
			$read_im_subject = $ret_e;
		}else{
			$read_im_subject = '';
		}
	}while (!empty($read_im_subject));
	//获得对应模块名的全局变量
	$tmp_global_var_list = array();
	foreach($tmp_im_list as $ival){
		$ival = trim(str_replace("'", '', $ival));
		$tmp_global_var_str = constant("MODULE_".strtoupper($ival)."_GLOBALS_VARNAMES");
		$tmp_global_var_list = array_merge($tmp_global_var_list, explode(',', $tmp_global_var_str));
	}
	//全局变量名前面加$
	foreach($tmp_global_var_list as &$gval){
		if(strpos($gval, '$')!==0) $gval = '$'.$gval;
	}
	array_unique($tmp_global_var_list);
	return $tmp_global_var_list;
}

//获得$subject里用global定义的全局变量
function merge_get_global_variables($subject){
	$tmp_global_var_list = array();
	$match = token_match('/(global)\s*(.*?);/si', array(T_GLOBAL, '*'), '<?php '.$subject);
	foreach($match as $mval){
		$marr = explode(',', $mval[2][0]);
		foreach($marr as $maval){
			$tmp_global_var_list[] = trim($maval);
		}		
	}
	array_unique($tmp_global_var_list);
	return $tmp_global_var_list;
}

//得到$subject里的所有本地变量名的数组
//$only_changed=true时只匹配进行过值操作的变量（暂时还不能识别一些会改变值的函数）。否则所有变量都会进行匹配，注意程序是无法区分input模块带进来的全局变量的。
//返回两个数组，第二个数组是$only_changed=true情况下才能识别的引用变量
function merge_get_local_variables($subject, $exception_list=array(), $only_changed=false){
	//获得$subject里定义过的变量，与例外变量差分，得到需要暂存的变量名数组
	$tmp_local_var_list = array();
	$tmp_local_var_list_ref = array();
	if($only_changed){
		$tmp_local_var_match = token_match('|(\$[A-Za-z0-9_]+)\s*?[\+\-\*/\.]*=([^=>].*?;)|s', array(T_VARIABLE, '*'), '<?php '.$subject);
	}else{
		$tmp_local_var_match = token_match('|(\$[A-Za-z0-9_]+)|s', array(T_VARIABLE), '<?php '.$subject);
	}	
	if(!empty($tmp_local_var_match)){
		foreach($tmp_local_var_match as $tval){
			$tval1 = $tval[1][0];
			$tval2 = isset($tval[2]) ? $tval[2][0] : '';
			//刨掉例外变量
			if(!in_array($tval1, array('$___RET_VALUE', '$chprocess')) && !in_array($tval1, $exception_list)){
				if(0 === strpos(trim($tval2), '&')){//引用
					$refname = trim(substr(trim($tval2), 1, -1));
					$tmp_local_var_list_ref[] = array($tval1, $refname);
				}else{
					$tmp_local_var_list[] = $tval1;
				}
			}
		}
		$tmp_local_var_list = array_unique($tmp_local_var_list);
	}
	return array($tmp_local_var_list, $tmp_local_var_list_ref);
}

//把所有的return换成${$ret_varname}=xxx;break;的形式
function merge_replace_return($ret_varname, $subject){
	$flag = 1;
	$add_open_tag = 0;
	if(strpos($subject, '<?php')===false){
		$subject = '<?php '.$subject;
		$add_open_tag = 1;
	}
	$tokens = token_get_all_adv($subject);
	$list = array();
	//记录所有的return位置和层数
	$reg_ret = token_match('/(return)/s', array(T_RETURN), $subject);
	foreach($reg_ret as $rval){
		$break_num = 1;
		$rval_offset = $rval[0][1];
		$tmp_cp_offset = token_get_single_adv($rval_offset, $tokens)['last_cp_token'];
		do{
			$tmp_cp_token = token_get_single_adv($tmp_cp_offset, $tokens);
			if(in_array($tmp_cp_token['type'], array(T_FOR, T_FOREACH, T_WHILE, T_DO))){
				$break_num++;
			}
			$tmp_cp_offset = $tmp_cp_token['last_cp_token'];
		}while(NULL !== $tmp_cp_offset);
		$list[] = array($rval_offset, $break_num);
	}
	//对每个记录的位置，自动添加大括号并替换return
	$global_offset = 0;
	foreach($list as $lval){
		list($ret_behind, $ret_middle, $ret_ahead) = merge_add_braces_core($lval[0]+$global_offset, $subject);
		$break_str = $break_str = $lval[1] > 1 ? 'break ' . $lval[1] : 'break';
		$ret_middle = preg_replace('/return\s*?;/s', $ret_varname." = NULL;\r\n\t\t\t{$break_str}; ", $ret_middle, 1);
		$ret_middle = preg_replace('/return\s*?(.*?);/s', $ret_varname." = $1;\r\n\t\t\t{$break_str}; ", $ret_middle, 1);
		$tmp_subject = $ret_behind . $ret_middle . $ret_ahead;		
		$global_offset += strlen($tmp_subject) - strlen($subject);
		$subject = $tmp_subject;
	}
	if($add_open_tag) $subject = substr($subject, 6);
	return $subject;
}

//用特定的函数内容替换模板文件里的同名函数内容，并写为目标文件
//理论上应该改成同样用token_get_all_adv()比较漂亮，但是今天真的累了
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
	global $modn, $___TEMP_flipped_modn, $quickmode, $quickmode_funclist;
	
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
		//快速模式下，不涉及的函数跳过
		if($quickmode && !in_array($key, $quickmode_funclist)) continue;
		
		$contents = $___TEMP_func_contents[$modid][$key]['contents'];
		//干掉eval字符串，因为不需要了
		$contents = str_replace('if (eval(__MAGIC__)) return $___RET_VALUE;', '', $contents);
		
		
		$contents = '<?php '.$contents;
		//函数内容里直接引用本模块的函数名需要加上namespace
		foreach($tmp_mod_funcname_list as $cmfn){
			$contents = token_replace('/([^\\\\A-Za-z0-9_])('.$cmfn.')\s*?\(/si', array('*',T_STRING), '$1\\\\'.$modn[$modid].'\\\\$2 (', $contents);
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
				$contents = merge_replace_chprocess($___TEMP_last_ret_varname[$key], $tmp_stored_contents, $contents, $modn[$modid], $key, $___TEMP_func_contents[$modid][$key]['vars']);
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
			$tmp_im_subject = '<?php '.$contents;
			$tmp_im_ret = '';
			$im_root_token_offset = NULL;
			$im_global_token_offset = 0;
			$im_tokens = token_get_all_adv($tmp_im_subject);
			$im_diff_arr = array();
			do{
				list($ret_a, $ret_b, $ret_c, $ret_d, $ret_e) = merge_split_paren_adv('import_module', T_STRING, $tmp_im_subject, $im_token_offset);
				if(!empty($ret_d)){
					$im_token_offset += $im_global_token_offset;
					//判定是否为最外层import_module
					if(NULL===$im_root_token_offset || !merge_check_same_cp_tree($im_root_token_offset, $im_token_offset, $im_tokens)){
						$im_root_token_offset = $im_token_offset;
						//如果是，则重新记录$im_diff_arr
						$im_diff_arr = array();
						foreach(explode(',', $ret_d) as $val){
							$im_diff_arr[] = trim($val);
						}
						//不进行任何替换并继续（$ret_t留不留后圆括号对结果没有区别）
						$tmp_im_ret .= $ret_a . $ret_b . $ret_c . $ret_d;
						$tmp_im_subject = $ret_e;
					}else{//不是外层import_module，替换内容
						$tmp_im_thiscont = explode(',',$ret_d);
						$im = sizeof($tmp_im_thiscont);
						for($i=0;$i<$im;$i++){
							$tmp_im_thiscont[$i] = trim($tmp_im_thiscont[$i]);
						}
						$tmp_input_flag = in_array("'input'", $tmp_im_thiscont) ? 1 : 0; //input模块必须保留
						$tmp_im_thiscont = array_diff($tmp_im_thiscont, $im_diff_arr);
						if($tmp_input_flag) $tmp_im_thiscont[] = "'input'";
						if(!empty($tmp_im_thiscont)){
							$tmp_im_ret .= $ret_a . $ret_b . $ret_c . implode(',', $tmp_im_thiscont);
							$tmp_im_subject = $ret_e;	
						}else{
							$tmp_im_ret .= $ret_a;
							$tmp_im_subject = $ret_e;
						}	
					}
					$im_global_token_offset += strlen($ret_a . $ret_b . $ret_c . $ret_d);
				}else{
					$tmp_im_ret .= $tmp_im_subject;
					$tmp_im_subject = '';
				}
			}while (!empty($tmp_im_subject));
			$contents = substr(str_replace('eval());', '', $tmp_im_ret), 6);	
			
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
			//参数处理，主要是调用子函数的变量名和子函数传参的定义名不同的情况
			list($null, $null, $null, $parent_chpvars, $null) = merge_split_paren_adv('$chprocess', T_VARIABLE, $parent_func_contents['contents']);
			$parent_varname_change = '';
			$vars_varname_list = array();
			$parent_vars_inherit_list = array();//记录继承的父函数变量名，后面变量改名需要豁免 //好像最后没用上
			if(!empty($parent_chpvars)){
				$vars_arr = $___TEMP_func_contents[$modid][$key]['vars'];
				$parent_chpvars = explode(',',$parent_chpvars);
				foreach($parent_chpvars as &$pvval){
					$pvval = trim($pvval);
				}
				$count_vars_arr = count($vars_arr);
				//用子函数的参数作循环判断
				for($i=0; $i < $count_vars_arr; $i++){
					$vc = $vars_arr[$i];
					$pvc = isset($parent_chpvars[$i]) ? $parent_chpvars[$i] : NULL;
					$vars_varname_list[] = $vc['name'];
					//情况1，父函数调用时子函数的变量名与子函数定义的不同
					if(NULL !== $pvc && $pvc != $vc['name']){
						$parent_vars_inherit_list[] = $pvc;
						if(isset($vc['ref']) && $vc['ref']){
							$parent_varname_change .= $vc['name'] . ' = &' . $pvc . '; ';
						}else{
							$parent_varname_change .= $vc['name'] . ' = ' . $pvc . '; ';
						}
					//情况2，父函数调用时没有给出这一变量
					}elseif(NULL === $pvc){
						if(isset($vc['default'])){
							$parent_varname_change .= $vc['name'] . ' = ' . $vc['default'] . '; ';
						}else{
							//故意产生错误提示
							$parent_varname_change .= $vc['name'] . ' = __MISSING_VARIABLE_' . substr($vc['name'],1) . '; ';
						}
					}
				}
			}
			if($parent_varname_change) {
				$parent_varname_change .= "\r\n";
			}
			$contents = $parent_varname_change . $contents;			
			
			//变量改名以避免撞车
			//还需要考虑载入htm的情况，目测还得改回$chprocess前后换变量的办法，直接改名过于蛋疼
			//只需要考虑改后头出现过的重复变量名
//			$local_variables = merge_get_local_variables($contents, array_merge($vars_varname_list, $parent_vars_inherit_list), true);
//			$tmp_contents = '<?php '.$contents;
//			usort($local_variables, function($a, $b) {return(strlen($a) > strlen($b));});  			
//			foreach($local_variables as $lval){
//				if(strpos($lval, '$__LOCAL_ALIAS_')===false){
//					$tmp_contents = token_replace('/\\$('.substr($lval,1).')([^A-Za-z0-9_])/s', array(T_VARIABLE, '*'), '$__LOCAL_ALIAS_'.$modn[$modid].'_$1$2', $tmp_contents);
//				}
//			}
//			//$contents = substr(str_replace('$__LOCAL_ALIAS_$', '$__LOCAL_ALIAS_', $tmp_contents), 6);
//			$contents = substr($tmp_contents, 6);
//			unset($tmp_contents);
			
			//return处理			
			//开头初始化$$ret_varname以避免未初始化的notice
			$ret_varname = '$___TMP_MOD_'.$modn[$modid].'_FUNC_'.$key.'_RET';
			$contents = $ret_varname." = NULL;\r\n".$contents;
			//之后把return换为$$ret_varname并且加break;
			$contents = merge_replace_return($ret_varname, $contents);
			//整体装进一个do...while(0)结构，方便用break模拟return
			$contents = "\r\n\t\t//======== Start of contents from mod {$modn[$modid]} ========\r\n\t\tdo{\r\n\t\t\t".$contents."\t}while(0);\r\n\t\t//======== End of contents from mod {$modn[$modid]} ========\r\n";
			//彻底去除eval字符串
			//$contents = str_replace('<<<<<<EVAL>>>>>>', '', $contents);
			
			//$___TEMP_stored_func_contents没内容时直接暂存本函数内容
			//也即本函数有2个以上的$chprocess
			if(empty($___TEMP_stored_func_contents[$key])) {
				$___TEMP_stored_func_contents[$key] = $contents;
			//$contents里只有1个$chprocess，先用$sfc替换本函数$chprocess，再将本函数全部暂存
			} else {
				$___TEMP_stored_func_contents[$key] = merge_replace_chprocess($___TEMP_last_ret_varname[$key], $___TEMP_stored_func_contents[$key], $contents, $modn[$modid], $key, $___TEMP_func_contents[$modid][$key]['vars']);
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
	global $___TEMP_modfuncs;
	global $___TEMP_func_contents;
	global $___MOD_CODE_COMBINE;
	foreach ($___TEMP_defined_funclist[$modid] as $key)
	{
		//$key();
		//php 7.1以上版本，函数参数不足时会Error，因此不能直接空参数执行
		eval(reflection_run_code($key));
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