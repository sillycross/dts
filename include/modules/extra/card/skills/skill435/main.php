<?php

namespace skill435
{
	//召唤NPC需要的金钱
	$skill435_need = 500;
	
	//召唤次数限制
	$skill435_lim = 5;
	
	//欠薪最大时长
	$merc_leave_timeout = 180;
	
	function init() 
	{
		define('MOD_SKILL435_INFO','card;upgrade;active;limited;');
		global $skill435_npc;
		eval(import_module('player','addnpc','npc'));
		$typeinfo[26]='保安';
		$npcinfo[26]=$skill435_npc;
		$anpcinfo[26]=$skill435_npc;
	}
	
	function acquire435(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $skill435_lim;
		//已经发动技能次数
		\skillbase\skill_setvalue(435,'t','0',$pa);
		for ($i=1; $i<=$skill435_lim; $i++)
		{
			//保安PID
			\skillbase\skill_setvalue(435,'p'.$i,'0',$pa);
			//上次发工资时间
			\skillbase\skill_setvalue(435,'l'.$i,'0',$pa);
			//保安类型
			\skillbase\skill_setvalue(435,'s'.$i,'0',$pa);
			//是否被雇佣（未被解雇）
			\skillbase\skill_setvalue(435,'h'.$i,'0',$pa);
		}
	}
	
	function lost435(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//失去技能时解雇所有保安
		eval(import_module('skill435','sys','player'));
		$x=(int)\skillbase\skill_getvalue(435,'t');
		for ($i=1; $i<=$x; $i++)
		{
			$is_hired=(int)\skillbase\skill_getvalue(435,'h'.$i);
			if ($is_hired==1)
			{
				$spid = (int)\skillbase\skill_getvalue(435,'p'.$i);
				$ty=(int)\skillbase\skill_getvalue(435,'s'.$i);
				if ($spid>0)
				{
					$employee=\player\fetch_playerdata_by_pid($spid);
					if ($employee['hp']>0)
					{
						$lastsal=(int)\skillbase\skill_getvalue(435,'l'.$i);
						if ($now-$lastsal>=60)
						{
							//这说明该保安工资处于拖欠状态
							$employee['money']+=$money;
							$money=0;
						}
						if ($skill435_npc['sub'][$ty]['mercfireaction']==1)
						{
							$employee['pls']=999;
						}
						\skillbase\skill_setvalue(435,'h'.$i,0);
						\player\player_save($employee);
					}
				}
			}
		}
	}
	
	function check_unlocked435(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=12;
	}
	
