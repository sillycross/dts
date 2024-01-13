<?php

namespace skill48
{
	//怒气消耗
	$ragecost = 8; 
	
	//$wepk_req = 'WC';
	$wep_skillkind_req = 'wc';
	
	//共享属性加成的属性
	$skill48_ex_map = Array(
		'f' => 'u',
		'k' => 'i',
		't' => 'w',
	);
	
	$skill48stateinfo = array(1 => '关闭', 2 => '开启');
	
	function init() 
	{
		define('MOD_SKILL48_INFO','club;battle;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[48] = '附魔';
		eval(import_module('itemmain','ex_dmg_att'));
		global $skill48_ex_map, $skill48_ex_kind_list; 
		$skill48_ex_kind_list=Array();
		foreach ($ex_attack_list as $key)
			if (!isset($skill48_ex_map[$key]))
			{
				$str=$itemspkinfo[$key];
				foreach ($skill48_ex_map as $k => $v)
					if ($v==$key)
						$str.='/'.$itemspkinfo[$k];
				$skill48_ex_kind_list[$key]=$str;
			}
	}
	
	function acquire48(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill48'));
		\skillbase\skill_setvalue(48,'tot','0',$pa);
		\skillbase\skill_setvalue(48,'choice','1',$pa);
		foreach ($skill48_ex_kind_list as $key => $value)
			\skillbase\skill_setvalue(48,$key,'0',$pa);
	}
	
	function lost48(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill48'));
		\skillbase\skill_delvalue(48,'tot',$pa);
		\skillbase\skill_delvalue(48,'choice',$pa);
		foreach ($skill48_ex_kind_list as $key => $value)
			\skillbase\skill_delvalue(48,$key,$pa);
	}
	
	function check_unlocked48(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=5;
	}
	
	function upgrade48()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(48) || !check_unlocked48($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val < 1 || $val > 2)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(48,'choice',$val);
		if(1 == $val) $log .= '现在不会默认发动<span class="yellow b">「附魔」</span>。<br>';
		else $log .= '现在会默认发动<span class="yellow b">「附魔」</span>。<br>';
	}
	
	function get_rage_cost48(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill48'));
		return $ragecost;
	}
	
	function get_single_status_html48($key, $value)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$z=(int)\skillbase\skill_getvalue(48,$key);
		if ($z<15) 
			$sty='<span class="grey b">';
		else if ($z<30)
			$sty='<span style="color:#ffff88">';
		else if ($z<60)
			$sty='<span style="color:#ffff00;">';
		else if ($z<90)
			$sty='<span style="color:#ff7700;">';
		else  $sty='<span style="color:#ff0000;">';

