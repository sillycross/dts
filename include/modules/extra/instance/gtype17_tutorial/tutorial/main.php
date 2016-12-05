<?php

namespace tutorial
{
	function init() {}
	
	function init_current_tutorial(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($gametype != 17){
			return Array('在非教程模式下试图处理教程讯息，请检查代码。<br>');
		}
		list($s, $tprog) = get_current_tutorial_step();
		$ct = get_tutorial();
		if(!is_array($ct)) {
			return Array('教程参数或代码错误，请检查tutorial模块代码<br>');
		}
		if(!empty($ct['pulse'])) {//闪烁指令
			$effect['pulse'][] = $ct['pulse'];
		}
		//当前obj是search并且将要调用itemfind battlecmd或者battleresult时应该显示下一个tips（search不存在过程）
		//此外，如果tprog为真，则显示对应的prog
		$l = $command;
		if($ct['object'] == 'search' && $command == 'search' && (isset($ct['obj2']['meetnpc']) || isset($ct['obj2']['itm']))){
			$nct = get_tutorial_setting($ct['next']);
			$r = Array(
				$nct['tips']."分歧111111111111".$l,
				$ct['object']
			);
		}elseif($tprog && $ct['prog']){
			$r = Array(
				$ct['prog']."分歧222222222222".$l,
				$ct['object']
			);
		}else{
			$r = Array(
				$ct['tips']."分歧33333333333333".$l,
				$ct['object']
			);
		}
		return $r;
	}
	
	function get_tutorial(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill1000','logger'));
		list($s, $tprog) = get_current_tutorial_step();
		//$log .= '目前的step为'.$s;
		if (!$s) {
			return false;
		}
		else {			
			return get_tutorial_setting($s);
		}		
	}
	
	function get_current_tutorial_step(){//成功的step必须是10的倍数，否则认为卡在半途
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill1000','logger'));
		$s = \skillbase\skill_getvalue(1000,'step');
		$tprog = false;
		if($s % 10){//fail
			$s = $s - ($s % 10);
			$tprog = true;
		}
		return Array($s, $tprog);
	}
	
	function get_tutorial_setting($no){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','tutorial','logger'));
		if($no < 0){
			$log.='教程参数小于0，将重置至10<br>';
			$no = 10;
		}elseif(!isset($tutorialsetting[$no])){
			$log.='教程参数不存在，将重置至70<br>';
			$no = 10;
		}
		return $tutorialsetting[$no];
	}
	
	function tutorial_forward_process($tp = 0){//0正常，1推进一半
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$ct = get_tutorial();
		if(!$tp){
			if($ct['next'] < 0){//游戏结束判定
				$log.='教程结束。这句话最终版应该删掉。<br>';
				$url = 'end.php';
				\sys\gameover ( $now, 'end9', $name );
			}else{
				\skillbase\skill_setvalue(1000,'step',$ct['next']);
				//$log.='教程推进到下一阶段。这句话最终版应该删掉。<br>';
			}
		}else{
			\skillbase\skill_setvalue(1000,'step',$ct['next'] - 5);
		}		
		return;
	}
	
	function tutorial_checknpc($atype,$asub,$ateam){//判断教程NPC的存在，如不存在则增加。如果恰好两个同type不同sub的NPC的名字还一样，这里会判断出错，所以在写config时尽量避免
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','addnpc','logger'));
		$aname = get_addnpclist()[$atype]['sub'][$asub]['name'];
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type='$atype' AND name='$aname' AND teamID='$ateam'");
		if(!$db->num_rows($result)){//不存在的情况下才addnpc，否则直接跳过，防止有人卡在这一步导致npc迅速增殖
			$apid = \addnpc\addnpc($atype,$asub,1);
			//通过队伍名来储存玩家绑定信息
			$t = $db->query("UPDATE {$tablepre}players SET teamID='$ateam' WHERE pid='$apid'");
		}else{
			$apid = $db->fetch_array($result)['pid'];
		}
		return $apid;
	}
	
	function tutorial_movenpc($mtype,$msub,$mteam,$moveto = -1,$mpid=0){//移动教程NPC。$moveto小于零则用当前玩家的pls
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if($mpid == 0){
			$mpid = tutorial_checknpc($mtype,$msub,$mteam);
			if(!$mpid){ $log.='NPC生成出错，请检查代码。';return; }
		}
		if($moveto < 0){$moveto = $pls;}
		$db->query("UPDATE {$tablepre}players SET pls='$moveto' WHERE pid='$mpid'");
		return $mpid;
	}
	
	function tutorial_changehp_npc($ctype,$csub,$cteam,$chp,$cpid=0){//把NPC的HP和MHP变成指定数值
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if($cpid == 0){
			$cpid = tutorial_checknpc($ctype,$csub,$cteam);
			if(!$cpid){ $log.='NPC生成出错，请检查代码。';return; }
		}		
		if($chp < 0){$chp = 1;}
		$db->query("UPDATE {$tablepre}players SET hp='$chp', mhp='$chp' WHERE pid='$cpid'");
		return $cpid;
	}
	
