<?php

namespace skill104
{
	$upgradecost = array(8,-1);
	$cubemax = array(3,5);
	
	function init()
	{
		define('MOD_SKILL104_INFO','club;active;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[104] = '晶环';
		$clubdesc_h[20] = '可吸收「方块」道具在战斗中获得额外攻击属性<br>'.$clubdesc_h[20];
	}
	
	function acquire104(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(104,'lvl','0',$pa);
		\skillbase\skill_setvalue(104,'itmarr','',$pa);
	}
	
	function lost104(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(104,'lvl',$pa);
		\skillbase\skill_delvalue(104,'itmarr',$pa);
	}
	
	function check_unlocked104(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade104()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(104,$sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		eval(import_module('skill104'));
		$clv = (int)\skillbase\skill_getvalue(104,'lvl',$sdata);
		$ucost = $upgradecost[$clv];
		if ($ucost == -1)
		{
			$log .= '该技能已升级完成，不能继续升级！<br>';
			return;
		}
		if ($skillpoint < $ucost)
		{
			$log .= '技能点不足。<br>';
			return;
		}
		$skillpoint -= $ucost;
		\skillbase\skill_setvalue(104,'lvl',$clv+1,$sdata);
		$log .= '升级成功。<br>';
	}
	
	function skill104_encode_itmarr($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gencode($arr);
	}
	
	function skill104_decode_itmarr($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gdecode($str, 1);
	}
	
	function skill104_prepare_itmarr(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(104, $pa)) 
		{
			$log .= '你没有这个技能。<br>';
			return Array();
		}
		$ret = \skillbase\skill_getvalue(104, 'itmarr', $pa);
		if(!empty($ret)) {
			$ret = skill104_decode_itmarr($ret);
		}else{
			$ret = Array();
		}
		return $ret;
	}
	
	function skill104_sendin($itmn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill23','logger','player'));
		if(!isset($bufflist23[${'itm'.$itmn}]))
		{
			$log .= "你的物品不是有效的宝石或方块。<br>请参阅帮助获得所有有效的宝石或方块及它们的对应改造属性的列表。<br>";
			$mode = 'command';
			return;
		}
		$theitem = Array();
		$theitem['itm'] = ${'itm'.$itmn};
		$theitem['itmk'] = ${'itmk'.$itmn};
		$theitem['itme'] = ${'itme'.$itmn};
		$theitem['itms'] = ${'itms'.$itmn};
		$theitem['itmsk'] = ${'itmsk'.$itmn};
		
		eval(import_module('skill104'));
		$clv = (int)\skillbase\skill_getvalue(104,'lvl',$sdata);
		$cmax = $cubemax[$clv];
		$skill104_itmarr = skill104_prepare_itmarr($sdata);
		$skill104_itmarr[] = $theitem;
		$skill104_nowcount = sizeof($skill104_itmarr);
		
		$log .= "你吸收了<span class=\"yellow b\">{$theitem['itm']}</span>。<br>";
		if ($skill104_nowcount > $cmax)
		{
			if (rand(0,99) < 85)
			{
				${'itm'.$itmn} = $skill104_itmarr[0]['itm'];
				${'itmk'.$itmn} = $skill104_itmarr[0]['itmk'];
				${'itme'.$itmn} = $skill104_itmarr[0]['itme'];
				${'itms'.$itmn} = $skill104_itmarr[0]['itms'];
				${'itmsk'.$itmn} = $skill104_itmarr[0]['itmsk'];
				$log .= "<span class=\"yellow b\">${'itm'.$itmn}</span>回到了包裹中。<br>";
			}
			else
			{
				$log .= "<span class=\"yellow b\">{$skill104_itmarr[0]['itm']}</span>化为光芒消失了。<br>";
				${'itm'.$itmn} = ${'itmk'.$itmn} = ${'itmsk'.$itmn} = '';
				${'itme'.$itmn} = ${'itms'.$itmn} = 0;
			}
			unset($skill104_itmarr[0]);
			$skill104_itmarr = array_values($skill104_itmarr);
		}
		else
		{
			${'itm'.$itmn} = ${'itmk'.$itmn} = ${'itmsk'.$itmn} = '';
			${'itme'.$itmn} = ${'itms'.$itmn} = 0;
		}
		\skillbase\skill_setvalue(104,'itmarr',skill104_encode_itmarr($skill104_itmarr),$sdata);
	}
	
	function cast_skill104()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(104, $sdata) || !check_unlocked104($sdata)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$skill104_choice = get_var_input('skill104_choice');
		if (!empty($skill104_choice))
		{
			$z = (int)$skill104_choice;
			if ((1<=$z) && ($z<=6) && ${'itms'.$z})
			{
				skill104_sendin($z);
				$mode = 'command';
				return;
			}
			else
			{
				$log .= '参数不合法。<br>';
			}
		}
		include template(MOD_SKILL104_CASTSK104);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill104_special' && get_var_input('subcmd')=='castsk104') 
		{
			cast_skill104();
			return;
		}
		$chprocess();
	}
	
	function get_skill104_actrate(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 15;
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(104,$pa) && check_unlocked104($pa))
		{
			if(!isset($pa['skill104_tmpsk']))
			{
				$pa['skill104_tmpsk'] = array();
				$skill104_actrate = get_skill104_actrate($pa);
				$itmarr = skill104_prepare_itmarr($pa);
				if ($itmarr)
				{
					$used_cube = array();
					eval(import_module('skill23'));
					foreach ($itmarr as $v)
					{
						if ((rand(0,99) < $skill104_actrate) && isset($bufflist23[$v['itm']]))
						{
							$tsk = array_randompick($bufflist23[$v['itm']]['W'])[1];
							$pa['skill104_tmpsk'][] = $tsk;
							$used_cube[] = $v['itm'];
						}
					}
					if (!empty($used_cube))
					{
						$cube_count = count($used_cube);
						if ($cube_count > 1) $cube_txt = implode('、', array_slice($used_cube, 0, $cube_count - 1)).'和'.end($used_cube);
						else $cube_txt = $used_cube[0];
						eval(import_module('logger'));
						if ($active) $log .= "<span class=\"lime b\">你的{$cube_txt}发出了光芒！</span><br>";
						else $log .= "<span class=\"lime b\">{$pa['name']}的{$cube_txt}发出了光芒！</span><br>";
					}
				}
			}
			$ret = array_merge($ret,$pa['skill104_tmpsk']);
		}
		return $ret;
	}
	
}

?>