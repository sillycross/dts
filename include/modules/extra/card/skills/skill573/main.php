<?php

namespace skill573
{
	function init() 
	{
		define('MOD_SKILL573_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[573] = '毒奶';
	}
	
	function acquire573(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost573(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$chprocess($pa);
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(573, $pa))
		{
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0 AND hp>0 AND pid != {$pa['pid']}");
			if($db->num_rows($result))
			{
				$list = array();
				while($r = $db->fetch_array($result)){
					$list[] = $r['pid'];
				}
				$pdlist = array();
				foreach($list as $pdid){
					$pdata = \player\fetch_playerdata_by_pid($pdid);
					\skillbase\skill_acquire(574, $pdata);
					$bless_log = '你感到自己受到了来自一位贤者的祝福！<br>';
					\logger\logsave($pdata['pid'], $now, $bless_log ,'o');			
					\player\player_save($pdata);	
				}
			}
			lost573($pa);
		}
	}
	
}

?>
