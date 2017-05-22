<?php

namespace gtype1
{
	function init() {}
	
	function prepare_new_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($room_prefix!='') return $chprocess();
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		$tg=$gamenum-3;
		$res=$db->query("SELECT gametype FROM {$gtablepre}winners WHERE gid='$tg'");
		$gt=1;
		if ($db->num_rows($res)){
			$zz=$db->fetch_array($res); $gt=$zz['gametype'];
		}
		if ($wday==3){
 			if (($hour>=19)&&($hour<21)&&($gt!=1)){ 
 				$gametype=1;
 			}else{
 				$gametype=0;
 			}
 		}
 		if ($disable_event) $gametype=0; 
		$chprocess();
	}
	
	function check_player_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($edata['type']==0 && $gametype == 1) {
			return 0;	
		}
		return $chprocess($edata);
	}
	
	//接管meetman_alternative，主要是判定遭遇玩家时必定为队友
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if($gametype == 1){
			if(!$edata['type'] && $tutorial_force_teamer){
				$log .= '你看到了同为测试工程师的玩家，你们似乎有点尴尬地笑了笑。<br>';
				\team\findteam($edata);
				return;
			}
		}
		return $chprocess($edata);
	}
	
	//递送道具时无视teamID
	function senditem_check($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		if ($gametype == 1)
		{
			if(isset($edata) && !$edata['type'] && $edata['pls'] == $pls && $edata['hp'] > 0 && $edata['pid'] != $pid){
				return true;
			}
		}
		return $chprocess($edata);
	}
	
	function checkcombo(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if (($gametype==1)&&($areanum<$areaadd*2)&&($alivenum>0)){
			return;
		}
		$chprocess();
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
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($gametype==1){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');//理论不会这样，防BUG
				return;
			}
			if ($areanum>=($areaadd*2)){//限时2禁
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
					if ($v>=5){
						\cardbase\get_qiegao(150,$k);
					}
					if ($v>=10){
						\cardbase\get_qiegao(300,$k);
					}
					if ($v>=20){
						\cardbase\get_qiegao(600,$k);
					}
					if ($v>=30){
						\cardbase\get_card(94,$k);
						\cardbase\get_qiegao(500,$k);
					}
					if ($rk==1){
						\cardbase\get_card(96,$k);
						\cardbase\get_card(95,$k);
					}
					if ($rk<=2){
						\cardbase\get_card(95,$k);
					}
					if ($rk<=$max_announce_num){
						\cardbase\get_qiegao(500,$k);
						$bestlist[$rk] = Array(0=>$kk, 1=>$v);
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
	
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'g1announce') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime\">本次游戏第<span class=\"yellow\">{$a}</span>名是完成了<span class=\"yellow\">{$c}</span>次除错的<span class=\"yellow\">{$b}</span>。</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	//改变DN逻辑
	function deathnote_process($dnname='',$dndeath='',$dngender='m',$dnicon=1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','metman'));
		if($gametype==1 && $dnname){
			$edata = \player\fetch_playerdata('黑熊',21);
			if(isset($edata['pid'])){//黑熊NPC存在，可以伪造战斗界面，因而剧情不同
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
还没等你合上■DeathNote■，就听到<span class="red b">英灵殿</span>的方向传来一声愤怒的咆哮。你扭头一看，发现一只长得很像<span class="yellow">熊本熊</span>的东西站在你身后，他看起来很生气。<br>
“熊本熊”伸出手来，指了指他自己，又指了指你，然后做了个“<span class="yellow">60</span>”的手势。<br>
虽然你不知道他的意思，但你看得懂气氛，乖乖交出了■DeathNote■，想了想，又<span class="yellow">掏出了60元钱</span>，一起交给了他。<br>
“熊本熊”接过钱愣了几秒，之后把■DeathNote■紧紧抱在怀里，钻进旁边的树丛里不见了踪影。<br>之后，似乎传来了舔什么的声音。<br>
不知为何，你决定还是把这件事完全忘掉。<br>
EOT;
				$money -= 60;if($money < 0) $money = 0;
				$fog = $o_fog;
			}else{
				$log .= '■DeathNote■忽然直接燃烧了起来。<br><span class="red">这不是火焰，是灼焰！你被■DeathNote■点燃的灼焰烧伤了！</span><br>■DeathNote■很快化为了灰烬。<br>';
				$inf = str_replace('u','',$inf);
				$inf .= 'u';
			}
			return true;
		}
		return $chprocess($dnname,$dndeath,$dngender,$dnicon);
	}
}

?>