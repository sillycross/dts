<?php

namespace skill91
{
	function init()
	{
		define('MOD_SKILL91_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[91] = '夺目';
	}
	
	function acquire91(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(91,'pls','0',$pa);
		\skillbase\skill_setvalue(91,'uss','0',$pa);
	}
	
	function lost91(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(91,'pls',$pa);
		\skillbase\skill_delvalue(91,'uss',$pa);
	}
	
	function check_unlocked91(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$ss_temp = $ss;
		$chprocess($sn);
		if (\skillbase\skill_query(91, $sdata) && check_unlocked91($sdata) && ($ss < $ss_temp))
		{
			$ssuse = $ss_temp - $ss;
			$skill91_pls = (int)\skillbase\skill_getvalue(91,'pls',$sdata);
			if ($pls == $skill91_pls)
			{
				$skill91_uss = (int)\skillbase\skill_getvalue(91,'uss',$sdata);
				$skill91_uss += $ssuse;
			}
			else
			{
				\skillbase\skill_setvalue(91,'pls',$pls,$sdata);
				$skill91_uss = $ssuse;
			}
			if ($skill91_uss >= 240)
			{
				\skillbase\skill_setvalue(91,'uss','0',$sdata);
				skill91_process();
			}
			else
			{
				\skillbase\skill_setvalue(91,'uss',$skill91_uss,$sdata);
			}
		}
	}
	
	function skill91_process()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$pls' AND hp>0 AND pid != '$pid'");
		if ($db->num_rows($result))
		{
			$log .= "<span class=\"yellow b\">你的演出俘获了在场所有人的目光！</span><br>";
			$list = array();
			while($r = $db->fetch_array($result)){
				$list[] = $r['pid'];
			}
			foreach($list as $pdid)
			{
				$pdata = \player\fetch_playerdata_by_pid($pdid);
				\skillbase\skill_acquire(95, $pdata);
				\skillbase\skill_setvalue(95, 'spid', $pid, $pdata);
				if ($pdata['type'] == 0)
				{
					$skill91_log = "<span class=\"yellow b\">你被{$name}的演唱深深打动了。你获得了技能「倾心」！</span><br>";
					\logger\logsave($pdata['pid'], $now, $skill91_log ,'s');
				}
				\player\player_save($pdata);
			}
			eval(import_module('map'));
			addnews($now, 'signal91', $name, $plsinfo[$pls]);
		}
		else
		{
			$log .= "<span class=\"yellow b\">你接连唱了好几首歌，但是好像没人来听的样子……</span><br>";
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'signal91')
		{
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}在{$b}举行了盛大的演唱会。</span></li>";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>