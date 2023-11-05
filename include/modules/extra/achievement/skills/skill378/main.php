<?php

namespace skill378
{
	//各级要完成的成就名，如果不存在则取低的
	$ach378_name = array(
		1=>'<:secret:>H4sIAAAAAAAAClMKycgsVkhJrFQAUun5mXnpCiX5CkmpCgWpRWmpySV6SgCFucbKIgAAAA==',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach378_desc= array(
		1=>'<:secret:>H4sIAAAAAAAACnWOsQ3AIAwEd2ECA8aGXWjiBG/A/nlHSpEijXU6v16f5m5a+tzs6nNrCx7SwaL1ABMNZEolfKUzuEoGF9bXCJ0NPhsuL7K53ekM4xY9TN9+ZFYYgW/8JJmu/34MiQ1mkm64W0d1sAAAAA==',
	);
	
	$ach378_proc_words = '当前纪录';
	
	$ach378_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach378_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach378_qiegao_prize = array(
		1 => 300,
	);
	
	//各级给的卡片奖励
	$ach378_card_prize = array(
	);
	
	function init() 
	{
		define('MOD_SKILL378_INFO','achievement;secret;');
		define('MOD_SKILL378_ACHIEVEMENT_ID','78');
	}
	
	function acquire378(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost378(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		//分两次判定，把需要import的条件放第二层
		if(!empty($pa['card']) && \skillbase\skill_query(378,$pa) && !$pd['type'] && $pa['cardname']==$pd['cardname'])
		{
			eval(import_module('cardbase'));
			if($cards[$pa['card']]['name'] != $pa['cardname']) {
				$x=(int)\skillbase\skill_getvalue(378,'cnt',$pa);
				$x+=1;
				\skillbase\skill_setvalue(378,'cnt',$x,$pa);
			}
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 378 && (int)\skillbase\skill_getvalue(378,'cnt',$pa)){
			$ret += 1;
		}
		return $ret;
	}
}

?>