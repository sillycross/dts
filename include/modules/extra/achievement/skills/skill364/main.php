<?php

namespace skill364
{
	//各级要完成的成就名，如果不存在则取低的
	$ach364_name = array(
		0=>'究极逃杀生命体',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach364_desc= array(
		1=>'完成所有成就（日常任务、限期活动、隐藏成就和终生成就除外）',
	);
	
	$ach364_proc_words = '完成版本';
	
	$ach364_unit = '';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	//全局成就此条是废弃的
	$ach364_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的因果奖励
	$ach364_karma_prize = array(
		1 => 200,
		2 => 20,
	);
	
	$ach364_unique_prize_desc = '<font color="olive">首次完成时获得<span class="cyan b">'.$ach364_karma_prize[1].'因果</span>，之后每次完成获得<span class="cyan b">'.$ach364_karma_prize[2].'因果</span></font>';
	
	function init() 
	{
		define('MOD_SKILL364_INFO','achievement;global;');
		define('MOD_SKILL364_ACHIEVEMENT_ID','64');
	}
	
	function acquire364(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost364(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//怕有兼容性问题，保留着吧
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $chprocess($pa, $data, $achid);
	}
	
	//全局成就是否满足条件
	//而是新完成还是已完成，是在ach_global_ach_check()根据版本号判定的
	function ach_global_ach_check_progress(&$ud, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ud, $achid);
		if(364 == $achid){
			eval(import_module('achievement_base'));
			$flag = 1;
			foreach($achlist as $aclass => $av){
				foreach($av as $ai){
					if($achid != $ai && !\skillbase\check_skill_info($ai, 'daily') && !\skillbase\check_skill_info($ai, 'spec-activity') && !\skillbase\check_skill_info($ai, 'secret') && !\skillbase\check_skill_info($ai, 'global'))
					{
						eval(import_module('skill'.$ai));
						if(!empty(${'ach'.$ai.'_threshold'})){
							$null = NULL;
							foreach(${'ach'.$ai.'_threshold'} as $tk => $tv){
								if(!empty($tv) && !\achievement_base\ach_finalize_check_progress($null, $tv, $ud['u_achievements'][$ai], $ai)) {
									$flag = 0;
									break 3;
								}
							}
						}else{
							//旧成就简直蛋疼，障眼法
							$func = '\\skill'.$ai.'\\show_achievement'.$ai;
							if(function_exists($func)){
								ob_start();
								$func($ud['u_achievements'][$ai]);
								$show = ob_get_contents();
								ob_end_clean();
								if(strpos($show, '[完成]')===false) {
									$flag = 0;
									break 2;
								}
							}
						}
						
					}
				}
			}
			$ret = $flag;
		}
		return $ret;
	}
	
	function ach_global_ach_finalize_save_getnum($data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($data, $achid);
		if(364 == $achid){
			eval(import_module('achievement_base'));
			$ret = 0;
			foreach($achlist as $aclass => $av){
				foreach($av as $ai){
					if($achid != $ai && !\skillbase\check_skill_info($ai, 'daily') && !\skillbase\check_skill_info($ai, 'spec-activity') && !\skillbase\check_skill_info($ai, 'secret') && !\skillbase\check_skill_info($ai, 'global'))
					{
						$ret ++ ;
					}
				}
			}
		}
		return $ret;
	}
}

?>