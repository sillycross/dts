<?php

namespace supplycrate_event
{
	function init()
	{
		global $crate_npc;
		eval(import_module('player','addnpc','npc'));
		$typeinfo[26] = '幻境速递';
		$npcinfo[26] = $crate_npc;
		$anpcinfo[26] = $crate_npc;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd);
		eval(import_module('sys'));
		if (in_array($gametype, array(18)))
		{
			if(($pd['type'] == 1) && ($pd['hp'] <= 0))
			{
				$gamevars['crimson_dead'] = 1;
				save_gameinfo();
			}
		}
		return $ret;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		eval(import_module('sys'));
		if (in_array($gametype, array(18)) && (rand(0,999) < 3))
		{
			if (!isset($gamevars['crimson_dead']))
			{
				$chatlog = "看来有热心观众投递了一份场外支援。";
				\sys\addchat(6, $chatlog, '红暮');
			}
			\addnpc\addnpc(26,0,1,1);
		}
	}
	
}
?>