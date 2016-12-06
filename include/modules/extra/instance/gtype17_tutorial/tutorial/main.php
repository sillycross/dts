<?php

namespace tutorial
{
	function init() {}
	
	//提示部分的初始化函数，只在template页面中调用
	function init_current_tutorial(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($gametype != 17){
			return Array('在非教程模式下试图处理教程讯息，请检查代码。<br>');
		}
		list($tstep, $tprog) = get_current_tutorial_step();
		$ct = get_tutorial();
		if(!is_array($ct)) {
			return Array('教程参数或代码错误，请检查tutorial模块代码<br>');
		}
		if(!empty($ct['pulse'])) {//闪烁指令，$effect是来自sys的全局函数，界面的具体实现可以在game.js里shwData()函数调整
			$effect['pulse'][] = $ct['pulse'];
		}
		//当前obj是search并且将要调用itemfind battlecmd battleresult时应该显示下一个tips（search不存在过程所以无视后一点）
		//之外，如果tprog为真，则显示对应的prog提示而非tips提示
		$l = $command;
		if($ct['object'] == 'search' && $command == 'search' && (isset($ct['obj2']['meetnpc']) || isset($ct['obj2']['itm']))){
			$nct = get_tutorial_setting($ct['next']);
			$r = Array(
				$nct['tips']."分歧A；命令：".$l,
				$ct['object']
			);
		}else
		if($tprog && $ct['prog']){
			$r = Array(
				$ct['prog']."分歧B；命令：".$l,
				$ct['object']
			);
		}else{
			$r = Array(
				$ct['tips']."分歧C；命令：".$l,
				$ct['object']
			);
		}
		return $r;
	}
	
	//获得当前玩家所处教程阶段的各项参数，目前只是一个外壳，供其他函数调用
	function get_tutorial(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill1000','logger'));
		if($gametype != 17){
			exit("Fatal error（伪）：在非教程模式下试图调用教程函数！");
		}
		list($tstep, $tprog) = get_current_tutorial_step();
		//$log .= '目前的step为'.$tstep;
		if (!$tstep) {
			return false;
		}
		else {			
			return get_tutorial_setting($tstep);
		}		
	}
	
	//获取当前教程阶段的函数，从skill1000中读取$step的值并作出判断
	//$step是10的倍数的场合是第一次进入这个阶段，否则在这个阶段已经提交过至少1次指令
	function get_current_tutorial_step(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill1000','logger'));
		$tstep = \skillbase\skill_getvalue(1000,'step');
		$tprog = \skillbase\skill_getvalue(1000,'prog');
		return Array($tstep, $tprog);
	}
	
	//根据玩家的$step读tutorial.config.php内的$tutorialsetting参数，目前也只是简单封装
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
	
