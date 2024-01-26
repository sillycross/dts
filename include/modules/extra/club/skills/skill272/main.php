<?php

namespace skill272
{
	$skill272_cd = 120;
	$skill272_act_time = 60;
	$skill272_factor = 10;//每种吸收的属性+10%属性伤害
	$skill272_pos_list = Array('wep','arh','arb','ara','arf','art','itm1','itm2','itm3','itm4','itm5','itm6');//可以吸收的部位
	
	$skill272_itmsk_cube = array(
		'c'=>'黄色方块', 'l'=>'X方块', 'g'=>'Y方块',
		'P'=>'红色方块', 'K'=>'绿色方块', 'G'=>'蓝色方块', 'C'=>'金色方块', 'D'=>'黄色方块', 'F'=>'银色方块',
		'p'=>'绿色方块', 'q'=>'绿色方块', 'u'=>'红色方块', 'U'=>'红色方块', 'i'=>'蓝色方块', 'I'=>'蓝色方块', 'e'=>'金色方块', 'E'=>'金色方块', 'w'=>'银色方块', 'W'=>'银色方块'
	);
	
	function init() 
	{
		define('MOD_SKILL272_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[272] = '吸光';
	}
	
	function acquire272(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(272,'lastuse',0,$pa);
		\skillbase\skill_setvalue(272,'unlockcount',0,$pa);
		\skillbase\skill_setvalue(272,'lvl',0,$pa);
		\skillbase\skill_setvalue(272,'num',0,$pa);
	}
	
	function lost272(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(272,'lastuse',$pa);
		\skillbase\skill_delvalue(272,'unlockcount',$pa);
		\skillbase\skill_delvalue(272,'lvl',$pa);
		\skillbase\skill_delvalue(272,'num',$pa);
	}
	
	function check_unlocked272(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function skill272_command()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','skill272'));
		if (!\skillbase\skill_query(272) || !check_unlocked272($sdata)) 
		{
			$log.='你没有这个技能。';
			$mode='command';
			return;
		}
		$subcmd = get_var_input('subcmd');
		$skill272_ipos = get_var_input('skill272_ipos');
		if ('activate'==$subcmd && !empty($skill272_ipos))
		{
			activate272($skill272_ipos);
			return;
		}
		$flag = 0;
		$effect_list = create_effect_list272();
		foreach ($skill272_pos_list as $val){
			if(check_valid_single272($val, 1, $effect_list)) $flag = 1;
		}
		if(!$flag) {
			$log.='你身上的装备道具全都不符合要求！';
			$mode='command';
			return;
		}
		include template(MOD_SKILL272_SKILL272_COMMAND);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	//判定一个位置的道具有没有效，同时也返回显示
	function check_valid_single272($ipos, $check_only=0, $effect_list=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$effect_list) $effect_list = create_effect_list272();
		eval(import_module('skill272','player','sys','logger'));
		$alert = '';
		$flag = 1;
		if (!in_array($ipos, $skill272_pos_list))
		{
			$alert.='你选择的道具无效，请重新选择。<br>';
			$flag = 0;
		}
		if(strpos($ipos, 'itm')===0) {
			$itm=&${$ipos};
			$itmk=&${'itmk'.substr($ipos,3,1)};
			$itme=&${'itme'.substr($ipos,3,1)};
			$itms=&${'itms'.substr($ipos,3,1)};
			$itmsk=&${'itmsk'.substr($ipos,3,1)};
		}else{
			$itm=&${$ipos};
			$itmk=&${$ipos.'k'};
			$itme=&${$ipos.'e'};
			$itms=&${$ipos.'s'};
			$itmsk=&${$ipos.'sk'};
		}
		if ($itmk=='WN')
		{
			$alert.='你不能吸收你自己的拳头。<br>';
			$flag = 0;
		}elseif ($itms <= 0 && $itms != $nosta) 
		{
			$alert.='道具不存在，请重新选择。<br>';
			$flag = 0;
		}elseif (!$itmsk || is_numeric($itmsk)) 
		{
			$alert.='该道具无属性，请重新选择。<br>';
			$flag = 0;
		}
		list($effect_num, $itmsk_after) = check_skill272num_single($itmsk, $effect_list);
		if(!$effect_num) 
		{
			$alert.='该道具没有可以吸收的属性，请重新选择。<br>';
			$flag = 0;
		}
		if(!$check_only && !$flag){
			$log .= $alert;
		}
		return $flag;
	}
	
	//即时生成可以吸光的属性
	function create_effect_list272(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//eval(import_module('skill272','skill23'));
//		$effect_list = Array();
//		foreach($bufflist23 as $bval){
//			foreach($bval as $eval){
//				foreach($eval as $val){
//					if(!in_array($val, array('z'))) $effect_list[] = $val[1];
//				}
//			}
//		}
//		$effect_list = array_unique($effect_list);
		
		//现在改成只吸下位5属性的攻击、防御属性，6系的防御属性，以及集气属性、同志、热恋属性
		$effect_list = Array('c','l','g');
		eval(import_module('ex_phy_def'));
		foreach($def_kind as $dv){
			$effect_list[] = $dv;
		}
		eval(import_module('ex_dmg_def'));
		foreach($def_kind as $dk => $dv){
			$effect_list[] = $dk;
			$effect_list[] = $dv;
		}
		$effect_list = array_diff($effect_list,array('d'));//去除爆炸属性
		$effect_list = array_unique($effect_list);
		return $effect_list;
	}
	
	function check_skill272num_single( $itmsk, $effect_list=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$effect_list) $effect_list = create_effect_list272();
		$effect_num = 0;
		$itmsk_after = $itmsk;
		$affected_arr = array();
		//吸光这里好像是倒过来效率更高，就单独实现吧，不调用replace_in_itmsk()了
		if(!empty($itmsk)) {
			//echo $itmsk.'=>';
			$itmsk_arr = \itemmain\get_itmsk_array($itmsk,1);//不忽略竖线
			foreach($itmsk_arr as &$isk){
				if(in_array($isk, $effect_list)){
					$effect_num ++ ;
					$affected_arr[] = $isk;
					$isk = '';
				}
			}
			$itmsk_after = implode('', $itmsk_arr);
			//echo $itmsk_after.'  ';
		}
		
		return array($effect_num, $itmsk_after, $affected_arr);
	}
	
