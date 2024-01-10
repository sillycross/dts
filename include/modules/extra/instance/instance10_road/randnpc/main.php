<?php

namespace randnpc
{
	function init()
	{
		eval(import_module('player'));
		$typeinfo[51] = '类别1';
		$typeinfo[52] = '类别2';
		$typeinfo[53] = '类别3';
		$typeinfo[54] = '类别4';
		$typeinfo[55] = '类别5';
		$typeinfo[56] = '类别6';
		$typeinfo[57] = '类别7';
		$typeinfo[58] = '类别8';
		$typeinfo[59] = '类别9';
		$typeinfo[60] = '类别10';
	}
	
	//生成若干个标准格式的随机NPC
	//rank：NPC的强度等级，为1-20，1级为杂兵，20级强于115
	//num：生成数量
	//offens_tend：攻击倾向（0-100整数），越高NPC越容易有高攻击力和熟练度，越容易出现强袭姿态和重视反击
	//defens_tend：防御倾向（0-100整数），越高NPC越容易有高生命值和防御力，越容易出现作战姿态和重视防御
	//variety：变化范围（0-50整数），越高则随机属性的变化范围越大
	//use_preset：是否基于预设生成
	function generate_randnpc($rank, $num=1, $offens_tend=0, $defens_tend=0, $variety=0, $use_preset=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$randnpcs = array();
		for ($i=0; $i<$num; $i++)
		{
			$randnpcs[] = generate_single_randnpc($rank, $offens_tend, $defens_tend, $variety, $use_preset);
		}
		return $randnpcs;
	}
	
