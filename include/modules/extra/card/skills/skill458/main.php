<?php

namespace skill458
{
	function init() 
	{
		define('MOD_SKILL458_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[458] = '鸡肉';
	}
	
	function acquire458(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost458(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked458(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//转化物品为补给
	//这个函数不检查输入合法性
	function sk458_convert_item($which)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (${'itms'.$which}==$nosta) ${'itms'.$which}=10;	//无限耐久变10
		${'itmk'.$which}='PB2';		//转化为剧毒补给
		${'itmsk'.$which}=$pid;		//下毒者是自己
		$z=${'itm'.$which};
		${'itm'.$which}='鸡肉';	
		$log.="你熟练的把<span class=\"yellow b\">{$z}</span>去掉了头，现在它可以吃了！<br>";
	}
	
	function cast_skill458()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(458)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		$skill458_choice = get_var_input('skill458_choice');
		if (!empty($skill458_choice))
		{
			$z=(int)$skill458_choice;
			if (1<=$z && $z<=6 && ${'itms'.$z} && ${'itmk'.$z}[0]!='H' && ${'itmk'.$z}[0]!='P')
			{
				sk458_convert_item($z);
				$mode='command';
				return;
			}
			else
			{
				$log.='参数不合法。<br>';
			}
		}
		include template(MOD_SKILL458_CASTSK458);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	//无视有毒补给
	//module dep里包含edible与poison模块，以保证本部分先于相应逻辑执行
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(458) && $theitem['itmk'][0]=='P')
		{
			$theitem['itmk'][0]='H';
			$itm_temp = $theitem['itm'];
			$itmk_temp = $theitem['itmk'];
			$ret=$chprocess($theitem);
			if (($theitem['itm'] == $itm_temp) && ($theitem['itmk'] == $itmk_temp)) $theitem['itmk'][0]='P';
			return $ret;
		}
		else	return $chprocess($theitem);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill458_special' && get_var_input('subcmd')=='castsk458') 
		{
			cast_skill458();
			return;
		}
			
		$chprocess();
	}
}

?>