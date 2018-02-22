<?php

namespace skill216
{
	
	function init() 
	{
		define('MOD_SKILL216_INFO','club;battle;limited;');
		eval(import_module('clubbase'));
		$clubskillname[216] = '双响';
	}
	
	function acquire216(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(216,'rmtime','2',$pa);
	}
	
	function lost216(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked216(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=19;
	}
	
	function get_remaintime216(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \skillbase\skill_getvalue(216,'rmtime',$pa);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=216) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(216,$pa) || !check_unlocked216($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$remtime = (int)get_remaintime216($pa);
			if (($remtime>=1)&&(\weapon\get_skillkind($pa,$pd,$active) == 'wd'))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime\">你对{$pd['name']}发动了技能「双响」！</span><br>";
				else  $log.="<span class=\"lime\">{$pa['name']}对你发动了技能「双响」！</span><br>";
				$remtime--; 
				\skillbase\skill_setvalue(216,'rmtime',$remtime,$pa);
				addnews ( 0, 'bskill216', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log.='怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill']=0;
			}
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function attack(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		eval(import_module('logger'));
		if (($pa['bskill']==216)&&(\weapon\get_skillkind($pa,$pd,$active) == 'wd')&&($pa['wepe']>0)&&(($pa['weps']>0)||($pa['weps']=='∞'))){
			unset($pa['bskill']);
			$log.="<span class=\"yellow\">你再一次引爆了爆炸物！</span><br>";
			$chprocess($pa,$pd,$active);
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill216') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}对{$b}发动了技能<span class=\"red\">「双响」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
