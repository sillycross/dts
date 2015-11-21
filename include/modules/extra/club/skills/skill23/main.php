<?php

namespace skill23
{
	function init() 
	{
		define('MOD_SKILL23_INFO','club;active;hidden;');
	}
	
	function acquire23(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost23(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
		
	function gemming_itme_buff(&$itm,&$itmk,&$itme,&$itms,&$itmsk,$lb,$ub)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		if ($itms==$nosta) 
		{
			$up_e=rand(round($lb*0.85),round($ub*0.85)); 
			$log.="你的装备<span class=\"yellow\">{$itm}</span>的效果值增加了<span class=\"yellow\">{$up_e}</span>点！";
			$itme+=$up_e;
		}
		else
		{
			$up_all=rand($lb,$ub); 
			$up_e=ceil(1.0*$up_all*$itme/($itme+$itms));
			$up_s=floor(1.0*$up_all*$itms/($itme+$itms));
			$log.="你的装备<span class=\"yellow\">{$itm}</span>的效果值增加了<span class=\"yellow\">{$up_e}</span>点，耐久值增加了<span class=\"yellow\">{$up_s}</span>点！";	
			$itme+=$up_e; $itms+=$up_s;
		}
	}

	function gemming($t1, $t2)	//宝石骑士宝石buff技能
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));

		if ($t1!='wep' && $t1!='arb' && $t1!='arh' && $t1!='ara' && $t1!='arf')
		{
			$log.='你只能给你的武器/防具增加属性。<br>';
			$mode = 'command';
			return;
		}
		
		$itm=&${$t1}; $itmk=&${$t1.'k'}; $itme=&${$t1.'e'}; $itms=&${$t1.'s'}; $itmsk=&${$t1.'sk'};
		
		if ($t1=='wep'|| !$itme || !$itms)
		{
			if ($itmk=='WN')
			{
				$log.='你试图改造你的武器，但是你没有装备武器。<br>';
				$mode = 'command';
				return;
			}
		}
		else
		{
			if (($itms <= 0) && ($itms != $nosta)) 
			{
				$log.='本防具不存在，请重新选择。<br>';
				$mode = 'command';
				return;
			}
		}
		
		if(\itemmain\count_itmsk_num($itmsk)>=6){
			$log .= '你选择的物品属性数目已达到6个属性的上限，无法改造！<br>';
			$mode = 'command';
			return;
		}
				
		$t2=(int)$t2;
		if ($t2<1 || $t2>6)
		{
			$log.='你选择的宝石/方块不存在，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		
		$gem=&${'itm'.$t2}; $gemk=&${'itmk'.$t2}; $geme=&${'itme'.$t2}; $gems=&${'itms'.$t2}; $gemsk=&${'itmsk'.$t2};
		
		if (($gems <= 0) && ($gems != $nosta)) 
		{
			$log.='你选择的宝石/方块不存在，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		
		$buff=Array();
		
		if ($gem=='红色方块')	//火焰
			if ($t1=='wep')
				$buff=Array(Array(35,'u'));
			else  $buff=Array(Array(65,'P'),Array(35,'U'));
		else  if ($gem=='黄色方块')	//重辅
			if ($t1=='wep')
				$buff=Array(Array(100,'c'));
			else  $buff=Array(Array(65,'D'),Array(35,'c'));
		else  if ($gem=='蓝色方块')	//冻气
			if ($t1=='wep')
				$buff=Array(Array(35,'i'));
			else  $buff=Array(Array(65,'G'),Array(35,'I'));
		else  if ($gem=='绿色方块')	//带毒
			if ($t1=='wep')
				$buff=Array(Array(35,'p'));
			else  $buff=Array(Array(65,'K'),Array(35,'q'));
		else  if ($gem=='金色方块')	//电击
			if ($t1=='wep')
				$buff=Array(Array(35,'e'));
			else  $buff=Array(Array(65,'C'),Array(35,'E'));
		else  if ($gem=='银色方块')	//音波
			if ($t1=='wep')
				$buff=Array(Array(35,'w'));
			else  $buff=Array(Array(65,'F'),Array(35,'W'));
		else  if ($gem=='红宝石方块')	//火焰/灼焰
			if ($t1=='wep')
				$buff=Array(Array(65,'u'),Array(35,'f'));
			else  $buff=Array(Array(100,'U'));
		else  if ($gem=='蓝宝石方块')	//冻气/冰华
			if ($t1=='wep')
				$buff=Array(Array(65,'i'),Array(35,'k'));
			else  $buff=Array(Array(100,'I'));
		else  if ($gem=='黄鸡方块')	//天然/菁英
			$buff=Array(Array(1,'Z'),Array(99,'z'));
		else  if ($gem=='绿宝石方块')	//武器：随机攻击属性 装备：随机防御属性
		{	
			if ($t1=='wep')
				$buff=Array(Array(15,'u'),Array(15,'i'),Array(15,'p'),Array(15,'e'),Array(15,'w'),Array(6,'f'),Array(6,'k'),Array(6,'n'),Array(6,'N'),Array(1,'d'));
			else  $buff=Array(Array(15,'C'),Array(15,'D'),Array(15,'F'),Array(15,'G'),Array(15,'K'),Array(15,'P'),Array(3,'A'),Array(3,'a'),Array(4,'H'));
		}
		else  if ($gem=='水晶方块')	//连击/HP制御
			if ($t1=='wep')
				$buff=Array(Array(1,'r'));
			else  $buff=Array(Array(5,'H'));
		else  if ($gem=='黑色方块')	//贯穿
			if ($t1=='wep')
				$buff=Array(Array(5,'n'));
			else  $buff=Array(Array(5,'m'));
		else  if ($gem=='白色方块')	//冲击
			if ($t1=='wep')
				$buff=Array(Array(5,'N'));
			else  $buff=Array(Array(5,'M'));
		else 
		{
			$log.="你的物品不是合法的宝石或方块。<br>请参阅帮助获得所有合法的宝石或方块及它们的对应改造属性的列表。<br>";
			$mode = 'command';
			return;
		}
			
		$dice=rand(1,100); $flag=0;
		$log.="你将<span class=\"yellow\">{$gem}</span>镶嵌到了<span class=\"yellow\">{$itm}</span>上。<br>";
		
		$lb=10; $ub=20;
		if (strpos($gem,'宝石') !== false) { $lb=round($lb*1.75); $ub=round($ub*1.75); }	//宝石强化效果更高
		
		foreach ($buff as $value)
		{
			if ($dice<=$value[0])
			{
				$flag=1;
				gemming_itme_buff($itm,$itmk,$itme,$itms,$itmsk,$lb,$ub);
				$log.="同时，你的装备<span class=\"yellow\">{$itm}</span>还获得了“<span class=\"yellow\">{$itemspkinfo[$value[1]]}</span>”属性！<br>";
				include_once GAME_ROOT.'./include/news.func.php';
				addnews ( 0, 'gemming', $name, $gem, $itm, $itemspkinfo[$value[1]]);
				if (strpos($itmsk,$value[1]) === false) $itmsk.=$value[1];
				break;
			}
			else  $dice-=$value[0];
		}
		
		$expgain = rand(4,7);
		
		if (!$flag) 
		{
			$lb=round($lb/2); $ub=round($ub/2);
			gemming_itme_buff($itm,$itmk,$itme,$itms,$itmsk,$lb,$ub);
			$log.="但是你的装备并没有获得额外属性。看起来技术还不过关的样子。<span class=\"yellow\">你决定痛定思痛，总结经验。</span><br>";
			$rage += rand(5,15); $rage = min($rage,100);
			$expgain = rand(7,11);
		}
		
		\lvlctl\getexp($expgain);
		
		$gems--;
		$log.="消耗了一枚{$gem}。<br>";
		if ($gems<=0)
		{
			$log.="{$gem}用完了。<br>";
			$gem=''; $gemk=''; $gems=0; $geme=0; $gemsk='';
		}
		$mode='command';
	}
	
	function do_gemming()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','input'));
		if (!\skillbase\skill_query(23)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		if (isset($skill23_t1) && isset($skill23_t2))
		{
			gemming($skill23_t1,$skill23_t2);
			return;
		}
		include template(MOD_SKILL23_GEMMING);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','input'));
	
		if ($mode == 'special' && $command == 'skill23_special' && $subcmd=='gemming') 
		{
			do_gemming();
			return;
		}
			
		$chprocess();
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'gemming') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用{$b}为{$c}添加了{$d}属性！</span><br>\n";

		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
}

?>
