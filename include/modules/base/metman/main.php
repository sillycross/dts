<?php

namespace metman
{
	global $tdata;
	global $w_upexp,$battle_title;
	global $hideflag;
	
	$hidelog = '<span class="yellow b">周围一个人都没有，但你觉得应该有人的……</span><br>';
	
	function init()
	{
		global $tdata; $tdata=Array(); 
		eval(import_module('player'));
		foreach ($db_player_structure as $key) global ${'w_'.$key};
	}
	
	function metman_load_playerdata($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('metman'));
		foreach ($pdata as $key => $value) ${'w_'.$key}=$value;
	}
	
	function init_battle($ismeet = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','metman'));
		
		$tdata=Array();
		$w_upexp = \lvlctl\calc_upexp($w_lvl);
		//$w_upexp = round(($w_lvl*$baseexp)+(($w_lvl+1)*$baseexp));
		
		if($w_hp <= 0) {
			$tdata['hpstate'] = "<span class=\"red b\">$hpinfo[3]</span>";
			$tdata['spstate'] = "<span class=\"red b\">$spinfo[3]</span>";
			$tdata['ragestate'] = "<span class=\"red b\">$rageinfo[3]</span>";
			$tdata['isdead'] = true;
		} else{
			if($w_hp < $w_mhp*0.2) {
			$tdata['hpstate'] = "<span class=\"red b\">$hpinfo[2]</span>";
			} elseif($w_hp < $w_mhp*0.5) {
			$tdata['hpstate'] = "<span class=\"yellow b\">$hpinfo[1]</span>";
			} else {
			$tdata['hpstate'] = "<span class=\"cyan b\">$hpinfo[0]</span>";
			}
			if($w_sp < $w_msp*0.2) {
			$tdata['spstate'] = "$spinfo[2]";
			} elseif($w_sp < $w_msp*0.5) {
			$tdata['spstate'] = "$spinfo[1]";
			} else {
			$tdata['spstate'] = "$spinfo[0]";
			}
			if($w_rage >= 100) {
			$tdata['ragestate'] = "<span class=\"red b\">$rageinfo[2]</span>";
			} elseif($w_rage >= 30) {
			$tdata['ragestate'] = "<span class=\"yellow b\">$rageinfo[1]</span>";
			} else {
			$tdata['ragestate'] = "$rageinfo[0]";
			}
		}
		
		if ($w_wepe >= 400000) {
			$tdata['wepestate'] = "$wepeinfo[7]";
		} elseif ($w_wepe >= 40000) {
			$tdata['wepestate'] = "$wepeinfo[6]";
		} elseif ($w_wepe >= 4000) {
			$tdata['wepestate'] = "$wepeinfo[5]";
		} elseif($w_wepe >= 1000) {
			$tdata['wepestate'] = "$wepeinfo[4]";
		} elseif($w_wepe >= 300) {
			$tdata['wepestate'] = "$wepeinfo[3]";
		} elseif($w_wepe >= 120) {
			$tdata['wepestate'] = "$wepeinfo[2]";
		} elseif($w_wepe >= 60) {
			$tdata['wepestate'] = "$wepeinfo[1]";
		}else {
			$tdata['wepestate'] = "$wepeinfo[0]";
		}
		
		$tdata['sNoinfo'] = $typeinfo[$w_type];
		if(!$w_type) $tdata['sNoinfo'] .= "({$sexinfo[$w_gd]}{$w_sNo}号)";
		else $tdata['sNoinfo'] .= "({$sexinfo[$w_gd]})";
		list($tdata['iconImg'], $tdata['iconImgB'], $tdata['iconImgBwidth']) = \player\icon_parser($w_type, $w_gd, $w_icon);
//		echo 'img/'.$wiconImgB;
//		if(file_exists('img/'.$wiconImgB)) $tdata['iconImg'] = $wiconImgB;
//		else $tdata['iconImg'] = $wiconImg;
//		$w_i = $w_type > 0 ? 'n' : $w_gd;
//		$tdata['iconImg'] = $w_i.'_'.$w_icon.'.gif';
//		if ($w_type==0) $tdata['iconImg'] = $w_i.'_'.$w_icon.'a.gif';
		$tdata['name']=$w_name;
		$tdata['wep']=$w_wep;
		$tdata['lvl']=$w_lvl;
		$tdata['wepk']=$w_wepk;
		return;
	}
	
	function calculate_findman_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('metman'));
		return $metman_obbs;
	}
	
	function calculate_hide_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	function check_alive_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','metman','logger'));
		$find_obbs = calculate_findman_obbs($edata);
		$hide_obbs = calculate_hide_obbs($edata);
		$enemy_dice = rand(0,99);
		//$log .= '最终发现率：'.$find_obbs.' 对方最终隐蔽率：'.$hide_obbs.' 发现骰：'.$enemy_dice.' ';
		if($enemy_dice < $find_obbs - $hide_obbs) 
			return 1; 
		else 
		{ 
			$hideflag=true;
			return 0;
		}
	}
	
	function check_corpse_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return check_corpse_discover_dice($edata);
	}
	
	function check_corpse_discover_dice(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_player_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($edata['hp'] > 0)
		{
			return check_alive_discover($edata);
		}
		else  
		{
			return check_corpse_discover($edata);
		}
	}
	
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		\player\update_sdata();
		$edata = \player\fetch_playerdata_by_pid($sid);
		meetman_alternative($edata);
	}
	
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','metman','logger'));
		$battle_title = '发现人物';
		extract($edata,EXTR_PREFIX_ALL,'w');
		init_battle(1);
		$log .= "你发现了人物<span class=\"yellow b\">$w_name</span>。<br>你友善地打了个招呼。<br>";
		include template(MOD_METMAN_MEETMAN_CMD);
		$cmd = ob_get_contents();
		ob_clean();
		$main = MOD_METMAN_MEETMAN;
		return;
	}
	
	function calculate_meetman_rate_by_mode($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid' AND (hp>'0' OR corpse_clear_flag!='1')");
		$pcount=$db->num_rows($result);
		if(!$pcount){//没有人
			if ($schmode == 'search') return 50;
			if ($schmode == 'move') return 50;
			if ($schmode == 'search2') return 50;
		}
		$erate=$pcount*3;
		if ($erate>30) $erate=30;
		if ($schmode == 'search') return 20+$erate;
		if ($schmode == 'move') return 40+$erate;
		if ($schmode == 'search2') return 100;
		return 0;
	}
	
	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return calculate_meetman_rate_by_mode($schmode);
	}
	
	//部分玩家、尸体数据直接在这里滤掉，不需要反复调用discover()这种蠢写法。平衡性？实际概率跟期望概率完全不是一回事的算法，再平衡也是错的。
	function discover_player_get_epids($spls, $spid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = array();
		eval(import_module('sys'));
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pls='$spls' AND pid!='$spid'");
		while($r = $db->fetch_array($result)){
			if(discover_player_filter($r))
				$ret[] = $r;
		}
		return $ret;
	}
	
	//去掉空尸体目标。
	//需要在读数据库时就实现“遇不到玩家”“摸不到尸体”之类判定的技能也可以继承这个函数，或者下面那俩函数
	function discover_player_filter($edata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($edata['hp'] > 0){
			return discover_player_filter_alive($edata);
		}else{
			return discover_player_filter_corpse($edata);
		}
	}
	
	function discover_player_filter_alive(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
	}
	
	function discover_player_filter_corpse(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = false;//先默认所有尸体都是空的
		if(1 != $edata['corpse_clear_flag']){ //如果尸体已被标记为清除，强制认为是空的，不用继续判定
			//未标记为清除的尸体，则逐个判定各部位的耐久，如果任一有非0值，则认为是非空尸体，否则仍为空
			if($edata['weps'] && ($edata['wepk']!='WN' || $edata['wepe'] > 0)) $ret = true;//武器和防具特判
			if($edata['arbs'] && ($edata['arbk']!='DN' || $edata['arbe'] > 0)) $ret = true;
			foreach( array('money','arhs','aras','arfs','arts','itms1','itms2','itms3','itms4','itms5','itms6') as $chkval){
				if($edata[$chkval]) {
					$ret = true;
					break;
				}
			}
		}
		return $ret;
	}
	
	//如果发现玩家，返回true
	function discover_player()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','metman'));
		$edata_arr = discover_player_get_epids($pls, $pid);

		if(!sizeof($edata_arr)){
			$log .= '<span class="yellow b">周围一个人都没有。</span><br>';
			$mode = 'command';
			return false;
		}
		
		shuffle($edata_arr);
		
		$hideflag = false;
		foreach($edata_arr as $ed){
			$edata = \player\fetch_playerdata_by_pid($ed['pid']);
			if (isset($edata['infochanged']) && $edata['infochanged']) \player\player_save($edata);
			if ($edata['pls']==$pls)	
			{
				$z=check_player_discover($edata);
				//这一奇葩设定被干掉了，真是大快人心啊
//				{
//					//这不是bug，是原版本的奇葩设定，请参见corpse模块注释
//					\explore\discover('search2');
//					return;
//				}
				if ($z)
				{
					meetman($edata['pid']);
					return true;
				}
			}
			else {
				$log .= '你发现了'.$edata['name'].'，但对方匆匆离去，你追之不及。<br>';
			}
		}
		if($hideflag == true){
			$log .= '似乎有人隐藏着……<br>';
		}else{
			eval(import_module('metman'));
			$log .= $hidelog;
		}
		$mode = 'command';
		return false;
	}
	
	function discover($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//echo 'metman ';
		$dice = rand(0,99);
		eval(import_module('logger'));
		$meetman_rate = calculate_meetman_rate($schmode);
		if($meetman_rate < 20) $meetman_rate = 20;//任何时候遇敌率不低于20%；
		//$log .= '发现玩家判定：骰'.$dice.' 阈：'.$meetman_rate.' ';
		if($dice < $meetman_rate) {
			$ret = discover_player();
			if(!can_continue_post_discover_player($ret)) return $ret;
		} 
		return $chprocess($schmode);
	}
	
	//探索玩家流程结束以后是否能够继续探索道具
	function can_continue_post_discover_player($dpret)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return false;
	}
}

?>