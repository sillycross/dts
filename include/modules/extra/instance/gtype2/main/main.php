<?php

namespace gtype2
{
	function init() {}
	
	function prepare_new_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $chprocess();
		//先ban了
		eval(import_module('sys'));
		if (room_check_subroom($room_prefix)) return $chprocess();
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		$tg=$gamenum-3;
		$res=$db->query("SELECT gametype FROM {$gtablepre}history WHERE gid='$tg'");
		$gt=2;
		if ($db->num_rows($res)){
			$zz=$db->fetch_array($res); $gt=$zz['gametype'];
		}
		if ($wday==5 && !$disable_event){
			if (($hour>=19)&&($hour<21)&&($gt!=2)){ 
				$gametype=2;
			}
		}
		$chprocess();
	}
	
	//这已经失效了，但是反正这个模式废了，懒得改
	function check_player_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($edata['type']==0 && $edata['hp']<=0 && $gametype == 2) return 0;	//摸不到玩家尸体
		return $chprocess($edata);
	}
	
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','gameflow_combo'));
		if ( $gametype==2 &&  \map\get_area_wavenum()<2 && $alivenum>0 ){
			return;
		}
		$chprocess($time);
	}
	
	function rs_game($xmode = 0) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		if (($gametype==2)&&($xmode & 2)) 
		{
			$weather = 1;
			//save_gameinfo();
		}
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		eval(import_module('sys'));
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {
			if ($itm == '装有H173的注射器' && $gametype==2) {
				eval(import_module('logger'));
				$log .= '你把注射器的包装撕开，却发现没有针头。“妈的智障”，你念叨着，把注射器随手扔掉了。<br>';
				\itemmain\itms_reduce($theitem);
				return;
			}
		}
		$chprocess($theitem);
	}
	
	function check_initnpcadd($typ)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype==2) return !in_array($typ,Array(1,9,14,20,21,22,88));
		return $chprocess($typ);
	}
	
	function get_shopconfig()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if ($gametype==2){
			$file = __DIR__.'/config/shopitem.config.php';
			$sl5 = openfile($file);
			return $sl5;
		}else return $chprocess();
	}
	
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gametype==2) 
		{
			\skillbase\skill_acquire(475,$pa);
			\skillbase\skill_acquire(235,$pa);
			\skillbase\skill_setvalue(235,'lvl','1',$pa);
			$pa['itm6']='任务指令书B';
			$pa['itmk6']='Y';
			$pa['itme6']='1';
			$pa['itms6']='1';
			$pa['itmsk6']='';
		}
		return $chprocess($pa);
	}
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($gametype==2){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');//理论不会这样，防BUG
			}
			elseif (\map\get_area_wavenum() >= 2){//限时2禁
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 ORDER BY pid DESC");
				$ml=-1;
				$winner='';
				$wl=array();
				$wc=0;
				while($pt = $db->fetch_array($result)) {
					$pa=\player\fetch_playerdata_by_pid($pt['pid']);
					$cl=(int)\skillbase\skill_getvalue(475,'wpt',$pa);
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
					if ($rk==1){
						\cardbase\get_card(151,$k);
						\cardbase\get_card(152,$k);
						\cardbase\get_qiegao(1200,$k);
					}
					$qiegaogain = Array(2=>1000,3=>800,4=>700,5=>550);
					if (2<=$rk && $rk<=5){
						\cardbase\get_card(152,$k);
						\cardbase\get_qiegao($qiegaogain[$rk],$k);
					}
					if (6<=$rk && $rk<=10){
						\cardbase\get_qiegao(400,$k);
					}
					if ($rk<=$max_announce_num){
						$bestlist[$rk] = Array(0=>$kk, 1=>$v);
					}		
				}
				
				for ($i=$max_announce_num; $i>=1; $i--) 
					if (isset($bestlist[$i]))
						addnews(0,'g2announce',$i,$bestlist[$i][0],$bestlist[$i][1]);
						
				\sys\gameover($atime,'end8',$winner);
			}
			return;
		}
		$chprocess($atime);	
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'g2announce') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">本次游戏第<span class=\"yellow b\">{$a}</span>名是获得了<span class=\"yellow b\">{$c}</span>点胜利点数的<span class=\"yellow b\">{$b}</span>。</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>