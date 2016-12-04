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
		$s = \skillbase\skill_getvalue(1000,'step');
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
	
	function tutorial_checknpc($atype,$asub,$ateam){//判断教程NPC的存在，如不存在则增加。如果恰好两个同type不同sub的NPC的名字还一样，这里会判断出错，所以在写config时尽量避免
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','addnpc','logger'));
		$aname = get_addnpclist()[$atype]['sub'][$asub]['name'];
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type='$atype' AND name='$aname' AND teamID='$ateam'");
		if(!$db->num_rows($result)){//不存在的情况下才addnpc，否则直接跳过，防止有人卡在这一步导致npc迅速增殖
			$apid = \addnpc\addnpc($atype,$asub,1);
			$log .= "编号为$apid";
			//通过队伍名来储存玩家绑定信息
			$t = $db->query("UPDATE {$tablepre}players SET teamID='$ateam' WHERE pid='$apid'");
			if($t){$log .= "更新成功$t";}
		}else{
			$apid = $db->fetch_array($result)['pid'];
		}
		return $apid;
	}
	
	function tutorial_movenpc($mtype,$msub,$mteam,$moveto = -1){//移动教程NPC。$moveto小于零则用当前玩家的pls
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','addnpc','logger'));
		$mpid = tutorial_checknpc($mtype,$msub,$mteam);
		if(!$mpid){ $log.='NPC生成出错，请检查代码。';return; }
		if($moveto < 0){$moveto = $pls;}
		$db->query("UPDATE {$tablepre}players SET pls='$moveto' WHERE pid='$mpid'");
		return $mpid;
	}
	
	function act(){//教程模式下的continue依赖于skill1000，其他模式下如果有别的需求可以在这里扩展
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if($gametype == 17) {
			$ct = get_current_tutorial();
			if(isset($ct['obj2']['addnpc'])){//判定是否需要addnpc
				$atype = $ct['obj2']['addnpc'];
				$asub = $ct['obj2']['asub'];
				$ateam = $pid;
				tutorial_checknpc($atype,$asub,$ateam);
			}
			if(in_array($command, Array('team'))) {//部分行动暂时限制掉
				$log .= '<span class="red">教程模式不能做出这一指令，请按教程提示操作！<span><br>';
				return;
			} elseif($ct['object'] != $command && $ct['object'] !=  'any' && $ct['object'] != 'back' && $ct['object'] != 'itemget'){//一般行动不限制死
				$log .= '<span class="yellow">请按教程提示操作！</span><br>';
			} 		
			if ($command == 'continue' || $ct['object'] ==  'back'|| $ct['object'] ==  'any'){
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
	
	function get_addnpclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','addnpc','tutorial'));
		if($gametype == 17){
			$anpcinfo = $tnpcinfo;
			return $anpcinfo;
		}
		return $chprocess();
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
			if($schmode == 'search' && ($ct['object'] == 'search' || $ct['object'] == 'itemget'|| $ct['object'] == 'back')){//需要探索时必定发现
				if(isset($ct['obj2']['itm'])){//判定必定发现道具
					discover_item();
					return;
				}elseif(isset($ct['obj2']['meetnpc'])){//判定必定发现NPC
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
			if($ct['object'] == 'search'|| $ct['object'] == 'itemget' && isset($ct['obj2']['itm'])){
				$itm0 = $itmk0 = $itmsk0 = '';
				$itme0 = $itms0 = 0;
				$itm0=$ct['obj2']['itm'];
				$itmk0=$ct['obj2']['itmk'];
				$itme0=$ct['obj2']['itme'];
				$itms0=$ct['obj2']['itms'];
				$itmsk0=$ct['obj2']['itmsk'];
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
	
	function discover_player(){
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','player','logger','metman'));
		if($gametype == 17){
			$ct = get_current_tutorial();			
			if($ct['object'] == 'search'|| $ct['object'] == 'back' && isset($ct['obj2']['meetnpc'])){
				$nid = tutorial_movenpc($ct['obj2']['meetnpc'],$ct['obj2']['meetsub'],$pid);//判定，顺便把NPC移到玩家所在地点
				\enemy\meetman($nid);
				return;
			}			
		}
		$chprocess();
	}
	
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman','enemy','tutorial'));
		if($gametype == 17){
			$ct = get_current_tutorial();		
			\player\update_sdata();
			$edata=\player\fetch_playerdata_by_pid($sid);
			if ($edata['hp']>0){
				extract($edata,EXTR_PREFIX_ALL,'w');
				if(isset($ct['obj2']['active']) && $ct['obj2']['active']){//判定玩家主动攻击
					$action = 'enemy'.$edata['pid'];
					\enemy\findenemy($edata);
					return;
				} elseif(isset($ct['obj2']['active']) && !$ct['obj2']['active']) {//判定玩家遭受攻击
					\enemy\battle_wrapper($edata,$sdata,0);
					return;
				}//没设定active则放弃这一段判定，正常判定meetman
			}
		}
		return $chprocess($sid);
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
			\skillbase\skill_setvalue(1000,'step',$ct['next']);
			//$log.='教程推进到下一阶段。这句话最终版应该删掉。<br>';
		}
		return;
	}
	
	function addnews($t = 0, $n = '',$a='',$b='',$c = '', $d = '', $e = '') {//重载addnpc太特么烦了，干脆改addnews
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gametype == 17 && $n=='addnpc'){
			return;
		}
		return $chprocess($t, $n, $a, $b, $c, $d, $e);
	}
}

?>