<?php

namespace skill952
{
	function init() 
	{
		define('MOD_SKILL952_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[952] = '宝盒';
	}
	
	function acquire952(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(952,'itmarr','',$pa);
	}
	
	function lost952(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(952,'itmarr',$pa);
	}
	
	function check_unlocked952(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//开局根据随机卡片的稀有度获得技能核心补偿
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$chprocess($pa);
		if (\skillbase\skill_query(952, $pa))
		{
			eval(import_module('cardbase'));
			//S卡：一个随机称号技能
			if ($cards[$pa['card']]['rare'] == 'S')
			{
				skill952_sendin_core(array('itm'=>'淡紫色的技能核心','itmk'=>'SC02','itme'=>1,'itms'=>1,'itmsk'=>''), $pa);
			}
			//A卡：随机称号技能三选一
			elseif ($cards[$pa['card']]['rare'] == 'A')
			{
				skill952_sendin_core(array('itm'=>'深紫色的技能核心','itmk'=>'SC01','itme'=>1,'itms'=>1,'itmsk'=>''), $pa);
			}
			//B卡：两次随机称号技能三选一
			elseif ($cards[$pa['card']]['rare'] == 'B')
			{
				skill952_sendin_core(array('itm'=>'深紫色的技能核心','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''), $pa);
			}
			//C卡：两次随机称号技能三选一，一个随机技能组C-B技能
			elseif ($cards[$pa['card']]['rare'] == 'C')
			{
				skill952_sendin_core(array('itm'=>'深紫色的技能核心','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''), $pa);
				if (rand(0,2)) skill952_sendin_core(array('itm'=>'蓝色的技能核心','itmk'=>'SCC2','itme'=>1,'itms'=>1,'itmsk'=>''), $pa);
				else skill952_sendin_core(array('itm'=>'绿色的技能核心','itmk'=>'SCB2','itme'=>1,'itms'=>1,'itmsk'=>''), $pa);
			}
			//M卡：两次随机称号技能三选一，一个随机技能组A-S技能
			else
			{
				skill952_sendin_core(array('itm'=>'深紫色的技能核心','itmk'=>'SC01','itme'=>1,'itms'=>2,'itmsk'=>''), $pa);
				if (rand(0,2)) skill952_sendin_core(array('itm'=>'橙色的技能核心','itmk'=>'SCA2','itme'=>1,'itms'=>1,'itmsk'=>''), $pa);
				else skill952_sendin_core(array('itm'=>'银色的技能核心','itmk'=>'SCS2','itme'=>1,'itms'=>1,'itmsk'=>''), $pa);
			}
		}
	}
	
	//简单粗暴的加解密
	function skill952_encode_itmarr($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gencode($arr);
	}
	
	function skill952_decode_itmarr($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return gdecode($str, 1);
	}
	
	//背包参数预处理，返回处理好的背包道具数组
	function skill952_prepare_itmarr(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(952, $pa)) 
		{
			$log.='你没有这个技能。<br>';
			return Array();
		}
		$ret = \skillbase\skill_getvalue(952,'itmarr', $pa);
		if(!empty($ret)) {
			$ret = skill952_decode_itmarr($ret);
		}else{
			$ret = Array();
		}
		return $ret;
	}
	
	//存入道具核心函数，包括加解密、修改技能参数
	function skill952_sendin_core($theitem, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$skill952_itmarr = skill952_prepare_itmarr($pa);
		$skill952_nowcount = sizeof($skill952_itmarr);
		if ($skill952_nowcount >= 30)
		{
			eval(import_module('logger'));
			$log .= '<span class="yellow b">但是你的奖励箱已经装满了，没法获得更多道具了。</span><br>';
			return;
		}
		$skill952_itmarr[] = $theitem;
		\skillbase\skill_setvalue(952,'itmarr',skill952_encode_itmarr($skill952_itmarr),$pa);
	}
	
	//取出道具
	function skill952_fetchout($bagn, &$pa=NULL, $showlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(empty($pa)) {
			$pa = $sdata;
		}
		if (!\skillbase\skill_query(952, $pa)) 
		{
			if($showlog) $log.='你没有这个技能。<br>';
			return;
		}	elseif($bagn < 0) {
			if($showlog) $log .= '道具参数错误。<br>';
			return;
		}
		
		$ret = skill952_fetchout_core($bagn, $pa);
		
		if(empty($ret)) {
			if($showlog) $log.='技能参数错误。<br>';
			return;
		}
		
		$pa['itm0'] = $ret['itm'];
		$pa['itmk0'] = $ret['itmk'];
		$pa['itme0'] = $ret['itme'];
		$pa['itms0'] = $ret['itms'];
		$pa['itmsk0'] = $ret['itmsk'];
		
		if($showlog) $log.='<span class="cyan">你从宝盒中取出了'.$pa['itm0'].'。</span><br>';
		
		if($pa['pid'] === $sdata['pid']) \itemmain\itemget();
		return;
	}
	
	//取出道具核心函数，包括加解密、修改技能参数，返回道具数组。
	//注意对超过道具数组下标的指令会返回NULL，然后具体的错误提示是在上面的外壳函数skill952_fetchout()处理。
	function skill952_fetchout_core($bagn, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		
		$skill952_itmarr = skill952_prepare_itmarr($pa);
		
		$ret = NULL;
		if(!empty($skill952_itmarr[$bagn])) {
			$ret = $skill952_itmarr[$bagn];
			unset($skill952_itmarr[$bagn]);
		}
		
		\skillbase\skill_setvalue(952,'itmarr',skill952_encode_itmarr($skill952_itmarr),$pa);
		
		return $ret;
	}
	
	function cast_skill952()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(952)) 
		{
			$log.='你没有这个技能。';
			return;
		}
		$flag = 0;
		$subcmd = get_var_input('subcmd');
		$skill952_fetchout = (int)get_var_input('skill952_fetchout');
		if(!empty($skill952_fetchout))
		{
			skill952_fetchout($skill952_fetchout-1); //为了防止传0过来，显示的数组编号都有+1
			$flag = 1;
		}
		if(!$flag && 'show' != $subcmd) {
			$log.='参数不合法。<br>';
		}
		if(empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_SKILL952_CASTSK952);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
	
		if ($mode == 'special' && $command == 'skill952_special') 
		{
			cast_skill952();
			return;
		}
			
		$chprocess();
	}
}

?>