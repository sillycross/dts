<?php

namespace achievement_base
{
	function init() {}
	
	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		$result = $db->query("SELECT name,pid FROM {$tablepre}players WHERE type=0");
		while ($udata=$db->fetch_array($result))
		{
			$edata=\player\fetch_playerdata_by_pid($udata['pid']);
			if ($edata===NULL) continue;
			$res = $db->query("SELECT n_achievements FROM {$gtablepre}users WHERE username='{$udata['name']}'");
			if (!$db->num_rows($res)) continue;
			$zz=$db->fetch_array($res); $ach=$zz['n_achievements'];
			$achdata=explode(';',$ach);
			$maxid=count($achdata)-2;
			foreach (\skillbase\get_acquired_skill_array($edata) as $key) 
				if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID'))
					if (strpos(constant('MOD_SKILL'.$key.'_INFO'),'achievement;')!==false)
					{
						$id=((int)(constant('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')));
						if ($id>$maxid) $maxid=$id;
						if (isset($achdata[$id])) $s=((string)$achdata[$id]); else $s='';
						$func='\\skill'.$key.'\\finalize'.$key;
						$achdata[$id]=$func($edata,$s);
					}
			
			$nachdata='';
			for ($i=0; $i<=$maxid; $i++)
				$nachdata.=$achdata[$i].';';
			
			$db->query("UPDATE {$gtablepre}users SET n_achievements = '$nachdata' WHERE username='{$udata['name']}'");	
		}
		
		$chprocess();
	}
	
	function show_achievements()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		$res = $db->query("SELECT n_achievements FROM {$gtablepre}users WHERE username='$cuser'");
		if (!$db->num_rows($res)) return;
		$zz=$db->fetch_array($res); $ach=$zz['n_achievements']; 
		$achdata=explode(';',$ach); 
		for ($key=1; $key<=1000; $key++)
			if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID'))
				if (strpos(constant('MOD_SKILL'.$key.'_INFO'),'achievement;')!==false)
				{
					$id=((int)(constant('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')));
					if (isset($achdata[$id])) $s=((string)$achdata[$id]); else $s='';
					$func='\\skill'.$key.'\\show_achievement'.$key;
					echo '成就编号'.$id.' 技能编号'.$key.' 成就信息<br><br>';
					$func($s);
					echo '<br><br>';
				}
	}		
}

?>
