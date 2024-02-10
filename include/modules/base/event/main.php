<?php

namespace event
{
	function init() {}

	//是否允许事件发生。本模块恒为是
	function event_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
	}
	
	//探索时概率触发事件
	function discover($schmode) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if(check_event_happen()){
			$ret = event_main();
			if(!can_continue_post_event_proc($ret)) {
				eval(import_module('sys'));
				$mode = 'command';
				return;
			}
		}
		return $chprocess($schmode);
	}

	//判定是否发生事件。
	//本模块的一般事件是纯概率判定，英灵殿则是必定发生事件。
	function check_event_happen()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = false;
		if(event_available()) {
			if(rand(0,99) < get_var_in_module('event_obbs', 'event') || check_event_happen_special()) {
				$ret = true;
			}
		}
		return $ret;
	}

	//无视概率必定发生的事件
	//如果别的模块需要增加必定事件则需要重载此函数。
	function check_event_happen_special()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$ret = false;
		if($art!="Untainted Glory" && $pls==34 && !\gameflow_duel\is_gamestate_duel()) {
			$ret = true;
		}
		return $ret;
	}
	
	//没有事件的地图，执行event事件以后继续判定遭遇敌人
	function can_continue_post_event_proc($evret)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return !$evret;
	}

	//2024.02.10 考虑到事件本身是高度过程性（而不是数据性）的，最终选择了保留函数形式，只是添加了更多复用

	//事件主函数。已经确定会发生事件的情况下，调用本函数
	//返回值为0表示没有事件或者没有成功触发事件，返回值为1表示触发了事件
	//内有事件死亡的判定，其他模块要添加事件的话，请重载event_core()而非本函数
	function event_main()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));

		$dice = rand(0,99);
		$dice2 = rand(20,40);
		$ret = event_core($dice, $dice2);

		if($hp<=0 && $state < 10){
			event_death();
		}

		return $ret;
	}

	//事件判定核心函数
	//主要功能是根据地点、玩家数值等判定执行事件的哪个效果
	//返回值为0表示没有事件或者没有成功触发事件，返回值为1表示触发了事件
	function event_core($dice, $dice2)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$ret = 0;
		
		switch ($pls) {
			case 2://RF高校
				$log .= '<br>突然，一个头戴面具的制服怪人华丽地现身，向你发出不容拒绝的挑战！<br>';
				//基础胜率10%，生命值比400每多100则胜率+10%
				$winrate = event_winrate_process(10 + ($hp - 400) / 10);
				if($dice < $winrate) {
					$log .= '你奋力还击，怪人“呜嘛呜——！”地怪叫着。<br>';
					if(rand(0,99) < 20 && $rp <= 45) {//rp值小于45时有20%概率获得钱
						$log .= '他双手奉上一个钱包，转身逃走了。<br>钱包里面是'.$dice2.'个硬币……<br>';
						event_get_money($dice2);
						event_get_rp(15);
					}else{
						$log .= '<span class="yellow b">借此机会，你顺利逃跑了。</span><br>';
					}
				}else{
					$log .= '“呜嘛呜——！”怪人挥动长矛（的柄），打中了你的脑袋！<br>';
					if(rand(0,99) < 50) {
						event_suffer_inf('h');
					}else{
						event_suffer_dmg($dice2);
					}
				}
				$ret = 1;
				break;

			case 21://圣G
				if(\gameflow_duel\is_gamestate_duel()) {//连斗不触发任何事件
					$log .= '<br>就在不久之前，特殊部队『天使』的少女们正在这里实弹演习，不过现在她们不知所踪。<br>';
				}else{
					$log .= '<br>隶属于时空部门G的特殊部队『天使』的少女们驾驶着帅气的机体，正在进行实弹演习！<br>你被卷入了弹幕中！<br>';
					//只要击杀玩家数大于0且rp大于40，都有1/63的概率被砸死。现在把这个概率修正为1/100
					if($dice < 1 && $rp > 40 && $killnum > 0) {
						$log .= '咦，头顶上……好像有一名少女被弹幕击中了……？<br>';
						event_death(33);
						return $ret;
					}
					//基础回避率33%，每有3级则回避率+1%
					$escrate = event_escrate_process(33 + $lvl/3);
					if($dice < $escrate) {
						$log .= '在弹幕的狂风中，你有惊无险地回避着弹幕，总算擦弹成功了。<br>';
						//原本回避成功有1/21的概率获得573元，现在改为5/100，但需要rp小于100
						if($dice < 5 && $rp < 100) {
							$log .= '<br>你看见驾驶着棕色机体的少女向你飞来。<br>“实在对不起，我们看起来没有放假的时候啊。危险躲藏在每个大意之中不是么？”<br>她扔给了你一叠东西，看起来是面额为573的『纸币』？<br>“祝你好运！”少女这么说完就飞走了。<br>';
							event_get_money(573);
							event_get_rp(100);
							$log .= '<br>这样的纸钞真的能用吗……？<br>';
						}
					}else{
						$log .= '<br>在弹幕的狂风中，你徒劳地试图回避弹幕……<br>擦弹什么的根本做不到啊！<br><br>你被少女们打成了筛子！<br>';
						//每个部位有60%概率受伤
						$infcache = '';
						foreach(Array('h','b','a','f') as $v){
							if(rand(0,99) <= 60){
								$infcache .= $v;
							}
						}
						if(!empty($infcache)) event_suffer_inf($infcache);
						//异常状态，减少了每个异常的概率，增加了无异常的概率（大约1/7）
						if($dice2 >= 39){//2/21
							$log .= '并且，弹幕击中了要害！<br><span class="red b">你感觉自己的小命差点就交代在这里了</span>。<br>';
							event_suffer_dmg($hp-1);
						}
						elseif($dice2 >= 36){//以下几个异常都是1/7
							$log .= '一发<span class="cyan b">黑洞激光</span>正中你的面门，';
							event_suffer_inf('i');
						}
						elseif($dice2 >= 33){
							$log .= '一发<span class="red b">环形激光</span>正中你的面门，';
							event_suffer_inf('u');
						}
						elseif($dice2 >= 30){
							$log .= '一发<span class="yellow b">精神震荡弹</span>正中你的面门，';
							event_suffer_inf('e');
						}
						elseif($dice2 >= 27){
							$log .= '一发<span class="grey b">音波装备</span>正中你的面门，';
							event_suffer_inf('w');
						}
						elseif($dice2 >= 24){
							$log .= '一发<span class="purple b">干扰用强袭装备</span>正中你的面门，';
							event_suffer_inf('p');
						}//4/21概率无异常
						if(!empty($inf) || $hp < 10) $log .= '<br>你遍体鳞伤、连滚带爬地逃走了。<br><br>';
						else $log .= '<br>你一边离开战场，一边庆幸还能全身而退。<br><br>';
					}
				}
				$ret = 1;
				break;

			case 33://雏菊
				$kdata = \player\fetch_playerdata('■', 4);
				if(empty($kdata)) {
					$flag = 0;//篝未加入战场，正常处理事件
				}elseif($kdata['hp'] > 0) {
					$flag = 1;//篝加入战场且存活
				}else{
					$flag = 2;//篝加入战场而且跪了
				}
				unset($kdata);

				if(1 == $flag){
					$log .= '端坐在雏菊之丘的少女现在拥有敌意。<br>明白这一点的你，刻意躲避着少女的追踪——现在不用担心被绘卷搞得七窍流血了。<br>';
				}elseif(2 == $flag){
					$log .= '雏菊盛开的山丘上，少女的尸体静静地躺着，犹如睡着了一般——这里再也没有任何危险了。<br>但不知为何，你丝毫没有轻松的感觉。<br>';
				}else{
					$log .= '<br>';
					if ($dice < 30){//30%概率，低互动度，收益不高但风险也不大，不会死亡
						if ($rp < 40){
							$log .= '少女抬头看了你一眼，随后低下头去继续她的研究。<br>';
							event_get_rp(round($dice2 / 2));
						}elseif ($rp < 500){
							$log .= '少女抬头看了你一眼，视线在你身上多停留了几秒钟，随后低下头去继续她的研究。<br>';
							event_get_rp(round($dice2 * 2.5));
						}elseif ($rp < 1000 && $killnum == 0){
							$log .= '少女抬头看了你一眼。<br>从那深不可测的眼神中，你读不出任何情感，但不知为什么，你觉得身体稍微舒服了些。<br>';
							$spup = round($dice2 * 2.5);
							if($sp + $spup > $msp) $spup = $msp - $sp;
							if($spup) {
								event_get_field($spup, 'sp');
								event_get_rp($spup);
							}
						}elseif ($rp < 1000){
							$log .= '少女抬头看了你一眼。<br>从那深不可测的眼神中，你读不出任何情感，但不知为什么，你觉得双腿一软……<br>';
							$spdown = round($rp/4);
							event_get_field(-$spdown, 'sp');
						}elseif ($rp < 5000 && $killnum == 0){
							$log .= '少女抬头看了你一眼，随后起身离去。<br>你好奇地看向少女原本翻看的那幅不明『绘卷』……<br>';
							event_suffer_dmg(floor($hp / 2));
							$log .= '『绘卷』记述的知识对你来说太沉重了！<br>你浑身冒血、连滚带爬地逃走了。<br>';
							event_get_rp(-$dice2);//没杀人的负面分支都有扣减rp，下同
						}else{
							$log .= '少女抬头看了你一眼，随后起身离去。<br>你好奇地看向少女原本翻看的那幅不明『绘卷』……<br><br>';
							event_suffer_dmg(floor($hp * 2 / 3));
							$min_msp = 51;
							if($msp > $min_msp) {
								$mspdown = round($dice2);
								if($msp - $mspdown < $min_msp) {
									$mspdown = $msp - $min_msp;
								}
								event_get_field(-$mspdown, 'sp');
								event_get_field(-$mspdown, 'msp');
							}
							$log .= '<br>你感受到了『绘卷』所散发的敌意！<br>不顾身上的重伤，你拼命逃走了。<br>';
						}
					}elseif ($dice < 60){//30%概率，中互动度，中风险、中收益，会死亡
						if ($rp < 40){
							$log .= '少女抬头看着你，貌似对你的举动很感兴趣的样子。可惜，片刻之后，她又低下头去继续她的研究。<br>';
							event_get_rp($dice2);
						}elseif ($rp < 500){
							$log .= '少女抬头看着你，貌似对你的举动很感兴趣的样子。你觉得稍微有些不自在。<br>';
							event_get_rp($dice2 * 5);
						}elseif ($rp < 1000 && $killnum == 0){
							$log .= '少女抬头看着你，随后向你扔来一个保温瓶。<br>里面装着奇怪的深色液体。<br>你喝了一口，感觉味道不怎么样，不过身体变得更加灵活了些。<br>';
							$spup = $dice2;
							event_get_field($spup, 'msp');
							event_get_field($spup, 'sp');
							event_get_rp($dice2 * 2);
						}elseif ($rp < 1000){
							$log .= '少女抬头看着你，随后向你扔来一个保温瓶。<br>你没能接住保温瓶，脸上挨了重重的一下，砸得你晕头转向。<br>';
							$spdown = $sp - 1;
							event_get_field(-$spdown, 'sp');
							event_suffer_inf('h');
						}elseif ($rp < 5000 && $killnum == 0){
							$log .= '少女身后的丝带如闪电般袭来，在你的头上重重地敲了一下。<br>';
							event_suffer_dmg(floor($hp * 0.9));
							event_suffer_inf('hwe');
							event_get_rp(-$dice2);
							$log .= '<br>你几乎被这一下打晕过去，连滚带爬地逃走了。<br>';
						}else{
							death_kagari(rand(1,2));
						}	
					}elseif ($dice < 90){//30%概率，高互动度，高风险、高收益，会死亡
						if ($rp < 40){
							$log .= '少女抬头盯着你，开始注意你的一举一动。<br>';
							event_get_rp($dice2 * 2);
						}elseif ($rp < 500){
							$log .= '少女抬头盯着你，开始注意你的一举一动。她那看不出情感的眼神让你感到不安。<br>';
							event_get_rp($dice2 * 8);
						}elseif ($rp < 1000 && $killnum == 0){
							$log .= '少女抬头看着你，随后向你扔来一个保温瓶。<br>里面装着奇怪的深色液体。<br>你喝了一口，感觉体内有一种力量涌出来。<br>';
							$hpup = $dice2;
							event_get_field($hpup, 'mhp');
							event_get_field($hpup, 'hp');
							event_get_rp($dice2 * 4);
						}elseif ($rp < 1000){
							$log .= '少女抬头看着你，随后向你扔来一个保温瓶。<br>保温瓶重重地砸在你的胸口，你差点喷出一口老血。<br>';
							event_suffer_dmg(floor($hp * 0.6));
							event_suffer_inf('bw');	
						}elseif ($rp < 5000 && $killnum == 0){
							$log .= '少女身后的丝带如闪电般袭来，如斩击一般划开了你的身躯。<br>';
							event_suffer_dmg(floor($hp * 0.99));
							event_suffer_inf('hbaf');
							$log .= '<br>你侥幸捡回一条命，连滚带爬地逃走了。<br>';
							event_get_rp(-$dice2);
						}else{
							death_kagari(rand(1,2));
						}		
					}else{//10%概率，最高风险和最高收益
						if ($rp < 40){
							$log .= '少女飘了起来，像幽灵一般在你身后跟随了一阵子。<br>那深不可测的眼神让你觉得恐怖异常，所幸你最终甩掉了她。<br>';
							event_get_rp($dice2 * 15);
						}elseif ($rp < 500){
							$log .= '你小心翼翼地在少女旁边坐下（竟然没被她赶走！），向她身下的『绘卷』看去……<br>';

							$skillupsum = 0;
							foreach(array('wp','wk','wg','wc','wd','wf') as $val){
								$up = rand(floor($dice2/2), $dice2);
								event_get_field($up, $val);
								$skillupsum += $up;
							}
							event_get_rp($skillupsum * 2);
							$log .= '当你觉得你看懂了点什么的时候，只见少女用惊讶的眼光盯着你，这时你才发现你已经七窍流血。<br>';
							$hpdown = floor($hp * 0.8);
							$spdown = floor($sp * 0.8);
							event_get_field(-$hpdown, 'hp');
							event_get_field(-$spdown, 'sp');
						}elseif ($rp < 1000 && $killnum == 0){
							$log .= '你小心翼翼地在少女旁边坐下，但少女似乎有些不太情愿。<br>她悄无声息地合上『绘卷』，起身离去。<br>';
							$up = $dice2;
							event_get_field($up, 'exp');
							event_get_rp($up * 15);
							$log .= '虽然只短暂地窥见『绘卷』上的一鳞半爪，但你仍为那庞大的信息量震撼，几乎昏迷过去。<br>';
							$hpdown = floor($hp * 0.99);
							$spdown = floor($sp * 0.99);
							event_get_field(-$hpdown, 'hp');
							event_get_field(-$spdown, 'sp');
						}elseif ($rp < 1000){
							$log .= '你试图在少女身旁坐下，但少女只是瞪了你一眼，你便觉得头晕目眩，生命力也似乎被抽干。<br>';
							$skilldownsum = 0;
							foreach(array('wp','wk','wg','wc','wd','wf') as $val){
								$down = rand(floor($dice2/4), floor($dice2/2));
								event_get_field(-$down, $val);
								$skilldownsum += $down;
							}
							event_get_rp(-$skilldownsum);
							event_get_field(-($hp-1), 'hp');
							event_get_field(-($sp-1), 'sp');
							$log .= '太可怕了，还是赶快离开为妙！<br>';
						}elseif ($rp < 5000 && $killnum == 0){
							$log .= '少女瞪了你一眼，你被一种无形的压力直接压在了地上。<br>';
							if($mhp > 37){
								$mhpdown = rand(1, floor($mhp / 2));
								event_get_field(-$mhpdown, 'mhp');
								event_get_field(-$mhpdown, 'hp');
							}
							if($msp > 37){
								$mspdown = rand(1, floor($msp / 2));
								event_get_field(-$mspdown, 'msp');
								event_get_field(-$mspdown, 'sp');
							}
							event_get_rp(-37);
							$log .= '太可怕了，还是赶快离开为妙！<br>';
						}elseif ($rp < 5000){
							death_kagari(rand(1,2));
						}else{
							death_kagari(3);
						}		
					}
					$ret = 1;
					//echo $rp;
				}
				break;

			default:
				$log .= '错误的事件参数。<br>';
				break;
		}

		if(!empty($ret)) {
			$log .= '<br>';
		}

		return $ret;
	}

	//事件主函数
	//事件默认只对当前玩家生效，所以大多数直接操作sdata的内容	
	function event()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','wound'));
		$ret = 0;
		$dice1 = rand(0,5);
		$dice2 = rand(20,40);//原为rand(5,10)
		if($pls == 0) { //无月之影
		} elseif($pls == 1) { //端点
		} elseif($pls == 2) { //现RF高校
			$log = ($log . "突然，一个戴着面具的怪人出现了！<BR>");
			if($dice1 == 2){
				$log = ($log . "“呜嘛呜——！”<br>被怪人<span class=\"red b\">打中了头</span>！<BR>");
				$inf = str_replace('h','',$inf);
				$inf = ($inf . 'h');
			}elseif($dice1 == 3){
				$log = ($log . "“呜嘛呜——！”<br>被怪人打中了，<span class=\"red b\">受到{$dice2}点伤害</span>！<BR>");
				$hp-=$dice2;
			}elseif($rp <=45){
				$log = ($log . "“呜嘛呜——！”<br>怪人给了你一个钱包！里面有<span class=\"red b\">{$dice2}个1元硬币</span>！<BR>");
				$money = $money + $dice2 * 1;
				$rp = $rp + 15;
			}else{
				$log = ($log . "呼，总算逃脱了。<BR>");
			}
			$ret = 1;
		} elseif($pls == 3) { //雪之镇
			if($rp <=70){
				$log = ($log . "突然，一位拿着纸袋的少女向你撞来！<BR>");
				if($dice1 == 2){
					$log = ($log . "你身体一侧，成功回避了被撞倒的厄运。<BR>你看着少女面朝下重重地摔在地上，转头走开了。<BR>");
					$rp = $rp + 40;
				}
				else{
					$log = ($log . "你回避不及，被少女撞个正着！<BR>你面朝下地重重地摔在地上。");
					$inf = str_replace('h','',$inf);
					$inf = ($inf . 'h');	
					$rp = $rp + 25;
					$log = ($log . "不过知道少女不是故意找茬后，<BR>你原谅了她，并且和她分享了雕鱼烧，你感觉全身舒畅。");
					if($hp < $mhp) $hp = $mhp;
					if($sp < $msp) $sp = $msp;
				}
			}else{
				$log = ($log . "突然，一位少女向你撞来！<BR>");
				if($dice1 == 2){
					$log = ($log . "你身体一侧，成功回避了被撞倒的厄运。<BR>你看着少女面朝下重重地摔在地上，转头走开了。<BR>");
					$rp = $rp + 40;
				}
				else{
					$log = ($log . "你回避不及，被少女撞个正着！<BR>你面朝下地重重地摔在地上。");
					$inf = str_replace('h','',$inf);
					$inf = ($inf . 'h');	
					$rp = $rp - 5;
				}
			}		
			$ret = 1;
		} elseif($pls == 4) { //索拉利斯
		} elseif($pls == 5) { //指挥中心
		} elseif($pls == 6) { //梦幻馆
		} elseif($pls == 7) { //清水池
			$log = ($log . "糟糕，脚下滑了一下！<BR>");
			if($dice1 <= 3){
				$dice2 += 10;
				if($sp <= $dice2){
					$dice2 = $sp-1;
				}
				$sp-=$dice2;
				$log = ($log . "你摔进了池里！<BR>从水池里爬出来<span class=\"red b\">消耗了{$dice2}点体力</span>。<BR>");
			}else{
				$log = ($log . "万幸，你没跌进池中。<BR>");
			}
			$ret = 1;
		} elseif($pls == 8) { //白穗神社
		} elseif($pls == 9) { //墓地
		} elseif($pls == 10) { //麦斯克林
		} elseif($pls == 11) { //现对天使用作战本部
			$log = ($log . "哇！一个大锤向你锤来！<BR>");
			if($dice1 == 2){
				$log = ($log . "大锤重重地<span class=\"red b\">砸到了腿上</span>，好疼！<BR>");
				$inf = str_replace('f','',$inf);
				$inf = ($inf . 'f');
			}elseif($dice1 == 3){
				$log = ($log . "你被击飞出了窗外，<span class=\"red b\">受到{$dice2}点伤害</span>！<BR>");
				$hp-=$dice2;
			}else{
				$log = ($log . "你勉强躲过了大锤的攻击。<BR>");
			}
			$ret = 1;
		} elseif($pls == 12) { //夏之镇
			$log = ($log . "突然，天空出现一大群乌鸦！<BR>");
			if($dice1 == 2){
				$log = ($log . "被乌鸦袭击，<span class=\"red b\">头部受了伤</span>！<BR>");
				$inf = str_replace('h','',$inf);
				$inf = ($inf . 'h');
			}elseif($dice1 == 3){
				$log = ($log . "被乌鸦袭击，<span class=\"red b\">受到{$dice2}点伤害</span>！<BR>");
				$hp-=$dice2;
			}else{
				$log = ($log . "呼，总算击退了。<BR>");
			}
			$ret = 1;
		} elseif($pls == 13) { //三体星
		} elseif($pls == 14) { //光坂高校
		} elseif($pls == 15) { //守矢神社
			$log = ($log . "突然有妖怪袭击你！<BR>");
			if($dice1 == 2){
				$log = ($log . "被妖怪吓着了！你惊慌中<span class=\"red b\">撞伤了自己的头部</span>！<BR>");
				$inf = str_replace('h','',$inf);
				$inf = ($inf . 'h');
			}elseif($dice1 == 3){
				$log = ($log . "妖怪的弹幕使你<span class=\"red b\">受到{$dice2}点伤害</span>！<BR>");
				$hp-=$dice2;
			}else{
				$log = ($log . "呼，所谓妖怪不过是个撑着紫伞的少女而已，没什么可害怕的。<BR>");
			}
			$ret = 1;
		} elseif($pls == 16) { //常磐森林
			$log = ($log . "野生的皮卡丘从草丛中钻出来了！<BR>");
			if($dice1 == 2){
				$log = ($log . "皮卡丘使用了电击！<span class=\"red b\">手臂被击伤了</span>！<BR>");
				$inf = str_replace('a','',$inf);
				$inf = ($inf . 'a');
			}elseif($dice1 == 3){
				$log = ($log . "皮卡丘使用了电光石火！<span class=\"red b\">受到{$dice2}点伤害</span>！<BR>");
				$hp-=$dice2;
			}else{
				$log = ($log . "成功地逃跑了。<BR>");
			}
			$ret = 1;
		} elseif($pls == 17) { //常磐台中学
		} elseif($pls == 18) { //秋之镇
			$log = ($log . "突然，天空出现一大群乌鸦！<BR>");
			if($dice1 == 2){
				$log = ($log . "被乌鸦袭击，<span class=\"red b\">头部受了伤</span>！<BR>");
				$inf = str_replace('h','',$inf);
				$inf = ($inf . 'h');
			}elseif($dice1 == 3){
				$log = ($log . "被乌鸦袭击，<span class=\"red b\">受到{$dice2}点伤害</span>！<BR>");
				$hp-=$dice2;
			}else{
				$log = ($log . "呼，总算击退了。<BR>");
			}
			$ret = 1;
		} elseif($pls == 19) { //精灵中心
		} elseif($pls == 20) { //春之镇
		} elseif($pls == 21) { //圣Gradius学园
			if($gamestate < 50){
				$log = ($log . "隶属于时空部门G的特殊部队『天使』正在实弹演习！<BR>你被卷入了弹幕中！<BR>");
				if($dice1 <= 1 ){
					$log = ($log . "在弹幕的狂风中，你有惊无险地回避着弹幕，总算擦弹成功了。<BR>");
					if($dice2 == 40 && $rp > 40 && $killnum > 0){
						$log = ($log . "咦，头顶上……好像有一名少女被弹幕击中了……？<BR>“对不起、对不起！”伴随着焦急的道歉声，少女以及她乘坐的机体向你笔直坠落下来。<br>你还来不及反应，重达数十吨的机体便直接落在了你的头上。<br>");
						$state = 33;
						\player\update_sdata(); $sdata['sourceless'] = 1; $sdata['attackwith'] = '';
						\player\kill($sdata,$sdata);
						\player\player_save($sdata);
						\player\load_playerdata($sdata);
						return;
					}
					elseif($dice2 == 30){
					$log = ($log . "接下来你看见驾驶着棕色机体的少女向你飞来。<BR>“实在对不起，我们看起来没有放假的时候啊。危险躲藏在每个大意之中不是么？”<br>她扔给了你些什么东西，貌似是面额为573的『纸币』？<br>“祝你好运！”少女这么说完就飞走了。");
					$money = $money + 573;
					$rp = $rp + 100;
					}
				}
				else{
					$log = ($log . "在弹幕的狂风中，你徒劳地试图回避弹幕……<BR>擦弹什么的根本做不到啊！<BR>你被少女们打成了筛子！<BR>");
					$infcache = '';
					foreach(Array('h','b','a','f') as $value){
						$dice3=rand(0,10);
						if($dice3<=6){
							$inf = str_replace($value,'',$inf);
							$infcache .= $value;
							$log .= "<span class=\"red b\">弹幕造成你{$infname[$value]}了！</span><br />";
						}
					}
					if(empty($infcache)){
						$inf = str_replace('b','',$inf);
						$inf .= 'b';
						$log .= "<span class=\"red b\">弹幕造成你胸部受伤了！</span><br />";
					} else {$inf .= $infcache;}
					if($dice2 >= 39){
						$log = ($log . "并且，少女们的弹幕击中了要害！<BR><span class=\"red b\">你感觉小命差点就交代在这里了</span>。<BR>");
						$hp = 1;
					}
					elseif($dice2 >= 36){
						$log = ($log . "并且，黑洞激光造成你<span class=\"blue b\">冻结</span>了！<BR>");
						$inf = str_replace('i','',$inf);
						$inf = ($inf . 'i');
					}
					elseif($dice2 >= 32){
						$log = ($log . "并且，环形激光导致你<span class=\"red b\">烧伤</span>了！<BR>");
						$inf = str_replace('u','',$inf);
						$inf = ($inf . 'u');
					}
					elseif($dice2 >= 27){
						$log = ($log . "并且，精神震荡弹导致你<span class=\"yellow b\">全身麻痹</span>了！<BR>");
						$inf = str_replace('e','',$inf);
						$inf = ($inf . 'e');
					}
					elseif($dice2 >= 23){
						$log = ($log . "并且，音波装备导致你<span class=\"grey b\">混乱</span>了！<BR>");
						$inf = str_replace('w','',$inf);
						$inf = ($inf . 'w');
					}
					else{
						$log = ($log . "并且，干扰用强袭装备导致你<span class=\"purple b\">中毒</span>了！<BR>");
						$inf = str_replace('p','',$inf);
						$inf = ($inf . 'p');
					}
					$log = ($log . "你遍体鳞伤、连滚带爬地逃走了。<BR>");
				}
			} else {
				$log = ($log . "特殊部队『天使』的少女们不知道去了哪里。<BR>");
			}
			$ret = 1;
		} elseif($pls == 22) { //初始之树
		} elseif($pls == 23) { //幻想世界
		} elseif($pls == 24) { //永恒的世界
		} elseif($pls == 25) { //妖精驿站
		} elseif($pls == 26) { //键刃墓场
		} elseif($pls == 27) { //花菱商厦
		} elseif($pls == 28) { //FARGO前基地
		} elseif($pls == 29) { //风祭森林
		} elseif($pls == 30) { //移动机库
		} elseif($pls == 31) { //太鼓实验室
		} elseif($pls == 32) { //SCP实验室
		} elseif($pls == 33) { //雏菊之丘
			$result = $db->query("SELECT pid,hp FROM {$tablepre}players WHERE type=4");
			if(!$db->num_rows($result)) $flag = 0;//篝未加入战场，正常处理事件；
			else{
				$result = $db->fetch_array($result);
				if($result['hp'] >0) $flag = 1;//篝加入战场
				else $flag = 2;//篝加入战场而且跪了
			}
			if(!$flag){
				$dice=rand(0,10);
				if ($dice < 3){
					if ($rp < 40){
						$log = ($log . "少女抬头看了你一眼，随后低下头去继续她的研究。<BR>");
						$rp = $rp + rand(10,25);
					}elseif ($rp < 500){
						$log = ($log . "少女抬头看了你一眼，貌似对你的举动很感兴趣的样子。<BR>");
						$rp = $rp + rand(50,100);
					}elseif ($rp < 1000 && $killnum == 0){
						$log = ($log . "少女向你扔来一个保温瓶。<BR>里面是类似于咖啡的液体；<BR>你喝了一口，感觉味道不怎么样。<BR>");
						//$rp = $rp + rand(100,200);
						$spup = rand(50,100);
						//$hp = $mhp;
						$msp += $spup;
						$sp = $msp;		
						$rp += $spup*2;	
					}elseif ($rp < 1000){
						$log = ($log . "不知道为什么，你觉得双腿一软……<BR>");
						$spdown = round($rp/4);
						$sp -= $spdown;
						if($sp <= 0){$sp = 1;}
						//$sp = 17;
					}elseif ($rp < 5000 && $killnum == 0){
						$log = ($log . "看见少女离开了，你好奇地向少女身下的那幅不明『绘卷』上看去……<BR>");
						$mhp = $mhp - rand(5,10);
						if($mhp <= 37){$mhp = 37;}
						$hp = 1;
						$msp = $msp - rand(10,20);
						if($msp <= 37){$msp = 37;}
						$sp = 1;
						//$sp = 1;
						$inf = str_replace('h','',$inf);
						$inf = str_replace('b','',$inf);
						$inf = str_replace('a','',$inf);
						$inf = str_replace('f','',$inf);
						$inf = ($inf . 'hbaf');
						$log = ($log . "不能承受绘卷上所述的知识量，你浑身冒血连滚带爬地逃走了。<BR>");
					}elseif ($rp < 5000){
						death_kagari(rand(1,2));
					}else{
						death_kagari(3);
						//$log = ($log . "少女抬头看了你一眼，随后低下头去继续她的研究。<BR>");
					}
				}elseif ($dice < 6){
					if ($rp < 40){
						$log = ($log . "少女抬头看了你一眼，貌似对你的举动很感兴趣的样子。<BR>");
						$rp = $rp + rand(50,100);
					}elseif ($rp < 500){
						$log = ($log . "不知道为什么，你觉得双腿一软……<BR>");
						$hpdown = round($rp/4);
						$hp -= $hpdown;
						if($hp <= 0 ){$hp = 1;}
						//$sp = $sp - 200;			
					}elseif ($rp < 1000 && $killnum == 0){
						$log = ($log . "少女的丝带飞到你的面前，<BR>在你的脸上重重地刮了一下。<BR>");
						$inf = str_replace('h','',$inf);
						$inf = ($inf . 'h');
					}elseif ($rp < 1000){
						$log = ($log . "少女的丝带飞到你的面前，<BR>在你的脸上重重地刮了一下。<BR>");
						$inf = str_replace('e','',$inf);
						$inf = ($inf . 'e');
					}elseif ($rp < 5000 && $killnum == 0){
						$log = ($log . "少女的丝带飞到你的面前，<BR>在你的头上重重地敲了一下。<BR>");
						$inf = str_replace('h','',$inf);
						$inf = str_replace('w','',$inf);
						$inf = ($inf . 'hw');
					}elseif ($rp < 5000){
						death_kagari(rand(1,2));
					}else{
						death_kagari(3);
						//$log = ($log . "少女抬头看了你一眼，随后低下头去继续她的研究。<BR>");
					}		
				}elseif ($dice < 9){
					if ($rp < 40){
						$log = ($log . "少女抬头开始注意你的一举一动。<BR>");
						$rp = $rp + rand(200,400);
					}elseif ($rp < 500){
						$log = ($log . "少女向你扔来一个保温瓶。<BR>里面是奇怪的深色液体；<BR>你喝了一口，感觉体内有一种力量涌出来。<BR>");
						$mhpup = rand(25,50);
						$mhp = $mhp + $mhpup;
						if($hp < $mhp) $hp = $mhp;
						$rp += $mhpup*4;
					}elseif ($rp < 1000 && $killnum == 0){
						$log = ($log . "你小心翼翼地在少女旁边坐下，（竟然没被她赶走！）<BR>看着她身下的『绘卷』<BR>");
						$hp = round($mhp/10);
						if($hp <= 0){$hp = 1;}
						$sp = round($msp/10);
						if($sp <= 0){$sp = 1;}
						$log = ($log . "当你觉得你看懂了点什么的时候<BR>只见少女用惊讶的眼光盯着你。<BR>这时你才发现你已经七窍流血。<BR>");
						$skillupsum = 0;
						foreach(array('wp','wk','wg','wc','wd','wf') as $val){
							$up = rand(23,34);
							${$val} += $up;
							$skillupsum += $up;
						}
						$rp += $skillupsum*2;
					}elseif ($rp < 1000){
						$log = ($log . "你小心翼翼地在少女旁边坐下，想看看她身下的『绘卷』<BR>结果被红色的丝带正中腿部。<BR>");
						$hp = round($mhp/8);
						if($hp <= 0){$hp = 1;}
						$inf = str_replace('f','',$inf);
						$inf = ($inf . 'f');
						$log = ($log . "你龇牙咧嘴地逃走了。<BR>");			
					}elseif ($rp < 5000){
						death_kagari(1);
					}elseif ($rp > 5000){
						death_kagari(2);
					}else{
						$log = ($log . "少女抬头看了你一眼，随后低下头去继续她的研究。<BR>");
					}		
				}else{
					if ($rp < 40){
						$log = ($log . "少女飘了起来，并且跟在了你的后面，<BR>太可怕了，还是赶快离开为妙！<BR>");
						$rp = $rp + rand(500,1000);
					}elseif ($rp < 500){
						$log = ($log . "少女瞪了你一眼，你感觉你的生命力被抽干了，<BR>太可怕了，还是赶快离开为妙！<BR>");
						$oldhp = $hp;$oldsp = $sp;
						$hp = 1;
						$sp = 1;
						$rp = $rp - round(($oldhp+$oldsp)/10);
					}elseif ($rp < 1000 && $killnum == 0){
						$log = ($log . "少女瞪了你一眼，你感觉头晕目眩，<BR>太可怕了，还是赶快离开为妙！<BR>");
						$skilldownsum = 0;
						foreach(array('wp','wk','wg','wc','wd','wf') as $val){
							$down = rand(1,round(${$val}/2));
							${$val} -= $down;
							$skilldownsum += $down;
						}
						$rp -= round($skilldownsum/6);
					}elseif ($rp < 1000){
						$log = ($log . "少女瞪了你一眼，你被一种无形的压力直接压在了地上，<BR>太可怕了，还是赶快离开为妙！<BR>");
						$mhp = round($mhp/2);
						if($mhp <= 37){$mhp = 37;}
						if($hp > $mhp){$hp = $mhp;}
						$msp = round($msp/2);
						if($msp <= 37){$msp = 37;}
						if($sp > $msp){$sp = $msp;}
						$rp = $rp - 37;
					}elseif ($rp < 5000){
						death_kagari(1);
					}elseif ($rp > 5000){
						death_kagari(2);
					}else{
						$log = ($log . "少女抬头看了你一眼，随后低下头去继续她的研究。<BR>");
					}		
				}
				//echo $rp;
				$ret = 1;
			}elseif($flag == 1){
				$log .= '明白了少女已经是敌人的你，刻意躲避着少女的追踪。不过至少你不用担心被绘卷搞得七窍流血了。<BR>';
			}else{
				$log .= '少女的尸体静静地躺在雏菊盛开的山丘上，这里不再有任何危险了。<BR>但是不知为何，你丝毫没有轻松的感觉。<br>';
			}
		}elseif ($pls==34){//英灵殿
			if (($art!='Untainted Glory')&&($gamestate != 50)&&($gametype!=2)){
				$safe_plslist = \map\get_safe_plslist(0);
				if($hack || sizeof($safe_plslist) > 1) {
					do{
						if($hack) $rpls = array_randompick(\map\get_all_plsno());
						else $rpls = array_randompick($safe_plslist);
					}
					while ($rpls == 34);
					$pls=$rpls;
					$log.="殿堂的深处传来一个声音：<span class=\"evergreen b\">“你还没有进入这里的资格”。</span><br>一股未知的力量包围了你，当你反应过来的时候，发现自己正身处<span class=\"yellow b\">{$plsinfo[$pls]}</span>。<br>";
					if (CURSCRIPT !== 'botservice') $log.="<span id=\"HsUipfcGhU\"></span>";
					$ret = 1;
				}
			}
			
		}	else {
		}

		if($hp<=0 && $state < 10){
			event_death();
		}
		return $ret;
	}

	//事件致死，默认13号死法
	function event_death($dstate = 13)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$state = $dstate;
		\player\update_sdata();
		$sdata['sourceless'] = 1;
		$sdata['attackwith'] = '';
		\player\kill($sdata,$sdata);
		\player\player_save($sdata);
		\player\load_playerdata($sdata);
	}

	function death_kagari($type)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if($type == 1){
			$log .= '从少女的身上延伸出了红色的丝带，如巨蟒般将你紧紧捆住！<br>';
			if (\gameflow_duel\is_gamestate_duel()){
				event_suffer_inf('b');
				event_suffer_dmg(floor($hp * 0.99));
				$log .= '不过，在你即将被绞碎时，上空射来的奇异光束烧毁了丝带，救了你一命。<br>少女见状扭头离去了。<br>';
			}else{
				event_death(36);
			}	
		}elseif($type == 2){
			$log .= '从少女的身上延伸出了红色的丝带，径直朝着你的颈部飞来！<br>';
			if (\gameflow_duel\is_gamestate_duel()){
				event_suffer_inf('h');
				event_suffer_dmg(floor($hp * 0.99));
				$log .= '不过，在你即将身首异处时，上空射来的奇异光束烧毁了丝带，救了你一命。<br>少女见状扭头离去了。<br>';
			}else{
				event_death(37);
			}		
		}elseif($type == 3){
			$log .= '从少女的身上延伸出了红色的丝带，一边散发着热气一边朝着你高速飞来！<br>';
			if (\gameflow_duel\is_gamestate_duel()){
				event_suffer_inf('u');
				event_suffer_dmg(floor($hp * 0.99));
				$log .= '不过，在灼热的丝带即将把你烫熟时，上空射来的奇异光束烧毁了丝带，救了你一命。<br>少女见状扭头离去了。<br>';
			}else{
				event_death(38);
			}	
		}
		return;
	}
	
	//因事件致伤，如果有防具则会降低防具耐久作为代替
	//也支持因事件致异常状态
	//传参$pos为字符串
	function event_suffer_inf($pos, $hurt = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','wound','ex_dmg_att'));
		//先判定是否肢体受伤
		$dummy = \player\create_dummy_playerdata();
		$inf_flag = 0;
		for ($i=0; $i<strlen($inf_place); $i++)
		{
			$c = $inf_place[$i];
			if(false !== strpos($pos, $c)) {
				$dummy['attack_wounded_'.$c] = $hurt;
				$inf_flag = 1;
			}
		}
		if($inf_flag) \wound\apply_inf_main($dummy, $sdata, 0);
		//再判定是否属性致异常状态
		if(defined('MOD_EX_DMG_ATT')){
			foreach(array_keys($ex_inf) as $k) {
				if(false !== strpos($pos, $k) && false === strpos($inf_place, $k)) {//f既是足部又是灼焰，必须排除
					\ex_dmg_att\get_ex_inf_main($dummy, $sdata, 0, $k);
				}
			}
		}
	}

	//因事件受到伤害/回复
	//因战斗流程较为复杂，直接判定伤害，不考虑减半防御等
	//返回实际的伤害值/回复值
	function event_suffer_dmg($dmg, $show_log = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$dmg = (int)$dmg;
		$o_hp = $hp;
		if(!empty($dmg)) {
			$hp -= $dmg;
			if($o_hp < $mhp && $hp > $mhp) {
				$hp = $mhp;
				$dmg = $o_hp - $hp;
			}
			if($show_log) {
				eval(import_module('logger'));
				if($dmg > 0) {
					$log .= '<span class="red b">受到了'.$dmg.'点伤害！</span><br>';
				}else{
					$rev = -$dmg;
					$log .= '<span class="lime b">回复了'.$rev.'点生命值。</span><br>';
				}
			}
			if($hp < 0) $hp = 0;
		}
		return $dmg;
	}

	//因事件获得特定字段的值，如怒气、最大生命、最大体力等等，比较泛用。
	//返回实际的值
	//除了几个不能减到0的值之外，没有数值上下限判定，请在赋值前自行判定。
	function event_get_field($got, $field, $show_log = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$got = (int)$got;
		$field_list = Array(
			'rage' => '怒气',
			'hp' => '生命',
			'mhp' => '最大生命',
			'sp' => '体力',
			'msp' => '最大体力',
			'ss' => '歌魂',
			'mss' => '最大歌魂',
			'wp' => '殴系熟练度',
			'wk' => '斩系熟练度',
			'wc' => '投系熟练度',
			'wg' => '射系熟练度',
			'wd' => '爆系熟练度',
			'wf' => '灵系熟练度',
			'exp' => '经验值',
		);
		if(!empty($got) && !empty($field_list[$field])) {
			$o_field_num = $sdata[$field];
			$sdata[$field] += $got;
			if($sdata[$field] <= 0 && in_array($field, Array('hp','mhp','sp','msp'))){//几个不能减到0的
				$sdata[$field] = 1;
				$got = $sdata[$field] - $o_field_num;
			}
			if($show_log) {
				eval(import_module('logger'));
				if($got > 0) {
					$log .= '<span class="cyan b">获得了'.$got.'点'.$field_list[$field].'！</span><br>';
				}else{
					$loss = -$got;
					$log .= '<span class="red b">失去了'.$loss.'点'.$field_list[$field].'！</span><br>';
				}
			}
			
		}
		return $got;
	}

	//因事件获得/失去金钱
	//返回实际的增加值
	function event_get_money($got, $show_log = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$got = (int)$got;
		if(!empty($got)) {
			$money += $got;
			if($show_log) {
				eval(import_module('logger'));
				if($got > 0) {
					$log .= '<span class="cyan b">获得了'.$got.'元钱。</span><br>';
				}else{
					$loss = -$got;
					$log .= '<span class="red b">失去了'.$loss.'元钱。</span><br>';
				}
			}
			if($money < 0) $money = 0;
		}
		return $got;
	}

	//因事件增加/减少rp。没有显示
	//返回实际的增减值
	function event_get_rp($got){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$got = (int)$got;
		if(!empty($got)) {
			$rp += $got;
		}
		return $got;
	}

	//事件中各种胜率的处理，本模块直接返回
	function event_winrate_process($eid, $winrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $winrate;
	}

	//事件中各种回避率的处理，本模块直接返回
	function event_escrate_process($eid, $escrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $escrate;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if(isset($exarr['dword'])) $e0 = $exarr['dword'];
			
		if($news == 'death13') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因<span class=\"red b\">意外事故</span>死亡{$e0}</li>";
		if($news == 'death33')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因卷入特殊部队『天使』的实弹演习，被坠落的少女和机体“亲吻”而死{$e0}</li>";
		if($news == 'death35')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因为敌意过剩，被虚拟意识救♀济！{$e0}</li>";
		if($news == 'death36')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因为敌意过剩，被虚拟意识腰★斩！{$e0}</li>";
		if($news == 'death37')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因为敌意过剩，被虚拟意识断★头！{$e0}</li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	
}

?>