<?php

namespace randrecipe
{
	//会生成随机配方的游戏模式
	$randrecipe_allow_mode = array(20);
	
	function init()
	{
	}
	
	function create_randrecipe_config($num = 50)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rl = array();
		for ($i=1; $i<=$num; $i++)
		{
			$rl[] = generate_randrecipe();
		}
		//保存为config文件
	}
	
	//生成一个标准格式的随机配方
	function generate_randrecipe($itmk = '')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('randrecipe'));
		$r = array('result' => array(),'extra' => array());
		if (empty($itmk)) $itmk = array_randompick(array('WP','WK','WC','WG','WD','WF','WP','WK','WC','WG','WD','WF','DB','DH','DA','DF','A','HH','HS','HB'));
		//初始化合成结果
		if ($itmk[0] == 'H') $r['result'][0] = array_randompick($randrecipe_resultname['H_prefix']).array_randompick($randrecipe_resultname[$itmk]);
		else $r['result'][0] = array_randompick($randrecipe_resultname['E_prefix']).array_randompick($randrecipe_resultname[$itmk]);
		$r['result'][1] = $itmk;
		if ($itmk[0] == 'H')
		{
			$r['result'][2] = rand(50,300);
			$r['result'][3] = rand(10,50);
		}
		elseif ($itmk[0] == 'W')
		{
			$r['result'][2] = rand(50,250);
			$r['result'][3] = rand(20,100);
			$skcount = rand(0,2);
			if ($skcount == 1) $r['result'][4] = [array_randompick($randrecipe_itmsk_list['W'])];
			elseif ($skcount > 1) $r['result'][4] = array_randompick($randrecipe_itmsk_list['W'], $skcount);
		}
		elseif ($itmk[0] == 'D')
		{
			$r['result'][2] = rand(40,200);
			$r['result'][3] = rand(20,100);
			$skcount = rand(0,2);
			if ($skcount == 1) $r['result'][4] = [array_randompick($randrecipe_itmsk_list['D'])];
			elseif ($skcount > 1) $r['result'][4] = array_randompick($randrecipe_itmsk_list['D'], $skcount);
		}
		else
		{
			$r['result'][2] = 1;
			$r['result'][3] = 1;
			$skcount = rand(1,2);
			if ($skcount == 1) $r['result'][4] = [array_randompick($randrecipe_itmsk_list['A'])];
			elseif ($skcount > 1) $r['result'][4] = array_randompick($randrecipe_itmsk_list['A'], $skcount);
		}
		$si = 1;
		//主要素材1-2个
		$c = rand(1,2);
		for ($i=0; $i<$c; $i++)
		{
			$r['stuff'.$si] = generate_randrecipe_stuff('main', $itmk);
			$r['result'][2] += rand(10,30);
			$r['result'][3] += rand(1,10);
			$si += 1;
		}
		//副素材0-2个
		$c = rand(0,2);
		for ($i=0; $i<$c; $i++)
		{
			$r['stuff'.$si] = generate_randrecipe_stuff('sub', $itmk);
			$r['result'][2] += rand(5,15);
			$r['result'][3] += rand(1,5);
			$si += 1;
		}
		//额外素材0-2个，但总素材数不超过6个
		$c = rand(0,2);
		for ($i=0; $i<$c; $i++)
		{
			if ($itmk[0] == 'H') $type = array_randompick(array('itme','itms'));
			elseif ($itmk[0] == 'A') $type = array_randompick(array('itmsk','itme','itms'));
			else $type = array_randompick(array('itmsk','itme','itms'));
			$r['stuff'.$si] = generate_randrecipe_stuff($type, $itmk, $r['result'][4]);
			if ($type == 'itme') $r['result'][2] += rand(30,50);
			elseif ($type == 'itms') $r['result'][3] += rand(20,40);
			if ($si > 5) break;
			$si += 1;
		}
		if (!empty($r['result'][4])) $r['result'][4] = implode('', array_unique($r['result'][4]));
		$r['extra']['materials'] = $si - 1;
		return $r;
	}
	
	//生成一个随机配方素材条件
	function generate_randrecipe_stuff($type, $itmk, &$itmsk=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('randrecipe'));
		$s = array();
		if ($type == 'itmsk')
		{
			$sk = array_rand($itmsk_stuff);
			$k = array_rand($itmsk_stuff[$sk]);
			$itmsk[] = $sk;
			$v = array_randompick($itmsk_stuff[$sk][$k]);
		}
		elseif (($type == 'itme') || ($type == 'itms'))
		{
			$r = array_merge_recursive(${$type.'_stuff'}[$itmk], ${$type.'_stuff'}['common']);
			$k = array_rand($r);
			$v = array_randompick($r[$k]);
		}
		else
		{
			$k = array_rand(${$type.'_stuff'}[$itmk]);
			$v = array_randompick(${$type.'_stuff'}[$itmk][$k]);
		}
		$s[$k] = $v;
		if ($k == 'itm') $s['itm_match'] = 1;
		elseif ($k == 'itmk') $s['itmk_match'] = 1;
		elseif ($k == 'itmsk') $s['itmsk_match'] = 1;
		return $s;
	}
	
}

?>