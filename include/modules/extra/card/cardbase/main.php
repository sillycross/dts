<?php

namespace cardbase
{
	$card_config_file = GAME_ROOT.'/include/modules/extra/card/cardbase/config/card.config.php';
	$card_main_file = GAME_ROOT.'/include/modules/extra/card/cardbase/main.php';
		
	function init() {}
	
	//============3202.10.15 改写卡片系统的数据库储存==========
	//旧思路：卡片和能量都以类似csv的形式（用下划线分隔）作为字符串储存，好处是易读易改，坏处是能量非常容易跟卡片错位，而且能量是带小数的，空间浪费特别厉害
	//新思路：$card_data是一个数组（储存进数据库用gencode），以卡片id为键名，键值为子数组，储存卡片id（备用）、能量和其他一些用来扩展的数据（比如未来可以做卡片罕贵）。好处是扩展性强，坏处是修改起来比原来麻烦
	
	//新版的初始化函数，从传入的$udata返回$cardlist、$cardenergy、$card_data三个重要变量
	//$cardlist和$cardenergy的格式仍维持原样用于兼容。其他数据请直接访问$card_data
	//实际编写完成之后发现$card_data变量基本没有大用（因为其他功能没专门储存这个变量），主要还是靠修改$udata实现卡片的修改
	//返回值请用list接收
	function get_cardlist_energy_from_udata($udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		//调用global.func.php下的gdecode()函数获得$card_data的内容
		$card_data = get_decoded_card_data($udata);
		
		//兼容性代码：如果数据库缺乏字段，自动新建字段。
		//在旧服数据稳定之后可以删除这一句
		if(0){
			$column_existed = 0;
			eval(import_module('sys'));
			$result = $db->query("SHOW COLUMNS FROM {$gtablepre}users");
			while($r = $db->fetch_array($result)){
				if($r['Field'] == 'card_data') {
					$column_existed = 1;
					break;
				}
			}
			if(!$column_existed) {
				$db->query("ALTER TABLE {$gtablepre}users ADD COLUMN `card_data` text NOT NULL DEFAULT '' AFTER `icon`");
			}
		}
		
		//兼容性代码：如果只有旧版数据，读取旧版数据。save时会保存到新版格式，旧版就被封存了
		if(empty($card_data) && !empty($udata['cardlist'])) 
		{
			eval(import_module('sys','cardbase'));
			$cardlist = get_user_cards_process($udata);
			$cardenergy = Array();
			$t = explode('_',$udata['cardenergy']);
			for ($i=0; $i<count($cardlist); $i++)
				if ($i<count($t))
				{
					$cardenergy[$cardlist[$i]]=(double)$t[$i];
				}
				else
				{
					$cardenergy[$cardlist[$i]] = $cards[$cardlist[$i]]['energy'];
				}
		}
		//有新版数据的情况下在这里处理
		else
		{
			list($cardlist, $cardenergy) = get_cardlist_energy_from_card_data($card_data);
		}
		
		return Array($cardlist, $cardenergy, $card_data);
	}
	
	//上面的的核心函数，从$card_data获取$cardlist和$cardenergy
	function get_cardlist_energy_from_card_data($card_data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$cardlist = $cardenergy = Array();
		
		foreach($card_data as $cid => $cv) {
			//由于可能存在非数字id的键名，以键值的id为准
			if(isset($cv['cardid'])) {
				$cardlist[] = $cv['cardid'];
				$cardenergy[$cid] = $cv['cardenergy'];//相当于自动对齐$cardenergy
			}
		}
		
		return Array($cardlist, $cardenergy, $card_data);
	}
	
	//新版的储存函数，把$cardlist、$cardenergy和$card_data保存到$udata中并编码，新版实际上只修改$card_data
	//返回修改后的$udata。因为是引用，$card_data、$udata也会被同时更新
	//注意这个函数不会储存数据库，请手动update
	function put_cardlist_energy_to_udata($cardlist, $cardenergy, &$card_data, &$udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$card_data = put_cardlist_energy_to_card_data($cardlist, $cardenergy, $card_data);
		put_encoded_card_data($udata, $card_data);
		return $udata;
	}
	
	//上面的的核心函数，把$cardlist和$cardenergy保存到$card_data
	//会把$cardlist新增的卡片加入$card_data，然后根据$cardenergy更新$card_data对应值
	//$cardlist和$cardenergy没给的卡不会动，因此可以用来对单卡进行更新
	//如果$allow_delete=1则会把$cardlist里不存在的卡片删掉
	//返回修改过的$card_data。因为是引用，$card_data也会被同时更新
	function put_cardlist_energy_to_card_data($cardlist, $cardenergy, &$card_data, $allow_delete = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		
		$card_data_keys = array_keys($card_data);
		$add_cards = array_diff($cardlist, $card_data_keys);
		$del_cards = array_diff($card_data_keys, $cardlist);
		
		if(!in_array(0, $card_data_keys)) $add_cards[] = 0;//必定拥有0号卡挑战者
		
		//有新增卡片的情况。由于增加了碎闪的判定，获得单项新卡并不是在这里进行了，这里只是一个保底
		if(!empty($add_cards)) {
			foreach($add_cards as $cid) {
				$card_data[$cid] = init_card_data_single($cid);
				$cardenergy[$cid] = $cards[$cid]['energy'];
			}
		}
		
		//根据$cardenergy更新$card_data对应值
		foreach($cardenergy as $cid => $cen) {
			if(isset($card_data[$cid]) && $card_data[$cid]['cardenergy'] != $cen){
				$card_data[$cid]['cardenergy'] = $cen;
			}
		}
		
		//如果$allow_delete不为0，则会判定是否删除卡片
		//会忽略非数字的键名
		if(!empty($allow_delete) && !empty($del_cards)) {
			foreach($del_cards as $cid) {
				if(is_numeric($cid)) {
					unset($card_data[$cid]);
				}
			}
		}
		
		ksort($card_data);//升序排列
		
		return $card_data;
	}
	
	//创建并返回新卡数组。需要用模块添加的新值可以继承这个函数
	function init_card_data_single($cardid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return Array(
			'cardid' => $cardid,
			'cardenergy' => 0, //新增卡片能量都是0，新卡充能在put_cardlist_energy_to_card_data()里实现
		);
	}
	
	//从传入的$udata返回解码后的$card_data
	//不会更改传参$udata的内容
	function get_decoded_card_data($udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = Array();
		if(!empty($udata['card_data'])) $ret = gdecode($udata['card_data'], 1);
		if(!is_array($ret)) $ret = Array();
		return $ret;
	}
	
	//把传入的$card_data_arr编码后覆盖到$udata
	//会更改传参$udata的内容，同时也会把更改过的$udata作为结果返回
	//返回值不是引用的，应该没有人在$udata里放奇怪的东西吧？
	function put_encoded_card_data(&$udata, $card_data_arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;

		if(is_array($card_data_arr)) //传参$card_data_arr是数组才会执行。但不会检查数组合法性
			$udata['card_data'] = gencode($card_data_arr);
		
		return $udata;
	}
	
