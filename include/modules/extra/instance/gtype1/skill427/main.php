<?php

namespace skill427
{
	function init() 
	{
		define('MOD_SKILL427_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[427] = '续命';
	}
	
	function acquire427(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function lost427(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked427(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd);
		
		eval(import_module('sys','logger'));
		if (($pa['type']==88)||($pa['type']==1)){
			$log.= "<span class=\"linen\">都告诉你了，对某些NPC无效……快去死吧。</span><br>";
			return;
		}
		
		if (in_array($pd['state'],Array(20,21,22,23,24,25,27,29)))
			if (\skillbase\skill_query(427,$pd))
			{
				\skillbase\skill_setvalue(427,'r','1',$pd);
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
				$pd['skill427_flag']=1;
				$deathnum--;
				if ($pd['type']==0) $alivenum++;
				save_gameinfo();
				
				if (\skillbase\skill_query(424,$pd)){
					$clv=\skillbase\skill_getvalue(424,'lvl',$pd); 
					$clv=$clv-2;
					if ($clv<0) $clv=0;
					\skillbase\skill_setvalue(424,'lvl',$clv,$pd); 
				}
				
				//陷阱杀人得技能点一起放在这里
				if ($pd['state']==27){
					$pa['skillpoint']+=2;
					if (\skillbase\skill_query(424,$pa)){
						$clv=\skillbase\skill_getvalue(424,'lvl',$pa); 
						$clv++;
						\skillbase\skill_setvalue(424,'lvl',$clv,$pa); 
					}
				}
				
				addnews ( $now, 'revival', $pd['name'] );
			}
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (isset($pd['skill427_flag']) && $pd['skill427_flag'])
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
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revival') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}因为及时按下了BOMB键而原地满血复活了！</span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
}

?>