	function activate272($ipos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(272) || !check_unlocked272($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill272_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==1){
			$log.='你已经发动过这个技能了！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		//即时生成可以吸光的属性
		$effect_list = create_effect_list272();
		
		if(!check_valid_single272($ipos, 0, $effect_list)){
			$mode = 'command';
			return;
		}
		$itm = &${$ipos};
		if(strpos($ipos, 'itm')===0) {
			$itmsk=&${'itmsk'.substr($ipos,3,1)};
		}else{
			$itmsk=&${$ipos.'sk'};
		}
		//计算可以抹掉多少个属性
		list($effect_num, $itmsk_after, $affected_arr) = check_skill272num_single($itmsk, $effect_list);
		if(!$effect_num) {
			$log.='该道具没有任何可以吸收的属性。<br>';
			$mode = 'command';
			return;
		}
		$itmsk = $itmsk_after;
		\skillbase\skill_setvalue(272,'lastuse',$now);
		\skillbase\skill_setvalue(272,'num',$effect_num);
		addnews ( 0, 'bskill272', $name );
		eval(import_module('skill272','itemmain'));
		$tmp_list='';
		$cubes = array();
		foreach($affected_arr as $eval){
			$tmp_list.=$itemspkinfo[$eval].'+';
			//概率得到方块
			if (rand(0,99) < 30)
			{
				$citm = $skill272_itmsk_cube[$eval];
				$cubes[] = $citm;
				$dropid = \itemmain\itemdrop_query($citm, 'X', 1, 1, '', $pls);
				$amarr = array('iid' => $dropid, 'itm' => $citm, 'pls' => $pls, 'unseen' => 0);
				\skill1006\add_beacon($amarr, $sdata);
			}
		}
		$clv = (int)\skillbase\skill_getvalue(272,'lvl',$sdata);
		if (!$clv)
		{
			$ucount = (int)\skillbase\skill_getvalue(272,'unlockcount',$sdata);
			$ucount += count($affected_arr);
			if ($ucount >= 15)
			{
				$log .= "<span class=\"yellow b\">你可以更熟练地使用「吸光」了。</span><br><br>";
				\skillbase\skill_setvalue(272,'lvl',1,$sdata);
			}
			else \skillbase\skill_setvalue(272,'unlockcount',$ucount,$sdata);
		}
		$extrac_rate = $clv ? 20 : 5;
		if (rand(0,99) < $extrac_rate)
		{
			$citm = array_randompick(array('X方块','X方块','Y方块','Y方块','黄鸡方块'));
			$cubes[] = $citm;
			$dropid = \itemmain\itemdrop_query($citm, 'X', 1, 1, '', $pls);
			$amarr = array('iid' => $dropid, 'itm' => $citm, 'pls' => $pls, 'unseen' => 0);
			\skill1006\add_beacon($amarr, $sdata);
		}
		if(!empty($tmp_list)) $tmp_list = str_replace('+','、',substr($tmp_list,0,-1));
		
		$log.='<span class="lime b">技能「吸光」发动成功。</span><br>你将'.$itm.'上的'.$tmp_list.'属性化为了自己的力量！<br>效果时间内，你的属性伤害将<span class="cyan b">增加'.($skill272_factor*$effect_num).'%</span>。<br>';
		if(!empty($cubes)) {
			$cube_count = count($cubes);
			if ($cube_count > 1) $cube_txt = implode('、', array_slice($cubes, 0, $cube_count - 1)).'和'.end($cubes);
			else $cube_txt = $cubes[0];
			$log .= "<span class=\"yellow b\">$cube_txt</span>在光芒中凝结而成，掉在了你的身旁。<br>";
		}
		$mode = 'command';
		return;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill272_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(272, $pa) || !check_unlocked272($pa)) return 0;
		eval(import_module('sys','player','skill272'));
		$l=\skillbase\skill_getvalue(272,'lastuse',$pa);
		$clv = (int)\skillbase\skill_getvalue(272,'lvl',$pa);
		if (($now-$l)<=$skill272_act_time) return 1;
		if (!$clv && (($now-$l)<=$skill272_act_time+$skill272_cd)) return 2;
		return 3;
	}
	
