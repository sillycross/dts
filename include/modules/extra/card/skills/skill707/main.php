<?php

namespace skill707
{
	function init() 
	{
		define('MOD_SKILL707_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[707] = '甩锅';
	}
	
	function acquire707(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost707(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked707(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//不会回避和重用，并且踩雷率大幅提升
	function get_trap_escape_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(707)) return 0;
		else return $chprocess();
	}
	
	function calculate_trap_reuse_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(707)) return 0;
		else return $chprocess();
	}
	
	function calculate_real_trap_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(707)) return $chprocess()+15;
		else return $chprocess();
	}
	
	function trap_survive()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		if (\skillbase\skill_query(707))
		{
			eval(import_module('player','logger'));
			$log .= "<span class=\"yellow b\">现在有两个好消息和两个坏消息。<br>坏消息是BUG在你手里爆炸了，好消息你人还能跑；<br>第二个好消息是你把BUG修复了，但第二个坏消息是BUG的数量好像并没有减少。<br>这是怎么回事呢？</span><br>";
			$itmk0 = str_replace('TO','TN',$itmk0);
			$itme0 = round($itme0 * 1.2);
			$itmsk0 = '';
			if (\searchmemory\searchmemory_available())
			{
				$dropid = \itemmain\itemdrop_query($itm0, $itmk0, $itme0, $itms0, $itmsk0, $pls);
				$amarr = array('iid' => $dropid, 'itm' => $itm0, 'pls' => $pls, 'unseen' => 0);
				\skill1006\add_beacon($amarr, $sdata);
				\player\player_save($sdata);
			}
		}		
	}
	
}

?>