	function generate_single_randnpc($rank, $offens_tend=0, $defens_tend=0, $variety=0, $use_preset=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//生成npc基础属性
		if ($use_preset)
		{
			eval(import_module('randnpc'));
			$npc = array_randompick($randnpc_presets[$rank]);
		}
		else
		{
			eval(import_module('npc'));
			$npc = $npcinit;
			//生成名字，待完善
			$npc['name'] = '幻境实验体'.$rank.'型';
			if (rand(0,1)) $npc['gd'] = 'f';
			$var1 = pow(2, $rank);
			$var2 = pow(1.5, $rank);
			$npc['mhp'] = $var1 * 300;
			$npc['msp'] = $rank * 100;
			$npc['att'] = $npc['def'] = round($var2 * 100);
			$npc['skill'] = round($var2 * 40);
			$npc['lvl'] = $rank * 5;
			$npc['money'] = array(20,40,60,80,120,160,220,300,420,560,720,900,1160,1500,1920,2440,3080,3840,4800,6000)[$rank];
			//武器
			if ($rank > 12) $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD','WB','WP','WK','WC','WG','WF','WD','WB','WJ'));
			elseif ($rank > 8) $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD','WB'));
			else $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD'));
			list($npc['wep'], $npc['wepsk']) = generate_randnpc_item($npc['wepk']);
			$npc['wepe'] = max($rank*10, $var1) * 5; $npc['weps'] = $rank * 50;
			//防具
			$npc['arbk'] = 'DB'; $npc['arhk'] = 'DH'; $npc['arfk'] = 'DF'; $npc['arak'] = 'DA';
			list($npc['arb'], $npc['arbsk']) = generate_randnpc_item('DB');
			list($npc['arh'], $npc['arhsk']) = generate_randnpc_item('DH');
			list($npc['arf'], $npc['arfsk']) = generate_randnpc_item('DF');
			list($npc['ara'], $npc['arask']) = generate_randnpc_item('DA');
			$npc['arbe'] = round($var2 * 15);
			$npc['arhe'] = $npc['arfe'] = $npc['arae'] = round($var2 * 10);
			$npc['arbs'] = $npc['arhs'] = $npc['arfs'] = $npc['aras'] = $rank * 50;
			//饰品
			list($npc['arte'], $npc['artsk']) = generate_randnpc_item('A');
			$npc['artk'] = 'A'; $npc['arte'] = 1; $npc['arts'] = 1;
		}
		//属性调整
		$npc['mhp'] = round($npc['mhp'] * rand(80-$variety+$defens_tend,120+$variety+$defens_tend) / 100);
		$npc['msp'] = round($npc['msp'] * rand(80-$variety,120+$variety) / 100);
		$npc['att'] = round($npc['att'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
		$npc['club'] = array_randompick(array(1,2,3,4,5,6,7,8,9,10,11,13,14,17,18,19,20,21,24));
		$npc['pose'] = array_randompick(array(0,1,4));
		if (rand(0,99) < $offens_tend + 3*$rank) $npc['pose'] = 2;
		$npc['tactic'] = array_randompick(array(0,2,3,4));
		if (rand(0,99) < $defens_tend) $npc['tactic'] = 2;
		$npc['pls'] = 99;
		$npc['skill'] = round($npc['skill'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
		$npc['lvl'] = min($npc['lvl'] + rand(-5,5), 1);
		$npc['money'] = round($npc['money'] * rand(80-$variety,120+$variety) / 100);
		//装备调整
		if ($npc['club']==19) //铁拳
		{
			$npc['att'] += 2 * round($npc['wepe'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
			$npc['wep'] = '拳头';
			$npc['wepk'] = 'WN';
			$npc['wepe'] = 0; $npc['weps'] = '∞';
			$npc['wepsk'] = '';
		}
		else
		{
			$npc['wepe'] = round($npc['wepe'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
			$npc['weps'] = round($npc['weps'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
			
			//生成武器属性，未完成
			$npc['wepsk'] = 'u';
		}
		$npc['arbe'] = round($npc['arbe'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
		$npc['arhe'] = $npc['arfe'] = $npc['arae'] = round($npc['arhe'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
		$npc['arbs'] = $npc['arhs'] = $npc['arfs'] = $npc['aras'] = round($npc['arbs'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
		
		//生成防御属性，未完成
		$npc['arbsk'] = 'A';
		$npc['arhsk'] = 'a';
		$npc['arfsk'] = 'H';
		$npc['arask'] = 'M';
		$npc['artsk'] = 'h';
		
		//添加技能，未完成
		$npc['skills'][] = array('400'=>'1');
		
		var_dump($npc);
		return $npc;
	}
	
	function generate_randnpc_item($itmk, $randname=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('randnpc'));
		$itm = '';
		$itmsk = '';
		if ($randname)
		{
			if (isset($randnpc_item_randname[$itmk])) $itm = array_randompick($randnpc_item_randname['prefix']).array_randompick($randnpc_item_randname[$itmk]);
		}
		else
		{
			if (isset($randnpc_item[$itmk]))
			{
				$r = array_randompick($randnpc_item[$itmk]);
				if (isset($r[0])) $itm = $r[0];
				if (isset($r[1])) $itmsk = $r[1];
			}
		}
		return [$itm, $itmsk];
	}
	
	function add_randnpc($rank, $num=1, $offens_tend=0, $defens_tend=0, $variety=0, $use_preset=1) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','addnpc'));
		$randnpcs = generate_randnpc($rank, $num, $offens_tend, $defens_tend, $variety, $use_preset);
		if (empty($randnpcs)) return;
		$pls_available = \map\get_safe_plslist();
		$summon_ids = array();
		for($i=0;$i<$num;$i++)
		{
			$npc = $randnpcs[$i];
			$npc['type'] = 50 + ceil($rank / 2);
			$npc['sNo'] = $i;
			$npc = \npc\init_npcdata($npc,$pls_available);
			$npc = \player\player_format_with_db_structure($npc);
			$db->array_insert("{$tablepre}players", $npc);
			$summon_ids[] = $db->insert_id();
			$newsname = $typeinfo[$npc['type']].' '.$npc['name'];
			addnews($now, 'addnpc', $newsname);
		}
		return $summon_ids;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0)
		{	
			if ($itm == '测试用NPC召唤器')
			{
				$log .= "使用了<span class=\"yellow b\">$itm</span>。<br>";
				$rank = 7;
				add_randnpc($rank, 3, 0, 0, 0, 0);
				return;
			}
		}
		$chprocess($theitem);
	}
	
}

?>