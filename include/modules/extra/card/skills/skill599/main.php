<?php

namespace skill599
{
	function init() 
	{
		define('MOD_SKILL599_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[599] = '迷梦';
	}
	
	function acquire599(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost599(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked599(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		if (!empty(\skillbase\skill_getvalue(1003,'sk599_flag')))
		{
			eval(import_module('sys','player','logger','rest'));
			\skillbase\skill_delvalue(1003,'sk599_flag');
			$state = 1;
			$endtime = $now;
			$mode = 'rest';
			$command = 'rest';
			$log .= "你感到昏昏欲睡，然后克制不住睡意倒了下去。<br>你开始了{$restinfo[$state]}…<br>";
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if ($mode == 'special' && $command == 'skill599_special' && get_var_in_module('subcmd','input') == 'castsk599') 
		{
			cast_skill599();
			return;
		}
		$chprocess();
	}

	function cast_skill599()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','weapon','skill599'));
		if (!\skillbase\skill_query(599)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (\skillbase\skill_getvalue(599,'activated')) 
		{
			$log .= '你已经发动过此技能了！<br>';
			return;
		}
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$pls' AND hp>0 AND pid!='$pid'");
		if($db->num_rows($result))
		{
			$list = array();
			while($r = $db->fetch_array($result)){
				$list[] = $r['pid'];
			}
			foreach($list as $pdid)
			{
				$pdata = \player\fetch_playerdata_by_pid($pdid);			
				if (!$pdata['type'] || ($pdata['type'] && ($pdata['hp'] < $hp)))
				{
					//改成治疗姿态和重视防御
					$pdata['pose'] = 5;
					$pdata['tactic'] = 2;
					if (!$pdata['type']) \skillbase\skill_setvalue(1003,'sk599_flag',1,$pdata);
					\player\player_save($pdata);
				}
			}
		}
		$log .= '你发动了技能「迷梦」，让周围的所有人进入了梦乡！<br>';
		addnews($now, 'signal_599', $name);
		\skillbase\skill_setvalue(599,'activated',1);
		$mode = 'command';
		return;
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'signal_599')
		{
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}发动了技能「迷梦」，让周围的所有人进入了梦乡！</span></li>";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}

}

?>