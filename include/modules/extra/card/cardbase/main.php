<?php

namespace cardbase
{
	$card_config_file = GAME_ROOT.'/include/modules/extra/card/cardbase/config/card.config.php';
	$card_main_file = GAME_ROOT.'/include/modules/extra/card/cardbase/main.php';
		
	function init() {}
	
	function cardlist_decode($str){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = explode('_',$str);
		if(empty($ret)) $ret[] = '0';
		return $ret;
	}
	
	function cardlist_encode($arr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = implode('_',$arr);
		return $ret;
	}

	function get_user_cards($username){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$udata = fetch_udata_by_username($username);
		$cardlist = get_user_cards_process($udata);
		return $cardlist;
	}	
	
	function get_user_cards_process($udata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$cardlist = cardlist_decode($udata['cardlist']);
		return $cardlist;
	}
	
	function get_energy_recover_rate($cardlist, $qiegao)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		/*
		 * 返回 Array ('S'=>..,'A'=>..,'B'=>..,'C'=>0)
		 */
		/*
		 * 新规：S卡CD时间大约在1-3天
		 * A卡CD时间大约在半天-1天
		 * B卡CD时间大约为几小时
		 */
		$ret = Array();
		//$ret['S']=100.0/7/86400;	//S卡固定基准CD 7天
		$ret['C']=0;			//C卡不受能量制影响
		$ret['M']=0;			//M卡更不受能量制影响
		$cnt=Array(); $cnt['S']=0; $cnt['A']=0; $cnt['B']=0;
		//计算S卡、A卡、B卡的数目
		foreach ($cardlist as $key)
		{
			if ($cards[$key]['rare']=='S') $cnt['S']++;
			if ($cards[$key]['rare']=='A') $cnt['A']++;
			if ($cards[$key]['rare']=='B') $cnt['B']++;
		}
		//估算现有切糕对卡片数量的影响，也即还可抽出多少张新卡
//		$bcost = Array('S'=> 90/0.01, 'A' => 90/0.05, 'B'=>90/0.2);
//		foreach (Array('S','A','B') as $ty)
//		{
//			$z=$qiegao;
//			$all=count($cardindex[$ty]);
//			while ($cnt[$ty]<$all && $z>$bcost[$ty]*$all/($all-$cnt[$ty]))
//			{
//				$z-=$bcost[$ty]*$all/($all-$cnt[$ty]);
//				$cnt[$ty]++;
//			}
//		}
		
		$tbase = Array('S' => 86400.0, 'A' => 28800.0, 'B' => 3600.0);
		foreach (Array('S','A','B') as $ty)
		{
			//卡片数目开根号
			$z = round(sqrt($cnt[$ty]));
			if($z<1) $z = 1;
//			$z=$cnt[$ty]/2;
//			if ($cnt[$ty]<=6) $z=$cnt[$ty]*2/3; 
//			if ($cnt[$ty]<=3) $z=2; 
			
			$tbase[$ty]*=$z;
			$ret[$ty]=100.0/$tbase[$ty];
		}
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
		
		$cardlist = get_user_cards_process($udata);		
		$energy_recover_rate = get_energy_recover_rate($cardlist, $udata['gold']);
		
		$cardenergy=Array();
		if ($udata['cardenergy']=="") $t=Array(); else $t=explode('_',$udata['cardenergy']);
		$lastupd = $udata['cardenergylastupd'];
		
		for ($i=0; $i<count($cardlist); $i++)
			if ($i<count($t))
			{
				$cardenergy[$cardlist[$i]]=((double)$t[$i])+($now-$lastupd)*$energy_recover_rate[$cards[$cardlist[$i]]['rare']];
				if (in_array($cards[$cardlist[$i]]['rare'], array('C','M')) || $cardenergy[$cardlist[$i]] > $cards[$cardlist[$i]]['energy']-1e-5)
					$cardenergy[$cardlist[$i]] = $cards[$cardlist[$i]]['energy'];
			}
			else
			{
				$cardenergy[$cardlist[$i]] = $cards[$cardlist[$i]]['energy'];
			}
		
		$ret=Array(
			'cardlist' => $cardlist,
			'cardenergy' => $cardenergy,
			'cardchosen' => $udata['card'],
			'cardenergylastupd' => $now,
		);
		
		if($t != $cardenergy) {
			save_cardenergy($ret, $who);
		}
			
