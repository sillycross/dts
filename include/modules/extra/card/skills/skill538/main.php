<?php

namespace skill538
{
	function init() 
	{
		define('MOD_SKILL538_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[538] = '仪式';
	}
	
	function acquire538(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(538,'maxlvl','0',$pa);
		\skillbase\skill_setvalue(538,'revive_unlocked','0',$pa);
		\skillbase\skill_setvalue(538,'revive_activated','0',$pa);
		\skillbase\skill_setvalue(538,'curse_unlocked','0',$pa);
		\skillbase\skill_setvalue(538,'reboot_unlocked','0',$pa);
		\skillbase\skill_setvalue(538,'mask_unlocked','0',$pa);
	}
	
	function lost538(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked538(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//20级前获得金钱和技能点为0
	function get_wdebug_money(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(538) && \skillbase\skill_getvalue(424,'lvl') < 20) return 0;
		
		return $chprocess();
	}
	
	function get_wdebug_skillpoint(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(538) && \skillbase\skill_getvalue(424,'lvl') < 20) return 0;
		
		return $chprocess();
	}
	
	//除错层数每有1层，物理固定伤害增加3点
	function get_skill538_extdmg(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(538, $pa) && \skillbase\skill_query(424, $pa)) {
			$clv=\skillbase\skill_getvalue(424,'lvl',$pa);
			if(!empty($clv)) {
				eval(import_module('logger'));
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"yellow b\"><:pa_name:>挥舞手中的武器，高喊着“Ka-ka!”的咒语！这让<:pa_name:>的力量增加了！</span><br>");
				return 3 * (int)$clv;
			}
		}
		
		return 0;
	}
	
	function get_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 0;
		if(\skillbase\skill_query(538, $pa) && \skillbase\skill_query(424, $pa)) {
			$r = get_skill538_extdmg($pa,$pd,$active);
		}
		return $chprocess($pa, $pd, $active) + $r;
	}
	
	//达到30、50、70、100层的特殊奖励
	function wdebug(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		if(\skillbase\skill_query(538) && \skillbase\skill_query(424)) {
			$clv = (int)\skillbase\skill_getvalue(424,'lvl');
			$mlv = (int)\skillbase\skill_getvalue(538,'maxlvl');
			if($clv > $mlv) {//没达到过这一层，才判定奖励
				eval(import_module('logger'));
				\skillbase\skill_setvalue(538,'maxlvl',$clv);
				if($clv >= 100 && empty(\skillbase\skill_getvalue(538,'revive_unlocked'))) {//100层，获得一次性的复活技能
					\skillbase\skill_setvalue(538,'revive_unlocked',1);
					$log .= '<span class="cyan b">你感到自己获得了觉醒般的力量！</span><br>';
				}
				if($clv >= 70 && empty(\skillbase\skill_getvalue(538,'curse_unlocked'))) {//70层，获得战斗中让对方眩晕的技能
					\skillbase\skill_setvalue(538,'curse_unlocked',1);
					$log .= '<span class="cyan b">你现在可以对你的敌人降下邪咒了！</span><br>';
					\skillbase\skill_acquire(467);//获得巨力
				}
				if($clv >= 50 && empty(\skillbase\skill_getvalue(538,'reboot_unlocked'))) {//50层，获得重载技能
					\skillbase\skill_setvalue(538,'reboot_unlocked',1);
					$log .= '<span class="yellow b">你想起了你以前除错时是如何挑肥拣瘦的。</span><br>';
					\skillbase\skill_acquire(425);//获得重载
				}
				if($clv >= 30 && empty(\skillbase\skill_getvalue(538,'mask_unlocked'))) {//30层，获得咔咔面具
					\skillbase\skill_setvalue(538,'mask_unlocked',1);
					$log .= '<span class="cyan b">你给自己制造了一个头套。戴上它让你有一种开口喊叫的欲望。</span><br>';
					eval(import_module('player'));
					$itm0 = '邪教徒头套';
					$itmk0 = 'DH';
					$itme0 = '15';
					$itms0 = '100';
					$itmsk0 = '';
					\itemmain\itemget();
				}
			}
		}
		return $ret;
	}
	
	//在生成技能表之前判定，如果获得眩晕技能，修改技能名
	function get_skillpage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(538) && !empty(\skillbase\skill_getvalue(538,'curse_unlocked')) && \skillbase\skill_query(467))
		{
			eval(import_module('clubbase'));
			$clubskillname[467] = '邪咒';
		}
		$chprocess();
	}
	
	//解锁复活之后，能够复活一次
	
	function check_skill538_revive_available(&$pdata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(538,$pdata) && !empty(\skillbase\skill_getvalue(538,'revive_unlocked', $pdata)) && empty(\skillbase\skill_getvalue(538,'revive_activated', $pdata))) 
			return true;
		return false;
	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(check_skill538_revive_available($pd)){
			$pd['revive_sequence'][300] = 'skill538';
		}
		return;
	}	

	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill538' == $rkey && in_array($pd['state'],Array(20,21,22,23,24,25,27,29,39,40,41))){
			if(check_skill538_revive_available($pd))
			$ret = true;
		}
		return $ret;
	}
	
	//回满血，发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill538' == $rkey){
			eval(import_module('sys'));
			$pd['hp']=$pd['mhp'];
			$pd['skill538_flag']=1;
			\skillbase\skill_setvalue(538,'revive_activated',1,$pd);
			$pd['rivival_news'] = array('revival538', $pd['name']);
		}
		return;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('sys','logger'));
		
		if(!empty($pd['skill538_flag'])){
			if ($pd['o_state']==27)	//陷阱
			{
				$log.= "<span class=\"lime b\">但是，你不甘地发出“咕嗷。。。”的叫声，并且重新爬了起来！</span><br>";
				if(!$pd['sourceless']){
					$w_log = "<span class=\"lime b\">但是，{$pd['name']}不甘地发出“咕嗷。。。”的叫声，并且重新爬了起来！</span><br>";
					\logger\logsave ( $pa['pid'], $now, $w_log ,'b');
				}
			}
		}
		return $ret;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (isset($pd['skill538_flag']) && $pd['skill538_flag'])
		{
			if ($active)
			{
				$log.='<span class="lime b">但是，'.$pa['name'].'不甘地发出“咕嗷。。。”的叫声，并且重新爬了起来！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是，你不甘地发出“咕嗷。。。”的叫声，并且重新爬了起来！</span>';
			}
			else
			{
				$log.='<span class="lime b">但是，你不甘地发出“咕嗷。。。”的叫声，并且重新爬了起来！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是，'.$pa['name'].'不甘地发出“咕嗷。。。”的叫声，并且重新爬了起来！</span>';
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revive538') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}不甘地发出“咕嗷。。。”的叫声并重新爬了起来！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}
?>