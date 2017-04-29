<?php

namespace replay
{
	//录像文件每块保存的操作数目
	//一般来说这个值越大压缩效果越好，但更吃内存
	$partsize=1000;
	
	function init() {}
	
	function get_html_color($r, $g, $b)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$a=Array($r, $g, $b); $ret='';
		foreach ($a as $key)
		{
			$key=round($key);
			if ($key<0) $key=0;
			if ($key>255) $key=255;
			$s='';
			for ($i=0; $i<=1; $i++)
			{
				$x=$key%16;
				if ($x<10) $s=(chr(ord('0')+$x)).$s; else $s=(chr(ord('a')+$x-10)).$s;
				$key=(int)floor($key/16);
			}
			$ret.=$s;
		}
		return $ret;
	}
	
	function get_ident_textcolor($u)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u=md5($u); $hash=0;
		for ($i=0; $i<31; $i++)
		{
			$z=0;
			if ('0'<=$u[$i] && $u[$i]<='9') $z=ord($u[$i])-ord('0');
			if ('a'<=$u[$i] && $u[$i]<='f') $z=ord($u[$i])-ord('a')+10;
			if ('A'<=$u[$i] && $u[$i]<='F') $z=ord($u[$i])-ord('A')+10;
			$hash=($hash*16+$z)%360;
		}
		if ($hash<60) 
			return get_html_color(255,$hash/60*255,0);
		else if ($hash<120) 
			return get_html_color(255-($hash-60)/60*255,255,0);
		else if ($hash<180) 
			return get_html_color(0,255,($hash-120)/60*255);
		else if ($hash<240) 
			return get_html_color(0,255-($hash-180)/60*255,255);
		else if ($hash<300) 
			return get_html_color(($hash-240)/60*255,0,255);
		else  return get_html_color(255,0,255-($hash-300)/60*255);
	}
	
	//游戏准备时，如果是不记录录像的房间（永续房等），直接清空目录。临时代码。
