<?php

namespace skill480
{
	function init() 
	{
		define('MOD_SKILL480_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[480] = '泡沫';
	}
	
	function acquire480(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(480,'activated',0,$pa);
	}
	
	function lost480(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//\skillbase\skill_delvalue(480,'activated',$pa);
	}
	
	function check_unlocked480(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill480_activate(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if(\skillbase\skill_getvalue(480,'activated')){
			$log .= '<span class="red b">本局游戏你已经发动过「泡沫」了！</span><br>';
			$mode = 'command';$command = '';
			return;
		}
		\skillbase\skill_setvalue(480,'activated',1);
		$log .= '你发动了技能<span class="gold">「泡沫」</span><br>虽然你的钱包并没有变鼓，但你知道，在这个虚拟世界里，你已经富了整整一倍。<br>想到这里，你心里充满了幸福。<br>';
		$money *= 2;
		addnews ( 0, 'bskill480', $name);
		$mode = 'command';$command = '';
		return;
	}
	
	function skill480_post_activated_effect(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if($hp>0 && \skillbase\skill_query(480) && \skillbase\skill_getvalue(480,'activated')){
			$money_loss = 50;
			if($money < $money_loss) $money_loss = $money;
			if($money_loss) $log .= '<span class="red b">「泡沫」</span>的余波让你的金钱减少了<span class="red b">'.$money_loss.'</span>！<br>';
			$money -= $money_loss;
		}
	}
	
//	function move($moveto) {
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess($moveto);
//		skill480_post_activated_effect();
//	}
//	
//	function search(){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess();
//		skill480_post_activated_effect();
//	}
	
	//改到discover执行后结算
	function discover($schmode){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($schmode);
		skill480_post_activated_effect();
		return $ret;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill480_activate') 
		{
			if (!\skillbase\skill_query(480)) 
			{
				$log.='你没有这个技能。';
				$mode = 'command';$command = '';
				return;
			}
			skill480_activate();
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill480') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"gold\">「泡沫」</span>，金钱数翻倍了。</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>