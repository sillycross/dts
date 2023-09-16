<?php

namespace skill426
{
	$upgradecost = Array(5,-1);
	$skill426_cd = array(180,100);
	
	function init() 
	{
		define('MOD_SKILL426_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[426] = '整备';
	}
	
	function acquire426(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(426,'lvl','0',$pa);
		\skillbase\skill_setvalue(426,'lastuse',-3000,$pa);
	}
	
	function lost426(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked426(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade426()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill426','player','logger'));
		if (!\skillbase\skill_query(426))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(426,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(426,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function activate426()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill426','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(426) || !check_unlocked426($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill426_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		if ( $hp>=$mhp && $sp>=$msp && empty($inf)){
			$log.='你十分健康，不需要使用这个技能。<br>';
			return;
		}
		\skillbase\skill_setvalue(426,'lastuse',$now);
		$hp=max($hp,$mhp);$sp=max($sp,$msp);$inf = '';
		$log.='<span class="lime b">技能「整备」发动成功。</span><br>';
		$log.='<span class="lime b">你的身体已经焕然一新了！</span><br>';
	}
	
	function check_skill426_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(426, $pa) || !check_unlocked426($pa)) return 0;
		eval(import_module('sys','player','skill426'));
		$l=\skillbase\skill_getvalue(426,'lastuse',$pa);
		$v=\skillbase\skill_getvalue(426,'clv',$pa);
		if (($now-$l)<=$skill426_cd[$v]) return 2;
		return 3;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(426,$sdata))&&check_unlocked426($sdata))
		{
			eval(import_module('skill426'));
			$skill426_lst = (int)\skillbase\skill_getvalue(426,'lastuse'); 
			$skill426_clv = (int)\skillbase\skill_getvalue(426,'lvl'); 
			$skill426_time = $now-$skill426_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「整备」',
				'activate_hint' => '点击发动技能「整备」',
				'onclick' => "$('mode').value='special';$('command').value='skill426_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill426_time<$skill426_cd[$skill426_clv])
			{
				$z['style']=2;
				$z['totsec']=$skill426_cd[$skill426_clv];
				$z['nowsec']=$skill426_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill426.gif',$z);
		}
		$chprocess();
	}

}

?>
