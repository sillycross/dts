<?php

namespace metman
{
	global $tdata;
	global $w_upexp,$battle_title;
	global $hideflag;
	
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
		
		$w_upexp = round(($w_lvl*$baseexp)+(($w_lvl+1)*$baseexp));
		
		if($w_hp <= 0) {
			$tdata['hpstate'] = "<span class=\"red\">$hpinfo[3]</span>";
			$tdata['spstate'] = "<span class=\"red\">$spinfo[3]</span>";
			$tdata['ragestate'] = "<span class=\"red\">$rageinfo[3]</span>";
			$tdata['isdead'] = true;
		} else{
			if($w_hp < $w_mhp*0.2) {
			$tdata['hpstate'] = "<span class=\"red\">$hpinfo[2]</span>";
			} elseif($w_hp < $w_mhp*0.5) {
			$tdata['hpstate'] = "<span class=\"yellow\">$hpinfo[1]</span>";
			} else {
			$tdata['hpstate'] = "<span class=\"clan\">$hpinfo[0]</span>";
			}
			if($w_sp < $w_msp*0.2) {
			$tdata['spstate'] = "$spinfo[2]";
			} elseif($w_sp < $w_msp*0.5) {
			$tdata['spstate'] = "$spinfo[1]";
			} else {
			$tdata['spstate'] = "$spinfo[0]";
			}
			if($w_rage >= 100) {
			$tdata['ragestate'] = "<span class=\"red\">$rageinfo[2]</span>";
			} elseif($w_rage >= 30) {
			$tdata['ragestate'] = "<span class=\"yellow\">$rageinfo[1]</span>";
			} else {
			$tdata['ragestate'] = "$rageinfo[0]";
			}
		}
		
		if($w_wepe >= 400) {
			$tdata['wepestate'] = "$wepeinfo[3]";
		} elseif($w_wepe >= 200) {
			$tdata['wepestate'] = "$wepeinfo[2]";
		} elseif($w_wepe >= 60) {
			$tdata['wepestate'] = "$wepeinfo[1]";
		} else {
			$tdata['wepestate'] = "$wepeinfo[0]";
		}
		
		$tdata['sNoinfo'] = "$typeinfo[$w_type]({$sexinfo[$w_gd]}{$w_sNo}号)";
		$w_i = $w_type > 0 ? 'n' : $w_gd;
		$tdata['iconImg'] = $w_i.'_'.$w_icon.'.gif';
		if ($w_type==0) $tdata['iconImg'] = $w_i.'_'.$w_icon.'a.gif';
		$tdata['name']=$w_name;
		$tdata['wep']=$w_wep;
		$tdata['lvl']=$w_lvl;
		$tdata['wepk']=$w_wepk;
		return;
	}
	
	function calculate_meetman_obbs(&$edata)
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
		
		eval(import_module('sys','player','metman'));
		$find_obbs = calculate_meetman_obbs($edata);
		$hide_obbs = calculate_hide_obbs($edata);
		$enemy_dice = rand(0,99);
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
		return 1;
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
			return check_alive_discover($edata);
		else  
		{
			//为何如此奇葩请看corpse模块的注释
			if (!check_corpse_discover($edata)) return -1;
			return check_corpse_discover_dice($edata);
		}
	}
	
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','metman','logger'));
		$battle_title = '发现人物';
		extract(\player\fetch_playerdata_by_pid($sid),EXTR_PREFIX_ALL,'w');
		init_battle(1);
	
		$log .= "你发现了人物<span class=\"yellow\">$w_name</span>。<br>你友善的打了个招呼。<br>";
		
		include template(MOD_METMAN_MEETMAN_CMD);
		$cmd = ob_get_contents();
		ob_clean();
		$main = MOD_METMAN_MEETMAN;
	}
	
	function calculate_meetman_rate_by_mode($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid' AND state!='16'");
		$pcount=$db->num_rows($result);
		if(!$pcount){//没有人
			if ($schmode == 'search') return 40;
			if ($schmode == 'move') return 40;
			if ($schmode == 'search2') return 40;
		}
		$erate=$pcount*3;
		if ($erate>33) $erate=33;
		if ($schmode == 'search') return 7+$erate;
		if ($schmode == 'move') return 37+$erate;
		if ($schmode == 'search2') return 100;
		return 0;
	}
	
	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return calculate_meetman_rate_by_mode($schmode);
	}

	function discover_player()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','metman'));
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid' AND state!='16'");
		if(!$db->num_rows($result)){
			$log .= '<span class="yellow">周围一个人都没有。</span><br>';
			$mode = 'command';
			return;
		}

		$enemynum = $db->num_rows($result);
		$enemyarray = range(0, $enemynum - 1);
		shuffle($enemyarray);
		
		$hideflag = false;
		foreach($enemyarray as $enum){
			$db->data_seek($result, $enum);
			$z=$db->fetch_array($result);
			$edata = \player\fetch_playerdata_by_pid($z['pid']);
			if (isset($edata['infochanged']) && $edata['infochanged']) \player\player_save($edata);
			if ($edata['pls']==$pls)	
			{
				$z=check_player_discover($edata);
				if ($z==-1)
				{
					//这不是bug，是原版本的奇葩设定，请参见corpse模块注释
					\explore\discover('search2');
					return;
				}
				if ($z)
				{
					meetman($edata['pid']);
					return;
				}
			}
		}
		if($hideflag == true){
			$log .= '似乎有人隐藏着……<br>';
		}else{
			$log .= '<span class="yellow">周围似乎一个人都没有。</span><br>';
		}
		$mode = 'command';
		return;
	}
	
	function discover($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$dice = rand(0,99);
		if($dice < calculate_meetman_rate($schmode)) {
			discover_player();
			return;
		} 
		$chprocess($schmode);
	}
}

?>
