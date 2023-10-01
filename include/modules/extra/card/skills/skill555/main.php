<?php

namespace skill555
{
	$skill555_cd = 180;
	$skill555_act_time = 20;
	
	function init() 
	{
		define('MOD_SKILL555_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[555] = '迷彩';
	}
	
	function acquire555(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill555'));
		\skillbase\skill_setvalue(555,'lastuse',0,$pa);
	}
	
	function lost555(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked555(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate555()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill555','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(555) || !check_unlocked555($sdata))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		$st = check_skill555_state($sdata);
		if ($st == 0){
			$log .= '你不能使用这个技能！<br>';
			return;
		}
		if ($st == 1){
			$log .= '你已经发动过这个技能了！<br>';
			return;
		}
		if ($st == 2){
			$log .= '技能冷却中！<br>';
			return;
		}
		\skillbase\skill_setvalue(555,'lastuse',$now);
		$log .= '<span class="lime b">你发动了「光学迷彩」，现在谁也注意不到你了！——虽然有效时间很短。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill555_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(555, $pa) || !check_unlocked555($pa)) return 0;
		eval(import_module('sys','player','skill555'));
		$lastuse = \skillbase\skill_getvalue(555,'lastuse',$pa);
		if (($now - $lastuse) <= $skill555_act_time) return 1;
		if (($now - $lastuse) <= $skill555_act_time + $skill555_cd) return 2;
		return 3;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(555,$sdata))
		{
			eval(import_module('skill555'));
			$skill555_lst = (int)\skillbase\skill_getvalue(555,'lastuse'); 
			$skill555_time = $now - $skill555_lst; 
			$z = Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「迷彩」',
				'activate_hint' => '点击发动技能「迷彩」',
				'onclick' => "$('mode').value='special';$('command').value='skill555_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill555_time < $skill555_act_time)
			{
				$z['style'] = 1;
				$z['totsec'] = $skill555_act_time;
				$z['nowsec'] = $skill555_time;
			}
			else if ($skill555_time < $skill555_act_time+$skill555_cd)
			{
				$z['style'] = 2;
				$z['totsec'] = $skill555_cd;
				$z['nowsec'] = $skill555_time - $skill555_act_time;
			}
			else 
			{
				$z['style'] = 3;
			}
			\bufficons\bufficon_show('img/skill555.gif',$z);
		}
		$chprocess();
	}
	
	//迷彩状态不会主动遇到敌人和尸体
	function discover_player()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));		
		if (1 == check_skill555_state($sdata)) {
			$log .= "<span class=\"yellow b\">你借助光学迷彩避开了全部敌人，专心摸鱼。</span><br>";
			return false;
		}
		return $chprocess();
	}
}

?>