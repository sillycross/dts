<?php

namespace skill1010
{
	function init() 
	{
		define('MOD_SKILL1010_INFO','active;unique;');
		eval(import_module('clubbase'));
		$clubskillname[1010] = '降神';
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
	
	function skill1010_mani_load_pcs($p)
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
	
	function skill1010_exma($mpid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skillbase'));
		$mpid = (int)$mpid;
		$mpdata = \player\fetch_playerdata_by_pid($mpid);
		if(\skillbase\skill_query(1010)) $flag1010 = 1;
		if(\skillbase\skill_query(1011)) $flag1011 = 1;
		if(empty($sdata['parameter_list'])) $sdata['parameter_list'] = array();
		if(empty($mpdata['parameter_list'])) $mpdata['parameter_list'] = array();
		$switch_list = array
		(
			'club',	'hp', 'mhp', 'sp', 'msp', 'ss', 'mss', 'att',	'def', 'lvl', 'exp',
			'money',	'rage',	'wp', 'wk', 'wg', 'wc', 'wd', 'wf', 'acquired_list', 'parameter_list',
			'wep',	'wepk',	'wepe',	'weps',	'wepsk',
			'arb',	'arbk',	'arbe',	'arbs',	'arbsk',
			'arh',	'arhk',	'arhe',	'arhs',	'arhsk',
			'arf',	'arfk',	'arfe',	'arfs',	'arfsk',
			'ara',	'arak',	'arae',	'aras',	'arask',
			'art',	'artk',	'arte',	'arts',	'artsk',
			'itm0',	'itmk0',	'itme0',	'itms0',	'itmsk0',
			'itm1',	'itmk1',	'itme1',	'itms1',	'itmsk1',
			'itm2',	'itmk2',	'itme2',	'itms2',	'itmsk2',
			'itm3',	'itmk3',	'itme3',	'itms3',	'itmsk3',
			'itm4',	'itmk4',	'itme4',	'itms4',	'itmsk4',
			'itm5',	'itmk5',	'itme5',	'itms5',	'itmsk5',
			'itm6',	'itmk6',	'itme6',	'itms6',	'itmsk6'
		);
		foreach ($switch_list as $sval){
			swap($sdata[$sval], $mpdata[$sval]);
		}
		$acquired_list = $sdata['acquired_list'];
		$parameter_list = $sdata['parameter_list'];
		if($flag1010) {\skillbase\skill_acquire(1010);\skillbase\skill_lost(1010,$mpdata);}
		if($flag1011) {\skillbase\skill_acquire(1011);\skillbase\skill_lost(1011,$mpdata);}
		\player\player_save($mpdata);
	}
	
	function skill1010_mani_page()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		global $pcs;
		$pcs = skill1010_mani_load_pcs($pls);
		include template(MOD_SKILL1010_MANI_PAGE);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill1010_special') 
		{
			$subcmd = get_var_input('subcmd');
			$mpid = get_var_input('mpid');
			if (!\skillbase\skill_query(1010)) 
			{
				$log.='你没有这个技能。';
				$mode = 'command';$command = '';
				return;
			}
			if(!isset($subcmd)){
				$mode = 'command';$command = '';
				return;
			}elseif($subcmd == 'mani_page') {
				skill1010_mani_page();
				return;
			}elseif($subcmd == 'meet' || $subcmd == 'exma'){
				$mpid = (int)$mpid;
				$pcs = skill1010_mani_load_pcs($pls);
				if(!isset($pcs[$mpid])) {
					$log .= '该角色不在当前地图。';
					$mode = 'command';$command = '';
					return;
				}
				if($subcmd == 'meet'){
					addnews (0, 'admin_mani', $name, $pcs[$mpid]['name'] );
					\metman\meetman($mpid);
				}else{
					if(!$pcs[$mpid]['type']) {
						$log .= '只能与NPC交换装备。';
					}else{
						addnews (0, 'admin_exma', $name, $pcs[$mpid]['name'] );
						skill1010_exma($mpid);
					}
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
		
		if($news == 'admin_mani') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}发动了技能「降神」，直接传送到了「{$b}」面前！（管理员{$a}宣告自己正在进行战斗测试。）</span></li>";
		elseif($news == 'admin_exma')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}发动了技能「降神」，与「{$b}」交换了能力值和装备！（管理员{$a}宣告自己正在进行NPC测试。）</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
