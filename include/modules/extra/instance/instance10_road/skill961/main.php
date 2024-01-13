<?php

namespace skill961
{
	function init()
	{
		define('MOD_SKILL961_INFO','card;');
		eval(import_module('clubbase','player','addnpc'));
		global $skill961_npc;
		$clubskillname[961] = '护送';
		$typeinfo[61] = '秘密武器？';
		$anpcinfo[61] = $skill961_npc;
	}
	
	function acquire961(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$vippid = \addnpc\addnpc(61,0,1,$pa['pls']);
		//如果没有队伍则创建一个
		if (empty($pa['teamID']))
		{
			do {
				$tID = 'ST'.rand(1000,9999);
				$tPass = rand(1000,9999);
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE teamID='$tID'");
			} while ($db->num_rows($result));
			\team\teammake($tID, $tPass);
		}
		
		$vip = \player\fetch_playerdata_by_pid($vippid[0]);
		$vip['teamID'] = $pa['teamID'];
		$vip['teamPass'] = $pa['teamPass'];
		\player\player_save($vip);
		
		\skillbase\skill_setvalue(961,'vippid',$vippid[0],$pa);
		\skillbase\skill_setvalue(961,'dest',end($arealist),$pa);
		\skillbase\skill_setvalue(961,'lvl','0',$pa);
	}
	
	function lost961(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(961,'vippid',$pa);
		\skillbase\skill_setvalue(961,'dest',$pa);
		\skillbase\skill_delvalue(961,'lvl',$pa);
	}
	
