<?php

namespace wep_b
{
	function init() 
	{
		eval(import_module('weapon','itemmain'));
		$iteminfo['GA'] = '箭矢';	
		
		//武器类型依赖的熟练度名称
		$skillinfo['B'] = 'wc';
		//武器类型攻击动词
		$attinfo['B'] = '投射';
		//武器类型名
		$iteminfo['WB'] = '弓';
		
		//基础反击率
		$counter_obbs['B'] = 30;
		//射程
		$rangeinfo['B'] = 5;
		//基础命中率
		$hitrate_obbs['B'] = 65;
		//各种攻击方式的最高命中率
		$hitrate_max_obbs['B'] = 90;
		//每点熟练增加的命中
		$hitrate_r['B'] = 0.15;
		//各种攻击方式的伤害变动范围，越少越稳定。
		$dmg_fluc['B'] = 10;
		//每点熟练度增加的伤害
		$skill_dmg['B'] = 0.5;
		//各种攻击方式的武器损伤概率
		$wepimprate['B'] = 10000;
		//以该类武器击杀敌人后的死亡状态标号
		$wepdeathstate['B'] = 43;
		
		$itemspkinfo['^ari'] = '箭矢';
		$itemspkdesc['^ari'] = '当前所装箭矢的信息为：<:skn:>';
	}
	
