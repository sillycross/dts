<?php

namespace skill802
{
	function init() 
	{
		define('MOD_SKILL802_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[802] = '遁逸';
	}
	
	function acquire802(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(802, 'lvl', '0', $pa);
	}
	
	function lost802(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(802, 'lvl', $pa);
	}
	
	function check_unlocked802(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		//lvl为1则物品和金钱全部消失
		if (\skillbase\skill_query(802,$pd) && \skillbase\skill_getvalue(802,'lvl',$pd))
		{
			$pd['weps']=0;$pd['arbs']=0;$pd['arhs']=0;$pd['aras']=0;$pd['arfs']=0;$pd['arts']=0;
			$pd['itms0']=0;$pd['itms1']=0;$pd['itms2']=0;$pd['itms3']=0;$pd['itms4']=0;$pd['itms5']=0;$pd['itms6']=0;
			$pd['money']=0;
			$pd['corpse_clear_flag']=1;
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">{$pd['name']}的尸体化作幻影消失了！</span><br><br>";
		}
	}

	function getcorpse_action(&$edata, $item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//lvl为0则在击杀并进行拾取后全部消失
		$chprocess($edata,$item);
		if (\skillbase\skill_query(802,$edata) && (0 === (int)\skillbase\skill_getvalue(802,'lvl',$edata)))
		{
			$edata['weps']=0;$edata['arbs']=0;$edata['arhs']=0;$edata['aras']=0;$edata['arfs']=0;$edata['arts']=0;
			$edata['itms0']=0;$edata['itms1']=0;$edata['itms2']=0;$edata['itms3']=0;$edata['itms4']=0;$edata['itms5']=0;$edata['itms6']=0;
			$edata['money']=0;
			$edata['corpse_clear_flag']=1;
			eval(import_module('logger'));
			$log .= "<span class=\"yellow b\">{$edata['name']}的尸体化作幻影消失了！</span><br><br>";
		}
	}
	
}

?>