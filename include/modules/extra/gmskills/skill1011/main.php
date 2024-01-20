<?php

namespace skill1011
{
	function init() 
	{
		define('MOD_SKILL1011_INFO','active;unique;');
		eval(import_module('clubbase'));
		$clubskillname[1011] = '具现';
	}
	
	function acquire1011(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost1011(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1011(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill1011_load_itemlist($t)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','itemmain','itemmix','itemmix_sync','itemmix_overlay'));
		$r = array();
		if(99==$t || \map\is_plsno_available($t)){
			$itemfc = \itemmain\get_itemfilecont();
			foreach($itemfc as $ival){
				$ival = explode(',',$ival);
				if(isset($ival[1]) && $ival[1] == $t) $r[] = array
				(
					'iarea' => $ival[0],
					'ipls' => $ival[1],
					'itm' => $ival[3],
					'itmk' => $ival[4],
					'itme' => $ival[5],
					'itms' => $ival[6],
					'itmsk' => $ival[7]
				);
			}
		}elseif('mix' == $t){
//			foreach($mixinfo as $minfo){
//				$mi0 = $mi; $ms = $minfo['stuff'];
//				sort($mi0);sort($ms);
//				if(count($mi0)==count($ms) && $mi0 == $ms) {
//					return $minfo;
//				}
//			}
		}
		return $r;
	}
	
	function skill1011_parse_itemwords($ilist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain'));
		$r = array();
		foreach($ilist as $iv){
			$iv['itmk'] = \itemmain\parse_itmk_words($iv['itmk'],1);
			$iv['itmsk'] = \itemmain\parse_itmsk_words($iv['itmsk']);
			$r[] = $iv;
		}
		return $r;
	}
	
	function skill1011_cons_page($page)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		//global $pcs;
		//$pcs = skill1011_load_pcs($pls);
		$cons_pls = get_var_input('cons_pls');
		$cons_page = & get_var_input('cons_page');
		if(2 == $page) {
			if(NULL==$cons_pls) {
				$log.='错误的地点指令。';
				$mode = 'command';$command = '';
				return;
			}
			global $itemlist;
			$itemlist = skill1011_load_itemlist($cons_pls);
			$itemlist = skill1011_parse_itemwords($itemlist);
		}
		$cons_page = $page;
		include template(MOD_SKILL1011_CONS_PAGE);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill1011_special') 
		{
			if (!\skillbase\skill_query(1011)) 
			{
				$log.='你没有这个技能。';
				$mode = 'command';$command = '';
				return;
			}
			$subcmd = get_var_input('subcmd');
			if(empty($subcmd)){
				$mode = 'command';$command = '';
				return;
			}elseif(strpos($subcmd,'cons_page')===0) {
				$page = (int)str_replace('cons_page','',$subcmd);
				if(!$page) $page = 1;
				skill1011_cons_page($page);
				return;
			}elseif(strpos($subcmd,'cons')===0){
				$cons_pls = get_var_input('cons_pls');
				$ciid = (int)str_replace('cons','',$subcmd);
				$itemlist = skill1011_load_itemlist($cons_pls);
				if(isset($itemlist[$ciid])){
					$item = $itemlist[$ciid];
					$itm0=$item['itm'];
					$itmk0=$item['itmk'];
					$itme0=$item['itme'];
					$itms0=$item['itms'];
					$itmsk0=$item['itmsk'];
					if(defined('MOD_ATTRBASE')) {
						$itmsk0=\attrbase\config_process_encode_comp_itmsk($itmsk0);
					}
					addnews (0, 'admin_cons', $name, $itm0 );
					\itemmain\itemget();
				}else{
					$log .= '道具参数错误。';
					$mode = 'command';$command = '';
					return;
				}
			}else{
				$log .= '命令参数错误。';
				$mode = 'command';$command = '';
				return;
			}
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'admin_cons') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}发动了技能「具现」，无中生有地创造了「{$b}」！（管理员{$a}宣告自己正在进行道具测试。）</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
