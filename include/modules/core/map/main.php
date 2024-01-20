<?php

namespace map
{
	function init() 
	{
		
	}

	//获取可用地图总数。一般用于游戏结束判定
	//本模块单纯计算所有的地图，如果需要有其他判断请继承这个函数
	function get_plsnum() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return sizeof(get_var_in_module('plsinfo','map'));
	}

	//获取可用地图的下标的数组。
	//本模块单纯获取$plsinfo的键名。
	function get_all_plsno() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array_keys(get_var_in_module('plsinfo','map'));
	}

	//判定某个地图编号是否可用。本模块单纯判定是不是$plsinfo的其中一个键名
	function is_plsno_available($plsno) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return in_array($plsno, get_all_plsno());
	}

	//获取当前是第几波禁区。
	//纯粹用(当前禁区数-开局禁区数)除以禁区每次增加数来计算。其他模式如果有修改禁区计算方式，请一并继承并修改这个函数
	function get_area_wavenum(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return floor((get_var_in_module('areanum', 'sys') - sizeof(get_var_in_module('area_on_start', 'map'))) / get_var_in_module('areaadd', 'map'));
	}

	//获得当前禁区地图编号组成的数组。
	//$wave指额外增加的禁数（如2就是下第二波）
	//因为array_slice的特性，$areanum过大就会返回整个数组，符合要求
	function get_current_area($wave = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array_slice(get_var_in_module('arealist', 'sys'), 0, get_var_in_module('areanum', 'sys') + $wave * get_var_in_module('areaadd', 'map'));
	}

	//获得当前非禁区地图编号组成的数组。
	//$wave指额外增加的禁数（如2就是下第二波）
	//因为array_slice的特性，$areanum过大就会返回空数组，符合要求
	function get_current_not_area($wave = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array_slice(get_var_in_module('arealist', 'sys'), get_var_in_module('areanum', 'sys') + $wave * get_var_in_module('areaadd', 'map'));
	}
	
	//检查一个地区编号是否是禁区，与hack无关。
	//$wave指额外增加的禁数（如2就是下第二波）
	//注意，数组的前n个元素一定被视为禁区，这个n是$area_on_start的元素个数
	function check_in_forbidden_area($pno, $wave=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return in_array($pno, get_current_area($wave));
	}
	
	//检查一个地区是否可进入，包含解禁和hack两种情况
	function check_can_enter($pno){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return !check_in_forbidden_area($pno) || get_var_in_module('hack', 'sys');
	}

	//计算禁区倒计时，显示用
	function init_areatiming(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!isset($uip['timing'])) $uip['timing'] = array();
		//如果不在游戏中，则不显示禁区倒计时，但是考虑到灵活性，数值还是会处理
		$on = true;
		if(defined('IN_REPLAY') || !in_array($GLOBALS['___CURSCRIPT'], array('GAME', 'ACT'))) $on = false;
		$timing = ($areatime-$now);
		$timing_r = sprintf("%02d", floor($timing/60)).':'.sprintf("%02d", $timing%60);
		if($timing < 10) $timing_r = '<span class="red b">'.$timing_r.'</span>';
		elseif($timing < 60) $timing_r = '<span class="yellow b">'.$timing_r.'</span>';
		$uip['timing']['area_timing'] = array(
			'on' => $on,
			'mode' => 0,
			'timing' => $timing*1000,
			'timing_r' => $timing_r
		);
	}
	
	//获取禁区间隔时间，不同游戏模式可能有不同的设置
	function get_area_interval(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$gametype = get_var_in_module('gametype', 'sys');
		$areainterval = get_var_in_module('areainterval','map');
		$ret = $areainterval[0];
		if(isset($areainterval[$gametype])) $ret = $areainterval[$gametype];
		return $ret;
	}
	
	//安全区域列表
	//返回并非禁区的区域列表，如果传参$no_dangerous_zone为真，则再排除掉SCP、英灵殿等危险地区
	//传参$type是给继承的函数用的
	function get_safe_plslist($no_dangerous_zone = true, $type = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ret = get_current_not_area();
		if(!empty($ret) && $no_dangerous_zone) $ret = array_diff($ret, get_var_in_module('dangerous_zone','map'));

		return $ret;
	}

	//上述高频使用的函数尽可能地少eval(import_module())而是用get_var_in_module的形式减少开销。后面的函数无所谓了

	//获得下一次要增加的禁区列表
	//传参为波数，如2就是下第二波
	function get_arealist_nextadd($wave = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		return array_slice($arealist, $areanum + ($wave - 1) * $areaadd, $areaadd);
	}
	
	//增加禁区时的角色处理。本模块是空的
	function addarea_pc_process($atime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//每次增加禁区时都检查是否结束游戏
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(empty(get_current_not_area())) //已经没有非禁区了，进入全灭结局
		{
			\sys\gameover($atime,'end1');
			return;
		}
			
		if( $alivenum == 1 && $gamestate >= 30 ) { //游戏停止激活时如果人数足够少则判定游戏结束
			\sys\gameover($atime);
			return;
		} elseif( $alivenum <= 0 && $gamestate >= 30 ) {
			\sys\gameover($atime,'end1');
		} 
	}
	
	//单次禁区增加
	//会自动发布公告和检查游戏是否结束（全部禁完的全灭结局）
	function add_once_area($atime) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		if ( $gamestate > 10 && $now > $atime ) {//增加禁区
			$plsnum = sizeof($arealist);
			$areaaddlist = get_arealist_nextadd();
			$areanum += $areaadd;
			if($areanum >= $plsnum) $areanum = $plsnum;
			if($hack > 0) $hack--;

			post_addarea_process($atime, $areaaddlist);//这里判定停止激活和无人参加结局
			
			check_addarea_gameover($atime);//判定游戏是否结束。有一大串模块继承这里

			if($gamestate > 0) \sys\rs_game(16+32); //若游戏没有结束，则重置商店和道具。2024.01.20 改到这个位置
		} else {
			return;
		}
	}
	
	//每次增加禁区之后都执行的事件
	function post_addarea_process($atime, $areaaddlist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//禁区宣告（进行+聊天）
		addnews($atime, 'addarea', $areaaddlist, $weather);
		systemputchat($atime, 'areaadd', $areaaddlist);
		//处理玩家禁区死亡/躲避和NPC躲避
		addarea_pc_process($atime);
		//检查是否满足无人参加/停止激活条件
		check_game_stop_joining();
	}

	//聊天发禁区警告
	function areawarn(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		$areaaddlist = get_arealist_nextadd();
		systemputchat($now, 'areawarn', $areaaddlist);
		return;
	}
	
	//新一局游戏开始时初始化禁区
	function rs_game($xmode = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys','map'));
		if ($xmode & 2) {
			//echo " - 禁区初始化 - ";
			list($sec,$min,$hour,$day,$month,$year,$wday,$yday,$isdst) = localtime($starttime);
			$areatime = rs_areatime();
			//init_areatiming();
			$areanum = sizeof($area_on_start);//初始禁区数目
			$all_plsno = get_all_plsno();
			$arealist = array_diff($all_plsno, $area_on_start);//生成其余禁区列表并随机化
			shuffle($arealist);
			$arealist = array_merge($area_on_start, $arealist);//合并禁区顺序列表
			$hack = 0;
			$areawarn = 0;
		}
	}
	
	function rs_areatime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		return ceil(($starttime + get_area_interval() * 60)/600) * 600; //禁区时刻为10分钟的倍数
	}
	
	//旧的显示移动列表函数，已废弃
	// function movehtm($atime = 0) {
	// 	if (eval(__MAGIC__)) return $___RET_VALUE;
	// 	//愚蠢的movehtm函数已经被移除…… 现在move.htm和areainfo.htm都由模板自动生成
	// 	return;
	// }
	
	//旧的下3轮禁区显示函数，已废弃
	// function get_area_plsname($forshort=0, $p1=0, $p2=0){
	// 	if (eval(__MAGIC__)) return $___RET_VALUE;
	// 	eval(import_module('sys','map'));
	// 	$plsnamedata = $forshort ? $plsinfo_for_short : $plsinfo;
	// 	if(!$p1) $p1 = $areanum;
	// 	if(!$p2) $p2 = $areanum+$areaadd-1;
	// 	$retarr = array();
	// 	for($i=$p1; $i<=$p2; $i++){
	// 		if(isset($plsnamedata[$arealist[$i]]))
	// 			$retarr[] = $plsnamedata[$arealist[$i]];
	// 	}
	// 	return $retarr;
	// }

	//获得下一次禁区列表的名字的数组
	function get_areanames_nextadd($wave = 1, $forshort = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		$plsnamedata = $forshort ? $plsinfo_for_short : $plsinfo;
		$areaaddlist = get_arealist_nextadd($wave);
		$retarr = array();
		foreach($areaaddlist as $i) {
			$retarr[] = $plsnamedata[$i];
		}
		return $retarr;
	}

	//生成进行状况页面提示的下3次禁区列表
	function get_next_areadata_html($atime=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$areadata='';
		if(!$atime){
			$atime = $areatime;
		}
		$timediff = $atime - $now;
		if($timediff > 43200){//如果禁区时间在12个小时以后则显示其他信息
			$areadata .= '距离下一次禁区还有12个小时以上';
		}else{
			for($i=1;$i<=3;$i++){
				$next_areanames = get_areanames_nextadd($i);
				if(!empty($next_areanames)){
					$at = getdate($atime + get_area_interval()*($i-1)*60);
					$nexthour = $at['hours']; $nextmin = $at['minutes'];
					while($nextmin >= 60){
						$nexthour +=1; $nextmin -= 60;
					}
					if($nexthour >= 24) $nexthour-=24;
					$areadata .= "<b>{$nexthour}时{$nextmin}分：</b> " . implode('&nbsp;&nbsp;', $next_areanames);
					if($i < 3){
						$areadata .= '；';
					}
				}
			}
		}
		return $areadata;
	}
	
	function updategame()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$chprocess();//先判定开始游戏和反挂机
		
		if (($gamestate > 10)&&($now > $areatime)) {//判定增加禁区，是一口气判定完毕
			while($now > $areatime){
				$o_areatime = $areatime;
				$areatime += get_area_interval() * 60;
				add_once_area($o_areatime);
				//init_areatiming();
				$areawarn = 0;
			}
		}elseif( $gamestate > 10 && ($now > $areatime - get_var_in_module('areawarntime','map')) && !$areawarn ){//判定警告增加禁区
			areawarn();
			$areawarn = 1;
		}
		
		//判定游戏无人参加/停止激活放到了每次增加禁区时
		
	}
	
	//判定游戏无人参加/停止激活
	function check_game_stop_joining(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		
		if($gamestate == 20) {
			$arealimit = $arealimit > 0 ? $arealimit : 1; 
			$now_wavenum = get_area_wavenum();
			if( $validnum <= 0 && $now_wavenum >= $arealimit ) {//判定无人参加并结束游戏
				\sys\gameover($areatime-get_area_interval()*60+1,'end4');
			} elseif( $now_wavenum >= $arealimit || $validnum >= $validlimit ) {//判定游戏停止激活
				$gamestate = 30;
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		if($news == 'death11') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因滞留在<span class=\"red b\">禁区【{$plsinfo[$c]}】</span>死亡</li>";
		
		elseif($news == 'addarea') {
			$info = "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，增加禁区：";
			$alist = explode('_',$a);
			foreach($alist as $ar) $info.="$plsinfo[$ar] ";
			$info .= "</li>";
			return $info;
		}
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>