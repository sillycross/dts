<?php

namespace gtype17
{
	function init() {}

	//教程房强制让禁区时间变为42年以后，变相不禁区
	function rs_game($xmode = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','map'));
		$chprocess($xmode);
		
		if ($xmode & 2 && $gametype == 17) {
			//echo " - 禁区初始化 - ";
			list($sec,$min,$hour,$day,$month,$year,$wday,$yday,$isdst) = localtime($starttime);
			$areatime = $starttime + 1324512000;
		}
	}

	//教程房特殊的npcinfo
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','map','gtype17'));
		if ($gametype!=17) return $chprocess();		
		return $npcinfo_gtype17;
	}
	
	//教程房特殊的shopitem.config
	function get_shoplist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype!=17) return $chprocess();		
		$file = __DIR__.'/config/shopitem.config.php';
		$l = openfile($file);
		return $l;
	}
	
	//教程房特殊的mapitem.config
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype!=17) return $chprocess();
		$file = __DIR__.'/config/mapitem.config.php';
		$l = openfile($file);
		return $l;
	}
	
	//教程房特殊的trapitem.config
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype!=17) return $chprocess();
		$file = __DIR__.'/config/trapitem.config.php';
		$l = openfile($file);
		return $l;
	}

	//修改禁区显示方式，其实这个可以丢到主模块里
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
	//教程房不真正连斗
	function checkcombo(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gametype!=17) return $chprocess();
//		return;
	}
	
	//待调整
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($gametype==17){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');
				return;
			}
			if ($areanum>=$areaadd){//限时1禁
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
				$wdata = $db->fetch_array($result);
				$winner = $wdata['name'];
				\sys\gameover($atime,'end8',$winner);
				return;
			}
			\sys\rs_game(16+32);
			return;
		}
		$chprocess($atime);	
	}
}

?>