<?php

namespace skill56
{
	//召唤NPC需要的金钱
	$skill56_need = 1000;
	
	//召唤次数限制
	$skill56_lim = 2;
	
	//欠薪最大时长
	$merc_leave_timeout = 180;
	
	function init() 
	{
		define('MOD_SKILL56_INFO','club;upgrade;active;limited;');
		global $skill56_npc;
		eval(import_module('player','addnpc','npc'));
		$typeinfo[25]='佣兵';
		$npcinfo[25]=$skill56_npc;
		$anpcinfo[25]=$skill56_npc;
	}
	
	function acquire56(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $skill56_lim;
		//已经发动技能次数
		\skillbase\skill_setvalue(56,'t','0',$pa);
		for ($i=1; $i<=$skill56_lim; $i++)
		{
			//佣兵PID
			\skillbase\skill_setvalue(56,'p'.$i,'0',$pa);
			//上次发工资时间
			\skillbase\skill_setvalue(56,'l'.$i,'0',$pa);
			//佣兵类型
			\skillbase\skill_setvalue(56,'s'.$i,'0',$pa);
			//是否被雇佣（未被解雇）
			\skillbase\skill_setvalue(56,'h'.$i,'0',$pa);
		}
	}
	
	function lost56(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//失去技能时解雇所有佣兵
		eval(import_module('skill56','sys','player'));
		$x=(int)\skillbase\skill_getvalue(56,'t');
		for ($i=1; $i<=$x; $i++)
		{
			$is_hired=(int)\skillbase\skill_getvalue(56,'h'.$i);
			if ($is_hired==1)
			{
				$spid = (int)\skillbase\skill_getvalue(56,'p'.$i);
				$ty=(int)\skillbase\skill_getvalue(56,'s'.$i);
				if ($spid>0)
				{
					$employee=\player\fetch_playerdata_by_pid($spid);
					if ($employee['hp']>0)
					{
						$lastsal=(int)\skillbase\skill_getvalue(56,'l'.$i);
						if ($now-$lastsal>=60)
						{
							//这说明该佣兵工资处于拖欠状态
							$employee['money']+=$money;
							$money=0;
						}
						if ($skill56_npc['sub'][$ty]['mercfireaction']==1)
						{
							$employee['pls']=999;
						}
						\skillbase\skill_setvalue(56,'h'.$i,0);
						\player\player_save($employee);
					}
				}
			}
		}
	}
	
