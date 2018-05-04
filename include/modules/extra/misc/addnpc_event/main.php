<?php

namespace addnpc_event
{
	function init() {}
	
	//杀死NPC时，如果NPC死亡数超过连斗死亡数，开始判定场上是否没有活着的NPC了
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		eval(import_module('sys','gameflow_combo'));
		if ( $pd['type'] && $pd['hp'] <= 0 && $deathnum > $combonum)
		{
			$pdpid = $pd['pid'];
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pid != '{$pdpid}' AND type > 0 AND hp > 0");
			if(!$db->num_rows($result)){
				addnpc_event(42);
			}
		}
	}
	
	function addnpc_event($ntype, $nsub=0, $num=1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\addnpc\addnpc($ntype,$nsub,$num,1);
	}
}
?>