		return $ret;
	}
	
	//更新卡片能量数据库，会自动将能量值转化为浮点数
	function save_cardenergy($data, $who)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
	
	//生成一条获得卡片的站内信，返回值为1则表示是新卡
	function get_card_message($ci,$ext='',&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','cardbase'));
		if ($pa==NULL){
			$n=$name;
		}else{
			if (isset($pa['username'])) $n=$pa['username'];
			else $n=$pa['name'];
		}
		//判定卡片是不是新卡
		$result = fetch_udata_by_username($n,'cardlist');
		if(empty($result)) return;
		//if(!empty($ext)) $ext.='<br>';
		include_once './include/messages.func.php';
		message_create(
			$n,
			'获得卡片',
			$ext.'查收本消息即可获取此卡片，如果已有此卡片则会转化为切糕。',
			'getcard_'.$ci
		);
		
		$ret = 0;
		$clist = explode('_',$result['cardlist']);
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
		$forced = !empty($cs['forced']) ? $cs['forced'] : Array();
		$ignore = !empty($cs['ignore_cards']) ? $cs['ignore_cards'] : Array();
		
		//实际随机卡片
		$arr=array(0);
		do{
			if(!empty($cs['real_random'])) {//真随机，把所有卡集合并
				$arr = array_merge($arr,$cardindex['S'],$cardindex['A'],$cardindex['B'],$cardindex['C'],$cardindex['EB']);
			}else{
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
			
			$arr = array_merge($arr, $forced);
			$arr = array_unique($arr);
			shuffle($arr);
			$ret = $arr[0];
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
				//这游戏有一种另类的debug办法
				eval(import_module('sys'));
				include_once './include/messages.func.php';
				foreach(Array('admin','Yoshiko_G') as $v){
					$r = fetch_udata('uid', "username='$v'");
					if(empty($r)) continue;
					message_create(
						$v,
						'截获big',
						($groomtype ? '房间' : '') . "第{$gamenum}局中，{$name}在入场时随到了空白的卡。卡片编号：".$card
					);
				}	
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
			$checkstr = substr($key,0,3);
			if (in_array($checkstr, Array('wep','arb','arh','ara','arf','art','itm'))){//道具类的，如果是数组则随机选一个
				if(is_array($value)){
					shuffle($value);
					$value = $value[0];
				}
			}
			$ebp[$key] = $value;
		}
		
		$ebp['card'] = $card;
		$ebp['cardname'] = $cardname;
		
		return Array($ebp, $skills, $prefix);
	}
	
	//判断一张卡当前是否在持有列表中
	function check_card_in_ownlist($card, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(in_array($card,$card_ownlist) || (5==$gametype && in_array($card,array(182, 183, 184, 185)))) return true;
		return false;
	}
	
	//根据用户名或者玩家数据，从数据库查询当前所用卡片，主要是数据库读写
	function get_card($ci,&$pa=NULL,$ignore_qiegao=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','cardbase'));
		if ($pa==NULL){
			$n=$name;
		}else{
			if (isset($pa['username'])) $n=$pa['username'];
			else $n=$pa['name'];
		}
		$pu = fetch_udata_by_username($n);
		$ret = get_card_process($ci,$pu,$ignore_qiegao);
		
		$upd = array(
			'cardlist' => $pu['cardlist'],
			'gold' => $pu['gold'],
		);
		update_udata_by_username($upd, $n);
		return $ret;
	}
	
	//获得卡片和切糕的核心判定，如果卡重复，则换算成切糕
	//会自动判定输入的cardlist键值是字符串还是数组
	function get_card_process($ci,&$pa,$ignore_qiegao=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
	
	/*
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map','cardbase'));
		if (!in_array($gametype,$qiegao_ignore_mode)){
			if (($itm0=="绝冲大剑【神威】")&&(($areanum/$areaadd)<2)){
				if (get_card(42)==1){
					$log.="恭喜您获得了活动奖励卡<span class=\"orange\">Fleur</span>！<br>";
				}else{
					$log.="您已经拥有活动奖励卡了，系统奖励您<span class=\"yellow b\">100</span>切糕！<br>";
					get_qiegao(100);
				}
			}
		}
		$chprocess();	
	}*/
	
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
				if ($kr==-1){
					return -1;
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
	function check_pack_availble($pn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		$ret = true;
		if(isset($packstart[$pn]) && $packstart[$pn] > $now) $ret = false;
		return $ret;
	}
	
	//卡包过滤器，过滤没开放的卡包。
	function pack_filter($packlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$n_packlist = array();
		foreach($packlist as $pv){
			if(check_pack_availble($pv)) $n_packlist[]=$pv;
		}
		return $n_packlist;
	}
	
	//卡名显示
	function parse_interface_profile()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys','player','cardbase'));
		if($cardname == $cards[$card]['name']) {
			if(!empty($cards[$card]['title'])) 
				$uip['cardname_show'] = $cards[$card]['title'];
			else
				$uip['cardname_show'] = $cards[$card]['name'];
		}else{
			$uip['cardname_show'] = $cardname;
		}
	}
	
	//战斗界面显示敌方卡片
	function init_battle($ismeet = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($ismeet);
		eval(import_module('sys','player','metman','cardbase'));
		
		$tdata['cardinfo'] = '';
		if(!empty($w_cardname)){
			if(!empty($cards[$w_card]['title']))
				$tdata['cardinfo'] = $cards[$w_card]['title'];
			else $tdata['cardinfo'] = $w_cardname;
		}
	}
	
	//玩家加入战场时有一次性效果的卡片的处理，主要是修改gamevars等
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
		return $ret;
	}
	
	//根据card.config.php的修改时间自动刷新$cardindex也就是各种罕贵的卡编号组成的数组，用于抽卡和随机卡
	function parse_card_index(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		//生成文件名
		//目前放在card.config.php里
		//$card_index_file = GAME_ROOT.'./gamedata/cache/card_index.config.php';
		
		eval(import_module('sys','cardbase'));//载入$card_main_file和$card_config_file
		//如果文件存在且最新，就不改变
		if(file_exists($card_index_file) && filemtime($card_main_file) < filemtime($card_index_file) && filemtime($card_config_file) < filemtime($file)) return;
		
		$new_cardindex = Array(
			'All' => Array(),//All是所有卡（无视开放情况和隐藏）
			'S' => Array(),//可抽到的卡
			'A' => Array(),
			'B' => Array(),
			'C' => Array(),//M卡算C卡
			'EB' => Array(),//SABC和Event Bonus加一起是可获得的全部卡，注意有些只能事件获得的卡比如篝火，虽然在别的卡包，也算EB
			'EB_S' => Array(),//奖励卡里区分SABC
			'EB_A' => Array(),
			'EB_B' => Array(),
			'EB_C' => Array(),
			'hidden' => Array(),//隐藏卡单开一列，一般不参与任何随机		
		);
		
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
			
		}
		
		if(empty($new_cardindex)) return;
		
		//开始生成文件
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
		$contents .= ');';
		//$contents .= '$cardindex = '.var_export($new_cardindex,1).';';
		
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
		
		//抽卡
		foreach($cardindex as $ckey => $cval){
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
		//$cgmethod[63][] = '在四禁前使锡安成员技能「破解」达到50层以上获得（只在标准、卡片、荣耀或极速模式有效）';
		$cgmethod[72][] = '完成竞速挑战「不动的大图书馆」获得';
		$cgmethod[78][] = '完成竞速挑战「烈火疾风」获得';
		$cgmethod[88][] = '完成战斗成就「谈笑风生」获得';
		$cgmethod[158][] = '在「伐木模式」从商店购买「博丽神社的参拜券」并在开局20分钟之内使用以获得';
		$cgmethod[159][] = '通过礼品盒开出的★闪熠着光辉的大逃杀卡牌包★获得（15%概率）';
		$cgmethod[160][] = '完成2017万圣节活动「噩梦之夜 LV2」获得';
		$cgmethod[165][] = '<br><br>这张卡片要如何获得喵？';//'<br>当你看到某张小纸条有「奇怪的空白」时，你可以按下F12。<br>这张卡的获得方式，就藏在那段空白对应的页面代码的位置。<br>　　　　　　　　　　　　　　　　　　　　——林苍月';
		$cgmethod[190][] = '帮助游戏抓到BUG后由管理员奖励获得';
		
		for($ci=200;$ci<=204;$ci++) {
			$cgmethod[$ci][] = '<font color=grey>完成2017十一活动「新的战场 LV2」可能获得</font>';
			$cgmethod[$ci][] = '<font color=grey>完成2017十一活动「新的战场 LV3」可能获得</font>';
			$cgmethod[$ci][] = '<font color=grey>完成2017十一活动「血染乐园 LV3」可能获得</font>';
			$cgmethod[$ci][] = '<font color=grey>完成2017十一活动「极光处刑 LV3」可能获得</font>';
			$cgmethod[$ci][] = '<font color=grey>完成2017万圣节活动「不给糖就解禁」可能获得</font>';
		}
		$cgmethod[200][] = '在「荣耀模式」模式击杀「全息实体 幻影斗将神 S.A.S」后，使用缴获的★锋利的卡牌包★获得（15%概率）';
		$cgmethod[201][] = '在「荣耀模式」模式击杀「全息实体 熵魔法传人 Howling」后，使用缴获的★长着兽耳的卡牌包★获得（15%概率）';
		$cgmethod[202][] = '在「荣耀模式」模式击杀「全息实体 通灵冒险家 星海」后，使用缴获的★套了好几层的卡牌包★获得（15%概率）';
		$cgmethod[203][] = '在「荣耀模式」模式击杀「全息实体 银白愿天使 Annabelle」后，使用缴获的★羽翼卡牌包★获得（15%概率）';
		$cgmethod[204][] = '在「荣耀模式」模式击杀「全息实体 麻烦妖精 Sophia」后，使用缴获的★蠢萌的卡牌包★获得（15%概率）';
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
}

?>