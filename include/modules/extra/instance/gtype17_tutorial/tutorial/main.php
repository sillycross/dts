<?php

namespace tutorial
{
	function init() {}
	
	function init_current_tutorial(){//主要是教程的界面处理
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($gametype != 17){
			return Array('在非教程模式下试图处理教程讯息，请检查代码。<br>');
		}
		$ct = get_current_tutorial();
		if(!is_array($ct)) {
			return Array('教程参数或代码错误，请检查tutorial模块代码<br>');
		}
		if(!empty($ct['pulse'])) {//闪烁指令
			$effect['pulse'][] = $ct['pulse'];
			}
		$r = Array(
			$ct['tips'],
			$ct['object']
		);	
		return $r;
	}
	
	function get_current_tutorial(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill1000','logger'));
		$s = \skillbase\skill_getvalue(1000,'step',$pa);
		//$log .= '目前的step为'.$s;
		if (!$s) {
			return false;
		}
		else {return get_tutorial_setting($s);}
	}
	
	function get_tutorial_setting($no){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','tutorial','logger'));
		if($no < 0){
			$log.='教程参数小于0，将重置至10<br>';
			$no = 10;
		}elseif(!isset($tutorialsetting[$no])){
			$log.='教程参数不存在，将重置至70<br>';
			$no = 70;
		}
		return $tutorialsetting[$no];
	}
	
	function act(){//教程模式下的continue依赖于skill1000，其他模式下如果有别的需求可以在这里扩展
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if($gametype == 17) {
			$ct = get_current_tutorial();
			if(in_array($command, Array('team'))) {//部分行动暂时限制掉
				$log .= '<span class="red">教程模式不能做出这一指令，请按教程提示操作！<span><br>';
				return;
			} elseif($ct['object'] != $command && $ct['object'] !=  'any' && $ct['object'] != 'back' && $ct['object'] != 'itemget'){//一般行动不限制死
				$log .= '<span class="yellow">请按教程提示操作！</span><br>';
			} 		
			if ($command == 'continue' || $ct['object'] ==  'any'){
				tutorial_forward_process();
				return;
			}
		}		
		return $chprocess();
	}
	
	function move($moveto = 99) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','itemshop'));
		$opls = $pls;
		$chprocess($moveto);
		if($gametype == 17) {
			$ct = get_current_tutorial();
			if($ct['object'] == 'move'){
				if(($ct['obj2'] == 'leave' && $opls != $pls) || ($ct['obj2'] == 'shop' && check_in_shop_area($pls))){
					tutorial_forward_process();
				}				
			}
		}
		return;
	}
	
	function search(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		$chprocess();
		if($gametype == 17) {
			$ct = get_current_tutorial();
			if($ct['object'] == 'search'){
				tutorial_forward_process();
			}
		}
		return;
	}
	
	function discover($schmode) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if($gametype == 17){
			$ct = get_current_tutorial();
			//$log .= $schmode.' '.$ct['object'];
			if($schmode == 'move'){//教程模式下，阻止移动时遇到意料之外的事件
				$log .= "但是什么都没有发现。<br>";
				return;
			}
			if($schmode == 'search' && ($ct['object'] == 'search' || $ct['object'] == 'itemget')){//需要探索时必定发现
				if(is_array($ct['obj2']) && isset($ct['obj2']['itm'])){//判定必定发现道具
					discover_item();
					return;
				}elseif(is_array($ct['obj2']) && isset($ct['obj2']['name'])){//判定必定发现NPC
					discover_player();
					return;
				}
				
			}
		}
		$chprocess($schmode);
	}
	
	function discover_item(){//绕过数据库伪造一个道具，存在代码错误的情况下可以无限刷道具的。
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmain'));
		if($gametype == 17){
			$ct = get_current_tutorial();
			if($ct['object'] == 'search'|| $ct['object'] == 'itemget'){
				$itm0 = $itmk0 = $itmsk0 = '';
				$itme0 = $itms0 = 0;
				if(is_array($ct['obj2']) && isset($ct['obj2']['itm'])){
					$itm0=$ct['obj2']['itm'];
					$itmk0=$ct['obj2']['itmk'];
					$itme0=$ct['obj2']['itme'];
					$itms0=$ct['obj2']['itms'];
					$itmsk0=$ct['obj2']['itmsk'];
				}
				$tpldata['itmk0_words'] = \itemmain\parse_itmk_words($itmk0);
				$tpldata['itmsk0_words'] = \itemmain\parse_itmsk_words($itmsk0);
				ob_clean();
				include template(MOD_TUTORIAL_TUTORIAL_ITEMFIND);
				$cmd = ob_get_contents();
				ob_clean();
				return;
			}
		}
		$chprocess();
	}
	
	function itemget() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$chprocess();
		if($gametype == 17) {
			$ct = get_current_tutorial();
			if($ct['object'] == 'itemget'){
				tutorial_forward_process();
			}
		}
		return;
	}
	
	function discover_player(){//绕过数据库伪造一个NPC，这个需要改的地方就多了。
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','player','logger','metman'));
		if($gametype == 17){
			$ct = get_current_tutorial();			
			if($ct['object'] == 'search'|| $ct['object'] == 'back'){
				$edata = $ct['obj2'];
				metman\meetenemy_by_edata($edata);
				return;
			}			
		}
		$chprocess();
	}
	
	function meetenemy_by_edata($edata){//重定义了一个遇敌函数
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman','enemy'));		
		\player\update_sdata();			
		if ($edata['hp']>0){
			extract($edata,EXTR_PREFIX_ALL,'w');
			if ($edata['active']) {
				$action = 'enemy'.$edata['pid'];
				findenemy($edata);
				return;
			} else {
				battle_wrapper($edata,$sdata,0);
				return;
			}
		}else {
			$action = 'corpse'.$sid;
			findcorpse($edata);
			return;
		}
		return $chprocess();
	}
	
	function tutorial_forward_process(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$ct = get_current_tutorial();
		if($ct['next'] < 0){//游戏结束判定
			$log.='教程结束。这句话最终版应该删掉。<br>';
			$url = 'end.php';
			\sys\gameover ( $now, 'end9', $name );
		}else{
			\skillbase\skill_setvalue(1000,'step',$ct['next'],$pa);
			//$log.='教程推进到下一阶段。这句话最终版应该删掉。<br>';
		}
		return;
	}
}

?>