<?php

namespace gtype1
{
	function init() {}
	
	function reset_game()
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
		/*if ($wday==3){
			if (($hour>=19)&&($hour<21)&&($gt!=1)){ 
				$gametype=1;
			}else{
				$gametype=0;
			}
		}*/
		if (($month==12)&&($day==27)&&(($hour<2))){
			$gametype=1;
		}else{
			$gametype=0;
		}
		if ($disableevent) $gametype=0;
		$chprocess();
	}
	
	function check_player_discover($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($edata['type']==0 && $gametype == 1) return 0;	//摸不到玩家
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
			save_gameinfo();
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
				while($pt = $db->fetch_array($result)) {
					$pa=\player\fetch_playerdata_by_pid($pt['pid']);
					$cl=(int)\skillbase\skill_getvalue(424,'lvl',$pa);
					$wl[$pa['name']]=$cl;
					if ($cl>$ml){
						$ml=$cl;
						$winner=$pa['name'];
					}
				}
				arsort($wl);
				$rk=0;
				foreach ($wl as $kk=>$v){
					$rk++;
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
					if ($rk<=3){
						\cardbase\get_qiegao(500,$k);
						addnews(0,'g1announce',$rk,$kk,$v);
					}		
				}
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
	
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'g1announce') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">本次游戏第<span class=\"yellow\">{$a}</span>名是完成了<span class=\"yellow\">{$c}</span>次除错的<span class=\"yellow\">{$b}</span>。</span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>