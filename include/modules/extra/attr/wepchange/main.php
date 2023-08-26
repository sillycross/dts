<?php

namespace wepchange
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['j'] = '多重';
		$itemspkdesc['j']='能切换武器模式';
		$itemspkremark['j']='部分转换不可逆；不能使用任何方式强化或改造这个装备';
	}
	
	function weaponswap()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		if (!\itemmain\check_in_itmsk('j', $wepsk))
		{
			$log.='你的武器不能变换。<br>';
			$mode = 'command';
			return;
		}
		
		$oldw=$wep;
		$wobj = get_weaponswap_obj($wep);
		if(!empty($wobj)){
			list($null,$wep,$wepk,$wepe,$weps,$wepsk) = $wobj;
			$log.="<span class=\"yellow b\">{$oldw}</span>变换成了<span class=\"yellow b\">{$wep}</span>。<br>";
			if(strpos($wepk,'W')!==0) {//变出非武器时自动卸下
				\itemmain\itemoff('wep');
			}
			return;
		}
		$log.="<span class=\"yellow b\">{$oldw}</span>由于改造或其他原因不能变换。<br>";
	}
	
	function get_weaponswap_obj($wn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$file = __DIR__.'/config/wepchange.config.php';
		$wlist = openfile($file);
		$wnum = count($wlist)-1;
		for ($i=0;$i<=$wnum;$i++){
			$wret = explode(',',$wlist[$i]);
			if ($wn==$wret[0]) return $wret;
		}
		return array();
	}
	
	function act()	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input'));
		
		if ($mode == 'command' && $command=='special' && $sp_cmd == 'sp_weapon')
		{
			weaponswap();
			$mode = 'command';
			return;
		}
		
		$chprocess();
	}
	
	function use_nail($itm, $itme)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player','logger'));
		if (\itemmain\check_in_itmsk('j', $wepsk))
		{
			$log.='多重武器不能改造。<br>';
			return 0;
		}
		return $chprocess($itm,$itme);
	}
	
	function use_hone($itm, $itme)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player','logger'));
		if (\itemmain\check_in_itmsk('j', $wepsk))
		{
			$log.='多重武器不能改造。<br>';
			return 0;
		}
		return $chprocess($itm,$itme);
	}
	
	function use_weapon_improvement($itm, $itmsk=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player','logger'));
		if (\itemmain\check_in_itmsk('j', $wepsk))
		{
			$log.='多重武器不能改造。<br>';
			return 0;
		}
		return $chprocess($itm, $itmsk);
	}
	
}

?>