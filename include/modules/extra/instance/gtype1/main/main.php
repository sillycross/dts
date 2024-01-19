<?php

namespace gtype1
{
	function init() {
	}
	
	//除错模式固定卡片（软件测试工程师）
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$card = $chprocess($card);
		if (1==$gametype){
			$card=93;
		}
		return $card;
	}
	
	//除错模式自动选择工程师，禁止其他卡片
	function card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$card_disabledlist = $chprocess($card_disabledlist, $card_ownlist);
		if (1==$gametype)	//除错模式自动选择工程师
		{
			foreach($card_ownlist as $cv){
				if(93 != $cv) $card_disabledlist[$cv][]='e3';
			}
		}
		return $card_disabledlist;
	}
	
	//除错模式选卡界面特殊显示
	function card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		//标准模式自动选择挑战者
		list($cardChosen, $card_ownlist, $packlist, $hideDisableButton) = $chprocess($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
		
		if (1==$gametype)	//除错模式自动选择工程师
		{
			$cardChosen = 93;//自动选择软件测试工程师
			$card_ownlist[] = 93;
			$packlist[] = $cards[93]['pack'] = 'Testing Fan Club';
			$hideDisableButton = 0;
		}
		
		return array($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
	}
	
	function prepare_new_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (room_check_subroom($room_prefix)) return $chprocess();
		
		//获取3局之前的游戏类别
		$last3game=$gamenum-3;
		$res=$db->query("SELECT gametype FROM {$gtablepre}history WHERE gid='$last3game'");
		$gt=0;
		if ($db->num_rows($res)){
			$zz=$db->fetch_array($res); $gt=$zz['gametype'];
		}

		if (!$disable_event && $gt!=1){//开启活动&&最多连续3局
			list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
 			if ( ($wday==3 && $hour>=19 && $hour<21) || ($wday==6 && $hour>=15 && $hour<17) || ($wday==0 && $hour>=15 && $hour<17)){ //周三19点-21点；周六15点-17点；周日15点-17点
 				$gametype=1;
 			}
 		}
 		//由于现在admin指令也能修改$gametype，需要把修改$gametype和准备游戏数据分开处理
 		if(1 == $gametype) {
 			prepare_new_game_gtype1();
 		}
		$chprocess();
	}
	
	function get_uee_deathlog () {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		eval(import_module('sys'));
		if(1 == $gametype) $ret = '<span class="lightpink b">“这下必须动用权限了。”</span>——<span class="cyan b">薇娜·安妮茜</span><br>';
		return $ret;
	}
	
	//除错模式每局之前生成一次道具表
	function prepare_new_game_gtype1()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;

		//各文件位置
		$iplacefilelist = array(
			'mapitem' => GAME_ROOT.'/include/modules/base/itemmain/config/mapitem.config.php',
			'shopitem' => GAME_ROOT.'./include/modules/extra/instance/gtype1/main/config/shopitem.config.php',
			'mixitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix/config/itemmix.config.php',
			'syncitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix_sync/config/sync.config.php',
			'overlayitem' => GAME_ROOT.'./include/modules/base/itemmix/itemmix_overlay/config/overlay.config.php',
			'presentitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/present.config.php',
			'ygoitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/ygobox.config.php',
			'fyboxitem' => GAME_ROOT.'./include/modules/base/items/boxes/config/fybox.config.php',
			//'npcinfo_gtype1' => GAME_ROOT.'./include/modules/extra/instance/gtype1/main/config/npc.data.config.php',
			//'anpcinfo' => GAME_ROOT.'./include/modules/base/addnpc/config/addnpc.config.php',
			//'enpcinfo_gtype1' => GAME_ROOT.'./include/modules/extra/instance/gtype1/main/config/evonpc.config.php',
		);
		
		\itemnumlist\itemnumlist_create('gtype1item', $iplacefilelist);
	}
	
	function check_player_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		//重视躲避不会摸到活的队友
		if ($gametype == 1 && $tactic==4 && $edata['type']==0 && $edata['hp'] > 0) {
			eval(import_module('metman'));
			$hidelog = '<span class="yellow b">周围有人，不过你刻意避开了他们。</span><br>';;
			return 0;	
		}
		return $chprocess($edata);
	}

	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','gtype1'));
		if (1 == $gametype){
			return $npcinfo_gtype1;
		}else return $chprocess();
	}
	
	function get_enpcinfo()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','gtype1'));
		if (1 == $gametype){
			return $enpcinfo_gtype1;
		}else return $chprocess();
	}
	
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype==1){
			$file = __DIR__.'/config/shopitem.config.php';
			$sl = openfile($file);
			return $sl;
		}else return $chprocess();
	}
	
	//接管meetman_alternative，主要是判定遭遇玩家时必定为队友
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if($gametype == 1){
			if(!$edata['type']){
				$log .= '你看到了同为测试工程师的玩家，你们似乎有点尴尬地笑了笑。<br>';
				\team\findteam($edata);
				return;
			}
		}
		return $chprocess($edata);
	}
	//接管calculate_hide_obbs，玩家隐蔽率上升40%（不然太容易互相遭遇）
	function calculate_hide_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata);
		eval(import_module('sys'));
		if($gametype == 1 && !$edata['type']){
			$ret += 40;
		}
		return $ret;
	}
	
	//递送道具时无视teamID
	function senditem_check_teammate($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gametype == 1)
		{
			return true;
		}
		return $chprocess($edata);
	}

	//2禁前不会连斗
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ( $gametype==1 && \map\get_area_wavenum()<2 && $alivenum>0 ){
			return;
		}
		$chprocess($time);
	}
	
	function rs_game($xmode = 0) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		if (($gametype==1)&&($xmode & 2)) 
		{
			$weather = 1;
			$hack=1;
			//save_gameinfo();
		}
		
	}
	
	function gtype1_post_rank_event(&$pa, $cl, $rk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\player\player_save($pa);
	}
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gametype==1){
			// if($alivenum <= 0 && $now_wavenum >= 3){//这里会导致，如果玩家死亡而且直到3禁才有人刷新游戏，会全灭而不是结束
			// 	\sys\gameover($atime,'end1');
			// 	return;
			// }
			if (\map\get_area_wavenum() >= 2){//限时2禁
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 ORDER BY pid DESC");
				$ml=-1;
				$winner='';
				$wl=array();
				$wc=0;
				while($pt = $db->fetch_array($result)) {
					$pa=\player\fetch_playerdata_by_pid($pt['pid']);
					$cl=(int)\skillbase\skill_getvalue(424,'lvl',$pa);
					//$wl[$pa['name']]=$cl;
					if ($cl>$ml){
						$ml=$cl;
						$winner=$pa['name'];
					}
					$wc++;
					$wl[$wc]['n']=$pa['name'];
					$wl[$wc]['p']=$pa['pid'];
					$wl[$wc]['c']=$cl;
				}
				//arsort($wl);
				for ($i=1;$i<$wc;$i++){
					for ($j=$i+1;$j<=$wc;$j++){
						if (($wl[$i]['c']<$wl[$j]['c'])||($wl[$i]['c']==$wl[$j]['c'])&&($wl[$i]['p']<$wl[$j]['p'])){
							$tt=$wl[$i];
							$wl[$i]=$wl[$j];
							$wl[$j]=$tt;
						}
					}
				}
				$rk=0; 
				$max_announce_num = 3;	//进行状况展示人数
				$bestlist = Array();	//进行状况中展示的前X名列表
				//foreach ($wl as $kk=>$v){
				for ($rk=1;$rk<=$wc;$rk++){
					//$rk++;
					$kk=$wl[$rk]['n'];
					$v=$wl[$rk]['c'];
					$k=\player\fetch_playerdata($kk);
					$qiegao_prize = $v*10;
					$card_prize = array();
					if ($v>=20) $qiegao_prize += 200;
					if ($v>=30) $qiegao_prize += 500;
					if ($v>=40) $qiegao_prize += 700;
					if ($v>=50) $qiegao_prize += 900;
					if ($v>=60) $qiegao_prize += 1100;
					if ($v>=70) $qiegao_prize += 1200;
					if ($rk==1) $qiegao_prize += 1000;
					if ($rk<=2) $qiegao_prize += 500;
					if ($rk<=$max_announce_num){
						$qiegao_prize += 500;
						$bestlist[$rk] = Array(0=>$kk, 1=>$v);
					}	
					\skillbase\skill_setvalue(424,'rank',$rk,$k);
					if($qiegao_prize) {
						//\cardbase\get_qiegao($qiegao_prize,$k);
						include_once './include/messages.func.php';
						message_create(
							$kk,
							'除错模式奖励',
							'感谢您为金龙通讯社所做的工作，这里是您的奖励，请查收。<br>',
							'getqiegao_'.$qiegao_prize
						);	
						\skillbase\skill_setvalue(424,'prize',$qiegao_prize,$k);
						gtype1_post_rank_event($k, $v, $rk);
					}
				}
				
				for ($i=$max_announce_num; $i>=1; $i--) 
					if (isset($bestlist[$i]))
						addnews(0,'g1announce',$i,$bestlist[$i][0],$bestlist[$i][1]);
						
				\sys\gameover($atime,'end8',$winner);
				return;
			}
			\sys\rs_game(16+32);
			return;
		}
		$chprocess($atime);	
	}

	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys','lvlctl'));
		if ($gametype==1) $lvupskpt=0;
	}
	
	//除错模式雏菊无事件
	function event_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'player'));
		if(19==$gametype && $pls == 33) return false;
		return $chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'g1announce') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">本次游戏第<span class=\"yellow b\">{$a}</span>名是完成了<span class=\"yellow b\">{$c}</span>次除错的<span class=\"yellow b\">{$b}</span>。</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	//除错模式不能销毁小兵和玩家以外的尸体
	function check_can_destroy($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gametype==1 && $edata['type'] && $edata['type']!=90) return false;
		return $chprocess($edata);
	}
	
	//改变DN逻辑
	function deathnote_process($dnname='',$dndeath='',$dngender='m',$dnicon=1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman'));
		if($gametype==1 && $dnname){
			$edata = \player\fetch_playerdata('黑熊',21);
			if(isset($edata['pid']) && $edata['hp'] > 0){//黑熊NPC存在且存活，可以伪造战斗界面，因而剧情不同
				$o_fog = $fog; $fog = 0;
				extract($edata,EXTR_PREFIX_ALL,'w');
				$battle_title = '发现敌人';
				\metman\init_battle();
				$main = MOD_METMAN_MEETMAN;
				$cmd = <<<EOH
<input type="button" class="cmdbutton" value="返回" onclick="postCmd('gamecmd','command.php');this.disabled=true;">
EOH;
				$log .= <<<EOT
你将<span class="yellow b">$dnname</span>的名字写在了红暮的脸上。但是什么都没有发生。<br>哪里出错了？<br>
还没等你合上■DeathNote■，就听到<span class="red b">英灵殿</span>的方向传来一声愤怒的咆哮。你扭头一看，发现一只长得很像<span class="yellow b">熊本熊</span>的东西站在你身后，他看起来很生气。<br>
“熊本熊”伸出手来，指了指他自己，又指了指你，然后做了个“<span class="yellow b">60</span>”的手势。<br>
虽然你不知道他的意思，但你看得懂气氛，乖乖交出了■DeathNote■，想了想，又<span class="yellow b">掏出了60元钱</span>，一起交给了他。<br>
“熊本熊”接过钱愣了几秒，之后把■DeathNote■紧紧抱在怀里，钻进旁边的树丛里不见了踪影。<br>之后，似乎传来了舔什么的声音。<br>
不知为何，你决定还是把这件事完全忘掉。<br>
EOT;
				$money -= 60;if($money < 0) $money = 0;
				$fog = $o_fog;
			}else{
				$log .= '■DeathNote■忽然直接燃烧了起来。<br><span class="red b">这不是火焰，是灼焰！你被■DeathNote■点燃的灼焰烧伤了！</span><br>■DeathNote■很快化为了灰烬。<br>';
				$inf = str_replace('u','',$inf);
				$inf .= 'u';
			}
			return true;
		}
		return $chprocess($dnname,$dndeath,$dngender,$dnicon);
	}
}

?>