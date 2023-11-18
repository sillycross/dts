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
//		eval(import_module('sys','weather'));
//		echo "当前天气：".$wthinfo[$weather].' ';
//		echo "天气修正：+".calculate_weather_active_obbs($ldata,$edata).'% <br>';
		$add = calculate_weather_active_obbs($ldata,$edata);
		$ldata['active_words'] = \attack\add_format($add, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)+$add;
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
		$ret = $chprocess($pa,$pd,$active);
		$var = calculate_weather_attack_modifier($pa,$pd,$active);
		array_unshift($ret, $var);
		return $ret;
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
		$ret = $chprocess($pa,$pd,$active);
		$var = calculate_weather_defend_modifier($pa,$pd,$active);
		array_unshift($ret, $var);
		return $ret;
	}
	
	function get_hitrate_base(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','weather'));
		$ret = $chprocess($pa,$pd,$active);
		$a = 0;
		if($weather == 12)
			$a=20;
		return $ret+$a;
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
		$log .= "被<span class=\"blue b\">冰雹</span>击中，生命减少了<span class=\"red b\">$damage</span>点！<br>";
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
		$log .= "但是强烈的龙卷风把你吹到了<span class=\"yellow b\">$plsinfo[$pls]</span>！<br>";
	}
	
	function move_to_area($moveto)	//天气对移动的特效
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player','logger'));
		if($weather == 11)	//龙卷风
		{
			apply_tornado_weather_effect();
		} 
		elseif($weather == 13) 	//冰雹
		{
			deal_hailstorm_weather_damage();
			if ($hp<=0) return false;
		} 
		elseif($weather == 14)	//离子暴
		{
			$dice = rand(0,9);
			if($dice ==0 && strpos($inf,'e')===false){
				$log .= "空气中充斥着的<span class=\"linen b\">狂暴电磁波</span>导致你<span class=\"yellow b\">身体麻痹</span>了！<br>";
				\wound\get_inf('e');
			}elseif($dice ==1 && strpos($inf,'w')===false){
				$log .= "空气中充斥着的<span class=\"linen b\">狂暴电磁波</span>导致你<span class=\"grey b\">混乱</span>了！<br>";
				\wound\get_inf('w');
			}else{
				$log .= "空气中充斥着狂暴的电磁波……<br>";
			}
		} 
		elseif($weather == 15)	//辐射尘
		{
			$dice = rand(0,9);
			if($dice == 0){
				$mhpdown = rand(1,4);
				if($mhp > $mhpdown){
					$log .= "空气中弥漫着的<span class=\"green b\">放射性尘埃</span>导致你的生命上限减少了<span class=\"red b\">{$mhpdown}</span>点！<br>";
					$mhp -= $mhpdown;
					if($hp > $mhp){$hp = $mhp;}
				}
			}else{
				$log .= "空气中弥漫着放射性尘埃……<br>";
			}
		} 
		elseif($weather == 16)	//臭氧洞
		{
			$dice = rand(0,9);
			if($dice == 0){
				$defdown = rand(2,5);
				if($def > $defdown){
					$log .= "高强度的<span class=\"purple b\">紫外线照射</span>导致你的防御力减少了<span class=\"red b\">{$defdown}</span>点！<br>";
					$def -= $defdown;
				}
			}elseif($dice ==1 && strpos($inf,'u')===false){
				$log .= "高强度的<span class=\"purple b\">紫外线照射</span>导致你<span class=\"red b\">烧伤</span>了！<br>";
				\wound\get_inf('u');
			}else{
				$log .= "高强度的紫外线灼烧着大地……<br>";
			}
		}
		elseif($weather == 17)	//极光
		{
			//也许就不需要有效果
		}
		return $chprocess($moveto);
	}
	
	function search_area()	//天气对探索的特效
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player','logger'));
		if($weather == 13) 
		{
			deal_hailstorm_weather_damage();
			if ($hp<=0) return false;
		} 
		elseif($weather == 14)	//离子暴
		{
			$dice = rand(0,9);
			if($dice ==0 && strpos($inf,'e')===false){
				$log .= "空气中充斥着的<span class=\"linen b\">狂暴电磁波</span>导致你<span class=\"yellow b\">身体麻痹</span>了！<br>";
				\wound\get_inf('e');
			}elseif($dice ==1 && strpos($inf,'w')===false){
				$log .= "空气中充斥着的<span class=\"linen b\">狂暴电磁波</span>导致你<span class=\"grey b\">混乱</span>了！<br>";
				\wound\get_inf('w');
			}else{
				$log .= "空气中充斥着狂暴的电磁波……<br>";
			}
		} 
		elseif($weather == 15)	//辐射尘
		{
			$dice = rand(0,9);
			if($dice == 0){
				$mhpdown = rand(1,4);
				if($mhp > $mhpdown){
					$log .= "空气中弥漫着的<span class=\"green b\">放射性尘埃</span>导致你的生命上限减少了<span class=\"red b\">{$mhpdown}</span>点！<br>";
					$mhp -= $mhpdown;
					if($hp > $mhp){$hp = $mhp;}
				}
			}else{
				$log .= "空气中弥漫着放射性尘埃……<br>";
			}
		} 
		elseif($weather == 16)	//臭氧洞
		{
			$dice = rand(0,9);
			if($dice == 0){
				$defdown = rand(2,5);
				if($def > $defdown){
					$log .= "高强度的<span class=\"purple b\">紫外线照射</span>导致你的防御力减少了<span class=\"red b\">{$defdown}</span>点！<br>";
					$def -= $defdown;if($def < 0) $def = 0;
				}
			}elseif($dice ==1 && strpos($inf,'u')===false){
				$log .= "高强度的<span class=\"purple b\">紫外线照射</span>导致你<span class=\"red b\">烧伤</span>了！<br>";
				\wound\get_inf('u');
			}else{
				$log .= "高强度的紫外线灼烧着大地……<br>";
			}
		} 
		elseif($weather == 17)	//极光
		{
			//也许就不需要有效果
		}
		return $chprocess();
	}
	
	//天气控制道具
	function wthchange($itm,$itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player','logger','weather'));
		if($weather >= 14 && $itmsk != 95){
			addnews ( $now, 'wthfail', $name, $weather, $itm );
			$log .= "你使用了{$itm}。<br /><span class=\"red b\">但是天气并未发生任何变化！</span><br />";
		}else{
			if($itmsk==99){$weather = rand ( 0, 13 );}//随机全天气
			elseif($itmsk==98){$weather = rand ( 10, 13 );}//随机恶劣天气
			elseif($itmsk==97){$weather = rand ( 0, 9 );}//随机一般天气
			elseif($itmsk==96){$weather = rand ( 8, 9 );}//随机起雾天气
			//elseif($itmsk==95){$weather = rand ( 14, 16 );}//随机末日天气
			elseif($itmsk==95){$weather = 17;}//末日天气改为极光，数值和大晴是一样的
			elseif(!empty($itmsk) && is_numeric($itmsk)){
				if($itmsk >=0 && $itmsk < count($wthinfo)){
					$weather = $itmsk;
				}else{$weather = 0;}
			}
			else{$weather = 0;}
			save_gameinfo ();
			addnews ( $now, 'wthchange', $name, $weather, $itm );
			$log .= "你使用了{$itm}。<br />天气突然转变成了<span class=\"red b\">$wthinfo[$weather]</span>！<br />";
		}
		return;
	}
	
	
	
	function add_once_area($atime)	//增加禁区天气变化
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		//末日天气不会因为禁区而消失
		if($weather <= 13) {
			$o_weather = $weather;
			do { $weather = rand(0,9); } while($weather == $o_weather);		//天气不会跟原本天气一样
		}
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
	
