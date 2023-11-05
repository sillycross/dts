<?php

namespace skill536
{
	function init() 
	{
		define('MOD_SKILL536_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[536] = '僵尸';
	}
	
	function acquire536(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
			\skillbase\skill_setvalue(536,'activated','0',$pa);
	}
	
	function lost536(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(536,'activated',$pa);
	}
	
	function check_unlocked536(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//入场后自动给全局变量里写入忽略本玩家的参数
	//注意暂时没有删去的功能……有必要删去吗？
	function post_load_profile_event(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(536) && empty(\skillbase\skill_getvalue(536,'activated')))
		{
			eval(import_module('sys'));
			//玩家第一次进入游戏的界面是不会player_save的，要正常执行，应该在玩家自动刷新界面时生成
			if(!empty($command)){
				eval(import_module('player'));
				if(empty($gamevars['alive_ignore_pid'])) $gamevars['alive_ignore_pid'] = Array($pid);
				elseif(!in_array($pid, $gamevars['alive_ignore_pid'])) $gamevars['alive_ignore_pid'][] = $pid;
				save_gameinfo();
				\skillbase\skill_setvalue(536,'activated',1);
			}
		}
		$chprocess();
	}
	
	//如果全局变量存在，且还没有连斗，扣减显示用的玩家数
	function get_alivenum_for_display()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		eval(import_module('sys'));
		if(!\gameflow_combo\is_gamestate_combo() && !empty($gamevars['alive_ignore_pid'])) {
			//先判定一下数组是否合法
			$o_alive_ignore_pid = $gamevars['alive_ignore_pid'];
			$gamevars['alive_ignore_pid'] = array_filter(array_unique($gamevars['alive_ignore_pid']));
			
//			foreach($gamevars['alive_ignore_pid'] as $k => $v) {
//				if(empty($v)) {
//					unset($gamevars['alive_ignore_pid'][$k]);
//				}
//			}
			
			if(sizeof($o_alive_ignore_pid) != sizeof($gamevars['alive_ignore_pid'])) {
				save_gameinfo();
			}
			//扣减返回值。先不判定该玩家是否死亡了，一是简化计算，二是我觉得倒扣也挺特色的。
			$ret -= sizeof($gamevars['alive_ignore_pid']);
			//$ret = max($ret, 0);
		}
		return $ret;
	}
	
	//如果全局变量存在，且还没有连斗，特定玩家不会被alive页面显示
	function check_alive_player_displayable($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!\gameflow_combo\is_gamestate_combo() && !empty($gamevars['alive_ignore_pid']) && in_array($pdata['pid'], $gamevars['alive_ignore_pid'])) 
			return false;
		return $chprocess($pdata);
	}
	
	
}
?>