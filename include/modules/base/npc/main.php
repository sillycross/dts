<?php

namespace npc
{
	function init() 
	{
		eval(import_module('player'));
		
		global $npc_typeinfo;
		$typeinfo+=$npc_typeinfo;
		
		global $npc_killmsginfo;
		$killmsginfo+=$npc_killmsginfo;
		
		global $npc_lwinfo;
		$lwinfo+=$npc_lwinfo;
	}
	
	function check_initnpcadd($typ)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return 1;
	}
	
	function rs_game($xmode = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		
		$chprocess($xmode);
		
		eval(import_module('sys','map','player','npc','lvlctl','skillbase'));
		if ($xmode & 8) {
			//echo " - NPC初始化 - ";
			$db->query("DELETE FROM {$tablepre}players WHERE type>0 ");
			//$typenum = sizeof($typeinfo);
			$plsnum = sizeof($plsinfo);
			$npcqry = '';
			$ninfo = get_npclist();
			foreach ($ninfo as $i => $npcs){
				if(!empty($npcs)) {
					if (!check_initnpcadd($i)) continue;
					$subnum = sizeof($npcs['sub']);
					$jarr = $jarr0 = array_keys($npcs['sub']);
					shuffle($jarr);
					if (!$subnum || !$npcs['num']) $jarr=array();
					elseif ($subnum > $npcs['num']) $jarr=array_slice($jarr,0,$npcs['num']);
					elseif ($subnum < $npcs['num']) {
						while(sizeof($jarr) < $npcs['num']) {
							$jarr = array_merge($jarr,$jarr0);
						}
					}
					sort($jarr);
					foreach($jarr as $j){
						$npc = array_merge($npcinit,$npcs);
						$npc['type'] = $i;
						$npc['endtime'] = $now;
						$npc['sNo'] = $j;
						$npc = array_merge($npc,$npc['sub'][$j]);
						$npc['hp'] = $npc['mhp'];
						$npc['sp'] = $npc['msp'];
						$npc['ss'] = $npc['mss'];
						$npc['exp'] = \lvlctl\calc_upexp($npc['lvl'] - 1);
						//$npc['exp'] = round(2*$npc['lvl']*$baseexp);
						$npc['wp'] = $npc['wk'] = $npc['wg'] = $npc['wc'] = $npc['wd'] = $npc['wf'] = $npc['skill'];
						if($npc['gd'] == 'r'){$npc['gd'] = rand(0,1) ? 'm':'f';}
						$rpls=rand(1,$plsnum-1);
						while ($rpls==34){$rpls=rand(1,$plsnum-1);}
						if($npc['pls'] == 99){$npc['pls'] = $rpls; }
						$npc['state'] = 0;
						$npcqrylit = "(";
						$npcqry = "(";
						foreach ($npc as $key => $value)
						{
							if (in_array($key,$db_player_structure))
							{
								$npcqrylit .= $key.",";
								$npcqry .= "'".$npc[$key]."',";
							}
						}
						$npcqrylit=substr($npcqrylit,0,strlen($npcqrylit)-1).")";
						$npcqry=substr($npcqry,0,strlen($npcqry)-1).")";
						
						$qry = "INSERT INTO {$tablepre}players ".$npcqrylit." VALUES ".$npcqry;
						$db->query($qry);
						unset($qry);
						
						if (isset($npc['skills']) && is_array($npc['skills'])){
							$npc['skills']['460']='0';
							$qry="SELECT * FROM {$tablepre}players WHERE type>'0' ORDER BY pid DESC LIMIT 1";
							$result=$db->query($qry);
							$pr=$db->fetch_array($result);
							$pp=\player\fetch_playerdata_by_pid($pr['pid']);
							foreach ($npc['skills'] as $key=>$value){
								if (defined('MOD_SKILL'.$key)){
									\skillbase\skill_acquire($key,$pp);
									if ($value>0){
										\skillbase\skill_setvalue($key,'lvl',$value,$pp);
									}
								}	
							}
							\player\player_save($pp);
						}
						
						unset($npc);
					}
				}
			}
		}
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','npc'));
		return $npcinfo;
	}
	
	function add_new_killarea($where,$atime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','npc'));
		$plsnum = sizeof($plsinfo) - 1;
		if ($areanum >= sizeof($plsinfo) - 1) return $chprocess($where);
		$query = $db->query("SELECT * FROM {$tablepre}players WHERE pls={$where} AND type>0 AND hp>0");
		while($sub = $db->fetch_array($query)) 
		{
			$pid = $sub['pid'];
			if (!in_array($sub['type'],$killzone_resistant_typelist))
			{
				$pls = $arealist[rand($areanum+1,$plsnum)];
				if ($areanum+1 < $plsnum)
				{
					while ($pls==34) {$pls = $arealist[rand($areanum+1,$plsnum)];}
				}
				$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid=$pid");
			}
		}
		$chprocess($where,$atime);
	}
	
	function get_player_killmsg(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player'));
		if ($pdata['type']>0)
		{
			if (isset($killmsginfo [$pdata['type']]))
				$kilmsg = $killmsginfo [$pdata['type']];
			else  $kilmsg = '';
			return $kilmsg;
		}
		else  return $chprocess($pdata);
	}
	
	function get_player_lastword(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player'));
		if ($pdata['type']>0)
		{
			if (is_array ( $lwinfo [$pdata['type']] )) {
				if (isset($lwinfo [$pdata['type']] [$pdata['name']]))
					$lstwd = $lwinfo [$pdata['type']] [$pdata['name']];
				else  $lstwd = '';
			} else {
				if (isset($lwinfo [$pdata['type']]))
					$lstwd = $lwinfo [$pdata['type']];
				else  $lstwd = '';
			}
			return $lstwd;
		}
		else  return $chprocess($pdata);
	}
	
}

?>
