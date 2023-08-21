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
		if(in_array($gametype, $replay_ignore_mode)){
			$replay_flag = 0;
		}
		//以下开始处理
		if($replay_flag){
			//startmicrotime();
			$curdatalib = file_get_contents(GAME_ROOT.'./gamedata/javascript/datalib.current.txt');
			//获取游戏时长和胜利者名字，其实可以简化掉数据库读取的
			$result = $db->query("SELECT winner,gstime,getime FROM {$wtablepre}history WHERE gid={$gamenum}");
			$data = $db->fetch_array($result);
			$gametimelen = (int)$data['getime']-(int)$data['gstime'];
			$winname = $data['winner'];
			//对每个存在的玩家挨个进行处理
			$result = $db->query("SELECT name,pid FROM {$tablepre}players WHERE type = 0");
			$plis = Array();
			$filelist = array();
			//房间前缀，一般是's'
			$room_gprefix = '';
			if (room_check_subroom($room_prefix)) $room_gprefix = room_prefix_kind($room_prefix).'.';
			//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-前序处理');
			while($data = $db->fetch_array($result))
			{
				if (is_dir(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/'.$data['pid']) && file_exists(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/'.$data['pid'].'/replay.php'))
				{
					$totsz = 0;
					//$arr=录像头文件，记录基本信息和每次操作的时刻
					$arr=Array(); $opdatalist=Array(); $opreclist=Array();
					$arr['replay_gamenum'] = $room_gprefix.((string)$gamenum);
					$arr['replay_player'] = $data['name'];
					$arr['replay_timelen'] = $gametimelen;
					$arr['replay_optime'] = Array();
					//读对应的replay.txt，内容什么时刻对应哪个response文件
					$oplist = openfile(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/'.$data['pid'].'/replay.php');
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
					//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-读取response');
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
					$filelist[] = GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.header.js';
					$totsz += strlen($jreplaydata);
					//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-写头文件');
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
					$filelist[] = GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.oprecord.js';
					$totsz += strlen($jreplaydata);
					//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-写点击记录');
					//分段读取并处理response					
					for($i = 0; $i < $arr['replay_datapart']; $i++) 
					{
						$i_start=$i*$partsize;
						$i_end=min($cnt-1,$i_start+$partsize-1);
						$xdata=Array();
						for ($k=$i_start; $k<=$i_end; $k++) {
							$fc = file_exists($opdatalist[$k]) ? file_get_contents($opdatalist[$k]) : '';
							array_push($xdata,gdecode($fc,true));
						}							
							
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
						$filelist[] = GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.replay.data.'.$i.'.js';
						$totsz += strlen($jreplaydata);
					}
					//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-写界面数据');
					$totsz = (round($totsz / 1024 * 10)/10).'KB';
					//保存当前的html缓存
					writeover(GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.rep',base64_encode($curdatalib).','.$gamenum.','.base64_encode($data['name']).','.$totsz.','.$cnt.',');
					$filelist[] = GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.rep';
					//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-写html缓存');
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
					$filelist[] = GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.'.$data['pid'].'.rep.bmp';
					//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-玩家'.$data['pid'].'-生成略缩图');
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
			unset($jreplaydata);
			$filelist[] = GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.rep.index';
			//打包成文件
			$foldfile = GAME_ROOT.'./gamedata/replays/'.$room_gprefix.$gamenum.'.dat';
			fold($foldfile, $filelist);
			//如果设定为远程储存，则发送到远端，并删除打包的文件
			//POST传输率简直慢得惊人，不能直接发送！
			if(!empty($replay_remote_storage) && $replay_remote_send){
				$rpurl = $replay_remote_storage;
				$context = array(
					'sign'=>$replay_remote_storage_sign, 
					'pass'=>$replay_remote_storage_key, 
					'cmd'=>'storage_req', 
					'filename'=>$room_gprefix.$gamenum.'.dat',
					'callurl'=>gurl().'replay_receive.php',
					//'content'=>file_get_contents($foldfile),
					'datalibname'=>$curdatalib,
					//'datalibcont'=>''//gencode(file_get_contents(GAME_ROOT.'./gamedata/javascript/'.$curdatalib))
				);
				curl_post($rpurl, $context, NULL, 0.1);//因为是对方反向请求，相当于异步调用
				
				//if(strpos($ret,'Successfully Received')!==false) unlink($foldfile);
			}
			//删除源文件
			foreach($filelist as $fv) unlink($fv);
			//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-储存录像索引');
		}
		// 注意虽然tmp文件夹下所有其他目录都是以room_prefix作为索引
		// tmp/response是以room_id作为索引的
		// 为什么不统一一下呢？因为我不想在socket.func里include room.func……
		// 虽然感觉是地雷但想了一下好像以后想踩到也挺难的
		
		//这里全部坑掉，现在是在游戏开局时清空
		
//		clear_dir(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/',1);
//		global $___MOD_TMP_FILE_DIRECTORY;
//		clear_dir($___MOD_TMP_FILE_DIRECTORY.$room_id.'_/',1);
		//logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-清空目录');
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
		if (room_check_subroom($room_prefix)) $room_gprefix = room_prefix_kind($room_prefix).'.';
		$replay_path = GAME_ROOT.'./gamedata/replays/';
		if (!file_exists($replay_path.$room_gprefix.$gnum.'.rep.index'))
		{
			if(file_exists($replay_path.$room_gprefix.$gnum.'.dat'))//先检查是否存在打包文件，如果是，则临时展开
			{
				unfold($replay_path.$room_gprefix.$gnum.'.dat');
			}else{
				$flag = get_replay_remote($room_gprefix.$gnum);
				if(!$flag){
					include template('MOD_REPLAY_GNUM_NO_REPLAY');
					return;
				}
			}
		}
		$arr=explode(',',file_get_contents($replay_path.$room_gprefix.$gnum.'.rep.index'));
		$lis=Array(); 
		if ($wmode!=4 && $wmode!=1 && $wmode!=6) $ff=1;//非无人参加、全灭、GM中止，需要显示优胜者，默认第一个是优胜者
		foreach ($arr as $key)
		{
			if ($key=='') continue;
			$x=(int)$key;
			if (file_exists($replay_path.$room_gprefix.$gnum.'.'.$x.'.rep'))
			{
				list($repdatalib,$repgnum,$repname,$repsz,$repopcnt) = explode(',',file_get_contents($replay_path.$room_gprefix.$gnum.'.'.$x.'.rep'));
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
	
	//调用远程录像
	function get_replay_remote($repfilename){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$remote_rdata = '';
		if(!empty($replay_remote_storage)){
			//先获取录像文件
			$rpurl = $replay_remote_storage;
			$context = array('sign'=>$replay_remote_storage_sign, 'cmd'=>'loadrep', 'filename'=>$repfilename.'.dat');
			$remote_rdata = curl_post($rpurl, $context, NULL, 30);
			if(strpos($remote_rdata, 'does not exist')===false && strpos($remote_rdata, 'Bad command')===false && strpos($remote_rdata, 'Invalid Sign')===false){
				//然后尝试获取对应的datalib.js
				$context = array('sign'=>$replay_remote_storage_sign, 'cmd'=>'checkdatalib', 'filename'=>$repfilename.'.dat');
				$remote_datalibname = curl_post($rpurl, $context);
				$datalibpath = GAME_ROOT.'./gamedata/javascript/'.$remote_datalibname;
				if($remote_datalibname && !file_exists($datalibpath)) {
					$context = array('sign'=>$replay_remote_storage_sign, 'cmd'=>'loaddatalib', 'filename'=>$remote_datalibname);
					$ret2 = curl_post($rpurl, $context);
					if(strpos($ret2, 'does not exist')===false && strpos($ret2, 'Bad command')===false && strpos($ret2, 'Invalid Sign')===false){
						file_put_contents($datalibpath, $ret2);
					}
				}
			}else $remote_rdata = '';
		}
		$ret = false;
		if(!empty($remote_rdata)) {
			file_put_contents(GAME_ROOT.'./gamedata/replays/'.$repfilename.'.dat', $remote_rdata);
			unfold(GAME_ROOT.'./gamedata/replays/'.$repfilename.'.dat');
			$ret = true;
		}
		return $ret;
	}
}

?>