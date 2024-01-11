<?php

namespace skill586
{
	$skill586_size = 30;
	$ragecost = 30;
	
	function init() 
	{
		define('MOD_SKILL586_INFO','card;active;battle;storage;');
		eval(import_module('clubbase'));
		$clubskillname[586] = '神隐';
	}
	
	function acquire586(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(586,'itmarr','',$pa);
		\skillbase\skill_setvalue(586,'npcarr','',$pa);
	}
	
	function lost586(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(586,'itmarr',$pa);
		\skillbase\skill_delvalue(586,'npcarr',$pa);
		\skillbase\skill_delvalue(586,'lvl',$pa);
	}
	
	function check_unlocked586(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_rage_cost586(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill586'));
		return $ragecost;
	}
	
	function skill586_get_npcarr(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (empty($pa))
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		$npcarr = \skillbase\skill_getvalue(586,'npcarr',$pa);
		if ('' === $npcarr) $npcarr = array();
		else $npcarr = explode('_',$npcarr);
		return $npcarr;
	}
	
	function skill586_add_tpid(&$pa, $tpid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$npcarr = skill586_get_npcarr($pa);
		if (!in_array($tpid, $npcarr))
		{
			$npcarr[] = $tpid;
			$npcarr = implode('_',$npcarr);
			\skillbase\skill_setvalue(586,'npcarr',$npcarr,$pa);
		}
	}
	
	function skill586_remove_tpid(&$pa, $tpid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$npcarr = skill586_get_npcarr($pa);
		$i = array_search($tpid, $npcarr);
		if ($i !== false)
		{
			unset($npcarr[$i]);
			$npcarr = array_values($npcarr);
			$npcarr = implode('_',$npcarr);
			\skillbase\skill_setvalue(586,'npcarr',$npcarr,$pa);
		}
	}
	
	function check_battle_skill_unactivatable(&$ldata, &$edata, $skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata, $edata, $skillno);
		if (586 == $skillno && 0 == $ret)
		{
			if (($edata['type'] == 0) || ($edata['hp'] >= $ldata['hp'])) $ret = 6;
			else
			{
				$skill586_size = skill586_get_packsize($ldata);
				$skill586_nowcount_item = sizeof(skill586_prepare_itmarr($ldata));
				$skill586_nowcount_npc = sizeof(skill586_get_npcarr($ldata));
				$skill586_totalcount = $skill586_nowcount_item + $skill586_nowcount_npc;
				if ($skill586_totalcount >= $skill586_size) $ret = 6;
			}
		}
		return $ret;
	}

	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 586) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(586,$pa) || !check_unlocked586($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		elseif (0 != $pd['type'] && ($pd['hp'] < $pa['hp']))
		{
			$rcost = get_rage_cost586($pa);
			if ($pa['rage'] >= $rcost)
			{
				eval(import_module('skill586','logger'));
				if ($active) $log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「神隐」！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「神隐」！</span><br>";
				$pa['rage'] -= $rcost;
				addnews ( 0, 'bskill586', $pa['name'], $pd['name'] );
				$temp_log = $log;
			}
		}
		else
		{
			if ($active)
			{
				eval(import_module('logger'));
				$log .= '怒气不足或其他原因不能发动。<br>';
			}
			$pa['bskill'] = 0;
		}
		$chprocess($pa, $pd, $active);
		if (isset($temp_log)) $log = $temp_log;
	}
	
	function strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] == 586)
		{
			eval(import_module('logger'));
			$skill586_size = skill586_get_packsize($pa);
			$skill586_nowcount_item = sizeof(skill586_prepare_itmarr($pa));
			$skill586_nowcount_npc = sizeof(skill586_get_npcarr($pa));
			$skill586_totalcount = $skill586_nowcount_item + $skill586_nowcount_npc;
			if ($skill586_totalcount >= $skill586_size)
			{
				$log .= "<span class=\"yellow b\">但是你的异次元已经没有多余的空间了！</span><br>";
				$chprocess($pa, $pd, $active);
			}
			else
			{
				$dice = rand(0,3);
				$dest = (array('异次元的星之古战场','异次元的境界线','次元的裂缝','壹世坏-珍珠世界'))[$dice];
				$log .= "<span class=\"yellow b\">{$pd['name']}被送去了{$dest}！</span><br>";
				\skillbase\skill_acquire(711, $pd);
				skill586_add_tpid($pa, $pd['pid']);
				$pd['skill586_flag']=1;
				$pa['is_hit']=0;
			}
		}
		else $chprocess($pa, $pd, $active);
	}
	
	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty($pa['skill586_flag'])) return 0;
		return $chprocess($pa, $pd, $active);
	}
	
	function player_cannot_counter(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (empty($pa['skill586_flag'])) $chprocess($pa, $pd, $active);
	}
	
	//获得异空间容量大小，其实就是lvl参数
	function skill586_get_packsize(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$ret = \skillbase\skill_getvalue(586,'lvl',$pa);
		if(empty($ret)) $ret = 0;
		return $ret;
	}
	
	//简单粗暴的加解密
	function skill586_encode_itmarr($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gencode($arr);
	}
	
	function skill586_decode_itmarr($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gdecode($str, 1);
	}
	
	//背包参数预处理，返回处理好的背包道具数组
	function skill586_prepare_itmarr(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(586, $pa)) 
		{
			$log.='你不能访问异次元。<br>';
			return Array();
		}
		$ret = \skillbase\skill_getvalue(586,'itmarr', $pa);
		if(!empty($ret)) {
			$ret = skill586_decode_itmarr($ret);
		}else{
			$ret = Array();
		}
		return $ret;
	}
	
	//存入并清除原道具
	function skill586_sendin($itmn, &$pa=NULL, $showlog = 1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(586, $pa)) 
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
		
		$skill586_size = skill586_get_packsize($pa);
		$skill586_nowcount_item = sizeof(skill586_prepare_itmarr($pa));
		$skill586_nowcount_npc = sizeof(skill586_get_npcarr($pa));
		$skill586_totalcount = $skill586_nowcount_item + $skill586_nowcount_npc;
		
		if($skill586_totalcount >= $skill586_size) {
			if($showlog) $log .= '<span class="yellow b">你的异次元空间已经装不下了。</span><br>';
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
		
		skill586_sendin_core($theitem, $pa);		
		
		$pa['itm'.$itmn] = $pa['itmk'.$itmn] = $pa['itmsk'.$itmn] = '';
		$pa['itme'.$itmn] = $pa['itms'.$itmn] = 0;
	}
	
	//存入道具核心函数，包括加解密、修改技能参数
	function skill586_sendin_core(&$theitem, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$skill586_itmarr = skill586_prepare_itmarr($pa);
		
		unset($theitem['itmn']);
		
		$skill586_itmarr[] = $theitem;
		
		\skillbase\skill_setvalue(586,'itmarr',skill586_encode_itmarr($skill586_itmarr),$pa);
	}
	
	function skill586_fetchout($bagn, &$pa=NULL, $showlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(586, $pa)) 
		{
			if($showlog) $log.='你没有这个技能。<br>';
			return;
		}	elseif($bagn < 0) {
			if($showlog) $log .= '道具参数错误。<br>';
			return;
		}		
		//放出npc
		if ($bagn > 100)
		{
			$tpid = $bagn - 100;
			skill586_release($tpid, $pa, $showlog);
			return;
		}
		//取出道具
		else
		{
			$ret = skill586_fetchout_core($bagn, $pa);
			
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
	}
	
	//放出npc的函数
	function skill586_release($tpid, &$pa, $showlog = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if (in_array($tpid, skill586_get_npcarr($pa)))
		{
			$tnpc = \player\fetch_playerdata_by_pid($tpid);
			if (!empty($tnpc))
			{
				$tnpc['pls'] = $pa['pls'];
				\skillbase\skill_lost(711, $tnpc);
				\player\player_save($tnpc);
				skill586_remove_tpid($pa, $tpid);
				if($showlog) $log.='<span class="cyan">异次元的电车送来了'.$tnpc['name'].'。</span><br>';
			}
			skill586_remove_tpid($pa, $tpid);
		}
		else
		{
			if($showlog) $log .= '技能参数错误。<br>';
		}
	}
	
	//取出道具核心函数，包括加解密、修改技能参数，返回道具数组。
	//注意对超过道具数组下标的指令会返回NULL，然后具体的错误提示是在上面的外壳函数skill586_fetchout()处理。
	function skill586_fetchout_core($bagn, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		
		$skill586_itmarr = skill586_prepare_itmarr($pa);
		
		$ret = NULL;
		if(!empty($skill586_itmarr[$bagn])) {
			$ret = $skill586_itmarr[$bagn];
			unset($skill586_itmarr[$bagn]);
			$skill586_itmarr = array_values($skill586_itmarr);
		}
		
		\skillbase\skill_setvalue(586,'itmarr',skill586_encode_itmarr($skill586_itmarr),$pa);
		
		return $ret;
	}
	
	function cast_skill586()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(586)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		$flag = 0;
		$skill586_sendin = get_var_input('skill586_sendin');
		$skill586_fetchout = get_var_input('skill586_fetchout');
		$subcmd = get_var_input('subcmd');
		if (!empty($skill586_sendin))
		{
			skill586_sendin($skill586_sendin);
			$flag = 1;
		}
		elseif(!empty($skill586_fetchout))
		{
			skill586_fetchout($skill586_fetchout-1); //为了防止传0过来，显示的数组编号都有+1
			$flag = 1;
		}
		if(!$flag && 'show' != $subcmd) {
			$log.='参数不合法。<br>';
		}
		if(empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_SKILL586_CASTSK586);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function kill(&$pa, &$pd) 
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd);
		if ($pd['hp'] <= 0 && \skillbase\skill_query(586,$pd))
		{
			$npcarr = skill586_get_npcarr($pd);
			if (!empty($npcarr))
			{
				eval(import_module('logger'));
				$log .= "<span class=\"yellow b\">被送去异次元的旅客们又回到了战场。</span><br>";
				foreach($npcarr as $tpid)
				{
					skill586_release($tpid, $pd);
				}
			}
		}
		return $ret;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
	
		if ($mode == 'special' && $command == 'skill586_special') 
		{
			cast_skill586();
			return;
		}
		
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill586') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「神隐」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>