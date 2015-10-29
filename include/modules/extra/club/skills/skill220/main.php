<?php

namespace skill220
{
	function init() 
	{
		define('MOD_SKILL220_INFO','club;active;hidden;');
	}
	
	function acquire220(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost220(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}

	function pcheck($itmn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		if ( $itmn < 1 || $itmn > 6 ) {
			$log .= '此道具不存在，请重新选择。';
			$mode = 'command';
			return;
		}
		global ${'itm'.$itmn},${'itmk'.$itmn},${'itme'.$itmn},${'itms'.$itmn},${'itmsk'.$itmn};
		$itm = & ${'itm'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
	
		if(!$itms) {
			$log .= '此道具不存在，请重新选择。<br>';
			$mode = 'command';
			return;
		}	
	
		if(strpos($itmk,'P') === 0) {
			$log .= '<span class="red">'.$itm.'有毒！</span>';
		} else {
			$log .= '<span class="yellow">'.$itm.'是安全的。</span>';
		}
		$mode = 'command';
		return;
	}
	
	function do_pcheck()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','input'));
		if (!\skillbase\skill_query(220)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		if(strpos($skillpara1,'chkp') === 0) {
			$itmn = substr($skillpara1,4,1);
			pcheck($itmn);
		}
		include template(MOD_SKILL220_POISONCHECK);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','input'));
	
		if ($mode == 'special' && $command == 'skill220_special' && $subcmd=='pcheck') 
		{
			do_pcheck();
			return;
		}
			
		$chprocess();
	}
	
}

?>
