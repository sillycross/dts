<?php

namespace skill597
{
	$skill597_size = 20;
	
	function init() 
	{
		define('MOD_SKILL597_INFO','card;active;storage;');
		eval(import_module('clubbase'));
		$clubskillname[597] = '猫车';
	}
	
	function acquire597(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill597'));
		\skillbase\skill_setvalue(597,'itmarr','',$pa);
		\skillbase\skill_setvalue(597,'lvl',$skill597_size,$pa);
	}
	
	function lost597(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(597,'itmarr',$pa);
		\skillbase\skill_delvalue(597,'lvl',$pa);
	}
	
	function check_unlocked597(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		return $pa['lvl'] >= 10;
	}
	
	function skill597_get_packsize(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$ret = \skillbase\skill_getvalue(597,'lvl',$pa);
		if(empty($ret)) $ret = 0;
		return $ret;
	}
	
	//简单粗暴的加解密
	function skill597_encode_itmarr($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gencode($arr);
	}
	
	function skill597_decode_itmarr($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gdecode($str, 1);
	}
	
	//背包参数预处理，返回处理好的背包道具数组
	function skill597_prepare_itmarr(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(597, $pa) && check_unlocked597($pa)) 
		{
			$log.='你还没有自己的小推车。<br>';
			return Array();
		}
		$ret = \skillbase\skill_getvalue(597,'itmarr', $pa);
		if(!empty($ret)) {
			$ret = skill597_decode_itmarr($ret);
		}else{
			$ret = Array();
		}
		return $ret;
	}
	
	//移动后，添加一个标记
	function move_to_area($moveto)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if(\skillbase\skill_query(597) && check_unlocked597() && !\gameflow_combo\is_gamestate_combo())
		{
			\skillbase\skill_setvalue(597, 'moveflag', 1);
		}
		return $chprocess($moveto);
	}
	
	//移动的这一步不会发现敌人和尸体
	// function discover_player()
	// {
		// if (eval(__MAGIC__)) return $___RET_VALUE;
		// if (\skillbase\skill_query(597) && check_unlocked597() && \skillbase\skill_getvalue(597, 'moveflag'))
		// {
			// \skillbase\skill_setvalue(597, 'moveflag', 0);
			// return false;
		// }
		// return $chprocess();
	// }
	
	//发现尸体并清除标记
	function discover($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		if(\skillbase\skill_query(597) && check_unlocked597() && \skillbase\skill_getvalue(597, 'moveflag'))
		{
			eval(import_module('sys','logger','player'));
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$pls' AND hp<=0");
			$i = 0;
			if ($db->num_rows($result)) 
			{
				$slotnum = \searchmemory\calc_memory_slotnum();
				eval(import_module('corpse'));
				$tm = $now - $corpseprotect2;
				while($r = $db->fetch_array($result))
				{
					$pdata = \player\fetch_playerdata_by_pid($r['pid']);
					if (($pdata['endtime'] <= $tm) && \metman\discover_player_filter_corpse($pdata))
					{
						$amarr = array('pid' => $pdata['pid'], 'Pname' => $pdata['name'], 'pls' => $pls, 'smtype' => 'corpse', 'unseen' => 0);
						$smn = \searchmemory\seek_memory_by_id($pdata['pid'], 'pid');
						if($smn >= 0) \searchmemory\remove_memory($smn,2);
						\skill1006\add_beacon($amarr);//2023.11.18改为使用临时视野
						$i += 1;
						if ($i >= $slotnum) break;
					}
				}
			}
			if ($i > 0) $log .= "<span class=\"yellow b\">此处的尸体被你尽收眼底。</span><br>";
			else $log .= "<span class=\"yellow b\">你没能找到新的尸体。</span><br>";
			\skillbase\skill_setvalue(597, 'moveflag', 0);
		}
		return $chprocess($schmode);
	}
	
	//带走尸体
	function getcorpse_action(&$edata, $item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','corpse'));
		if($item == 'takeaway')
		{
			if(!\skillbase\skill_query(597, $sdata) && check_unlocked597($sdata)){
				$log .= '你还没有自己的小推车。<br>';
				$mode = 'command';
				return;
			}
			$corpse_size = 0;
			$corpse_items = array();
			foreach (array('wep','arb','arh','ara','arf','art','itm0','itm1','itm2','itm3','itm4','itm5','itm6') as $val)
			{
				//感觉写得有点蠢
				if (strlen($val) == 3) $k = $val.'s';
				else $k = 'itms'.substr($val,3,1);
				if (!empty($edata[$k]))
				{
					$corpse_size += 1;
					$corpse_items[] = $val;
				}
			}
			$skill597_size = skill597_get_packsize($pa);
			$skill597_nowcount = sizeof(skill597_prepare_itmarr($pa));
			if ($skill597_nowcount + $corpse_size > $skill597_size)
			{
				$log .= '你的小推车放不下这具尸体了。<br>';
				$mode = 'command';
				return;
			}
			foreach ($corpse_items as $val)
			{
				if (strlen($val) == 3)
				{
					$theitem = array('itm' => &$edata[$val], 'itmk' => &$edata[$val.'k'.substr($val,3,1)], 'itme' => &$edata[$val.'e'],'itms' => &$edata[$val.'s'],'itmsk' => &$edata[$val.'sk']);
				}
				else
				{
					$i = substr($val,3,1);
					$theitem = array('itm' => &$edata[$val], 'itmk' => &$edata['itmk'.$i], 'itme' => &$edata['itme'.$i],'itms' => &$edata['itms'.$i],'itmsk' => &$edata['itmsk'.$i]);
				}
				skill597_sendin_core($theitem);
				$theitem['itms'] = 0;
			}
			$log .= "你将{$edata['name']}的尸体打包带走了。大丰收！<br>";
			if ($edata['money'] > 0) $log .= "获得了金钱 <span class=\"yellow b\">{$edata['money']}</span>。<br>";
			$money += $edata['money'];
			$edata['money'] = 0;
			$edata['state'] = 16;
			$edata['corpse_clear_flag'] = 1;
			\player\player_save($edata);
			
			$mode = 'command';
			return;
		}
		$chprocess($edata, $item);
	}
	
	//存入道具核心函数，包括加解密、修改技能参数
	function skill597_sendin_core(&$theitem, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$skill597_itmarr = skill597_prepare_itmarr($pa);
		
		unset($theitem['itmn']);
		
		$skill597_itmarr[] = $theitem;
		
		\skillbase\skill_setvalue(597,'itmarr',skill597_encode_itmarr($skill597_itmarr),$pa);
	}
	
	//取出道具
	function skill597_fetchout($bagn, &$pa=NULL, $showlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(597, $pa) && check_unlocked597($pa)) 
		{
			if($showlog) $log.='你无法使用这个技能。<br>';
			return;
		}	elseif($bagn < 0) {
			if($showlog) $log .= '道具参数错误。<br>';
			return;
		}
		
		$ret = skill597_fetchout_core($bagn, $pa);
		
		if(empty($ret)) {
			if($showlog) $log.='技能参数错误。<br>';
			return;
		}
		
		$pa['itm0'] = $ret['itm'];
		$pa['itmk0'] = $ret['itmk'];
		$pa['itme0'] = $ret['itme'];
		$pa['itms0'] = $ret['itms'];
		$pa['itmsk0'] = $ret['itmsk'];
		
		if($showlog) $log.='<span class="cyan">你从推车中取出了'.$pa['itm0'].'。</span><br>';
		
		if($pa['pid'] === $sdata['pid']) \itemmain\itemget();
		return;
	}
	
	//取出道具核心函数，包括加解密、修改技能参数，返回道具数组。
	//注意对超过道具数组下标的指令会返回NULL，然后具体的错误提示是在上面的外壳函数skill597_fetchout()处理。
	function skill597_fetchout_core($bagn, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		
		$skill597_itmarr = skill597_prepare_itmarr($pa);
		
		$ret = NULL;
		if(!empty($skill597_itmarr[$bagn])) {
			$ret = $skill597_itmarr[$bagn];
			unset($skill597_itmarr[$bagn]);
		}
		
		\skillbase\skill_setvalue(597,'itmarr',skill597_encode_itmarr($skill597_itmarr),$pa);
		
		return $ret;
	}
	
	//一键清空
	function skill597_empty(&$pa=NULL, $showlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(597, $pa) && check_unlocked597($pa))
		{
			if($showlog) $log .= '你无法使用这个技能。<br>';
			return;
		}
		elseif (empty(\skillbase\skill_getvalue(597,'itmarr',$pa)))
		{
			if($showlog) $log .= '你的推车里什么也没有。<br>';
			return;
		}
		\skillbase\skill_setvalue(597,'itmarr','',$pa);
		if($showlog) $log.="<span class=\"yellow b\">你把推车里的燃料送进了锅炉房。</span><br>";
		return;
	}
	
	function cast_skill597()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(597) && check_unlocked597()) 
		{
			$log.='你无法使用这个技能。';
			return;
		}
		$flag = 0;
		$skill597_empty = get_var_input('skill597_empty');
		$skill597_fetchout = get_var_input('skill597_fetchout');
		$subcmd = get_var_input('subcmd');
		if (!empty($skill597_empty))
		{
			skill597_empty();
			$flag = 1;
		}
		elseif(!empty($skill597_fetchout))
		{
			skill597_fetchout($skill597_fetchout-1); //为了防止传0过来，显示的数组编号都有+1
			$flag = 1;
		}
		if(!$flag && 'show' != $subcmd) {
			$log.='参数不合法。<br>';
		}
		if(empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_SKILL597_CASTSK597);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		if ($mode == 'special' && $command == 'skill597_special') 
		{
			cast_skill597();
			return;
		}
		
		$chprocess();
	}
}

?>