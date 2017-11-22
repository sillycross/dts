<?php

namespace gtype1
{
	function init() {}
	
	function prepare_new_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (room_check_subroom($room_prefix)) return $chprocess();
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		$tg=$gamenum-3;
		$res=$db->query("SELECT gametype FROM {$gtablepre}history WHERE gid='$tg'");
		$gt=1;
		if ($db->num_rows($res)){
			$zz=$db->fetch_array($res); $gt=$zz['gametype'];
		}

		if (!$disable_event && $gt!=1){//开启活动&&最多连续3局
 			if ( ($wday==3 && $hour>=19 && $hour<21) || ($wday==6 && $hour>=15 && $hour<17) ){ //周三19点-21点；周六15点-17点
 				$gametype=1;
 				prepare_new_game_gtype1();
 			}
 		}
		$chprocess();
	}
	
	//除错模式每局之前生成一次道具表
	function prepare_new_game_gtype1()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$dir = GAME_ROOT.'/gamedata/cache';
		if(!file_exists($dir)) mymkdir($dir);
		$file = $dir.'/gtype1item.config.php';
		$contents = "<?php\r\n if(!defined('IN_GAME')) exit('Access Denied');\r\n";
		//各文件位置
		$iplacefilelist = array(
			'mapitem' => GAME_ROOT.'/include/modules/base/itemmain/config/mapitem.config.php',
			'shopitem' => GAME_ROOT.'./include/modules/base/itemshop/config/shopitem.config.php',
			'mixitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix/config/itemmix.config.php',
			'syncitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix_sync/config/sync.config.php',
			'overlayitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix_overlay/config/overlay.config.php',
			'presentitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/present.config.php',
			'ygoitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/ygobox.config.php',
			'fyboxitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/fybox.config.php',
			'npc' => GAME_ROOT.'./include/modules/extra/instance/gtype1/main/config/npc.data.config.php',
			'addnpc' => GAME_ROOT.'./include/modules/base/addnpc/config/addnpc.config.php',
			'evonpc' => GAME_ROOT.'./include/modules/extra/club/skills/skill21/config/evonpc.config.php',
		);
		//如果更新过文件则生成道具表
		$need_update = 0;
		if(!file_exists($file)) $need_update = 1;
		else{
			foreach($iplacefilelist as $ipfval){
				if(filemtime($ipfval) > filemtime($file)){
					$need_update = 1; 
					break;
				}
			}
		}
		if(!$need_update) return;
		
		//从各文件提取道具信息
		$iplacefiledata = array();
		foreach($iplacefilelist as $ipfkey => $ipfval){
			if($ipfkey == 'mixitem') {
				include $ipfval;
				$iplacefiledata[$ipfkey] = $mixinfo;
			}elseif(strpos($ipfkey, 'npc') !==false){
				include $ipfval;
				if($ipfkey == 'npc') $varname = 'npcinfo_gtype1';
				elseif($ipfkey == 'addnpc') $varname = 'anpcinfo';
				elseif($ipfkey == 'evonpc') $varname = 'enpcinfo';
				if(!empty($varname)) $iplacefiledata[$ipfkey] = $$varname;
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
						if($thisarea > 1) {
							$iname = '';//过滤2禁以后刷新的玩意
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
					//忽略隐藏合成以及一些浮云玩意
					if($ipdval2['class'] == 'hidden' || in_array($iname, array('游戏解除钥匙', '『G.A.M.E.O.V.E.R』'))){
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
				elseif(strpos($ipdkey, 'npc')!==false && (!isset($ipdval2['num']) || $ipdval2['num'] > 0) && !in_array($ipdkey2, array(1, 4, 7, 9, 12, 13, 14, 15, 16, 20, 21, 22, 40))){
					$nownpclist = array($ipdval2);
					
					if(isset($ipdval2['sub'])){
						$ipdval2['type'] = $ipdkey2;
						$nownpclist = array();
						foreach ($ipdval2['sub'] as $subval){
							$nownpclist[] = array_merge($ipdval2, $subval);
						}
					}elseif($ipdkey == 'evonpc') {
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
								prepare_update_slist($slist, $ipdkey, $iname, $thisnum, $thisarea);
							}
						}
					}
					
				}
				
				if(!empty($iname) && strpos($ipdkey, 'npc')===false){//npc另外判定
					prepare_update_slist($slist, $ipdkey, $iname, $thisnum, $thisarea);
				}
				
			}
		}
		
		
		foreach(array_keys($iplacefilelist) as $ival){
			${'cont_'.$ival} = array();
		}
		
		foreach($slist as $sk => $sv){
			foreach($sv[0] as $skv){
				${'cont_'.$skv}[$sk] = array($sv[1], $sv[2]);
			}
		}
		
		foreach(array_keys($iplacefilelist) as $ival){
			$contents .= '$cont_'.$ival."=array(\r\n";
			foreach(${'cont_'.$ival} as $sk => $sv){
				$contents .= "'$sk' => array({$sv[0]}, {$sv[1]}),\r\n";
			}
			$contents .= ");\r\n";
		}
		
		file_put_contents($file, $contents);
		//writeover($file, $contents, 'rb+', 0);
	}
	