		$str=$sty.$value.':+'.$z.'%</span>';
		return $str;
	}
	
	function get_status_html48()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//以相对智能的方式生成html
		eval(import_module('skill48'));
		$sz=count($skill48_ex_kind_list);
		if ($sz%4==0)
			$lz=4;
		else if ($sz%3==0) 
			$lz=3;
		else if ($sz%4>=$sz%3)
			$lz=4;
		else  $lz=3;
		
		$s=Array();
		foreach ($skill48_ex_kind_list as $key => $value) $s[$key]=strlen($value);
		asort($s);
		
		$t=Array();
		foreach ($s as $key => $value) array_push($t,$key);
		
		$rz=floor($sz/$lz); $rz=(int)$rz; 
		for ($i=0; $i<$rz; $i++)
		{
			for ($j=0; $j<$lz; $j++)
			{
				$id=$j*$rz+$i; $key=$t[$id]; $value=$skill48_ex_kind_list[$key];
				if ($j>0) echo '&nbsp;&nbsp';
				echo get_single_status_html48($key,$value);
			}
			echo '<br>';
		}
		if ($sz%$lz!=0)
		{
			for ($i=$sz-$sz%$lz; $i<$sz; $i++)
			{
				$key=$t[$i]; $value=$skill48_ex_kind_list[$key];
				if ($i>0) echo '&nbsp;&nbsp';
				echo get_single_status_html48($key,$value);
			}
			echo '<br>';
		}
	}
	
	function check_available_attr48(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		if(empty($pa['bskill'])) $pa['bskill']=0;
		if(empty($pa['wep_kind'])) $pa['wep_kind']=\weapon\get_attack_method($pa);
		$att_arr = \attrbase\get_ex_attack_array_core($pa, $pd, $active);
		return array_intersect($att_arr, $ex_attack_list);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($active && \skillbase\skill_query(48,$pa) && check_unlocked48($pa) && empty($pa['bskill']) && (2 == \skillbase\skill_getvalue(48,'choice',$pa))) $pa['bskill']=48;
		if ($pa['bskill']!=48) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(48,$pa) || !check_unlocked48($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost48($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,48) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「附魔」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「附魔」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill48', $pa['name'], $pd['name'] );
				//原花雨技能
//				eval(import_module('ex_dmg_att'));
//				$att_arr = \attrbase\get_ex_attack_array_core($pa, $pd, $active);
				//var_dump(array_intersect($att_arr, $ex_attack_list));
				if(empty(check_available_attr48($pa, $pd, $active))){
					$lis = Array('p', 'u', 'i', 'e', 'w');
					$pa['skill48_flag2']=array_randompick($lis);
				}
				
				//更新附魔发动次数
				$ori_val=(int)\skillbase\skill_getvalue(48,'tot',$pa);
				$ori_val++;
				\skillbase\skill_setvalue(48,'tot',$ori_val,$pa);
		
				eval(import_module('ex_dmg_att','skill48'));
				$lis=Array();
				$ex_attack_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
				foreach ( $ex_attack_list as $key )
					if (\attrbase\check_in_itmsk($key, $ex_attack_array))
						if (isset($skill48_ex_map[$key]))
							array_push($lis,$skill48_ex_map[$key]);
						else  array_push($lis,$key);
				
				if (count($lis)>0)
					$pa['skill48_flag']=$lis[rand(0,count($lis)-1)];
				else  unset($pa['skill48_flag']);
			}
			else
			{
				// if ($active)
				// {
					// eval(import_module('logger'));
					// $log.='怒气不足或其他原因不能发动。<br>';
				// }
				$pa['bskill']=0;
			}
		}
		$chprocess($pa, $pd, $active);
	}	
	
	//主动发动附魔，叠属性伤害加成
	function ex_attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=$chprocess($pa, $pd, $active);
		if ($pa['bskill']==48) {
			eval(import_module('itemmain','logger'));
			$headword = '技能「附魔」';
			if(!empty($pa['skill48_flag2'])){
				$log.='技能「附魔」附加了<span class="yellow b">'.$itemspkinfo[$pa['skill48_flag2']].'</span>属性伤害！<br>';
				$headword = '并';
			} 
			if (isset($pa['skill48_flag']))
			{
				if ($active)
					$log.=$headword.'使你的<span class="yellow b">'.$itemspkinfo[$pa['skill48_flag']].'</span>伤害永久提高了<span class="yellow b">3%</span>。<br>';
				else  $log.=$headword.'使敌人的<span class="yellow b">'.$itemspkinfo[$pa['skill48_flag']].'</span>伤害永久提高了<span class="yellow b">3%</span>。<br>';
				$ori_val=(int)\skillbase\skill_getvalue(48,$pa['skill48_flag'],$pa);
				$ori_val+=3;
				if ($ori_val>150) $ori_val=150;
				\skillbase\skill_setvalue(48,$pa['skill48_flag'],$ori_val,$pa);
			}
		}
		
		return $r;
	}
	
	//属性伤害增加（被动）
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(48,$pa) || !check_unlocked48($pa)) return $chprocess($pa, $pd, $active, $key);
		
		eval(import_module('skill48'));
		$z=$key; if (isset($skill48_ex_map[$z])) $z=$skill48_ex_map[$z];
		$ori_val=(int)\skillbase\skill_getvalue(48,$z,$pa);
		return $chprocess($pa, $pd, $active, $key)*(1+$ori_val/100);
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=$chprocess($pa, $pd, $active);
		if ($pa['bskill']==48 && !empty($pa['skill48_flag2'])) {
			array_push($r,$pa['skill48_flag2']);
		}
		return $r;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill48') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「附魔」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>