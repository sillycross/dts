<?php

namespace skill62
{
	//最大发动次数
	$numlim62 = 7;
	
	function init() 
	{
		define('MOD_SKILL62_INFO','club;upgrade;limited;');
		eval(import_module('clubbase'));
		$clubskillname[62] = '腐蚀';
	}
	
	function acquire62(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill62'));
		\skillbase\skill_setvalue(62,'t',$numlim62,$pa);
	}
	
	function lost62(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill62'));
		$clv = $numlim62-((int)\skillbase\skill_getvalue(62,'t',$pa));
		//倒扣生命上限
		$pa['mhp']-=$clv*100; $pa['hp']-=$clv*100;
		if ($pa['mhp']<=0) $pa['mhp']=1;
		if ($pa['hp']<=0) $pa['hp']=1;
		\skillbase\skill_delvalue(62,'t',$pa);
	}
	
	function check_unlocked62(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function upgrade62()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill62','player','logger'));
		\player\update_sdata();
		if (!\skillbase\skill_query(62) || !check_unlocked62($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = (int)\skillbase\skill_getvalue(62,'t');
		if ($clv == 0)
		{
			$log.='发动次数达到了上限。<br>';
			return;
		}
		\skillbase\skill_setvalue(62,'t',$clv-1);
		$mhp+=50; $hp+=50;
		addnews ( 0, 'bskill62', $name );
		$log.='发动成功。<br>';
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(62,$pa) && check_unlocked62($pa))
		{
			eval(import_module('skill62','logger'));
			$x = 7*($numlim62-((int)\skillbase\skill_getvalue(62,'t',$pa)));
			if ($x>0)
			{
				if ($active)
					$log.='「腐蚀」技能使敌人受到的伤害减少了'.$x.'%！<br>';
				else  $log.='「腐蚀」技能使你受到的伤害减少了'.$x.'%！<br>';
				$r=Array((100-$x)/100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill62') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}发动了技能<span class=\"yellow\">「腐蚀」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
