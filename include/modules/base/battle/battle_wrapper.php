<?php

namespace battle
{
	//注意，各种攻击函数的$active都是相对于玩家而言的，$active=1代表$pa（攻击者）是玩家
	
	//保存敌人的战斗log
	function save_enemy_battlelog(&$pl)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger'));
		
		\logger\logsave ( $pl['pid'], $now, $pl['battlelog'] ,'b');
	}
	
	function battle_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function battle_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function battle(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		assault_prepare($pa,$pd,$active);
		assault($pa,$pd,$active);
		assault_finish($pa,$pd,$active);
	} 
	
	function battle_wrapper(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		battle_prepare($pa, $pd, $active);
		battle($pa, $pd, $active);
		battle_finish($pa, $pd, $active);
		
		//写回数据库
		eval(import_module('sys','logger','player','metman'));
		
		if ($active) 
		{ 
			if ($pd['hp']<=0 && $pa['hp']>0)
			{
				$pa['action']='corpse'.$pd['pid'];
			}
			if ($pa['hp']<=0 && $pd['hp']>0 && $pd['action']=='' && $pd['type']==0)
			{
				$pd['action'] = 'pacorpse'.$pa['pid']; 
			}
		}
		else
		{
			if ($pd['hp']<=0 && $pa['hp']>0 && $pa['action']=='' && $pa['type']==0)
			{
				$pa['action']='pacorpse'.$pd['pid'];
			}
			if ($pa['hp']<=0 && $pd['hp']>0)
			{
				$pd['action'] = 'corpse'.$pa['pid']; 
			}
		}
		
		if ($active)
		{
			$edata=$pd;
			\player\player_save($pa); \player\player_save($pd);
			\metman\metman_load_playerdata($pd);
			if ($pd['type']==0) save_enemy_battlelog($pd);
			\player\load_playerdata($pa);
		}
		else
		{
			$edata=$pa;
			\player\player_save($pa); \player\player_save($pd);
			\metman\metman_load_playerdata($pa);
			if ($pa['type']==0) save_enemy_battlelog($pa);
			\player\load_playerdata($pd);
		}
		
		$battle_title = '战斗发生';
		$main = MOD_METMAN_MEETMAN;
		\metman\init_battle(1);
		
		if (substr($action,0,6)=='corpse')
		{
			\corpse\findcorpse($edata);
		}
		else
		{
			include template(MOD_BATTLE_BATTLERESULT);
			$cmd = ob_get_contents();
			ob_clean();
			$action = '';
		}
	}
}

?>
