<?php

namespace item_uu
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['U']='扫雷设备';
	}

	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(strpos($k,'U')===0) {
			$ret .= '使用后将随机扫除当前地区的陷阱，效果值累计不超过'.$e.'点。无法扫除某些特殊地雷';
		}
		return $ret;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ($itmk=='U') 
		{
			$log.="你使用了<span class=\"yellow b\">{$itm}</span>。";
			
			$amount = $itme;//一次能扫除的效果值上限
			$traps = $traps_swept_ids = $traps_swept_names = Array();
			$result = $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' AND itmk != 'TOc'");
			while($traparr = $db->fetch_array($result)){
				$traps[] = $traparr;
			}
			shuffle($traps);
			$i = 0;
			foreach($traps as $tv){
				if($tv['itme'] <= $amount) {//单雷效果值小于扫雷器的效果值，被扫除
					$traps_swept_ids[] = $tv['tid'];
					$traps_swept_names[] = $tv['itm'];
					$amount -= $tv['itme'];
					$i++;
				}
				
				if($i>=77) break;//最多一次扫除77个雷
			}
			
			if(!empty($traps_swept_ids)) {
				$delc = implode(',',$traps_swept_ids);
				$db->query("DELETE FROM {$tablepre}maptrap WHERE tid IN ($delc)");
			
				$traps_swept_names_log = $traps_swept_names_by_name = array();
				//分名字显示。其实也可以写在前面那段，但因为这里是后来加的，懒得改已经验证过的代码了，反正性能差距不会很大。
				foreach($traps_swept_names as $nv) {
					if(empty($traps_swept_names_by_name[$nv])) {
						$traps_swept_names_by_name[$nv] = 1;
					}else{
						$traps_swept_names_by_name[$nv] ++;
					}
				}
				foreach($traps_swept_names_by_name as $nk => $nv) {
					$traps_swept_names_log[] = $nv.'个'.$nk;
				}
				$traps_swept_names_log = implode('、', $traps_swept_names_log);
				if($itm=='☆混沌人肉探雷车★') $log.="远方传来一阵爆炸声，伟大的<span class=\"yellow b\">{$itm}</span>用生命和鲜血扫除了<span class=\"yellow b\">{$traps_swept_names_log}</span>。<br><span class=\"red b\">实在是大快人心啊！</span><br>";
				else $log.="远方传来一阵爆炸声，<span class=\"yellow b\">{$itm}</span>引爆并扫除了<span class=\"yellow b\">{$traps_swept_names_log}</span>。<br>";
				\sys\addnews ( 0, 'minesweep', $name, $itm, $traps_swept_names_log, $pls);
			}else{
				$log.="但是，没有触发任何陷阱。<br>";
			}

			\itemmain\itms_reduce($theitem);
			return;
		}
		
		$chprocess($theitem);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map'));
		
		if($news == 'minesweep') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}使用了{$b}，引爆并清除了{$plsinfo[$d]}的{$c}！</span></li>";	
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>