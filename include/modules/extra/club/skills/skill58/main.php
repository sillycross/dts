<?php

namespace skill58
{
	function init() 
	{
		define('MOD_SKILL58_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[58] = '复活';
		$clubdesc_h[24] = $clubdesc_a[24] = '你被战斗/陷阱杀死时会立即复活。1局游戏只能复活1次。';
	}
	
	function acquire58(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//复活标记
		\skillbase\skill_setvalue(58,'r','0',$pa);
	}

	function lost58(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(58,'r',$pa);
	}
	
	function check_unlocked58(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(\skillbase\skill_query(58,$pd) && check_unlocked58($pd)){
			$pd['revive_sequence'][200] = 'skill58';
		}
		return;
	}	

	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill58' == $rkey && in_array($pd['state'],Array(20,21,22,23,24,25,27,29,39,40,41))){
			if((int)\skillbase\skill_getvalue(58,'r',$pd) == 0)
			$ret = true;
		}
		return $ret;
	}
	
	//回满血，发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill58' == $rkey){
			$pd['hp']=$pd['mhp'];
			$pd['skill58_flag']=1;
			\skillbase\skill_setvalue(58,'r','1',$pd);
			$pd['rivival_news'] = array('revival', $pd['name']);
			//addnews ( 0, 'revival', $pd['name']);
			//满血复活时加成效果（这个其实是技能“新生”的内容，但直接做在一起好了）
			$pd['mhp']+=$pd['lvl']*2; 
			$pd['hp']+=$pd['lvl']*2;
			$pd['def']+=$pd['lvl']*5;
		}
		return;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('sys','logger','trap'));
		
		if(!empty($pd['skill58_flag'])){
			if ($pd['o_state']==27)	//陷阱
			{
				$log.= "<span class=\"lime b\">但是，由于你及时按下了BOMB键，你原地满血复活了！</span><br>";
				if(!$pd['sourceless'] && !$selflag){//自杀不提示
					$w_log = "<span class=\"lime b\">但是，由于{$pd['name']}及时按下了BOMB键，其原地满血复活了！</span><br>";
					\logger\logsave ( $pa['pid'], $now, $w_log ,'b');
				}
			}
		}
		return $ret;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (isset($pd['skill58_flag']) && $pd['skill58_flag'])
		{
			if ($active)
			{
				$log.='<span class="lime b">但是，由于敌人及时按下了BOMB键，其原地满血复活了！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是，由于你及时按下了BOMB键，你原地满血复活了，</span>';
			}
			else
			{
				$log.='<span class="lime b">但是，由于你及时按下了BOMB键，你原地满血复活了！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是，由于敌人及时按下了BOMB键，其原地满血复活了，</span>';
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revival') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}因为及时按下了BOMB键而原地满血复活了！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>