//	function init_playerdata()
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('player'));
//		$fog = check_fog();
//		$chprocess();
//	}
	
	//2023.09.29厌烦了每次调用$fog都得把冗长的player模组import一遍，加了一个这个函数。以后尽量把$fog的判定都改成这个吧
	function check_fog()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','weather'));
		return !empty($weather_fog[$weather]);
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
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','weather'));
		if($news == 'wthchange') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}使用了{$c}，天气变成了{$wthinfo[$b]}！</span></li>";
		elseif($news == 'wthfail') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}使用了{$c}，但是天气并未发生改变！</span></li>";
		elseif($news == 'syswthchg') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">奇迹和魔法都是存在的！当前天气变成了{$wthinfo[$a]}！</span></li>";
		elseif($news == 'aurora_revival') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}在奥罗拉的作用下原地复活了！</span></li>";
		elseif($news == 'death17') 
		{
			$dname = $typeinfo[$b].' '.$a;
			if(!$e){
				$e="<span class=\"yellow b\">【{$dname} 什么都没说就死去了】</span><br>\n";
			}else{
				$e="<span class=\"yellow b\">【{$dname}：“{$e}”】</span><br>\n";
			}
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"red b\">冰雹砸死</span>{$e}</li>";
		}
		elseif($news == 'addarea') 
		{
			$info = $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
			$info = str_replace("</li>", "<span class=\"yellow b\">【天气：{$wthinfo[$b]}】</span></li>", $info);
			return $info;
		}
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function use_radar($mms = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger'));
		if($weather == 14)
		{
			$log .= '由于<span class="linen b">离子风暴</span>造成了电磁干扰，探测仪器完全显示不出信息……<br>';
			return;
		}
		$chprocess($mms);
	}
	
	function apply_fog_meetenemy_effect($ismeet)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('metman'));
		if (check_fog() && !$ismeet)
		{
			$tdata['sNoinfo'] = '？？？';
			$tdata['iconImg'] = 'question.gif';
			$tdata['iconImgB'] = '';
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
	
	//极光天气每次战斗结束都有概率无视生死状态而回血。
	//注意，如果死亡，会在kill判定里处理回血（复活），否则在这个函数最后处理
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(17 == $weather){
			weather_aurora_check($pa, $pd, $active);
		}
		$chprocess($pa, $pd, $active);//如果死亡，在这里处理
		weather_aurora_revive_process($pa, $pd);//没死则在这里处理
		unset($pa['aurora_revive_flag'],$pd['aurora_revive_flag']);
	}
	
