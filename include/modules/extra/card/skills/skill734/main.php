<?php

namespace skill734
{
	
	function init() 
	{
		define('MOD_SKILL734_INFO','card;active;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[734] = '打牌';
	}
	
	function acquire734(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost734(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked734(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill734_deal()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$rares = array_randompick(array('S','A','B','C'), 3);
		$rares = array_values($rares);
		$skill734_cards = array();
		$skill734_cards[1]['cid'] = array_randompick($cardindex[$rares[0]]);
		$skill734_cards[2]['cid'] = array_randompick($cardindex[$rares[1]]);
		$skill734_cards[3]['cid'] = array_randompick($cardindex[$rares[2]]);
		
		$arr = array_merge($cardindex['S'],$cardindex['A'],$cardindex['B'],$cardindex['C'],$cardindex['EB']);
		$arr = array_diff($arr, array($skill734_cards[1]['cid'], $skill734_cards[2]['cid'], $skill734_cards[3]['cid']));
		
		$cards_r = array_randompick($arr, 2);
		$cards_r = array_values($cards_r);
		$skill734_cards[4]['cid'] = $cards_r[0];
		$skill734_cards[5]['cid'] = $cards_r[1];
		
		$dice = rand(1,5);
		$skill734_cards[$dice]['open'] = 1;
		
		return $skill734_cards;
	}
	
	function skill734_compare($cards1, $cards2)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$score = array();
		for ($i=0; $i<3; $i++)
		{
			$score[] = skill734_compare_rare($cards[$cards1[$i]]['rare'], $cards[$cards2[$i]]['rare']);
		}
		return $score;
	}
	
	function skill734_compare_rare($rare1, $rare2)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($rare1 == $rare2) return 0;
		if (($rare1 == 'S') && in_array($rare2, array('A','B','C'))) return 1;
		if (($rare1 == 'A') && in_array($rare2, array('B','C','M'))) return 1;
		if (($rare1 == 'B') && in_array($rare2, array('C','M'))) return 1;
		if (($rare1 == 'C') && ($rare2 == 'M')) return 1;
		if (($rare1 == 'M') && ($rare2 == 'S')) return 1;
		return -1;
	}
	
	function skill734_play(&$pa, &$pd=null)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		$pa_step = (int)\skillbase\skill_getvalue(734,'step',$pa);
		if (empty($pa_step))
		{
			$pa_cards = skill734_deal();
			\skillbase\skill_setvalue(734,'cards',gencode($pa_cards),$pa);
			if (empty($pd))
			{
				$pd_cards = skill734_deal();
				\skillbase\skill_setvalue(734,'cards_op',gencode($pd_cards),$pa);
			}
			\skillbase\skill_setvalue(734,'step',1,$pa);
			ob_start();
			include template(MOD_SKILL734_PLAYCARD);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		elseif ($pa_step == 1 || $pa_step == 2)
		{
			$card1 = (int)get_var_input('skill734_card1');
			$card2 = (int)get_var_input('skill734_card2');
			$card3 = (int)get_var_input('skill734_card3');
			if ($card1 < 1 || $card1 > 5 || $card2 < 1 || $card2 > 5 || $card3 < 1 || $card3 > 5 || $card1 == $card2 || $card1 == $card3 || $card2 == $card3)
			{
				$log .= '输入参数有误。';
				return;
			}
			$card_played = array($card1, $card2, $card3);
			$pa_cards = gdecode(\skillbase\skill_getvalue(734,'cards',$pa), 1);
			if ($pa_step == 1)
			{
				$pa_cards[$card1]['open'] = 1;
				foreach ($card_played as $v)
				{
					$pa_cards[$v]['played'] = 1;
				}
				$pa_cards[$card1]['open'] = 1;
				$arr = array_diff(range(1,5), $card_played);
				foreach ($arr as $v)
				{
					$pa_cards[$v]['open'] = 1;
				}
				\skillbase\skill_setvalue(734,'cards',gencode($pa_cards),$pa);
				if (empty($pd))
				{
					$pd_cards = gdecode(\skillbase\skill_getvalue(734,'cards_op',$pa), 1);
					foreach (range(1,3) as $v)
					{
						$pd_cards[$v]['played'] = 1;
					}
					$pd_cards[1]['open'] = 1;
					foreach (range(4,5) as $v)
					{
						$pd_cards[$v]['open'] = 1;
					}
					\skillbase\skill_setvalue(734,'cards_op',gencode($pd_cards),$pa);
				}
				\skillbase\skill_setvalue(734,'step',2,$pa);
				ob_start();
				include template(MOD_SKILL734_PLAYCARD);
				$cmd=ob_get_contents();
				ob_end_clean();
			}
			elseif ($pa_step == 2)
			{
				if (empty($pd))
				{
					$pd_cards = gdecode(\skillbase\skill_getvalue(734,'cards_op',$pa), 1);
				}
				if (!isset($pa_cards[$card1]['played']) || !isset($pa_cards[$card2]['played']) || !isset($pa_cards[$card3]['played']))
				{
					$log .= '输入参数有误。';
					return;
				}
				$cards1 = array($pa_cards[$card1]['cid'], $pa_cards[$card2]['cid'], $pa_cards[$card3]['cid']);
				$cards2 = array($pd_cards[1]['cid'], $pd_cards[2]['cid'], $pd_cards[3]['cid']);
				$score = skill734_compare($cards1, $cards2);
				$scoresum = array_sum($score);
				
				eval(import_module('cardbase'));				
				for ($i=0; $i<3; $i++)
				{
					$k = $i + 1;
					$log .= "第{$k}张牌：你出牌<span class=\"{$card_rarecolor[$cards[$cards1[$i]]['rare']]}\">【{$cards[$cards1[$i]]['name']} {$cards[$cards1[$i]]['rare']}】</span>，对手出牌<span class=\"{$card_rarecolor[$cards[$cards2[$i]]['rare']]}\">【{$cards[$cards2[$i]]['name']} {$cards[$cards2[$i]]['rare']}】</span>，得分为<span class=\"yellow b\">{$score[$i]}</span>。<br>";
				}
				if ($scoresum > 0) $log .= "<span class=\"yellow b\">你赢了！</span><br>";
				elseif ($scoresum < 0) $log .= "<span class=\"yellow b\">你输了！</span><br>";
				else $log .= "<span class=\"yellow b\">居然是平局？</span><br>";
				\skillbase\skill_setvalue(734,'step',0,$pa);
				return;
			}
		}
		else
		{
			$log .= '输入参数有误。';
			return;
		}
	}
	
	function cast_skill734()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(734, $sdata)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$skill734_opid = (int)get_var_input('skill734_opid');
		if (!empty($skill734_opid))
		{
			if ($skill734_opid == 1000) skill734_play($sdata);
			else //未完成
			{
				$log .= '输入参数有误。';
				return;
			}
		}
		else
		{
			ob_start();
			include template(MOD_SKILL734_CASTSK734);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill734_special') 
		{
			cast_skill734();
			return;
		}
		
		$chprocess();
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'playcard734')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}与{$b}打牌，{$c}！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>