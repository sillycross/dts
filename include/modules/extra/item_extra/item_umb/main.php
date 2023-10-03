<?php

namespace item_umb
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['MB'] = '状态药物';
	}

	function itemuse_um(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm = &$theitem['itm'];
		$itmk = &$theitem['itmk'];
		$itmsk = &$theitem['itmsk'];
		
		//状态药物MB，效果是获得时效buff类的技能
		if (0 === strpos($itmk, 'MB'))
		{
			eval(import_module('clubbase','player','logger'));
			
			$log .= "你服用了<span class=\"red b\">$itm</span>。<br>";
			$sk_kind = (int)$itmsk;
			if ($sk_kind < 1) $sk_kind = 1;
			
			if (defined('MOD_SKILL'.$sk_kind) && $clubskillname[$sk_kind]!='')
			{
				//失去该技能，用于刷新该技能状态
				if (\skillbase\skill_query($sk_kind)) \skillbase\skill_lost($sk_kind);
				$log .= "你获得了状态「<span class=\"yellow b\">".$clubskillname[$sk_kind]."</span>」！<br>";
				\skillbase\skill_acquire($sk_kind);
			}
			else
			{
				$log .= "参数错误，这应该是一个BUG，请联系管理员。<br>";
				return;
			}
			
			\itemmain\itms_reduce($theitem);
		}
		else $chprocess($theitem);
	}
	
}

?>
