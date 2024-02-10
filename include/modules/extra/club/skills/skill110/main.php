<?php

namespace skill110
{
	$skill110_cd = 240;
	
	function init()
	{
		define('MOD_SKILL110_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[110] = '通才';
	}
	
	function acquire110(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(110,'lastuse',-3000,$pa);
	}
	
	function lost110(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(110,'lastuse',$pa);
	}
	
	function check_unlocked110(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function skill110_tempskill_list(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$tsklist = array();
		$acquired_skills = \skillbase\get_acquired_skill_array($pa);
		foreach ($acquired_skills as $skillid)
		{
			$tsk_expire = \skillbase\skill_getvalue($skillid, 'tsk_expire', $pa);
			if (!empty($tsk_expire)) $tsklist[] = $skillid;
		}
		return $tsklist;
	}
	
	function skill110_learn_tempskill($skillid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$acquired_skills = \skillbase\get_acquired_skill_array($pa);
		if (!in_array($skillid, $acquired_skills))
		{
			$log .= "你没有这个技能。<br>";
			return;
		}
		eval(import_module('sys'));
		$tsk_expire = \skillbase\skill_getvalue($skillid, 'tsk_expire', $pa);
		if (empty($tsk_expire) || ($now > $tsk_expire))
		{
			$log .= "输入参数不正确。<br>";
			return;
		}
		\skillbase\skill_delvalue($skillid, 'tsk_expire', $pa);
		\skillbase\skill_setvalue(110, 'lastuse', $now, $pa);
		eval(import_module('clubbase'));
		$log .= "你记住了技能<span class=\"yellow b\">「{$clubskillname[$skillid]}」</span>。<br>";
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill110_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(110, $pa) || !check_unlocked110($pa)) return 0;
		eval(import_module('sys','player','skill110'));
		$l=\skillbase\skill_getvalue(110,'lastuse',$pa);
		if (($now-$l)<=$skill110_cd) return 2;
		return 3;
	}
	
	function cast_skill110()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(110, $sdata) || !check_unlocked110($sdata)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$skill110_skillid = (int)get_var_input('skill110_skillid');
		if (!empty($skill110_skillid))
		{
			$st = check_skill110_state($sdata);
			if ($st==0)
			{
				$log .= '你不能使用这个技能！<br>';
				return;
			}
			elseif ($st==2)
			{
				$log .= '技能冷却中！<br>';
				return;
			}
			skill110_learn_tempskill($skill110_skillid, $sdata);
			$mode = 'command';
			return;
		}
		include template(MOD_SKILL110_CASTSK110);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill110_special' && get_var_input('subcmd')=='castsk110') 
		{
			cast_skill110();
			return;
		}
		$chprocess();
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(110,$sdata))&&check_unlocked110($sdata))
		{
			eval(import_module('skill110'));
			$skill110_lst = (int)\skillbase\skill_getvalue(110,'lastuse'); 
			$skill110_time = $now-$skill110_lst;
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「通才」',
				'activate_hint' => '点击发动技能「通才」',
				'onclick' => "$('mode').value='special';$('command').value='skill110_special';$('subcmd').value='castsk110';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill110_time<$skill110_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill110_cd;
				$z['nowsec']=$skill110_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill110.gif',$z);
		}
		$chprocess();
	}
	
}

?>