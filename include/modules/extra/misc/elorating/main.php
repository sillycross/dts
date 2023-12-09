<?php

namespace elorating
{
	global $elo_servermark;
	
	function init() {

	}
	
	//入场时，如果是要计算积分的模式，则增加面包矿泉水数量，并且开局道具增加探测器。不知为何这个变量放在sys模块里，可能是图省事吧
	function init_enter_battlefield_items($ebp){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp = $chprocess($ebp);
		eval(import_module('sys'));
		if (in_array($gametype,$elorated_mode))
		{
			$ebp['itms1'] = 50; $ebp['itms2'] = 50;
			$ebp['itm5'] = '生命探测器'; $ebp['itmk5'] = 'ER'; $ebp['itme5'] = 5; $ebp['itms5'] = 1;$ebp['itmsk5'] = '';
		}
		return $ebp;
	}
	
	function get_servermark() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'elorating'));
		//确定自己的服务器代号
		$ret = 'X';
		foreach($elo_servermark as $ek => $ev){
			if(strpos(gurl(), $ev)!==false) {
				$ret = $ek;
				break;
			}
		}
		return $ret;
	}
	
	function elorating_math_erf($x)
	{
		//erf函数
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$t=1/(1+0.5*abs($x));
		$z=0; $v=1;
		$con=Array(	-1.26551223,+1.00002368,+0.37409196,+0.09678418,-0.18628806,
				+0.27886807,-1.13520398,+1.48851587,-0.82215223,+0.17087277);
		for ($i=0; $i<=9; $i++)
		{
			$z+=$v*$con[$i];
			$v*=$t;
		}
		$tau=$t*exp(-$x*$x+$z);
		if ($x>=0) return 1-$tau; else return $tau-1;
	}

	function elorating_math_ierf($x) 
	{
		//erf的反函数，精度有点烂
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pi=3.14159265358979323846264338;
		return 1/2 * pow($pi, 0.5) * (
			$x + 
			pow($pi/4, 1)*pow($x, 3)/3 + 
			7*pow($pi/4, 2)*pow($x, 5)/30 + 
			127*pow($pi/4, 3)*pow($x, 7)/630 + 
			4369*pow($pi/4, 4)*pow($x, 9)/22680 + 
			34807*pow($pi/4, 5)*pow($x, 11)/178200 +
			20036983*pow($pi/4, 6)*pow($x, 13)/97297200 +
			2280356863.0*pow($pi/4, 7)*pow($x, 15)/10216206000.0 +
			49020204823.0*pow($pi/4, 8)*pow($x, 17)/198486288000.0 +
			65967241200001.0*pow($pi/4, 9)*pow($x, 19)/237588086736000.0 +
			15773461423793767.0*pow($pi/4, 10)*pow($x, 21)/49893498214560000.0 +
			655889589032992201.0*pow($pi/4, 11)*pow($x, 23)/1803293578326240000.0 +
			94020690191035873697.0*pow($pi/4, 12)*pow($x, 25)/222759794969712000000.0 +
			655782249799531714375489.0*pow($pi/4, 13)*pow($x, 27)/1329207696584271504000000.0
			);
	}
	
	function elorating_math_probit($x)
	{	
		//probit函数
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return sqrt(2)*elorating_math_ierf(2*$x-1);
	}
	
	//根据rating计算期望单挑胜率，即p1与p2单挑时p1胜率几率
	function elorating_calculate_win_probability($p1, $p2)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return (elorating_math_erf(($p1['rating']-$p2['rating'])/(sqrt(2*$p1['vol']*$p1['vol']+2*$p2['vol']*$p2['vol'])))+1)/2;
	}
	
	//根据rating得出的期望单挑胜率进行游戏模拟，得到一次模拟排名
	function elorating_stimulate_game(&$arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$num_player=count($arr);
		for ($i=0; $i<$num_player; $i++) $f[$i]=1;
		for ($i=0; $i<$num_player; $i++) $knum[$i]=0;
		
		$tlis=Array();
		for ($i=0; $i<$num_player; $i++)
		{
			$key=$arr[$i];
			if (!isset($tlis[$key['teamID']])) $tlis[$key['teamID']]=Array();
			array_push($tlis[$key['teamID']],$i);
		}
		
		$z=$num_player;
		while (1)
		{
			$flag=0; 
			foreach ($tlis as $tid => &$key) if (count($key)>0) $flag++;
			if ($flag<=1) break;
				
			//等概率随机一场击杀战
			$s=0; $t=0;
			foreach ($tlis as $tid => &$key) 
			{
				$s+=$t*count($key);
				$t+=count($key);
			}
			$v=rand(1,$s); $t=0;
			foreach ($tlis as $tid => &$key)
			{
				if ($v<=$t*count($key))
				{
					$pos1=&$key;
					$pos1i=floor(($v-1)/$t);
					$p1=$pos1[$pos1i];
					$z=($v-1)%$t+1;
					foreach ($tlis as $tid2 => &$k2)
						if ($z<=count($k2))
						{
							$pos2=&$k2;
							$pos2i=$z-1;
							$p2=$pos2[$pos2i];
							break;
						}
						else  $z-=count($k2);
					
					if (rand(0,9999)<10000*elorating_calculate_win_probability($arr[$p1],$arr[$p2]))
					{
						$f[$p2]=0;
						$knum[$p1]++;
						array_splice($pos2,$pos2i,1);
					}
					else  
					{
						$f[$p1]=0;
						$knum[$p2]++;
						array_splice($pos1,$pos1i,1);
					}
					
					break;
				}
				else $v-=$t*count($key);
				$t+=count($key);
			}
		}
		
		foreach ($tlis as $tid => &$key) 
			if (count($key)>0) 
			{
				$win_teamID=$tid;
				break;
			}
		
		$tmp=Array();
		for ($i=0; $i<$num_player; $i++)
		{
			//胜利者队伍最优先，胜利队伍内部存活者优先，然后按杀人数排序
			$wg=0;
			if ($arr[$i]['teamID']==$win_teamID) $wg+=10000;
			if ($f[$i]) $wg+=5000;
			$wg+=$knum[$i];
			
			$wg=-$wg;
			
			if (!isset($tmp[$wg])) $tmp[$wg]=Array();
			array_push($tmp[$wg],$i);
		}
		
		ksort($tmp);
		$nowr=1;
		foreach ($tmp as $key => $value)
		{
			foreach ($value as $id)
			{
				$arr[$id]['erank']+=$nowr+(count($value)-1)/2;
			}
			$nowr+=count($value);
		}
	}
			
	//每个玩家描述格式
	//name: 用户名
	//rating: 先前rating（调用后被修改为新值）
	//vol: 先前volatility（调用后修改为新值）
	//timesPlayed: 参与游戏次数（调用后修改为新值）
	//teamID: 本次游戏队伍名
	//winner: 是否胜利
	//alive: 是否存活到最后
	//killnum: 杀人数
	function elorating_calculate_update(&$arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$num_player = count($arr);
		if ($num_player<=1) return;
		
		foreach ($arr as &$key) if (!isset($key['teamID']) || $key['teamID']=='') $key['teamID']=(uniqid('',true)).((string)rand(10000,99999));
		
		if (!in_array($gametype,$teamwin_mode)) 
		{
			$z=0;
			foreach ($arr as &$key) 
			{
				$z++;
				$key['teamID']=$z;
			}
		}
		
		$aveRating = 0;
		foreach ($arr as &$key) $aveRating+=$key['rating'];
		$aveRating/=$num_player;
		
		$x=0; $y=0;
		foreach ($arr as &$key) 
		{
			$x+=pow($key['vol'],2);
			$y+=pow($key['rating']-$aveRating,2);
		}
		$x/=$num_player; $y/=($num_player-1);
		$cf=sqrt($x+$y);
		
		//计算期望排名
		foreach ($arr as &$key) $key['erank']=0;
		
		$iter_max_num = 10000;
		for ($cur_iter_num=0; $cur_iter_num<$iter_max_num; $cur_iter_num++)
			elorating_stimulate_game($arr);
		
		foreach ($arr as &$key) $key['erank']/=$iter_max_num;
		
		foreach ($arr as &$key) $key['eperf']=-elorating_math_probit(($key['erank']-0.5)/$num_player);
		
		//计算实际排名
		$tmp=Array();
		for ($i=0; $i<$num_player; $i++)
		{
			//胜利者队伍最优先，胜利队伍内部存活者优先，然后按杀人数排序
			$wg=0;
			if ($arr[$i]['winner']) $wg+=10000;
			if ($arr[$i]['alive']) $wg+=5000;
			$wg+=$arr[$i]['killnum'];
			
			$wg=-$wg;
			
			if (!isset($tmp[$wg])) $tmp[$wg]=Array();
			array_push($tmp[$wg],$i);
		}
		
		ksort($tmp);
		$nowr=1;
		foreach ($tmp as $skey => $value)
		{
			foreach ($value as $id)
			{
				$arr[$id]['arank'] = $nowr+(count($value)-1)/2;
				$arr[$id]['aperf']=-elorating_math_probit(($arr[$id]['arank']-0.5)/$num_player);
			}
			$nowr+=count($value);
		}
		
		foreach ($arr as &$key) 
		{
			$key['perfAs']=$key['rating']+$cf*($key['aperf']-$key['eperf']);
			$key['weight']=1/(1-0.18-0.42/($key['timesPlayed']+1))-1;
			if ($key['rating']>=2000 && $key['rating']<2500)
					$key['weight']*=0.9;
			else  if ($key['rating']>=2500)
					$key['weight']*=0.8;
					
			$cap=150+1500/(max(2,$key['timesPlayed'])+2);
			$nrt=($key['rating']+$key['weight']*$key['perfAs'])/(1+$key['weight']);
			$diff=$nrt-$key['rating'];
			if ($diff>$cap) $diff=$cap;
			if ($diff<-$cap) $diff=-$cap;
			$key['diff']=$diff;
		}
		
		//最终修正
		//保证胜利者不会掉rating（这个在有多个胜利者时候可能发生）
		//这部分rating由所有其他涨rating的人分摊
		$ss=0;
		foreach ($arr as &$key) 
		{
			if ($key['diff']<=0 && $key['winner'])
			{
				$ss+=(-$key['diff'])+1;
				$key['diff']=1;
				$key['spflag']=1;
			}
		}
		
		if ($ss>0)
		{
			$ss*=0.8;
			$dfsum=0;
			foreach ($arr as &$key) 
				if ($key['diff']>0 && !$key['spflag']) 
					$dfsum+=$key['diff'];
					
			foreach ($arr as &$key)
				if ($key['diff']>0 && !$key['spflag'])
				{
					$z=$key['diff']/$dfsum*$ss;
					$key['diff']-=$z;
				}
		}
		
		foreach ($arr as &$key) if ($key['diff']<=1 && $key['winner']) $key['diff']=1;
		
		foreach ($arr as &$key) $key['diff']=round($key['diff']);
		
		//更新各项
		foreach ($arr as &$key) 
		{
			$key['rating']+=$key['diff'];
			$key['vol']=ceil(sqrt(pow($key['diff'],2)/$key['weight']+pow($key['vol'],2)/(1+$key['weight'])));
			$key['timesPlayed']++;
		}
	}
	
	function elorating_update()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(empty($gameover_plist)) return;
		$esm = get_servermark();
		//已改为直接用$gameover_plist和$gameover_ulist直接判定
		if (room_check_subroom($room_prefix) && in_array($gametype,$elorated_mode))
		{
			$tlist = Array();
			
			foreach($gameover_plist as $data)
			{
				$z = $gameover_ulist[$data['name']];
				if(empty($z)) continue;
				$data['rating']=$z['elo_rating'];
				$data['vol']=$z['elo_volatility'];
				$data['timesPlayed']=$z['elo_playedtimes'];
				$data['elo_history']=$z['elo_history'];
				if ($data['winner_flag'])
				{
					$data['winner']=1;
					if ($data['hp']>0) $data['alive']=1; else $data['alive']=0;
				}
				else
				{
					$data['winner']=0;
					$data['alive']=0;
				}
				//file_put_contents('a.txt',$data['state'],FILE_APPEND);
				array_push($tlist,$data);
			}
			elorating_calculate_update($tlist);
			foreach ($tlist as $data)
			{
				//游戏历史描述
				//1字符：服务器代号
				//1字符：游戏区域前缀
				//4字符：游戏局号
				//1字符：游戏类型
				//1字符：结果
				//3字符：rating（-131072后为真实值，虽然理论上rating不会有负数……）
				if ($room_prefix=='') $xr=' '; else $xr=room_prefix_kind($room_prefix);
				$eh=$data['elo_history'].$esm.$xr.base64_encode_number($gamenum,4).base64_encode_number($gametype,1).base64_encode_number($data['winner']?1:0,1).base64_encode_number($data['rating']+131072,3);
				//考虑了一下还是不拼成一个大query了，毕竟有个text…… 爆了mysql最大query长度限制就囧了
				//$db->query("UPDATE {$gtablepre}users SET elo_rating='{$data['rating']}', elo_volatility='{$data['vol']}', elo_playedtimes='{$data['timesPlayed']}', elo_history='{$eh}' WHERE username = '{$data['name']}'");
				//现在改成存回$gameover_ulist
				$gameover_ulist[$data['name']]['elo_rating'] = $data['rating'];
				$gameover_ulist[$data['name']]['elo_volatility'] = $data['vol'];
				$gameover_ulist[$data['name']]['elo_playedtimes'] = $data['timesPlayed'];
				$gameover_ulist[$data['name']]['elo_history'] = $eh;
			}
		}
	}
	
	function get_gameover_udata_update_fields(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		eval(import_module('sys'));
		if (room_check_subroom($room_prefix) && in_array($gametype,$elorated_mode)) {
			$ret = array_merge($ret, array('u_achievements', 'elo_rating', 'elo_volatility', 'elo_playedtimes', 'elo_history'));
		}
		return $ret;
	}
	
	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		elorating_update();