	function check_unlocked961(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//不许退队
	function teamquit()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(961))
		{
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">你还有要完成的任务。</span><br>";
			return;
		}
		$chprocess();
	}
	
	//移动时NPC跟着移动
	function move($moveto = 99)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($moveto);
		eval(import_module('player'));
		if (\skillbase\skill_query(961,$sdata))
		{
			$vippid = (int)\skillbase\skill_getvalue(961,'vippid',$sdata);
			if ($vippid > 0)
			{
				$vip = \player\fetch_playerdata_by_pid($vippid);
				if (($vip['hp']>0) && ($vip['pls']!=$pls))
				{
					eval(import_module('map','logger'));
					$log .= "<span class=\"yellow b\">{$vip['name']}跟随你来到了{$plsinfo[$pls]}。</span><br>";
					$vip['pls'] = $pls;
					\player\player_save($vip);
					//完成任务判定
					if (\skillbase\skill_getvalue(961,'dest',$sdata) == $pls)
					{
						$log .= "<span class=\"yellow b\">你完成了护送任务！</span><br>";//现在暂时还没加护送完后NPC消失或退队的处理
						\skillbase\skill_setvalue(961,'lvl','1',$sdata);
						\skillbase\skill_setvalue(961,'vippid','0',$sdata);
					}
				}
			}
		}
	}
	
	//遇到护送NPC时的特殊显示
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(961,$sdata))
		{
			$vippid = (int)\skillbase\skill_getvalue(961,'vippid',$sdata);
			if ($edata['pid'] == $vippid)
			{
				eval(import_module('logger'));
				$log .= "<span class=\"yellow b\">{$edata['name']}</span>正跟随着你。<br>";
				\team\findteam($edata);
				return;
			}
		}
		return $chprocess($edata);
	}
	
	//战斗中NPC有小概率会受到伤害
	function strike_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['is_hit'] && \skillbase\skill_query(961,$pd) && (rand(0,99) < 10))
		{
			$vippid = (int)\skillbase\skill_getvalue(961,'vippid',$pd);
			if ($vippid > 0)
			{
				$vip = \player\fetch_playerdata_by_pid($vippid);
				if (($vip['hp'] > 1) && ($vip['pls'] == $pd['pls']))
				{
					eval(import_module('logger'));
					$dmg = min(rand(50,130), $vip['hp'] - 1);
					$log .= "<span class=\"red b\">{$vip['name']}被战斗的余波击伤了，受到了{$dmg}点伤害！</span><br>";
					$vip['hp'] -= $dmg;
					\player\player_save($vip);
				}
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	//NPC被击败时任务失败
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('player'));
		if (\skillbase\skill_query(961,$sdata))
		{
			$vippid = (int)\skillbase\skill_getvalue(961,'vippid',$sdata);
			if ($vippid > 0)
			{
				$vip = \player\fetch_playerdata_by_pid($vippid);
				if ($vip['hp'] <= 0)
				{
					eval(import_module('logger'));
					$log .= "<span class=\"red b\">{$vip['name']}并没有跟上来……这样的事情你早该想到的。</span><br>";
					skill_lost(961, $sdata);
				}
			}
		}
	}
	
	//护送时被发现率提高
	function calculate_hide_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata);
		if (\skillbase\skill_query(961, $edata) && ((int)\skillbase\skill_getvalue(961, 'lvl', $edata) == 0)) return $ret-30;
		return $ret;
	}
	
	//护送时先攻率降低
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(961, $ldata) && ((int)\skillbase\skill_getvalue(961, 'lvl', $ldata) == 0)) $r = 0.75;
		return $chprocess($ldata,$edata)*$r;
	}
	
	//护送时受到伤害增加，暂时先不要了
	// function get_final_dmg_multiplier(&$pa, &$pd, $active)
	// {
		// if (eval(__MAGIC__)) return $___RET_VALUE;
		// $r = array();
		// if (\skillbase\skill_query(961, $pd) && ((int)\skillbase\skill_getvalue(961, 'lvl', $pd) == 0))
		// {
			// eval(import_module('logger'));
			// if ($active) $log .= "<span class=\"red b\">{$pd['name']}为了保护同伴，受到的伤害增加了30%！</span><br>";
			// else $log .= "<span class=\"red b\">你为了保护同伴，受到的伤害增加了30%！</span><br>";
			// $r = array(1.3);
		// }
		// return array_merge($r,$chprocess($pa,$pd,$active));
	// }
	
	//NPC助攻
	function attack(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(961,$pa))
		{
			if (rand(0,99) < 15)
			{
				$vippid = (int)\skillbase\skill_getvalue(961,'vippid',$pa);
				if ($vippid > 0)
				{
					$vip = \player\fetch_playerdata_by_pid($vippid);
					if (($vip['hp']>0) && ($vip['pls']==$pa['pls']))
					{
						eval(import_module('logger'));
						$log .= "<span class=\"cyan b\">{$vip['name']}的追加攻击！</span><br>";
						//补充攻击方式和必要参数
						$vip['skill961_flag'] = 1;
						$vip['wep_kind'] = $vip['wepk'][1];
						$vip['bskill'] = 0;
						$vip['fin_skill'] = \weapon\get_skill($vip,$pd,$active);
						$vip['fin_hitrate'] = \weapon\get_hitrate($vip,$pd,$active);
						$vip['mult_words_fdmgbs'] = '';
						$vip['physical_dmg_dealt'] = 0;
						$vip['dmg_dealt'] = 0;
						$vip['actual_rapid_time'] = 0;
						$vip['wepimp'] = 0;
						$chprocess($vip,$pd,$active);
						\player\player_save($vip);
						//伤害追加到主玩家伤害
						if ($vip['dmg_dealt'])
						{
							$pa['dmg_dealt'] += $vip['dmg_dealt'];
							$pa['mult_words_fdmgbs'] = \attack\add_format($vip['dmg_dealt'], $pa['mult_words_fdmgbs']);
							$pa['skill961_helped'] = 1;
						}
					}
				}
			}
		}
	}
	
	//击杀显示处理
	function deathnews(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','player'));
		if (\skillbase\skill_query(961,$pa) && isset($pa['skill961_helped']))
		{
			$vippid = (int)\skillbase\skill_getvalue(961,'vippid',$pa);
			$vip = \player\fetch_playerdata_by_pid($vippid);
			deathnews($vip, $pd);
		}
		else $chprocess($pa, $pd);
	}
	
	//助攻不显示伤害计算log
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$temp_log = $log;
		$ret = $chprocess($pa, $pd, $active);
		if (isset($pa['skill961_flag'])) $log = $temp_log;
		return $ret;
	}
	
	//助攻不显示耐久扣减log
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$temp_log = $log;
		$chprocess($pa, $pd, $active);
		if (isset($pa['skill961_flag'])) $log = $temp_log;
	}
	
}

?>