	//弓没弓箭时当做锐器
	function get_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (check_WB_att_as_WK($pdata)) {
			return 'K';
		}
		else return $chprocess($pdata);
	}
	
	function check_WB_att_as_WK(&$pdata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return substr($pdata['wepk'],1,1) == 'B' && $pdata['weps']==$nosta;
	}
	
	//弓当锐器时伤害仅为25%
	function get_WB_att_as_WK_modifier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0.25;
	}
	
	//当锐器特判
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$c = $chprocess($pa, $pd, $active);
		if ($pa['wep_kind']=='K' && substr($pa['wepk'],1,1) == 'B')
			return get_WB_att_as_WK_modifier($pa,$pd,$active)*$c;
		else  return $c;
	}
	
	//弓当锐器时损坏率3倍
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$r=1; 
		if ($pa['wep_kind']=='K' && substr($pa['wepk'],1,1) == 'B') $r=3;	
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if ($pa['wep_kind']=='B')	//弓log提示改变
		{
			if (isset($pa['wepimp']) && $pa['wepimp'])
			{
				$pa['weps']-=$pa['wepimp'];
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<:pa_name:>的{$pa['wep']}用掉了{$pa['wepimp']}支箭。<br>");

				if ($pa['weps']<=0) \weapon\weapon_break($pa, $pd, $active);
			}
		}
		else $chprocess($pa,$pd,$active);
	}
	
	function weapon_break(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if ($pa['wep_kind']=='B')	//弓系武器损坏特判（箭矢用光）
		{
			eval(import_module('player','weapon','logger'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, "<:pa_name:>的<span class=\"red b\">{$pa['wep']}</span>的箭矢用光了！<br>");
			$pa['weps']=$nosta;
//			//箭矢用光时抹掉箭矢名
//			wep_b_clean_arrow_name($pa['wepk']);

			//箭矢用光时抹掉箭矢带来的属性
			wep_b_clean_arrow_sk($pa['wepsk']);
			//箭矢用光时清除换上的箭矢的信息
			$pa['wepsk'] = wep_b_put_ari($pa['wepsk'], Array());
		}
		else $chprocess($pa,$pd,$active);
	}
	
	function get_weapon_range(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess($pa,$active);
		if(empty($pa['wep_kind'])) $pa['wep_kind'] = \weapon\get_attack_method($pa);
		//弓系射程随熟练增加，每200点加1，最多加3
		if($pa['wep_kind']=='B') {
			eval(import_module('weapon'));
			$r_add = floor($pa[$skillinfo[$pa['wep_kind']]] / 200);
			if($r_add > 3) $r_add = 3;
			$r += $r_add;
		}
		return $r;
	}
	
	//从武器属性字符串获取所储存箭矢的信息，所用复合属性为^ari_XXXXX1这样的
	function wep_b_get_ari($wsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = Array();
		$aris = \itemmain\check_in_itmsk('^ari', $wsk);
		if(empty($aris)) return $ret;
		$aris = \itemmain\base64_decode_comp_itmsk($aris);
		
		if(!empty($aris)) {
			$ariarr = explode(',', $aris);
			$ret = Array(
				'itm' => $ariarr[0],
				'itmk' => $ariarr[1],
				'itme' => $ariarr[2],
				'itms' => $ariarr[3],
				'itmsk' => $ariarr[4]
			);
		}
		//var_dump($ret);
		return $ret;
	}
	
	//把箭矢信息保存到武器属性字符串，所用复合属性为^ari_XXXXX1这样的，如果提供空数组则会把这个属性清除
	//返回一个修改过的武器属性字符串
	function wep_b_put_ari($wsk, $ariarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$nowaris = \itemmain\check_in_itmsk('^ari', $wsk);
		//如果已经存在这个属性，先抹掉
		if(!empty($nowaris)) {
			$wsk =  \itemmain\replace_in_itmsk('^ari','',$wsk);
//			$wsk = preg_replace('/\^ari[_]?[a-zA-Z\+\/\=\)\!\@\#\$\%\-\&\*\(]+?[0-9]/s', '', $wsk);//先抹掉原^ari属性字符串，不管原来的内容是什么
		}
		//如果传入的数组非空，构造一个属性并加在$wsk后面
		if(!empty($ariarr)){
			//注意这里不会一一检测传入数组的字段是否符合要求
			$aris = $ariarr['itm'].','.$ariarr['itmk'].','.$ariarr['itme'].','.$ariarr['itms'].','.$ariarr['itmsk'];
			$wsk .= '^ari_'.\itemmain\base64_encode_comp_itmsk($aris).'1';
		}
		return $wsk;
	}
	
	//把箭矢名字抹掉
	//认为武器类别|后的都是箭矢名，返回抹掉的名字
	//已废弃不再使用
	function wep_b_clean_arrow_name(&$itmk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(strpos($itmk,'|')===false) return '';
		$ofs = strpos($itmk,'|');
		$ret = substr($itmk, $ofs+1);
		$itmk = substr($itmk, 0, $ofs);
		return $ret;
	}
	
	//把引用的参数中的箭矢带来的属性抹掉，返回抹掉的属性
	//认为|之间的属性都是箭矢属性
	function wep_b_clean_arrow_sk(&$itmsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(strpos($itmsk,'|')===false) return '';
		//如果奇数个，则结尾补一个|，嘿嘿
		if(substr_count($itmsk, '|') % 2) $itmsk .= '|';
		preg_match('/\|.*\|/s',$itmsk,$matches);
		$ret = '';
		if(!empty($matches)) {
			$itmsk = preg_replace('/\|.*?\|/s','',$itmsk);
			$ret = substr($matches[0], 1, -1);
		}
		return $ret;
	}
	
	function itemuse_uga(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger','weapon'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		//获得原本保存的箭矢数值
		$swapitem = wep_b_get_ari($wepsk);
//		//清除箭矢名
//		$swapn = wep_b_clean_arrow_name($wepk);
		//清除武器上的箭属性，这里不能直接扣除箭矢的属性（会误伤弓本来有的属性）
		$swapsk = wep_b_clean_arrow_sk($wepsk);
		//如果卸下来的箭矢是投掷武器那么从武器效果值扣除投掷武器的部分
//		if(!empty($swapitem) && strpos($swapitem['itmk'], 'WC')===0){
//			$wepe -= $swapitem['itme'];
//		}
		
		//判定卸下来的箭矢数目，然后把武器改成无穷耐
		$swapnum = 0;
		if ($weps !== $nosta) {
			$swapnum = $weps;
			$weps = $nosta;
		}
		
		$wepsk_arr = \itemmain\get_itmsk_array($wepsk);
		$itmsk_arr = \itemmain\get_itmsk_array($itmsk);
		$arrowmax = (\itemmain\check_in_itmsk('r',$itmsk_arr) || \itemmain\check_in_itmsk('r',$wepsk_arr)) ? 2 + min ( floor(${$skillinfo['B']} / 200), 4 ) : 1;
		$arrownum = min($arrowmax, $itms);
		$weps = $arrownum;
		
		//记录换上的箭矢数值
		$reloadsk = Array(
			'itm' => $itm,
			'itmk' => $itmk,
			'itme' => $itme,
			'itms' => $arrownum,
			'itmsk' => $itmsk
		);
		$wepsk = wep_b_put_ari($wepsk, $reloadsk);
		
//		//记录箭矢名
//		$wepk .= '|'.$itm;
		//为武器增加箭属性
		if(!empty($itmsk_arr)){
//			$add_arr = array_diff($itmsk_arr,$wepsk_arr);
//			$wepsk .= '|'.implode('', $add_arr).'|';
			$wepsk .= '|'.implode('', $itmsk_arr).'|';
		}
		
		if(!$swapnum)	$log .= "为<span class=\"red b\">$wep</span>选用了<span class=\"red b\">$itm</span>，<span class=\"red b\">$wep</span>发射次数增加了<span class=\"yellow b\">$arrownum</span>。<br>";
		else $log .= "为<span class=\"red b\">$wep</span>换上了<span class=\"red b\">$itm</span>，<span class=\"red b\">$wep</span>发射次数增加了<span class=\"yellow b\">$arrownum</span>。<br>";
		\itemmain\itms_reduce($theitem, $arrownum);

		//获得卸下的箭矢
		if($swapnum){
			if(empty($swapitem)) {
				$itm0 = '卸下的箭';
				$itmk0 = 'GA';
				$itme0 = 1;
				$itms0 = $swapnum;
				$itmsk0 = $swapsk;
			}else{
				$itm0 = $swapitem['itm'];
				$itmk0 = $swapitem['itmk'];
				$itme0 = $swapitem['itme'];
				$itms0 = !empty($swapnum) ? $swapnum : $swapitem['itms'];
				$itmsk0 = $swapitem['itmsk'];
			}
			\itemmain\itemget();
		}
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		//var_dump($theitem['itmn']);var_dump($weps);
		if (strpos ( $itmk, 'GA' ) === 0) 
		{
			if (strpos ( $wepk, 'WB' ) !== 0) {
				$log .= "<span class=\"red b\">你没有装备弓，不能给武器上箭。</span><br>";
				$mode = 'command';
				return;
			} elseif('0' === $theitem['itmn']) {
				//捡到的箭矢不能马上拉弓，避免换箭覆盖itm0的问题
				$log .= "你一只手捏着弓箭，一只手抓着刚捡到的箭矢，没法马上弯弓搭箭。<span class=\"red b\">还是先把箭矢收进包裹里吧。</span><br>";
				$mode = 'command';
				return;
			} 
			itemuse_uga($theitem);
			return;
		}
		$chprocess($theitem);
	}
		
	//先制敌人时，如果箭是空的，自动装填
	function findenemy(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		
		if (strpos ( $wepk, 'WB' ) === 0 && $weps == $nosta) {			
			$tmp_log = $log;
			$pos = 0;
			//遍历所有包裹，寻找最靠前的箭矢装填
			for($i=1; $i<=6; $i++){
				if($sdata['itmk'.$i] == 'GA') {
					$pos = $i;
					break;
				}
			}
			//直接调用使用道具的函数试试
			if($pos) {
				$log .= '你及时弯弓搭箭，';
				\itemmain\itemuse_wrapper($pos);
			}
			$tmp_log_2 = substr($log, strlen($tmp_log));
			$log = $tmp_log;//暂存一下$log，调整显示顺序，不过可能不利于以后扩展，再说吧……
		}
		$chprocess($edata);
		if(!empty($tmp_log_2)) $log .= $tmp_log_2;
	}
	
	//覆盖$skn返回值，显示箭矢信息
	function get_itmsk_desc_single_comp_process($skk, $skn, $sks) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skn = $chprocess($skk, $skn, $sks);
		if(strpos($skk, '^ari')===0) {
			$skarr = explode(',',\itemmain\base64_decode_comp_itmsk($sks));
			$itm = $skarr[0];
			$itmk_words = \itemmain\parse_itmk_words($skarr[1]);
			$itme = $skarr[2];
			$itms = $skarr[3];
			$itmsk_words = \itemmain\parse_itmsk_words($skarr[4]);
			$skn = $itm.'/'.$itmk_words.'/'.$itme.'/'.$itms.(!empty($itmsk_words) ? '/'.$itmsk_words : '');
		}
		return $skn;
	}
	
	//弓系特殊的攻击宣言
	function get_attackwords(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if('B' == $pa['wep_kind']) {
			$arrow = wep_b_get_ari($pa['wepsk']);
			//如果有箭矢信息那么导出特殊的攻击宣言
			if(!empty($arrow['itm'])) {
				eval(import_module('weapon'));
			
				if(isset($attinfo2[$pa['wep_kind']])) $att_method_words = $attinfo2[$pa['wep_kind']];
				else $att_method_words = $attinfo[$pa['wep_kind']];
				
				$arrowname = $arrow['itm'];
				
				if ($active)
				{
					$ret = "使用{$pa['wep']}向{$pd['name']}<span class=\"yellow b\">{$att_method_words}</span>出<span class=\"yellow b\">{$arrowname}</span>！<br>";
				}
				else  
				{
					$ret = "{$pa['name']}使用{$pa['wep']}向你<span class=\"yellow b\">{$att_method_words}</span>出<span class=\"yellow b\">{$arrowname}</span>！<br>";
				}
				return $ret;
			}
		}
		
		return $chprocess($pa, $pd, $active);
	}
	
	//弓系特有的击杀讯息
	
	//在攻击准备时记录箭矢数值
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if('B' == $pa['wep_kind']) {
			$arrow = wep_b_get_ari($pa['wepsk']);
			if(!empty($arrow['itm'])) {
				$pa['attackwith_arrowarr'] = $arrow;
			}
		}else{
			unset($pa['attackwith_arrowarr']);
		}
		
		$chprocess($pa, $pd, $active);
	}
	
	//在重置$sdata前后，保留箭矢信息
	//好像并不需要
