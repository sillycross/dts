<?php

namespace skill583
{
	$sk583_mixinfo = array
	(
		array('class' => 'card', 'stuff' => array('空白符卡','★瓦衣山彐★'),'result' => array('「自家割草」','WF',75,'∞','c')),
		array('class' => 'card', 'stuff' => array('★瓦衣山彐★','★瓦衣山彐★'),'result' => array('★瓶装幽灵★','X',1,1,)),
	);
	
	function init() 
	{
		define('MOD_SKILL583_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[583] = '半人';
	}
	
	function acquire583(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(583,'activated','0',$pa);
	}
	
	function lost583(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(583,'activated',$pa);
	}
	
	function check_unlocked583(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//入场后自动给全局变量里写入本玩家人数为0.5的参数
	function post_load_profile_event(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(583) && empty(\skillbase\skill_getvalue(583,'activated')))
		{
			eval(import_module('sys'));
			//玩家第一次进入游戏的界面是不会player_save的，要正常执行，应该在玩家自动刷新界面时生成
			if(!empty($command)){
				eval(import_module('player'));
				if(empty($gamevars['alive_half_pid'])) $gamevars['alive_half_pid'] = Array($pid);
				elseif(!in_array($pid, $gamevars['alive_half_pid'])) $gamevars['alive_half_pid'][] = $pid;
				save_gameinfo();
				\skillbase\skill_setvalue(583,'activated',1);
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
		if(!\gameflow_combo\is_gamestate_combo() && !empty($gamevars['alive_half_pid'])) {
			//先判定一下数组是否合法
			$o_alive_half_pid = $gamevars['alive_half_pid'];
			$gamevars['alive_half_pid'] = array_filter(array_unique($gamevars['alive_half_pid']));
			
			//把尸体多扣的半个人补回来
			//现在不补了，半个人死了扣一个人的量，很特色吧！
			//主要是因为以下的写法会造成诡异的问题，但要改正的边际效益又很小
//			foreach($gamevars['alive_half_pid'] as $k => $v)
//			{
//				$pdata = \player\fetch_playerdata_by_pid($v);
//				if ($pdata['player_dead_flag'])
//				{
//					$ret += 0.5;
//					unset($gamevars['alive_ignore_pid'][$k]);
//				}
//			}
	
			if(sizeof($o_alive_half_pid) != sizeof($gamevars['alive_half_pid']))
			{
				save_gameinfo();
			}	
			//扣减返回值
			$ret -= 0.5 * sizeof($gamevars['alive_half_pid']);
		}
		return $ret;
	}
	
	function get_mixinfo()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		if (\skillbase\skill_query(583) && check_unlocked583())
		{
			eval(import_module('skill583'));
			$ret = array_merge($ret, $sk583_mixinfo);
		}
		return $ret;
	}
	
}

?>