//	function check_player_discover(&$edata)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys'));
//		if ($edata['type']==0 && $gametype == 1) {
//			return 0;	
//		}
//		return $chprocess($edata);
//	}

	function prepare_update_slist(&$slist, $ikey, $iname, $thisnum, $thisarea){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
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
		$slist[$iname] = array($ikind, $thisnum, $thisarea);
	}

	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','gtype1'));
		if (1 == $gametype){
			return $npcinfo_gtype1;
		}else return $chprocess();
	}
	
	//接管meetman_alternative，主要是判定遭遇玩家时必定为队友
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if($gametype == 1){
			if(!$edata['type']){
				$log .= '你看到了同为测试工程师的玩家，你们似乎有点尴尬地笑了笑。<br>';
				\team\findteam($edata);
				return;
			}
		}
		return $chprocess($edata);
	}
	//接管calculate_hide_obbs，玩家隐蔽率上升40%（不然太容易互相遭遇）
	function calculate_hide_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata);
		eval(import_module('sys'));
		if($gametype == 1 && !$edata['type']){
			$ret += 40;
		}
		return $ret;
	}
	
	//递送道具时无视teamID
	function senditem_check($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		if ($gametype == 1)
		{
			if(isset($edata) && !$edata['type'] && $edata['pls'] == $pls && $edata['hp'] > 0 && $edata['pid'] != $pid){
				return true;
			}
		}
		return $chprocess($edata);
	}
	
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if ( $gametype==1 && $areanum<$areaadd*2 && $alivenum>0 ){
			return;
		}
		$chprocess($time);
	}
	
	function rs_game($xmode = 0) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		if (($gametype==1)&&($xmode & 2)) 
		{
			$weather = 1;
			$hack=1;
			//save_gameinfo();
		}
		
	}
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($gametype==1){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');//理论不会这样，防BUG
				return;
			}
			if ($areanum>=($areaadd*2)){//限时2禁
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 ORDER BY pid DESC");
				$ml=-1;
				$winner='';
				$wl=array();
				$wc=0;
				while($pt = $db->fetch_array($result)) {
					$pa=\player\fetch_playerdata_by_pid($pt['pid']);
					$cl=(int)\skillbase\skill_getvalue(424,'lvl',$pa);
					//$wl[$pa['name']]=$cl;
					if ($cl>$ml){
						$ml=$cl;
						$winner=$pa['name'];
					}
					$wc++;
					$wl[$wc]['n']=$pa['name'];
					$wl[$wc]['p']=$pa['pid'];
					$wl[$wc]['c']=$cl;
				}
				//arsort($wl);
				for ($i=1;$i<$wc;$i++){
					for ($j=$i+1;$j<=$wc;$j++){
						if (($wl[$i]['c']<$wl[$j]['c'])||($wl[$i]['c']==$wl[$j]['c'])&&($wl[$i]['p']<$wl[$j]['p'])){
							$tt=$wl[$i];
							$wl[$i]=$wl[$j];
							$wl[$j]=$tt;
						}
					}
				}
				$rk=0; 
				$max_announce_num = 3;	//进行状况展示人数
				$bestlist = Array();	//进行状况中展示的前X名列表
				//foreach ($wl as $kk=>$v){
				for ($rk=1;$rk<=$wc;$rk++){
					//$rk++;
					$kk=$wl[$rk]['n'];
					$v=$wl[$rk]['c'];
					$k=\player\fetch_playerdata($kk);
					if ($v>=5){
						\cardbase\get_qiegao(150,$k);
					}
					if ($v>=10){
						\cardbase\get_qiegao(300,$k);
					}
					if ($v>=20){
						\cardbase\get_qiegao(600,$k);
					}
					if ($v>=30){
						\cardbase\get_card(94,$k);
						\cardbase\get_qiegao(500,$k);
					}
					if ($rk==1){
						\cardbase\get_card(96,$k);
						\cardbase\get_card(95,$k);
					}
					if ($rk<=2){
						\cardbase\get_card(95,$k);
					}
					if ($rk<=$max_announce_num){
						\cardbase\get_qiegao(500,$k);
						$bestlist[$rk] = Array(0=>$kk, 1=>$v);
					}		
				}
				
				for ($i=$max_announce_num; $i>=1; $i--) 
					if (isset($bestlist[$i]))
						addnews(0,'g1announce',$i,$bestlist[$i][0],$bestlist[$i][1]);
						
				\sys\gameover($atime,'end8',$winner);
				return;
			}
			\sys\rs_game(16+32);
			return;
		}
		$chprocess($atime);	
	}

	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys','lvlctl'));
		if ($gametype==1) $lvupskpt=0;
	}
	
	//除错模式雏菊无事件
	function event_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'player'));
		if(19==$gametype && $pls == 33) return false;
		return $chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'g1announce') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime\">本次游戏第<span class=\"yellow\">{$a}</span>名是完成了<span class=\"yellow\">{$c}</span>次除错的<span class=\"yellow\">{$b}</span>。</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	//改变DN逻辑
	function deathnote_process($dnname='',$dndeath='',$dngender='m',$dnicon=1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman'));
		if($gametype==1 && $dnname){
			$edata = \player\fetch_playerdata('黑熊',21);
			if(isset($edata['pid']) && $edata['hp'] > 0){//黑熊NPC存在且存活，可以伪造战斗界面，因而剧情不同
				$o_fog = $fog; $fog = 0;
				extract($edata,EXTR_PREFIX_ALL,'w');
				$battle_title = '发现敌人';
				\metman\init_battle();
				$main = MOD_METMAN_MEETMAN;
				$cmd = <<<EOH
<input type="button" class="cmdbutton" value="返回" onclick="postCmd('gamecmd','command.php');this.disabled=true;">
EOH;
				$log .= <<<EOT
你将<span class="yellow b">$dnname</span>的名字写在了红暮的脸上。但是什么都没有发生。<br>哪里出错了？<br>
还没等你合上■DeathNote■，就听到<span class="red b">英灵殿</span>的方向传来一声愤怒的咆哮。你扭头一看，发现一只长得很像<span class="yellow">熊本熊</span>的东西站在你身后，他看起来很生气。<br>
“熊本熊”伸出手来，指了指他自己，又指了指你，然后做了个“<span class="yellow">60</span>”的手势。<br>
虽然你不知道他的意思，但你看得懂气氛，乖乖交出了■DeathNote■，想了想，又<span class="yellow">掏出了60元钱</span>，一起交给了他。<br>
“熊本熊”接过钱愣了几秒，之后把■DeathNote■紧紧抱在怀里，钻进旁边的树丛里不见了踪影。<br>之后，似乎传来了舔什么的声音。<br>
不知为何，你决定还是把这件事完全忘掉。<br>
EOT;
				$money -= 60;if($money < 0) $money = 0;
				$fog = $o_fog;
			}else{
				$log .= '■DeathNote■忽然直接燃烧了起来。<br><span class="red">这不是火焰，是灼焰！你被■DeathNote■点燃的灼焰烧伤了！</span><br>■DeathNote■很快化为了灰烬。<br>';
				$inf = str_replace('u','',$inf);
				$inf .= 'u';
			}
			return true;
		}
		return $chprocess($dnname,$dndeath,$dngender,$dnicon);
	}
}

?>