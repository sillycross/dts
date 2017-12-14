<?php

namespace attrbase
{
	function init() {}
	
	//下面这两个获取属性的函数规则如下：
	//添加：请在get_ex_XXX_array_core()里使用array_push()
	//删除/改变：请在get_ex_XXX_array()里删除或者直接赋值
	//也就是说，删除的效果一定覆盖添加的效果，至于删除怎么判定再说
	
	//获取防御属性列表（全部战斗装备）
	function get_ex_def_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return get_ex_def_array_core($pa, $pd, $active);
	}
	
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$ret = Array();
		foreach ($battle_equip_list as $itm)
			foreach (\itemmain\get_itmsk_array($pd[$itm.'sk']) as $key)
				array_push($ret,$key);
				
		return $ret;
	}
	
	//获取攻击属性列表（武器防具和饰品）
	function get_ex_attack_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return get_ex_attack_array_core($pa, $pd, $active);
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (attr_dmg_check_not_WPG($pa, $pd, $active))
			$ret = \itemmain\get_itmsk_array($pa['wepsk']);
		else $ret = array();
		
		if (defined('MOD_ARMOR'))
		{		
			eval(import_module('armor'));
			foreach ($armor_equip_list as $itm)
				foreach (\itemmain\get_itmsk_array($pa[$itm.'sk']) as $key)
					array_push($ret,$key);	
		}
		
		if (defined('MOD_ARMOR_ART'))
		{
			$ret = array_merge($ret,\itemmain\get_itmsk_array($pa['artsk']));
			//奇葩的饰品特判…… 木有办法……
			if ($pa['artk']=='Al') array_push($ret,'l');
			if ($pa['artk']=='Ag') array_push($ret,'g');
		}
		return $ret;
	}
			
	function attr_dmg_check_not_WPG(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//必须作为本系武器使用才有属性伤害（枪械当钝器没有）
		return (strpos($pa['wepk'],$pa['wep_kind'])!==false);
	}
	
	//检查$pa是否具有$nm属性，如$pa为NULL则检查当前玩家
	//警告：本函数不供战斗使用！！！！！本函数只应当被用来检查非战斗相关属性！！！
	function check_itmsk($nm, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if ($pa == NULL)
		{
			foreach ($battle_equip_list as $itm)
				foreach (\itemmain\get_itmsk_array(${$itm.'sk'}) as $key)
					if ($key==$nm)
						return 1;
			return 0;
		}
		else
		{
			foreach ($battle_equip_list as $itm)
				foreach (\itemmain\get_itmsk_array($pa[$itm.'sk']) as $key)
					if ($key==$nm)
						return 1;
			return 0;
		}
	}
}

?>