	function act(){//教程模式下的continue依赖于skill1000，其他模式下如果有别的需求可以在这里扩展
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if($gametype == 17) {
			$ct = get_tutorial();
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
				//$log .= '<span class="yellow">请按教程提示操作！</span><br>';
			} 		
			if ($command == 'continue' || $ct['object'] ==  'any'){
				tutorial_forward_process();
				return;
			}else{
				tutorial_forward_process(1);//推进一半
			}
//			elseif (strpos($ct['object'],'inf') === 0 && !$inf){//防呆设计
//				$log .= "看来你比较熟练呢，我们继续。<br>";
//				tutorial_forward_process();
//				return;
//			}
		}		
		return $chprocess();
	}
	
	function move($moveto = 99) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','itemshop'));
		$opls = $pls;
		$chprocess($moveto);
		if($gametype == 17) {
			$ct = get_tutorial();
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
		if($gametype == 17) {//为了保证执行逻辑，在显示了结果之后才推进进程，造成itemfind battlecmd battleresult三个界面必须另外推进1步
			$ct = get_tutorial();
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
			$ct = get_tutorial();
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
			$ct = get_tutorial();
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
				include template(MOD_TUTORIAL_TUTORIAL_ITEMFIND);//顺便把模板改了
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
			$ct = get_tutorial();
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
			$ct = get_tutorial();			
			if($ct['object'] == 'search'|| $ct['object'] == 'back' && isset($ct['obj2']['meetnpc'])){
				$nid = tutorial_movenpc($ct['obj2']['meetnpc'],$ct['obj2']['meetsub'],$pid);//判定，顺便把NPC移到玩家所在地点
				if(isset($ct['obj2']['changehp'])){//改动NPC血量
					$n = tutorial_changehp_npc($ct['obj2']['meetnpc'],$ct['obj2']['meetsub'],$pid,1,$nid);
				}
				\enemy\meetman($nid);
				return;
			}			
		}
		$chprocess();
	}
	
	function get_battlecmd_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman','tutorial'));
		if($gametype == 17) return MOD_TUTORIAL_TUTORIAL_BATTLECMD;
		$chprocess();
	}
	
	function get_battleresult_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman','tutorial'));
		if($gametype == 17) return MOD_TUTORIAL_TUTORIAL_BATTLERESULT;
		$chprocess();
	}
	
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman','enemy','tutorial'));
		if($gametype == 17){
			$ct = get_tutorial();		
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
	
	function get_hitrate(&$pa,&$pd,$active){//强制遭遇NPC的教程里，如果必定命中，那么命中率100%
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','tutorial'));
		if($gametype == 17){
			$ct = get_tutorial();
			if(isset($ct['obj2']['always_hit']) && $ct['obj2']['always_hit']){
				return 100;
			}
		}
		return $chprocess($pa,$pd,$active);
	}
	
//	function post_weapon_strike_events(&$pa, &$pd, $active, $is_hit){//强制遭遇NPC的教程里强制受伤
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','tutorial'));
//		if($gametype == 17){
//			$ct = get_tutorial();
//			if(isset($ct['obj2']['meetnpc']) && isset($ct['obj2']['inf'])){
//				check_weapon_inf($pa, $pd, $active);
//				return;
//			}
//		}
//		return $chprocess($pa, $pd, $active, $is_hit);
//	}
	
	function apply_weapon_inf(&$pa, &$pd, $active){//强制遭遇NPC的教程里强制受伤
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','wound','logger','tutorial'));
		if($gametype == 17 && $active==0){
			$ct = get_tutorial();
			if(isset($ct['obj2']['meetnpc']) && isset($ct['obj2']['inf'])){
				\wound\apply_weapon_wound_real($pa, $pd, $active, $ct['obj2']['inf']);	
				return;
			}
		}
		return $chprocess($pa, $pd, $active);
	}
	
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key){//强制遭遇NPC的教程里强制异常状态
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ex_dmg_att','wound','logger'));
		if($gametype == 17 && $active==0){
			$ct = get_tutorial();
			if(isset($ct['obj2']['meetnpc']) && isset($ct['obj2']['ex_inf'])){
				$infkey = array_search($ex_inf[$ct['obj2']['ex_inf']], $infskillinfo);
				$log .= "并造成你{$infname[$infkey]}了！";
				\wound\get_inf($infkey,$pd);
				return;
			}
		}
		return $chprocess($pa, $pd, $active, $key);
	}
	
//	function check_weapon_inf(&$pa, &$pd, $active){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('wound'));
//		if($gametype == 17){
//			$ct = get_tutorial();
//			if(isset($ct['obj2']['meetnpc']) && isset($ct['obj2']['inf'])){
//				$pa['attack_wounded_'.$ct['obj2']['inf']]+=1000;
//				return;
//			}
//		}
//		return $chprocess($pa, $pd, $active);
//	}
	
	function itemuse(&$theitem) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger','tutorial'));		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		$chprocess($theitem);
		if($gametype == 17) {
			$ct = get_tutorial();
			if(strpos($ct['object'] ,'itm')===0 && isset($ct['obj2']['itmk']) && in_array($itmk,$ct['obj2']['itmk'])){//按教程使用补给之后推进教程进度
				tutorial_forward_process();
			}
		}
		return;
	}	
	
	function heal_inf($hurtposition, &$pa = NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','tutorial'));
		$chprocess($hurtposition, $pa);
		if($gametype == 17) {
			$ct = get_tutorial();
			if(strpos($ct['object'] ,'inf')===0 && $hurtposition = substr($ct['object'],3,1)){//按教程治疗伤口之后推进教程进度
				tutorial_forward_process();
			}
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