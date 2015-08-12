<?php

namespace skill210
{
	function init() 
	{
		define('MOD_SKILL210_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[210] = '歼灭';
	}
	
	function acquire210(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill210'));
		\skillbase\skill_setvalue(210,'lastuse',-3000,$pa);
	}
	
	function lost210(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked210(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=20;
	}
	
	function upgrade210()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill210','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(210) || !check_unlocked210($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill210_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==1){
			$log.='你已经发动过这个技能了！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		\skillbase\skill_setvalue(210,'lastuse',$now);
		addnews ( 0, 'bskill210', $name );
		$log.='技能<span class=\"yellow\">「歼灭」</span>发动成功。<br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill210_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(210, $pa) || !check_unlocked210($pa)) return 0;
		eval(import_module('sys','player'));
		$l=\skillbase\skill_getvalue(210,'lastuse',$pa);
		if (($now-$l)<=240) return 1;//持续240s
		if (($now-$l)<=1440) return 2;//冷却1200s
		return 3;
	}
	
	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(210,$pa) || !(check_skill210_state($pa)==1)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*100;
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		eval(import_module('logger','skill210'));
		if ((\skillbase\skill_query(210,$pa))&&(check_skill210_state($pa)==1)&&(rand(0,99)<20)&&($pa['wepk'][1]=='K')) 
		{
			if ($active)
				$log.='<span class="red">暴击！</span><span class="yellow">「歼灭」使你造成的物理伤害提高了20%！</span><br>';
			else  $log.='<span class="red">暴击！</span><span class="yellow">「歼灭」使敌人造成的物理伤害提高了20%！</span><br>';
			$r=Array(2.0);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_basic_ex_dmg(&$pa,&$pd,$active,$key){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(210,$pa) || !(check_skill210_state($pa)==1) ||!($pa['wepk'][1]=='K')) return $chprocess($pa, $pd, $active,$key);
		$damage = $ex_base_dmg[$key]+$pa['wepe']/$ex_wep_dmg[$key]+$pa['fin_skill']/$ex_skill_dmg[$key];
		eval(import_module('ex_dmg_att'));
		return $chprocess($pa, $pd, $active)+$pa['att']/$ex_wep_dmg[$key];
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill210') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}发动了技能<span class=\"yellow\">「歼灭」</span></span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
