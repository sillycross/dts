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
		eval(import_module('sys','player','logger','map'));
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
						$log .= '借此机会，你顺利逃跑了。<br>';
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
			
			case 3://雪之镇
				if($rp <= 70){//rp较小时遇到小天使1号
					$log .= '在你正要穿过商店街拐角时，一名拿着纸袋的少女朝你撞来！<br><br>';
					//基础回避率20%，每有3级则回避率+1%.虽然这个有必要回避吗？
					$escrate = event_escrate_process(20 + $lvl/3);
					if($dice < $escrate) {
						$log .= '你敏捷地一闪，成功回避了被撞倒的厄运，而少女面朝下重重地摔在地上。<br>少女“呜咕……”地哭泣着，爬起来跑掉了。而你觉得有些过意不去。<br>';
						event_get_rp($dice2);
					}else{
						$log .= '你回避不及，被少女撞个正着！<br>你重重地摔在地上，而少女因为有你做缓冲，幸免于难。<br>';
						event_suffer_dmg($dice2);
						event_suffer_inf(array_randompick(Array('h','b','a','f')));
						$log .= '<br>少女忙不迭地向你道歉，并把她手中的鲷鱼烧塞给了你，然后飞快地跑掉了。<br>';
						$getitem = Array(
							'itm' => '鲷鱼烧',
							'itmk' => 'HB',
							'itme' => 500,
							'itms' => 2,
							'itmsk' => 'z'
						);
						event_get_item($getitem);
						event_get_rp($dice2);
					}
				}else{//rp较高时遇到小天使2号
					$log .= '在你正要穿过商店街拐角时，一个瘦高的身影朝你撞来！<br>哗，原来是一具穿着围巾和披风的大只骷髅！<br>从那热忱的眼神，开朗的笑意，便知他是骷髅中的极品了！<br><br>';
					//基础回避率20%，每有3级则回避率+1%
					$escrate = event_escrate_process(20 + $lvl/3);
					if($dice < $escrate){
						$log .= '你敏捷地一闪，成功回避了被撞倒的厄运，而骷髅面朝下重重地摔在地上。<br>看着骷髅在地上扑腾的诡异光景，你吓得转身就跑。<br>';
						event_get_rp($dice2);
					}
					else{
						$log .= '你回避不及，被骷髅撞个正着！<br>你重重地摔在地上，而骷髅因为有你做缓冲，幸免于难。<br>';
						event_suffer_dmg($dice2 * 2);
						event_suffer_inf(array_randompick(Array('h','b','a','f')));
						if($rp < 1000) {
							$log .= '<br>骷髅忙不迭地向你道歉，向你解释说他是这里的保安，并把他的名片塞给了你。<br>';
							$getitem = Array(
								'itm' => '自奏圣乐·谐谑曲骷髅 ★3',
								'itmk' => 'WC03',
								'itme' => 120,
								'itms' => 1,
								'itmsk' => 'O'
							);
							event_get_item($getitem);
							$log .= '直到骷髅离开，你依然觉得此事甚为诡异。<br>';
							event_get_rp($dice2);
						}else{
							$log .= '<br>骷髅把你拉了起来，对你进行了一通安全教育之后离开了。<br>';
						}
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
							$log .= '<br>这样的纸钞真的能用吗……？<br>';
							event_get_rp(100);
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
			case 7://清水池
				$log .= '在你东张西望时，四周突然聚集起一股寒气。<br>一名<span class="cyan b">看起来很智慧的冰精</span>在湖面上现身，并向你发出决定最强者的挑战！<br><br>';
				//基础胜率9%，每有1个多余的技能点+9%
				$winrate = event_winrate_process(9 + $skillpoint * 9);
				if($dice < $winrate) {
					$log .= '你急中生智，向对方发问：⑨+10等于多少？<br>冰精果然掰起手指开始计算，而你趁机逃离了现场。<br>';
				}else{
					$log .= '没等你回应，成片的冰柱就向你射来！你手忙脚乱地开始躲避弹幕。<br>';
					$spdown = $dice2;
					event_get_field(-$spdown, 'sp');
					if($rp > 1000) {
						event_suffer_inf('i');
					}
					$log .= '冰柱掀起的烟尘遮盖了视线，你连忙逃离了现场。<br>';
				}

				$ret = 1;
				break;
			case 15:
				$log .= '伴随着诡异的怪笑声，令人眼花缭乱的弹幕从四面八方飞来。<br>有妖怪来袭击人类了！<br><br>';
				//基础回避率60%，每有3级则回避率+1%
				$escrate = event_escrate_process(60 + $lvl/3);
				if($dice < $escrate){
					if($dice2 == 40 && $rp < 100) {
						$log .= '你躲开袭来的弹幕，定睛看去，所谓的妖怪不过是个配色像蔬菜一样的少女而已。<br>似乎是察觉到被发现了，她把一双拖鞋塞到你手中，蹦蹦跳跳地跑掉了。<br>';
						$getitem = Array(
							'itm' => '小五拖鞋',
							'itmk' => 'DF',
							'itme' => 5,
							'itms' => 10,
							'itmsk' => ''
						);
						event_get_item($getitem);
						$log .= '<br>妖怪的想法真难猜啊。<br>你决定还是不要操心了。<br>';
						event_get_rp($dice2);
					}elseif($dice2 > 30 && $rp < 100) {
						$log .= '你躲开袭来的弹幕，定睛看去，所谓的妖怪不过是个长着狐狸尾巴的少女而已。<br>对方见弹幕都被躲过，丢下用于攻击的道具就离开了。<br>';
						$getitem = Array(
							'itm' => '试管',
							'itmk' => 'WC',
							'itme' => 1,
							'itms' => 1,
							'itmsk' => '^res_<:comp_itmsk:>{空瓶,X,1,1,,}1^reptype2^rtype4'
						);
						event_get_item($getitem);
						$log .= '<br>你觉得刚才的战斗有些意犹未尽。<br>';
						event_get_rp($dice2);
					}else{
						$log .= '你躲开袭来的弹幕，定睛看去，所谓的妖怪不过是个举着紫色怪伞的少女而已。<br>趁她不知所措，你抢先一步上去摸了摸少女的头，把她吓得夺路而逃。<br>手感挺好的，真是可惜啊。<br>';
						//event_get_rp($dice2);
					}
				}else{
					if(rand(0,1)) {
						$log .= '被妖怪撒出的弹幕吓着了！<br>你慌不择路，一头撞到了林立的御柱上！<br>';
						event_suffer_inf('h');
					}else{
						$log .= '妖怪撒出了密集的爱心……不，是密集的弹幕！<br>';
						event_suffer_dmg($dice2);
					}
				}
				$ret = 1;
				break;

			case 16://常磐森林
				$log .= '野生的黄色老鼠从草丛中钻了出来！<br>你需要一只帕鲁才能对战，但你并没有一只帕鲁。<br><br>';
				//基础回避率60%，每有3级则回避率+1%
				$escrate = event_escrate_process(60 + $lvl/3);
				if($dice < $escrate){
					$log .= '不过你还是成功地逃跑了。<br>';
				}else{
					if(rand(0,1)) {
						$log .= '黄色老鼠使用了电击！<br>';
						if($rp > 1000) {
							event_suffer_inf(array_randompick(Array('h','b','a','f')).'e');
						}else{
							event_suffer_inf(array_randompick(Array('h','b','a','f')));
						}
					}else{
						$log .= '黄色老鼠使用了电光石火！<br>';
						event_suffer_dmg($dice2);
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

				$log .= '<br>';
				if(1 == $flag){
					$log .= '端坐在雏菊之丘的少女现在拥有敌意。<br>明白这一点的你，刻意躲避着少女的追踪——现在不用担心被绘卷搞得七窍流血了。<br>';
				}elseif(2 == $flag){
					$log .= '雏菊盛开的山丘上，少女的尸体静静地躺着，犹如睡着了一般——这里再也没有任何危险了。<br>但不知为何，你丝毫没有轻松的感觉。<br>';
				}else{
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

			case 34://英灵殿
				if (!check_pls34_enterable()) {
					$safe_plslist = \map\get_safe_plslist(0);
					if($hack || sizeof($safe_plslist) > 1) {
						do{
							if($hack) $rpls = array_randompick(\map\get_all_plsno());
							else $rpls = array_randompick($safe_plslist);
						}
						while ($rpls == 34);
						$pls=$rpls;
						$log .= "殿堂的深处传来一个声音：<span class=\"evergreen b\">“你还没有进入这里的资格”。</span><br>一股未知的力量包围了你，当你反应过来的时候，发现自己正身处<span class=\"yellow b\">{$plsinfo[$pls]}</span>。<br>";
						
						$ret = 1;
					}
				}
				break;

			default:
				//$log .= '错误的事件参数。<br>';
				break;
		}

		if(!empty($ret)) {
			$log .= '<br>';
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

	//因事件获得道具
	//传参为标准的theitem数组
	function event_get_item(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!empty($theitem['itms'])) {
			eval(import_module('player'));
			$itm0 = $theitem['itm'];
			$itmk0 = $theitem['itmk'];
			$itme0 = $theitem['itme'];
			$itms0 = $theitem['itms'];
			$itmsk0 = $theitem['itmsk'];
			
			\itemmain\itemget();
		}
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
	//其他模块如果需要判定是哪个事件，可以设置一个本地变量来判定
	function event_winrate_process($winrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $winrate;
	}

	//事件中各种回避率的处理，本模块直接返回
	function event_escrate_process($escrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $escrate;
	}

	//英灵殿进入性检查
	//不可进入返回0，可进入返回1
	function check_pls34_enterable()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = 1;
		eval(import_module('player'));
		if('Untainted Glory' != $art && !\gameflow_duel\is_gamestate_duel()){
			$ret = 0;
		}
		return $ret;
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