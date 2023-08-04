<?php

//记录一些杂项数据的技能
//反正技能参数兼容性比较强，就不考虑耦合问题了
//目前记录数据有：

//本局得到过的金钱数 money_got
//本局累计获得的切糕 qiegao_got
//被DN的理由 dndeath
//上一次击杀的角色名 last_kill_name
//上一次击杀的角色类型 last_kill_type
//上一次击杀的先制情况 last_kill_active
//上一次击杀的方式 last_kill_method
//击杀过的重要NPC killed_vip
//攻击过的重要NPC attacked_vip
//本局实际使用的卡片 actual_card 位于valid.func.php
//上一次唱过的歌 songkind 位于base/items/song
//上一次唱到的地方 songpos 位于base/items/song
//是否已跳过开局剧情 opening_skip 位于base/opening
//上一次拾取尸体道具的毫秒数 last_corpse_time 位于extra/searchmemory
//上一次拾取尸体的pid last_corpse_pid 位于extra/searchmemory

namespace skill1003
{
	$skill1003_o_money = 0;
	
	function init() 
	{
		define('MOD_SKILL1003_INFO','hidden;');
	}
	
	function acquire1003(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1003,'money_got',0,$pa);
		\skillbase\skill_setvalue(1003,'qiegao_got',0,$pa);
		\skillbase\skill_setvalue(1003,'killed_vip','',$pa);
		\skillbase\skill_setvalue(1003,'attacked_vip','',$pa);
	}
	
	function lost1003(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function pre_act(){//每次行动记录得到的金钱
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skill1003'));
		if(\skillbase\skill_query(1003)){
			$skill1003_o_money = $money;
		}
		$chprocess();
	}
	
	function post_act(){//每次行动记录得到的金钱
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skill1003'));
		$chprocess();
		if(\skillbase\skill_query(1003) && $money > $skill1003_o_money){
			
			$money_got = $money - $skill1003_o_money;
			$money_got += \skillbase\skill_getvalue(1003,'money_got');	
			\skillbase\skill_setvalue(1003,'money_got',$money_got);	
		}
	}
	
	//战斗获得切糕改为先暂存在这个技能里，战斗结束才结算
	function battle_get_qiegao_update($qiegaogain,&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(1003, $pa)){
			$nowqiegao = \skillbase\skill_getvalue(1003,'qiegao_got', $pa);	
			\skillbase\skill_setvalue(1003,'qiegao_got',$nowqiegao + $qiegaogain, $pa);
		}else{
			$chprocess($qiegaogain,$pa);
		}
	}
	
	//战斗结束时的结算
	function gameover_get_gold_up($data, $winner = '',$winmode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($data, $winner, $winmode);
		$tmp_data = $data;
		list($tmp_data['acquired_list'], $tmp_data['parameter_list']) = \skillbase\skillbase_load_process($data['nskill'], $data['nskillpara']);
		if(\skillbase\skill_query(1003, $tmp_data)) {
			$ret += \skillbase\skill_getvalue(1003,'qiegao_got', $tmp_data);	
		}
		return $ret;
	}
	
	//被DN时记录理由
	function deathnote_process_core(&$pa, &$pd, $dndeath=''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(1003, $pd)) {
			\skillbase\skill_setvalue(1003,'dndeath', $dndeath, $pd);
		}
		$chprocess($pa, $pd, $dndeath);
	}
	
	//顺带着给个DN死亡提示好了
	function get_dinfo(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa);
		if(28 == $pa['state'] && \skillbase\skill_query(1003, $pa)) {
			$dndeath = \skillbase\skill_getvalue(1003,'dndeath', $pa);
			$ret = '你因为<span class="yellow b">'.$dndeath.'</span>意外死去了。<br>';
		}
		return $ret;
	}
	
	//记录攻击过的重要NPC
	function player_damaged_enemy(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(1003,$pa))
		{
			if(in_array($pd['type'], array(1, 4, 5, 6, 7, 9, 12, 14, 15, 42, 88))){
				$attacked_vip = \skillbase\skill_getvalue(1003,'attacked_vip', $pa);
				$attacked_vip = explode(',',$attacked_vip);
				$attacked_vip = array_filter($attacked_vip);
				if(!in_array($pd['type'], $attacked_vip)) {
					$attacked_vip[] = $pd['type'];
					$attacked_vip = implode(',',$attacked_vip);
					\skillbase\skill_setvalue(1003,'attacked_vip', $attacked_vip, $pa);
				}
			}
		}
	}
	
	//记录上一个战斗击杀的角色，以及击杀过的重要角色
	function record_last_kill(&$pa,&$pd,$active,$method='')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ( \skillbase\skill_query(1003,$pa)){
			\skillbase\skill_setvalue(1003,'last_kill_name',$pd['name'],$pa);
			\skillbase\skill_setvalue(1003,'last_kill_type',$pd['type'],$pa);
			\skillbase\skill_setvalue(1003,'last_kill_active',$active,$pa);
			if(empty($method)) $method = $pd['state'];
			\skillbase\skill_setvalue(1003,'last_kill_method',$method,$pa);
			if(in_array($pd['type'], array(1, 4, 5, 6, 7, 9, 12, 14, 15, 42, 88))){
				$killed_vip = \skillbase\skill_getvalue(1003,'killed_vip', $pa);
				$killed_vip = explode(',',$killed_vip);
				$killed_vip = array_filter($killed_vip);
				if(!in_array($pd['type'], $killed_vip)) {
					$killed_vip[] = $pd['type'];
					$killed_vip = implode(',',$killed_vip);
					\skillbase\skill_setvalue(1003,'killed_vip', $killed_vip, $pa);
				}
			}
		}
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(1003,$pa))
		{
			record_last_kill($pa,$pd,$active);
		}
	}	
	
	function trap_hit()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','trap'));
		$skill1003_itmsk0 = $itmsk0;
		$chprocess();	
		if(!$selflag && $playerflag && $hp<=0) {
			$edata = \player\fetch_playerdata_by_pid($skill1003_itmsk0);
			if ( \skillbase\skill_query(1003,$edata))
			{
				record_last_kill($edata,$sdata,1);
				\player\player_save($edata);
			}
		}
	}
	
	//所有模式入场都会获得skill1003
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_acquire(1003,$pa);
		return $chprocess($pa);
	}
}

?>