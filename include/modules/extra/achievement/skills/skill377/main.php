<?php

namespace skill377
{
	//各级要完成的成就名，如果不存在则取低的
	$ach377_name = array(
		1=>'<:secret:>H4sIAAAAAAAAClOKKTVJNTSLKTU3NUmOKTW1tDCKKTVLMjMBso1T04DiRsaGSgBqf0/wJgAAAA==',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach377_desc= array(
		1=>'<:secret:>H4sIAAAAAAAAClWOyw0CMQxEe9kKnPgXuqAAX5JscuCyEtr0j70IBBfLejP2zGaLpk5byrnYYpTkeya1hQA9CFf3DNydg7ItKVUu1QlrXNEA8L2TTxnoRDLM4Nk9ohdv2myVNDGcKckl1/94fxRPKYJVCn1KiGqKsN0Jt1Huz+M8HqOf3x4/vd+HivUW5ibbC+g/GM7kAAAA',
	);
	
	$ach377_proc_words = '当前纪录';
	
	$ach377_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach377_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach377_qiegao_prize = array(
		1 => 300,
	);
	
	//各级给的卡片奖励
	$ach377_card_prize = array(
	);
	
	function init() 
	{
		define('MOD_SKILL377_INFO','achievement;secret;');
		define('MOD_SKILL377_ACHIEVEMENT_ID','77');
	}
	
	function acquire377(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(377,'killed_list','',$pa);
	}
	
	function lost377(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function encode_killedlist377($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($arr)) return '';
		return implode('_', $arr);
	}
	
	function decode_killedlist377($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($str)) return Array();
		return explode('_', $str);
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if(!empty($pa['card']) && \skillbase\skill_query(377,$pa) && !$pd['type'] && \achievement_base\achievement_secret_decode('<:secret:>H4sIAAAAAAAAClOKKTU1Nk2MKTVJNU6JKTU3MDeNKTWzSDRTAgCb7Sp3GgAAAA==')==$pa['cardname'])
		{
			$pd_card = \cardbase\check_realcard($pd['card'], $pd['cardname']);
			if(!empty($pd_card)) {
				eval(import_module('cardbase'));
				if(\achievement_base\achievement_secret_decode('<:secret:>H4sIAAAAAAAAClOKKTUzNzeMKTU1TzEBkkmpFgFF+SX5WanJJUoAhhDwqx0AAAA=') == $cards[$pd_card]['pack']) {
					//判定是不是已经在列表里
					$killed_list = decode_killedlist377(\skillbase\skill_getvalue(377,'killed_list',$pa));
					if(!in_array($pd_card, $killed_list)) {
						$x=(int)\skillbase\skill_getvalue(377,'cnt',$pa);
						$x+=1;
						\skillbase\skill_setvalue(377,'cnt',$x,$pa);
						$killed_list[] = $pd_card;
						\skillbase\skill_setvalue(377,'killed_list',encode_killedlist377($killed_list),$pa);
					}
				}
			}
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 377 && (int)\skillbase\skill_getvalue(377,'cnt',$pa) >= 6){//需要每局击杀数大约等于6次
			$ret += 1;
		}
		return $ret;
	}
}

?>