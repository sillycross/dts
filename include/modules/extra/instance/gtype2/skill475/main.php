<?php

namespace skill475
{
	$skill475_revive_time = 15;
	
	function init() 
	{
		define('MOD_SKILL475_INFO','club;locked;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[475] = '特殊';
	}
	
	/*
	 * 游戏模式介绍：
	 * 死后会在15秒后复活（少数死法除外），限时2禁，目标是获得尽可能多的胜利点数
	 * 
	 * 胜利点数结算方式：
	 * 自身赏金初始为100，每当通过击杀玩家或NPC收入金钱时，自身赏金增加同样数目
	 * 击杀其他玩家时，设对方赏金为x，则你的胜利点数增加0.15x点，自身赏金增加0.3x点，而对方的赏金降低45%
	 * 被NPC击杀或因意外事件而死时，前3次没有惩罚，之后每次损失5%生命上限和3%胜利点数
	 * 击杀NPC时获得的胜利点数：小兵20点，幻象250点，真职人/电波幽灵750点，杏仁豆腐1200点，猴子2500点
	 * 
	 * 战斗击杀其他玩家时可以选择的选项：
	 * 1. 销毁对方30%的金钱（自己不获得）
	 * 2. 获得对方30%的金钱，并放弃同等数值的胜利点数
	 * 3. 降低12%对方武器效果值
	 * 4. 降低对方一件防具的20%效果值
	 * 5. 销毁对方装备的饰品或一件背包物品
	 *
	 * 其他方式击杀玩家依然获得胜利点数但没有上述选项可以选择
	 *
	 * 其他特性：
	 * 1. 没有boss npc刷新（红蓝，scp，英灵），英灵没有特效
	 * 2. 商店地雷价格下降，妖精的羽翼价格上升，没有药剂系列物品
	 * 3. 蛋服、炸弹人卡片禁用
	 */
	 
	function acquire475(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(475,'wpt',0,$pa);		//胜利点数
		\skillbase\skill_setvalue(475,'bounty',100,$pa);	//自身被击杀时对方获得的胜利点数
		\skillbase\skill_setvalue(475,'dc',0,$pa);		//非其他玩家原因导致的死亡计数
	}
	
	function lost475(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(475,'wpt',$pa);
		\skillbase\skill_delvalue(475,'bounty',$pa);
		\skillbase\skill_delvalue(475,'dc',$pa);
	}
	
	function check_unlocked475(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function sk475_revive_player(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['hp']=round($pa['mhp']/2); $pa['state']=0;
		$pa['player_dead_flag']=0;
		eval(import_module('sys'));
		$db->query("UPDATE {$tablepre}players SET player_dead_flag='0' WHERE pid='".$pa['pid']."'");
	}
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(475))
		{
			eval(import_module('sys','player','skill475'));
			if ($hp<=0 && $now>=$endtime+$skill475_revive_time)	//复活
				sk475_revive_player($sdata);
		}
		$chprocess();
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(475,$pa))
		{
			eval(import_module('sys','skill475'));
			if ($pa['hp']<=0 && $now>=$pa['endtime']+$skill475_revive_time)	//复活
				sk475_revive_player($pa);
		}
		$chprocess($pa);
	}
	
	function getcorpse_action(&$edata, $item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (!\skillbase\skill_query(475)) {
			$chprocess($edata, $item);
			return;
		}
		
		if ($edata['type']!=0)	
		{
			if ($item=='money')	//获得金钱时候增加自身赏金
			{
				$btgain=$edata['money'];
				$nbt=((int)\skillbase\skill_getvalue(475,'bounty'))+$btgain;
				\skillbase\skill_setvalue(475,'bounty',$nbt);
			}
			$chprocess($edata, $item);
			return;
		}
		
		$w_log='';
		eval(import_module('sys','player','logger'));
		if ($item=='money1')
		{
			$t=round($edata['money']*0.3);
			$edata['money']-=$t;
			$log.="销毁了对方{$t}元金钱。<br>";
			$w_log="敌人销毁了你的{$t}元金钱！<br>";
		}
		else if ($item=='money2')
		{
			$t=round($edata['money']*0.3);
			$edata['money']-=$t;
			$money+=$t;
			$wpt=(int)\skillbase\skill_getvalue(475,'wpt');
			$wpt=max($wpt-$t,0);
			\skillbase\skill_setvalue(475,'wpt',$wpt);
			$log.="获得了对方{$t}元金钱。<br>";
			$w_log="敌人抢走了你的{$t}元金钱！<br>";
			//获得金钱，增加自身赏金
			$nbt=((int)\skillbase\skill_getvalue(475,'bounty'))+$t;
			\skillbase\skill_setvalue(475,'bounty',$nbt);
		}
		else if ($item=='wep')
		{
			$edata['wepe']=round($edata['wepe']*0.88);
			$log.="削弱了对方的武器。<br>";
			$w_log="敌人削弱了你的武器！<br>";
		}
		else if ($item=='arb' || $item=='ara' || $item=='arh' || $item=='arf')
		{
			$edata[$item.'e']=round($edata[$item.'e']*0.8);
			$log.="削弱了对方的指定防具。<br>";
			if ($item=='arb') $w_log='敌人削弱了你的身体防具！<br>';
			if ($item=='ara') $w_log='敌人削弱了你的手臂防具！<br>';
			if ($item=='arh') $w_log='敌人削弱了你的头部防具！<br>';
			if ($item=='arf') $w_log='敌人削弱了你的足部防具！<br>';
		}
		if ($item=='art')
		{
			$edata['art']='';
			$edata['ark']='';
			$edata['arte']=0;
			$edata['arts']=0;
			$edata['artsk']='';
			$log.="摧毁了对方装备的饰品。<br>";
			$w_log='敌人摧毁了你的饰品！<br>';
		}
		else if ($item=='itm0' || $item=='itm1' || $item=='itm2' || $item=='itm3' || $item=='itm4' || $item=='itm5' || $item=='itm6')
		{
			$w_log='敌人摧毁了你背包中的物品<span class="yellow b">'.$edata[$item].'</span>！<br>';
			$edata[$item]='';
			$edata['itmk'.$item[3]]='';
			$edata['itme'.$item[3]]=0;
			$edata['itms'.$item[3]]=0;
			$edata['itmsk'.$item[3]]='';
			$log.="摧毁了对方的指定背包物品。<br>";
		}
		$mode = 'command';
		$action = '';
		if ($w_log!='') \logger\logsave ( $edata['pid'], $now, $w_log ,'b');
	}
	
	function kill(&$pa, &$pd) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (!\skillbase\skill_query(475,$pa) && !\skillbase\skill_query(475,$pd)) return $chprocess($pa, $pd);

		if ($pa['pid']!=$pd['pid'] && $pa['type']==0 && $pd['type']==0)	//玩家被其他玩家杀死
		{
			$ebounty=(int)\skillbase\skill_getvalue(475,'bounty',$pd);
			$wptgain=round($ebounty*0.15);
			$btgain=round($ebounty*0.3);
			$nwpt=((int)\skillbase\skill_getvalue(475,'wpt',$pa))+$wptgain;
			$nbt=((int)\skillbase\skill_getvalue(475,'bounty',$pa))+$btgain;
			$nbt_pd=$ebounty-$wptgain-$btgain;
			\skillbase\skill_setvalue(475,'wpt',$nwpt,$pa);
			\skillbase\skill_setvalue(475,'bounty',$nbt,$pa);
			\skillbase\skill_setvalue(475,'bounty',$nbt_pd,$pd);
			eval(import_module('sys','player','logger'));
			if ($pa['pid']==$sdata['pid'])
				$log.='<span class="orange">你获得了'.$wptgain.'点胜利点数！</span><br>';
			else
			{
				if (in_array($pd['state'],Array(20,21,22,23,24,25,29)))	//战斗击杀
					$pa['battlelog'].='<span class="orange">你获得了'.$wptgain.'点胜利点数！</span><br>';
				else
				{
					$w_log.='<span class="orange">你获得了'.$wptgain.'点胜利点数！</span><br>';
					\logger\logsave ( $pa['pid'], $now, $w_log ,'b');
				}
			}
		}
		else if ($pa['type']==0 && $pd['type']>0)	//玩家击杀npc
		{
			$wptarr=Array(
				2=>250,	//幻象
				5=>1200,	//豆腐
				6=>2500,	//猴子
				11=>750,	//真职人
				45=>750,	//电波幽灵
				90=>20	//小兵
			);
			if (isset($wptarr[$pd['type']]))
			{
				$wptgain=$wptarr[$pd['type']];
				$nwpt=((int)\skillbase\skill_getvalue(475,'wpt',$pa))+$wptgain;
				\skillbase\skill_setvalue(475,'wpt',$nwpt,$pa);
				eval(import_module('logger'));
				$log.='<span class="orange">你获得了'.$wptgain.'点胜利点数！</span><br>';
			}
		}
		else if ($pd['type']==0) //玩家自杀
		{
			$cnt=(int)\skillbase\skill_getvalue(475,'dc',$pd);
			$cnt++;
			if ($cnt>3) 
			{
				$pd['mhp']=ceil($pd['mhp']*0.95);
				$nwpt=(int)\skillbase\skill_getvalue(475,'wpt',$pd);
				$nwpt=round($nwpt*0.97);
				\skillbase\skill_setvalue(475,'wpt',$nwpt,$pd);
			}
			\skillbase\skill_setvalue(475,'dc',$cnt,$pd);
		}
				
		return $chprocess($pa,$pd);
	}
	
}

?>