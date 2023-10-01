<?php

namespace skill559
{
	function init() 
	{
		define('MOD_SKILL559_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[559] = '姿势';	
	}
	
	function acquire559(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(559,'classes','',$pa);
	}
	
	function lost559(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(559,'classes',$pa);
	}
	
	function check_unlocked559(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//这里没做$pa=NULL时的判定，但是似乎也没有用到$pa的具体元素，而用到的技能函数都会自动处理$pa=NULL时的情况，所以能跑通，甚为奇特……
	function sk559_getnewclass(&$pa, $class)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;

		//如果没有该技能，则习得
		if (!\skillbase\skill_query(559,$pa)) \skillbase\skill_acquire(559,$pa);
		$classes = \skillbase\skill_getvalue(559,'classes',$pa);
		if ('' === $classes) $ls = array();
		else $ls = explode('_',$classes);
		if (!in_array($class, $ls))
		{
			$ls[] = $class;
			$classes = implode('_',$ls);
			\skillbase\skill_setvalue(559,'classes',$classes,$pa);
			eval(import_module('logger'));
//			$showrecipe=show_sk559_recipe(get_sk559_mixinfo());
			$log .= "<span class='yellow b'>你学会了新的合成公式！请到技能页面查看。</span><br>";
//			foreach($showrecipe as $v){
//				$log .= '<span class="yellow b">'.$v.'</span><br>';
//			}
		}
	}
	
	function get_sk559_mixinfo(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$extmixinfo = array();
		$classes = \skillbase\skill_getvalue(559,'classes',$pa);
		if ('' === $classes) $ls = array();
		else $ls = explode('_',$classes);
		eval(import_module('skill559'));
		foreach ($skill559_mixinfo as $ma)
		{
			if (in_array($ma['class'], $ls)) $extmixinfo[] = $ma;
		}
		return $extmixinfo;
	}

	function get_mixinfo()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		if (\skillbase\skill_query(559) && check_unlocked559())
		{
			$extmixinfo = get_sk559_mixinfo();
			$ret = array_merge($ret, $extmixinfo);
		}
		return $ret;
	}
		
	function show_sk559_recipe($extmixinfo)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$showrecipe = array();
		foreach($extmixinfo as $ma){
			$s = '';
			foreach($ma['stuff'] as $ms){
				$s .= $ms.' + ';
			}
			$r = \itemmix\parse_itemmix_resultshow($ma['result']);
			$s = substr($s,0,-2).'→ '.$r;
			$showrecipe[] = $s;
		}
		return $showrecipe;
	}
}

?>
