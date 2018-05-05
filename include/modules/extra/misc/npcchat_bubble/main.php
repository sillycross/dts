<?php

namespace npcchat_bubble
{
	$npcchat_bubble_on = 0;
	$npcchat_bubble_replace_log = 0;
	
	function init() {}
	
	function npcchat_print($printlog)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','npcchat_bubble'));
		if($npcchat_bubble_on) {
			if(!isset($uip['npcchat']['enemy'])) $uip['npcchat']['enemy']=array();
			$uip['npcchat']['enemy'][] = $printlog;
		}		
		if(!$npcchat_bubble_on || !$npcchat_bubble_replace_log) {
			$chprocess($printlog);
		}
	}
	
	function npcchat_tag_process(&$pa, &$pd, $active, $situation, $npc_active, $nchat){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $situation, $npc_active, $nchat);
		if ($situation == 'evolve') //复活特殊演出
		{
			$sid = 14;
			$chattag = 'evolve';
			$ret = array($chattag, $sid);
		}
		return $ret;
	}
	
	function npcchat_get_chatlog($chattag,$sid,$nchat){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($chattag,$sid,$nchat);
		if('evolve' == $chattag && NULL !== $ret){
			eval(import_module('sys','npcchat_bubble'));
			if(!isset($uip['npcchat']['enemy'])) $uip['npcchat']['enemy']=array();
			foreach($nchat[$chattag] as $cv){
				$uip['npcchat']['enemy'][] = \npcchat\npcchat_decorate(npcchat_bubble_cleanqm($cv), $nchat);
			}
			$ret = NULL;//不输出标准的npc对白
		}
		return $ret;
	}
	
	function npcchat_bubble_cleanqm($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		preg_match('/^“(.*)”$/s', $str, $matches);
		$ret = $str;
		if($matches) $ret = $matches[1];
		return $ret;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ($pd['type'] && !empty($pd['npc_evolved'])) \npcchat\npcchat($pa, $pd, $active, 'evolve');
	}
	
	function npcchat_bubble_show($tp='enemy')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!isset($uip['npcchat'][$tp])) return '';
		$bubblecont = $uip['npcchat'][$tp];
		ob_start();
		include template(MOD_NPCCHAT_BUBBLE_BUBBLEPAGE);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}

?>