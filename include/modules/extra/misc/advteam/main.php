<?php

namespace advteam
{
	function init() {}
	
	function get_advteam_html()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map'));
		if (!in_array($gametype,$teamwin_mode) || !$teamID) return;
		$result = $db->query("SELECT pid,mhp,hp,pls,name,flare FROM {$tablepre}players WHERE teamID='$teamID' AND pid<>'$pid'");
		$teammate_num = $db->num_rows($result);
		$i=0; 
		while($data = $db->fetch_array($result)) 
		{
			$i++; $teammateinfo[$i]=$data; $teammateinfo[$i]['dummy']=0;
		}
		include template('MOD_ADVTEAM_TEAM');
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		if ($mode == "advteamspecial") 
		{
			if (!in_array($gametype,$teamwin_mode)) 
			{ 
				$log.="此命令在本游戏模式下不可用。<br>"; 
				$mode = 'command';
			}
			else
			{
				if ($command=="sendflare")
				{
					$log.="发出支援请求成功。<br>";
					$flare=1;
					$mode='command';
				}
				else  if ($command=="stopflare")
				{
					$log.="取消支援请求成功。<br>";
					$flare=0;
					$mode='command';
				}
				else  if (strpos($command,'findteam') === 0)
				{
					$which=(int)substr($command,8);
					$edata = \player\fetch_playerdata_by_pid($which);
					if ($edata === NULL)
					{
						$log.="队友不存在。<br>";
						$mode='command';
					}
					else
					{
						if (!$teamID || $edata['teamID']!=$teamID)
						{
							$log.="对方不是你的队友！<br>";
							$mode='command';
						}
						else  if ($edata['hp']<=0)
						{
							$log.="对方已经死亡！<br>";
							$mode=='command';
						}
						else  if ($edata['pls']!=$pls)
						{
							$log.="对方与你不在同一个地图！<br>";
							$mode=='command';
						}
						else
						{
							\team\findteam($edata);
						}	
					}
				}
			}
			return;
		}
		$chprocess();
	}
}

?>
