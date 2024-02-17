<?php

namespace skill534
{
	$skill534_size = 2;
	
	function init() 
	{
		define('MOD_SKILL534_INFO','card;active;storage;');
		eval(import_module('clubbase'));
		$clubskillname[534] = '空子';
	}
	
	function acquire534(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill534'));
		\skillbase\skill_setvalue(534,'itmarr','',$pa);
		\skillbase\skill_setvalue(534,'lvl',$skill534_size,$pa);
	}
	
	function lost534(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(534,'itmarr',$pa);
		\skillbase\skill_delvalue(534,'lvl',$pa);
	}
	
	function check_unlocked534(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//获得异空间容量大小，其实就是lvl参数
	function skill534_get_packsize(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$ret = \skillbase\skill_getvalue(534,'lvl',$pa);
		if(empty($ret)) $ret = 0;
		return $ret;
	}
	
	//简单粗暴的加解密
	function skill534_encode_itmarr($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gencode($arr);
	}
	
	function skill534_decode_itmarr($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gdecode($str, 1);
	}
	
	//背包参数预处理，返回处理好的背包道具数组
	function skill534_prepare_itmarr(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(534, $pa)) 
		{
			$log.='你不能访问异次元。<br>';
			return Array();
		}
		$ret = \skillbase\skill_getvalue(534,'itmarr', $pa);
		if(!empty($ret)) {
			$ret = skill534_decode_itmarr($ret);
		}else{
			$ret = Array();
		}
		return $ret;
	}
	
	//存入并清除原道具
	function skill534_sendin($itmn, &$pa=NULL, $showlog = 1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(534, $pa)) 
		{
			if($showlog) $log.='你没有这个技能。<br>';
			return;
		}	elseif($itmn <= 0 || $itmn > 6) {
			if($showlog) $log .= '道具参数错误。<br>';
			return;
		}elseif(empty(${'itms'.$itmn})) {
			if($showlog) $log .= '该道具不存在！<br>';
			return;
		}
		
		$skill534_size = skill534_get_packsize($pa);
		$skill534_nowcount = sizeof(skill534_prepare_itmarr($pa));
		
		if($skill534_nowcount >= $skill534_size) {
			if($showlog) $log .= '<span class="yellow b">你那小小的异次元空间已经装不下了……</span><br>';
			return;
		}
		
		if($showlog) $log .= '<span class="cyan">你将'.$pa['itm'.$itmn].'丢进了异次元。</span><br>';
		
		$theitem=Array();
		$theitem['itm'] = & $pa['itm'.$itmn];
		$theitem['itmk'] = & $pa['itmk'.$itmn];
		$theitem['itme'] = & $pa['itme'.$itmn];
		$theitem['itms'] = & $pa['itms'.$itmn];
		$theitem['itmsk'] = & $pa['itmsk'.$itmn];
		$theitem['itmn'] = $itmn;
		
		skill534_sendin_core($theitem, $pa);		
		
		$pa['itm'.$itmn] = $pa['itmk'.$itmn] = $pa['itmsk'.$itmn] = '';
		$pa['itme'.$itmn] = $pa['itms'.$itmn] = 0;
	}
	
	//存入道具核心函数，包括加解密、修改技能参数
	function skill534_sendin_core(&$theitem, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$skill534_itmarr = skill534_prepare_itmarr($pa);
		
		unset($theitem['itmn']);
		
		$skill534_itmarr[] = $theitem;
		
		\skillbase\skill_setvalue(534,'itmarr',skill534_encode_itmarr($skill534_itmarr),$pa);
	}
	
	//取出道具
	function skill534_fetchout($bagn, &$pa=NULL, $showlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(534, $pa)) 
		{
			if($showlog) $log.='你没有这个技能。<br>';
			return;
		}	elseif($bagn < 0) {
			if($showlog) $log .= '道具参数错误。<br>';
			return;
		}
		
		$ret = skill534_fetchout_core($bagn, $pa);
		
		if(empty($ret)) {
			if($showlog) $log.='技能参数错误。<br>';
			return;
		}
		
		$pa['itm0'] = $ret['itm'];
		$pa['itmk0'] = $ret['itmk'];
		$pa['itme0'] = $ret['itme'];
		$pa['itms0'] = $ret['itms'];
		$pa['itmsk0'] = $ret['itmsk'];
		
		if($showlog) $log.='<span class="cyan">你从异次元中取出了'.$pa['itm0'].'。</span><br>';
		
		if($pa['pid'] === $sdata['pid']) \itemmain\itemget();
		return;
	}
	
	//取出道具核心函数，包括加解密、修改技能参数，返回道具数组。
	//注意对超过道具数组下标的指令会返回NULL，然后具体的错误提示是在上面的外壳函数skill534_fetchout()处理。
	function skill534_fetchout_core($bagn, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		
		$skill534_itmarr = skill534_prepare_itmarr($pa);
		
		$ret = NULL;
		if(!empty($skill534_itmarr[$bagn])) {
			$ret = $skill534_itmarr[$bagn];
			unset($skill534_itmarr[$bagn]);
		}
		
		\skillbase\skill_setvalue(534,'itmarr',skill534_encode_itmarr($skill534_itmarr),$pa);
		
		return $ret;
	}
	
	function cast_skill534()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(534)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		$flag = 0;
		$subcmd = get_var_input('subcmd');
		$skill534_sendin = get_var_input('skill534_sendin');
		$skill534_fetchout = get_var_input('skill534_fetchout');
		if (!empty($skill534_sendin))
		{
			skill534_sendin($skill534_sendin);
			$flag = 1;
		}
		elseif(!empty($skill534_fetchout))
		{
			skill534_fetchout($skill534_fetchout-1); //为了防止传0过来，显示的数组编号都有+1
			$flag = 1;
		}
		if(!$flag && 'show' != $subcmd) {
			$log.='参数不合法。<br>';
		}
		if(empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_SKILL534_CASTSK534);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill534_special') 
		{
			cast_skill534();
			return;
		}
			
		$chprocess();
	}
}

?>