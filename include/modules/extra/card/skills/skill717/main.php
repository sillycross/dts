<?php

namespace skill717
{
	function init() 
	{
		define('MOD_SKILL717_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[717] = '随缘';
	}
	
	function acquire717(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost717(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked717(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//你移动时会随机选择一个目标地点
	function move_to_area($moveto)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(717))
		{
			eval(import_module('sys','map','player','logger'));
			if ($hack) $pls = rand(0,sizeof($plsinfo)-1);
			else
			{
				$pls = rand($areanum+1,sizeof($plsinfo)-1);
				$pls=$arealist[$pls];
			}
			$log .= "你随缘走到了<span class=\"yellow b\">$plsinfo[$pls]</span>！<br>";
		}
		return $chprocess($moveto);
	}
	
	//获得物品时会变为获得一个随机商店或地图道具
	function itemget_process()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(717) && ($itms0 >= 0))
		{
			eval(import_module('sys'));
			$itm_temp = $itm0;
			if (rand(0,99) < 90) //抽一个地图道具
			{
				$rand_pls = rand(0,33);
				$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE pls = '$rand_pls'");
				$itemnum = $db->num_rows($result);
				if($itemnum > 0)
				{
					//原道具与另一个道具交换位置
					$db->query("INSERT INTO {$tablepre}mapitem (itm, itmk, itme, itms, itmsk ,pls) VALUES ('$itm0', '$itmk0', '$itme0', '$itms0', '$itmsk0', '$rand_pls')");
					$mipool = array();
					while($r = $db->fetch_array($result)){
						$mipool[] = $r;
					}
					$mi = array_randompick($mipool);
					$iid = $mi['iid'];
					$db->query("DELETE FROM {$tablepre}mapitem WHERE iid='$iid'");
					$itm0 = $mi['itm'];
					$itmk0 = $mi['itmk'];
					$itme0 = $mi['itme'];
					$itms0 = $mi['itms'];
					$itmsk0 = $mi['itmsk'];
				}
			}
			else //抽一个商店道具
			{
				eval(import_module('map'));
				$an = $areanum / $areaadd;
				if (rand(0,33) < 33) $result = $db->query("SELECT * FROM {$tablepre}shopitem WHERE num>0 AND area<='$an' AND price<=1500");
				else $result = $db->query("SELECT * FROM {$tablepre}shopitem WHERE num>0 AND area<='$an' AND price>1500");				
				$itemnum = $db->num_rows($result);
				//不会真有人把商店买空吧！
				if($itemnum > 0)
				{
					//原道具留在原地
					$db->query("INSERT INTO {$tablepre}mapitem (itm, itmk, itme, itms, itmsk ,pls) VALUES ('$itm0', '$itmk0', '$itme0', '$itms0', '$itmsk0', '$pls')");
					$sipool = array();
					while($r = $db->fetch_array($result))
					{
						$sipool[] = $r;
					}
					$shopiteminfo = array_randompick($sipool);
					$inum = $shopiteminfo['num']-1;
					$sid = $shopiteminfo['sid'];
					$db->query("UPDATE {$tablepre}shopitem SET num = '$inum' WHERE sid = '$sid'");
					$itm0 = $shopiteminfo['item'];
					$itmk0 = $shopiteminfo['itmk'];
					$itme0 = $shopiteminfo['itme'];
					$itms0 = $shopiteminfo['itms'];
					$itmsk0 = $shopiteminfo['itmsk'];
				}
			}
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">$itm_temp</span>变成了<span class=\"yellow b\">$itm0</span>。<br>";
		}
		$chprocess();
	}
	
	//遭遇NPC时会变为遭遇随机地点的一个NPC
	function discover_player()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		eval(import_module('player'));
		if (\skillbase\skill_query(717,$sdata))
		{
			$pls_available = \map\get_safe_plslist();
			//英灵殿，特殊待遇
			if (!in_array('34',$pls_available)) $pls_available[] = '34';
			$pls_temp = $pls;
			$pls = array_randompick($pls_available);
			\skillbase\skill_setvalue(717,'pls',$pls,$sdata);
		}
		$ret = $chprocess();
		if (\skillbase\skill_query(717,$sdata) && !empty($pls_temp))
		{
			$pls = $pls_temp;
		}
		return $ret;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(717,$sdata) && ($mode == 'combat')) 
		{
			$pls_temp = \skillbase\skill_getvalue(717,'pls',$sdata);
			if (!empty($pls_temp)) swap($pls, $pls_temp);
		}
		$chprocess();
		if (\skillbase\skill_query(717,$sdata) && !empty($pls_temp))
		{
			$pls = $pls_temp;
		}
	}
	
	function getcorpse($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(717,$sdata)) 
		{
			$pls_temp = \skillbase\skill_getvalue(717,'pls',$sdata);
			if (!empty($pls_temp)) swap($pls, $pls_temp);
		}
		$chprocess($item);
		if (\skillbase\skill_query(717,$sdata) && !empty($pls_temp))
		{
			$pls = $pls_temp;
		}
	}

}

?>