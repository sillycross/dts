<?php

namespace skill182
{
	function init() 
	{
		define('MOD_SKILL182_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[182] = '共鸣';
	}
	
	function acquire182(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(182, 'skey', '', $pa);
		\skillbase\skill_setvalue(182, 'svalue', '', $pa);
		\skillbase\skill_setvalue(182, 'stime', '', $pa);
	}
	
	function lost182(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ss_record_tempbuff($key, $value, $bufftime, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$expire = $now + $bufftime;
		if (!\skillbase\skill_query(182, $pa)) \skillbase\skill_acquire(182, $pa);
		$skey = \skillbase\skill_getvalue(182, 'skey', $pa);
		$svalue = \skillbase\skill_getvalue(182, 'svalue', $pa);
		//此处的time是结束时间
		$stime = \skillbase\skill_getvalue(182, 'stime', $pa);
		if ('' === $skey)
		{
			$lskey = array();
			$lsvalue = array();
			$lstime = array();
		}
		else
		{
			$lskey = explode('_', $skey);
			$lsvalue = explode('_', $svalue);
			$lstime = explode('_', $stime);
		}
		$lskey[] = $key;
		$lsvalue[] = $value;
		$lstime[] = $expire;
		$skey = implode('_',$lskey);
		$svalue = implode('_',$lsvalue);
		$stime = implode('_',$lstime);
		\skillbase\skill_setvalue(182, 'skey', $skey, $pa);
		\skillbase\skill_setvalue(182, 'svalue', $svalue, $pa);
		\skillbase\skill_setvalue(182, 'stime', $stime, $pa);
		
		$s = "持续时间<span class=\"yellow b\">".$bufftime."</span>秒";
		return $s;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();		
		if (\skillbase\skill_query(182,$sdata))
		{
			$skey = \skillbase\skill_getvalue(182, 'skey', $sdata);
			if (!empty($skey))
			{
				$svalue = \skillbase\skill_getvalue(182, 'svalue', $sdata);
				$stime = \skillbase\skill_getvalue(182, 'stime', $sdata);
				$lskey = explode('_', $skey);
				$lsvalue = explode('_', $svalue);
				$lstime = explode('_', $stime);			
				$tmax = max($lstime) - $now;
				$z = array(
					'disappear' => 1,
				);
				if ($tmax > 0)
				{
					$z['clickable'] = 1;
					$z['style'] = 1;
					$z['totsec'] = $tmax;
					$z['nowsec'] = 0;
					$z['hint'] = show_songbuff_hint($lskey, $lsvalue);
				}
				else
				{
					$z['clickable'] = 0;
					$z['style'] = 3;
					$z['activate_hint'] = "歌唱效果已经结束";
				}
				\bufficons\bufficon_show('img/skill182.gif',$z);
			}		
		}
		$chprocess();
	}
	
	function show_songbuff_hint($lskey, $lsvalue)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$s = '';
		eval(import_module('sys','song'));
		foreach ($lskey as $i => $key)
		{
			if (!empty($key))
			{
				if ($lsvalue[$i] > 0) $o = '+';
				else $o ='';
				$s .= "{$ef_type[$key]}{$o}{$lsvalue[$i]} 剩<span class=\"yellow b\" id=\"songbuff{$i}\">{$uip['timing']['songbuff'.$i]['timing_r']}</span>秒<br>";
			}
		}
		return $s;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		if (\skillbase\skill_query(182,$pa))
		{
			$skey = \skillbase\skill_getvalue(182, 'skey', $pa);
			if (!empty($skey))
			{
				eval(import_module('sys'));
				$svalue = \skillbase\skill_getvalue(182, 'svalue', $pa);
				$stime = \skillbase\skill_getvalue(182, 'stime', $pa);
				$lskey = explode('_', $skey);
				$lsvalue = explode('_', $svalue);
				$lstime = explode('_', $stime);			
				$flag = 0;
				foreach ($lstime as $i => $time)
				{
					if ((int)$time < $now)
					{
						$flag = 1;
						$ek = $lskey[$i];
						$change = -(int)$lsvalue[$i];		
						if ($change > 0)
						{
							if (!isset($pa['m'.$ek]) || $pa[$ek] < $pa['m'.$ek])
							{
								if (isset($pa['m'.$ek]) && ($pa[$ek] + $change > $pa['m'.$ek])) $change = $pa['m'.$ek] - $pa[$ek];
								$pa[$ek] += $change;
							}
						}
						else
						{
							if ($pa[$ek] + $change < 1 && in_array($ek, array('hp','mhp','sp','msp'))) $change = 1 - $pa[$ek];
							elseif ($pa[$ek] + $change < 0) $change = -$pa[$ek];
							$pa[$ek] += $change;
						}
						unset($lskey[$i]);
						unset($lsvalue[$i]);
						unset($lstime[$i]);
					}
				}
				if ($flag)
				{
					$skey = implode('_',$lskey);
					$svalue = implode('_',$lsvalue);
					$stime = implode('_',$lstime);
					\skillbase\skill_setvalue(182, 'skey', $skey, $pa);
					\skillbase\skill_setvalue(182, 'svalue', $svalue, $pa);
					\skillbase\skill_setvalue(182, 'stime', $stime, $pa);
				}
			}
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		init_songbuff_timing_process();
	}
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($sn);
		init_songbuff_timing_process();
	}
	
	function init_songbuff_timing_process()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$skey = \skillbase\skill_getvalue(182, 'skey', $pa);
		if (!empty($skey))
		{
			$svalue = \skillbase\skill_getvalue(182, 'svalue', $pa);
			$stime = \skillbase\skill_getvalue(182, 'stime', $pa);
			$lskey = explode('_', $skey);
			$lstime = explode('_', $stime);
			foreach ($lskey as $i => $key)
			{
				if (!empty($key)) init_songbuff_timing($i, $lstime[$i] - $now);
			}
		}
	}

	function init_songbuff_timing($i, $rm){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if(!isset($uip['timing'])) $uip['timing'] = array();
		$timing_r = sprintf("%02d", floor($rm/60)).':'.sprintf("%02d", $rm%60);
		$uip['timing']['songbuff'.$i] = array(
			'on' => true,
			'mode' => 0,
			'timing' => $rm * 1000,
			'timing_r' => $timing_r,
			'format' => 'mm:ss'
		);
	}
	
}

?>