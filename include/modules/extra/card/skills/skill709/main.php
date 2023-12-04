<?php

namespace skill709
{
	$ragecost = 30;
	$skill709_fakeitem = array('礼帽','礼貌','狸帽');
	$skill709_resitem = array('黑磨刀石,Y,25,1,,','岩石,WC,5,14,O,');
	
	function init() 
	{
		define('MOD_SKILL709_INFO','card;battle;');
		eval(import_module('clubbase'));
		$clubskillname[709] = '调换';
		$itemspkinfo['^wepflag'] = '调换武器标记';//不显示
	}
	
	function acquire709(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost709(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked709(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl'] >= 6;
	}
	
	function get_rage_cost709(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill709'));
		return $ragecost;
	}
	
	function check_battle_skill_unactivatable(&$ldata, &$edata, $skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata, $edata, $skillno);
		if (709 == $skillno && 0 == $ret)
		{
			if ($edata['type'] > 0) $ret = 8;
		}
		return $ret;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 709) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(709,$pa) || !check_unlocked709($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		else if (0 == $pd['type'])
		{
			$rcost = get_rage_cost709($pa);
			if ($pa['rage'] >= $rcost)
			{
				eval(import_module('logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「调换」！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「调换」！</span><br>";
				$pa['rage'] -= $rcost;
				addnews ( 0, 'bskill709', $pa['name'], $pd['name'] );
			}
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
		$chprocess($pa, $pd, $active);
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($pa['bskill'] == 709) && ($pa['is_hit'])) skill709_process($pa, $pd, $active);
		$chprocess($pa, $pd, $active);
	}
	
	function skill709_process(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if ('WN' == $pd['wepk'])
		{
			if ($active)
				$log .= "<span class=\"lime b\">但是对手两手空空，你的戏法失败了！</span><br>";
			else $log .= "<span class=\"lime b\">但是你两手空空，{$pa['name']}的戏法失败了！</span><br>";
		}
		else
		{
			if ($active)
				$log .= "<span class=\"lime b\">你用戏法变走了{$pd['name']}的武器，然后将其藏到了三顶礼帽中！</span><br>";
			else $log .= "<span class=\"lime b\">{$pa['name']}用戏法变走了你的武器，然后将其藏到了三顶礼帽中！</span><br>";
			eval(import_module('weapon','skill709'));
			if (\searchmemory\searchmemory_available())
			{
				$skill709_itmsk = array();
				$weparr = implode(',', array($pd['wep'], $pd['wepk'], $pd['wepe'], $pd['weps'], $pd['wepsk']));
				$skill709_itmsk[] = '^wepflag709^rtype4^reptype1^res_'.\attrbase\base64_encode_comp_itmsk($weparr).'1';
				$skill709_itmsk[] = '^rtype4^reptype1^res_'.\attrbase\base64_encode_comp_itmsk($skill709_resitem[0]).'1';
				$skill709_itmsk[] = '^rtype4^reptype1^res_'.\attrbase\base64_encode_comp_itmsk($skill709_resitem[1]).'1';
				shuffle($skill709_itmsk);//仪式感
				for ($i=0;$i<3;$i++)
				{
					$fakeitm = $skill709_fakeitem[rand(0,2)];
					$dropid = \itemmain\itemdrop_query($fakeitm, 'DH', 1, 1, $skill709_itmsk[$i], $pd['pls']);
					$amarr = array('iid' => $dropid, 'itm' => $fakeitm, 'pls' => $pd['pls'], 'unseen' => 0);
					\skill1006\add_beacon($amarr, $pd);
					\player\player_save($pd);
				}
				$pd['wep'] = $nowep;
				$pd['wepk'] = 'WN';
				$pd['wepe'] = 0;
				$pd['weps'] = $nosta;
				$pd['wepsk'] = '';
				$pd['battlelog'] .= "<span class=\"yellow b\">{$pa['name']}用戏法将你的武器藏到了三顶礼帽中！</span><br>";
			}
		}
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (709 === (int)\itemmain\check_in_itmsk('^wepflag', $theitem['itmsk']))
		{
			eval(import_module('logger'));
			$temp_log = $log;
			$chprocess($theitem);
			$log = $temp_log;
			$log .= '你找回了你丢失的武器。<br>';
		}
		else $chprocess($theitem);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'bskill709')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「调换」</span></span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if('^wepflag' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
}

?>