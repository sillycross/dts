<?php

namespace skill562
{
	$skill562_lvlup_ss = 6;
	$skill562_nocost_rate = 25;
	
	function init() 
	{
		define('MOD_SKILL562_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[562] = '歌姬';
	}
	
	function acquire562(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(562,'learnedsongs','',$pa);
	}
	
	function lost562(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(562,'learnedsongs',$pa);
	}
	
	function check_unlocked562(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//升级获得歌魂
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(562,$pa) && check_unlocked562($pa))
		{
			eval(import_module('skill562'));
			$pa['mss'] += $skill562_lvlup_ss;
			$pa['ss'] += $skill562_lvlup_ss;		
		}
		$chprocess($pa);
	}
	
	//使用歌词卡片后记录歌曲名
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$itm = $theitem['itm']; $itmk = $theitem['itmk'];
		if (0 === strpos($itmk, 'ss'))
		{
			if (\skillbase\skill_query(562) && check_unlocked562())
			{
				$sn = \song\check_sname($itm);
				eval(import_module('song'));
				//如果该歌曲存在，则记录
				foreach($songlist as $skey => $sval)
				{
					if ($sval['songname'] === $sn)
					{
						eval(import_module('skill562'));
						$learnedsongs = \skillbase\skill_getvalue(562,'learnedsongs');
						if ('' === $learnedsongs) $ls = array();
						else $ls = explode('_',$learnedsongs);
						if (!in_array($skey, $ls)) 
						{
							$ls[] = $skey;
							$learnedsongs = implode('_',$ls);
							\skillbase\skill_setvalue(562,'learnedsongs',$learnedsongs);
							eval(import_module('logger'));
							$log .= "你学会了歌曲<span class=\"yellow b\">{$sval['songname']}</span>！<br>";
						}
						break;
					}
				}
			}
		}
		$chprocess($theitem);
	}
	
	function cast_skill562()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','input'));
		if (!\skillbase\skill_query(562)) 
		{
			$log.='你没有这个技能。';
			return;
		}		
		if (isset($skill562_choice))
		{
			$z = (int)$skill562_choice;
			eval(import_module('song'));
			if (isset($songlist[$z]['songname']))
			{
				ss_sing($songlist[$z]['songname']);
				$mode = 'command';
				return;
			}
			else
			{
				$log .= '参数不合法。<br>';
			}
		}
		include template(MOD_SKILL562_CASTSK562);
		$cmd = ob_get_contents();
		ob_clean();
	}
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if (\skillbase\skill_query(562) && check_unlocked562())
		{
			eval(import_module('sys','player'));
			$ss_temp = $ss;
			//检测是否已经习得，已习得则跳过装备歌词卡片判定
			$learnedsongs = \skillbase\skill_getvalue(562,'learnedsongs');
			if ('' !== $learnedsongs)
			{
				$ls = explode('_',$learnedsongs);
				eval(import_module('song'));
				foreach($songlist as $skey => $sval)
				{
					if ($sval['songname'] === $sn)
					{
						if (in_array($skey, $ls))
						{
							//不太优雅
							$artk_temp = $artk;
							$artk = 'ss';
							break;
						}
					}
				}
			}
		}
		$chprocess($sn);
		if (\skillbase\skill_query(562) && check_unlocked562())
		{
			//之后如果有唱完以后歌词卡片会消失的歌，或者改变饰品类别的歌就会出问题
			//……不会有吧？
			if(isset($artk_temp)) $artk = $artk_temp;
			//概率返还歌魂
			eval(import_module('skill562'));
			$dice = rand(0,99);
			if ($dice < $skill562_nocost_rate)
			{
				if(isset($ss_temp))
				{
					eval(import_module('logger'));
					$log .= "<span class=\"yellow b\">你的本次演唱没有消耗歌魂！<br>";
					$ss = $ss_temp;
				}
			}
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','player','logger','input'));	
		if ($mode == 'special' && $command == 'skill562_special' && $subcmd == 'castsk562') 
		{
			cast_skill562();
			return;
		}
		$chprocess();
	}

}

?>