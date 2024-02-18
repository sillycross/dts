<?php
//视野扩展技能，允许临时视野的存在
//说明一下构思：视野可能有几十上百个格子，因此单独作为一个字段比较稳健；临时视野通常较少而且需要支持各种功能，做成技能更加合适
namespace skill1006
{
	$beacon_pool = Array();//维护一个临时视野池用于避免反复解码同一个值
	
	function init() 
	{
		define('MOD_SKILL1006_INFO','hidden;');
	}
	
	//为了避免和searchmemory模块弄混，代码上临时视野的术语叫beacon
	function acquire1006(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1006,'beacons','',$pa);
	}
	
	function lost1006(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1006(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//所有模式入场都会获得skill1006
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_acquire(1006,$pa);
		$chprocess($pa);
	}
	
	//测试用函数
	function skill1006_get_beacon_pool()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill1006'));
		$ret = '';
		foreach($beacon_pool as $k => $v) {
			$ret .= var_export(gdecode($k, 1),1).' ====> '.var_export($v, 1).' ;;;; ';
		}
		return $ret;
	}
	
	//临时视野构思：只在显示时挂靠在视野的界面上，实际处理都是走的本模块。
	//临时视野不参与各种视野边缘之类的判定，会因为正常探索到该内容而被消除，移动后临时视野会转化为正常的记忆
	//不存在“临时记忆”
	
	//临时视野编码。
	//传入临时视野数组以及$pa数组，会自动gencode并写入技能列表
	//由于目测临时视野不会频繁增减，本模块对临时视野的每次修改都会进行解码和编码并直接写到技能里，与searchmemory模块只在进程开始和结束时进行解码编码不同
	//传入非法的字符会直接清空临时视野
	function encode_beacon($barr, &$pa=NULL) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		if(\skillbase\skill_query(1006, $pa)) {
			$e = '';
			if(is_array($barr)) {
				eval(import_module('skill1006'));
				$e = gencode($barr);
				$beacon_pool[$e] = $barr;
			}
			\skillbase\skill_setvalue(1006,'beacons',$e,$pa);
		}
	}
	
	//临时视野解码。
	//传入$pa数组，返回解码过的临时视野数组。临时视野的数据结构与视野类似。
	function decode_beacon(&$pa=NULL) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		if(\skillbase\skill_query(1006, $pa)) {
			$r = \skillbase\skill_getvalue(1006,'beacons',$pa);
			if(!empty($r)) {
				eval(import_module('skill1006'));
				if(!empty($beacon_pool[$r])) {
					$ret = $beacon_pool[$r];
				}else{
					$ret = gdecode($r, 1);
				}
				
				if(!empty($ret) && is_array($ret)) {
					$beacon_pool[$r] = $ret;
					return $ret;
				}
			}
		}
		return Array();
	}
	
	//临时视野增加的核心函数
	//传入类似searchmemory模块的$marr结构的数组以及$pa数组
	function add_beacon_core($marr, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$beacons = decode_beacon($pa);
		array_push($beacons, $marr);
		encode_beacon($beacons, $pa);
	}
	
	//临时视野增加。目前只是单纯调用核心函数
	function add_beacon($marr, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		//echo 'before add '.skill1006_get_beacon_pool();
		add_beacon_core($marr, $pa);
	}
	
	//查找特定iid或者pid的临时视野，$id为需查找的id，$ikind为类别（默认为pid）
	//返回临时视野下标。如果不存在，返回-1
	function seek_beacon_by_id($id, $ikind = 'pid', &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$beacons = decode_beacon($pa);
		foreach($beacons as $i => $v){
			if(isset($v[$ikind]) && $v[$ikind] == $id){
				return $i;
			}
		}
		return -1;
	}
	
	//移除临时视野的核心函数，根据传入的指令移除不同的视野数组
	//$mn为移除哪一个下标的数组，默认是0，如果为-1则是最末位，如果是字符串'ALL'则重置整个临时视野数组
	//返回被移除的那个数组，如果是全部删除则返回整个临时视野数值
	function remove_beacon_core($bn = 0, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$beacons = decode_beacon($pa);
		$ret = Array();
		if($bn === 'ALL'){
			$ret = array_clone($beacons);
			$beacons = array();
			encode_beacon($beacons, $pa);
			return $ret;
		}
		if($bn == -1){//把刚拿到的忘掉
			$bn = sizeof($beacons) - 1;
		}
		if(isset($beacons[$bn])){
			$ret = $beacons[$bn];
			array_splice($beacons,$bn,1);
			encode_beacon($beacons, $pa);
		}
		return $ret;
	}
	
	//移除临时视野，目前只是个壳
	function remove_beacon($bn = 0, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		return remove_beacon_core($bn, $pa);
	}
	
	//继承searchmemory模块，在所有视野转化成记忆之前（一般是移动），把临时视野变成记忆（也就是不再保存于skill1006）
	//会额外挤掉几个记忆，不过一般没人在乎吧
	function change_memory_unseen($mn = 0, $showlog = 1, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if('ALL' === $mn) {
			if(empty($pa)) {
				$pa = & get_var_in_module('sdata', 'player');
			}
			if(!empty(\skillbase\skill_getvalue(1006,'beacons',$pa))) {
				$beacons = decode_beacon($pa);
				if(!empty($beacons)) {
					foreach($beacons as $bv) {
						\searchmemory\add_memory($bv, 0, $pa);
					}
					$beacons = Array();
					encode_beacon($beacons, $pa);
				}
			}
		}
		$ret = $chprocess($mn, $showlog, $pa);
	}
	
	//探到了临时视野、记忆里的角色，移除相关格子
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$bn = seek_beacon_by_id($sid, 'pid');
		if($bn >= 0) remove_beacon($bn);
		$chprocess($sid);
	}
	
	//再探记忆主函数
	//参数值代表访问元素的下标
	function beacon_discover($bn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$bn = (int)$bn;
		
		eval(import_module('sys','logger'));
		$beacons = decode_beacon();
		
		if(empty($beacons[$bn])){
			$log .= '临时记忆参数有误。<br>';
			$mode = 'command';
			return;
		}
		
		$barr = $beacons[$bn];
		
		//借用searchmemory模块的判定函数
		if(!\searchmemory\searchmemory_discover_valid_check($barr)) return;
		
		//临时视野一定能看到，所以不需要做额外探索的判定
		
		//删除临时视野
		remove_beacon($bn);
		
		//借用searchmemory模块的执行函数
		\searchmemory\searchmemory_discover_core($barr);
	}
	
	//访问临时视野
	function act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		if(\searchmemory\searchmemory_available()){
			//再探的判断
			if ($mode == 'command' && strpos($command,'beacon')===0){
				$bn = substr($command,6);
				beacon_discover($bn);
			}
		}
		
		$chprocess();
	}
	
	//如果队友道具栏满了，会把东西放在队友的临时视野
	function senditem_before_log_event($itmn, $sendflag, &$edata) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$sendflag && \searchmemory\searchmemory_available() && !$edata['type']) {//只能送到玩家的视野里
			eval(import_module('sys','logger','player'));
			$itm = & ${'itm'.$itmn};
			$itmk = & ${'itmk'.$itmn};
			$itme = & ${'itme'.$itmn};
			$itms = & ${'itms'.$itmn};
			$itmsk = & ${'itmsk'.$itmn};
			//先把道具数据插入地图
			$dropid = \itemmain\itemdrop_query($itm, $itmk, $itme, $itms, $itmsk, $pls);
			//把该道具追加到队友临时视野
			$barr = array('iid' => $dropid, 'itm' => $itm, 'pls' => $pls, 'unseen' => 0, 'itmsk' => $itmsk);
			add_beacon($barr, $edata);
			//进行提示和保存对方数据
			$log .= "你将<span class=\"yellow b\">".$itm."</span>送到了<span class=\"yellow b\">{$edata['name']}</span>的身旁。<br>";
			$x = "<span class=\"yellow b\">$name</span>将<span class=\"yellow b\">".$itm."</span>送到了你的身旁。";
				
			\logger\logsave($edata['pid'],$now,$x,'t');
			addnews($now,'senditem',$name,$edata['name'],$itm);
			\player\player_save($edata);

			//然后销毁当前道具
			\itemmain\item_destroy_core('itm'.$itmn, $sdata);
			
			$sendflag = 1;
		}
		return $sendflag;
	}
	
	//界面显示用的判定函数，如果有临时视野则判定为true
	function is_searchmemory_extra_displayed()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		if(\skillbase\skill_query(1006) && !empty(decode_beacon())) {
			$ret = true;
		}
		return $ret;
	}
	
	//从一个道具池中添加一定数量的道具到临时视野
	//传入的$mipool是\itemmain\discover_item()生成的数组，从mapitem表拉取的数据
	function add_beacon_from_itempool($mipool, $num, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		//先把先前的临时视野都扔进视野。注意需要倒序。
		if(!empty(\skillbase\skill_getvalue(1006,'beacons',$pa))) {
			$beacons = decode_beacon($pa);
			if(!empty($beacons)) {
				$beacons = array_reverse($beacons);
				foreach($beacons as $bv) {
					\searchmemory\add_memory($bv, 0, $pa);
				}
				$beacons = Array();
				encode_beacon($beacons, $pa);
			}
		}
		$itemnum = count($mipool);
		$itmlist = array();
		for ($i=0;$i<min($num,$itemnum);$i++)
		{
			$amarr = array('iid' => $mipool[$i]['iid'], 'itm' => $mipool[$i]['itm'], 'pls' => $pa['pls'], 'unseen' => 0);
			add_beacon($amarr, $pa);
			$itmlist[] = $mipool[$i]['itm'];
		}
		\player\player_save($pa);
		return $itmlist;
	}
	
	//从一个角色池中添加一定数量的角色到临时视野
	function add_beacon_from_edata_arr($edata_arr, $num, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		if(!empty(\skillbase\skill_getvalue(1006,'beacons',$pa))) {
			$beacons = decode_beacon($pa);
			if(!empty($beacons)) {
				$beacons = array_reverse($beacons);
				foreach($beacons as $bv) {
					\searchmemory\add_memory($bv, 0, $pa);
				}
				$beacons = Array();
				encode_beacon($beacons, $pa);
			}
		}
		$edatanum = count($edata_arr);
		$enamelist = array();
		for ($i=0;$i<min($num,$edatanum);$i++)
		{
			if ($edata_arr[$i]['hp'] > 0)
			{
				$amarr = array('pid' => $edata_arr[$i]['pid'], 'Pname' => $edata_arr[$i]['name'], 'pls' => $pa['pls'], 'smtype' => 'enemy', 'unseen' => 0);
				$enamelist[] = $edata_arr[$i]['name'];
			}
			else
			{
				$amarr = array('pid' => $edata_arr[$i]['pid'], 'Pname' => $edata_arr[$i]['name'], 'pls' => $pa['pls'], 'smtype' => 'corpse', 'unseen' => 0);
				$enamelist[] = $edata_arr[$i]['name'].'的尸体';
			}
			add_beacon($amarr, $pa);
		}
		\player\player_save($pa);
		return $enamelist;
	}
	
	//获得一个道具列表中所有的道具，该列表中每个元素都是类似$theitem的标准形式，优先加入包裹中的空位，多余的会进入临时视野
	function multi_itemget($items, &$pa=NULL, $showlog=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$selflag = 0;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
			$selflag = 1;
		}
		elseif ($pa['pid'] == get_var_in_module('pid', 'player')) $selflag = 1;
		
		$smflag = 1;
		if (!\searchmemory\searchmemory_available() || !\skillbase\skill_query(1006, $pa)) $smflag = 0;
		if (empty($items)) return;
		
		$pos = 1;
		$topack_item_names = array();
		$drop_item_names = array();
		foreach ($items as $item)
		{
			$packflag = 0;
			if ($pos < 6)
			{
				for ($i=$pos;$i<=6;$i++)
				{
					$pos = $i;
					if (empty($pa['itms'.$i]))
					{
						$pa['itm'.$i] = $item['itm'];
						$pa['itmk'.$i] = $item['itmk'];
						$pa['itme'.$i] = $item['itme'];
						$pa['itms'.$i] = $item['itms'];
						$pa['itmsk'.$i] = $item['itmsk'];
						$topack_item_names[] = "<span class=\"yellow b\">{$item['itm']}</span>";
						$packflag = 1;
						break;
					}
				}
			}
			if (!$packflag)
			{
				$dropid = \itemmain\itemdrop_query($item['itm'], $item['itmk'], $item['itme'], $item['itms'], $item['itmsk'], $pa['pls']);
				$drop_item_names[] = "<span class=\"yellow b\">{$item['itm']}</span>";
				if ($smflag)
				{
					$amarr = array('iid' => $dropid, 'itm' => $item['itm'], 'pls' => $pa['pls'], 'unseen' => 0);
					add_beacon($amarr, $pa);
				}
			}
		}
		if ($showlog) eval(import_module('logger'));
		$picount = count($topack_item_names);
		if ($picount)
		{
			if ($picount > 1) $pi_words = implode('、', array_slice($topack_item_names, 0, $picount - 1)).'和'.end($topack_item_names);
			else $pi_words = $topack_item_names[0];
			if ($selflag && $showlog)
			{
				$log .= "你获得了{$pi_words}。<br>";
			}
		}
		$dicount = count($drop_item_names);
		if ($dicount)
		{
			if ($dicount > 1) $di_words = implode('、', array_slice($drop_item_names, 0, $dicount - 1)).'和'.end($drop_item_names);
			else $di_words = $drop_item_names[0];
			if ($selflag && $showlog)
			{
				if ($smflag) $log .= "{$di_words}掉到了你的脚边。<br>";
				else $log .= "{$di_words}掉到了地上。<br>";
			}
		}
		\player\player_save($pa);
	}
	
}

?>