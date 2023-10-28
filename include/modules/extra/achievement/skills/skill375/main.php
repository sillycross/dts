<?php

namespace skill375
{
	//各级要完成的成就名，如果不存在则取低的
	$ach375_name = array(
		1=>'<:secret:>H4sIAAAAAAAAChWJ2wnAIAwAd3EClTxBOkl+qrEDFLK/8ec47oqFOCwL2PW9bGTBeAsCe97PaxYSSCpuC52YjtrZgnqTMf+nHDHJQcBIAAAA',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach375_desc= array(
		1=>'<:secret:>H4sIAAAAAAAACjWMyRHAMAjEenEFPgBDL3x80QH9hyTjn0aa3aQOpw11Eq7qaIbBk0ideQW3nI965wZRa5HP2K3INKNWK+oyob9vEEay7bulZeHxjN9YegCOngf+dAAAAA==',
	);
	
	$ach375_proc_words = '当前纪录';
	
	$ach375_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach375_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach375_qiegao_prize = array(
		1 => 500,
	);
	
	//各级给的卡片奖励
	$ach375_card_prize = array(
	);
	
	function init() 
	{
		define('MOD_SKILL375_INFO','achievement;secret;');
		define('MOD_SKILL375_ACHIEVEMENT_ID','75');
	}
	
	function acquire375(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost375(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemuse_uga(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($theitem);
		//如果武器名符合条件，进入判定
		eval(import_module('player'));
		if(\achievement_base\achievement_secret_decode('<:secret:>H4sIAAAAAAAAClOKKTWztDCKKTVNSzMFspPMzGJKLSySgWxjA4PUmFJzC2MToKyRoSVYJE0JAOI/jhIyAAAA') == \itemmix\itemmix_name_proc($wep) && \skillbase\skill_query(375)) {
			$checkitem = \wep_b\wep_b_get_ari($wepsk);
			//如果箭矢名也符合，给技能参数+1
			if(\achievement_base\achievement_secret_decode('<:secret:>H4sIAAAAAAAAClOKKTU3SjOMKbVMMjGPKTVJNUkCsg3SUmJKjQ0MUmNKzZLTgOKmqYkQkTQlAEcsLS8yAAAA') == \itemmix\itemmix_name_proc($checkitem['itm'])) {
				\skillbase\skill_setvalue(375,'cnt',(int)\skillbase\skill_getvalue(375,'cnt') + 1);
			}
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 375 && !empty(\skillbase\skill_getvalue(375,'cnt',$pa))){
			$ret += 1;
//			eval(import_module('sys'));
//			if(\sys\is_winner($pa['name'],$winner))
//				$ret += 1;
		}
		return $ret;
	}
}

?>