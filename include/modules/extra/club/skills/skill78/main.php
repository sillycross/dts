<?php

namespace skill78
{
	function init() 
	{
		define('MOD_SKILL78_INFO','club;upgrade;locked;');
	}
	
	function acquire78(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost78(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked78(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade78()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','weapon'));
		if (!\skillbase\skill_query(78))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$skillpara1 = (int)get_var_input('skillpara1');
		$skillpara2 = get_var_input('skillpara2');
		if ($skillpara1 <= 0)
		{
			$log.='技能点指令错误！<br>';
			return;
		}
		if ($skillpoint<1 || $skillpoint < $skillpara1) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		if (!$skillpara2 || !isset($skilltypeinfo[$skillpara2]))
		{
			$log.='系别指令错误！<br>';
			return;
		}
		$dice = $skillpara1 * 5;
		${$skillpara2} += $dice;
		$log.='消耗了<span class="lime b">'.$skillpara1.'</span>点技能点，你的'.$skilltypeinfo[$skillpara2].'系熟练度提升了<span class="yellow b">'.$dice.'</span>点。<br>';
		$skillpoint-=$skillpara1;
	}
	
	function get_max_skillkindname78(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','weapon'));
		$maxkind = 'wp';$maxvar = 0;
		foreach(array_keys($skilltypeinfo) as $v){
			$nowkind = $v; $nowvar = $$v;
			if($nowvar > $maxvar){
				$maxvar = $nowvar;
				$maxkind = $nowkind;
			}
		}
		return $maxkind;
	}
}

?>