//		logmicrotime('天梯积分计算');
		$chprocess();
	}
	
	function elorating_draw_line($p1,$p2)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$w=$p2['x']-$p1['x']; $h=abs($p2['y']-$p1['y']);
		if ($w>=$h)
		{
			for ($i=$p1['x']; $i<=$p2['x']; $i++)
			{
				$y=$p1['y']+($i-$p1['x'])/($p2['x']-$p1['x'])*($p2['y']-$p1['y']);
				echo '<div style="position:absolute; left:'.$i.'px;top:'.$y.'px; ';
				echo 'height:3px; width:1px; background-image:url(\'img/rating-line-vertical.png\');">';
				echo '</div>';
			}
		}
		else
		{
			if ($p1['y']<=$p2['y'])
			{
				for ($i=$p1['y']; $i<=$p2['y']; $i++)
				{
					$x=$p1['x']+($i-$p1['y'])/($p2['y']-$p1['y'])*($p2['x']-$p1['x']);
					echo '<div style="position:absolute; left:'.$x.'px;top:'.$i.'px; ';
					echo 'height:1px; width:4px; background-image:url(\'img/rating-line-horizonal.png\');">';
					echo '</div>';
				}
			}
			else
			{
				for ($i=$p1['y']; $i>=$p2['y']; $i--)
				{
					$x=$p1['x']+($i-$p1['y'])/($p2['y']-$p1['y'])*($p2['x']-$p1['x']);
					echo '<div style="position:absolute; left:'.$x.'px;top:'.$i.'px; ';
					echo 'height:1px; width:4px; background-image:url(\'img/rating-line-horizonal.png\');">';
					echo '</div>';
				}
			}
		}
	}
	
	function elorating_get_title($r)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($r<900) return '沙包';
		if ($r<1200) return '沙包';
		if ($r<1500) return '新人';
		if ($r<1700) return '新人';
		if ($r<1900) return '准触手';
		if ($r<2100) return '触手';
		if ($r<2400) return '大触';
		if ($r<3000) return '神触';
		return '宇宙神触';
	}
	
	function elorating_get_color($r)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($r<900) return '#cccccc';
		if ($r<1200) return '#33ff33';
		if ($r<1500) return '#00aa00';
		if ($r<1700) return '#00ffff';
		if ($r<1900) return '#cc00cc';
		if ($r<2100) return '#ffa000';
		if ($r<2400) return '#ff4c00';
		if ($r<3000) return '#ff1a1a';
		return '#ff0000';
	}
	
	function elorating_get_graph_color($r)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($r<900) return '#cccccc';
		if ($r<1200) return '#aaffaa';
		if ($r<1500) return '#77ff77';
		if ($r<1700) return '#aaaaff';
		if ($r<1900) return '#ff88ff';
		if ($r<2100) return '#ffcc88';
		if ($r<2400) return '#ffbb55';
		if ($r<3000) return '#ff7777';
		return '#ff4c4c';
	}
	
	function elorating_draw_point($p)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $roomtypelist;
		//外服识别
		eval(import_module('elorating'));
		if('X' != $p['spre'] && isset($elo_servermark[$p['spre']])) {
			$shref = 'http://'.$elo_servermark[$p['spre']].'/';
		}else{
			$shref = '';
		}
		echo '<div onclick="window.location.href=\''.$shref.'replay.php?repid='.(($p['rpre']!=' ')?($p['rpre'].'.'):'').$p['gid'].'\'"; onmouseover="show_fixed_div(\'user-rating-point-'.$p['id'].'\');" onmouseout="hide_fixed_div(\'user-rating-point-'.$p['id'].'\');" style="cursor:pointer; z-index:10000; position:absolute; left:'.($p['x']-6).'px;top:'.($p['y']-6).'px; height:14px; width:14px; overflow:hidden;">';
		echo '<div style="position:absolute; left:3px; top:3px;">';
		echo '<img src="img/rating-point-highest.png" style="height:8px; width:8px;">';
		echo '</div></div>';
		echo '<div id="user-rating-point-'.$p['id'].'" style="position:absolute; z-index:10010; width:100px; text-align:left; display:none; filter:alpha(opacity=80); opacity:0.8; background-color:#ffffff; left:'.(($p['x']>500) ? ($p['x']-112) : ($p['x']+12)).'px; top:'.$p['y'].'px;">';
		echo '<span style="display:block; margin:2px 2px 2px 2px; color:#000000; font-size:11px;">';
		echo '= '.$p['rating'];
		if (isset($p['diff'])) 
		{
			echo ' (';
			if ($p['diff']>=0) echo '+';
			echo $p['diff'].')';
		}
		echo '<br>';
		if ($p['gtype']<10)
		{
			echo '常规局 ';
		}
		else  
		{
			if(!function_exists('room_gettype_from_gtype')) include_once GAME_ROOT.'./include/roommng/roommng.func.php';
			echo $roomtypelist[room_gettype_from_gtype($p['gtype'])]['name'].' ';
		}
		if ($p['win']) echo '<span style="color:#008800;">胜利</span>'; else echo '<span style="color:#ff0000;">失败</span>';
		echo '<br>';
		echo '点击可观看录像';
		echo '</span>';
		echo '</div>';
	}
		
	function elorating_draw($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$n=count($data);
		$minr=1500; $maxr=1500;
		for ($i=0; $i<$n; $i++)
		{
			$minr=min($minr,$data[$i]['rating']);
			$maxr=max($maxr,$data[$i]['rating']);
		}
		
		$downran=min($minr-150,750);	
		$upran=2250;
		if ($maxr>1700) $upran=550+$maxr;
		if ($maxr>1900) $upran=500+$maxr;
		if ($maxr>2100) $upran=450+$maxr;
		if ($maxr>2300) $upran=400+$maxr;
		if ($maxr>2500) $upran=350+$maxr;
		
		$arr=Array(900,1200,1500,1700,1900,2100,2400,3000);
		$lis=Array($downran);
		for ($i=0; $i<=7; $i++)
			if ($downran<$arr[$i] && $arr[$i]<$upran)
			{
				array_push($lis,$arr[$i]);
				$y=($upran-$arr[$i])/($upran-$downran)*280-3;
				echo '<div style="position:absolute; left:0px; top:'.$y.'px; height:12px; width:40px; overflow:hidden;">';
				echo '<span style="font-size:11px;">';
				echo $arr[$i];
				echo '</span></div>';
			}
		array_push($lis,$upran);
		
		echo '<div style="position:absolute; left:40px; height:280px; width:560px; border:3px #888888 solid;">';
		
		for ($i=0; $i<count($lis)-1; $i++)
		{
			$y1=($upran-$lis[$i+1])/($upran-$downran)*280;
			$y2=($lis[$i+1]-$lis[$i])/($upran-$downran)*280+1;
			if ($i==0) $y2--;
			echo '<div style="position:absolute; left:0px; top:'.$y1.'px; width:560px; height:'.$y2.'px; background-color:'.elorating_get_graph_color(($lis[$i]+$lis[$i+1])/2).'"></div>';
		}
		for ($i=0; $i<$n; $i++) 
		{
			if ($n>1)
				$data[$i]['x']=562.0/($n-1)*$i;
			else  $data[$i]['x']=281.0;
			$data[$i]['y']=($upran-$data[$i]['rating'])/($upran-$downran)*280;
			$data[$i]['id']=$i;
			if (!isset($data[$i]['diff']) && $i>0) $data[$i]['diff']=$data[$i]['rating']-$data[$i-1]['rating'];
		}
		for ($i=0; $i<$n; $i++) $data[$i]['x']-=2;
		for ($i=0; $i<$n-1; $i++) 
		{
			$p1=$data[$i]; $p2=$data[$i+1];
			if ($i!=0) $p1['x']++;
			if ($i!=$n-2) $p2['x']--;
			elorating_draw_line($p1,$p2);
		}
		for ($i=0; $i<$n; $i++) elorating_draw_point($data[$i]);
		echo '</div>';
	}
	
	function elorating_show()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $elo_history,$elo_rating;
		$i=0; $hist=Array(); $elo_max_rating=0;
		while ($i<strlen($elo_history))
		{
			if('s'!=$elo_history[$i] && ' '!=$elo_history[$i]) {
				$x0=$elo_history[$i]; $i++;
			}else{
				$x0='X';
			}
			$x1=$elo_history[$i]; $i++;
			$x2=base64_decode_number(substr($elo_history,$i,4)); $i+=4;
			$x3=base64_decode_number(substr($elo_history,$i,1)); $i++;
			$x4=base64_decode_number(substr($elo_history,$i,1)); $i++;
			$x5=base64_decode_number(substr($elo_history,$i,3))-131072; $i+=3;
			$elo_max_rating=max($elo_max_rating,$x5);
			array_push($hist,
				Array(
					'spre' => $x0,
					'rpre' => $x1,
					'gid' => $x2,
					'gtype' => $x3,
					'win' => $x4,
					'rating' => $x5,
				)
			);
		}
		
		$n=count($hist);
		for ($i=1; $i<$n; $i++) $hist[$i]['diff']=$hist[$i]['rating']-$hist[$i-1]['rating'];
		if(isset($hist[0]))	$hist[0]['diff']=$hist[0]['rating']-1500;
		
		//只显示最后70场
		$data=Array();
		for ($i=max(0,$n-70); $i<$n; $i++)
			array_push($data,$hist[$i]);
		
		include template('MOD_ELORATING_ELORATING');
	}				
}

?>