//	function gamestate_prepare_game()
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;		
//		eval(import_module('sys'));
//		$chprocess();
//		if($room_prefix && in_array($gametype, $replay_ignore_mode)){
//			clear_dir(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/',1);
//			global $___MOD_TMP_FILE_DIRECTORY;
//			clear_dir($___MOD_TMP_FILE_DIRECTORY.$room_prefix.'_/',1);
//		}
//		
//	}
	
	//游戏结束后，保存上局的录像文件并清空目录准备下一局
	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('replay','sys'));
		//daemon没开则返回
		global $___MOD_SRV, $___MOD_CODE_ADV3;
		if (!$___MOD_SRV || !$___MOD_CODE_ADV3) return;
		
		//主游戏：激活数>1或者最高伤害>400的幸存；任何解禁；任何核爆；任何解离
		//房间：最高伤害>400的全部死亡；激活数>1的幸存（任何队伍和SOLO模式）；任何解离；周常活动；最高伤害>400的挑战结束
		$replay_flag = 0;
		if(($winmode == 1 && ($gametype >= 10  || $hdamage > 400)) || ($winmode == 2 && ($validnum > 1 || $hdamage > 400)) || ($winmode == 3) || ($winmode == 5) || ($winmode == 7) || ($winmode == 8 && ($gametype < 10  || $hdamage > 400))){
			$replay_flag = 1;
		}
		//不记录录像的类型直接跳过
		if(in_array($gametype, $replay_ignore_mode)) $replay_flag = 0;
		//以下开始处理
		if($replay_flag){
			startmicrotime();
			$curdatalib = file_get_contents(GAME_ROOT.'./gamedata/javascript/datalib.current.txt');
			//获取游戏时长和胜利者名字，其实可以简化掉数据库读取的
			$result = $db->query("SELECT name,gstime,getime FROM {$wtablepre}winners WHERE gid={$gamenum}");
			$data = $db->fetch_array($result);
			$gametimelen = (int)$data['getime']-(int)$data['gstime'];
			$winname = $data['name'];
			//对每个存在的玩家挨个进行处理
			$result = $db->query("SELECT name,pid FROM {$tablepre}players WHERE type = 0");
			$plis = Array();
			//房间前缀，一般是's'
			$room_gprefix = '';
			if ($room_prefix!='') $room_gprefix = ((string)$room_prefix[0]).'.';
			logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-前序处理');
			while($data = $db->fetch_array($result))
			{
				if (is_dir(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/'.$data['pid']) && file_exists(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/'.$data['pid'].'/replay.txt'))
				{
					$totsz = 0;
					//$arr=录像头文件，记录基本信息和每次操作的时刻
					$arr=Array(); $opdatalist=Array(); $opreclist=Array();
					$arr['replay_gamenum'] = $room_gprefix.((string)$gamenum);
					$arr['replay_player'] = $data['name'];
					$arr['replay_timelen'] = $gametimelen;
					$arr['replay_optime'] = Array();
					//读对应的replay.txt，内容什么时刻对应哪个response文件
					$oplist = openfile(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/'.$data['pid'].'/replay.txt');
					$cnt = sizeof($oplist);
					//计算分几段
					//卧槽你这里直接ceil()不就好了嘛……算了不改了
					$arr['replay_datapart'] = ((int)floor($cnt/$partsize));
					if ($cnt%$partsize!=0) $arr['replay_datapart']++;
					//处理replay.txt的每一行
					for($i = 0; $i < $cnt; $i++) 
						if(!empty($oplist[$i]) && strpos($oplist[$i],',')!==false)
						{
							//点击状况，操作时间，对应的response文件
							list($oprec,$optime,$opdata) = explode(',',$oplist[$i]);
							array_push($opreclist,gdecode($oprec));
							array_push($arr['replay_optime'],round($optime*10000)/10000);
							array_push($opdatalist,$opdata);
						}
					logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-读取response');
					//将$arr保存为录像头文件
					//我勒个去sc你把进度条写在这里面……好吧仔细想想这也算是某种意义上的闭包，没毛病
					$jreplaydata = json_encode($arr);
					$jreplaydata = '___temp_s = new String(\''.base64_encode(gzencode($jreplaydata,9)).'\');
					replay_header = JSON.parse(JXG.decompress(___temp_s));
					delete ___temp_s;
					replay_data = new Array();
					replayload_progressbar('.round(100/($arr['replay_datapart']+2)).');
					jQuery.cachedScript("gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.oprecord.js");
					';
					writeover(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.header.js',$jreplaydata);
					$totsz += strlen($jreplaydata);
					logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-写头文件');
					//点击状况记录
					$jreplaydata = json_encode($opreclist);
					$jreplaydata = '___temp_s = new String(\''.base64_encode(gzencode($jreplaydata,9)).'\');
					replay_oprecord = JSON.parse(JXG.decompress(___temp_s));
					delete ___temp_s;
					replay_data = new Array();
					replayload_progressbar('.round(100/($arr['replay_datapart']+2)*2).');
					jQuery.cachedScript("gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.data.0.js");
					';
					
					writeover(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.oprecord.js',$jreplaydata);
					$totsz += strlen($jreplaydata);
					logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-写点击记录');
					//分段读取并处理response					
					for($i = 0; $i < $arr['replay_datapart']; $i++) 
					{
						$i_start=$i*$partsize;
						$i_end=min($cnt-1,$i_start+$partsize-1);
						$xdata=Array();
						for ($k=$i_start; $k<=$i_end; $k++)
							array_push($xdata,gdecode(file_get_contents($opdatalist[$k]),true));
							
						$jreplaydata = json_encode($xdata);
						$jreplaydata = '___temp_s = new String(\''.base64_encode(gzencode($jreplaydata,9)).'\');
						replay_part = JSON.parse(JXG.decompress(___temp_s));
						replay_data = replay_data.concat(replay_part);
						delete replay_part;
						delete ___temp_s;
						replayload_progressbar('.round(100/($arr['replay_datapart']+2)*($i+3)).');
						';
						if ($i+1<$arr['replay_datapart'])
							$jreplaydata .='jQuery.cachedScript("gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.data.'.($i+1).'.js");';
						else  $jreplaydata .='replay_init();';
							
						writeover(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.data.'.$i.'.js',$jreplaydata);
						
						$totsz += strlen($jreplaydata);
					}
					logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-写界面数据');
					$totsz = (round($totsz / 1024 * 10)/10).'KB';
					//保存当前的html缓存
					writeover(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.rep',base64_encode($curdatalib).','.$gamenum.','.base64_encode($data['name']).','.$totsz.','.$cnt.',');
					logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-写html缓存');
					//生成缩略图
					$pic_len = 1000;
					$pix_time = $gametimelen / $pic_len;
					
					$cur=0; $ca=count($arr['replay_optime']);
					$content=Array();
					for ($i=0; $i<$pic_len*4; $i++) for ($j=0; $j<3; $j++) $content[$i][$j]=255;
					for ($i=0; $i<$pic_len*4; $i++) $csz[$i]=0;
					for ($i=0; $i<$pic_len; $i++)
					{
						$cz=0;
						while ($cur<$ca && $arr['replay_optime'][$cur]<=$pix_time*($i+1)) { $cz++; $cur++; }
						//对相邻像素的渲染强度
						if ($pix_time<=1.2) 
							$render_strength=0.6; 
						else if ($pix_time<=2)
							$render_strength=0.35; 
						else if ($pix_time<=3)
							$render_strength=0.15;
						else  $render_strength=0;
						//单像素最高标准是0.3秒一次操作
						$color = 255*$cz/($pix_time/0.3);
						//对相邻像素渲染
						$r=$render_strength; $cs=$i;
						while ($r>0.1 && $cs+1<$pic_len)
						{
							$cs++; $csz[$cs]+=$r*$color;
							$r*=$render_strength;
						}
						$r=$render_strength; $cs=$i;
						while ($r>0.1 && $cs>0)
						{
							$cs--; $csz[$cs]+=$r*$color;
							$r*=$render_strength;
						}
						$csz[$i]+=$color;
					}
					for ($i=0; $i<$pic_len; $i++) 
					{
						if ($csz[$i]>=255) $csz[$i]=255;
						$csz[$i]=((int)round($csz[$i]));
						$content[$i][1]=255-$csz[$i];
						$content[$i][2]=255-$csz[$i];
					}
					
					file_put_contents(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.rep.bmp',\bmp_util\gen_bmp($content,$pic_len,1));
					logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-生成略缩图');
					$data['opnum']=-$cnt;
					if ($data['name']==$winname) $data['opnum']=-2000000000;
					array_push($plis,$data);
				}
			}
			
			$hs=Array();
			foreach ($plis as $key) 
			{
				if (!isset($hs[$key['opnum']])) $hs[$key['opnum']]=Array();
				array_push($hs[$key['opnum']],$key);
			}
			ksort($hs);
			
			$sstr='';
			foreach ($hs as $key => $value)
				foreach ($value as $wz)
					$sstr.=$wz['pid'].',';
			
			file_put_contents(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.rep.index',$sstr);
			logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-储存录像索引');
			clear_dir(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/',1);
			global $___MOD_TMP_FILE_DIRECTORY;
			clear_dir($___MOD_TMP_FILE_DIRECTORY.$room_prefix.'_/',1);
			logmicrotime('房间'.$room_gprefix.'-第'.$gamenum.'局-清空目录');
		}
	}
	
	function replay_validify_record($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($str=='e') return Array($str);
		$arr=Array();
		$arr=explode('.',$str);
		$narr=Array();
		for ($i=0; $i<count($arr); $i++)
			if ($arr[$i]!='') array_push($narr,$arr[$i]);
		$arr=$narr;
		
		for ($i=0; $i<count($arr); $i++)
		{
			if (strlen($arr[$i])>3) return false;
			for ($j=0; $j<strlen($arr[$i]); $j++)
				if ($arr[$i][$j]<'0' || $arr[$i][$j]>'9')
					return false;
		}
		return $arr;
	}
	
	function replay_record_op($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$arr=Array();
		$arr=explode(',',$str);
		
		//清除空白、过深的DOM Path和不合法DOM Path（基本是恶意的）
		$narr=Array();
		for ($i=0; $i<count($arr); $i++)
			if ($arr[$i]!='' && strlen($arr[$i])<=200 && (!(replay_validify_record($arr[$i])===false)))
				array_push($narr,replay_validify_record($arr[$i]));
		$arr=$narr; unset($narr);
		
		//只留最后10次点击
		if (count($arr)>=10) 
		{
			$narr=Array();
			for ($i=count($arr)-10; $i<count($arr); $i++) array_push($narr,$arr[$i]);
			$arr=$narr; unset($narr);
		}
		
		return gencode($arr);
	}
	
	function get_replay_by_gnum($gnum,$wmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$room_gprefix = '';
		if ($room_prefix!='') $room_gprefix = ((string)$room_prefix[0]).'.';
		if (!file_exists(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gnum.'.rep.index'))
		{
			include template('MOD_REPLAY_GNUM_NO_REPLAY');
			return;
		}
		$arr=explode(',',file_get_contents(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gnum.'.rep.index'));
		$lis=Array(); 
		if ($wmode!=4 && $wmode!=1 && $wmode!=6) $ff=1;
		foreach ($arr as $key)
		{
			if ($key=='') continue;
			$x=(int)$key;
			if (file_exists(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gnum.'.'.$x.'.rep'))
			{
				list($repdatalib,$repgnum,$repname,$repsz,$repopcnt) = explode(',',file_get_contents(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gnum.'.'.$x.'.rep'));
				$repdatalib=base64_decode($repdatalib);
				$repname=base64_decode($repname);
				$d=Array(); $d['repname']=$repname; $d['repsz']=$repsz; $d['repopcnt']=$repopcnt; $d['link']=$gnum.'.'.$x;
				if ($ff) $d['is_winner']=1;
				array_push($lis,$d);
			}
			$ff=0;
		}
		include template('MOD_REPLAY_GNUM_DATA');
	}
}

?>
