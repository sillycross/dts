<?php

namespace skill92
{
	//复唱概率
	$encorerate = array(10,15,20,25);
	//升级所需技能点数值
	$upgradecost = array(3,3,3,-1);
	
	function init()
	{
		define('MOD_SKILL92_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[92] = '安可';
	}
	
	function acquire92(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(92,'lvl','0',$pa);
	}
	
	function lost92(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked92(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=11;
	}
	
	function upgrade92()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill92','player','logger'));
		if (!(\skillbase\skill_query(92, $sdata) && check_unlocked92($sdata)))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		$clv = (int)\skillbase\skill_getvalue(92, 'lvl', $sdata);
		$ucost = $upgradecost[$clv];
		if ($ucost == -1)
		{
			$log .= '你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint < $ucost)
		{
			$log .= '技能点不足。<br>';
			return;
		}
		$skillpoint -= $ucost;
		\skillbase\skill_setvalue(92, 'lvl', $clv+1);
		$log .= '升级成功。<br>';
	}
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$ss_temp = $ss;
		$chprocess($sn);
		if (\skillbase\skill_query(92, $sdata) && check_unlocked92($sdata) && ($ss < $ss_temp))
		{
			eval(import_module('skill92'));
			$clv = (int)\skillbase\skill_getvalue(92, 'lvl', $sdata);
			$erate = $encorerate[$clv];
			$learnedsongs = \song\get_available_songlist($sdata);
			if (!empty($learnedsongs))
			{
				eval(import_module('logger', 'song'));
				while (rand(0,99) < $erate)
				{
					\skillbase\skill_setvalue(92,'encore_flag','1',$sdata);
					$log .= "<br><span class=\"L5 b\">但是演出还没有结束！</span><br>";
					$songid = (int)array_randompick($learnedsongs);
					$sn_new = $songlist[$songid]['songname'];
					$chprocess($sn_new);
				}
				//1003中改回原先要唱的歌
				if (isset($sn_new) && ($sn_new != $sn))
				{
					\skillbase\skill_setvalue(1003,'songpos',0,$sdata);
					\skillbase\skill_setvalue(1003,'songkind',$sn,$sdata);
				}
			}
		}
	}
	
	function ss_cost_proc($cost)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(92, $sdata) && check_unlocked92($sdata) && !empty(\skillbase\skill_getvalue(92, 'encore_flag', $sdata)))
		{
			\skillbase\skill_delvalue(92,'encore_flag',$sdata);
			return 0;
		}
		return $chprocess($cost);
	}
	
}

?>