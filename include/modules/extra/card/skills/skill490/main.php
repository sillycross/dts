<?php

namespace skill490
{	
	$skill490_cd = 60;
	$skill490_minsp = 1;
	
	function init() 
	{
		define('MOD_SKILL490_INFO','card;upgrade;unique;');
		eval(import_module('clubbase'));
		$clubskillname[490] = '空想';
	}
	
	function acquire490(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(490,'nextuse',0,$pa);
	}
	
	function lost490(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(490,'nextuse',$pa);
	}
	
	function check_unlocked490(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate490()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill490','logger'));
		\player\update_sdata();
		if (!\skillbase\skill_query(490) || !check_unlocked490($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill490_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		if($sp <= $skill490_minsp){
			$log.='体力不足，无法使用技能！<br>';
			return;
		}
		$spdown = $sp - $skill490_minsp;
		$sp = $skill490_minsp;
		\skillbase\skill_setvalue(490,'nextuse',$now + $skill490_cd);
		get_random_item490($spdown);
		addnews ( 0, 'bskill490', $name , $itmk0, $itm0);
		if($itms0) {
			$log.='<span class="lime b">获得了「空想道具」！</span><br>';
			\itemmain\itemget();
		}
	}
	
	//计算空想类别和属性表。会自动生成缓存文件。
	//现在空想只根据itemmain和itemshop模块的道具配置文件来生成。
	function get_random_itmksklist490()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$writefile = GAME_ROOT.'/gamedata/cache/skill490itmksk.config.php';
		$mapitemfile = GAME_ROOT.'/include/modules/base/itemmain/config/mapitem.config.php';
		$shopitemfile = GAME_ROOT.'/include/modules/base/itemshop/config/shopitem.config.php';
		
		//文件过期，需要重生成
		if(check_filemtime_expired($writefile, Array($mapitemfile, $shopitemfile))){
			$itemklist = $itemsklist = array();
			//类别和属性是从地图和商店道具里随机一个的
			$itemfc = openfile($mapitemfile);
			foreach($itemfc as $ival){
				$ival = explode(',',$ival);
				if(is_numeric($ival[0]) && $ival[0] > 100) continue;//禁数在100以上的道具不考虑
				if(isset($ival[4])) $itemklist[] = $ival[4];
				if(isset($ival[7])) $itemsklist[] = \attrbase\config_process_encode_comp_itmsk($ival[7]);
			}
			$shoplist = openfile($shopitemfile);
			foreach($shoplist as $lst){
				if(!empty($lst) && strpos($lst,',')!==false){
					list($kind,$num,$price,$area,$item,$itmk,$itme,$itms,$itmsk)=explode(',',$lst);
					if($kind != 0){
						$itemklist[] = $itmk;
						$itemsklist[] = \attrbase\config_process_encode_comp_itmsk($itmsk);
					}
				}	
			}
			$writecont = str_replace('?>','',$checkstr);//"<?php\r\nif(!defined('IN_GAME')) exit('Access Denied');\r\n";
			$writecont .= '$itemklist = '.var_export($itemklist, 1).";\r\n\r\n";
			$writecont .= '$itemsklist = '.var_export($itemsklist, 1).";\r\n\r\n";
			$writecont .= "/* End of file skill490itmksk.config.php */";
			
			file_put_contents($writefile, $writecont);
		}else{
			include $writefile;
		}

		return Array($itemklist, $itemsklist);
	}
	
	//获得道具的主技能
	function get_random_item490($spdown){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill490'));
		$ritm = '「空想道具」';
		$ritms = 1;$ritme = 0;
		if($spdown > 0){
			$rmax = rand(round($spdown*0.75), $spdown);
			$r = rand(0, $rmax);
			$ritme += $r;
			$ritms += $rmax - $r;
		}
		
		list($itemklist, $itemsklist) = get_random_itmksklist490();
		
		$exception = array('TNc','TOc','VS','Y','Z');//不管怎样都不会出现奇迹雷、获得技能的技能书、特殊道具
		if($ritms > 1) $exception[] = 'VO';//随出的耐久大于1时，阻止出现卡片礼物
		if($ritms > 3) $exception = array_merge($exception, array('TN', 'TO'));//随出的耐久大于3时，阻止出现陷阱
		if($ritms > 10) $exception = array_merge($exception, array('p', 'ygo', 'fy', 'kj3'));//随出的耐久大于10时，阻止出现各类礼品
		if($ritms > 70) $exception = array_merge($exception, array('WN', 'WP', 'WK', 'WC', 'WG','WJ','WD','WF'));//随出的耐久大于70时，阻止出现各类武器
		if($ritme > 180) $exception = array_merge($exception, array('DB', 'DA', 'DH', 'DF'));//随出的效果值大于180时，阻止出现各类防具
		if($ritme * $ritms > 40) $exception = array_merge($exception, array('VV','VP','VK','VG','VC','VD','VF','MS'));//随出的效*耐之乘积大于40时，阻止出现各类技能书
		if($ritme * $ritms > 50) $exception = array_merge($exception, array('MV','MH','ME'));//随出的效*耐之乘积大于50时，阻止出现攻防以外的强化药
		if($ritme * $ritms > 100) $exception = array_merge($exception, array('MA','MD'));//随出的效*耐之乘积大于100时，阻止出现攻防强化药
		if($ritme * $ritms > 200) $exception = array_merge($exception, array('HM','HT'));//随出的效*耐之乘积大于200时，阻止出现歌魂增加和回复道具
		$itemklist = array_diff($itemklist, $exception);
		
		foreach($itemklist as $ik => &$iv){//防止一些奇葩武器类型的出现
			foreach($exception as $ev){
				if(strpos($iv, $ev)===0) $iv=NULL;
			}
		}
		$itemklist = array_filter($itemklist);
		if(empty($itemklist)) $itemklist[] = 'X';//当随机出的数值很高的时候，可选类型会被过滤清空，这时用合成专用来保底
		$ritmk = array_randompick($itemklist);
		$ritmsk = array_randompick($itemsklist);
		$itm0 = $ritm; $itmk0 = $ritmk; $itmsk0 = $ritmsk;
		$itme0 = $ritme; $itms0 = $ritms;
		return;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill490_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(490, $pa) || !check_unlocked490($pa)) return 0;
		eval(import_module('sys','player','skill490'));
		$nextuse=\skillbase\skill_getvalue(490,'nextuse',$pa);
		if ($nextuse > $now) return 2;
		return 3;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(490,$sdata)) && check_unlocked490($sdata))
		{
			eval(import_module('skill490'));
			$skill490_nextuse = (int)\skillbase\skill_getvalue(490,'nextuse');
			$skill490_lst = $skill490_nextuse-$skill490_cd;
			$skill490_time = $now - $skill490_lst;
			if($skill490_time > $skill490_cd) $skill490_time = $skill490_cd;
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「空想」',
				'activate_hint' => '点击发动技能「空想」',
				'onclick' => "$('mode').value='special';$('command').value='skill490_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill490_time < $skill490_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill490_cd;
				$z['nowsec']=$skill490_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill490.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain'));
		
		if($news == 'bskill490') {
			$kwords = \itemmain\parse_itmk_words($b);
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「空想」</span>，获得了{$kwords}{$c}！</span></li>";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>