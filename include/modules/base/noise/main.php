<?php

namespace noise
{
	function init() {}
	
	function pre_act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','noise','logger','player'));
		if($hp > 0){
			//显示枪声信息
			if(($now <= $noisetime+$noiselimit)&&$noisemode&&($noiseid!=$pid)&&($noiseid2!=$pid)) {
				if(($now-$noisetime) < 60) {
					$noisesec = $now - $noisetime;
					$log .= "<span class=\"yellow b\">{$noisesec}秒前，{$plsinfo[$noisepls]}传来了{$noiseinfo[$noisemode]}。</span><br>";
				} else {
					$noisemin = floor(($now-$noisetime)/60);
					$log .= "<span class=\"yellow b\">{$noisemin}分钟前，{$plsinfo[$noisepls]}传来了{$noiseinfo[$noisemode]}。</span><br>";
				}
			}
		}
		$chprocess();
	}
	
	function addnoise($where, $typ, $pid1 = -1, $pid2 = -1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		$noisetime = $now;
		$noisepls = $where;
		$noisemode = $typ;
		$noiseid = $pid1;
		$noiseid2 = $pid2;
		\sys\save_combatinfo();
	}
}

?>
