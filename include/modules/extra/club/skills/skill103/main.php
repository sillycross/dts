<?php

namespace skill103
{
	function init()
	{
		define('MOD_SKILL103_INFO','club;active;locked;');
		eval(import_module('clubbase'));
		$clubskillname[103] = '魂兵';
	}
	
	function acquire103(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(103, 'soulwep', '', $pa);
		\skillbase\skill_setvalue(103, 'exatt', 0, $pa);
		\skillbase\skill_setvalue(103, 'exsk', '', $pa);
	}
	
	function lost103(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(103, 'soulwep', $pa);
		\skillbase\skill_delvalue(103, 'exatt', $pa);
		\skillbase\skill_delvalue(103, 'exsk', $pa);
	}
	
	function check_unlocked103(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=20;
	}
	
	function skill103_encode_itmarr($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gencode($arr);
	}
	
	function skill103_decode_itmarr($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gdecode($str, 1);
	}
	
	function skill103_sendin($itmn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','player'));
		$theitem = array(${'itm'.$itmn}, ${'itmk'.$itmn}, ${'itme'.$itmn}, ${'itms'.$itmn}, ${'itmsk'.$itmn});
		
		$log .= "<span class=\"yellow b\">${'itm'.$itmn}</span>融入了你的身躯，化为了你的力量！<br>";
		\skillbase\skill_setvalue(103,'soulwep',skill103_encode_itmarr($theitem),$sdata);
		$exatt = max(round(${'itme'.$itmn} * 0.35), 1);
		\skillbase\skill_setvalue(103,'exatt',$exatt,$sdata);
		//把复合属性滤掉
		$itmsk_filtered = ${'itmsk'.$itmn};
		foreach (array('^ari','^alt','^atype','^hlog','^tlog','^su','^arn','^are','^ars','^st','^vol','^res','^rtype','^reptype') as $filsk)
		{
			$itmsk_filtered = \itemmain\replace_in_itmsk($filsk,'',$itmsk_filtered);
		}
		\skillbase\skill_setvalue(103,'exsk',$itmsk_filtered,$sdata);
		
		${'itm'.$itmn} = ${'itmk'.$itmn} = ${'itmsk'.$itmn} = '';
		${'itme'.$itmn} = ${'itms'.$itmn} = 0;
	}
	
	function cast_skill103()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(103,$sdata) || !check_unlocked103($sdata)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		$skill103_choice = get_var_input('skill103_choice');
		if (!empty($skill103_choice))
		{
			$z = (int)$skill103_choice;
			if ((1<=$z) && ($z<=6) && ${'itms'.$z} && (${'itmk'.$z}[0]=='W'))
			{
				skill103_sendin($z);
				$mode = 'command';
				return;
			}
			else
			{
				$log .= '参数不合法。<br>';
			}
		}
		include template(MOD_SKILL103_CASTSK103);
		$cmd=ob_get_contents();
		ob_end_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		if ($mode == 'special' && $command == 'skill103_special' && get_var_input('subcmd')=='castsk103') 
		{
			cast_skill103();
			return;
		}
		$chprocess();
	}
	
	function get_internal_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(103,$pa) && check_unlocked103($pa))
		{
			$attup = (int)\skillbase\skill_getvalue(103,'exatt',$pa);
			$ret += $attup;
		}
		return $ret;
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(103,$pa) && check_unlocked103($pa))
		{
			$exsk = \skillbase\skill_getvalue(103,'exsk',$pa);
			if (!empty($exsk)) $ret = array_merge($ret,\itemmain\get_itmsk_array($exsk));
		}
		return $ret;
	}
	
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(103,$pd) && check_unlocked103($pd))
		{
			$exsk = \skillbase\skill_getvalue(103,'exsk',$pd);
			if (!empty($exsk)) $ret = array_merge($ret,\itemmain\get_itmsk_array($exsk));
		}
		return $ret;
	}
	
}

?>