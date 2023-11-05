<?php

namespace item_armor_empower
{
	$ea_itmsk = array
	(
		//防具改造可获得的高级防御属性
		'sup' => array('B','b','Z','h'),
		//防具改造可获得的减半防御属性
		'def' => array('A','a','P','K','G','C','D','F','R','q','U','I','E','W'),
		//防具改造可获得的杂项属性
		'misc' => array('H','M','m'),
	);
	
	function init() {
		eval(import_module('itemmain'));
		$iteminfo['EA'] = '防具改造';
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(strpos($k,'EA')===0){
			$ret .= '使防具获得额外的属性，或者使其发生神秘的变化';
		}
		return $ret;
	}
	
	function use_armor_empower($itmn = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itmn = (int)$itmn;
		$itmp = (int)get_var_in_module('itmp', 'input');
		
		if ($itmp < 1 || $itmp > 6) {
			$log .= '此道具不存在，请重新选择。';
			$mode = 'command';
			return;
		}
		
		$emp = & ${'itm'.$itmp};
		$empe = & ${'itms'.$itmp};
		$emps = & ${'itms'.$itmp};
		$empk = & ${'itmk'.$itmp};
		$empsk = & ${'itms'.$itmp};
		
		if ($itmn < 1 || $itmn > 6) {
			$log .= '此道具不存在，请重新选择。';
			$mode = 'command';
			return;
		}

		$itm = & ${'itm'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
		
		if (!$emps || (0 !== strpos($empk, 'EA')))
		{
			$log .= '强化道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}
		if (!$itms || (0 !== strpos($itmk, 'D')))
		{
			$log .= '被强化道具选择错误，请重新选择。<br>';
			$mode = 'command';
			return;
		}

		$theitem = Array('itm' => &$itm, 'itmk' => &$itmk, 'itme' => &$itme,'itms' => &$itms,'itmsk' => &$itmsk);
		$empitem = Array('itm' => &$emp, 'itmk' => &$empk, 'itme' => &$empe,'itms' => &$emps,'itmsk' => &$empsk);
		armor_empower($theitem, $empitem);

		$mode = 'command';
		return;
	}
	
	function armor_empower(&$theitem, &$empitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$emp = & $empitem['itm'];
		
		$itm = & $theitem['itm'];
		$itme = & $theitem['itme'];
		$itms = & $theitem['itms'];
		$itmk = & $theitem['itmk'];
		$itmsk = & $theitem['itmsk'];
		
		eval(import_module('sys','player','logger','armor'));
		$log .= "你使用了<span class=\"yellow b\">$emp</span>，";
		$dice = rand(0, 99);
		if ($dice < 30 && strpos(substr($itmk,2),'S') === false)
		{
			//将防具改造为外甲
			$itmk = substr_replace($itmk, 'S', 2, 1);
			$log .= "将<span class=\"yellow b\">{$itm}</span>改造成了一件外甲！";
		}
		elseif ($dice < 60)
		{
			//获得升血属性
			$hu_up = (int)(min($itme/2, $mhp/2));
			$hu_value = \itemmain\check_in_itmsk('^hu', $itmsk);
			if ($hu_value)
			{
				$hu_value = (int)$hu_value + $hu_up;
				$itmsk = \itemmain\replace_in_itmsk('^hu', '^hu'.$hu_value, $itmsk);
			}
			else $itmsk .= '^hu'.$hu_up;
			$log .= "使<span class=\"yellow b\">{$itm}</span>变得更加坚韧、牢靠了！";
		}
		else
		{
			//获得3-5个额外的防御属性
			//暂时不会获得重复的属性，等减半叠加实装了再改
			$count = rand(3,5);
			eval(import_module('item_armor_empower'));
			$i = 0;
			if (rand(0,3) < 1)
			{
				shuffle($ea_itmsk['sup']);
				if (!\itemmain\check_in_itmsk($ea_itmsk['sup'][0], $itmsk))
				{
					$itmsk .= $ea_itmsk['sup'][0];
					$i += 1;
					
				}
			}
			if (rand(0,2) < 1)
			{
				shuffle($ea_itmsk['misc']);
				if (!\itemmain\check_in_itmsk($ea_itmsk['misc'][0], $itmsk))
				{
					$itmsk .= $ea_itmsk['misc'][0];
					$i += 1;
				}
			}
			$def_nonexist = array_diff($ea_itmsk['def'], \itemmain\get_itmsk_array($itmsk));
			shuffle($def_nonexist);
			foreach($def_nonexist as $key)
			{
				$itmsk .= $key;
				$i += 1;
				if ($i >= $count) break;
			}
			
			if ($i > 0) $log .= "使<span class=\"yellow b\">{$itm}</span>获得了额外的防御属性！";
			else
			{
				$itme += (int)$itme/2;
				if ($nosta !== $itms) $itms += (int)$itms/2;
				$log .= "增强了<span class=\"yellow b\">{$itm}</span>的效果和耐久！";
			}
		}	
		addnews($now, 'armorempower', $name, $emp, $itm);
		if (strpos($itm, '-改') === false) {
			$itm = $itm.'-改';
		}
		\itemmain\itms_reduce($empitem);
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','armor','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ($itmk === 'EA')
		{
			$flag = false;
			for ($i = 1; $i <= 6; $i ++) {
				if (strpos(${'itmk'.$i}, 'D') === 0) {
					$flag = true;
					break;
				}
			}
			if (!$flag) $log .= '你的包裹里没有可以改造的防具。<br>';
			else
			{
				ob_start();
				include template(MOD_ITEM_ARMOR_EMPOWER_USE_ARMOR_EMPOWER);
				$cmd = ob_get_contents();
				ob_end_clean();
			}				
			return;
		}
		$chprocess($theitem);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if ($mode == 'item' && get_var_in_module('usemode','input') == 'armor_empower')
		{
			$item = substr($command, 3);
			use_armor_empower($item);
			return;
		}		
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'armorempower') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}使用了{$b}，改造了<span class=\"yellow b\">$c</span>！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