	/*function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(272,$pd) || !(check_skill272_state($pd)==1) || $pd['club']!=2 || $pd['wepk']!='WK') return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*0.75;
	}*/
	
	function calculate_ex_attack_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(272,$pa) && check_unlocked272($pa) && 1==check_skill272_state($pa))
		{
			eval(import_module('logger','skill272'));
			$effect = $skill272_factor * \skillbase\skill_getvalue(272,'num',$pa);
			if(empty($pa['skill272_log'])){
				$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">「吸光」使<:pa_name:>的属性伤害增强了'.$effect.'%！</span><br>');
				$pa['skill272_log'] = 1;
			}
			$r[] = 1 + $effect / 100;
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
//	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$ret = $chprocess($pa, $pd, $active, $key);
//		if (\skillbase\skill_query(272,$pa) && check_unlocked272($pa) && 1==check_skill272_state($pa)){
//			eval(import_module('logger','skill272'));
//			$effect = $skill272_factor * \skillbase\skill_getvalue(272,'num',$pa);
//			if(empty($pa['skill272_log'])){
//				$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b">「吸光」使<:pa_name:>的属性伤害增强了'.$effect.'%！</span><br>');
//				$pa['skill272_log'] = 1;
//			}
//			return $ret * (1 + $effect / 100);
//		}
//			
//		else return $ret;
//	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(272,$sdata))&&check_unlocked272($sdata))
		{
			eval(import_module('skill272'));
			$skill272_lst = (int)\skillbase\skill_getvalue(272,'lastuse'); 
			$skill272_clv = (int)\skillbase\skill_getvalue(272,'lvl');
			$skill272_time = $now-$skill272_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「吸光」',
				'activate_hint' => '点击发动技能「吸光」',
				'onclick' => "$('mode').value='special';$('command').value='skill272_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill272_time<$skill272_act_time)
			{
				$z['style']=1;
				$z['totsec']=$skill272_act_time;
				$z['nowsec']=$skill272_time;
			}
			elseif (!$skill272_clv && ($skill272_time<$skill272_act_time+$skill272_cd))
			{
				$z['style']=2;
				$z['totsec']=$skill272_cd;
				$z['nowsec']=$skill272_time-$skill272_act_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill272.gif',$z);
		}
		$chprocess();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
	
		if ($mode == 'special' && $command == 'skill272_special') 
		{
			skill272_command();
			return;
		}
			
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill272') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「吸光」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>