	//更新卡片能量
	//返回更新过的$cardenergy数组，注意不会直接修改$card_data
	function card_energy_update($cardlist, $cardenergy, $udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		$energy_recover_rate = get_energy_recover_rate($cardlist, $udata['gold']);
		$lastupd = $udata['cardenergylastupd'];
		
		foreach ($cardenergy as $cid => &$cen) {
			$rare = $cards[$cid]['rare'];
			$energy = $cards[$cid]['energy'];
			if(in_array($rare, array('C','M'))) {//C、M卡直接设为上限
				$cen = $energy;
			}else{//其他罕贵的卡才计算能量
				$cen = (double)$cen + ($now-$lastupd) * $energy_recover_rate[$rare];
				if($cen > $energy - 1e-5) $cen = $energy;//双精度误差控制
			}
		}
		
		return $cardenergy;
	}
	
	//============以下是旧的卡片存取函数，旧服将只在兼容模式使用，新服将弃用==========
	
	function cardlist_decode($str){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(strpos(debug_backtrace()[2]['function'],'get_cardlist_energy_from_udata')===false)
			gexit('trying to use function cardlist_decode from '.debug_backtrace()[2]['function']);
		if(!is_array($str)) $ret = explode('_',$str);
		if(empty($ret)) $ret[] = '0';
		return $ret;
	}
	
	function cardlist_encode($arr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		gexit('trying to use function cardlist_encode');
		$ret = implode('_',$arr);
		return $ret;
	}
	
	//============以上是旧的卡片存取函数，旧服将只在兼容模式使用，新服将弃用==========
	
	//从用户名获得玩家卡片数组，这个函数似乎已经被架空了
	function get_user_cards($username){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		gexit('trying to use function get_user_cards');
		$udata = fetch_udata_by_username($username);
		$cardlist = get_user_cards_process($udata);
		return $cardlist;
	}	
	
	//从$udata获得玩家卡片数组。即将弃用
	function get_user_cards_process($udata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(strpos(debug_backtrace()[1]['function'],'get_cardlist_energy_from_udata')===false)
			gexit('trying to use function get_user_cards_process from '.debug_backtrace()[1]['function']);
		$cardlist = cardlist_decode($udata['cardlist']);
		return $cardlist;
	}
	
	//根据$cardlist和$qiegao的值计算卡片能量恢复效率
	function get_energy_recover_rate($cardlist, $qiegao)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		/*
		 * 返回 Array ('S'=>..,'A'=>..,'B'=>..,'C'=>0)
		 */
		/*
		 * 新规：S卡CD时间为2小时-1天
		 * A卡CD时间大约在1-12小时
		 * B卡CD时间大约为0.5-6小时
		 */
		$ret = Array();
		
		$cnt=Array();
		$cnt['S']=$cnt['A']=$cnt['B']=0;
		//计算玩家拥有的能抽到的S卡、A卡、B卡的总数目
		//$cardlist仅包含玩家拥有卡片的编号
		foreach ($cardlist as $key)
		{
			$vrare = $cards[$key]['rare'];
			if('S' == $vrare || 'A' == $vrare || 'B' == $vrare) {
				if(in_array($key, $cardindex[$vrare]))//只计算能抽到的卡，不计算奖励卡
					$cnt[$vrare]++;
			}
		}
		
		//计算所有能抽到的S、A、B卡的总数目
		$ttl['S'] = sizeof($cardindex['S']);
		$ttl['A'] = sizeof($cardindex['A']);
		$ttl['B'] = sizeof($cardindex['B']);
		
		$tbase = $card_recrate_base;//基础值在card.config.php定义
		
		foreach (Array('S','A','B') as $ty)
		{
			//如果玩家只有1张该类别的卡那么CD膨胀系数是1；如果抽满了该类别的卡那么CD膨胀系数是12；
			//file_put_contents('a.txt', $cnt[$ty].' '.$ttl[$ty]);
			$fct = max(0, min(1, $cnt[$ty] / $ttl[$ty])); //计算玩家拥有卡片占该类可抽到卡片的比例，最小0最大1
			$rate = pow($fct,2) * 11 + 1;//通过二次函数计算膨胀系数，最小1最大12
			
			$ret[$ty]=100.0/($tbase[$ty]*$rate);
		}
		$ret['C']=$ret['M']=0;//C、M卡不需要CD
		
		return $ret;
	}
		
	//获得玩家的卡片数据，会自动更新卡片冷却信息
	function get_user_cardinfo($who)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		if(is_array($who) && isset($who['username'])) {//减少一次数据库操作
			$udata = $who;
			$who = $udata['username'];
		}else{
			$udata = fetch_udata_by_username($who);
		}
		
		//这是获得$cardlist唯一的入口，把这里修改成新的
		list($cardlist, $cardenergy, $card_data) = get_cardlist_energy_from_udata($udata);
		
		//更新卡片能量
		$o_cardenergy = $cardenergy;
		$cardenergy = card_energy_update($cardlist, $cardenergy, $udata);
		if($o_cardenergy != $cardenergy) {//如果能量有修改，直接保存一次数据库。这里也会同时修改传入的$card_data和$udata
			save_cardenergy_to_db($cardlist, $cardenergy, $card_data, $udata);
		}
		
		//以下是老代码
		
//		$cardlist = get_user_cards_process($udata);		//仅包含玩家拥有卡片的编号
//		$energy_recover_rate = get_energy_recover_rate($cardlist, $udata['gold']);
//		
//		$cardenergy=Array();
//		if ($udata['cardenergy']=="") $t=Array(); else $t=explode('_',$udata['cardenergy']);
//		$lastupd = $udata['cardenergylastupd'];
//		
//		for ($i=0; $i<count($cardlist); $i++)
//			if ($i<count($t))
//			{
//				$cardenergy[$cardlist[$i]]=((double)$t[$i])+($now-$lastupd)*$energy_recover_rate[$cards[$cardlist[$i]]['rare']];
//				if (in_array($cards[$cardlist[$i]]['rare'], array('C','M')) || $cardenergy[$cardlist[$i]] > $cards[$cardlist[$i]]['energy']-1e-5)
//					$cardenergy[$cardlist[$i]] = $cards[$cardlist[$i]]['energy'];
//			}
//			else
//			{
//				$cardenergy[$cardlist[$i]] = $cards[$cardlist[$i]]['energy'];
//			}
		
		$ret=Array(
			'cardlist' => $cardlist,
			'cardenergy' => $cardenergy,
			'cardchosen' => $udata['card'],
			'cardenergylastupd' => $now,
			'card_data' => $card_data,
		);
		