	//使玩家教程阶段推进的函数，教程胜利也在此判断
	//$tp=0为正常推进，直接进到config设定的下一阶段，一般是+10
	//$tp=1为进行中，进到下一阶段-5的值。某几步玩家可能容易不按教程走，这个时候需要有提示
	function tutorial_forward_process($tp = 0){
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
				\skillbase\skill_setvalue(1000,'prog',0);
				//$log.='教程推进到下一阶段。这句话最终版应该删掉。<br>';
			}
		}else{
			\skillbase\skill_setvalue(1000,'prog',1);//推进一半
		}		
		return;
	}
	
	//判断教程NPC是否存在，如不存在则增加。
	//如果恰好两个NPC同type，不同sub，名字还一样，则这里会判断出错，所以在写config时应尽量避免
	function tutorial_checknpc($atype,$asub,$ateam){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','addnpc','logger'));
		$aname = get_addnpclist()[$atype]['sub'][$asub]['name'];
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type='$atype' AND name='$aname' AND teamID='$ateam'");
		if(!$db->num_rows($result)){//不存在的情况下才addnpc，否则直接跳过，防止有人卡在这一步导致npc迅速增殖
			$apid = \addnpc\addnpc($atype,$asub,1);
			//在npc的teamID字段储存对应的玩家pid，大房模式下这个NPC只有对应pid的玩家可以摸到
			$db->query("UPDATE {$tablepre}players SET teamID='$ateam' WHERE pid='$apid'");
		}else{
			$apid = $db->fetch_array($result)['pid'];
		}
		return $apid;
	}
	
	//移动教程NPC
	//$moveto小于零则用当前玩家的pls
	//如果给出了$mpid的值，那么跳过判定NPC存在的步骤
	function tutorial_movenpc($mtype,$msub,$mteam,$moveto = -1,$mpid=0){
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
	
	//把NPC的HP和MHP变成指定数值
	//$chp最小为1
	//如果给出了$cpid的值，那么跳过判定NPC存在的步骤
	function tutorial_changehp_npc($ctype,$csub,$cteam,$chp,$cpid=0){
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
	
	//接管act()，判定continue或者任意命令下的玩家教程阶段推进
	function act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','input'));
		if($gametype == 17) {
			$ct = get_tutorial();
			if(isset($ct['obj2']['addnpc'])){//判定是否需要addnpc
				$atype = $ct['obj2']['addnpc'];
				$asub = $ct['obj2']['asub'];
				$ateam = $pid;
				tutorial_checknpc($atype,$asub,$ateam);
			}
			if(in_array($command, Array('team'))) {//部分行动限制掉
				$log .= '<span class="red">教程模式不能做出这一指令，请按教程提示操作！<span><br>';
				return;
			} elseif($ct['object'] != $command && $ct['object'] !=  'any' && $ct['object'] != 'back' && $ct['object'] != 'itemget'){//一般行动不限死
				//$log .= '<span class="yellow">请按教程提示操作！</span><br>';
			}
			if (($sp_cmd == 'sp_shop' && $ct['object'] == 'sp_shop') || ($command == 'shop4' && $ct['object'] == 'shop4')){//打开商店的初级和次级页面直接推进
				tutorial_forward_process();
			}elseif ($command == 'continue' || $ct['object'] ==  'any'){//continue和any则直接推进，之后返回
				tutorial_forward_process();
				return;
			}elseif (($ct['object'] == 'inff' && strpos($inf,'f')===false) || ($ct['object'] == 'itm3' && strpos($inf,'p')===false) || ($ct['object'] == 'move' && $ct['obj2'] == 'shop' && \itemshop\check_in_shop_area($pls))){//防呆设计
				$log .= "看来你比较熟练呢，我们继续。<br>";
				tutorial_forward_process();
				return;
			}else{//否则判定推进一半
				tutorial_forward_process(1);
			}			
		}		
		return $chprocess();
	}
	
	//接管get_addnpclist()，教程房独有的addnpc数据
	function get_addnpclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','addnpc','tutorial'));
		if($gametype == 17){
			$anpcinfo = $tnpcinfo;
			return $anpcinfo;
		}
		return $chprocess();
	}
	
	//接管move()，只判定阶段推进
	function move($moveto = 99) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','itemshop'));
		$opls = $pls;
		$chprocess($moveto);
		if($gametype == 17) {
			$ct = get_tutorial();
			if($ct['object'] == 'move'){
				if(($ct['obj2'] == 'leave' && $opls != $pls) || ($ct['obj2'] == 'shop' && \itemshop\check_in_shop_area($pls))){
					tutorial_forward_process();
				}				
			}
		}
		return;
	}
	
	//接管search()，只判定阶段推进
	function search(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		$chprocess();
		if($gametype == 17) {
			//为了保证执行逻辑，在search本体执行完毕之后才推进，造成itemfind battlecmd battleresult三个界面必须单独设定显示内容（前面init_current_tutorial()）
			$ct = get_tutorial();
			if($ct['object'] == 'search'){
				tutorial_forward_process();
			}
		}
		return;
	}
	
	//接管discover()，玩家某几步必定发现NPC或者道具
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
			if($schmode == 'search' && (isset($ct['obj2']['itm']) || isset($ct['obj2']['meetnpc']))){//需要探索时必定发现
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
	
	//绕过数据库伪造一个道具。
	//如果玩家卡在这一步，可以无限刷道具。不过教程房里怎样都好。
	function discover_item(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmain'));
		if($gametype == 17){
			$ct = get_tutorial();
			if(isset($ct['obj2']['itm'])){
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
	
	//接管itemget()，判定推进
	function itemget() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$r = $chprocess();
		if($gametype == 17) {
			$ct = get_tutorial();
			if($ct['object'] == 'itemget'){
				tutorial_forward_process();
			}
		}
		return;
	}
	
	//接管discover_player()，对玩家遭遇敌人做数据准备
	//NPC绕过数据库的成本比直接addnpc还大，就老实addnpc并且discover了
	function discover_player(){
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','player','logger','metman'));
		if($gametype == 17){
			$ct = get_tutorial();			
			if(isset($ct['obj2']['meetnpc'])){
				$nid = tutorial_movenpc($ct['obj2']['meetnpc'],$ct['obj2']['meetsub'],$pid);//判定NPC是否存在，顺便把NPC移到玩家所在地点
				if(isset($ct['obj2']['changehp'])){//有设定时，改动NPC血量
					$n = tutorial_changehp_npc($ct['obj2']['meetnpc'],$ct['obj2']['meetsub'],$pid,1,$nid);
				}
				\enemy\meetman($nid);
				return;
			}			
		}
		return $chprocess();
	}
	
	//这3个是改对应的模板。注意教程模板内嵌套了默认的模板，所以不能改为变量或者直接改原模板，用常数反而最合适
	function get_battlecmd_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman','tutorial'));
		if($gametype == 17) return MOD_TUTORIAL_TUTORIAL_BATTLECMD;
		return $chprocess();
	}
	
	function get_battleresult_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman','tutorial'));
		if($gametype == 17) return MOD_TUTORIAL_TUTORIAL_BATTLERESULT;
		return $chprocess();
	}
	
	function get_corpse_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman','tutorial'));
		if($gametype == 17) return MOD_TUTORIAL_TUTORIAL_CORPSE;
		return $chprocess();
	}
	
	function get_sp_shop_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','tutorial'));
		if($gametype == 17) return MOD_TUTORIAL_TUTORIAL_SP_SHOP;
		return $chprocess();
	}
	
	function get_itemshop_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','tutorial'));
		if($gametype == 17) return MOD_TUTORIAL_TUTORIAL_SHOP;
		return $chprocess();
	}
	
	//接管meetman，主要是判定必定发现敌人和必定先制/被先制
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
	
	//接管get_hitrate()
	//如果设定遭遇NPC时必定命中，那么命中率100%
	function get_hitrate(&$pa,&$pd,$active){
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
	
	//接管apply_weapon_inf()
	//有设定时强制受伤，部位可在config内设置。此处暂时只设定玩家会受伤，而且会无视防具，教程需要。
	function apply_weapon_inf(&$pa, &$pd, $active){
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
	
	//接管check_ex_inf_infliction(），有设定时强制异常状态
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key){
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
	
	//接管itemuse()，主要为了推进
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
	
	//接管heal_inf()，主要为了推进
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
	
	//接管player_kill_enemy()，主要为了推进
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','tutorial'));
		if($gametype == 17) {
			$ct = get_tutorial();
			if($ct['object']=='kill' && $active){//玩家杀死敌人以后推进进度
				tutorial_forward_process();
			}
		}
		$chprocess($pa, $pd, $active);
		return;
	}
	
	//接管getcorpse_action()，主要为了推进
	function getcorpse_action(&$edata, $item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','tutorial'));
		$chprocess($edata, $item);
		if($gametype == 17) {
			$ct = get_tutorial();
			if($ct['object']=='money' && $item == 'money'){//玩家从尸体拿钱以后推进进度
				tutorial_forward_process();
			}
		}
		return;
	}
	
	//接管itembuy()，主要为了推进
	function itembuy($item,$shop,$bnum=1) {
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('sys','map','player','logger','itemmain','tutorial'));
		if($gametype == 17) {
			$ct = get_tutorial();
			$shopiteminfo = \itemshop\get_shopiteminfo($item);
			if($ct['object']=='itembuy' && $shopiteminfo['item'] == $ct['obj2']){//玩家购买特定名字的商品后推进进度
				tutorial_forward_process();
			}
		}
		return $chprocess($item,$shop,$bnum);
	}
	
	//阻止addnpc进行状况提示
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