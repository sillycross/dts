<?php

namespace randnpc
{
	function init()
	{
		eval(import_module('player'));
		$typeinfo[51] = '实验体I型';
		$typeinfo[52] = '实验体II型';
		$typeinfo[53] = '实验体III型';
		$typeinfo[54] = '实验体IV型';
		$typeinfo[55] = '实验体V型';
		$typeinfo[56] = '实验体E型';
		$typeinfo[57] = '实验体D型';
		$typeinfo[58] = '实验体C型';
		$typeinfo[59] = '实验体B型';
		$typeinfo[60] = '实验体A型';
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
			//生成名字，待修改
			$npc['name'] = $rank.'级虚像';
			if (rand(0,1)) $npc['gd'] = 'f';
			$var1 = pow(2, $rank);
			$var2 = pow(1.5, $rank);
			$npc['mhp'] = $var1 * 300;
			$npc['msp'] = $rank * 100;
			$npc['att'] = $npc['def'] = round($var2 * 100);
			$npc['skill'] = round($var2 * 40);
			$npc['lvl'] = $rank * 5;
			$npc['money'] = array(20,40,60,80,120,160,220,300,420,560,720,900,1160,1500,1920,2440,3080,3840,4800,6000)[$rank-1];
			//武器
			if ($rank > 12) $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD','WB','WP','WK','WC','WG','WF','WD','WB','WJ'));
			elseif ($rank > 8) $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD','WB'));
			else $npc['wepk'] = array_randompick(array('WP','WK','WC','WG','WF','WD'));
			list($npc['wep'], $npc['wepsk']) = generate_randnpc_item($npc['wepk'], 1);
			$npc['wepe'] = max($rank*10, $var1) * 5; $npc['weps'] = $rank * 50;
			//防具
			$npc['arbk'] = 'DB'; $npc['arhk'] = 'DH'; $npc['arfk'] = 'DF'; $npc['arak'] = 'DA';
			list($npc['arb'], $npc['arbsk']) = generate_randnpc_item('DB', 1);
			list($npc['arh'], $npc['arhsk']) = generate_randnpc_item('DH', 1);
			list($npc['arf'], $npc['arfsk']) = generate_randnpc_item('DF', 1);
			list($npc['ara'], $npc['arask']) = generate_randnpc_item('DA', 1);
			$npc['arbe'] = round($var2 * 15);
			$npc['arhe'] = $npc['arfe'] = $npc['arae'] = round($var2 * 10);
			$npc['arbs'] = $npc['arhs'] = $npc['arfs'] = $npc['aras'] = $rank * 50;
			//饰品
			list($npc['art'], $npc['artsk']) = generate_randnpc_item('A', 1);
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
			
			//生成武器属性
			$npc['wepsk'] = generate_randnpc_itmsk($rank, $npc['wepk'], $npc['wepsk']);
		}
		$npc['arbe'] = round($npc['arbe'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
		$npc['arhe'] = $npc['arfe'] = $npc['arae'] = round($npc['arhe'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
		$npc['arbs'] = $npc['arhs'] = $npc['arfs'] = $npc['aras'] = round($npc['arbs'] * rand(80-$variety+$offens_tend,120+$variety+$offens_tend) / 100);
		
		//生成防具属性
		$npc['arbsk'] = generate_randnpc_itmsk($rank, 'DB', $npc['arbsk']);
		$npc['arhsk'] = generate_randnpc_itmsk($rank, 'DH', $npc['arhsk']);
		$npc['arfsk'] = generate_randnpc_itmsk($rank, 'DF', $npc['arfsk']);
		$npc['arask'] = generate_randnpc_itmsk($rank, 'DA', $npc['arask']);
		
		//生成饰品属性
		$npc['artsk'] = generate_randnpc_itmsk($rank, 'A', $npc['artsk']);
		
		//添加技能
		if (!isset($npc['skills'])) $npc['skills'] = array();
		$npc['skills'] = generate_randnpc_skills($rank, $npc['skills']);
		
		return $npc;
	}
	
	//生成随机道具，$randname为1则仅生成道具名但不生成属性，$randname为0则从随机道具池中挑选道具并带有相应的属性
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
	
	//生成随机道具的属性
	function generate_randnpc_itmsk($rank, $itmk, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty($itmsk)) $itmsk_arr = \itemmain\get_itmsk_array($itmsk);
		else $itmsk_arr = array();
		if ($itmk[0] == 'W')
		{
			$min_itmsk_count = array(0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 5, 6, 8)[$rank-1];
			$max_itmsk_count = array(1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 8, 10)[$rank-1];
		}
		else
		{
			$min_itmsk_count = array(0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 4)[$rank-1];
			$max_itmsk_count = array(1, 1, 1, 2, 2, 2, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 5, 6)[$rank-1];
		}
		$guarant_rate = 25 * floor(($rank - 1) / 5);
		eval(import_module('randnpc'));
		$r = ceil($rank / 5);
		
		if ($r <= 1) $skpool = $randnpc_itmsk[$itmk[0]][1];
		else $skpool = array_merge($randnpc_itmsk[$itmk[0]][$r], $randnpc_itmsk[$itmk[0]][$r-1]);
		if (rand(0,99) < $guarant_rate) $itmsk_arr[] = array_randompick($randnpc_itmsk[$itmk[0]][$r]);
		$sk_count = rand($min_itmsk_count, $max_itmsk_count);
		if ($sk_count > 1) $itmsk_arr = array_merge($itmsk_arr, array_randompick($skpool, $sk_count));
		elseif ($sk_count == 1) $itmsk_arr[] = array_randompick($skpool);
		$itmsk_arr = array_unique($itmsk_arr);
		$itmsk = implode('', $itmsk_arr);
		return $itmsk;
	}
	
	//生成随机技能
	function generate_randnpc_skills($rank, $skills)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('randnpc'));
		$min_skills_count = array(0, 0, 0, 0, 1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 6, 9)[$rank-1];
		$max_skills_count = array(1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 8, 9, 12)[$rank-1];
		
		$skills_count = rand($min_skills_count, $max_skills_count);
		$r = max(ceil($rank / 3) - 1, 1);
		$k = array_rand($randnpc_skills[$r]);
		$skills[$k] = $randnpc_skills[$r][$k];
		
		for ($i=0;$i<$skills_count;$i++)
		{
			$skr = rand(max($r-2, 1), $r);
			$k = array_rand($randnpc_skills[$skr]);
			$skills[$k] = $randnpc_skills[$skr][$k];
		}
		return $skills;
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
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0)
		{	
			if ($itm == '测试用NPC召唤器')
			{
				$log .= "使用了<span class=\"yellow b\">$itm</span>。<br>";
				$rank = rand(1,20);
				add_randnpc($rank, 3, 0, 0, 0, 0);
				return;
			}
		}
		$chprocess($theitem);
	}
	
}

?>