//	function kill(&$pa, &$pd)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		
//		$chprocess($pa,$pd);
//		
//		eval(import_module('sys','logger'));
//		//weather_aurora_revive_process($pa, $pd);
//	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		eval(import_module('sys'));
		if(17 == $weather){
			$pd['revive_sequence'][50] = 'aurora';
		}
		return;
	}	
	
	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('aurora' == $rkey && !empty($pd['aurora_revive'])){
			$ret = true;
		}
		return $ret;
	}
	
	//随机改变HP，发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('aurora' == $rkey){
			$pd['hp'] = weather_aurora_revive_num($pd);
			$pd['aurora_revive'] = 0;
			$pd['rivival_news'] = array('aurora_revival', $pd['name']);
			//addnews ( 0, 'aurora_revival', $pd['name'] );
		}
		return;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));

		if (!empty($pd['aurora_revive_flag']))
		{
			if ($active)
			{
				$log.='但是，空气中弥漫着的<span class="cyan b">奥罗拉</span><span class="lime b">让敌人重新站了起来！</span><br>';
				$log .= '并且，敌人的生命回复了<span class="cyan b">'.$pd['aurora_revive_flag'].'</span>点！<br>';
				$pd['battlelog'].='<span class="lime b">但是，空气中弥漫着的奥罗拉让你重新站了起来！</span>';
			}
			else
			{
				$log.='但是，空气中弥漫着的<span class="cyan b">奥罗拉</span><span class="lime b">让你重新站了起来！</span><br>';
				$log .= '并且，你的生命回复了<span class="cyan b">'.$pd['aurora_revive_flag'].'</span>点！<br>';
				$pd['battlelog'].='<span class="lime b">但是，空气中弥漫着的奥罗拉让敌人重新站了起来！</span>';
			}
			//unset($pd['aurora_revive_flag']);
		}
	}
	
	function weather_aurora_check(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger'));
		if(17 != $weather) return;
		foreach(array('pa','pd') as $pn){
			if(!${$pn}['type']) $aurora_rate = 10;//玩家回血概率10%，NPC回血概率1%
			else $aurora_rate = 1;
			if(rand(0,99) < $aurora_rate){
				${$pn}['aurora_revive'] = rand(1,9);
			}
		}
	}
	
	function weather_aurora_revive_num(&$pd){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(17 != $weather || !$pd['aurora_revive']) return 0;
		if($pd['hp'] < 0) $pd['hp'] = 0;
		$aurora_revive = $pd['aurora_revive'];
		if($aurora_revive > $pd['mhp'] - $pd['hp']) $aurora_revive = $pd['mhp'] - $pd['hp'];
		$pd['aurora_revive_flag'] = $pd['aurora_revive'];
		return $aurora_revive;
	}
	
	//这个函数只在没死时处理
	function weather_aurora_revive_process(&$pa,&$pd){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if(17 != $weather) return;
		foreach(array('pa','pd') as $pn){
			if(!empty(${$pn}['aurora_revive']) && ${$pn}['hp'] < ${$pn}['mhp']){
				$aurora_revive = weather_aurora_revive_num(${$pn});
				if($aurora_revive) {
					${$pn}['hp'] += $aurora_revive;

					if(${$pn}['name'] == $sdata['name']) $logname = '你';
					else $logname = ${$pn}['name'];

					//复活时在player_kill_enemy()里记录$log
					$log .= "空气中弥漫着的<span class='cyan b'>奥罗拉</span>让{$logname}的生命回复了<span class='cyan b'>$aurora_revive</span>点！<br>";
				}
				unset(${$pn}['aurora_revive']);
			}
		}
	}
	
	//复活后不会反击
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)			//不会反击敌人
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//注意判定的是$pa能否反击$pd
		if (!empty($pa['aurora_revive_flag'])) return 0; 
		return $chprocess($pa, $pd, $active);
	}
}

?>