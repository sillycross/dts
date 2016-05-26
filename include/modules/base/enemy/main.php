<?php

namespace enemy
{
	function init() {}
	
	function findenemy($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','metman'));
		
		\player\update_sdata();
		
		$battle_title = '发现敌人';
		\metman\init_battle();
		$log .= "你发现了敌人<span class=\"red\">{$tdata['name']}</span>！<br>对方好像完全没有注意到你！<br>";
		
		include template(MOD_ENEMY_BATTLECMD);
		$cmd = ob_get_contents();
		ob_clean();

		$main = MOD_METMAN_MEETMAN;
		
		return;
	}
	
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('enemy'));
		return $active_obbs;
	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	//判定主动，判定成功代表可以主动选择是否战斗，失败则被动强制进入战斗
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$active_r = max(calculate_active_obbs($ldata,$edata),1)*calculate_active_obbs_multiplier($ldata,$edata);
		$active_dice = rand(0,99);
		return ($active_dice < $active_r);
	}
	
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','metman','enemy'));
		
		\player\update_sdata();
		$edata=\player\fetch_playerdata_by_pid($sid);
			
		if ($edata['hp']>0)
		{
			extract($edata,EXTR_PREFIX_ALL,'w');
			if (check_enemy_meet_active($sdata,$edata)) {
				$action = 'enemy'.$edata['pid'];
				findenemy($edata);
				return;
			} else {
				battle_wrapper($edata,$sdata,0);
				return;
			}
		}
		else  $chprocess($sid);
	}
	
	function battle_wrapper(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player','logger','metman','input'));
		if($mode == 'combat') 
		{
			if ($command == 'back') 
			{
				$log .= "你逃跑了。";
				$action = '';
				$mode = 'command';
				return;
			}
			
			$enemyid = str_replace('enemy','',$action);
			
			if(!$enemyid || strpos($action,'enemy')===false){
				$log .= "<span class=\"yellow\">你没有遇到敌人，或已经离开战场！</span><br>";
				$action = '';
				$mode = 'command';
				return;
			}
		
			$result = $db->query ( "SELECT * FROM {$tablepre}players WHERE pid='$enemyid'" );
			if (! $db->num_rows ( $result )) {
				$log .= "对方不存在！<br>";
				$action = '';
				$mode = 'command';
				return;
			}
		
			$edata=\player\fetch_playerdata_by_pid($enemyid);
			extract($edata,EXTR_PREFIX_ALL,'w');
			
			if ($edata ['pls'] != $pls) {
				$log .= "<span class=\"yellow\">" . $edata ['name'] . "</span>已经离开了<span class=\"yellow\">$plsinfo[$pls]</span>。<br>";
				$action = '';
				$mode = 'command';
				return;
			} elseif ($edata ['hp'] <= 0) {
				$log .= "<span class=\"red\">" . $edata ['name'] . "</span>已经死亡，不能被攻击。<br>";
				if(\corpse\check_corpse_discover($edata))
				{
					$action = 'corpse'.$edata['pid'];
					\corpse\findcorpse ( $edata );
				}
				return;
			}
			
			\player\update_sdata();
			$ldata=$sdata;
			battle_wrapper($ldata,$edata,1);
			return;
		}
		$chprocess();
	}
}

?>
