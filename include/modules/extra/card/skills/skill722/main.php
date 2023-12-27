<?php

namespace skill722
{
	$ragecost = 60;
	
	function init()
	{
		define('MOD_SKILL722_INFO','card;battle;');
		eval(import_module('clubbase'));
		$clubskillname[722] = '雪崩';
		if (defined('MOD_NOISE'))
		{
			eval(import_module('noise'));
			$noiseinfo['snowslip'] = '巨大的咆哮声';
		}
	}
	
	function acquire722(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost722(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked722(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_rage_cost722(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill722'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 722) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(722,$pa) || !check_unlocked722($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		else
		{
			$rcost = get_rage_cost722($pa);
			if ($pa['rage'] >= $rcost)
			{
				eval(import_module('logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你大声吼叫着，对{$pd['name']}发动了技能「雪崩」！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}大声吼叫着，对你发动了技能「雪崩」！</span><br>";
				if (defined('MOD_NOISE')) \noise\addnoise($pa['pls'],'snowslip',$pa['pid']);
				$pa['rage'] -= $rcost;
				addnews ( 0, 'bskill722', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log .= '怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill'] = 0;
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($pa['bskill'] == 722) && ($pa['is_hit'])) skill722_process($pa, $pd, $active);
		$chprocess($pa, $pd, $active);
	}
	
	function skill722_process(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if ($active)
			$log .= "<span class=\"lime b\">雪崩将你和{$pd['name']}淹没了！哇……这可真是……</span><br>";
		else $log .= "<span class=\"lime b\">雪崩将{$pa['name']}和你淹没了！哇……这可真是……</span><br>";
		skill722_process_single($pa);
		skill722_process_single($pd);
		if (!$pd['type']) $pd['battlelog'] .= "<span class=\"yellow b\">你被{$pa['name']}引起的雪崩淹没了！</span><br>";
	}
	
	function skill722_process_single(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		for ($i=1;$i<=6;$i++)
		{
			if ($pa['itm'.$i] != '雪')
			{
				if (!empty($pa['itms'.$i]))
				{
					$itmarr = implode(',', array($pa['itm'.$i], $pa['itmk'.$i], $pa['itme'.$i], $pa['itms'.$i], $pa['itmsk'.$i]));
					$pa['itmsk'.$i] = 'O^rtype1^res_'.\attrbase\base64_encode_comp_itmsk($itmarr).'1';
				}
				else $pa['itmsk'.$i] = 'O';
				$pa['itm'.$i] = '雪';
				$pa['itmk'.$i] = 'PB';
				if (rand(0,2) == 0) $pa['itmk'.$i] .= '2';
				$pa['itme'.$i] = rand(20,200);
				$pa['itms'.$i] = 1;
			}
		}
		if (\searchmemory\searchmemory_available())
		{
			eval(import_module('sys'));
			$mslotnum = \searchmemory\calc_memory_slotnum($pa);
			foreach ($pa['searchmemory'] as &$val)
			{
				if (!$val['unseen'] && isset($val['iid']))
				{
					$mid = $val['iid'];
					$mpls = $val['pls'];
					$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE iid = '$mid' AND pls = '$mpls'");
					$itemnum = $db->num_rows($result);
					if ($itemnum)
					{
						$nmarr = $db->fetch_array($result);
						if ($nmarr['itm'] != '雪')
						{
							$itmarr = implode(',', array($nmarr['itm'], $nmarr['itmk'], $nmarr['itme'], $nmarr['itms'], $nmarr['itmsk']));
							$nmitmsk = 'O^rtype1^res_'.\attrbase\base64_encode_comp_itmsk($itmarr).'1';
							$nmitm = '雪';
							$nmitmk = 'PB';
							$nmitme = rand(20,200);
							$db->query("UPDATE {$tablepre}mapitem SET itm='$nmitm',itmk='$nmitmk',itme='$nmitme',itms='1',itmsk='$nmitmsk' WHERE iid = '$mid'");
							$val['itm'] = '雪';
						}
					}
				}
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'bskill722')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「雪崩」</span></span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>