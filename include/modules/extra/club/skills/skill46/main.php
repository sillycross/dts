<?php

namespace skill46
{
	function init() 
	{
		define('MOD_SKILL46_INFO','club;battle;limited;');
		eval(import_module('clubbase'));
		$clubskillname[46] = '暴打';
	}
	
	function acquire46(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(46,'rmtime','2',$pa);
	}
	
	function lost46(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked46(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function get_remaintime46(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \skillbase\skill_getvalue(46,'rmtime',$pa);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=46) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(46,$pa) || !check_unlocked46($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$remtime = (int)get_remaintime46($pa);
			if ($remtime>=1)
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime\">你对{$pd['name']}发动了技能「暴打」！</span><br>";
				else  $log.="<span class=\"lime\">{$pa['name']}对你发动了技能「暴打」！</span><br>";
				$remtime--; 
				\skillbase\skill_setvalue(46,'rmtime',$remtime,$pa);
				$pd['skill46_flag']=1;
				addnews ( 0, 'bskill46', $pa['name'], $pd['name'] );
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
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==46) 
		{
			eval(import_module('logger'));
			$r=Array(1.65);
			if ($active)
				$log.='<span class="yellow">你把敌人按在地上一顿暴打！</span><br>';
			else  $log.='<span class="yellow">敌人把你按在地上一顿暴打！</span><br>';
		}
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill46') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}对{$b}发动了技能<span class=\"red\">「暴打」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function skill_query($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillid=(int)$skillid;
		if ($pa!=NULL && isset($pa['skill46_flag']) && $pa['skill46_flag'])
		{
			//所有技能失效（称号特性不失效）
			if (!\skillbase\check_skill_info($skillid,'achievement') && !\skillbase\check_skill_info($skillid,'hidden') && !\skillbase\check_skill_info($skillid,'feature'))
				return 0;
		}
		return $chprocess($skillid,$pa);
	}
}

?>
