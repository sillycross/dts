<?php

namespace map
{
	function init() {}
	
	//非禁区域列表。如果$no_dangerous_zone开启，则再排除掉SCP、英灵殿等危险地区
	function get_safe_plslist($no_dangerous_zone = true){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','map'));
		if($areanum+1 > sizeof($arealist)) return array();
		else {
			$r = array_slice($arealist,$areanum+1);
			if($no_dangerous_zone) $r = array_diff($r, array(32,34));
			return $r;
		}
	}
	
//	function add_new_killarea($where,$atime)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//	}
	
	//增加禁区时的角色处理
	function addarea_pc_process($atime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		$plsnum = sizeof($plsinfo) - 1;
		if($areanum >= $plsnum) 
		{
			\sys\gameover($atime,'end1');
			return;
		}
			
		if(($alivenum == 1)&&($gamestate >= 30)) { 
			\sys\gameover($atime);
			return;
		} elseif(($alivenum <= 0)&&($gamestate >= 30)) {
			\sys\gameover($atime,'end1');
		} else {
			\sys\rs_game(16+32);
		}
	}
	
	function add_once_area($atime) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		if (($gamestate > 10)&&($now > $atime)) {
			$plsnum = sizeof($plsinfo) - 1;
			
			$areanum += $areaadd;
			
//			for ($x=0; $x<=$areanum; $x++)
//			{
//				if ($x>$plsnum) continue;
//				add_new_killarea($arealist[$x],$atime);
//			}
			
			if($areanum >= $plsnum) 
			{
				$areaaddlist = array_slice($arealist,$areanum - $areaadd +1);
				$areanum = $plsnum;
			}
			else
			{
				if($hack > 0){$hack--;}
				$areaaddlist = array_slice($arealist,$areanum - $areaadd +1,$areaadd);
				//movehtm();
			}
			
			addarea_pc_process($atime);
			
			addnews($atime, 'addarea',$areaaddlist,$weather);
			systemputchat($atime,'areaadd',$areaaddlist);
			
			check_addarea_gameover($atime);
		} else {
			return;
		}
	}

	function areawarn(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		$areaaddlist = array_slice($arealist,$areanum+1,$areaadd);
		$areawarn = 1;
		systemputchat($now,'areawarn',$areaaddlist);
		return;
	}
	
	function rs_game($xmode = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys','map'));
		if ($xmode & 2) {
			//echo " - 禁区初始化 - ";
			list($sec,$min,$hour,$day,$month,$year,$wday,$yday,$isdst) = localtime($starttime);
			$areatime = (ceil(($starttime + $areahour*60)/600))*600;//$areahour已改为按分钟计算，ceil是为了让禁区分钟为10的倍数
			$plsnum = sizeof($plsinfo);
			$arealist = range(1,$plsnum-1);
			shuffle($arealist);
			array_unshift($arealist,0);
			$areanum = 0;
			$hack = 0;
			//movehtm($areatime);
		}
	}
	
	function movehtm($atime = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//愚蠢的movehtm函数已经被移除…… 现在move.htm和areainfo.htm都由模板自动生成
		return;
	}

	function get_next_areadata_html()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		$areadata='';
		if(!$atime){
			$atime = $areatime;
		}
		$timediff = $atime - $now;
		if($timediff > 43200){//如果禁区时间在12个小时以后则显示其他信息
			$areadata .= '距离下一次禁区还有12个小时以上';
		}else{
			if($areanum < count($plsinfo)) {
				$at= getdate($atime);
				$nexthour = $at['hours'];$nextmin = $at['minutes'];
				while($nextmin >= 60){
					$nexthour +=1;$nextmin -= 60;
				}
				if($nexthour >= 24){$nexthour-=24;}
				$areadata .= "<b>{$nexthour}时{$nextmin}分：</b> ";
				for($i=1;$i<=$areaadd;$i++) {
					$areadata .= '&nbsp;'.$plsinfo[$arealist[$areanum+$i]].'&nbsp;';
				}
			}
			if($areanum+$areaadd < count($plsinfo)) {
				$at2= getdate($atime + $areahour*60);
				$nexthour2 = $at2['hours'];$nextmin2 = $at2['minutes'];
				while($nextmin2 >= 60){
					$nexthour2 +=1;$nextmin2 -= 60;
				}
				if($nexthour2 >= 24){$nexthour2-=24;}
				$areadata .= "；<b>{$nexthour2}时{$nextmin2}分：</b> ";
				for($i=1;$i<=$areaadd;$i++) {
					$areadata .= '&nbsp;'.$plsinfo[$arealist[$areanum+$areaadd+$i]].'&nbsp;';
				}
			}
			if($areanum+$areaadd*2 < count($plsinfo)) {
				$at3= getdate($atime + $areahour*120);
				$nexthour3 = $at3['hours'];$nextmin3 = $at3['minutes'];
				while($nextmin3 >= 60){
					$nexthour3 +=1;$nextmin3 -= 60;
				}
				if($nexthour3 >= 24){$nexthour3-=24;}
				$areadata .= "；<b>{$nexthour3}时{$nextmin3}分：</b> ";
				for($i=1;$i<=$areaadd;$i++) {
					$areadata .= '&nbsp;'.$plsinfo[$arealist[$areanum+$areaadd*2+$i]].'&nbsp;';
				}
			}
		}
		echo $areadata;
	}
	
	function updategame()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		$chprocess();//先判定开始游戏和反挂机
		
		if (($gamestate > 10)&&($now > $areatime)) {//判定增加禁区
			while($now>$areatime){
				$o_areatime = $areatime;
				$areatime += $areahour*60;
				add_once_area($o_areatime);
				$areawarn = 0;
			}
		}elseif(($gamestate > 10)&&($now > $areatime - $areawarntime)&&(!$areawarn)){//判定警告增加禁区
			areawarn();
		}
		
		if($gamestate == 20) {
			$arealimit = $arealimit > 0 ? $arealimit : 1; 
			if(($validnum <= 0)&&($areanum >= $arealimit*$areaadd)) {//判定无人参加并结束游戏
				\sys\gameover($areatime-$areahour*60+1,'end4');
			} elseif(($areanum >= $arealimit*$areaadd) || ($validnum >= $validlimit)) {//判定游戏停止激活
				$gamestate = 30;
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		if($news == 'death11') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因滞留在<span class=\"red\">禁区【{$plsinfo[$c]}】</span>死亡</li>";
		
		if($news == 'addarea') {
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
