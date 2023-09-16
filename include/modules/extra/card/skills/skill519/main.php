<?php

namespace skill519
{
	$skill519_cd = 360;//冷却时间，单位秒
	
	function init() 
	{
		define('MOD_SKILL519_INFO','club;feature;locked;');
		eval(import_module('clubbase'));
		$clubskillname[519] = '幽灵';
	}
	
	function acquire519(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		\skillbase\skill_setvalue(519,'lastrev',0,$pa);//开局就可使用
	}

	function lost519(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(519,'lastrev',$pa);
	}
	
	function check_unlocked519(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(\skillbase\skill_query(519,$pd) && check_unlocked519($pd)){
			$pd['revive_sequence'][70] = 'skill519';
		}
		return;
	}	

	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill519' == $rkey && in_array($pd['state'],Array(20,21,22,23,24,25,27,29,39,40,41))){
			if(3 == check_skill519_state($pd))
			$ret = true;
		}
		return $ret;
	}
	
	//回满血，发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill519' == $rkey){
			eval(import_module('sys'));
			$pd['hp']=$pd['mhp'];
			$pd['skill519_flag']=1;
			\skillbase\skill_setvalue(519,'lastrev',$now,$pd);
			$pd['rivival_news'] = array('revival519', $pd['name']);
		}
		return;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('sys','logger'));
		
		if(!empty($pd['skill519_flag'])){
			if ($pd['o_state']==27)	//陷阱
			{
				$log.= "<span class=\"lime b\">但是，在构成你身躯的粉雾作用下，你原地满血复活了！</span><br>";
				if(!$pd['sourceless']){
					$w_log = "<span class=\"lime b\">但是，{$pd['name']}的身躯在粉雾中重生了！</span><br>";
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
		if (isset($pd['skill519_flag']) && $pd['skill519_flag'])
		{
			if ($active)
			{
				$log.='<span class="lime b">但是，敌人的躯体渗出了浓浓的粉雾。等粉雾散去，你惊讶地发现'.$pd['name'].'毫发无伤地站在原地！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是，在构成你身躯的粉雾作用下，你原地满血复活了！</span>';
			}
			else
			{
				$log.='<span class="lime b">但是，在构成你身躯的粉雾作用下，你原地满血复活了！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是，敌人的躯体渗出了浓浓的粉雾。等粉雾散去，你惊讶地发现'.$pa['name'].'毫发无伤地站在原地！</span>';
			}
		}
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill519_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(519, $pa) || !check_unlocked519($pa)) return 0;
		eval(import_module('sys','player','skill519'));
		$l=\skillbase\skill_getvalue(519,'lastrev',$pa);
		if ($now-$l<=$skill519_cd) return 2;
		return 3;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(519,$sdata))&&check_unlocked519($sdata))
		{
			eval(import_module('skill519'));
			$skill519_lst = (int)\skillbase\skill_getvalue(519,'lastrev'); 
			$skill519_time = $now-$skill519_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 0,
				'hint' => '技能「幽灵」',
			);
			if ($skill519_time<$skill519_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill519_cd;
				$z['nowsec']=$skill519_time;
				$z['activate_hint'] = "技能「幽灵」冷却中";
			}
			else 
			{
				$z['clickable'] = 0;
				$z['style']=3;
				$z['activate_hint'] = "技能「幽灵」冷却完毕";
			}
			\bufficons\bufficon_show('img/skill519.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revival519') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}的躯体在粉雾中重生了！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>