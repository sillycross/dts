<?php

namespace skill242
{
	//不会被学习的技能（因为要求其他技能而没有意义）
	$sk242_cannot_list=Array(
		33,	//应变
		34,	//百战
		206,	//爆头
		51,	//百出
	);
	
	function init() 
	{
		define('MOD_SKILL242_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[242] = '灵感';
	}
	
	function acquire242(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(242,'c',0,$pa);
		\skillbase\skill_setvalue(242,'i',0,$pa);
	}
	
	function lost242(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(242,'c',$pa);
		\skillbase\skill_delvalue(242,'i',$pa);
	}
	
	function check_unlocked242(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function upgrade242()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','clubbase','skill242','logger'));
		if (!\skillbase\skill_query(242) || !check_unlocked242($sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$remcnt=$lvl-(int)\skillbase\skill_getvalue(242,'c');
		if ($remcnt<=0)
		{
			$log .= '你现在不能改变技能。<br>';
			return;
		}
		$can_list = Array();
		foreach ($clublist as $nowclub => $arr)
			if ($nowclub!=$club)
			{
				foreach ($arr['skills'] as $skillid)
					if (\sklearn_util\sklearn_basecheck($skillid) && !in_array($skillid,$sk242_cannot_list) && !\skillbase\skill_query($skillid))
						array_push($can_list,$skillid);
			}
		$sk=$can_list[rand(0,count($can_list)-1)];
		
		$ori_sk=(int)\skillbase\skill_getvalue(242,'i');
		if ($ori_sk) \skillbase\skill_lost($ori_sk);
		\skillbase\skill_acquire($sk);
		\skillbase\skill_setvalue(242,'i',$sk);
		$z=(int)\skillbase\skill_getvalue(242,'c'); $z++;
		\skillbase\skill_setvalue(242,'c',$z);
		$log.="改变成功。<br>";
	}
}

?>
