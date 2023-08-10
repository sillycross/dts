<?php

namespace addnpc_event
{
	function init() {}
	
	//115入场专用判断
	//杀死NPC时，如果NPC死亡数超过连斗死亡数，开始判定场上是否没有活着的NPC了
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		eval(import_module('sys','gameflow_combo'));
		if ( in_array($gametype, array(0, 4, 6, 18)) && $pd['type'] && $pd['hp'] <= 0 && $deathnum > $combonum)
		{
			$pdpid = $pd['pid'];
			//条件1：没有其他存活NPC
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pid != '{$pdpid}' AND type > 0 AND hp > 0");
			$npcnum = $db->num_rows($result);
			if(!$npcnum){
				//条件2：115只入场1次
				$result2 = $db->query("SELECT pid FROM {$tablepre}players WHERE type = '42'");
				if(!$db->num_rows($result2))
					addnpc_event(42);
			}
		}
	}
	
	function addnpc_event($ntype, $nsub=0, $num=1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ids = \addnpc\addnpc($ntype,$nsub,$num,1);
		$id = reset($ids);
		eval(import_module('sys','player'));
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$id'");
		$edata = $db->fetch_array($result);
		$chatlog = \npcchat\npcchat($sdata, $edata, 1, 'addnpc', 0);
		\sys\addchat(6, $chatlog, $edata['name']);
	}
}
?>