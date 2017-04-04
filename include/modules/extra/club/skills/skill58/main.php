<?php

namespace skill58
{
	function init() 
	{
		define('MOD_SKILL58_INFO','club;hidden;');
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
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd);
		
		eval(import_module('sys','logger'));
		if (in_array($pd['state'],Array(20,21,22,23,24,25,27,29,40,41)))
			if (\skillbase\skill_query(58,$pd) && ((int)\skillbase\skill_getvalue(58,'r',$pd))==0)
			{
				\skillbase\skill_setvalue(58,'r','1',$pd);
				if ($pd['state']==27 && !$pd['sourceless'])	//陷阱
				{
					$log.= "<span class=\"lime\">但是，由于你及时按下了BOMB键，你原地满血复活了！</span><br>";
					$w_log = "<span class=\"lime\">但是，由于{$pd['name']}及时按下了BOMB键，其原地满血复活了！</span><br>";
					\logger\logsave ( $pa['pid'], $now, $w_log ,'b');
				}
				else
				{
					//击杀复活提示将接管player_kill_enemy进行
				}
				$pd['state']=0; $pd['hp']=$pd['mhp'];
				$pd['skill58_flag']=1;
				$deathnum--;
				if ($pd['type']==0) $alivenum++;
				save_gameinfo();
				
				addnews ( $now, 'revival', $pd['name'] );
				//满血复活时加成效果（这个其实是技能“新生”的内容，但直接做在一起好了）
				$pd['mhp']+=$pd['lvl']*2; $pd['hp']+=$pd['lvl']*2;
				$pd['def']+=$pd['lvl']*5;
			}
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
				$log.='<span class="lime">但是，由于敌人及时按下了BOMB键，其原地满血复活了！</span><br>';
				$pd['battlelog'].='<span class="lime">但是，由于你及时按下了BOMB键，你原地满血复活了，</span>';
			}
			else
			{
				$log.='<span class="lime">但是，由于你及时按下了BOMB键，你原地满血复活了！</span><br>';
				$pd['battlelog'].='<span class="lime">但是，由于敌人及时按下了BOMB键，其原地满血复活了，</span>';
			}
		}
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revival') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}因为及时按下了BOMB键而原地满血复活了！</span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
}

?>