	function skill435_summon_npc($nkind)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill435','map','sys','player','logger'));
		$log.='你召唤出了保安<span class="yellow b">'.$skill435_npc['sub'][$nkind]['name'].'</span>来保护你！<br>';
		$x=(int)\skillbase\skill_getvalue(435,'t');
		$spids = \addnpc\addnpc(25,$nkind,1);
		if (!$spids)
		{
			$log.='出现了一个BUG，请联系管理员。抱歉。<br>';
			return;
		}
		$spid = $spids[0];
		//设置位置
		$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid='$spid'");
		\skillbase\skill_setvalue(435,'p'.$x,$spid);
		\skillbase\skill_setvalue(435,'l'.$x,$now);
		\skillbase\skill_setvalue(435,'s'.$x,$nkind);
		\skillbase\skill_setvalue(435,'h'.$x,1);
		//保安技能skill436（收工资）
		$pdata = \player\fetch_playerdata_by_pid($spid);
		\skillbase\skill_acquire(436,$pdata);
		\skill436\skill436_set_hpid($pid,$pdata);
		\skill436\skill436_set_label($x,$pdata);
		\player\player_save($pdata);
	}
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill435'));
		if (!\skillbase\skill_query(435) || !check_unlocked435($sdata)) return $chprocess();
		//工资结算有两处：
		//1. 雇佣者主动行动时（触发skill436）
		//2. 保安被人摸到时（由skill436处理）
		$mercnum=(int)\skillbase\skill_getvalue(435,'t');
		for ($i=1; $i<=$mercnum; $i++)
		{
			$lastsal=(int)\skillbase\skill_getvalue(435,'l'.$i);
			$ty=(int)\skillbase\skill_getvalue(435,'s'.$i);
			//先预判是否触发成功支付工资或强制解雇事件，减少数据库读写
			if (($now-$lastsal>=60 && $money>=$skill435_npc['sub'][$ty]['mercsalary']) || $now-$lastsal>=$merc_leave_timeout)
			{
				$is_hired=(int)\skillbase\skill_getvalue(435,'h'.$i);
				if ($is_hired==1)
				{
					$rs=(int)floor(($now-$lastsal)/60);
					if ($rs>0)
					{
						$spid=(int)\skillbase\skill_getvalue(435,'p'.$i);
						//触发skill436
						$pdata=\player\fetch_playerdata_by_pid($spid);
						if ($pdata['hp']<=0)
						{
							//已经死亡，不再在未来触发事件，减少数据库读写
							\skillbase\skill_setvalue(435,'l'.$i,2147483647);
						}
						\player\player_save($pdata);
					}
				}
			}
		}
		$chprocess();
	}
	
	function action435()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill435','sys','map','player','logger'));
		if (!\skillbase\skill_query(435) || !check_unlocked435($sdata)) 
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
			$x=(int)\skillbase\skill_getvalue(435,'t');
			if ($x>=$skill435_lim)
			{
				$log .= '发动次数已经达到了上限。<br>';
				return;
			}
			
			if ($money<$skill435_need)
			{
				$log .= '金钱不足。<br>';
				return;
			}
			
			if(!in_array($pls,\map\get_all_plsno())){
				$log.='电话里传来保安的骂声：哥们，脚踏实地一点好吗？<br>';
				return;
			}
			elseif(!\map\check_can_enter($pls)){
				$log.='不能在禁区招募保安。<br>';
				return;
			}
			else
			{	
				addnews ( 0, 'bskill435', $name );
				
				$money-=$skill435_need;
				$x++; 
				\skillbase\skill_setvalue(435,'t',$x);
				
				$tot = count($skill435_npc['sub']);
				$tp = 0;
				for ($i=0; $i<$tot; $i++) $tp+=$skill435_npc['sub'][$i]['probability'];
				$dice = rand(1,$tp);
				for ($i=0; $i<$tot; $i++)
				{
					if ($dice<=$skill435_npc['sub'][$i]['probability'])
					{
						skill435_summon_npc($i);
						break;
					}
					else  $dice-=$skill435_npc['sub'][$i]['probability'];
				}
			}
		}
		else 
		{
			$x=(int)\skillbase\skill_getvalue(435,'t');
			if ($skillpara2<1 || $skillpara2>$x)
			{
				$log.='参数错误。<br>';
				return;
			}
			if (((int)\skillbase\skill_getvalue(435,'h'.$skillpara2))!=1)
			{
				$log.='该保安已经与你没有雇佣关系。<br>';
				return;
			}
			$spid = (int)\skillbase\skill_getvalue(435,'p'.$skillpara2);
			if ($spid<=0)
			{
				$log.='出现了一个BUG，请报告管理员。抱歉。<br>';
				return;
			}
			$employee=\player\fetch_playerdata_by_pid($spid);
			if ($employee['hp']<=0)
			{
				$log.='该保安已经死亡！<br>';
				return;
			}
			$ty = (int)\skillbase\skill_getvalue(435,'s'.$skillpara2);
			if ($skillpara1==2)
			{
				//解雇
				$lastsal=(int)\skillbase\skill_getvalue(435,'l'.$skillpara2);
				if ($now-$lastsal>=60)
				{
					//这说明该保安工资处于拖欠状态
					$employee['money']+=$money;
					$money=0;
				}
				if ($skill435_npc['sub'][$ty]['mercfireaction']==1)
				{
					$employee['pls']=999;
				}
				\skillbase\skill_setvalue(435,'h'.$skillpara2,0);
				$log.='解雇成功。';
			}
			else  if ($skillpara1==3)
			{
				//移动
				$cost = $skill435_npc['sub'][$ty]['mercsalary']*2;
				if ($money>=$cost)
				{
					if ($skillpara3==$employee['pls'])
					{
						$log.='保安已经在该地点，不需移动。<br>';
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
						$log.="消耗了<span class=\"yellow b\">$cost</span>元，保安<span class=\"yellow b\">{$employee['name']}</span>移动到了<span class=\"yellow b\">{$plsinfo[$employee['pls']]}</span>。<br>";
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
				$cost = $skill435_npc['sub'][$ty]['mercsalary']*$skillpara3;
				if ($money>=$cost)
				{
					$lastsal=(int)\skillbase\skill_getvalue(435,'l'.$skillpara2);
					$lastsal+=$skillpara3*60;
					\skillbase\skill_setvalue(435,'l'.$skillpara2,$lastsal);
					$money-=$cost;
					$employee['money']+=$cost;
					$log.="消耗了<span class=\"yellow b\">$cost</span>元，预付了保安<span class=\"yellow b\">{$employee['name']}</span>将来<span class=\"yellow b\">$skillpara3</span>分钟的工资。<br>";
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
		if ($mode == 'special' && $command == 'skill435_special' && $subcmd=='summon') 
		{
			ob_clean();
			include template('MOD_SKILL435_MERCPANEL');
			$cmd=ob_get_contents();
			ob_clean();
			return;
		}
		
		if ($mode == 'special' && $command == 'skill435_special' && $subcmd=='action435') 
		{
			action435();
			ob_clean();
			include template('MOD_SKILL435_MERCPANEL');
			$cmd=ob_get_contents();
			ob_clean();
			return;
		}
		
		$chprocess();
	}
	
	function check_alive_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//保安活着时不会与之进入战斗界面，无论是否被解雇
		eval(import_module('player'));
		if (!\skillbase\skill_query(435) || !check_unlocked435($sdata)) return $chprocess($edata);
		$x=(int)\skillbase\skill_getvalue(435,'t');
		for ($i=1; $i<=$x; $i++)
		{
			$spid = (int)\skillbase\skill_getvalue(435,'p'.$i);
			if ($edata['pid']==$spid)
				return 0;
		}
		return $chprocess($edata);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill435') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「保安团」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
