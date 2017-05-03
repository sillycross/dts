<?php

namespace weather
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['EW'] = '天气控制';
	}
	
	function calculate_weather_itemfind_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','weather'));
		return $weather_itemfind_obbs[$weather];
	}
	
	function calculate_itemfind_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $chprocess()+calculate_weather_itemfind_obbs();
	}
	
	function calculate_weather_meetman_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','weather'));
		return $weather_meetman_obbs[$weather];
	}
	
	function calculate_findman_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $chprocess($edata)+calculate_weather_meetman_obbs($edata);
	}
	
	function calculate_weather_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','weather'));
		return $weather_active_obbs[$weather];
	}
	
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $chprocess($ldata,$edata)+calculate_weather_active_obbs($ldata,$edata);
	}
	
	function calculate_weather_attack_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','weather'));
		return 1+$weather_attack_modifier[$weather]/100;
	}
	
	function get_att_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $chprocess($pa,$pd,$active)*calculate_weather_attack_modifier($pa,$pd,$active);
	}
	
	function calculate_weather_defend_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','weather'));
		return 1+$weather_defend_modifier[$weather]/100;
	}
	
	function get_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $chprocess($pa,$pd,$active)*calculate_weather_defend_modifier($pa,$pd,$active);
	}
	
	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','weather'));
		if($weather == 12)
			return $chprocess($pa,$pd,$active)+20;
		else  return $chprocess($pa,$pd,$active);
	}
	
	function calculate_hailstorm_weather_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return round($mhp/12) + rand(0,20);
	}
	
	function deal_hailstorm_weather_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$damage = calculate_hailstorm_weather_damage();
		$hp -= $damage;
		$log .= "被<span class=\"blue\">冰雹</span>击中，生命减少了<span class=\"red\">$damage</span>点！<br>";
		if($hp <= 0 ) {
			$state = 17;
			\player\update_sdata();
			$sdata['selflag'] = 1;
			\player\kill($sdata,$sdata);
			return;
		}
	}
	
	function apply_tornado_weather_effect()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','player','logger'));
		if($hack)
		{
			$pls = rand(0,sizeof($plsinfo)-1);
		}
		else 
		{
			$pls = rand($areanum+1,sizeof($plsinfo)-1);$pls=$arealist[$pls];
		}
		$log .= "但是强烈的龙卷风把你吹到了<span class=\"yellow\">$plsinfo[$pls]</span>！<br>";
	}
	
	function move_to_area($moveto)	//天气对移动的特效
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player','logger'));
		if($weather == 11)	//龙卷风
		{
			apply_tornado_weather_effect();
		} 
		else  if($weather == 13) 	//冰雹
		{
			deal_hailstorm_weather_damage();
			if ($hp<=0) return;
		} 
		else  if($weather == 14)	//离子暴
		{
			$dice = rand(0,9);
			if($dice ==0 && strpos($inf,'e')===false){
				$log .= "空气中充斥着的<span class=\"linen\">狂暴电磁波</span>导致你<span class=\"yellow\">身体麻痹</span>了！<br>";
				\wound\get_inf('e');
			}elseif($dice ==1 && strpos($inf,'w')===false){
				$log .= "空气中充斥着的<span class=\"linen\">狂暴电磁波</span>导致你<span class=\"grey\">混乱</span>了！<br>";
				\wound\get_inf('w');
			}else{
				$log .= "空气中充斥着狂暴的电磁波……<br>";
			}
		} 
		else  if($weather == 15)	//辐射尘
		{
			$dice = rand(0,9);
			if($dice == 0){
				$mhpdown = rand(4,8);
				if($mhp > $mhpdown){
					$log .= "空气中弥漫着的<span class=\"green\">放射性尘埃</span>导致你的生命上限减少了<span class=\"red\">{$mhpdown}</span>点！<br>";
					$mhp -= $mhpdown;
					if($hp > $mhp){$hp = $mhp;}
				}
			}else{
				$log .= "空气中弥漫着放射性尘埃……<br>";
			}
		} 
		else  if($weather == 16)	//臭氧洞
		{
			$dice = rand(0,9);
			if($dice == 0){
				$defdown = rand(3,6);
				if($def > $defdown){
					$log .= "高强度的<span class=\"purple\">紫外线照射</span>导致你的防御力减少了<span class=\"red\">{$defdown}</span>点！<br>";
					$def -= $defdown;
				}
			}elseif($dice ==1 && strpos($inf,'u')===false){
				$log .= "高强度的<span class=\"purple\">紫外线照射</span>导致你<span class=\"red\">烧伤</span>了！<br>";
				\wound\get_inf('u');
			}else{
				$log .= "高强度的紫外线灼烧着大地……<br>";
			}
		} 
		$chprocess($moveto);
	}
	
	function search_area()	//天气对探索的特效
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player','logger'));
		if($weather == 13) 
		{
			deal_hailstorm_weather_damage();
			if ($hp<=0) return;
		} 
		else  if($weather == 14)	//离子暴
		{
			$dice = rand(0,9);
			if($dice ==0 && strpos($inf,'e')===false){
				$log .= "空气中充斥着的<span class=\"linen\">狂暴电磁波</span>导致你<span class=\"yellow\">身体麻痹</span>了！<br>";
				\wound\get_inf('e');
			}elseif($dice ==1 && strpos($inf,'w')===false){
				$log .= "空气中充斥着的<span class=\"linen\">狂暴电磁波</span>导致你<span class=\"grey\">混乱</span>了！<br>";
				\wound\get_inf('w');
			}else{
				$log .= "空气中充斥着狂暴的电磁波……<br>";
			}
		} 
		else  if($weather == 15)	//辐射尘
		{
			$dice = rand(0,9);
			if($dice == 0){
				$mhpdown = rand(4,8);
				if($mhp > $mhpdown){
					$log .= "空气中弥漫着的<span class=\"green\">放射性尘埃</span>导致你的生命上限减少了<span class=\"red\">{$mhpdown}</span>点！<br>";
					$mhp -= $mhpdown;
					if($hp > $mhp){$hp = $mhp;}
				}
			}else{
				$log .= "空气中弥漫着放射性尘埃……<br>";
			}
		} 
		else  if($weather == 16)	//臭氧洞
		{
			$dice = rand(0,9);
			if($dice == 0){
				$defdown = rand(3,6);
				if($def > $defdown){
					$log .= "高强度的<span class=\"purple\">紫外线照射</span>导致你的防御力减少了<span class=\"red\">{$defdown}</span>点！<br>";
					$def -= $defdown;
				}
			}elseif($dice ==1 && strpos($inf,'u')===false){
				$log .= "高强度的<span class=\"purple\">紫外线照射</span>导致你<span class=\"red\">烧伤</span>了！<br>";
				\wound\get_inf('u');
			}else{
				$log .= "高强度的紫外线灼烧着大地……<br>";
			}
		} 
		$chprocess();
	}
	
	//天气控制道具
	function wthchange($itm,$itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player','logger','weather'));
		if($weather >= 14 && $weather <= 16){
			addnews ( $now, 'wthfail', $name, $weather, $itm );
			$log .= "你使用了{$itm}。<br /><span class=\"red\">但是恶劣的天气并未发生任何变化！</span><br />";
		}else{
			if($itmsk==99){$weather = rand ( 0, 13 );}//随机全天气
			elseif($itmsk==98){$weather = rand ( 10, 13 );}//随机恶劣天气
			elseif($itmsk==97){$weather = rand ( 0, 9 );}//随机一般天气
			elseif($itmsk==96){$weather = rand ( 8, 9 );}//随机起雾天气
			elseif(!empty($itmsk) && is_numeric($itmsk)){
				if($itmsk >=0 && $itmsk < count($wthinfo)){
					$weather = $itmsk;
				}else{$weather = 0;}
			}
			else{$weather = 0;}
			save_gameinfo ();
			addnews ( $now, 'wthchange', $name, $weather, $itm );
			$log .= "你使用了{$itm}。<br />天气突然转变成了<span class=\"red\">$wthinfo[$weather]</span>！<br />";
		}
		return;
	}
	
	
	
	function add_once_area($atime)	//增加禁区天气变化
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		$o_weather = $weather;
		do { $weather = rand(0,9); } while($weather == $o_weather);		//天气不会跟原本天气一样
		$chprocess($atime);
	}
	
	function rs_game($xmode = 0) 	//开局天气初始化
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		if ($xmode & 2) 
		{
			$opening_weather_list = array(0, 2, 3, 4, 7);//开局只会是晴天 多云 小雨 暴雨 下雪
			shuffle($opening_weather_list);
			$weather = $opening_weather_list[0];
		}
	}
	
	function init_playerdata()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(($weather == 8)||($weather == 9)||($weather == 12)) 
		{
			$fog = true;
		}
		$chprocess();
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ( strpos( $itmk,'EW' ) ===0 )	
		{
			wthchange ( $itm,$itmsk);
			\itemmain\itms_reduce($theitem);
			return;
		}
		$chprocess($theitem);
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','weather'));
		if($news == 'wthchange') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了{$c}，天气变成了{$wthinfo[$b]}！</span><br>\n";
		if($news == 'wthfail') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了{$c}，但是恶劣的天气并未发生改变！</span><br>\n";
		if($news == 'syswthchg') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">奇迹和魔法都是存在的！当前天气变成了{$wthinfo[$a]}！</span><br>\n";
		
		if($news == 'death17') 
		{
			$dname = $typeinfo[$b].' '.$a;
			if(!$e){
				$e="<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
			}else{
				$e="<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
			}
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">冰雹砸死</span>{$e}";
		}
		
		if($news == 'addarea') 
		{
			$info = $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
			$info .= "<span class=\"yellow\">【天气：{$wthinfo[$b]}】</span><br>\n";
			return $info;
		}
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function newradar($mms = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger'));
		if($weather == 14)
		{
			$log .= '由于<span class="linen">离子风暴</span>造成了电磁干扰，探测仪器完全显示不出信息……<br>';
			return;
		}
		$chprocess($mms);
	}
	
	function apply_fog_meetenemy_effect($ismeet)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','metman'));
		if ($fog && !$ismeet)
		{
			$tdata['sNoinfo'] = '？？？';
			$tdata['iconImg'] = 'question.gif';
			$tdata['name'] = '？？？';
			$tdata['wep'] = '？？？';
			$tdata['infdata'] = '？？？';
			$tdata['pose'] = '？？？';
			$tdata['tactic'] = '？？？';
			$tdata['lvl'] = '？';
			$tdata['hpstate'] = '？？？';
			$tdata['spstate'] = '？？？';
			$tdata['ragestate'] = '？？？';
			$tdata['wepestate'] = '？？？';
			$tdata['wepk'] = '？？？';
		}
	}
	
	function init_battle($ismeet = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($ismeet);
		apply_fog_meetenemy_effect($ismeet);
	}
}

?>
