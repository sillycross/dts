<?php

namespace skill274
{
	//附加伤害比例
	$factor = array(10,20,30,40);
	$npc_factor = 2;
	//升级所需技能点数值
	$upgradecost = array(3,5,7,-1);
	
	$ragecost=30;
	$wepk_req='WN';
//	$skill274_factor_pc = 40;//附加对面总面板攻击力25%的最终伤害
//	$skill274_factor_npc = 75;
	$skill274_factor_maxhp = 40;//不超过对方mhp
	$skill274_263up = 30;//格斗触发概率+10%
	
	$alternate_skillno274 = 259;//互斥技能编号
	$unlock_lvl274 = 7;//解锁等级
	
	function init() 
	{
		define('MOD_SKILL274_INFO','club;battle;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[274] = '截拳';
	}

	function acquire274(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(274,'lvl','0',$pa);
		\skillbase\skill_setvalue(274,'unlocked','0',$pa);	//是否已经被解锁
	}
	
	function unlock274(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(274,'unlocked','1',$pa);
	}
	
	function lost274(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(274,'lvl',$pa);
		\skillbase\skill_delvalue(274,'unlocked',$pa);
	}
	
	function check_unlocked274(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\clubbase\skill_check_unlocked_state(274, $pa) > 0) return 0;
		else return 1;
	}
	
	function get_rage_cost274(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill274'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==274) {
			if (!\skillbase\skill_query(274,$pa) || !check_unlocked274($pa))
			{
				eval(import_module('logger'));
				$log .= '你尚未解锁这个技能！';
				$pa['bskill']=0;
			}
			else
			{
				$rcost = get_rage_cost274($pa);
				if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,274) )
				{
					eval(import_module('logger'));
					$log .= \battle\battlelog_parser($pa,$pd,$active,"<span class=\"lime b\"><:pa_name:>对<:pd_name:>发动了技能「截拳」！</span><br>");
					$pa['rage']-=$rcost;
					$pa['skill274_flag'] = 1;
					addnews ( 0, 'bskill274', $pa['name'], $pd['name'] );
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
		}
		return $chprocess($pa, $pd, $active);
	}	
	
	function upgrade274()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill274','player','logger','clubbase'));
		if (!\skillbase\skill_query(274))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$skillpara1 = get_var_input('skillpara1');
		if('choose'==$skillpara1) {
			if (\skillbase\skill_getvalue(274,'unlocked') > 0)
			{
				$log .= '你已经选择了这个技能<br>';
				return;
			}

			\skillbase\skill_setvalue(274,'unlocked',1);
			
			$log.='技能「'.$clubskillname[274].'」选择成功。<br>';
		}else{
			if( !check_unlocked274($sdata)) {
				$log .= '你尚未解锁这个技能<br>';
				return;
			}
			$clv = \skillbase\skill_getvalue(274,'lvl');
			$ucost = $upgradecost[$clv];
			if ($clv == -1)
			{
				$log.='你已经升级完成了，不能继续升级！<br>';
				return;
			}
			if ($skillpoint<$ucost) 
			{
				$log.='技能点不足。<br>';
				return;
			}
			$skillpoint-=$ucost; \skillbase\skill_setvalue(274,'lvl',$clv+1);
			$log.='技能「'.$clubskillname[274].'」升级成功。<br>';
		}
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (274==$pa['bskill']) 
		{
			eval(import_module('logger'));
			$v = get_skill274_dmg($pa, $pd, $active);
			if($v) {
				$log.=\battle\battlelog_parser($pa,$pd,$active,'你如流水一般的灵活攻击使<:pd_name:>额外受到<span class="yellow b">'.$v.'</span>点伤害！<br>');
				$ret += $v;
				$pa['mult_words_fdmgbs'] = \attack\add_format($v, $pa['mult_words_fdmgbs']);
			}
		}
		return $ret;
	}	
	
	function get_skill274_factor($lv, $tp){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$f = 0;
		eval(import_module('skill274'));
		$f = $factor[$lv];
		if($tp) $f *= $npc_factor;
		return $f;
	}
	
	function get_skill274_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$v = 0;
		if (274==$pa['bskill']) 
		{
			eval(import_module('skill274'));
			$clv = \skillbase\skill_getvalue(274,'lvl');
			$f = get_skill274_factor($clv, $pd['type']);
			$v = round(min($pd['mhp'] * $skill274_factor_maxhp / 100, ($pd['att'] + $pd['wepe']) * $f / 100));
		}
		return $v;
	}
	
	function get_skill263_chance(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (!empty($pd['skill274_flag']))
		{
			eval(import_module('skill274'));
			$ret += $skill274_263up;
		}
		return $ret;
	}	

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill274') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「截拳」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>