//	function load_playerdata($pdata)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if(!empty($pdata['attackwith_arrowarr'])) {
//			$o_attackwith_arrowarr = $pdata['attackwith_arrowarr'];
//		}
//		$chprocess($pdata);
//		//注意由于这个函数对$pdata是传值，而实际修改的是$sdata，后边这里也得是$sdata
//		if(!empty($o_attackwith_arrowarr)) {
//			eval(import_module('player'));
//			$sdata['attackwith_arrowarr'] = $o_attackwith_arrowarr;
//		}
//	}
	
	//生成击杀进行状况时提交记录的箭矢信息。这里不能直接读$pa是因为箭矢已经被用掉啦
	function deathnews(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if('B' == $pa['wep_kind'] && !empty($pa['attackwith_arrowarr'])) {
			//如果有箭矢信息那么提供箭矢的信息，并用特殊的分隔符隔开
			if(!empty($pa['attackwith_arrowarr']['itm'])) {
				$o_pa_attackwith = $pa['attackwith'];
				$pa['attackwith'] .= '<:sep:>'.$pa['attackwith_arrowarr']['itm'];
			}
		}
		$chprocess($pa, $pd);
		
		if(!empty($o_pa_attackwith)) {
			$pa['attackwith'] = $o_pa_attackwith;
			unset($o_pa_attackwith);
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(isset($exarr['dword'])) $e0 = $exarr['dword'];

		if($news == 'death43') {
			if(strpos($d, '<:sep:>')!==false) {
				list($d, $d2) = explode('<:sep:>', $d);
			}
			if(empty($d2)) return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>使用<span class=\"red b\">$d</span>投射致死$e0</li>";
			else return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>使用<span class=\"red b\">$d</span>投射<span class=\"red b\">$d2</span>致死$e0</li>";
		}
		else return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>