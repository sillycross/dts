<?php

namespace skill453
{
	$skill453_cd = 36000;
	$skill453_act_time = 30;
	$skill453_buff_lose_time = 120;	//失去buff的时间，秒
	$skill453_factor = 10;
	
	function init() 
	{
		define('MOD_SKILL453_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[453] = '狂击';
	}
	
	function acquire453(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill453'));
		\skillbase\skill_setvalue(453,'lasthit',$now,$pa);
		\skillbase\skill_setvalue(453,'target','',$pa);
		\skillbase\skill_setvalue(453,'tarpid',0,$pa);
		\skillbase\skill_setvalue(453,'cnt',0,$pa);
	}
	
	function lost453(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked453(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(453,$pa) && !$pa['is_counter']) 	//反击不触发
		{
			sk453_skill_status_update();
			eval(import_module('sys','logger','skill453'));
			$tarpid = (int)\skillbase\skill_getvalue(453,'tarpid',$pa); 
			$skill453_count = (int)\skillbase\skill_getvalue(453,'cnt',$pa); 
			
			if ($pd['pid']!=$tarpid || $skill453_count==0)
			{
				//主动攻击其他目标，重置层数
				$skill453_count=0;
				\skillbase\skill_setvalue(453,'target',$pd['name'],$pa); 
				\skillbase\skill_setvalue(453,'tarpid',$pd['pid'],$pa); 
			}
			$skill453_count++;
			$rat=$skill453_count*$skill453_factor;
			if ($active)
				$log.='<span class="yellow b">你对敌人的连续攻击使伤害增加了'.$rat.'%！</span><br>';
			else  $log.='<span class="yellow b">敌人对你的连续攻击使伤害增加了'.$rat.'%！</span><br>';
			$r=Array(1+$rat/100.0);
			\skillbase\skill_setvalue(453,'cnt',$skill453_count,$pa); 
			\skillbase\skill_setvalue(453,'lasthit',$now,$pa);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function sk453_skill_status_update()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill453'));
		$skill453_lst = (int)\skillbase\skill_getvalue(453,'lasthit'); 
		$skill453_count = (int)\skillbase\skill_getvalue(453,'cnt'); 
		//自动失去buff
		$tm=$now-$skill453_lst;
		$down=floor($tm/$skill453_buff_lose_time);
		if ($down>0)
		{
			$skill453_count=max($skill453_count-$down,0);
			$skill453_lst+=$skill453_buff_lose_time*$down;
			\skillbase\skill_setvalue(453,'lasthit',$skill453_lst);
			\skillbase\skill_setvalue(453,'cnt',$skill453_count);
		}
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(453,$sdata))&&check_unlocked453($sdata))
		{
			sk453_skill_status_update();
			
			eval(import_module('skill453'));
			$skill453_lst = (int)\skillbase\skill_getvalue(453,'lasthit'); 
			$skill453_target = \skillbase\skill_getvalue(453,'target'); 
			$skill453_count = (int)\skillbase\skill_getvalue(453,'cnt'); 
			$rat=$skill453_count*$skill453_factor;
			
			$nlostime = $skill453_buff_lose_time-($now-$skill453_lst);
			
			$z=Array(
				'style' => 3,
				'disappear' => 0,
				'clickable' => 0,
			);
			if ($skill453_count>0)
			{
				$z['activate_hint'] = "你对{$skill453_target}造成的最终伤害将增加{$rat}%<br>{$nlostime}秒内没有攻击将会自动失去一层效果";
				$z['corner-text'] = $skill453_count;
			}
			else
			{
				$z['activate_hint'] = "你连续主动攻击同一目标时<br>造成的伤害每次增加{$skill453_factor}%";
			}
			
			\bufficons\bufficon_show('img/skill453.gif',$z);
		}
		$chprocess();
	}
}

?>
