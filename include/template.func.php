<?php


if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function parse_template($tplfile, $objfile, $templateid, $tpldir, $nospace=1) {
	global $language;

	$nest = 10;
	//$tplfile = GAME_ROOT."./$tpldir/$file.htm";
	//$objfile = GAME_ROOT."./gamedata/templates/{$templateid}_$file.tpl.php";
	if(!$tplfile) {
		var_dump(debug_backtrace()[1]);
		gexit("Unvalid or empty tplfile name!");
	} elseif (!$fp = fopen($tplfile, 'r')) {
		var_dump(debug_backtrace()[1]);
		gexit("Current template file '$tplfile' not found or have no access!");
	} elseif(!include_once language('templates', $templateid, $tpldir)) {
		gexit("<br>Current template pack do not have a necessary language file 'templates.lang.php' or have syntax error!");
	}

	$template = fread($fp, filesize($tplfile));
	fclose($fp);
	
	//变量正则
	$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	//常量正则	
	$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";
	
	//去除缩进
	$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
	//所有<!--{变为{  所有}-->变为}
	$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
	//lang xxx识别
	$template = preg_replace_callback("/\{lang\s+(.+?)\}/is", function($r) {
		return languagevar($r[1]);
	}, $template);
	$template = str_replace("{LF}", "<?=\"\\n\"?>", $template);	//WTF?

	//{$abc}变为< ?=和? >包含的短标签，相当于echo。可以识别数组
	//todo: 能够识别嵌套型的变量${'AAA'.$a}
	$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
	//加单引号，{$abc[ABC]}变为$abc['ABC']且加上短标签
	$template = preg_replace_callback("/".$var_regexp."/s", function($r) {
		return addquote("<?=".$r[1]."?>");
	}, $template);
	//两重短标签变为一重
	$template = preg_replace_callback("/\<\?\=\<\?\=".$var_regexp."\?\>\?\>/s", function($r) {
		return addquote("<?=".$r[1]."?>");
	}, $template);
	//还原可变变量，上一步之后应该是${'AAA'.< ?=$a? >}的样子。不过只能还原仅有1个变量，且其他部分为纯字符串的可变变量名
	//此处生成的{ }变为[@[ ]@]以避免干扰if loop等的判定，最后再换回来
	$template = preg_replace("/\\\$\{([\'\"][a-zA-Z0-9_]*[\'\"]\.)*\<\?\=(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\?\>(\.[\'\"][a-zA-Z0-9_]*[\'\"])*\}/s", "<?=\$[@[\\1\\2\\3]@]?>", $template);
	$template = preg_replace("/\{\<\?\=\\\$\[\@\[(.*)\]\@\]\?\>\}/s", "<?=\$[@[\\1]@]?>", $template);

	$template = "<? if(!defined('IN_GAME')) exit('Access Denied'); ?>\n$template";
	//{template XXX}变为对应的php语句，XXX是字符串的场合加上单引号，是变量的场合自动去除短标签
	$template = preg_replace("/[\n\r\t]*\{template\s+([a-z0-9_]+)\}[\n\r\t]*/is", "\n<? include template('\\1'); ?>\n", $template);
	$template = preg_replace_callback("/[\n\r\t]*\{template\s+(.+?)\}[\n\r\t]*/is", function($r) {
		return stripvtags("\n<? include template(".$r[1]."); ?>\n","");
	}, $template);
	
	//{eval XXX}变为对应的php语句，并自动去除短标签
	$template = preg_replace_callback("/[\n\r\t]*\{eval\s+(.+?)\}[\n\r\t]*/is", function($r) {
		return stripvtags("\n<? ".$r[1]." ?>\n","");
	}, $template);
	//{echo XXX}变为对应的php语句，并自动去除短标签
	$template = preg_replace_callback("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/is", function($r) {
		return stripvtags("\n<? echo ".$r[1]."; ?>\n","");
	}, $template);
	//{elseif XXX}变为对应的php语句，并自动去除短标签
	$template = preg_replace_callback("/[\n\r\t]*\{elseif\s+(.+?)\}[\n\r\t]*/is", function($r) {
		return stripvtags("\n<? } elseif(".$r[1].") { ?>\n","");
	}, $template);
	//{elseif}变为对应的php语句
	$template = preg_replace("/[\n\r\t]*\{else\}[\n\r\t]*/is", "\n<? } else { ?>\n", $template);

	//if 和 loop 最多只能嵌套5层，需要注意
	for($i = 0; $i < $nest; $i++) {
		//{loop XXX XXX}和{/loop}变为对应的php语句：foreach(XXX as XXX)
		$template = preg_replace_callback("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/is", function($r) {
			return stripvtags("\n<? if(is_array(".$r[1].")) { foreach(".$r[1]." as ".$r[2].") { ?>","\n".$r[3]."\n<? } } ?>\n");
		}, $template);
		//{loop XXX XXX XXX}和{/loop}变为对应的php语句：foreach(XXX as XXX => XXX)
		$template = preg_replace_callback("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/is", function($r) {
			return stripvtags("\n<? if(is_array(".$r[1].")) { foreach(".$r[1]." as ".$r[2]." => ".$r[3].") { ?>","\n".$r[4]."\n<? } } ?>\n");
		}, $template);
		//{if XXX}和{/if}变为对应的php语句：if(XXX){}
		$template = preg_replace_callback("/[\n\r\t]*\{if\s+(.+?)\}[\n\r]*(.+?)[\n\r]*\{\/if\}[\n\r\t]*/is", function($r) {
			return stripvtags("\n<? if(".$r[1].") { ?>","\n".$r[2]."\n<? } ?>\n");
		}, $template);
	}
	
	//[[[换回{，]]]换回}
	$template = str_replace("[@[", "{", $template);
	$template = str_replace("]@]", "}", $template);
	//常量替换
	$template = preg_replace("/\{$const_regexp\}/s", "<?=\\1?>", $template);
	//短标签之间空的部分替换
	$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);
	
	//短标签替换成长标签以保证兼容性
	$template = preg_replace("/\<\?/s", "<?php", $template);
	$template = preg_replace("/\<\?php\=/s", "<?php echo ", $template);

	if(!$fp = fopen($objfile, 'w')) {
		gexit("Directory './gamedata/templates/' not found or have no access!");
	}
	
	//开启无空字符模式，php开闭符号前后不会输出空格，但是tpl文件可读性会变差
	//好像会导致一些奇怪的故障，需要再观察
	if($nospace){
		$template = str_replace("\n<?", "<?", $template);
		$template = str_replace("?>\n", "?>", $template);
	}
	
	//网址修正
	$template = preg_replace_callback("/\"(http)?[\w\.\/:]+\?[^\"]+?&[^\"]+?\"/", function($r) {
		return transamp($r[0]);
	}, $template);
	flock($fp, 2);
	fwrite($fp, $template);
	fclose($fp);
}

function transamp($str) {
	$str = str_replace('&', '&amp;', $str);
	$str = str_replace('&amp;amp;', '&amp;', $str);
	$str = str_replace('\"', '"', $str);
	return $str;
}

function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

function languagevar($var) {
	if(isset($GLOBALS['language'][$var])) {
		return $GLOBALS['language'][$var];
	} else {
		return "!$var!";
	}
}

function stripvtags($expr, $statement) {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}

?>