//		if($t != $cardenergy) {
//			save_cardenergy($ret, $who);
//		}
			
		return $ret;
	}
	
	//单把卡片能量更新到数据库，因为有多处调用所以单独做一个函数
	//返回$card_data
	function save_cardenergy_to_db($cardlist, $cardenergy, &$card_data, &$udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		put_cardlist_energy_to_udata($cardlist, $cardenergy, $card_data, $udata);
		$upd=Array(
			'card_data' => $udata['card_data'],
			'cardenergylastupd' => $now,
		);
		update_udata_by_username($upd, $udata['username']);
		return $card_data;
	}
	
	//更新卡片能量数据库，会自动将能量值转化为浮点数
	//这个函数已废弃
	function save_cardenergy($data, $who)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		gexit('trying to use function save_cardenergy');
		eval(import_module('sys'));
		if(is_array($data['cardenergy'])) {
			$cd_n='';
			for ($i=0; $i<count($data['cardlist']); $i++)
			{
				$x=(double)$data['cardenergy'][$data['cardlist'][$i]];
				if ($i>0) $cd_n.='_';
				$cd_n.=$x;
			}
		}else{
			$cd_n = $data['cardenergy'];
		}
		$upd=Array(
			'cardenergy' => $cd_n,
			'cardenergylastupd' => $data['cardenergylastupd'],
		);
		update_udata_by_username($upd, $who);
	}
	
	//生成一条获得卡片的站内信
	//$ci为卡号，$ext是额外显示的文本，$blink代表碎闪等级
	//会自动判定是不是新卡，返回值为1则表示是新卡
	function get_card_message($ci,$ext='',$blink=0,&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','cardbase'));
		if ($pa==NULL){
			$n=$name;
		}else{
			if (isset($pa['username'])) $n=$pa['username'];
			else $n=$pa['name'];
		}
		//判定卡片是不是新卡，当场显示用
		$result = fetch_udata_by_username($n);
		if(empty($result)) return;
		
		//if(!empty($ext)) $ext.='<br>';
		$prizecode = 'getcard_'.$ci.';';
		if(!empty($blink)) $prizecode .= 'getcardblink_'.$blink.';';
		include_once './include/messages.func.php';
		message_create(
			$n,
			'获得卡片',
			$ext.'查收本消息即可获取此卡片，如果已有此卡片则会转化为切糕。',
			$prizecode
		);
		
		$ret = 0;
		$clist = get_cardlist_energy_from_udata($result)[0];
		if (!in_array($ci,$clist)) $ret = 1;
		return $ret;
	}
	
	//某些特殊模式的限定用卡，主要是enter_battlefield()过程调用
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//标准模式禁用卡片
		if(0==$gametype){
			$card = 0;
		}
		return $card;
	}
	
	//效果是随机发动一张卡的卡片
	function cardchange($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		if(empty($cards[$card]['valid']['cardchange'])) return $card;
		$cs = $cards[$card]['valid']['cardchange'];
		//判定卡片概率，注意卡片设定里的几个概率是单项概率
		$S_odds = !empty($cs['S_odds']) ? $cs['S_odds'] : 0;
		$A_odds = !empty($cs['A_odds']) ? $cs['A_odds'] : 0;
		$B_odds = !empty($cs['B_odds']) ? $cs['B_odds'] : 0;
		$C_odds = !empty($cs['C_odds']) ? $cs['C_odds'] : 0;//实际上不顶用，SAB都没选到就一定是C
		$packlimit = !empty($cs['packlimit']) ? $cs['packlimit'] : '';
		$forced = !empty($cs['forced']) ? $cs['forced'] : Array();
		$ignore = !empty($cs['ignore_cards']) ? $cs['ignore_cards'] : Array();
		
		//实际随机卡片
		$arr=array(0);
		do{
			if(!empty($cs['real_random'])) {//真随机，把所有卡集合并
				$arr = array_merge($arr,$cardindex['S'],$cardindex['A'],$cardindex['B'],$cardindex['C'],$cardindex['EB']);
			}else{
				//判定随机到的卡的罕贵
				$r=rand(1,100);
				if ($r<=$S_odds){
					$arr=$cardindex['S'];
					if(!empty($cs['allow_EB'])) $arr=array_merge($arr, $cardindex['EB_S']);
				}elseif($r - $S_odds <= $A_odds){
					$arr=$cardindex['A'];
					if(!empty($cs['allow_EB'])) $arr=array_merge($arr, $cardindex['EB_A']);
				}elseif($r - $S_odds - $A_odds <= $B_odds){
					$arr=$cardindex['B'];
					if(!empty($cs['allow_EB'])) $arr=array_merge($arr, $cardindex['EB_B']);
				}else{
					$arr=$cardindex['C'];
					if(!empty($cs['allow_EB'])) $arr=array_merge($arr, $cardindex['EB_C']);
				}
			}
			
			if(!empty($packlimit)){//有卡包限制，那么对选择集挨个判定一遍卡包
				foreach($arr as $i => $v) {
					if($cards[$v]['pack'] != $packlimit) 
						unset($arr[$i]);
				}
				$arr = array_filter($arr);
			}
			
			$arr = array_merge($arr, $forced);
			$arr = array_unique($arr);
			$ret = array_randompick($arr);
		}while($ret == $card || in_array($ret, $ignore));//必定选不到自己
		return $ret;
	}
	
	//进入游戏时根据所用卡片对玩家数据的操作
	//输入$ebp即valid.func.php里初始化的玩家数组，$card是所用卡的编号
	//返回Array($eb_pdata, $skills, $prefix)其中$skills是需要载入的技能列表，$prefix是消息中的玩家名前缀
	function enter_battlefield_cardproc($ebp, $card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		
		//先判定是否随机发动一张卡
		if(!empty($cards[$card]['valid']['cardchange'])){
			if(empty($cards[$card]['valid']['cardchange']['perm_change'])) $ebp['o_card'] = $card;//只要不是永久变化，就记录原本的卡
			$card = cardchange($card);
			//有些问题，先这样
			
			if(empty($cards[$card])) {
				$card = 0;				
			}
		}
		
		//获取卡片的具体设定
		$card_valid_info = $cards[$card]['valid'];
	
		$cardname = $newscardname = $cards[$card]['name'];
		$cardrare = $newscardrare = $cards[$card]['rare'];
		
		if(!empty($cards[$card]['title'])) $cardname = $cards[$card]['title'];//有定义了title的卡，用title来显示
		
		//如果卡片本身有换卡效果（随机卡等），在这里把消息用的卡名换回来
		if(!empty($ebp['o_card'])) {
			$newscardname=$cards[$ebp['o_card']]['name'];
			$newscardrare=$cards[$ebp['o_card']]['rare'];
		}
		//生成玩家名前缀
		$prefix = '<span class="'.$card_rarecolor[$newscardrare].'">'.$newscardname.'</span> ';
		
		///////////////////////////////////////////////////////////////
		//实际卡片效果的载入
		//////////////////////////////////////////////////////////////
		$skills = Array();
		foreach ($card_valid_info as $key => $value){
			if('skills' == $key) {
				//$skills = $value;
				//考虑到跟rand_skill的顺序不确定，应该逐个赋值。也不能用array_merge()，至于理由，你可以试一试（
				foreach($value as $sk => $sv){
					$skills[$sk] = $sv;
				}
				continue;//技能另外判定，不直接写入，也不把skills写进$ebp
			}elseif('rand_skills' == $key){//随机技能，rand_skills下每一个数组代表一组随机，rnum键名的元素代表选取数目
				foreach($value as $rk => $rv){
					$rnum = 1;
					if(!empty($rv['rnum'])) $rnum = $rv['rnum'];
					unset($rv['rnum']);
					$rkeys = array_keys($rv);
					shuffle($rkeys);
					for($i = 1;$i <= $rnum;$i++){
						$skills[$rkeys[$i]] = $rv[$rkeys[$i]];
					}
				}
				continue;
			}
			if (in_array(substr($key,0,3), Array('wep','arb','arh','ara','arf','art','itm'))){//道具类的，如果是数组则随机选一个
				if(is_array($value)){
					$value = array_randompick($value);
				}
			}
			$value = enter_battlefield_cardproc_valueproc($key, $value);//单项数据的处理
			//如果是数值类的字段，先判定是增加减少还是赋值。没有前缀的当做赋值
			$val1 = substr($value, 0, 1);
			$val2 = substr($value, 1);
			if(in_array($val1, Array('+','-','=')) && is_numeric($val2)){
				if('+' == $val1) $ebp[$key] += $val2;
				elseif('-' == $val1) $ebp[$key] -= $val2;
				else $ebp[$key] = $val2;
			}else{
				$ebp[$key] = $value;
			}
		}
		
		$ebp['card'] = $card;
		$ebp['cardname'] = $cardname;
		
		return Array($ebp, $skills, $prefix);
	}
	
	//入场卡片生效时，单项数据的处理。本模块是空的
	//传参$key为为卡片config里记录的键名，$value为键值
	function enter_battlefield_cardproc_valueproc($key, $value){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $value;
	}
	
	//判断一张卡当前是否在持有列表中
	//gtype5这个部分应该拆出去
	function check_card_in_ownlist($card, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(0===(int)$card || in_array($card,$card_ownlist) || (5==$gametype && in_array($card,array(182, 183, 184, 185)))) return true;
		return false;
	}
	
	//玩家获得卡片的判定
	//传参：$ci为卡片编号，$pa为玩家数组（会用来做用户名判定），$ignore_qiegao为真则会忽略切糕的判定
	//会自动修改数据库
	//这个函数好像已经被架空了，彻底弃用吧
	function get_card($ci,&$pa=NULL,$ignore_qiegao=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		gexit('trying to use function get_card');
		eval(import_module('sys','player','cardbase'));
		if ($pa==NULL){
			$n=$name;
		}else{
			if (isset($pa['username'])) $n=$pa['username'];
			else $n=$pa['name'];
		}
		$pu = fetch_udata_by_username($n);
		$ret = get_card_process($ci,$pu,$ignore_qiegao);
		
		//3202.10.15这里改成用新模式，先调整$card_data然后保存
		$card_data = put_cardlist_energy_to_udata($pi['cardlist'], $pu['cardenergy'], $pu['card_data'], $pu);
		
		$upd = array(
			'card_data' => $card_data,
			'gold' => $pu['gold'],
		);
		update_udata_by_username($upd, $n);
		return $ret;
	}
	
	//获得卡片和切糕的核心判定，如果卡重复，则换算成切糕
	//会自动判定输入的cardlist键值是字符串还是数组
	//这里是获得卡片的唯一入口
	//目前已废弃
	function get_card_process($ci,&$pa,$ignore_qiegao=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		gexit('trying to use function get_card_process from '.debug_backtrace()[2]['function']);
		if(!is_array($pa['cardlist'])) {
			$cl_changed = 1;
			$pa['cardlist'] = explode('_',$pa['cardlist']);
		}
		eval(import_module('sys','player','cardbase'));
		if (in_array($ci,$pa['cardlist'])){
			if(!$ignore_qiegao) $pa['gold'] += $card_price[$cards[$ci]['rare']];
			$ret = 0;
		}else{
			$pa['cardlist'][] = $ci;
			$ret = 1;
		}
		if(!empty($cl_changed)) $pa['cardlist'] = implode('_',$pa['cardlist']);
		
		return $ret;
	}
	
	//获得卡时判定闪烁等级（也就是类似游戏王镜碎啦闪卡啦这种东西）
	//目前有两种等级$blink=20代表镜碎，$blink=10代表闪，概率对应config里$card_blink_rate_20和$card_blink_rate_10两个
	//M卡目前不能获得任何闪烁等级，C卡不能获得镜碎等级
	function get_card_calc_blink($cardid, &$udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$rare = $cards[$cardid]['rare'];
		$r = rand(0,99);
		if(!empty($card_blink_rate_20[$rare]) && $r < $card_blink_rate_20[$rare]) return 20;
		if(!empty($card_blink_rate_10[$rare]) && $r < $card_blink_rate_10[$rare]) return 10;
		return 0;
	}
	
	//新模式的获得卡片判定。如果卡片重复则换算成切糕
	//会自动更新$udata里$card_data的值，但不会写数据库
	//返回一个数组array($isnew, $qiegao)，$isnew为真则表示获得卡片，$qiegao则代表转化成的切糕数
	function get_card_alternative($cardid, &$udata, $ignore_qiegao=0, $blink=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($cardlist, $cardenergy, $card_data) = get_cardlist_energy_from_udata($udata);
		if(in_array($cardid, $cardlist))//卡存在，原卡换算成切糕，如果闪碎等级高则会刷新闪碎等级
		{
			$getqiegao = 0;
			$o_blink = !empty($card_data[$cardid]['blink']) ? $card_data[$cardid]['blink'] : 0;//把原卡按闪碎等级和罕贵换算成切糕
			if(!$ignore_qiegao) {
				//按原卡和新卡里闪碎等级较小的那个判定切糕
				$getqiegao = get_card_transfer_to_qiegao($cardid, $udata, min($o_blink, $blink));
				$udata['gold'] += $getqiegao;
				//如果新卡闪碎等级高于原卡，那么更新原卡的闪碎等级。只有$ignore_qiegao=0才会生效
				if($blink > $o_blink) {
					$card_data[$cardid]['blink'] = $blink;
					put_cardlist_energy_to_udata($cardlist, $cardenergy, $card_data, $udata);
				}
			}
				
			$ret = Array(false, $getqiegao);
		}
		else//卡不存在，但是由于可能有闪碎等级，不能直接交给put_cardlist_energy_to_udata()
		{
			eval(import_module('cardbase'));
			$cardlist[] = $cardid;
			$card_data[$cardid] = init_card_data_single($cardid);
			$cardenergy[$cardid] = $cards[$cardid]['energy'];
			if(!empty($blink)) {
				$card_data[$cardid]['blink'] = $blink;
			}
			//其实这里可以直接用put_encoded_card_data()，但保留原来的逻辑吧
			put_cardlist_energy_to_udata($cardlist, $cardenergy, $card_data, $udata);
			$ret = Array(true, 0);
		}
		return $ret;
	}
	
	//计算卡片转换成切糕的数值（不会直接修改$udata）
	function get_card_transfer_to_qiegao($cardid, &$udata, $blink=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$ret = $card_price[$cards[$cardid]['rare']];
		if(!empty($blink) && !empty($card_price_blink_rate[$blink])) {
			$ret *= $card_price_blink_rate[$blink];
		}
		return $ret;
	}
	
	function get_qiegao($num,&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($pa==NULL){
			$n=$name;
		}else{
			if (isset($pa['username'])) $n=$pa['username'];
			else $n=$pa['name'];
		}
		$result = fetch_udata_by_username($n,'gold');
		$cg = $result['gold'];
		$cg=$cg+$num;
		if ($cg<0) $cg=0;
		if($pa) $pa['gold'] = $cg;
		update_udata_by_username(array('gold' => $cg), $n);
	}
	
	function calc_qiegao_drop(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase','sys','logger','map'));
		$qiegaogain=0;
		if (!in_array($gametype,$qiegao_ignore_mode)){		
			if ($pd['type']==90)	//杂兵
			{
				if ($areanum/$areaadd<1)	//0禁
				{
					$dice = rand(0,99);
					if ($dice<5) 
						$qiegaogain=rand(7,15);
					else if ($dice<20)
						$qiegaogain=rand(3,7);
					else if ($dice<50)
						$qiegaogain=rand(1,3);
				}
				else if ($areanum/$areaadd<2)	//1禁
				{
					$dice = rand(0,99);
					if ($dice<5) 
						$qiegaogain=rand(3,5);
					else if ($dice<15)
						$qiegaogain=rand(1,3);
				}
			}
			if ($pd['type']==2)	//幻象
			{
				if ($areanum/$areaadd<1)
				{
					$qiegaogain=rand(9,19);
				}
				else if ($areanum/$areaadd<2)
				{
					$dice=rand(0,99);
					if ($dice<30)
						$qiegaogain=rand(3,7);
					else  $qiegaogain=rand(1,3);
				}
			}
		}
		return $qiegaogain;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		battle_get_qiegao($pa,$pd,$active);
	}	
	
	function battle_get_qiegao(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$qiegaogain=calc_qiegao_drop($pa,$pd,$active);
		if ($qiegaogain>0){
			battle_get_qiegao_update($qiegaogain,$pa);
			$log.="<span class=\"orange\">敌人掉落了{$qiegaogain}单位的切糕！</span><br>";
		}
		return $qiegaogain;
	}
	
	function battle_get_qiegao_update($qiegaogain,&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		get_qiegao($qiegaogain,$pa);
	}
	
	function get_card_pack($card_pack_name) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$card_pack = Array();
		foreach ($cards as $ci => $card) {
			if ($card["pack"] == $card_pack_name)
				$card_pack[$ci] = $card;
		}
		//return  json_encode($card_pack, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."test";
		return $card_pack;
	}

	function get_card_pack_list() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		return $packlist;
	}

	function in_card_pack($packname) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		return in_array($packname, $packlist);
	}
	
	function kuji($type, &$pa, $is_dryrun = false){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$ktype=(int)$type;
		if (defined('MOD_KUJIBASE')) {
			$kr=\kujibase\kujidraw($ktype, $pa, $is_dryrun);
			if (!is_array($kr)){
				if (empty($kr) || $kr==-1){
					return $kr;
				}else{
					$dr=array($kr);
				}
			}else{
				$dr=$kr;
			}
			return $dr;
		}
		return -1;
	}
	
	//卡片按罕贵从罕到平排序
	function card_sort($cards){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = array();
		$typeweight = array('S'=> 1000000, 'A' => 100000, 'B' => 10000, 'C'=> 1000, 'M'=>0);
		foreach($cards as $ci => $cv){
			$cv['id'] = $ci;
			$weight = $typeweight[$cv['rare']] - $ci;
			$ret[$weight] = $cv;
		}
		krsort($ret);
		return $ret;
	}
	
	//卡包过滤器核心代码，过滤没开放的卡包
	function check_pack_availble($pn, $ignore_kuji = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		if(in_array($pn, Array('hidden', 'stealth'))) return false;
		if(!empty($packstart[$pn]) && $packstart[$pn] > $now) return false;
		if($ignore_kuji && in_array($pn,$pack_ignore_kuji)) return false;
		return true;
	}
	
	//卡包过滤器，过滤没开放的卡包。
	function pack_filter($packlist, $ignore_kuji = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$n_packlist = array();
		foreach($packlist as $pv){
			if(check_pack_availble($pv, $ignore_kuji)) $n_packlist[]=$pv;
		}
		return $n_packlist;
	}
	
	//卡名显示
	function parse_interface_profile()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys','player','cardbase'));
		
		list($show_cardid, $uip['cardname_show'], $show_rare, $show_cardblink, $uip['cardinfo_show']) = parse_card_show_data($sdata);
		
		//如果卡片是挑战者或者是隐藏卡片，不予显示卡面
		if(!$show_cardid || !empty($cards[$show_cardid]['hidden_cardframe'])) {
			unset($uip['cardinfo_show']);
		}
		//用于显示用的罕贵类型
		$uip['cardrare_show'] = $card_rarecolor[$show_rare];
		
		//碎闪等级的显示
		if(!empty($uip['cardinfo_show']) && $show_cardblink) $uip['cardinfo_show']['blink'] = $show_cardblink;

		$uip['card_rarecolor'] = $card_rarecolor;//备用
	}
	
	//从player表数据获得卡名和卡面显示的数据
	//返回一个数组，元素分别是显示的卡片id、显示的卡名、显示的罕贵（字母）、显示的碎闪等级和用于card_frame的$nowcard数据
	function parse_card_show_data($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		//显示的卡名，通常情况下是$cardname
		$show_cardname = !empty($pdata['cardname']) ? $pdata['cardname'] : $cards[$pdata['card']]['name'];
		//用显示卡名反查显示卡的id
		$show_cardid = check_realcard($pdata['card'], $show_cardname);
		//获得罕贵等级
		$show_rare = $cards[$show_cardid]['rare'];
		//获得$nowcard数据
		$show_cardinfo = $cards[$show_cardid];
		//碎闪等级的显示（借用skill1003，由于pdata可能是没有经过skillbase处理的，需要处理裸数据）
		$show_cardblink = 0;
		if(defined('MOD_SKILL1003')) {
			$show_cardblink = (int)\skillbase\skill_getvalue_direct(1003,'nowcard_blink',$pdata['nskillpara']);
		}
		return Array($show_cardid, $show_cardname, $show_rare, $show_cardblink, $show_cardinfo);
	}
	
	//通过记录卡名与卡号判定实际卡号（$cardname所记录的卡）
	function check_realcard($c, $cn) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$ret = $c;
		//卡名与卡号不符，并且卡名与头衔不符，那么认为是篝火等随机卡片，需通过反查确定显示的卡片信息
		if($cn != $cards[$c]['name'] && (empty($cards[$c]['title']) || $cn != $cards[$c]['title'])) {
			$ret = $cardindex_reverse[md5sub($cn,10)];
		}
		return $ret;
	}
	
	//战斗界面显示敌方卡片
	function init_battle($ismeet = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($ismeet);
		eval(import_module('sys','player','metman','cardbase'));
		
		$tdata['cardinfo'] = '';
		
		if(!empty($w_cardname) && (!\weather\check_fog() || $ismeet))
		{
			if(!empty($cards[$w_card]['title']))
				$tdata['cardinfo'] = $cards[$w_card]['title'];
			else $tdata['cardinfo'] = $w_cardname;
		}
	}
	
	//玩家加入战场时有一次性效果的卡片的处理，主要是修改gamevars等
	//把卡片碎闪等级写入skill1003也在这里
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		$ret = $chprocess($pa);
		$card = $pa['card'];
		//入场修改$gamevars
		if(!empty($cards[$card]['valid']['gamevars'])) {
			$cgarr = $cards[$card]['valid']['gamevars'];
			foreach ($cgarr as $cgk => $cgv){
				$gamevars[$cgk] = $cgv;
			}
		}
		//读当前玩家数据并且写入碎闪等级
		$card_data = \cardbase\get_cardlist_energy_from_udata($cudata)[2];
		if(!empty($card_data[$card]['blink']) && defined('MOD_SKILL1003')) {
			\skillbase\skill_setvalue(1003,'nowcard_blink',$card_data[$card]['blink'],$pa);
		}
		return $ret;
	}
	
	//根据card.config.php的修改时间自动刷新$cardindex也就是各种罕贵的卡编号组成的数组，用于抽卡和随机卡
	//并生成一个卡名转卡号的数组$cardindex_reverse用于反查
	function parse_card_index(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		//生成文件名
		//目前放在card.config.php里
		//$card_index_file = GAME_ROOT.'./gamedata/cache/card_index.config.php';
		
		eval(import_module('sys','cardbase'));//载入$card_main_file和$card_config_file
		//如果文件存在且最新，就不改变
		if(file_exists($card_index_file) && filemtime($card_main_file) < filemtime($card_index_file) && filemtime($card_config_file) < filemtime($card_index_file)) return;
		
		$new_cardindex = Array(
			'All' => Array(),//All是所有卡（无视开放情况和隐藏）
			'S' => Array(),//可抽到的卡
			'A' => Array(),
			'B' => Array(),
			'C' => Array(),//M卡算C卡
			'M' => Array(),//占位符
			'EB' => Array(),//SABC和Event Bonus加一起是可获得的全部卡，注意有些只能事件获得的卡比如篝火，虽然在别的卡包，也算EB
			'EB_S' => Array(),//奖励卡里区分SABC
			'EB_A' => Array(),
			'EB_B' => Array(),
			'EB_C' => Array(),
			'hidden' => Array(),//隐藏卡单开一列，一般不参与任何随机		
		);
		
		$new_cardindex_reverse = Array();
		
		foreach($cards as $ci => $cv){
			//$new_cardindex['All'][] = $ci;
			$pack = $cv['pack'];
			if('hidden' == $pack) $new_cardindex['hidden'][] = $ci;//隐藏卡，注意隐藏卡不算卡包开放			
			if(!$ci || !check_pack_availble($pack)) continue;//卡包未开放，则不继续判定；0号卡挑战者也不继续判定
			$rare = !empty($cv['real_rare']) ? $cv['real_rare'] : $cv['rare'];//如果有真实罕贵则填真实罕贵
			if('M' == $rare) $rare = 'C';//M卡实际罕贵是C
			
			$prefix = '';
			//判定是要归到正常卡还是Event Bonus卡
			if('Event Bonus' == $pack || !empty($cv['ignore_kuji']) || in_array($cv['pack'], $pack_ignore_kuji)) {
				$new_cardindex['EB'][] = $ci;
				$prefix = 'EB_';
			}
			if('S' == $rare) $new_cardindex[$prefix.'S'][] = $ci;
			elseif('A' == $rare) $new_cardindex[$prefix.'A'][] = $ci;
			elseif('B' == $rare) $new_cardindex[$prefix.'B'][] = $ci;
			else $new_cardindex[$prefix.'C'][] = $ci;
			
			if(!empty($cv['title'])) $new_cardindex_reverse[md5sub($cv['title'],10)] = $ci;
			else $new_cardindex_reverse[md5sub($cv['name'],10)] = $ci;
		}
		
		if(empty($new_cardindex)) return;
		
		//开始生成文件。这里不直接用var_export()是为了生成方便查看的文件结构
		$contents = str_replace('?>','',$checkstr);//"<?php\r\nif(!defined('IN_GAME')) exit('Access Denied');\r\n";
		$contents .= '$cardindex = Array('."\r\n";
		$i = 1;$z = sizeof($new_cardindex);
		foreach($new_cardindex as $nk => $nv){
			$contents .= "  '$nk' => Array(\r\n";
			foreach($nv as $nvi) {
				$contents .= '    '.$nvi.', //'.$cards[$nvi]['name']."\r\n";
			}
			$contents .= ($i < $z ? '  ),' : '  )') . "\r\n";
			$i++;
		}
		$contents .= ");\r\n\r\n";
		
		//反查数组就无所谓了
		$contents .= '$cardindex_reverse = '.var_export($new_cardindex_reverse,1).';';
		$contents .= "\r\n/* End of file card_index.config.php */";
		
		file_put_contents($card_index_file, $contents);
		chmod($card_index_file, 0777);
		return;
	}
	
	//如果成就或者卡片设定有变，更新卡片获得方式
	function parse_card_gaining_method()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$dir = GAME_ROOT.'./gamedata/cache';
		//生成文件名
		$filename = 'card_gaining_method';
		$file = $dir.'/'.$filename.'.config.php';
		
		eval(import_module('sys','cardbase'));//载入$card_main_file和$card_config_file
		$ach_config_file = GAME_ROOT.'/include/modules/extra/achievement/achievement_base/config/achievement_base.config.php';
		
		//如果文件存在且最新，就不改变
		if(file_exists($file) && filemtime($card_main_file) < filemtime($file) && filemtime($card_config_file) < filemtime($file) && filemtime($ach_config_file) < filemtime($file)) return;
		
		$cgmethod = array();
		
		$tmp_cardtypes = array_keys($cardtypecd);
		//抽卡
		foreach($cardindex as $ckey => $cval){
			if(in_array($ckey, $tmp_cardtypes))	
				foreach($cval as $ci)
					$cgmethod[$ci] = array('通过抽卡获得');
				
		}
		//成就
		if(defined('MOD_ACHIEVEMENT_BASE')){
			eval(import_module('achievement_base'));
			//生成未生效成就列表
			$ach_expired = array();
			foreach(array_keys($ach_available_period) as $aap_key){
				if(1 != \achievement_base\check_achtype_available($aap_key)) {
					$ach_expired = array_merge($ach_expired, $achlist[$aap_key]);
				}
			}
			foreach($achlist as $aclass => $aval) {
				foreach($aval as $ai) {
					if(defined('MOD_SKILL'.$ai.'_ACHIEVEMENT_ID') && !defined('MOD_SKILL'.$ai.'_ABANDONED') && !\skillbase\check_skill_info($ai, 'global')){
						eval(import_module('skill'.$ai));
						$astart = ${'ach'.$ai.'_name'};$astart = array_shift($astart);
						//新成就储存格式，直接读数据
						if(!empty(${'ach'.$ai.'_desc'})) {
							if(!empty(${'ach'.$ai.'_card_prize'})) {
								foreach (${'ach'.$ai.'_card_prize'} as $at => $aarr) {
									$cardset_flag = 1;
									if(!is_array($aarr)) {
										$aarr = array($aarr);
										$cardset_flag = 0;
									}
									foreach($aarr as $acard) {
										if(!isset($cgmethod[$acard])) $cgmethod[$acard] = array();
										$seriesname = $achtype[$aclass];
										$cardset_notice = $cardset_flag ? '可能' : '';
										$aname = ${'ach'.$ai.'_name'}[$at];
										if(count(${'ach'.$ai.'_name'})==1) $cgmethod_this = '完成'.$seriesname.'「'.$astart.'」'.$cardset_notice.'获得';
										elseif(preg_match('/LV\d/s', $aname)) $cgmethod_this = '完成'.$seriesname.'成就「'.$aname.'」'.$cardset_notice.'获得';
										else $cgmethod_this = '完成'.$seriesname.'「'.$astart.'」的第'.$at.'阶段「'.$aname.'」'.$cardset_notice.'获得';
										//过期成就判定
										if(in_array($ai, $ach_expired)) $cgmethod_this = '<font color=grey>'.$cgmethod_this.'</font>';
										$cgmethod[$acard][] = $cgmethod_this;
									}
								}
							}
						}else{
							//旧成就储存格式，要暴力读desc.htm
							$desc_cont_file = constant('MOD_SKILL'.$ai.'_DESC').'.htm';
							if(file_exists($desc_cont_file)){
								
								$desc_cont = file_get_contents($desc_cont_file);
								//第一步读取所有奖励显示
								preg_match_all('|if\s*?\(\$c'.$ai.'\s*?==\'(\d)\'.+?\-\-\>(.+?)\<\!\-\-\{/if|s', $desc_cont, $matches);
								$count = count($matches[0])-1;
								for($i=1;$i<=$count;$i++) {
									$at = $matches[1][$i];
									$adesc = $matches[2][$i];
									preg_match('|奖励.+?\<span.+?\>(.+?)\<\/span|s', $adesc, $matches2);
									if(!empty($matches2)) {
										$cn = $matches2[1];
										$acard = 0;
										foreach($cards as $acard => $cv){
											if($cv['name'] == $cn) break;
										}
										if($acard) {
											if(!isset($cgmethod[$acard])) $cgmethod[$acard] = array();
											$seriesname = $achtype[$aclass];
											$aname = ${'ach'.$ai.'_name'}[$at];
											if(count(${'ach'.$ai.'_name'})==1) $cgmethod[$acard][] = '完成'.$seriesname.'「'.$astart.'」获得';
											elseif(preg_match('/LV\d/s', $aname)) $cgmethod[$acard][] = '完成'.$seriesname.'「'.$aname.'」获得';
											else $cgmethod[$acard][] = '完成'.$seriesname.'「'.$astart.'」的第'.$i.'阶段「'.$aname.'」获得';
										}
									}
								}
							}
							
						}
					}
				}
			}
		}
		
		//特判
		$cgmethod[0][] = '注册账号即有';
		//$cgmethod[63][] = '在四禁前使边缘行者技能「破解」达到50层以上获得（只在标准、卡片、荣耀或极速模式有效）';
		$cgmethod[72][] = '完成竞速挑战「不动的大图书馆」获得';
		$cgmethod[78][] = '完成竞速挑战「烈火疾风」获得';
		$cgmethod[88][] = '完成战斗成就「谈笑风生」获得';
		$cgmethod[158][] = '在「伐木模式」从商店购买「博丽神社的参拜券」并在开局20分钟之内使用以获得';
		$cgmethod[159][] = '通过礼品盒开出的★闪熠着光辉的大逃杀卡牌包★获得（15%概率）';
		$cgmethod[160][] = '完成2017万圣节活动「噩梦之夜 LV2」获得';
		$cgmethod[165][] = '<br><br>这张卡片要如何获得喵？';//'<br>当你看到某张小纸条有「奇怪的空白」时，你可以按下F12。<br>这张卡的获得方式，就藏在那段空白对应的页面代码的位置。<br>　　　　　　　　　　　　　　　　　　　　——林苍月';
		$cgmethod[190][] = '帮助游戏抓到BUG后由管理员奖励获得';
		$cgmethod[368][] = '帮助游戏抓到BUG后由管理员奖励获得';
		$cgmethod[369][] = '帮助游戏抓到BUG后由管理员奖励获得';
		$cgmethod[383][] = '帮助游戏抓到BUG后由管理员奖励获得';
		
		for($ci=200;$ci<=204;$ci++) {
			$cgmethod[$ci][] = '<font color=grey>完成2017十一活动「新的战场 LV2」可能获得</font>';
			$cgmethod[$ci][] = '<font color=grey>完成2017十一活动「新的战场 LV3」可能获得</font>';
			$cgmethod[$ci][] = '<font color=grey>完成2017十一活动「血染乐园 LV3」可能获得</font>';
			$cgmethod[$ci][] = '<font color=grey>完成2017十一活动「极光处刑 LV3」可能获得</font>';
			$cgmethod[$ci][] = '<font color=grey>完成2017万圣节活动「不给糖就解禁」可能获得</font>';
		}
		$cgmethod[200][] = '击杀「全息实体 幻影斗将神 S.A.S」后，使用缴获的★锋利的卡牌包★获得（15%概率）';
		$cgmethod[201][] = '击杀「全息实体 熵魔法传人 Howling」后，使用缴获的★长着兽耳的卡牌包★获得（15%概率）';
		$cgmethod[202][] = '击杀「全息实体 通灵冒险家 星海」后，使用缴获的★套了好几层的卡牌包★获得（15%概率）';
		$cgmethod[203][] = '击杀「全息实体 银白愿天使 Annabelle」后，使用缴获的★羽翼卡牌包★获得（15%概率）';
		$cgmethod[204][] = '击杀「全息实体 麻烦妖精 Sophia」后，使用缴获的★蠢萌的卡牌包★获得（15%概率）';
		$cgmethod[211][] = '击杀场上所有NPC之后，击杀入场的「断罪女神 一一五」，之后使用缴获的★印着「Mind Over Matters」的卡牌包★获得（必定获得）';
		
		
		if(empty($cgmethod)) return;
		$contents = str_replace('?>','',$checkstr);//"<?php\r\nif(!defined('IN_GAME')) exit('Access Denied');\r\n";

		$contents .= '$card_gaining_method = '.var_export($cgmethod,1).';';
		
		file_put_contents($file, $contents);
		chmod($file, 0777);
	}
	
	//进入游戏前判定卡片是否可用
	//传入$udata即用户数据，返回$card_disabledlist,$card_error两个数组
	//$card_disabledlist：键值是卡片编号，errid为错误编号
	//$card_error：错误编号的解释说明
	function card_validate($udata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		
		$nowcard = $udata['card'];
		
		$userCardData = get_user_cardinfo($udata['username']);
		$card_ownlist = $userCardData['cardlist'];
		$card_energy = $userCardData['cardenergy'];
		$card_data_fetched = $userCardData['card_data'];
		$cardChosen = $userCardData['cardchosen'];
		
		/*
		 * $card_disabledlist id => errid
		 * id: 卡片ID errid: 不能使用这张卡的原因
		 * 原因可以叠加
		 * e0: S卡总体CD
		 * e1: 单卡CD
		 * e2: 有人于本局使用了同名卡
		 * e3: 本游戏模式不可用
		 *
		 * $card_error errid => msg
		 */
		$card_disabledlist=Array();
		$card_error=Array();
		
		$energy_recover_rate = get_energy_recover_rate($card_ownlist, $udata['gold']);
		
		//最低优先级错误原因：同名非C卡
		$result = $db->query("SELECT card FROM {$tablepre}players WHERE type = 0");
		$t=Array();
		while ($cdata = $db->fetch_array($result)) $t[$cdata['card']]=1;
		//限制同名卡片入场：在$card_prohibit_same_gtype中设定，instance文件夹下的各模块会修改这个变量，没有单独模块的游戏模式直接写在card.config.php里
		//只有卡片模式、无限复活模式、荣耀房、极速房才限制卡片
		if(in_array($gametype, $card_force_different_gtype)) 
			foreach ($card_ownlist as $key)
				if (!in_array($cards[$key]['rare'], array('C', 'M')) && isset($t[$key])) 
				{
					$card_disabledlist[$key][] = 'e2';
					$card_error['e2'] = '这张卡片暂时不能使用，因为本局已经有其他人使用了这张卡片<br>请下局早点入场吧！';
				}
		
		//次高优先级错误原因：单卡CD
		foreach ($card_ownlist as $key)
			if ($card_energy[$key]<$cards[$key]['energy'] && in_array($gametype, $card_need_charge_gtype))
			{
				$t=($cards[$key]['energy']-$card_energy[$key])/$energy_recover_rate[$cards[$key]['rare']];
				$card_disabledlist[$key][] = 'e1'.$key;
				$card_error['e1'.$key] = '这张卡片暂时不能使用，因为它目前正处于蓄能状态<br>这张卡片需要蓄积'.$cards[$key]['energy'].'点能量方可使用，预计在'.convert_tm($t).'后蓄能完成';
			}
		
		//最高优先级错误原因：卡片类别时间限制
		foreach($cardtypecd as $ct => $ctcd){
			if(!empty($ctcd) && in_array($gametype, $card_need_charge_gtype)){
				$ctcdstr = seconds2hms($ctcd);
				$card_error['e0'.$ct] = '这张卡片暂时不能使用，因为最近'.$ctcdstr.'内你已经使用过'.$ct.'卡了<br>在'.convert_tm($ctcd-($now-$udata['cd_'.strtolower($ct)])).'后你才能再次使用'.$ct.'卡';
		
				if (($now-$udata['cd_'.strtolower($ct)]) < $ctcd){
					foreach ($card_ownlist as $key)
						if ($cards[$key]['rare']==$ct)
							$card_disabledlist[$key][] = 'e0'.$ct;
				}
			}
		}
		
		//最高优先级错误原因：本游戏模式不可用
		$card_error['e3'] = '这张卡片在本游戏模式下禁止使用！';
		
		//获取各模式禁卡表
		$card_disabledlist = card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist);
		
		return array($card_disabledlist,$card_error);
	}
	
	//获取各模式的特殊禁用卡表，各gtype模块可以继承这个函数
	function card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if(0==$gametype) //标准模式禁用挑战者以外的一切卡
		{
			foreach($card_ownlist as $cv){
				if($cv) $card_disabledlist[$cv][]='e3';
			}
		}
