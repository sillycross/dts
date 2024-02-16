<?php

namespace item_misc
{
	function init() 
	{
		eval(import_module('itemmain'));
		
		if (defined('MOD_NOISE'))
		{
			eval(import_module('noise'));
			$noiseinfo['corpseclear']='一阵强大的吸力';
		}
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		
		if(strpos($k,'Y')===0 || strpos($k,'Z')===0){
			if ($n == '凸眼鱼'){
				$ret .= '使用后可以销毁整个战场现有的尸体';
			}elseif ($n == '■DeathNote■') {
				$ret .= '填入玩家的名字和头像就可以直接杀死该玩家';
			}elseif ($n == '游戏解除钥匙') {
				$ret .= '使用后达成『锁定解除』胜利';
			}elseif ($n == '奇怪的按钮') {
				$ret .= '警告：极度危险！';
			}elseif ($n == '『C.H.A.O.S』') {
				$ret .= '献祭包裹里的全部物品以获得通往『幻境解离』的必备道具。需要持有黑色发卡，当前歌魂不少于233点，且击杀玩家数不能过多。';
			}elseif ($n == '『S.C.R.A.P』') {
				$ret .= '还不满足『幻境解离』的条件！使用后可以恢复成『C.H.A.O.S』';
			}elseif ($n == '『G.A.M.E.O.V.E.R』') {
				$ret .= '使用后达成『幻境解离』胜利';
			}elseif ($n == '杏仁豆腐的ID卡') {
				$ret .= '连斗后使用可以让全场NPC消失并进入『死斗阶段』';
			}elseif (strpos($n,'水果刀')!==false) {
				$ret .= '可以切水果，视你的斩系熟练度决定生成补给还是水果皮';
			}
		}
		return $ret;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {	
			if ($itm == '御神签') {
				$log .= "使用了<span class=\"yellow b\">$itm</span>。<br>";
				divining ();
				\itemmain\itms_reduce($theitem);
				return;
			} elseif ($itm == '凸眼鱼') {
				eval(import_module('corpse'));
				$tm = $now - $corpseprotect;//尸体保护
				if ($gametype!=2)
					$db->query ( "UPDATE {$tablepre}players SET corpse_clear_flag='1',weps='0',arbs='0',arhs='0',aras='0',arfs='0',arts='0',itms0='0',itms1='0',itms2='0',itms3='0',itms4='0',itms5='0',itms6='0',money='0' WHERE hp <= 0 AND endtime <= $tm" );
				else	$db->query ( "UPDATE {$tablepre}players SET corpse_clear_flag='1',weps='0',arbs='0',arhs='0',aras='0',arfs='0',arts='0',itms0='0',itms1='0',itms2='0',itms3='0',itms4='0',itms5='0',itms6='0',money='0' WHERE type > 0 AND hp <= 0 AND endtime <= $tm" );
				$cnum = $db->affected_rows ();
				addnews ( $now, 'corpseclear', $name, $cnum );
				if (defined('MOD_NOISE')) \noise\addnoise($pls,'corpseclear',$pid);
				$log .= "使用了<span class=\"yellow b\">$itm</span>。<br>突然刮起了一阵怪风，";
				if($cnum) $log .= "<span class=\"yellow b\">吹走了地上的{$cnum}具尸体！</span><br>";
				else $log .= "不过好像没有什么效果？";
				\itemmain\itms_reduce($theitem);
				return;
			} elseif ($itm == '■DeathNote■') {
				include template('deathnote');
				$cmd = ob_get_contents();
				ob_clean();
				$log .= '你翻开了■DeathNote■<br>';
				return;
			} elseif ($itm == '游戏解除钥匙') {
				//$state = 6;
				$winner_flag = 3;//2023.12.09 获胜标记现在写入$winner_flag而不是$state，避免脏数据污染
				\player\player_save($sdata, 1);
				$url = 'end.php';
				\sys\gameover ( $now, 'end3', $name );
			}elseif ($itm == '『C.H.A.O.S』') {
				$flag=false;
				$log.="一阵强光刺得你睁不开眼。<br>强光逐渐凝成了光球，你揉揉眼睛，发现包裹里的东西全都不翼而飞了。<br>";
				for ($i=1;$i<=6;$i++){
					//global ${'itm'.$i},${'itmk'.$i},${'itme'.$i},${'itms'.$i},${'itmsk'.$i};
					if (${'itm'.$i}=='黑色发卡') {
						$flag=true;
						$tmp_itm = ${'itm'.$i}; $tmp_itmk = ${'itmk'.$i}; $tmp_itmsk = ${'itmsk'.$i};
						$tmp_itme = ${'itme'.$i}; $tmp_itms = ${'itms'.$i};
					}
					${'itm'.$i} = ${'itmk'.$i} = ${'itmsk'.$i} = '';
					${'itme'.$i} = ${'itms'.$i} = 0;
				}
				
				$f1=$f2=$f3=false;
				//『G.A.M.E.O.V.E.R』itmk:Y itme:1 itms:1 itmsk:zxZ
				if ($ss>=233){//歌魂大于等于233点
					$itm0='『T.E.R.R.A』';
					$itmk0='Y';
					$itme0=1;
					$itms0=1;
					$itmsk0='z';
					\itemmain\itemget();
					$f1=true;
				}
				//杀玩家数小于等于100
				//$karma=$rp*$killnum-$def+$att;
				if ($killnum<=100){
					$itm0='『A.Q.U.A』';
					$itmk0='Y';
					$itme0=1;
					$itms0=1;
					$itmsk0='x';
					\itemmain\itemget();
					$f2=true;
				}
				if ($flag==true){//拥有黑色发卡
					$itm0='『V.E.N.T.U.S』';
					$itmk0='Y';
					$itme0=1;
					$itms0=1;
					$itmsk0='Z';
					\itemmain\itemget();
					$f3=true;
				}
				if (!$f1 || !$f2 || !$f3){
					$itm0='『S.C.R.A.P』';
					$itmk0='Z';
					$itme0=1;
					$itms0=1;
					$itmsk0='';
					\itemmain\itemget();
					if(isset($tmp_itm)){
						for ($i=1;$i<=6;$i++){
							if(!${'itms'.$i}) {
								${'itm'.$i} = $tmp_itm; ${'itmk'.$i} = $tmp_itmk; ${'itmsk'.$i} = $tmp_itmsk;
								${'itme'.$i} = $tmp_itme; ${'itms'.$i} = $tmp_itms;
								break;
							}
						}
					}					
				}
				return;
			}elseif ($itm == '『S.C.R.A.P』') {
				$log.="你眼前一黑。当你再次能看见东西，你发现包裹里的东西再次不翼而飞了。<br>";
				for ($i=1;$i<=6;$i++){
					if (${'itm'.$i}!='黑色发卡'){
						${'itm'.$i} = ${'itmk'.$i} = ${'itmsk'.$i} = '';
						${'itme'.$i} = ${'itms'.$i} = 0;
					}					
				}
				$itm0='『C.H.A.O.S』';
				$itmk0='Z';
				$itme0=1;
				$itms0=1;
				$itmsk0='';
				\itemmain\itemget();
				return;
			}elseif ($itm == '『G.A.M.E.O.V.E.R』') {
				//$state = 6;
				$winner_flag = 7;//2023.12.09 获胜标记现在写入$winner_flag而不是$state，避免脏数据污染
				\player\player_save($sdata, 1);
				$url = 'end.php';
				\sys\gameover ( $now, 'end7', $name );
			}elseif ($itm == '杏仁豆腐的ID卡') {
				if ($gametype==2)
				{
					$log.='本模式下不可用。<br>';
					return;
				}
				$duelstate = \gameflow_duel\duel($now,$itm);
				if($duelstate == 50){
					$log .= "<span class=\"yellow b\">你使用了{$itm}。</span><br><span class=\"evergreen b\">“干得不错呢，看来咱应该专门为你清扫一下战场……”</span><br><span class=\"evergreen b\">“所有的NPC都离开战场了。好好享受接下来的杀戮吧，祝你好运。”</span>——林无月<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				}elseif($duelstate == 51){
					$log .= "你使用了<span class=\"yellow b\">{$itm}</span>，不过什么反应也没有。<br><span class=\"evergreen b\">“咱已经帮你准备好舞台了，请不要要求太多哦。”</span>——林无月<br>";
				} else {
					$log .= "你使用了<span class=\"yellow b\">{$itm}</span>，不过什么反应也没有。<br><span class=\"evergreen b\">“表演的时机还没到呢，请再忍耐一下吧。”</span>——林无月<br>";
				}
				return;
			} elseif ($itm == '权限狗的ID卡') {
				$ugroupid = $cudata['groupid'];
				$upassword = $cudata['password'];
				if(pass_compare($cuser, $cpass, $upassword) && ($ugroupid >= 5 || $cuser == $gamefounder)){
					$log.='大逃杀幻境已确认你的权限狗身份，正在为你输送权限套装……<br>';
					$wp=$wk=$wg=$wc=$wd=$wf=666;
					$ss=$mss=600;
					//$att+=200;$def+=200;
					$money+=114494;
					$itm1='会员制大餐';$itmk1 = 'HB';$itme1 = 114514;$itms1 = 1919;$itmsk1 = '';
					$itm2='你不准增加禁区';$itmk2 = 'Z';$itme2 = 1;$itms2 = 44;$itmsk2 = '';
					$itm3='百毒贴吧';$itmk3 = 'HR';$itme3 = 100;$itms3 = 100;$itmsk3 = '';
					$itm4='哔哔小马';$itmk4 = 'ER';$itme4 = 20;$itms4 = 1;$itmsk4 = '^rdsk58';
					$itm5='脑残片';$itmk5 = 'ME';$itme5 = 100;$itms5 = 100;$itmsk5 = '';				
					if (defined('MOD_EX_STORAGE'))
					{
						//劲爆！
						$itmarr = array
						(
							array('感觉像最终战术『光矢』的东西','WB',65500,'∞','Zrcdz'),
							array('武器师安雅的奖赏','Y',1,20,'z'),
							array('这个是什么按钮','Y',1,10,'z'),
							array('你不准增加禁区','Y',1,10,'z'),
							array('游戏解除钥匙','Y',1,1,'z'),
							array('移动PC','EE',20,1,'z',),
							array('《电波大逃杀源代码：从初学到跑路》','VS',1,10,'479'),
							array('薛定谔的棒球棍','WP',3000,300,'cj'),
							array('感觉像复合武器的杏仁豆腐','WFJ',999,999,'rd'),
							array('NPC召唤设备','Y',1,1,'42'),
							array('RP回复设备','Y',1,1,'z'),
							array('氪金道具','VO',1,10,'159'),
							array('隐身药水','MB',1,10,'^mbid246'),
							array('权限狗的ID卡','Y',1,1,'z'),
							array('『G.A.M.E.O.V.E.R』','Z',1,1,'z'),
							array('权限狗的权限权杖','WF',42000000,'∞','ZrdVny'),
							array('脑残片','ME',100,100,''),
							array('黄鸡之歌','HM',2000,20,'z'),
							array('百毒贴吧','HR',100,100,''),
							array('小薄本','VV',666,3,''),
							array('《全频道阻塞干扰》','VS',1,1,'503'),
							array('《孔明的陷阱》','VS',1,1,'252'),
							array('『re-inCarnation』','MB',1,1,'^mbid710'),
							array('感觉像弹幕符札的东西','WF',1,'∞',''),
						);
						$itm0='权限狗的全家桶';$itmk0 = 'DAS';$itme0 = 1919;$itms0 = 810;
						$itmsk0 = '^st_'.\ex_storage\storage_encode_itmarr($itmarr).'1^vol50';
					}
					$arb='权限狗的毛绒兽装';$arbk = 'DB';$arbe = 5000;$arbs = 1000;$arbsk = 'Bb';
					$arh='权限狗的毛绒头套';$arhk = 'DH';$arhe = 5000;$arhs = 1000;$arhsk = 'Aa';
					$ara='权限狗的毛绒前爪';$arak = 'DA';$arae = 5000;$aras = 1000;$arask = 'Hh';
					$arf='权限狗的毛绒后爪';$arfk = 'DF';$arfe = 5000;$arfs = 1000;$arfsk = 'Mm';
					$art='Untainted Glory';$artk = 'A';$arte = 1;$arts = 1;$artsk = 'c^hu2000';
					if (defined('MOD_CLUBBASE')) eval(import_module('clubbase'));
					foreach(array(1010,1011,1012) as $skv){
						if(defined('MOD_SKILL'.$skv)) {
							if (!\skillbase\skill_query($skv)) {
								$log.="你获得了技能「<span class=\"yellow b\">$clubskillname[$skv]</span>」！<br>";
								\skillbase\skill_acquire($skv);
							}
						}
					}
					addnews ( $now, 'adminitem', $name, $itm );
				}else{
					$log.='你没有足够的权限。可能因为是你的缓存密码有误，也可能你压根就不是一条权限狗。<br>';
				}
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				$mode='command';$command='';
				return;
			} elseif ($itm == '奇怪的按钮') {
				$button_dice = rand ( 1, 10 );
				if(1 == $itmsk) $button_dice = 6;
				$log .= "你按下了<span class=\"yellow b\">$itm</span>。<br>";
				if ($button_dice < 5) {
					$log .= '按钮不翼而飞，你的手中多了一瓶褐色的饮料，上面还有个标签……<br><span class="gold b">“感谢特朗普总统选用我司的可乐递送服务。”</span><br>蛤？<br>';
					$itm = '特朗普特供版「核口可乐」';
					$itmk = 'HB';
					$itmsk = '';
					$itme = 200;
					$itms = 1;
				} elseif ($button_dice < 8) {
					//$state = 6;
					$winner_flag = 5;//2023.12.09 获胜标记现在写入$winner_flag而不是$state，避免脏数据污染
					\player\player_save($sdata, 1);
					$url = 'end.php';
					\sys\gameover ( $now, 'end5', $name );
				} else {
					$log .= '好像什么也没发生嘛？咦，按钮上的标签写着什么？<br><span class="red b">“危险，勿触！”</span>……？<br>呜哇，按钮爆炸了！<br>';
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
					$state = 30;
					\player\update_sdata(); $sdata['sourceless'] = 1; $sdata['attackwith'] = '';
					\player\kill($sdata,$sdata);
					\player\player_save($sdata);
					\player\load_playerdata($sdata);
				}
				return;
			} elseif (substr($itm,0,strlen('任务指令书'))=='任务指令书') {
				if ($itm == '任务指令书A') {
					$log .= '指令书上这样写着：<br>“很高兴大家能来参与幻境系统的除错工作。”<br>“我们对系统进行了一些调整，就算遭遇袭击和陷阱也不会造成致命伤害，所以请尽管放心。”<br>“任务结束后我们会根据工作量发放相应的奖励。”<br>';
				} else if ($itm == '任务指令书B') {
					ob_clean();
					include template('MOD_SKILL475_EXPLANATION');
					$log .= ob_get_contents();
					ob_clean();
					return;
				} else {
					$log .= '你展开了指令书，发现上面什么都没写。<br>';
				}
				return;
			}elseif ($itm == '仪水镜') {
				$log .= '水面上映出了你自己的脸，你仔细端详着……<br>';
				if ($rp < 40){
					$log .= '你的脸看起来十分白皙。<br>';
				} elseif ($rp < 200){
					$log .= '你的脸看起来略微有点黑。<br>';
				} elseif ($rp < 550){
					$log .= '你的脸上貌似笼罩着一层黑雾。<br>';
				} elseif ($rp < 1200){
					$log .= '你的脸已经和黑炭差不多了，赶快去洗洗！<br>';
				} elseif ($rp < 5499){
					$log .= '你印堂漆黑，看起来最近要有血光之灾！<br>';
				} elseif ($rp > 5500){
					$log .= '水镜中已经黑的如墨一般了。<br>希望你的H173还在……<br>';
				} else{
					$log .= '你的脸从水镜中消失了。<br>';
				}
				return;
			} elseif ($itm == '风祭河水'){
				$slv_dice = rand ( 1, 20 );
					if ($slv_dice < 8) {
					$log .= "你一口干掉了<span class=\"yellow b\">$itm</span>，不过好像什么都没有发生！";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				} elseif ($slv_dice < 16) {
					$rp = $rp - 10*$slv_dice;
					$log .= "你感觉身体稍微轻了一点点。<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				} elseif ($slv_dice < 20) {
					$rp = 0 ;
					$log .= "你头晕脑胀地躺到了地上，<br>感觉整个人都被救济了。<br>你努力着站了起来。<br>";
					$wp = $wk = $wg = $wc = $wd = $wf = 100;
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				} else {
					$log .= '你头晕脑胀地躺到了地上，<br>感觉整个人都被救济了。<br>';
					$log .= '然后你失去了意识。<br>';
					$state = 35;
					\player\update_sdata(); $sdata['sourceless'] = 1; $sdata['attackwith'] = '';
					\player\kill($sdata,$sdata);
					\player\player_save($sdata);
					\player\load_playerdata($sdata);
				}
				return;
			} elseif (strpos($itm,'水果刀')!==false) {
				$flag = false;
				
				for($i = 1; $i <= 6; $i ++) {
					foreach(Array('香蕉','苹果','西瓜','葡萄','菠萝','脸') as $fruit){
						
						if ( strpos ( ${'itm' . $i} , $fruit ) !== false && strpos ( ${'itm' . $i} , '皮' ) === false && (strpos ( ${'itmk' . $i} , 'H' ) === 0 || strpos ( ${'itmk' . $i} , 'P' ) === 0 )) {
							if($wk >= 120){
								$log .= "练过刀就是好啊。你娴熟地削着果皮。<br><span class=\"yellow b\">${'itm'.$i}</span>变成了<span class=\"yellow b\">★残骸★</span>！<br>咦为什么会出来这种东西？算了还是不要吐槽了。<br>";
								${'itm' . $i} = '★残骸★';
								${'itme' . $i} *= rand(2,4);
								${'itms' . $i} *= rand(3,5);
								${'itmsk' . $i} = '';
								$flag = true;
								$wk++;
							}else{
								$log .= "想削皮吃<span class=\"yellow b\">${'itm'.$i}</span>，没想到削完发现只剩下一堆果皮……<br>手太笨拙了啊。<br>";
								${'itm' . $i} = str_replace('唯一','不唯一',str_replace($fruit, $fruit.'皮',${'itm' . $i} ));
								${'itmk' . $i} = 'TN';
								${'itms' . $i} *= rand(2,4);
								${'itmsk' . $i} = '';
								$flag = true;
								$wk++;
							}
							break;
						}
					}
					if($flag == true) {break;};
				}
				if (! $flag) {
					$log .= '包裹里没有水果。<br>';
				} else {
					$dice = rand(1,5);
					if($dice==1){
						$log .= "<span class=\"red b\">$itm</span>变钝了，无法再使用了。<br>";
						$itm = $itmk = $itmsk = '';
						$itme = $itms = 0;
					}
				}
				return;
			} elseif(strpos($itm,'NPC召唤设备')!==false){
				$log .= "你使用了<span class=\"yellow b\">$itm</span>。<br>";
				\addnpc_event\addnpc_event($itmsk);
				//\addnpc\addnpc($itmsk, 0, 1, 1);
				return;
			}elseif(strpos($itm,'RP回复设备')!==false){
				$rp = 0;
				$log .= "你使用了<span class=\"yellow b\">$itm</span>。你的RP归零了。<br>";
				return;
			} elseif(strpos($itm,'测试用阻塞设备')!==false){
				sleep(10);
				$log .= "刚才那是什么，是卡了么？<br>";
				//$hp = 1;
				return;
			} elseif(strpos($itm,'测试用查看常量设备')!==false){
				eval(import_module('clubbase'));
				for($i=10;$i<610;$i++){
					if(defined('MOD_SKILL'.$i.'_INFO')) {
						$log .= $i.'号技能「'.$clubskillname[$i].'」标签：'.constant('MOD_SKILL'.$i.'_INFO').'<br>';
					}
				}
				return;
			} elseif(strpos($itm,'测试道具A')!==false){
				var_dump(\attrbase\config_process_encode_comp_itmsk('z^res_<:comp_itmsk:>{测试成功,HB,2,2,,}1Z'));
				return;
			} elseif('『我是说在座的各位都是垃圾』' === $itm){
				$mhpdown = 100;
				if($mhp <= $mhpdown){
					$log .= '一个声音传来：<span class="yellow b">“wslnm，没血你装什么逼？！”</span><br>';
				}elseif($now - $starttime > 300){//开局5分钟之内吃才有用
					$log .= '你一边拉屎，一边看着外边满地乱滚的无名沙包，忽然决定给自己增加一点挑战。不过你胯下的翔似乎已经凉了。<br>';
				}else{
					$mhp -= $mhpdown;
					if($hp > $mhp) $hp = $mhp;
					$log .= '你一边拉屎，一边看着外边满地乱滚的无名沙包，忽然决定给自己增加一点挑战。于是你抓起自己胯下的翔，大口地吃了下去。<br><span class="red b">你自扣了100点生命上限！</span><br>';
					if(!$club) {
						$log .= '你突然想起一件很重要的事情：<span class="red b">老子还没选称号呢？</span>不过似乎你不用担心了，因为<span class="yellow b">你刚才吃下的翔化为了你的力量！</span><br>';
						\clubbase\club_acquire(97);
					}
					\sys\addnews ( 0, 'debuffself', $name);
					\sys\addchat(6, "{$name}一边大口吃翔一边说道：“满场沙包，不足为惧。且看爷吃了这百斤翔，再来包你们爽！”");
				}
				return;
			}
		}
		$chprocess($theitem);
	}
	
