<?php

namespace skill512
{
	function init() 
	{
		define('MOD_SKILL512_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[512] = '幻象';
		//效果：被打死时判定一下本类别同名NPC是否已经全部死亡，如果是，则addnpc
	}
	
	function acquire512(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(512,'type','0',$pa);
		\skillbase\skill_setvalue(512,'sub','0',$pa);
		\skillbase\skill_setvalue(512,'num','1',$pa);
	}
	
	function lost512(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked512(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (!$pd['hp'] && \skillbase\skill_query(512,$pd))
		{
			eval(import_module('sys','logger'));
			$result = $db->query("SELECT hp FROM {$tablepre}players WHERE name='{$pd['name']}' AND type='{$pd['type']}' AND pid!='{$pd['pid']}'");
			$flag = 1;
			while($r = $db->fetch_array($result)){
				if($r['hp']) {
					$flag = 0;
					break;
				}
			}
			if($flag) {
				$atype = \skillbase\skill_getvalue(512,'type',$pd);
				$asub = \skillbase\skill_getvalue(512,'sub',$pd);
				$anum = \skillbase\skill_getvalue(512,'num',$pd);
				if($atype && $anum) {
					$log .= '<span class="yellow b">'.$pd['name'].'死去了，但你发现那只是一个分身，而'.('m'==$pd['gd'] ? '他' : '她').'的本体刚刚苏醒……</span><br>';
					\addnpc\addnpc($atype,$asub,$anum,1);
				}
			}
		}
	}
}

?>