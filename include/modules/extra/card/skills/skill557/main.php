<?php

namespace skill557
{
	function init() 
	{
		define('MOD_SKILL557_INFO','card;hidden;');
	}
	
	function acquire557(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost557(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		if (\skillbase\skill_query(557)) 
		{
			$itm = &$theitem['itm'];
			$itmk = &$theitem['itmk'];
			if (strpos ( $itmk, 'DF' ) === 0)
			{
				ob_start();
				include template(MOD_SKILL557_CASTSK557);
				$cmd = ob_get_contents();
				ob_end_clean();	
				return;
			}
		}
		$chprocess($theitem);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		eval(import_module('sys','player','input','logger'));
		if($mode == 'item' && $command == 'castsk557') 
		{
			if (!\skillbase\skill_query(557))
			{
				$log .= "似乎你并没有四条腿的样子……<br>";
				$mode = 'command';
				return;
			}
			if (isset($skill557_choice))
			{
				$eqp = $skill557_choice;
				if ('ara' != $eqp && 'arf' != $eqp)
				{
					$log.='参数不合法。<br>';
					$mode = 'command';
					return;
				}
				$itmp = (int)$itmp;
				$itm = & ${'itm'.$itmp};
				$itme = & ${'itme'.$itmp};
				$itms = & ${'itms'.$itmp};
				$itmk = & ${'itmk'.$itmp};
				$itmsk = & ${'itmsk'.$itmp};
				if ('' === ${$eqp.'k'} || ! ${$eqp.'s'}) {
					${$eqp} = $itm;
					${$eqp.'k'} = $itmk;
					${$eqp.'e'} = $itme;
					${$eqp.'s'} = $itms;
					${$eqp.'sk'} = $itmsk;
					$log .= "装备了<span class=\"yellow b\">$itm</span>。<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				} else {
					swap(${$eqp},$itm);
					swap(${$eqp.'k'},$itmk);
					swap(${$eqp.'e'},$itme);
					swap(${$eqp.'s'},$itms);
					swap(${$eqp.'sk'},$itmsk);
					$log .= "卸下了<span class=\"red b\">$itm</span>，装备了<span class=\"yellow b\">${$eqp}</span>。<br>";
				}
				return;
			}
		}	
		$chprocess();
	}
}

?>