//		elseif (2==$gametype)	//deathmatch模式禁用蛋服和炸弹人。死掉的模式不拆分了
//		{
//			if (in_array(97,$card_ownlist)) $card_disabledlist[97][]='e3';
//			if (in_array(144,$card_ownlist)) $card_disabledlist[144][]='e3';
//		}
//		elseif (5==$gametype)	//圣诞模式只允许某4张卡，按理应该拆分掉的，不想改了！反正圣诞模式黑历史了
//		{
//			$tmp_add_hidden_list = array(182, 183, 184, 185);
//			foreach($card_ownlist as $cv){
//				if(!in_array($cv, $tmp_add_hidden_list)) $card_disabledlist[$cv][]='e3';
//			}
//		}
		
		return $card_disabledlist;
	}
	
	//选卡界面的一些特殊显示，全是欺骗
	//返回：
	//$cardChosen当前选择卡片
	//$card_ownlist拥有的卡片清单
	//$packlist存在的卡包清单
	//$hideDisableButton是否默认显示不可用卡片（并隐藏切换按钮）
	function card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		//标准模式自动选择挑战者
		if(0==$gametype) //标准模式禁用挑战者以外的一切卡
		{
			$cardChosen = 0;//自动选择挑战者
			$hideDisableButton = 0;
		}
//		elseif (5==$gametype)	//圣诞模式只允许某4张卡
//		{
//			$tmp_add_hidden_list = array(182, 183, 184, 185);
//			if(!in_array($cardChosen, $tmp_add_hidden_list)) {
//				$cardChosen = $tmp_add_hidden_list[0];//自动选择简单难度
//			}
//			foreach ($tmp_add_hidden_list as $adv){
//				$card_ownlist[] = $adv;
//				$cards[$adv]['pack'] = 'Difficulty';
//			}
//			$packlist[] = 'Difficulty';
//			$hideDisableButton = 0;
//		}
		
		
		return array($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
	}
	
	//⑨卡头像特判放这里，毕竟⑨没有自己的模块
	function icon_parser_valid(&$pdata=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pdata) {
			eval(import_module('player'));
			$pdata = &$sdata;
		}
		$ret = $chprocess($pdata);
		if(!$ret && !empty($pdata['card']) && !empty($pdata['cardname'])) {
			if(check_realcard($pdata['card'], $pdata['cardname'])==299)
				$ret = true;
		}
		return $ret;
	}
}

?>