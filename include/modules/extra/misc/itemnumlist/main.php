<?php

namespace itemnumlist
{
	//无视的道具名列表
	$itemname_ignore = array(
		'面包','矿泉水',
		'游戏解除钥匙',
		'SCP-294的饮料',
	);
	
	//无视的道具地点
	$itempls_ignore = array(
		0,32,
	);
	
	function init() {}
	
	//道具数目和禁数的统一处理，并且写入道具列表
	function itemnumlist_num_area_proc(&$slist, $ikey, $iname, $thisnum, $thisarea){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('itemnumlist'));
		$first = 0;
		if(isset($slist[$iname])) {
			list($ikind, $globalnum, $globalarea) = $slist[$iname];
		}else{
			$ikind = array();
			$globalnum = $globalarea = 0;
			$first = 1;
		}

		if(!in_array($ikey, $ikind)) $ikind[] = $ikey;
		if(empty($thisarea)) $thisarea = 0;
		if(!$first && $thisarea > $globalarea) {//0禁和1禁都刷时，认为是0禁物，但是1禁那部分数目减半
			$thisarea = $globalarea;
			$thisnum /= 2;
		}
		$thisnum = $globalnum + $thisnum;
		if($thisnum > 500) $thisnum = 500;
		//忽略一些浮云物
		if(!in_array($iname, $itemname_ignore)) {
			$slist[$iname] = array($ikind, $thisnum, $thisarea);
		}
	}
	
	//写入道具表数组，$filename只需要是字符串
	function itemnumlist_write($file, $cont){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$file) return false;
		$contents = "<?php\r\n if(!defined('IN_GAME')) exit('Access Denied');\r\n";
		
		foreach($cont as $ckey => $cval){
			$contents .= '$cont_'.$ckey."=array(\r\n";
			foreach($cval as $ck => $cv){
				$contents .= "'$ck' => array({$cv[0]}, {$cv[1]}),\r\n";
			}
			$contents .= ");\r\n";
		}
		
		file_put_contents($file, $contents);
		chmod($file, 0777);
	}
	
	//生成道具表功能的外壳，会检查是否需要生成道具表
	function itemnumlist_create($filename, $iplacefilelist = NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//自动生成目录
		$dir = GAME_ROOT.'./gamedata/cache';
		if(!file_exists($dir)) mymkdir($dir);
		//生成文件名
		if(!$filename) return false;
		$file = $dir.'/'.$filename.'.config.php';
		//各文件默认位置
		if(!$iplacefilelist) $iplacefilelist = array(
			'mapitem' => GAME_ROOT.'/include/modules/base/itemmain/config/mapitem.config.php',
			'shopitem' => GAME_ROOT.'./include/modules/base/itemshop/config/shopitem.config.php',
			'mixitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix/config/itemmix.config.php',
			'syncitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix_sync/config/sync.config.php',
			'overlayitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix_overlay/config/overlay.config.php',
			'presentitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/present.config.php',
			'ygoitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/ygobox.config.php',
			'fyboxitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/fybox.config.php',
			'npcinfo' => GAME_ROOT.'./include/modules/base/npc/config/npc.data.config.php',
			'anpcinfo' => GAME_ROOT.'./include/modules/base/addnpc/config/addnpc.config.php',
			'enpcinfo' => GAME_ROOT.'./include/modules/extra/club/skills/skill21/config/evonpc.config.php',
		);
		//如果更新过文件则生成道具表
		$need_update = 0;
		$versionfile = GAME_ROOT.'./include/modules/core/sys/config/version.config.php';
		if(!file_exists($file) || filemtime($versionfile) > filemtime($file)) $need_update = 1;
		else{
			foreach($iplacefilelist as $ipfval){
				if(filemtime($ipfval) > filemtime($file)){
					$need_update = 1; 
					break;
				}
			}
		}
		if(!$need_update) return;
		$contents = itemnumlist_create_proc($iplacefilelist);
		if(!empty($contents)) itemnumlist_write($file, $contents);
	}
	
	//生成道具表数组
	function itemnumlist_create_proc($iplacefilelist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','itemnumlist'));
		//从各文件提取道具信息
		$iplacefiledata = array();
		foreach($iplacefilelist as $ipfkey => $ipfval){
			if($ipfkey == 'mixitem') {
				include $ipfval;
				$iplacefiledata[$ipfkey] = $mixinfo;
			}elseif(strpos($ipfkey, 'npc') !==false){
				include $ipfval;
//				if($ipfkey == 'npc') $varname = 'npcinfo';
//				elseif($ipfkey == 'addnpc') $varname = 'anpcinfo';
//				elseif($ipfkey == 'evonpc') $varname = 'enpcinfo';
				$iplacefiledata[$ipfkey] = $$ipfkey;
			}else {
				$iplacefiledata[$ipfkey] = openfile($ipfval);
			}
		}
		//生成个数数组
		$slist = array();
		//暂存游戏王星数数据
		$starnum = array();
		foreach($iplacefiledata as $ipdkey => $ipdval){
			foreach($ipdval as $ipdkey2 => $ipdval2){
				$globalnum = $thisnum = $thisarea = 0;
				//地图掉落
				if(strpos($ipdkey, 'mapitem')===0) {
					if(!empty($ipdval2) && strpos($ipdval2,',')!==false)
					{
						list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$ipdval2);
						
						$thisnum = $inum;
						$thisarea = $iarea;
						if($iarea == 99){
							$thisarea = 0;
							$thisnum *= 1.5;
						}elseif($iarea == 98){
							$thisarea = 1;
						}
						if($thisarea > 1 || in_array($imap, $itempls_ignore)) {
							$iname = '';//过滤2禁以后刷新的或者危险地点玩意
						}else{
							if($imap == 99) {//全图随机物折减数目
								$thisnum /= 33;
							}
							$star = \itemmix_sync\itemmix_get_star($ikind);//记录游戏王道具星数
							if(!isset($starnum[$star])) $starnum[$star] = 0;
							$starnum[$star] += $thisnum;
						}
					}
				}
				//商店出售
				elseif(strpos($ipdkey, 'shopitem')===0) {
					if(!empty($ipdval2) && strpos($ipdval2,',')!==false)
					{
						list($kind,$num,$price,$area,$iname)=explode(',',$ipdval2);
						if($price > 0){
							//$thisnum = $num;
							$thisarea = $area;
							if($thisarea > 2) {
								$iname = '';//过滤2禁以后刷新的玩意
							}else{
								//估算玩家购买意愿，10块钱相当于数目500（常刷的固定道具），60000块钱相当于数目1（浮云物）
								$thisnum = 	4989.918/$price + 1.0082;
								if($thisnum > $num) $thisnum = $num;//个数限制
							}
						}else{
							$iname = '';
						}
					}
				}
				//同调
				elseif(strpos($ipdkey, 'sync')===0){
					if(!empty($ipdval2) && strpos($ipdval2,',')!==false)
					{
						list($iname,$ik,$ie,$is,$isk,$star,$special)=explode(',',$ipdval2);
						
						$recipe = array();
						for($i=1;$i<$star;$i++){
							for($j=1;$j<=$star-$i;$j++){
								if(isset($starnum[$i]) && isset($starnum[$j])){
									$recipe0 = array($i, $j);
									if($i + $j < $star) $recipe0[] = $star - $i - $j;//只算2-3个合成的情况
									sort($recipe0);
									$recipe0 = implode('_', $recipe0);
									$recipe[] = $recipe0;
								}
							}
						}
						$recipe = array_unique($recipe);
						$avg = array();
						foreach($recipe as &$rr){
							$rr = explode('_', $rr);
							$tmpnum = 0;
							foreach($rr as $rrv){
								$tmpnum += $starnum[$rrv];
							}
							$avg[] = $tmpnum / count($rr) / count($rr); //平均数再除以个数
						}
						if(!empty($avg)) $thisnum = array_sum($avg) / count($avg);
						else $thisnum = 0;
						if($special) $thisnum /= 2;
					}
				}
				//超量
				elseif(strpos($ipdkey, 'overlay')===0){
					if(!empty($ipdval2) && strpos($ipdval2,',')!==false)
					{
						list($iname,$ik,$ie,$is,$isk,$star,$num)=explode(',',$ipdval2);
						$thisnum = $starnum[$star] / $num / 2;//大部分超量要打钱才能合，难度视为2倍
					}
				}
				//通常合成
				elseif(strpos($ipdkey, 'mixitem')===0){
					$iname = trim($ipdval2['result'][0]);
					//忽略隐藏合成
					if($ipdval2['class'] == 'hidden'){
						$iname = '';
					}else{
						$thisnum = 500;
						$thisarea = 0;
						foreach($ipdval2['stuff'] as $stv){//挨个判定合成素材
							if(!isset($slist[$stv])){
								$iname = ''; break;//已处理数据里不存在这个素材，那么这一合成不予处理（可能会有少数不按顺序来的合成被过滤）
							}else{
								$thisnum = min($slist[$stv][1], $thisnum); 
								$thisarea = max($slist[$stv][2], $thisarea);
							}
						}
						$thisnum /= count($ipdval2['stuff']); 
					}
				}
				
				//各类礼品盒
				elseif(strpos($ipdkey, 'present')===0 || strpos($ipdkey, 'ygo')===0 || strpos($ipdkey, 'fybox')===0){
					if(!empty($ipdval2) && strpos($ipdval2,',')!==false)
					{
						list($iname,$kind)=explode(',',$ipdval2);

						$thisnum = 0.1;//礼品盒恒视为0.1
						$thisarea = 1;//礼品盒恒视为1禁
						if(strpos($ipdkey, 'fybox')===0) $thisnum = 0.01;//浮云更浮云
					}
				}
				//NPC
				elseif(strpos($ipdkey, 'npc')!==false && (!isset($ipdval2['num']) || $ipdval2['num'] > 0) 
					&& !in_array($ipdkey2, array(1, 4, 7, 9, 12, 13, 14, 15, 16, 20, 21, 22, 40))){
					$nownpclist = array($ipdval2);
					
					if(isset($ipdval2['sub'])){
						$ipdval2['type'] = $ipdkey2;
						$nownpclist = array();
						foreach ($ipdval2['sub'] as $subval){
							$nownpclist[] = array_merge($ipdval2, $subval);
						}
					}elseif(strpos($ipdkey,'enpc')!==false) {
						$nownpclist = $ipdval2;
						foreach($nownpclist as &$nval){
							$nval['type'] = $ipdkey2;
						}
					}
					
					foreach ($nownpclist as $nownpc){
						foreach(array('wep','arb','arh','ara','arf','art','itm1','itm2','itm3','itm4','itm5','itm6') as $nipval){
							if(!empty($nownpc[$nipval])) {
								$globalnum = 0;
								$iname = $nownpc[$nipval];

								$thisnum = isset($nownpc['num']) ? $nownpc['num']/sizeof($nownpclist) : 1;
								if($nownpc['type'] != 90){
									//$thisnum /= 2;//非杂兵数目除以2
									if($nownpc['mhp'] > 3000){
										$thisnum /= ($nownpc['mhp'] / 3000);//血越多则数目视为越少
									}
								}
								
								itemnumlist_num_area_proc($slist, $ipdkey, $iname, $thisnum, $thisarea);
							}
						}
					}
					
				}
				
				if((is_array($ipdval2) || !empty(trim($ipdval2))) && !empty($iname) && strpos($ipdkey, 'npc')===false){//npc另外判定
					itemnumlist_num_area_proc($slist, $ipdkey, $iname, $thisnum, $thisarea);
				}
				
			}
		}
		
		$ret = array();
		
		foreach($slist as $sk => $sv){
			foreach($sv[0] as $skv){
				if(!isset($ret[$skv])) $ret[$skv] = array();
				$ret[$skv][$sk] = array($sv[1], $sv[2]);
			}
		}
		
		return $ret;
	}
}

?>