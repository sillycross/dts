<?php

namespace noise
{
	function init() {
		global $noisetime, $noisepls, $noiseid, $noiseid2, $noisemode;
//		$noisetime = $noisevars['noisetime'];
//		$noisepls = $noisevars['noisepls'];
//		$noiseid = $noisevars['noiseid'];
//		$noiseid2 = $noisevars['noiseid2'];
//		$noisemode = $noisevars['noisemode'];
	}
	
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
		
		eval(import_module('sys','noise'));
		$noisetime = $now;
		$noisepls = $where;
		$noisemode = $typ;
		$noiseid = $pid1;
		$noiseid2 = $pid2;
		\sys\save_combatinfo();
	}
	
	function load_gameinfo_post_work(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','noise'));
		$chprocess();
		foreach($noisevars as $key => $val){
			${$key} = $val;
		}
		return;
	}
	
	function gameinfo_audit(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','noise'));
		$chprocess();
		if(!$noisetime){$noisetime = 0;}
		if(!$noisepls){$noisepls = 0;}
		if(!$noiseid){$noiseid = 0;}
		if(!$noiseid2){$noiseid2 = 0;}
		if(!$noisemode){$noisemode = '';}
		return;
	}
	
	function reset_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('sys','noise'));
		//重设声音信息
		//$noisevars = array();
		$noisetime = 0;
		$noisepls = 0;
		$noiseid = 0;
		$noiseid2 = 0;
		$noisemode = '';
	}
	
	function save_gameinfo_prepare_work($ginfo, $ignore_room = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','noise'));
		//因为sys模块里的这个函数最后是要gencode的，数组处理必须先进行，而不能先$chprocess()。
		if(!is_array($ginfo['noisevars'])){
			//var_dump($ginfo['noisevars']);
			$ginfo['noisevars'] = array();
		}
		foreach(array('noisetime', 'noisepls', 'noiseid', 'noiseid2', 'noisemode') as $nv){
			$ginfo['noisevars'][$nv] = ${$nv};
		}
		return $chprocess($ginfo, $ignore_room);
	}
}

?>
