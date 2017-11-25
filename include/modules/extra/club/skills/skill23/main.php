<?php

namespace skill23
{
	function init() 
	{
		define('MOD_SKILL23_INFO','club;active;locked;');
		eval(import_module('clubbase'));
		$clubskillname[23] = '宝石';
		$clubdesc_h[20] = $clubdesc_a[20] = '可用「方块」道具为武器或防具增加效耐值或添加属性';
	}
	
	function acquire23(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost23(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked23(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
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
		eval(import_module('sys','player','itemmain','logger','skill23'));

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
		
		if(isset($bufflist23[$gem])){
			$tmp_gemk = substr($itmk,0,1);
			if(isset($bufflist23[$gem][$tmp_gemk])){
				$buff = $bufflist23[$gem][$tmp_gemk];
			}else{
				$log.="你的物品不是宝石改造的有效目标";
				$mode = 'command';
				return;
			}
		}else{
			$log.="你的物品不是有效的宝石或方块。<br>请参阅帮助获得所有有效的宝石或方块及它们的对应改造属性的列表。<br>";
			$mode = 'command';
			return;
		}
		
		$dicesum = 0;
		foreach ($buff as $value)
		{
			$dicesum += $value[0];
		}
		if($dicesum < 100) $dicesum = 100;
		$dice=rand(1,$dicesum); $flag=0;
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
			eval(import_module('rage'));
			$rage += rand(5,15); $rage = min($rage,$max_rage);
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
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'gemming') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用{$b}为{$c}添加了{$d}属性！</span></li>";

		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
