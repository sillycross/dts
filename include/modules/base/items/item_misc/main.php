<?php

namespace item_misc
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['U']='扫雷设备';
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ($itmk=='U') 
		{
			$trapresult = $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' AND itme>='$itme'");
			$trpnum = $db->num_rows($trapresult);
			if ($trpnum>0){
				$itemno = rand(0,$trpnum-1);
				$db->data_seek($trapresult,$itemno);
				$mi=$db->fetch_array($trapresult);
				$deld = $mi['itm'];
				$delp = $mi['tid'];
				$db->query("DELETE FROM {$tablepre}maptrap WHERE tid='$delp'");
				$log.="远方传来一阵爆炸声，伟大的<span class=\"yellow\">{$itm}</span>用生命和鲜血扫除了<span class=\"yellow\">{$deld}</span>。<br><span class=\"red\">实在是大快人心啊！</span><br>";
			}else{
				$log.="你使用了<span class=\"yellow\">{$itm}</span>，但是没有发现陷阱。<br>";
			}
			\itemmain\itms_reduce($theitem);
			return;
		}elseif (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {	
			if ($itm == '御神签') {
				$log .= "使用了<span class=\"yellow\">$itm</span>。<br>";
				divining ();
				\itemmain\itms_reduce($theitem);
				return;
			} elseif ($itm == '凸眼鱼') {
				eval(import_module('corpse'));
				$tm = $now - $corpseprotect;//尸体保护
				$db->query ( "UPDATE {$tablepre}players SET corpse_clear_flag='1',weps='0',arbs='0',arhs='0',aras='0',arfs='0',arts='0',itms0='0',itms1='0',itms2='0',itms3='0',itms4='0',itms5='0',itms6='0',money='0' WHERE hp <= 0 AND endtime <= $tm" );
				$cnum = $db->affected_rows ();
				addnews ( $now, 'corpseclear', $name, $cnum );
				$log .= "使用了<span class=\"yellow\">$itm</span>。<br>突然刮起了一阵怪风，吹走了地上的{$cnum}具尸体！<br>";
				\itemmain\itms_reduce($theitem);
				return;
			} elseif ($itm == '■DeathNote■') {
				include template('deathnote');
				$cmd = ob_get_contents();
				ob_clean();
				$log .= '你翻开了■DeathNote■<br>';
				return;
			} elseif ($itm == '游戏解除钥匙') {
				$state = 6;
				$url = 'end.php';
				\sys\gameover ( $now, 'end3', $name );
			}elseif ($itm == '『C.H.A.O.S』') {
				$flag=false;
				$log.="一阵强光刺得你睁不开眼。<br>强光逐渐凝成了光球，你揉揉眼睛，发现包裹里的东西全都不翼而飞了。<br>";
				for ($i=1;$i<=6;$i++){
					global ${'itm'.$i},${'itmk'.$i},${'itme'.$i},${'itms'.$i},${'itmsk'.$i};
					$itm = & ${'itm'.$i};
					$itmk = & ${'itmk'.$i};
					$itme = & ${'itme'.$i};
					$itms = & ${'itms'.$i};
					$itmsk = & ${'itmsk'.$i};
					if ($itm=='黑色发卡') {$flag=true;}
					$itm = '';
					$itmk = '';
					$itme = 0;
					$itms = 0;
					$itmsk = '';
				}
				$karma=$rp*$killnum-$def+$att;
				$f1=false;
				//『G.A.M.E.O.V.E.R』itmk:Y itme:1 itms:1 itmsk:zxZ
				if (($ss>=600)&&($killnum<=15)){
					$itm0='『T.E.R.R.A』';
					$itmk0='Y';
					$itme0=1;
					$itms0=1;
					$itmsk0='z';
					include_once GAME_ROOT . './include/game/itemmain.func.php';
					\itemmain\itemget();
					$f1=true;
				}
				if ($karma<=2000){
					$itm0='『A.Q.U.A』';
					$itmk0='Y';
					$itme0=1;
					$itms0=1;
					$itmsk0='x';
					include_once GAME_ROOT . './include/game/itemmain.func.php';
					\itemmain\itemget();
					$f1=true;
				}
				if ($flag==true){
					$itm0='『V.E.N.T.U.S』';
					$itmk0='Y';
					$itme0=1;
					$itms0=1;
					$itmsk0='Z';
					include_once GAME_ROOT . './include/game/itemmain.func.php';
					\itemmain\itemget();
					$f1=true;
				}
				if ($f1==false){
					$itm0='『S.C.R.A.P』';
					$itmk0='Y';
					$itme0=1;
					$itms0=1;
					include_once GAME_ROOT . './include/game/itemmain.func.php';
					\itemmain\itemget();
				}
			}elseif ($itm == '『G.A.M.E.O.V.E.R』') {
				$state = 6;
				$url = 'end.php';
				\sys\gameover ( $now, 'end7', $name );
			}elseif ($itm == '杏仁豆腐的ID卡') {
				$duelstate = \gameflow_duel\duel($now,$itm);
				if($duelstate == 50){
					$log .= "<span class=\"yellow\">你使用了{$itm}。</span><br><span class=\"evergreen\">“干得不错呢，看来咱应该专门为你清扫一下战场……”</span><br><span class=\"evergreen\">“所有的NPC都离开战场了。好好享受接下来的杀戮吧，祝你好运。”</span>——林无月<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				}elseif($duelstate == 51){
					$log .= "你使用了<span class=\"yellow\">{$itm}</span>，不过什么反应也没有。<br><span class=\"evergreen\">“咱已经帮你准备好舞台了，请不要要求太多哦。”</span>——林无月<br>";
				} else {
					$log .= "你使用了<span class=\"yellow\">{$itm}</span>，不过什么反应也没有。<br><span class=\"evergreen\">“表演的时机还没到呢，请再忍耐一下吧。”</span>——林无月<br>";
				}
				return;
			} elseif ($itm == '奇怪的按钮') {
				$button_dice = rand ( 1, 10 );
				if ($button_dice < 5) {
					$log .= "你按下了<span class=\"yellow\">$itm</span>，不过好像什么都没有发生！";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				} elseif ($button_dice < 8) {
					$state = 6;
					$url = 'end.php';
					\sys\gameover ( $now, 'end5', $name );
				} else {
					$log .= '好像什么也没发生嘛？<br>咦，按钮上的标签写着什么？“危险，勿触”……？<br>';
					$log .= '呜哇，按钮爆炸了！<br>';
					$state = 30;
					\player\update_sdata(); $sdata['sourceless'] = 1; $sdata['attackwith'] = '';
					\player\kill($sdata,$sdata);
					\player\player_save($sdata);
					\player\load_playerdata($sdata);
				}
				return;
			} else if (substr($itm,0,strlen('提示纸条'))=='提示纸条') {
				if ($itm == '提示纸条A') {
					$log .= '你读着纸条上的内容：<br>“执行官其实都是幻影，那个红暮的身上应该有召唤幻影的玩意。”<br>“用那个东西然后打倒幻影的话能用游戏解除钥匙出去吧。”<br>';
				} elseif ($itm == '提示纸条B') {
					$log .= '你读着纸条上的内容：<br>“我设下的灵装被残忍地清除了啊……”<br>“不过资料没全部清除掉。<br>用那个碎片加上传奇的画笔和天然属性……”<br>“应该能重新组合出那个灵装。”<br>';
				} elseif ($itm == '提示纸条C') {
					$log .= '你读着纸条上的内容：<br>“小心！那个叫红暮的家伙很强！”<br>“不过她太依赖自己的枪了，有什么东西能阻挡那伤害的话……”<br>';
				} elseif ($itm == '提示纸条D') {
					$log .= '你读着纸条上的内容：<br>“喂你真的是全部买下来了么……”<br>“这样的提示纸条不止这四种，其他的纸条估计被那两位撒出去了吧。”<br>“总之祝你好运。”<br>';
				} elseif ($itm == '提示纸条E') {
					$log .= '你读着纸条上的内容：<br>“生存并不能靠他人来喂给你知识，”<br>“有一套和元素有关的符卡的公式是没有出现在帮助里面的，用逻辑推理好好推理出正确的公式吧。”<br>“金木水火土在这里都能找到哦～”<br>';
				} elseif ($itm == '提示纸条F') {
					$log .= '你读着纸条上的内容：<br>“我不知道另外那个孩子的底细。如果我是你的话，不会随便乱惹她。”<br>“但是她貌似手上拿着符文册之类的东西。”<br>“也许可以利用射程优势？！”<br>“你知道的，法师的射程都不咋样……”<br>';
				} elseif ($itm == '提示纸条G') {
					$log .= '你读着纸条上的内容：<br>“上天保佑，”<br>“请不要在让我在模拟战中被击坠了！”<br>“空羽 上。”<br>';
				} elseif ($itm == '提示纸条H') {
					$log .= '你读着纸条上的内容：<br>“在研究施设里面出了大事的SCP竟然又输出了新的样本！”<br>“按照董事长的意见就把这些家伙当作人体试验吧！”<br>署名看不清楚……<br>';
				} elseif ($itm == '提示纸条I') {
					$log .= '你读着纸条上的内容：<br>“嗯……”<br>“制作神卡所用的各种认证都可以在商店里面买到。”<br>“其实卡片真的有那么强大的力量么？”<br>';
				} elseif ($itm == '提示纸条J') {
					$log .= '你读着纸条上的内容：<br>“知道么？”<br>“果酱面包果然还是甜的好，哪怕是甜的生姜也能配制出如地雷般爆炸似的美味。”<br>“祝你好运。”<br>';
				} elseif ($itm == '提示纸条K') {
					$log .= '你读着纸条上的内容：<br>“水符？”<br>“你当然需要水，然后水看起来是什么颜色的？”<br>“找一个颜色类似的东西合成就有了吧。”<br>';
				} elseif ($itm == '提示纸条L') {
					$log .= '你读着纸条上的内容：<br>“木符？”<br>“你当然需要树叶，然后说到树叶那是什么颜色？”<br>“找一个颜色类似的东西合成就有了吧。”<br>';
				} elseif ($itm == '提示纸条M') {
					$log .= '你读着纸条上的内容：<br>“火符？”<br>“你当然需要找把火，然后说到火那是什么颜色？”<br>“找一个颜色类似的东西合成就有了吧。”<br>';
				} elseif ($itm == '提示纸条N') {
					$log .= '你读着纸条上的内容：<br>“土符？”<br>“说到土那就是石头吧，然后说到石头那是什么颜色？”<br>“找一个颜色类似的东西合成就有了吧。”<br>';
				} elseif ($itm == '提示纸条P') {
					$log .= '你读着纸条上的内容：<br>“金符？这个的确很绕人……”<br>“说到金那就是炼金，然后这是21世纪了，炼制一个金色方块需要什么？”<br>“总之祝你好运。”<br>';
				} elseif ($itm == '提示纸条Q') {
					$log .= '你读着纸条上的内容：<br>“据说在另外的空间里面；”<br>“一个吸血鬼因为无聊就在她所居住的地方洒满了大雾，”<br>“真任性。”<br>';
				} elseif ($itm == '提示纸条R') {
					$log .= '你读着纸条上的内容：<br>“知道么，”<br>“东方幻想乡这作游戏里面EXTRA的最终攻击”<br>“被老外们称作『幻月的Rape Time』，当然对象是你。”<br>';
				} elseif ($itm == '提示纸条S') {
					$log .= '你读着纸条上的内容：<br>“土水符？”<br>“哈哈哈那肯定是需要土和水啦，可能还要额外的素材吧。”<br>“总之祝你好运。”<br>';
				} elseif ($itm == '提示纸条T') {
					$log .= '你读着纸条上的内容：<br>“我一直对虚拟现实中的某些迹象很在意……”<br>“这种未名的威压感是怎么回事？”<br>“总之祝你好运。”<br>';
				} elseif ($itm == '提示纸条U') {
					$log .= '你读着纸条上的内容：<br>“纸条啥的……”<br>“希望这张纸条不会成为你的遗书。”<br>“总之祝你好运。”<br>';
				} else {
					$log .= '你打开了纸条，发现是一张白纸。<br>';
				}
				return;
			} else if (substr($itm,0,strlen('任务指令书'))=='任务指令书') {
				if ($itm == '任务指令书A') {
					$log .= '指令书上这样写着：<br>“很高兴大家能来参与幻境系统的除错工作。”<br>“我们对系统进行了一些调整，就算遭遇袭击和陷阱也不会造成致命伤害，所以请尽管放心。”<br>“任务结束后我们会根据工作量发放相应的奖励。”<br>';
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
					$log .= "你一口干掉了<span class=\"yellow\">$itm</span>，不过好像什么都没有发生！";
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
			} elseif ($itm == '水果刀') {
				$flag = false;
				
				for($i = 1; $i <= 6; $i ++) {
					foreach(Array('香蕉','苹果','西瓜') as $fruit){
						
						if ( strpos ( ${'itm' . $i} , $fruit ) !== false && strpos ( ${'itm' . $i} , '皮' ) === false && (strpos ( ${'itmk' . $i} , 'H' ) === 0 || strpos ( ${'itmk' . $i} , 'P' ) === 0 )) {
							if($wk >= 120){
								$log .= "练过刀就是好啊。你娴熟地削着果皮。<br><span class=\"yellow\">${'itm'.$i}</span>变成了<span class=\"yellow\">★残骸★</span>！<br>咦为什么会出来这种东西？算了还是不要吐槽了。<br>";
								${'itm' . $i} = '★残骸★';
								${'itme' . $i} *= rand(2,4);
								${'itms' . $i} *= rand(3,5);
								$flag = true;
								$wk++;
							}else{
								$log .= "想削皮吃<span class=\"yellow\">${'itm'.$i}</span>，没想到削完发现只剩下一堆果皮……<br>手太笨拙了啊。<br>";
								${'itm' . $i} = str_replace($fruit, $fruit.'皮',${'itm' . $i} );
								${'itmk' . $i} = 'TN';
								${'itms' . $i} *= rand(2,4);
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
						$log .= "<span class=\"red\">$itm</span>变钝了，无法再使用了。<br>";
						$itm = $itmk = $itmsk = '';
						$itme = $itms = 0;
					}
				}
				return;
			} elseif(strpos($itm,'RP回复设备')!==false){
				$rp = 0;
				$log .= "你使用了<span class=\"yellow\">$itm</span>。你的RP归零了。<br>";
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
			$log .= "大凶？总觉得有什么可怕的事快要发生了<BR><span class=\"red b\">【命】-$uphp 【攻】-$upatt 【防】-$updef</span><BR>";
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
		$att-=$upatt;
		$def-=$updef;

		return "$uphp,$upatt,$updef";

	}

	function deathnote($itmd=0,$dnname='',$dndeath='',$dngender='m',$dnicon=1,$sfn) {
		
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
		if($dnname == $sfn){
			$log .= "你不能自杀。<br>";
			return;
		}
		if(!$dndeath){$dndeath = '心脏麻痹';}
		//echo "name=$dnname,gender = $dngender,icon=$dnicon,";
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$dnname' AND type = 0");
		if(!$db->num_rows($result)) { 
			$log .= "你使用了■DeathNote■，但是什么都没有发生。<br>哪里出错了？<br>"; 
		} else {
			$edata = \player\fetch_playerdata($dnname);
			if(($dngender != $edata['gd'])||($dnicon != $edata['icon'])) {
				$log .= "你使用了■DeathNote■，但是什么都没有发生。<br>哪里出错了？<br>"; 
			} else {
				$log .= "你将<span class=\"yellow b\">$dnname</span>的名字写在了■DeathNote■上。<br><span class=\"yellow b\">$dnname</span>被你杀死了。";
				$edata['state'] = 28; $sdata['attackwith']=$dndeath;
				\player\update_sdata(); 
				\player\kill($sdata,$edata);
				\player\player_save($edata);
				\player\player_save($sdata);
				\player\load_playerdata($sdata);
				$killnum++;
			}
		}
		$dns--;
		if($dns<=0){
			$log .= '■DeathNote■突然燃烧起来，转瞬间化成了灰烬。<br>';
			$dn = $dnk = $dnsk = '';
			$dne = $dns = 0;
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input'));
		
		if($mode == 'deathnote') {
			if($dnname){
				deathnote($item,$dnname,$dndeath,$dngender,$dnicon,$name);
			} else {
				$log .= '嗯，暂时还不想杀人。<br>你合上了■DeathNote■。<br>';
				$mode = 'command';
			}
			return;
		}
		$chprocess();
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		$dname = $typeinfo[$b].' '.$a;
		if(!$e)
			$e0="<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
		else  $e0="<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
			
		if($news == 'death28') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"yellow\">$d</span>意外身亡{$e0}";
		if($news == 'death30') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因误触伪装成核弹按钮的蛋疼机关被炸死{$e0}";
		if($news == 'death38')
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因为敌意过剩，被虚拟意识救♀济！{$e0}";
			
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
