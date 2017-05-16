<?php

namespace skill1010
{
	function init() 
	{
		define('MOD_SKILL1010_INFO','card;active;unique;');
		eval(import_module('clubbase'));
		$clubskillname[1010] = '操弄';
	}
	
	function acquire1010(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost1010(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1010(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill1010_manu_load_pcs($p)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$p = (int)$p;
		$r = array();
		$result=$db->query("SELECT * FROM {$tablepre}players WHERE pls='$p' AND pid != '$pid' ORDER BY type");
		while($pc = $db->fetch_array($result)){
			$r[$pc['pid']] = $pc;
		}
		return $r;
	}
	
	function skill1010_manu_page()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','input','logger'));
		global $pcs;
		$pcs = skill1010_manu_load_pcs($pls);
		include template(MOD_SKILL1010_MANU_PAGE);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input'));
	
		if ($mode == 'special' && $command == 'skill1010_special') 
		{
			if (!\skillbase\skill_query(1010)) 
			{
				$log.='你没有这个技能。';
				$mode = 'command';$command = '';
				return;
			}
			if(!isset($subcmd)){
				$mode = 'command';$command = '';
				return;
			}elseif($subcmd == 'manu_page') {
				skill1010_manu_page();
				return;
			}elseif(strpos($subcmd,'manu')===0){
				$mpid = (int)str_replace('manu','',$subcmd);
				$pcs = skill1010_manu_load_pcs($pls);
				if(!isset($pcs[$mpid])) {
					$log .= '该角色不在当前地图。';
					$mode = 'command';$command = '';
					return;
				}
				addnews (0, 'admin_manu', $name, $pcs[$mpid]['name'] );
				\enemy\meetman($mpid);
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
		
		if($news == 'admin_manu') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}发动了技能「操弄」，把「{$b}」直接传送到了面前！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
