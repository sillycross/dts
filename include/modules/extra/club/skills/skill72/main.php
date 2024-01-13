<?php

namespace skill72
{
	//最大学习技能数目
	$sk72_max_learn_num = 6;
	
	//学习消耗技能点数目
	$skill72_skpoint_need = Array(
		25 => 15,
		26 => 7,
		27 => 9,
		28 => 10,
		
		30 => 21,
		
		32 => 12,
		33 => 10,
		34 => 15,
		35 => 12,
		36 => 5,
		37 => 8,
		38 => 21,
		
		40 => 15,
		
		44 => 4,
		45 => 5,
		
		47 => 3,
		48 => 8,
		49 => 2,
		50 => 9,
		51 => 5,
		52 => 3,
		
		54 => 9,
		
		60 => 10,
		61 => 15,
		
		64 => 14,
		65 => 7,
		66 => 7,
		67 => 10,
		68 => 1,
		
		71 => 5,
		
		73 => 18,
		74 => 18,
		75 => 4,
		76 => 10,
		
		82 => 5,
		85 => 5,
		
		201 => 8,
		202 => 8,
		203 => 3,
		204 => 14,
		205 => 16,
		206 => 12,
		207 => 11,
		208 => 25,
		209 => 18,
		210 => 24,
		211 => 7,
		212 => 7,
		213 => 5,
		214 => 14,
		215 => 16,
		
		217 => 22,
		218 => 14,
		
		221 => 12,
		222 => 12,
		
		226 => 10,
		
		228 => 18,
		229 => 16,
		
		233 => 5,
		
		235 => 5,
		236 => 4,
		237 => 13,
		
		239 => 12,
		240 => 9,
		241 => 6,
		
		243 => 11,
		244 => 20,

		251 => 12,
		252 => 5,
		253 => 16,
		254 => 16,
		
		257 => 20,
		258 => 6,
		259 => 9,
		
		262 => 12,
		263 => 20,
		
		265 => 18,
		
		267 => 18,
		
		270 => 10,
		271 => 14,
		
		273 => 14,
		274 => 12,
	);
	
	function init() 
	{
		define('MOD_SKILL72_INFO','club;upgrade;locked;');
	}
	
	function acquire72(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(72,'t','0',$pa);
		\skillbase\skill_setvalue(72,'b','0',$pa);
	}
	
	function lost72(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked72(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function sklearn_checker72($a='', $b='')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($a=='caller_id') return 72;
		if ($a=='show_cost') return 1;
		if ($a=='query_cost')
		{
			eval(import_module('skill72'));
			return $skill72_skpoint_need[((int)$b)];
		}
		if ($a=='is_learnable') 
		{
			eval(import_module('skill72'));
			$x=(int)\skillbase\skill_getvalue(72,'t');
			if ($x>=$sk72_max_learn_num) return 0;	//学习数目达到上限
			
			$x=(int)\skillbase\skill_getvalue(72,'b');
			if ($x==0) return 1;				//尚未学习战斗技能，可以随便学
			
			$val=constant('MOD_SKILL'.$b.'_INFO');
			if (strpos($val,'battle;')===false) return 1;	//至多一个战斗技能
			return 0;
		}
		if ($a=='now_learnable') 
		{
			eval(import_module('skill72','player'));
			if ($skillpoint>=$skill72_skpoint_need[((int)$b)]) return 1; else return 0;
		}
	}
	
	function upgrade72()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill72','player','logger'));
		$skillpara1 = (int)get_var_input('skillpara1');
		if (!\skillbase\skill_query(72) || !check_unlocked72($sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (!\sklearn_util\sklearn_basecheck($skillpara1) || !sklearn_checker72('is_learnable',$skillpara1)) 
		{
			$log.='你不可以学习这个技能！<br>';
			return;
		}
		if (!sklearn_checker72('now_learnable',$skillpara1))
		{
			$log.='现在尚没有足够资源学习这个技能！<br>';
			return;
		}
		if (\skillbase\skill_query($skillpara1))
		{
			$log.='你已经拥有这个技能了！<br>';
			return;
		}
		
		$x=(int)\skillbase\skill_getvalue(72,'t');
		$x++;
		\skillbase\skill_setvalue(72,'t',$x);
		
		$val=constant('MOD_SKILL'.$skillpara1.'_INFO');
		if (strpos($val,'battle;')!==false)
		{
			$x=(int)\skillbase\skill_getvalue(72,'b');
			$x++;
			\skillbase\skill_setvalue(72,'b',$x);
		}
		
		$skillpoint-=$skill72_skpoint_need[$skillpara1];
		
		\skillbase\skill_acquire($skillpara1);
		$log.='学习成功。<br>';
	}
}

?>
