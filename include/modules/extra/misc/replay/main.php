<?php

namespace replay
{
	//录像文件每块保存的操作数目
	//一般来说这个值越大压缩效果越好，但更吃内存
	$partsize=1000;
	
	function init() {}
	
	function gamestate_prepare_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $___MOD_SRV;
		if (!$___MOD_SRV) return $chprocess();
		//游戏准备时，保存上局的录像文件并清空目录准备下一局
		$curdatalib = file_get_contents(GAME_ROOT.'./gamedata/javascript/datalib.current.txt');
		eval(import_module('replay','sys'));
		$result = $db->query("SELECT name,gstime,getime FROM {$tablepre}winners WHERE gid={$gamenum}");
		$data = $db->fetch_array($result);
		$gametimelen = (int)$data['getime']-(int)$data['gstime'];
		$winname = $data['name'];
		$result = $db->query("SELECT name,pid FROM {$tablepre}players WHERE type = 0");
		$plis = Array();
		while($data = $db->fetch_array($result))
		{
			if (is_dir(GAME_ROOT.'./gamedata/tmp/replay/'.$data['pid']) && file_exists(GAME_ROOT.'./gamedata/tmp/replay/'.$data['pid'].'/replay.txt'))
			{
				$totsz = 0;
				$arr=Array(); $opdatalist=Array(); $opreclist=Array();
				$arr['replay_gamenum'] = $gamenum;
				$arr['replay_player'] = $data['name'];
				$arr['replay_timelen'] = $gametimelen;
				$arr['replay_optime'] = Array();
				$oplist = openfile(GAME_ROOT.'./gamedata/tmp/replay/'.$data['pid'].'/replay.txt');
				$cnt = sizeof($oplist);
				$arr['replay_datapart'] = ((int)floor($cnt/$partsize));
				if ($cnt%$partsize!=0) $arr['replay_datapart']++;
				for($i = 0; $i < $cnt; $i++) 
					if(!empty($oplist[$i]) && strpos($oplist[$i],',')!==false)
					{
						list($oprec,$optime,$opdata) = explode(',',$oplist[$i]);
						array_push($opreclist,json_decode(mgzdecode(base64_decode($oprec))));
						array_push($arr['replay_optime'],round($optime*10000)/10000);
						array_push($opdatalist,$opdata);
					}
					
				$jreplaydata = compatible_json_encode($arr);
				$jreplaydata = '___temp_s = new String(\''.base64_encode(gzencode($jreplaydata,9)).'\');
				replay_header = JSON.parse(JXG.decompress(___temp_s));
				delete ___temp_s;
				replay_data = new Array();
				replayload_progressbar('.round(100/($arr['replay_datapart']+2)).');
				jQuery.cachedScript("gamedata/replays/'.$gamenum.'.'.$data['pid'].'.replay.oprecord.js");
				';
				
				writeover(GAME_ROOT.'./gamedata/replays/'.$gamenum.'.'.$data['pid'].'.replay.header.js',$jreplaydata);
				$totsz += strlen($jreplaydata);
				
				$jreplaydata = compatible_json_encode($opreclist);
				$jreplaydata = '___temp_s = new String(\''.base64_encode(gzencode($jreplaydata,9)).'\');
				replay_oprecord = JSON.parse(JXG.decompress(___temp_s));
				delete ___temp_s;
				replay_data = new Array();
				replayload_progressbar('.round(100/($arr['replay_datapart']+2)*2).');
				jQuery.cachedScript("gamedata/replays/'.$gamenum.'.'.$data['pid'].'.replay.data.0.js");
				';
				
				writeover(GAME_ROOT.'./gamedata/replays/'.$gamenum.'.'.$data['pid'].'.replay.oprecord.js',$jreplaydata);
				$totsz += strlen($jreplaydata);
				
				for($i = 0; $i < $arr['replay_datapart']; $i++) 
				{
					$i_start=$i*$partsize;
					$i_end=min($cnt-1,$i_start+$partsize-1);
					$xdata=Array();
					for ($k=$i_start; $k<=$i_end; $k++)
						array_push($xdata,json_decode(mgzdecode(base64_decode(file_get_contents($opdatalist[$k]))),true));
						
					$jreplaydata = compatible_json_encode($xdata);
					$jreplaydata = '___temp_s = new String(\''.base64_encode(gzencode($jreplaydata,9)).'\');
					replay_part = JSON.parse(JXG.decompress(___temp_s));
					replay_data = replay_data.concat(replay_part);
					delete replay_part;
					delete ___temp_s;
					replayload_progressbar('.round(100/($arr['replay_datapart']+2)*($i+3)).');
					';
					if ($i+1<$arr['replay_datapart'])
						$jreplaydata .='jQuery.cachedScript("gamedata/replays/'.$gamenum.'.'.$data['pid'].'.replay.data.'.($i+1).'.js");';
					else  $jreplaydata .='replay_init();';
						
					writeover(GAME_ROOT.'./gamedata/replays/'.$gamenum.'.'.$data['pid'].'.replay.data.'.$i.'.js',$jreplaydata);
					
					$totsz += strlen($jreplaydata);
				}
				
				$totsz = (round($totsz / 1024 * 10)/10).'KB';
				writeover(GAME_ROOT.'./gamedata/replays/'.$gamenum.'.'.$data['pid'].'.rep',base64_encode($curdatalib).','.$gamenum.','.base64_encode($data['name']).','.$totsz.','.$cnt.',');
				
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
				
				file_put_contents(GAME_ROOT.'./gamedata/replays/'.$gamenum.'.'.$data['pid'].'.rep.bmp',\bmp_util\gen_bmp($content,$pic_len,1));
			
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
		
		file_put_contents(GAME_ROOT.'./gamedata/replays/'.$gamenum.'.rep.index',$sstr);
		
		clear_dir(GAME_ROOT.'./gamedata/tmp/replay/',1);
		global $___MOD_TMP_FILE_DIRECTORY;
		clear_dir($___MOD_TMP_FILE_DIRECTORY,1);
		$chprocess();
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
		
		return base64_encode(gzencode(compatible_json_encode($arr)));
	}
	
	function get_replay_by_gnum($gnum,$wmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!file_exists(GAME_ROOT.'./gamedata/replays/'.$gnum.'.rep.index'))
		{
			include template('MOD_REPLAY_GNUM_NO_REPLAY');
			return;
		}
		$arr=explode(',',file_get_contents(GAME_ROOT.'./gamedata/replays/'.$gnum.'.rep.index'));
		$lis=Array(); 
		if ($wmode!=4 && $wmode!=1 && $wmode!=6) $ff=1;
		foreach ($arr as $key)
		{
			if ($key=='') continue;
			$x=(int)$key;
			if (file_exists(GAME_ROOT.'./gamedata/replays/'.$gnum.'.'.$x.'.rep'))
			{
				list($repdatalib,$repgnum,$repname,$repsz,$repopcnt) = explode(',',file_get_contents(GAME_ROOT.'./gamedata/replays/'.$gnum.'.'.$x.'.rep'));
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
