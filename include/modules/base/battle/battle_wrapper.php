<?php

namespace battle
{
	//注意，各种攻击函数的$active都是相对于玩家而言的，$active=1代表$pa（攻击者）是玩家
	
	//保存敌人的战斗log
	function save_enemy_battlelog(&$pl)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger'));
		if(!$pl['type']){
			if(isset($pl['battle_msg'])) {
				$pl['battlelog'] = $pl['battle_msg'].$pl['battlelog'];
			}
			if(!empty($pl['battlelog'])) \logger\logsave ( $pl['pid'], $now, $pl['battlelog'] ,'b');
		}
	}
	
	function send_battle_msg(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$message = get_var_in_module('message', 'input');
		if(!empty($message)){
			eval(import_module('logger'));
			$log .= "<span class=\"lime b\">你向{$pd['name']}喊道：“{$message}”</span><br>";
			$pd['battle_msg'] = "<span class=\"lime b\">{$pa['name']}向你喊道：“{$message}”</span><br><br>";
			\sys\addchat(6, "{$pa['name']}高喊着“{$message}”杀向了{$pd['name']}");
		}
	}
	
	function battle_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//判定是否发送战斗叫喊（？）
		send_battle_msg($pa, $pd, $active);
		//互相保存对方id。这个字段在相当长的一段时间里莫名其妙地失去了作用
		$pa['bid'] = $pd['pid']; $pd['bid'] = $pa['pid'];
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
		
		if (substr($action,0,6)=='corpse' && (int)substr($action,6) == $edata['pid'])
		{
			\corpse\findcorpse($edata);
		}
		else
		{
			include template(get_battleresult_filename());
			$cmd = ob_get_contents();
			ob_clean();
		}
		
		if (defined('MOD_CLUBBASE')) include template(MOD_CLUBBASE_NPCSKILLPAGE);
	}
	
	function get_battleresult_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman'));
		return MOD_BATTLE_BATTLERESULT;
	}
}

?>