	function divining(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','player'));
		
		$dice = rand(0,99);
		if($dice < 20) {
			$up = 5;
			list($uphp,$upatt,$updef) = explode(',',divining1($up));
			$log .= "是大吉！要有什么好事发生了！<BR><span class=\"yellow b\">【命】+$uphp 【攻】+$upatt 【防】+$updef</span><BR>";
		} elseif($dice < 40) {
			$up = 3;
			list($uphp,$upatt,$updef) = explode(',',divining1($up));
			$log .= "中吉吗？感觉还不错！<BR><span class=\"yellow b\">【命】+$uphp 【攻】+$upatt 【防】+$updef</span><BR>";
		} elseif($dice < 60) {
			$up = 1;
			list($uphp,$upatt,$updef) = explode(',',divining1($up));
			$log .= "小吉吗？有跟无也没有什么分别。<BR><span class=\"yellow b\">【命】+$uphp 【攻】+$upatt 【防】+$updef</span><BR>";
		} elseif($dice < 80) {
			$up = 1;
			list($uphp,$upatt,$updef) = explode(',',divining2($up));
			$log .= "凶，真是不吉利。<BR><span class=\"red b\">【命】-$uphp 【攻】-$upatt 【防】-$updef</span><BR>";
		} else {
			$up = 3;
			list($uphp,$upatt,$updef) = explode(',',divining2($up));
			$log .= "大凶？总觉得有什么可怕的事快要发生了……<BR><span class=\"red b\">【命】-$uphp 【攻】-$upatt 【防】-$updef</span><BR>";
		}
		return;
	}