	function check_unlocked56(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function skill56_summon_npc($nkind)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill56','map','sys','player','logger'));
		$log.='你召唤出了佣兵<span class="yellow b">'.$skill56_npc['sub'][$nkind]['name'].'</span>来保护你！<br>';
		$x=(int)\skillbase\skill_getvalue(56,'t');
		$spids = \addnpc\addnpc(25,$nkind,1);
		if ($spids==-1)
		{
			$log.='出现了一个BUG，请联系管理员。抱歉。<br>';
			return;
		}
		$spid = $spids[0];
		//设置位置
		$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid='$spid'");
		\skillbase\skill_setvalue(56,'p'.$x,$spid);
		\skillbase\skill_setvalue(56,'l'.$x,$now);
		\skillbase\skill_setvalue(56,'s'.$x,$nkind);
		\skillbase\skill_setvalue(56,'h'.$x,1);
		//佣兵技能skill57（收工资）
		$pdata = \player\fetch_playerdata_by_pid($spid);
		\skillbase\skill_acquire(57,$pdata);
		\skill57\skill57_set_hpid($pid,$pdata);
		\skill57\skill57_set_label($x,$pdata);
		\player\player_save($pdata);
	}
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill56'));
		if (!\skillbase\skill_query(56) || !check_unlocked56($sdata)) return $chprocess();
		//工资结算有两处：
		//1. 雇佣者主动行动时（触发skill57）
		//2. 佣兵被人摸到时（由skill57处理）
		$mercnum=(int)\skillbase\skill_getvalue(56,'t');
		for ($i=1; $i<=$mercnum; $i++)
		{
			$lastsal=(int)\skillbase\skill_getvalue(56,'l'.$i);
			$ty=(int)\skillbase\skill_getvalue(56,'s'.$i);
			//先预判是否触发成功支付工资或强制解雇事件，减少数据库读写
			if (($now-$lastsal>=60 && $money>=$skill56_npc['sub'][$ty]['mercsalary']) || $now-$lastsal>=$merc_leave_timeout)
			{
				$is_hired=(int)\skillbase\skill_getvalue(56,'h'.$i);
				if ($is_hired==1)
				{
					$rs=(int)floor(($now-$lastsal)/60);
					if ($rs>0)
					{
						$spid=(int)\skillbase\skill_getvalue(56,'p'.$i);
						//触发skill57
						$pdata=\player\fetch_playerdata_by_pid($spid);
						if ($pdata['hp']<=0)
						{
							//已经死亡，不再在未来触发事件，减少数据库读写
							\skillbase\skill_setvalue(56,'l'.$i,2147483647);
						}
						\player\player_save($pdata);
					}
				}
			}
		}
		$chprocess();
	}
	
	function action56()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill56','sys','map','player','logger'));
		if (!\skillbase\skill_query(56) || !check_unlocked56($sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$skillpara1=(int)get_var_input('skillpara1');
		$skillpara2=(int)get_var_input('skillpara2');
		$skillpara3=(int)get_var_input('skillpara3');
		if ($skillpara1==1)
		{
			//雇佣
			$x=(int)\skillbase\skill_getvalue(56,'t');
			if ($x>=$skill56_lim)
			{
				$log .= '发动次数已经达到了上限。<br>';
				return;
			}
			
			if ($money<$skill56_need)
			{
				$log .= '金钱不足。<br>';
				return;
			}
			
			if(!in_array($pls,\map\get_all_plsno())){
				$log.='电话里传来佣兵的骂声：哥们，脚踏实地一点好吗？<br>';
				return;
			}
			elseif(!\map\check_can_enter($pls)){
				$log.='不能在禁区招募佣兵。<br>';
				return;
			}
			else
			{	
				addnews ( 0, 'bskill56', $name );
				
				$money-=$skill56_need;
				$x++; 
				\skillbase\skill_setvalue(56,'t',$x);
				
				$tot = count($skill56_npc['sub']);
				$tp = 0;
				for ($i=0; $i<$tot; $i++) $tp+=$skill56_npc['sub'][$i]['probability'];
				$dice = rand(1,$tp);
				for ($i=0; $i<$tot; $i++)
				{
					if ($dice<=$skill56_npc['sub'][$i]['probability'])
					{
						skill56_summon_npc($i);
						break;
					}
					else  $dice-=$skill56_npc['sub'][$i]['probability'];
				}
			}

		}
		else 
		{
			$x=(int)\skillbase\skill_getvalue(56,'t');
			if ($skillpara2<1 || $skillpara2>$x)
			{
				$log.='参数错误。<br>';
				return;
			}
			if (((int)\skillbase\skill_getvalue(56,'h'.$skillpara2))!=1)
			{
				$log.='该佣兵已经与你没有雇佣关系。<br>';
				return;
			}
			$spid = (int)\skillbase\skill_getvalue(56,'p'.$skillpara2);
			if ($spid<=0)
			{
				$log.='出现了一个BUG，请报告管理员。抱歉。<br>';
				return;
			}
			$employee=\player\fetch_playerdata_by_pid($spid);
			if ($employee['hp']<=0)
			{
				$log.='该佣兵已经死亡！<br>';
				return;
			}
			$ty = (int)\skillbase\skill_getvalue(56,'s'.$skillpara2);
			if ($skillpara1==2)
			{
				//解雇
				$lastsal=(int)\skillbase\skill_getvalue(56,'l'.$skillpara2);
				if ($now-$lastsal>=60)
				{
					//这说明该佣兵工资处于拖欠状态
					$employee['money']+=$money;
					$money=0;
				}
				if ($skill56_npc['sub'][$ty]['mercfireaction']==1)
				{
					//$employee['pls']=999;
					$db->query("DELETE FROM {$tablepre}players WHERE pid = '{$employee['pid']}'");
				}
				else $employee['pls'] = rand(1,33);
				\skillbase\skill_setvalue(56,'h'.$skillpara2,0);
				$log.='解雇成功。';
			}
			else  if ($skillpara1==3)
			{
				//移动
				$cost = $skill56_npc['sub'][$ty]['mercsalary']*2;
				if ($money>=$cost)
				{
					if ($skillpara3==$employee['pls'])
					{
						$log.='佣兵已经在该地点，不需移动。<br>';
						return;
					}
					elseif(!in_array($skillpara3,\map\get_all_plsno())){
						$log.='不能将保安派遣到虚空！<br>';
						return;
					}
					elseif(!\map\check_can_enter($skillpara3)){
						$log.='不能将保安移动到禁区！<br>';
						return;
					}
					else
					{
						$money-=$cost;
						$employee['money']+=$cost;
						$employee['pls']=$skillpara3;
						$log.="消耗了<span class=\"yellow b\">$cost</span>元，佣兵<span class=\"yellow b\">{$employee['name']}</span>移动到了<span class=\"yellow b\">{$plsinfo[$employee['pls']]}</span>。<br>";
					}
				}
				else
				{
					$log.='金钱不足。';
					return;
				}
			}
			else  if ($skillpara1==4)
			{
				//预付工资
				if ($skillpara3<0) 
				{
					$log.='不能倒贴工资。<br>';
					return;
				}
				if ($skillpara3>20) $skillpara3=20;	//防溢出。。。
				$cost = $skill56_npc['sub'][$ty]['mercsalary']*$skillpara3;
				if ($money>=$cost)
				{
					$lastsal=(int)\skillbase\skill_getvalue(56,'l'.$skillpara2);
					$lastsal+=$skillpara3*60;
					\skillbase\skill_setvalue(56,'l'.$skillpara2,$lastsal);
					$money-=$cost;
					$employee['money']+=$cost;
					$log.="消耗了<span class=\"yellow b\">$cost</span>元，预付了佣兵<span class=\"yellow b\">{$employee['name']}</span>将来<span class=\"yellow b\">$skillpara3</span>分钟的工资。<br>";
				}
				else
				{
					$log.='金钱不足。<br>';
					return;
				}
			}
			else
			{
				$log.='未知参数。<br>';
				return;
			}
			\player\player_save($employee);
		}
		
				
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		$subcmd = get_var_input('subcmd');
		if ($mode == 'special' && $command == 'skill56_special' && $subcmd=='summon') 
		{
			ob_clean();
			include template('MOD_SKILL56_MERCPANEL');
			$cmd=ob_get_contents();
			ob_clean();
			return;
		}
		
		if ($mode == 'special' && $command == 'skill56_special' && $subcmd=='action56') 
		{
			action56();
			ob_clean();
			include template('MOD_SKILL56_MERCPANEL');
			$cmd=ob_get_contents();
			ob_clean();
			return;
		}
		
		$chprocess();
	}
	
	function check_alive_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//佣兵活着时不会与之进入战斗界面，无论是否被解雇
		//20240103更改：现在能遇到自己解雇的佣兵了
		eval(import_module('player'));
		if (!\skillbase\skill_query(56) || !check_unlocked56($sdata)) return $chprocess($edata);
		$x=(int)\skillbase\skill_getvalue(56,'t');
		for ($i=1; $i<=$x; $i++)
		{
			$spid = (int)\skillbase\skill_getvalue(56,'p'.$i);
			$is_hired = (int)\skillbase\skill_getvalue(56,'h'.$i);
			if (($edata['pid']==$spid) && $is_hired) return 0;
		}
		return $chprocess($edata);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill56') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「招募佣兵」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
