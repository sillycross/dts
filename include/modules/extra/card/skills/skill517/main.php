<?php

namespace skill517
{
	function init() 
	{
		define('MOD_SKILL517_INFO','card;battle;limited;');
		eval(import_module('clubbase'));
		$clubskillname[517] = '鱼弹';
	}
	
	function acquire517(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(517,'rmtime','2',$pa);
	}
	
	function lost517(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(517,'rmtime',$pa);
	}
	
	function check_unlocked517(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($pa['lvl'] > 9) return 1;
	}
	
	function get_remaintime517(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return (int)\skillbase\skill_getvalue(517,'rmtime',$pa);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty($pa['bskill']) && $pa['bskill']==517) {
			if (!\skillbase\skill_query(517,$pa) || !check_unlocked517($pa))
			{
				eval(import_module('logger'));
				$log .= '你尚未解锁这个技能！';
				$pa['bskill']=0;
			}
			else
			{
				$remtime = (int)get_remaintime517($pa);
				if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,517))
				{
					eval(import_module('logger','clubbase'));
					$log .= \battle\battlelog_parser($pa,$pd,$active,"<span class=\"lime b\"><:pa_name:>对<:pd_name:>发动了技能「{$clubskillname[517]}」！</span><br>");
					
					$remtime--; 
					\skillbase\skill_setvalue(517,'rmtime',$remtime,$pa);
					$pd['skill517_flag']=1;
					addnews ( 0, 'bskill517', $pa['name'], $pd['name'] );
				}
				else
				{
					if ($active)
					{
						eval(import_module('logger'));
						$log.='剩余次数用尽，不能发动。<br>';
					}
					$pa['bskill']=0;
				}
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	//物理伤害变成0
	function get_physical_dmg_change(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $dmg);
		if (!empty($pa['bskill']) && $pa['bskill']==517) 
		{
			eval(import_module('logger'));
			$log .=  \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b"><:pa_name:>的攻击并没有瞄准<:pd_name:>本身……</span><br>');
			$ret = 0;
		}
		return $ret;
	}
	
	function armor_hurt(&$pa, &$pd, $active, $which, $hurtvalue)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty($pa['bskill']) && $pa['bskill']==517 && 1 == $pa['skill517_hit_flag']) 
		{
			eval(import_module('logger'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"yellow b\">然而，攻击瞄准的是<:pd_name:>的防具！</span><br>");
			$pa['skill517_hit_flag'] = 2;
		}
		return $chprocess($pa, $pd, $active, $which, $hurtvalue);
	}
	
	//（第一次连击）受攻击部位的耐久下降值额外附加武器效果值
	function weapon_wound_success(&$pa, &$pd, $active, $hurtposition)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active, $hurtposition);
		if (!empty($pa['bskill']) && $pa['bskill']==517 && empty($pa['skill517_hit_flag'])) 
		{
			$pa['attack_wounded_'.$hurtposition]+=$pa['wepe'];//round($pa['wepe']/2);
			$pa['skill517_hit_flag'] = 1;
		}
	}
	
	//（第一次连击）必定致伤
	function check_weapon_inf_rate_hit(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!empty($pa['bskill']) && $pa['bskill']==517 && empty($pa['skill517_hit_flag'])){
			return 1;
		}
		return $chprocess($pa, $pd, $active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','clubbase'));
		
		if($news == 'bskill517') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"red b\">「{$clubskillname[517]}」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>