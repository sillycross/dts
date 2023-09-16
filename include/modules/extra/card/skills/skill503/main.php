<?php

namespace skill503
{
	
	function init() 
	{
		define('MOD_SKILL503_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[503] = '幻禁';
	}
	
	function acquire503(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$pa['type'] && $pa['hp'] > 0) {
			eval(import_module('sys'));
			//设置一个全局变量，代表拥有这个技能的玩家名
			if(empty($gamevars['skill503_pnlist'])) $pnlist = array();
			else $pnlist = decode_pnlist503($gamevars['skill503_pnlist']);
			$pnlist[] = $pa['name'];
			$gamevars['skill503_pnlist'] = encode_pnlist503($pnlist);
			\sys\save_gameinfo();
		}
		\skillbase\skill_setvalue(503,'hack2_r','2',$pa);
		\skillbase\skill_setvalue(503,'hack3_r','2',$pa);
	}
	
	function lost503(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//取消上述全局变量
		if(empty($gamevars['skill503_pnlist'])) return;
		else $pnlist = decode_pnlist503($gamevars['skill503_pnlist']);
		$pnlist = array_diff($pnlist, array($pa['name']));
		$gamevars['skill503_pnlist'] = encode_pnlist503($pnlist);
		\sys\save_gameinfo();
	}
	
	function encode_pnlist503($pnlist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return implode('|',$pnlist);
	}
	
	function decode_pnlist503($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return explode('|', $str);
	}
	
	function check_unlocked503(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_uee_deathlog () {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		eval(import_module('sys'));
		if(!empty($gamevars['skill503_pnlist'])) {
			$ret = '<span class="evergreen b">“也许咱应该断定你上网成瘾？”</span><br>';
		}
		return $ret;
	}
	
	//自己使用移动PC的成功率是100%
	function calculate_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(503)) return 100;
		return $chprocess();
	}
	
	//自己失败率是0，所有其他玩家PC失败概率上升
	//假设两个本技能玩家在场，则两个都是0
	function calculate_post_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		list($v1, $v2) = $chprocess();
		eval(import_module('sys','player'));
		if(\skillbase\skill_query(503) && check_unlocked503()) {
			$v1 = $v2 = 0;
		}
		elseif(!empty($gamevars['skill503_pnlist'])) {
			$pnlist = decode_pnlist503($gamevars['skill503_pnlist']);
			if(in_array($name,$pnlist)) {
				$v1 = $v2 = 0;
			}else{
				$v2 = 30;
			}
		}
		return array($v1,$v2);
	}
	
	//移动PC变为三项指令
	function itemuse_uee_core($itmn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','input'));
		if(!\skillbase\skill_query(503) || !check_unlocked503()) return $chprocess($itmn);
		$hack2_r = \skillbase\skill_getvalue(503,'hack2_r');
		$hack3_r = \skillbase\skill_getvalue(503,'hack3_r');
		if(empty($ueecmd)) {
			$ueen = $itmn;
			ob_start();
			include template(MOD_SKILL503_UEECMD);
			$cmd = ob_get_contents();
			ob_end_clean();
		}else{
			if(1==$ueecmd){//解除禁区，同原移动PC
				return $chprocess($itmn);
			}elseif(2==$ueecmd){//增加禁区
				$interval = \map\get_area_interval()/2;
				if($hack2_r<=0){
					$log .= '<span class="red b">你本局游戏中已经不能再提前禁区了！</span><br>';
					return;
				}elseif($areatime < $now + 60){
					$log .= '<span class="red b">下一次禁区时间已经很近了，无法干扰！</span><br>';
					return;
				}elseif($now < $areatime - $interval*60){
					$log .= '<span class="red b">距离下次禁区时间还有'.$interval.'分钟以上，无法干扰！</span><br>';
					return;
				}elseif($gamestate >= 40){
					$log .= '<span class="red b">连斗后不能再篡改禁区了！</span><br>';
					return;
				}
				$areatime = $now + 60;
				save_gameinfo();
				$log .= '<span class="yellow b">干扰成功，下一次禁区将在60秒后到来！</span><br>';
				\skillbase\skill_setvalue(503,'hack2_r',$hack2_r - 1);
				\sys\systemputchat($now,'hack2');
				addnews($now,'hack2',$name);
			}elseif(3==$ueecmd){//打乱之后的禁区
				if($hack3_r<=0){
					$log .= '<span class="red b">你本局游戏中已经不能再打乱禁区了！</span><br>';
					return;
				}elseif($areatime < $now + 60){
					$log .= '<span class="red b">下一次禁区时间已经很近了，无法干扰！</span><br>';
					return;
				}elseif($gamestate >= 40){
					$log .= '<span class="red b">连斗后不能再篡改禁区了！</span><br>';
					return;
				}
				
//				$tmp_areanum = $hack ? 0 : $areanum;
//				$n_arealist = array_slice($arealist, 1 + $tmp_areanum);
//				shuffle($n_arealist);
//				$arealist = array_merge(array_slice($arealist,0,1 + $tmp_areanum), $n_arealist);
//				
//				if($hack) $log .= '<span class="yellow b">干扰成功，你打乱了未来的禁区顺序！</span><br>';
//				else $log .= '<span class="yellow b">干扰成功，你打乱了全部禁区的顺序！</span><br>';
				
//				eval(import_module('map'));
//				$log .= '新禁区顺序列表如下：';
//				foreach($arealist as $av){
//					$log .= $plsinfo[$av].' ';
//				}

				$n_arealist = array_slice($arealist, 1 + $areanum);
				shuffle($n_arealist);
				$arealist = array_merge(array_slice($arealist,0,1 + $areanum), $n_arealist);
				
				$log .= '<span class="yellow b">干扰成功，你打乱了未来的禁区顺序！</span><br>';
				\skillbase\skill_setvalue(503,'hack3_r',$hack3_r - 1);
				\sys\systemputchat($now,'hack3');
				save_gameinfo();
				addnews($now,'hack3',$name);
			}
			return true;
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'hack2') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}成功干扰了幻境的运转，下一次禁区将在60秒后到来！</span></li>";
		elseif($news == 'hack3') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}成功干扰了幻境的运转，打乱了未来的禁区顺序！</span></li>";
	
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>