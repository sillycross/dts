<?php

namespace skill720
{
	function init() 
	{
		define('MOD_SKILL720_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[720] = '鸡汤';
	}
	
	function acquire720(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost720(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function enter_battlefield_cardproc($ebp, $card)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ebp_temp = $ebp;
		$ret = $chprocess($ebp, $card);
		if ($card == 381) return skill720_proc($ret, $ebp_temp);
		return $ret;
	}
	
	function skill720_proc($proc, $ebp_temp)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ebp_temp['card'] = $proc[0]['card'];
		$ebp_temp['cardname'] = $proc[0]['cardname'];
		$proc[0] = $ebp_temp;
		$proc[0]['o_card'] = 381;
		$proc[0]['wep'] = '手榴弹';$proc[0]['wepk'] = 'WC';$proc[0]['wepe'] = '40';$proc[0]['weps'] = '1';$proc[0]['wepsk'] = '';
		$proc[0]['art'] = '毒物说明书';$proc[0]['artk'] = 'A';$proc[0]['arte'] = '1';$proc[0]['arts'] = '1';$proc[0]['artsk'] = '';
		$proc[1] = array();
		return $proc;
	}
	
}

?>