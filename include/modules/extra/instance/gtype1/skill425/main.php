<?php

namespace skill425
{
	$skill425_cd = 90;
	$skill425_cost=500;
	
	function init() 
	{
		define('MOD_SKILL425_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[425] = '重载';
	}
	
	function acquire425(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill425'));
		\skillbase\skill_setvalue(425,'lastuse',-3000,$pa);
		\skillbase\skill_setvalue(425,'bribe_times','0',$pa);
	}
	
	function lost425(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked425(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	//return 0:没有这个技能 1:CD中，金钱少于500 2:CD中，金钱大于500（花钱） 3:CD完毕 4:花钱使用次数不足
	function check_skill425_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(425, $pa) || !check_unlocked425($pa)) return 0;
		eval(import_module('sys','player','skill425'));
		$l=\skillbase\skill_getvalue(425,'lastuse',$pa);
		$b=\skillbase\skill_getvalue(425,'bribe_times',$pa);
		if (($now-$l)<=$skill425_cd){
			if ($pa['money'] < $skill425_cost) return 1;
			elseif($b >= get_skill425_max_times()) return 4;
			return 2;
		}
		return 3;
	}
	
	function activate425()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill425','skill424','player','logger','sys','skillbase'));
		\player\update_sdata();
		if (!\skillbase\skill_query(425) || !check_unlocked425($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st=check_skill425_state($sdata);
		if ($st==1){
			$log.='你的金钱不足！<br>';
			return;
		}elseif($st==4){
			$log.='贿赂次数已经用完，请升级层数后再尝试！<br>';
			return;
		}
		if ($st==2){
			$money-=$skill425_cost;
			$log.='<span class="lime b">消耗了'.$skill425_cost.'元，</span>';
			$bt=\skillbase\skill_getvalue(425,'bribe_times',$sdata);
			\skillbase\skill_setvalue(425,'bribe_times',$bt+1,$sdata);
		}
		$log.='<span class="lime b">技能「重载」发动成功。</span><br>';
		\skillbase\skill_setvalue(425,'lastuse',$now);
		\skill424\wdebug_reset();
		$log .='下次除错需要物品'.\skill424\wdebug_showreq();
	}
	
	//每3级有1次花钱重载机会
	function get_skill425_max_times()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$clv=\skillbase\skill_getvalue(424,'lvl');
		return ceil($clv/3);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(425,$sdata))&&check_unlocked425($sdata))
		{
			eval(import_module('skill425'));
			$skill425_lst = (int)\skillbase\skill_getvalue(425,'lastuse'); 
			$skill425_time = $now-$skill425_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「重载」',
				'activate_hint' => '点击发动技能「重载」',
				'onclick' => "$('mode').value='special';$('command').value='skill425_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill425_time<$skill425_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill425_cd;
				$z['nowsec']=$skill425_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill425.gif',$z);
		}
		$chprocess();
	}
}

?>
