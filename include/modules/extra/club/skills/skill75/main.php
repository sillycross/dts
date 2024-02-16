<?php

namespace skill75
{
	$skill75_cd = 90;
	$wep_skillkind_req = 'wk';
	
	function init() 
	{
		define('MOD_SKILL75_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[75] = '剑心';
	}
	
	function acquire75(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill75'));
		if ($pa['club']==2)
		{
			\skillbase\skill_setvalue(75,'lastuse',-3000,$pa);
		}
		else
		{
			\skillbase\skill_setvalue(75,'lastuse',$now,$pa);
		}
	}
	
	function lost75(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked75(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill75_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(75, $pa) || !check_unlocked75($pa)) return 0;
		eval(import_module('sys','player','skill75'));
		$l=\skillbase\skill_getvalue(75,'lastuse',$pa);
		if (($now-$l)<=$skill75_cd) return 2;
		return 3;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=75) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(75,$pa) || !check_unlocked75($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			eval(import_module('sys','skill75'));
			//$l=\skillbase\skill_getvalue(75,'lastuse',$pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,75) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「剑心」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「剑心」！</span><br>";
				\skillbase\skill_setvalue(75,'lastuse',$now,$pa);
				addnews ( 0, 'bskill75', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log.='冷却时间未到或其他原因不能发动。<br>';
				}
				$pa['bskill']=0;
			}
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if ($pa['bskill']==75 && $pa['is_hit']) 
		{
			eval(import_module('logger'));
			$d=$pa['lvl']+30;
			$log.='<span class="yellow b">「剑心」附加了'.$d.'点伤害！</span><br>';
			$ret += $d;
			$pa['mult_words_fdmgbs'] = \attack\add_format($d, $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
//	function strike_finish(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if ($pa['bskill']==75 && $pa['is_hit'])
//		{
//			eval(import_module('logger'));
//			$d=$pa['lvl']+30;
//			$log.='<span class="yellow b">「剑心」附加了'.$d.'点伤害！</span><br>';
//			$pa['dmg_dealt']+=$d;
//		}
//		$chprocess($pa, $pd, $active);
//	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(75,$sdata))&&check_unlocked75($sdata))
		{
			eval(import_module('skill75'));
			$skill75_lst = (int)\skillbase\skill_getvalue(75,'lastuse'); 
			$skill75_time = $now-$skill75_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '战斗技「剑心」',
				'activate_hint' => '战斗技「剑心」已就绪<br>在战斗界面可以发动',
				'onclick' => "",
			);
			if ($skill75_time<$skill75_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill75_cd;
				$z['nowsec']=$skill75_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill75.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill75') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「剑心」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
