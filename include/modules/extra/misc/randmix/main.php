<?php

namespace randmix
{
	//不生成随机合成表的模式
	//$randmix_ignore_mode = Array(1,17);
	
	//类别概率
	$randmix_kind_rate = Array(
		'WP' => 6,
		'WK' => 6,
		'WG' => 6,
		'WC' => 6,
		'WD' => 6,
		'WF' => 6,//武器总和是36
		'DB' => 8,
		'DH' => 8,
		'DA' => 8,
		'DF' => 8,
		'A' => 3,//防具总和35
		'HB' => 15,//命体回复15
		'HR' => 5,//怒气药5
		'HM' => 4,//歌魂增加药4
		'TO' => 5,//陷阱5
	);
	
	//攻击属性概率
	$randmix_sk_rate_att = Array(
		'c' => 10,
		'p' => 8,
		'u' => 8,
		'i' => 8,
		'w' => 8,
		'e' => 8,
		'f' => 5,
		'k' => 5,
		't' => 5,
		'd' => 3,
		'r' => 2,
	);
	
	//防御属性概率
	$randmix_sk_rate_def = Array(
		'q' => 8,
		'U' => 8,
		'I' => 8,
		'W' => 8,
		'E' => 8,
		'A' => 4,
		'a' => 4,
		'B' => 1,
		'b' => 1,
	);
	
	$randmix_name_list = Array(
		'',
	);
	
	$randmix_name_prefix_list = Array(
		'超神的' => 0.5,
		'奇迹的' => 1,
		'辉煌的' => 2,
		'稀有的' => 5,
		'罕见的' => 10,
		'平凡的' => 20,
	);
	
	$randmix_name_kind_list = array(
		'WP' => array('锤','鞭','杖','拳'),
		'WK' => array('刀','剑','刃','枪'),
		'WG' => array('枪','铳','炮'),
		'WC' => array('球','镖','弹'),
		'WD' => array('炸弹','爆弹','雷'),
		'WF' => array('符卡','魔弹','灵击'),
		'DB' => array('甲','衣','装','战甲B'),
		'DH' => array('盔','镜','帽','战甲H'),
		'DA' => array('盾','爪','手套','战甲A'),
		'DF' => array('鞋','靴','爪','战甲F'),
		'A' => array('挂件','项链','插件','饰品A'),
		'HB' => array('秘药','伤药','罐头'),
		'HR' => array('黑暗料理','食堂剩饭','泔水'),
		'HM' => array('唱片','乐谱'),
		'TO' => array('地雷','阔剑地雷','魔法阵'),
	);
	
	
	function init() {}
	
	function create_randmix_config(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$file = GAME_ROOT.'./gamedata/cache/randmix.config.php';
		$file2 = GAME_ROOT.'./gamedata/cache/common_itemnum.config.php';
		\itemnumlist\itemnumlist_create('common_itemnum');
		$cont = create_randmix_list();
		$cont = str_replace('?>','',$checkstr)."\r\n\$randmixinfo = ".var_export($cont,1).';';
		writeover($file, $cont);
		chmod($file, 0777);
		
	}
	
	function create_randmix_list(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','item_slip','randmix'));
		
		include GAME_ROOT.'./gamedata/cache/common_itemnum.config.php';
		
		//每种纸条生成2条随机的合成
		$clist = array();
		foreach(array_keys($item_slip_hint) as $key){
			$s1 = $s2 = $s3 = '';$aready = array();
			$s1 = '提示纸条'.$key;
			$s1_num = $cont_mapitem[$s1][0];
			if(!$s1_num || $s1_num > 20) continue;
			//第一条，当前纸条+商店物/道具
			$minnum = 3;$maxnum = 10;
			list($s2,$s2_num) =  randmix_getitemname(array('mapitem','shopitem'), $minnum, $maxnum, $aready);			
			
			$clist[] = array('class' => 'randmix', 'stuff' => array($s1,$s2),'result' => create_randmix_result(array($s1,$s2), $s2_num));
			var_dump($s2_num);
			//第二条，当前纸条+商店物/道具+随机物
			list($s2,$s2_num) =  randmix_getitemname(array('mapitem','shopitem'), $minnum, $maxnum, $aready);
			$minnum = 2;$maxnum = 7;
			list($s3,$s3_num) =  randmix_getitemname(array('mapitem','shopitem','npcinfo'), $minnum, $maxnum, $aready);
			var_dump($s2_num*$s3_num);
			$clist[] = array('class' => 'randmix', 'stuff' => array($s1,$s2,$s3),'result' => create_randmix_result(array($s1,$s2,$s3), $s2_num*$s3_num));
		}
		return $clist;
	}
	
