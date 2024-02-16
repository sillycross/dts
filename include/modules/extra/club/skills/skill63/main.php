<?php

namespace skill63
{
	function init() 
	{
		define('MOD_SKILL63_INFO','club;battle;limited;');
		eval(import_module('clubbase'));
		$clubskillname[63] = '噩梦';
	}
	
	function acquire63(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//剩余发动次数
		\skillbase\skill_setvalue(63,'t','1',$pa);
		//被标记的玩家pid
		\skillbase\skill_setvalue(63,'p','0',$pa);
		//被标记的玩家姓名
		\skillbase\skill_setvalue(63,'n','0',$pa);
	}
	
	function lost63(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(63,'t',$pa);
	}
	
	function check_unlocked63(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=21;
	}
	
	function get_remaintime63(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \skillbase\skill_getvalue(63,'t',$pa);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=63) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(63,$pa) || !check_unlocked63($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$clv = (int)get_remaintime63($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,63) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「噩梦」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「噩梦」！</span><br>";
				$clv--; \skillbase\skill_setvalue(63,'t',$clv,$pa);
				\skillbase\skill_setvalue(63,'p',$pd['pid'],$pa);
				\skillbase\skill_setvalue(63,'n',$pd['name'],$pa);
				addnews ( 0, 'bskill63', $pa['name'], $pd['name'] );
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
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(63,$pd) && check_unlocked63($pd)) 
		{
			eval(import_module('logger'));
			$clv = (int)\skillbase\skill_getvalue(63,'t',$pd);
			if ($clv==0)	//已经发动
			{
				$ep = (int)\skillbase\skill_getvalue(63,'p',$pd);
				if ($pa['pid']==$ep)	//攻击者被噩梦标记
				{
					if ($active)
						$log.='<span class="yellow b">由于你被噩梦缠绕，你对其造成的最终伤害减少了20%！</span><br>';
					else  $log.='<span class="yellow b">由于敌人被噩梦缠绕，其对你造成的最终伤害减少了20%！</span><br>';
					$r=Array(0.8);
				}
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill63') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"red b\">「噩梦」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
