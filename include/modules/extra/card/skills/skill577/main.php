<?php

namespace skill577
{
	function init() 
	{
		define('MOD_SKILL577_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[577] = '毒奶';
	}
	
	function acquire577(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost577(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$chprocess($pa);
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(577, $pa))
		{
			$apid = $pa['pid'];
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0 AND hp>0 AND pid != '$apid'");
			if($db->num_rows($result))
			{
				$list = array();
				while($r = $db->fetch_array($result)){
					$list[] = $r['pid'];
				}
				$pdlist = array();
				foreach($list as $pdid){
					$pdata = \player\fetch_playerdata_by_pid($pdid);
					\skillbase\skill_acquire(578, $pdata);
					$bless_log = '你感到自己受到了来自一位贤者的祝福！<br>';
					\logger\logsave($pdata['pid'], $now, $bless_log ,'s');			
					\player\player_save($pdata);	
				}
			}
			lost577($pa);
		}
	}
	
}

?>
