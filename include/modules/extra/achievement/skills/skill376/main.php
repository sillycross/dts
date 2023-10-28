<?php

namespace skill376
{
	//各级要完成的成就名，如果不存在则取低的
	$ach376_name = array(
		1=>'<:secret:>H4sIAAAAAAAAClOKKTU3NDaLKTU1MUiNKU1LM0gGipibGcaUmqQaGADJNMNEoKyhcZoSAMPRzAssAAAA',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach376_desc= array(
		1=>'<:secret:>H4sIAAAAAAAACjWNwQ2AMAhFVzFOgC0tOoS6ABdbqXrRxMj+osbLD3mfBzVr8DNZOg+sMWG2jMWxeoDcH6dU4y7VsC3r9bLZdgs1lpifGUFYSwHzQhT4PFaS3Nolcum3KLZoJODLu+dPImtRpqa+AWFIf0eIAAAA',
	);
	
	$ach376_proc_words = '当前纪录';
	
	$ach376_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach376_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach376_qiegao_prize = array(
		1 => 200,
	);
	
	//各级给的卡片奖励
	$ach376_card_prize = array(
	);
	
	function init() 
	{
		define('MOD_SKILL376_INFO','achievement;secret;');
		define('MOD_SKILL376_ACHIEVEMENT_ID','76');
	}
	
	function acquire376(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost376(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//受到歌曲影响则给自己加一个记录
	function ss_data_proc_single($sname, &$pdata, $effect, $sscost=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($sname, $pdata, $effect, $sscost);
		if(\achievement_base\achievement_secret_decode('<:secret:>H4sIAAAAAAAAClPyzS9KVfDPS1Xwy0zPKFECAKk9jQ8QAAAA')==$sname) {
			\skillbase\skill_setvalue(376,'cnt',(int)\skillbase\skill_getvalue(376,'cnt',$pdata) + 1,$pdata);
		}
		return $ret;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 376 && !empty(\skillbase\skill_getvalue(376,'cnt',$pa)) && 48 == $pa['state']){
			$ret += 1;
		}
		return $ret;
	}
}

?>