	//随机生成合成产物，是一个数组
	function create_randmix_result($stuff, $stuffnum){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('item_slip','itemmain','randmix'));
		//最先生成类型，纯随机
		$dice = rand(0,99);
		$retk = 'WP';
		foreach($randmix_kind_rate as $rk => $rr){
			if($dice < $rr) {
				$retk = $rk;
				break;
			}else {
				$dice -= $rr;
			}
		}
		//然后生成效和耐，有所浮动
		$rete = round(100+500/sqrt($stuffnum));
		$rete = min(2000,$rete);
		$rete = round($rete * rand(90,110) / 100);
		$rete = (int)$rete;
		//耐由平均数决定
		$rets = round(20+100/sqrt($stuffnum));
		$rets = round($rets * rand(90,110) / 100);
		if($rets > 500) $rets = '∞';
		else $rets = (int)$rets;
		//然后生成属性，$sk_factor为随机数的扩张值（降低概率）
		$retsk = '';
		foreach(Array('randmix_sk_rate_att','randmix_sk_rate_def') as $class){
			//属性数目
			$sk_num = min(5,round(15/$stuffnum));
			
			$sk_factor = 1;
			if(strpos($retk,'D')===0 && 'randmix_sk_rate_att'==$class) $sk_factor = 1.5;//类别不合时很难获得对方的属性
			elseif(strpos($retk,'W')===0 && 'randmix_sk_rate_def'==$class) $sk_factor = 2;
			for($i=0;$i<$sk_num;$i++){
				
				$dice = rand(0,99) * max(1, min(2,$stuffnum/10));
				var_dump($dice*$sk_factor);
				foreach(${$class} as $rsk => $rr){
					//var_dump($dice*$sk_factor, $rr);
					if($dice*$sk_factor < $rr) {
						$retsk .= $rsk;
						$sk_factor *= 1.2;
						break;
					}else {
						$dice -= $rr;
					}
				}
			}
		}
		$retn = '';
		//最后生成名字
//		foreach($randmix_name_prefix_list as $nk => $nv){
//			$retn = $nk;
//			if($nv > $stuffnum) break;
//		}
		foreach($stuff as $i => $sv){
			if($i) {
				$sv0 = mb_substr($sv,0,1);
				if(!in_array($sv0, array('【','《','「','『','〖','☆','★','■','◎'))){
					$retn .= mb_substr($sv,0,ceil(mb_strlen($sv)/2));
				}else{
					$retn .= mb_substr($sv,1,ceil(mb_strlen($sv)/2));
				}
			}
		}
//		if(strpos($stuff[0], '提示纸条')===0){
//			$retn .= str_replace('提示纸条','',$stuff[0]);
//		}
		//if(!empty($retsk)) $retn .= $itemspkinfo[$retsk[0]];
		$narr = $randmix_name_kind_list[$retk];
		$retn .= array_randompick($narr);
		
		return array($retn, $retk, $rete, $rets, $retsk);
	}
	
	//在类别为$kind、数量在$min, $max之间的道具里随机选择1个并返回名字和概率
	function randmix_getitemname($kind, $minnum, $maxnum=-1, &$aready=''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));

		global $cont_mapitem,$cont_shopitem,$cont_mixitem,$cont_syncitem,$cont_overlayitem,$cont_presentitem,$cont_ygoitem,$cont_fyboxitem,$cont_npcinfo_gtype1;
		if(empty($cont_mapitem)) include GAME_ROOT.'/gamedata/cache/common_itemnum.config.php';
		if(!is_array($kind)) $kind = array($kind);
		if(!is_array($aready)) $aready = array($aready);
		$nowkindarr = array();
		foreach($kind as $kv){
			if('npc' == $kv) $kv = 'npcinfo';
			if(!isset(${'cont_'.$kv})) ${'cont_'.$kv} = array();
			$nowkindarr = array_merge($nowkindarr, ${'cont_'.$kv});
		}
		for($i=0;$i<99;$i++){
			if(empty($nowkindarr)) {
				break;
			}
			$iname = array_rand($nowkindarr);
			
			if(in_array($iname,$aready) || $nowkindarr[$iname][0] < $minnum || ($maxnum > 0 && $nowkindarr[$iname][0] > $maxnum))
			{
				unset($nowkindarr[$iname]);
			}else{
				break;
			}
		}
		$aready[] = $iname;
		return array($iname, $nowkindarr[$iname][0]);
	}
	
	//不太合适
//	function prepare_new_game()
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','randmix'));
//		$chprocess();
//
//		if(!$gametype || 4==$gametype) {
//			create_randmix_config();//仅大房间新游戏时重生成
//		}
//	}
}

?>