<?php

namespace skill269
{
	//怒气消耗
	$ragecost = 100; 
	//下位怒气消耗
	$ragecost0 = 40; 
	
	function init() 
	{
		define('MOD_SKILL269_INFO','club;battle;limited;');
		eval(import_module('clubbase'));
		$clubskillname[269] = '浴血';
	}
	
	function acquire269(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//剩余次数
//		\skillbase\skill_setvalue(269,'rmt',2,$pa);
	}
	
	function lost269(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
//		\skillbase\skill_delvalue(269,'rmt',$pa);
	}
	
	function check_unlocked269(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=21;
	}
	
	function check_available269(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;//不再需要毅重生效
//		return \skill28\check_available28($pa);
	}
	
	//如果满足下位怒气消耗，也可以消耗怒气+生命发动
	function check_battle_skill_unactivatable(&$ldata,&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata,$edata,$skillno);
		if(269 == $skillno && 3 == $ret){
			eval(import_module('skill269'));
			if($ldata['rage'] >= $ragecost0 && $ldata['hp'] > get_hp_cost269($ldata, $edata)) $ret = 0;//满足要求，返回0
			else $ret = 7;//都不满足，返回7
		}
		return $ret;
	}
	
	function get_rmt269(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = \skillbase\skill_getvalue(269,'rmt',$pa);
		if(!$ret) $ret = 0;
		return $ret;
	}
	
	function get_rage_cost269()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill269'));
		return $ragecost;
	}
	
	function get_min_rage_cost269()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill269'));
		return $ragecost0;
	}
	
	function get_hp_cost269(&$pa, &$pd, $fuzzy = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$hpcost = max( round($pa['mhp']*0.25), round(($pa['hp'] - $pd['hp'])/2));//还是减半吧
		if($hpcost > $pa['hp'] - 1) $hpcost = $pa['hp'] - 1;
		if($fuzzy) {
			$c = floor($hpcost/50);
			$cmin = max(0,($c-1)*50); $cmax = max(0,($c+1)*50);
			$hpcost = '大约'.$cmin.'-'.$cmax;
		}
		return $hpcost;
	} 
	
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=269) {
			$chprocess($pa, $pd, $active);
			return;
		}
		$hpcost = get_hp_cost269($pa, $pd);
		$rcost = get_rage_cost269();
		$rcost0 = get_min_rage_cost269();
		eval(import_module('logger'));
		if (!\skillbase\skill_query(269,$pa) || !check_unlocked269($pa))
		{
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		elseif (\clubbase\check_battle_skill_unactivatable($pa,$pd,269)) {//跟其他是反着的，历史原因
			if($pa['rage'] < $rcost0)
			{
				$log .= '你怒气不足，不能发动此技能！';
				$pa['bskill']=0;
			}
			elseif($hpcost >= $pa['hp'])
			{
				$log .= '你的身体状态不允许你发动这个技能！';
				$pa['bskill']=0;
			}
			else
			{
				$log .= '由于其他原因不能发动技能，可能是BUG';
				$pa['bskill']=0;
			}
		}		
		else
		{
			$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"lime b\"><:pa_name:>对<:pd_name:>发动了技能「浴血」！</span><br>");
			
			if ($pa['rage']>=$rcost)
			{
				//怒气充足则消耗怒气
				$pa['rage']-=$rcost;
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"yellow b\"><:pa_name:>打出了排山倒海的一击，物理伤害增加了<span class=\"yellow b\">{$hpcost}</span>点！</span><br>");
			}
			else
			{
				//否则消耗较低怒气值+生命
				$pa['rage']-=$rcost0;
				$pa['hp']-=$hpcost;
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"yellow b\"><:pa_name:>燃烧了<span class=\"red b\">{$hpcost}</span>点生命值，打出了视死如归的一击！</span><br>");
			}
			
			$pa['skill269_hpcost']=$hpcost;
//			\skillbase\skill_setvalue(269,'rmt',$rmt-1,$pa);
			addnews ( 0, 'bskill269', $pa['name'], $pd['name'] );
		}
		$chprocess($pa, $pd, $active);
	}	
	
	//物理固伤
	function get_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=0;
		if(!empty($pa['skill269_hpcost'])) {
			$r=$pa['skill269_hpcost'];
			unset($pa['skill269_hpcost']);
		}
		return $chprocess($pa, $pd, $active)+$r;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill269') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「浴血」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>