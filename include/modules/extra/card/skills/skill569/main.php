<?php

namespace skill569
{
	function init() 
	{
		define('MOD_SKILL569_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[569] = '解放';
	}
	
	function acquire569(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost569(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$chprocess($pa);
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(569, $pa))
		{
			if (5 < $validnum)
			{
				eval(import_module('sys'));
				$apid = $pa['pid'];
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0 AND hp>0 AND pid != '$apid'");
				if($db->num_rows($result))
				{
					$list = array();
					while($r = $db->fetch_array($result)){
						$list[] = $r['pid'];
					}
					foreach($list as $pdid){
						$pdata = \player\fetch_playerdata_by_pid($pdid);
						$pdata['pls'] = 9;
						$mvpls_log = '在你毫无察觉的时候，你的深层无意识似乎被解放了！<br>……<br>等你清醒过来的时候，你发现自己身处于墓地。<br>这是怎么回事呢？<br>';
						\logger\logsave($pdata['pid'], $now, $mvpls_log ,'s');
						\player\player_save($pdata);
					}
					addnews($now, 'signal_569', $pa['name']);
				}
			}	
			lost569($pa);
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'signal_569')
		{
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">随着".$a."的进场，其他玩家都被送去了墓地！大家好像都被解放了！</span></li>";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