	function divining1($u) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		
		$uphp = rand(0,$u);
		$upatt = rand(0,$u);
		$updef = rand(0,$u);
		
		$hp+=$uphp;
		$mhp+=$uphp;
		$att+=$upatt;
		$def+=$updef;

		return "$uphp,$upatt,$updef";

	}

	function divining2($u) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		
		$uphp = rand(0,$u);
		$upatt = rand(0,$u);
		$updef = rand(0,$u);
		
		$hp-=$uphp;
		$mhp-=$uphp;
		$hp = max($hp, 1);
		$mhp = max($mhp, 1);
		$att-=$upatt;
		$def-=$updef;

		return "$uphp,$upatt,$updef";

	}

	function deathnote($itmd=0,$dnname='',$dndeath='',$dngender='m',$dnicon=1) {
		
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		
		$dn = & ${'itm'.$itmd};
		$dnk = & ${'itmk'.$itmd};
		$dne = & ${'itme'.$itmd};
		$dns = & ${'itms'.$itmd};
		$dnsk = & ${'itmsk'.$itmd};

		$mode = 'command';

		if($dn != '■DeathNote■'){
			$log .= '道具使用错误！<br>';
			return;
		} elseif($dns <= 0) {
			$dn = $dnk = $dnsk = '';
			$dne = $dns = 0;
			$log .= '道具不存在！<br>';
			return;
		}

		if(!$dnname){return;}
		if($dnname == $cuser){
			$log .= "你不能自杀。<br>";
			return;
		}
		if(!$dndeath){$dndeath = '心脏麻痹';}
		$dn_ignore_words = deathnote_process($dnname,$dndeath,$dngender,$dnicon);
		$dns--;
		if($dns<=0){
			if(!$dn_ignore_words) $log .= '■DeathNote■突然燃烧起来，转瞬间化成了灰烬。<br>';
			$dn = $dnk = $dnsk = '';
			$dne = $dns = 0;
		}
		return;
	}
	
	function deathnote_process($dnname='',$dndeath='',$dngender='m',$dnicon=1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		$log .= "你将<span class=\"yellow b\">$dnname</span>的名字写在了■DeathNote■上。";
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$dnname' AND type = 0 AND hp > 0");
		$ret = false;
		if(!$db->num_rows($result)) { 
			$log .= "但是什么都没有发生。<br>哪里出错了？<br>"; 
		} else {
			$edata = \player\fetch_playerdata($dnname);
			if(($dngender != $edata['gd'])||($dnicon != $edata['icon'])) {
				$log .= "但是什么都没有发生。<br>哪里出错了？<br>"; 
			} else {
				$log .= "<br><span class=\"yellow b\">$dnname</span>被你杀死了。";
				deathnote_process_core($sdata, $edata, $dndeath);
				$ret = true;
			}
		}
		return $ret;
	}
	
	function deathnote_process_core(&$pa, &$pd, $dndeath='')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pd['state'] = 28;
		$pa['attackwith']=$dndeath;
		\player\kill($pa,$pd);
		\player\player_save($pd);
		\player\player_save($pa);
		\player\load_playerdata($pa);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		if($mode == 'deathnote') {
			$dnname = get_var_input('dnname');
			if($dnname){
				list($item, $dndeath, $dngender, $dnicon) = get_var_input('item', 'dndeath', 'dngender', 'dnicon');
				deathnote($item, $dnname, $dndeath, $dngender, $dnicon);
			} else {
				$log .= '嗯，暂时还不想杀人。<br>你合上了■DeathNote■。<br>';
				$mode = 'command';
			}
			return;
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if(isset($exarr['dword'])) $e0 = $exarr['dword'];
		
		if($news == 'adminitem') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}使用了{$b}，变成了一条权限狗！（管理员{$a}宣告其正在进行测试。）</span></li>";	
		elseif($news == 'death28') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因<span class=\"yellow b\">$d</span>意外身亡{$e0}</li>";
		elseif($news == 'death30') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因误触伪装成核弹按钮的蛋疼机关被炸死{$e0}</li>";
		elseif($news == 'death38')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因为敌意过剩，被虚拟意识救♀济！{$e0}</li>";
		elseif($news == 'debuffself')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}认为在座的各位都是垃圾，并大口吃下一百斤翔以表达他的不屑！（{$a}自扣了100点生命上限）</span></li>";
			
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>