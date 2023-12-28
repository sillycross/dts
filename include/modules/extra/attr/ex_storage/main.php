<?php

namespace ex_storage
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^st'] = '储物';
		$itemspkdesc['^st'] = '这一道具能储存其他道具';
		$itemspkinfo['^vol'] = '储物容量';//不显示
	}
	
	function storage_encode_itmarr($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = array();
		foreach ($arr as $i)
		{
			$i = implode(',',$i);
			$ret[] = $i;
		}
		$ret = implode(';',$ret);
		$ret = \attrbase\base64_encode_comp_itmsk($ret);
		return $ret;
	}
	
	function storage_decode_itmarr($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$str = \attrbase\base64_decode_comp_itmsk($str);
		$arr = explode(';',$str);
		$ret = array();
		foreach ($arr as $i)
		{
			$i = explode(',',$i);
			$ret[] = array('itm' => $i[0], 'itmk' => $i[1], 'itme' => $i[2], 'itms' => $i[3], 'itmsk' => $i[4]);
		}
		return $ret;
	}
	
	function get_item_storage($itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$st = \itemmain\check_in_itmsk('^st', $itmsk);
		if ($st === false) return false;
		if (empty($st) || is_numeric($st)) return array();
		else return storage_decode_itmarr($st);
	}
	
	//存入并清除原道具
	function storage_sendin($itmn, $pos=NULL, &$pa=NULL, $showlog = 1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (empty($pos)) $pos = get_empty_stpos($pa);
		if (!in_array($pos, array('wep','arb','arh','ara','arf','art'))) {
			if($showlog) $log .= '无法存入道具。<br>';
			return;
		}elseif (!\itemmain\check_in_itmsk('^st', $pa[$pos.'sk'])) {
			if($showlog) $log .= '该位置无法存放道具。<br>';
			return;
		}elseif($itmn <= 0 || $itmn > 6) {
			if($showlog) $log .= '道具参数错误。<br>';
			return;
		}elseif(empty($pa['itms'.$itmn])) {
			if($showlog) $log .= '该道具不存在！<br>';
			return;
		}elseif(\itemmain\check_in_itmsk('^st', $pa['itmsk'.$itmn])) {
			if($showlog) $log .= '你试图构造一个四维空间，然而失败了。<br>';
			return;
		}
		
		$vol = (int)\itemmain\check_in_itmsk('^vol', $pa[$pos.'sk']);
		$stcount = sizeof(get_item_storage($pa[$pos.'sk']));
		
		if($stcount >= $vol) {
			if($showlog) $log .= '<span class="yellow b">该位置无法存放更多道具。</span><br>';
			return;
		}
		
		if($showlog) $log .= '<span class="cyan">你将'.$pa['itm'.$itmn].'丢进了'.$pa[$pos].'。</span><br>';
		
		$theitem=Array();
		$theitem['itm'] = & $pa['itm'.$itmn];
		$theitem['itmk'] = & $pa['itmk'.$itmn];
		$theitem['itme'] = & $pa['itme'.$itmn];
		$theitem['itms'] = & $pa['itms'.$itmn];
		$theitem['itmsk'] = & $pa['itmsk'.$itmn];
		$theitem['itmn'] = $itmn;
		
		storage_sendin_core($theitem, $pos, $pa);
		
		//故意的
		if (strlen($pa[$pos.'sk']) > 65500)
		{	
			$pa['hp'] = 1;
			foreach(array('h','b','a','f') as $value)
			{
				$pa['inf'] = str_replace($value,'',$pa['inf']);
			}
			$pa['inf'] .= 'hbaf';
			if ($pa == $sdata) $log .= '<span class="red b">糟糕，'.$pa[$pos].'爆炸了！你被炸得差点爬不起来。</span><br>';
			\itemmain\item_destroy_core($pos, $pa);
		}
		//不要在包里放答辩
		elseif (\itemmain\check_in_itmsk('O', $theitem['itmsk']) && !\itemmain\check_in_itmsk('O', $pa[$pos.'sk']))
		{
			$log .= '<span class="red b">'.$theitem['itm'].'附带的诅咒将'.$pa[$pos].'污染了！</span><br>';
			$pa[$pos.'sk'] .= 'O';
		}
		
		$pa['itm'.$itmn] = $pa['itmk'.$itmn] = $pa['itmsk'.$itmn] = '';
		$pa['itme'.$itmn] = $pa['itms'.$itmn] = 0;
	}
	
	function get_empty_stpos(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = $sdata;
		}
		foreach (array('wep','arb','arh','ara','arf','art') as $pos)
		{
			$st = \itemmain\check_in_itmsk('^st', $pa[$pos.'sk']);
			if (\itemmain\check_in_itmsk('^st', $pa[$pos.'sk']))
			{
				$storage_itmarr = get_item_storage($pa[$pos.'sk']);
				$stcount = sizeof($storage_itmarr);
				$vol = (int)\itemmain\check_in_itmsk('^vol', $pa[$pos.'sk']);
				if ($stcount < $vol) return $pos;
			}
		}
		return NULL;
	}
	
	//存入道具核心函数
	function storage_sendin_core(&$theitem, $pos, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$storage_itmarr = get_item_storage($pa[$pos.'sk']);
		
		unset($theitem['itmn']);
		
		$storage_itmarr[] = $theitem;
		$pa[$pos.'sk'] = \itemmain\replace_in_itmsk('^st','',$pa[$pos.'sk']);
		$pa[$pos.'sk'] .= '^st_'.storage_encode_itmarr($storage_itmarr).'1';
	}
	
	//取出道具
	function storage_fetchout($bagn, $pos, &$pa=NULL, $showlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (!\itemmain\check_in_itmsk('^st', $pa[$pos.'sk'])) 
		{
			if($showlog) $log .= $pa[$pos].'中无法取出道具。<br>';
			return;
		}	elseif($bagn < 0) {
			if($showlog) $log .= '道具参数错误。<br>';
			return;
		}
		
		$ret = storage_fetchout_core($bagn, $pos, $pa);
		
		if(empty($ret)) {
			if($showlog) $log.='道具参数错误。<br>';
			return;
		}
		if(\itemmain\check_in_itmsk('O', $ret['itmsk'])) {
			if (!\ex_cursed\check_enkan())
			{
				if($showlog) $log.='<span class="red b">'.$ret['itm'].'像是被强力胶粘在了'.$pa[$pos].'上，拿不下来。</span><br>';
				return;
			}
			elseif($showlog) $log .= '<span class="lime b">圆环之理的光辉使你顺利地拿出了被诅咒的'.$ret['itm'].'。</span><br>';
		}
		
		$pa['itm0'] = $ret['itm'];
		$pa['itmk0'] = $ret['itmk'];
		$pa['itme0'] = $ret['itme'];
		$pa['itms0'] = $ret['itms'];
		$pa['itmsk0'] = $ret['itmsk'];
		
		if($showlog) $log.='<span class="cyan">你从'.$pa[$pos].'中取出了'.$pa['itm0'].'。</span><br>';
		
		if($pa['pid'] === $sdata['pid']) \itemmain\itemget();
		return;
	}
	
	//取出道具核心函数
	//注意对超过道具数组下标的指令会返回NULL，然后具体的错误提示是在上面的外壳函数storage_fetchout()处理。
	function storage_fetchout_core($bagn, $pos, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		
		$storage_itmarr = get_item_storage($pa[$pos.'sk']);
		
		$ret = NULL;
		if(!empty($storage_itmarr[$bagn])) {
			$ret = $storage_itmarr[$bagn];
			unset($storage_itmarr[$bagn]);
		}
		
		if (!\itemmain\check_in_itmsk('O', $ret['itmsk']) || \ex_cursed\check_enkan())
		{
			$pa[$pos.'sk'] = \itemmain\replace_in_itmsk('^st','',$pa[$pos.'sk']);
			//坑
			if (empty($storage_itmarr)) $pa[$pos.'sk'] .= '^st1';
			else $pa[$pos.'sk'] .= '^st_'.storage_encode_itmarr($storage_itmarr).'1';
		}
		return $ret;
	}
	
	function use_storage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','input'));
		if (!\attrbase\check_itmsk('^st')) 
		{
			$log .= '你没有额外的储物空间。<br>';
			return;
		}
		$flag = 0;
		if (!empty($storage_sendin))
		{
			storage_sendin($storage_sendin);
			$flag = 1;
		}
		elseif(!empty($storage_fetchout) && in_array($pos, array('wep','arb','arh','ara','arf','art')))
		{
			storage_fetchout($storage_fetchout-1, $pos); //为了防止传0过来，显示的数组编号都有+1
			$flag = 1;
		}
		if(!$flag && 'show' != $subcmd) {
			$log.='参数不合法。<br>';
		}
		if(\attrbase\check_itmsk('^st') && empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_EX_STORAGE_OPEN_STORAGE);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','input'));
	
		if ($mode == 'special' && $command == 'storage') 
		{
			use_storage();
			return;
		}
		
		$chprocess();
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if ($ret) {
			if ('^vol' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
	//不能切割主包
	function wep_b_extra_reloading_check(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if((strpos($wepk, 'WB')===0) && \itemmain\check_in_itmsk('^st', $theitem['itmsk']))
		{
			$log .= "你试图将<span class=\"yellow b\">$wep</span>与<span class=\"yellow b\">{$theitem['itm']}</span>的储物空间相连，然而失败了。<br>";
			return false;
		}
		else return $chprocess($theitem);
	}
	
	//不能包叠包
	function use_armor(&$theitem, $pos = '')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));	
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];$itmsk=&$theitem['itmsk'];
		if(!$pos) {
			if(strpos ( $itmk, 'DB' ) === 0) {
				$pos = 'arb';
			}elseif(strpos ( $itmk, 'DH' ) === 0) {
				$pos = 'arh';
			}elseif(strpos ( $itmk, 'DA' ) === 0) {
				$pos = 'ara';
			}elseif(strpos ( $itmk, 'DF' ) === 0) {
				$pos = 'arf';
			}
		}
		if(false !== strpos(substr($itmk,2),'S') && \itemmain\check_in_itmsk('^st', $itmsk) && \itemmain\check_in_itmsk('^st', ${$pos.'sk'}))
		{
			$log .= "你试图用<span class=\"yellow b\">$itm</span>和<span class=\"yellow b\">${$pos}</span>制造一个四维空间，然而失败了。<br>";
			return;
		}
		$chprocess($theitem, $pos);
	}
	
	//如果卸下储物外甲，把储物属性移到外甲上
	function armor_remove_su(&$positem, &$getitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($positem, $getitem);
		//内甲上有储物
		if (isset($getitem['itmsk']) && \itemmain\check_in_itmsk('^vol', $getitem['itmsk']) && \itemmain\check_in_itmsk('^st', $positem['itmsk']))
		{
			$getitem['itmsk'] = \itemmain\replace_in_itmsk('^st','',$getitem['itmsk']);
			$getitem['itmsk'] .= '^st_'.\itemmain\check_in_itmsk('^st', $positem['itmsk']).'1';
			$positem['itmsk'] = \itemmain\replace_in_itmsk('^st','',$positem['itmsk']);
		}
		return $ret;
	}
	
	//如果储物外甲被打爆，东西会消失
	function suit_break(&$pa, &$pd, $active, $whicharmor)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active, $whicharmor);
		if (!\itemmain\check_in_itmsk('^vol', $pd[$whicharmor.'sk'])) \itemmain\replace_in_itmsk('^st', '', $pd[$whicharmor.'sk']);
	}
	
}

?>
