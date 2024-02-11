<?php

namespace skill737
{
	function init()
	{
		define('MOD_SKILL737_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[737] = '雕花';
	}
	
	function acquire737(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost737(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked737(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function cast_skill737()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(737,$sdata) || !check_unlocked737($sdata)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		$skill737_choice = get_var_input('skill737_choice');
		if (!empty($skill737_choice))
		{
			$z = (int)$skill737_choice;
			if ((1<=$z) && ($z<=6) && ${'itms'.$z} && (${'itmk'.$z}[0]=='H' || ${'itmk'.$z}[0]=='P'))
			{
				if (strpos(${'itm'.$z}, '雏菊') !== false)
				{
					$log .= '这已经是一朵花了，不能继续雕花。<br>';
					return;
				}
				elseif ((strlen(${'itmsk'.$z}) > 233) || \itemmain\check_in_itmsk('^alt', ${'itmsk'.$z}))
				{
					$log .= '这个道具的成分过于复杂，没法在上面雕花。<br>';
					return;
				}
				skill737_item_process($z);
				$mode = 'command';
				return;
			}
			else
			{
				$log .= '参数不合法。<br>';
			}
		}
		include template(MOD_SKILL737_CASTSK737);
		$cmd=ob_get_contents();
		ob_end_clean();
	}
	
	function skill737_item_process($itmn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','player'));
		$log .= "在你精妙的刀工下，<span class=\"yellow b\">{${'itm'.$itmn}}</span>变成了一朵栩栩如生的<span class=\"yellow b\">黄色雏菊</span>！<br>";
		$itmarr = implode(',', array(${'itm'.$itmn}, ${'itmk'.$itmn}, ${'itme'.$itmn}, ${'itms'.$itmn}, ${'itmsk'.$itmn}));
		${'itmsk'.$itmn} = 'z^rtype4^reptype1^res_'.\attrbase\base64_encode_comp_itmsk($itmarr).'1';
		${'itm'.$itmn} = '黄色雏菊';
		${'itmk'.$itmn} = 'HM';
		${'itme'.$itmn} = 120;
		${'itms'.$itmn} = 1;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($mode == 'special' && $command == 'skill737_special' && get_var_input('subcmd')=='castsk737') 
		{
			cast_skill737();
			return;
		}
		$chprocess();
	}
	
}

?>