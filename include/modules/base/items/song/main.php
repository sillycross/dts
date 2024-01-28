<?php

namespace song
{
	$ef_type = array(
		'rp' => '■',
		'hp' => '生命',
		'mhp' => '最大生命',
		'sp' => '体力',
		'msp' => '最大体力',
		'ss' => '歌魂',
		'mss' => '最大歌魂',
		'att' => '攻击力',
		'def' => '防御力',
		'money' => '金钱',
		'rage' => '怒气',
		'exp' => '经验值',
		'wp' => '殴系熟练度',
		'wk' => '斩系熟练度',
		'wg' => '射系熟练度',
		'wc' => '投系熟练度',
		'wd' => '爆系熟练度',
		'wf' => '灵系熟练度',
		'wep' => '武器',
		'arb' => '身体防具',
		'arh' => '头部防具',
		'ara' => '手部防具',
		'arf' => '足部防具',
		'art' => '饰物',
		'itm' => '包裹道具',
	);
	
	$ef_type2 = array(
		'k' => '类别',
		'e' => '效果值',
		's' => '耐久值',
		'sk' => '属性',
	);
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(strpos($k,'ss')===0){
			$sn = check_sname($n);
			switch ($sn) {//这段是用AI从if else转换过来的，如果能顺利执行我就回来点个赞 //可以执行，点赞
				case 'Alicemagic':
					$ret .= '歌唱：使你和同地区玩家的防御力上升30';
					break;
				case 'Crow Song':
					$ret .= '歌唱：使你和同地区玩家的攻击力上升30';
					break;
				case 'KARMA':
					$ret .= '歌唱：使你和同地区玩家的RP变为0';
					break;
				case '驱寒颂歌':
					$ret .= '歌唱：使你和同地区玩家的最大生命、最大体力和金钱上升10，RP下降10';
					break;
				case 'Butterfly':
					$ret .= '歌唱：使你和同地区玩家的生命和体力回复2000（可超出最大值），且武器耐久变为∞';
					break;
				case '小苹果':
					$ret .= '歌唱：使你和同地区玩家的体力下降100，但怒气增加5';
					break;
				case '空想神话':
					$ret .= '歌唱：耗尽歌魂，你和同地区玩家的攻击、防御、生命、体力、怒气、各系熟练、武器和防具耐久随机增加，增加值总和不大于你消耗的歌魂';
					break;
				case 'ぼくのフレンド':
					$ret .= '歌唱：使你和同地区玩家获得技能「朋友」';
					break;
				case '星めぐりの歌':
					$ret .= '歌唱：使你和同地区玩家的射熟和投熟上升30';
					break;
				case 'CANOE':
					$ret .= '歌唱：使你和同地区玩家的爆熟和灵熟上升30';
					break;
				case '遥か彼方':
					$ret .= '歌唱：使你和同地区玩家的殴熟和斩熟上升30';
					break;
				case '雨だれの歌':
					$ret .= '歌唱：使你和同地区玩家的生命上限上升10，体力上限上升50';
					break;
				case 'More One Night':
					$ret .= '歌唱：使你和同地区玩家的最大生命增加，增加值等于场上死亡NPC数，但无法再赢得「最后幸存」胜利';
					break;
				case 'Baba yetu':
					$ret .= '歌唱：使你和同地区玩家获得12点经验值（不会立刻升级）';
					break;
				case 'Clear Morning':
					$ret .= '歌唱：使你和同地区玩家的生命回复1000（可超出最大值）';
					break;
				case 'Never Gonna Give You Up':
					$ret .= '歌唱：使你和同地区玩家的怒气清空，但也有概率变为最大值';
					break;
				case '『勇者』':
					$ret .= '歌唱：使你和同地区玩家的灵熟上升30，其他系熟练度上升5';
					break;
				default :
					$ret .= '好像不存在这样一首歌呢……';
					break;
			}
		}
		return $ret;
	}
	
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['ss'] = '歌词卡片';
		$iteminfo['HM'] = '歌魂增加';
		$iteminfo['HT'] = '歌魂恢复';
		
		if (defined('MOD_NOISE'))
		{
			eval(import_module('noise'));
			$noiseinfo['ss_CS']='《Crow Song》';
			$noiseinfo['ss_AM']='《Alicemagic》';
			$noiseinfo['KARMA']='■';
			$noiseinfo['ss_HWC']='《驱寒颂歌》';
			$noiseinfo['ss_BF']='《Butterfly》';
			$noiseinfo['ss_XPG']='《小苹果》';
			$noiseinfo['ss_kuusou']='《空想神话》';
			$noiseinfo['ss_friend']='《ぼくのフレンド》';
			$noiseinfo['ss_planet']='《星めぐりの歌》';
			$noiseinfo['ss_rewrite']='《CANOE》';
			$noiseinfo['ss_lb']='《遥か彼方》';
			$noiseinfo['ss_amadare']='《雨だれの歌》';
			$noiseinfo['ss_mon']='《More One Night》';
			$noiseinfo['ss_BY']='《Baba yetu》';
			$noiseinfo['ss_CM']='《Clear Morning》';
			$noiseinfo['ss_NG']='《Never Gonna Give You Up》';
			$noiseinfo['ss_ys']='『勇者』';
		}
	}
	
	function ss_get_affected_players($pos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		//获得受影响的自身以外玩家的pid
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$pos' AND type=0 AND hp>0 AND pid != '$pid'");
		if(!$db->num_rows($result)) return array();
		$list = array();
		while($r = $db->fetch_array($result)){
			$list[] = $r['pid'];
		}
		//然后挨个获得数据。因为可能涉及到技能、上限之类的，不能直接粗暴UPDATE
		//这里同时也加了锁
		$ret = array();
		foreach($list as $pdid){
			$ret[] = \player\fetch_playerdata_by_pid($pdid);
		}
		return $ret;
	}
	
	//单项处理，返回一个提示信息
	function ss_data_proc_single($sname, &$pdata, $effect, $sscost=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ss_log = $ss_log_2 = array();
		if(empty($effect)) return $ss_log;
		eval(import_module('sys','song'));
		$pdata['mrage'] = \rage\get_max_rage($pdata);
		$timeflag = isset($effect['time']);
		//一些特殊歌的处理
		if(!empty($effect['special'])){
			if(1==$effect['special']){//空想神话，消耗所有歌魂，随机增加耐久
				$esum = $sscost;
				$kinds = array('att','def','hp','sp','rage','wp','wk','wg','wc','wd','wf','weps','arbs','arhs','aras','arfs','arts');
				$ups = array();
				$imax = sizeof($kinds);
				for($i=0;$i<$imax;$i++){
					if($esum>0) {
						if($i == $imax-1){
							$rand = $esum;
						}else{
							$rand = rand(0,round($esum/4));
						}
						$esum-=$rand;
						$ups[] = $rand;
					}else{
						$ups[] = 0;
					}
				}
				shuffle($ups);
				foreach($kinds as $sei => $sev){
					if($ups[$sei] > 0) $effect[$sev] = $ups[$sei];
				}
			}
			elseif(2==$effect['special']){//More One Night，按场上死亡NPC数增加最大生命
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type>0 AND hp=0");
				$dpnum = $db->num_rows($result);
				$dpnum = (int)$dpnum;
				$effect['mhp'] = $dpnum;
			}
			elseif(3==$effect['special']){//Never Gonna Give You Up，随机让受影响玩家的怒气变为最大值或者清零
				if(defined('MOD_RAGE')){
					$rate = 30;//变为最大值的概率
					if(rand(0,99)<$rate){
						$effect['rage'] = $pdata['mrage'];
					}else{
						$effect['rage'] = -$pdata['rage'];
					}
				}
			}
			unset($effect['special']);
		}
		foreach($effect as $ek => $ev){
			if('addskill' == $ek){
				if(defined('MOD_SKILLBASE')){
					eval(import_module('clubbase'));
					if(!is_array($ev) && is_numeric($ev)) $ev = array($ev);
					foreach($ev as $skv){
						if(!\skillbase\skill_query($skv, $pdata)){
							\skillbase\skill_acquire($skv, $pdata);
							$ss_log[] = '获得了技能<span class="cyan b">「'.$clubskillname[$skv].'」</span>';
							if ($timeflag)
							{
								$tsk_time = round($effect['time'] * ss_factor($pdata));
								$ss_log[] = "持续时间<span class=\"yellow b\">".$tsk_time."</span>秒<br>";
								\skillbase\skill_setvalue($skv, 'tsk_expire', $now + $tsk_time, $pdata);
							}
						}
					}
				}
			}
			elseif(isset($pdata[$ek])){
				//如果变化量是数组，那么把'e'键当做变化量，其他的当做参数
				if(is_array($ev)) {
					$o_ev = $ev;
					$ev = $ev['e'];
				}
				//生成提示变化了哪里，目前仅支持HP SP 歌魂 怒气 金钱 攻防 经验值 装备道具的效果和耐久值
				if(strpos($ek,'wep')===0 || strpos($ek,'ar')===0 || strpos($ek,'itm')===0) {
					$ss_tn = $ef_type[substr($ek,0,3)];
					if(strpos($ek,'itm')===0) {
						$ss_tn .= substr($ek,3,1).$ef_type2[substr($ek,4,1)];
					}else{
						$ss_tn .= $ef_type2[substr($ek,3,1)];
					}
				}else{
					$ss_tn = $ef_type[$ek];
				}
				if(strpos($ev,'=')===0) {//变化值
					$change = substr($ev,1);
					//如果变化量是数值，那么变化量乘以一个系数
					if(is_numeric($change)) $change = round($ev * ss_factor($pdata));
					$pdata[$ek] = $change;
					$ss_log[] = $ss_tn.'<span class="yellow b">变成了'.$change.'</span>';
				}elseif(is_numeric($ev) && is_numeric($pdata[$ek]) && ($pdata[$ek] > 0 || !ss_check_s($ek))) {//无限耐的统一忽略
					//如果变化量是数值，那么变化量乘以一个系数
					$change = round($ev * ss_factor($pdata));
					if($change > 0) {//增加值，要判定是否最大值。如果设置了ignore_limit则无视最大值
						if(!isset($pdata['m'.$ek]) || $pdata[$ek] < $pdata['m'.$ek] || !empty($o_ev['ignore_limit'])){
							if(isset($pdata['m'.$ek]) && $pdata[$ek] + $change > $pdata['m'.$ek] && empty($o_ev['ignore_limit'])) {
								$change = $pdata['m'.$ek] - $pdata[$ek];
							}
							$pdata[$ek] += $change;
						}else{//超过最大值的不改动
							$change = 0;
						}
						if($change) $ss_log[] = $ss_tn.'<span class="cyan b">增加了'.$change.'</span>';
					}else{//减少值直接减
						if($pdata[$ek] + $change < 1 && in_array($ek, array('hp','mhp','sp','msp'))) {
							$change = 1 - $pdata[$ek];//生命体力不会降低到小于1，也就是不会唱死人
						}elseif($pdata[$ek] + $change < 0){
							$change = -$pdata[$ek];
						}
						$pdata[$ek] += $change;
						if($change != 0) $ss_log[] = $ss_tn.'<span class="red b">减少了'.(0-$change).'</span>';
					}
					//记录增益/减益，需要模组skill182
					if ($timeflag)
					{
						$tempbuff_log = ss_record_tempbuff($ek, $change, $effect['time'], $pdata);
						if (!empty($tempbuff_log)) $ss_log[] = $tempbuff_log;
					}
				}
				//装备耐久变成0的情况
				if($change <= 0 && !$pdata[$ek] && ss_check_s($ek)){
					$itmpos = (strpos($ek,'wep')===0 || strpos($ek,'ar')===0) ? substr($ek,0,3) : substr($ek,0,3).substr($ek,3,1);
					$itmname = $pdata[$itmpos];
					$ss_log_2[] = $itmname;
				}
			}
		}
		unset($pdata['mrage']);
		$ss_log_f = '';
		if(!empty($ss_log)) $ss_log_f .= '歌声让你的'.implode('，',$ss_log).'。<br>';
		if(strpos($ss_log_f,'获得了技能')!==false) $ss_log_f = str_replace('歌声让你的获得了技能','歌声让你获得了技能',$ss_log_f);
		if(!empty($ss_log_2)) $ss_log_f .= '<!--SPERATOR--><span class="red b">你的'.implode('、',$ss_log_2).'损坏了！</span><br>';
		return $ss_log_f;
	}
	
	//判定指定的字段是不是装备道具的耐久
	function ss_check_s($dname){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $dname=='weps' || (substr($dname,0,2) == 'ar' && substr($dname,strlen($dname)-1,1) == 's') || substr($dname,0,4) == 'itms';
	}
	
	//在skill182中记录歌曲临时增益和处理增益的失去，返回的是显示增益时长的log
	function ss_record_tempbuff($key, $value, $bufftime, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return '';
	}
	
	//歌效果处理
	function ss_data_proc($sname, $effect, $sscost=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if(empty($effect)) return;
		//先处理自己
		eval(import_module('sys','player','logger'));
		$log .= ss_data_proc_single($sname, $sdata, $effect, $sscost);
		//获取所有影响到的玩家		
		$pdlist = ss_get_affected_players($pls);
		if(empty($pdlist)) return;
		//依次处理玩家
		foreach($pdlist as $pdata){
			$ss_log = ss_data_proc_single($sname, $pdata, $effect, $sscost);
			if(empty($ss_log)) {//歌的返回值是空的，说明没有影响任何参数。
				$ss_log = '你听到了歌声，但并没有受到触动。<br>';
			}
			\logger\logsave ( $pdata['pid'], $now, $ss_log ,'o');			
			\player\player_save($pdata);
		}
	}
	
	//消耗歌魂的前置处理
	function ss_cost_proc($cost)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $cost;
	}
	
	//歌唱效果加成系数
	function ss_factor(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_available_songlist(&$pdata = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$pdata)
		{
			eval(import_module('player'));
			$pdata = $sdata;
		}
		$learnedsongs = \skillbase\skill_getvalue(1003,'learnedsongs',$pdata);
		if (empty($learnedsongs)) $ls = array();
		else $ls = explode('_',$learnedsongs);
		if ($pdata['artk'] == 'ss')
		{
			eval(import_module('song'));
			$sn = check_sname($pdata['art']);
			if (!empty($sn))
			{
				$k = 0;
				foreach($songlist as $skey => $sval)
				{
					if($sval['songname'] == $sn)
					{
						$k = $skey;
						break;
					}
				}
			}
			if ($k && !in_array($k, $ls)) $ls[] = $k;
		}
		return $ls;
	}
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','noise','song'));
		$songcfg = NULL;
		foreach($songlist as $skey => $sval){
			if($sval['songname'] == $sn) {
				$songcfg = $sval;
				$k = $skey;
				break;
			}
		}
		if(!$songcfg) {
			$log .= '好像不存在这样一首歌呢……<br>';
			return;
		}
		
		if (!in_array($k, get_available_songlist($sdata)))
		{
			$log .= '好像你并不会唱这首歌……<br>';
			return;
		}
		
		$r = ss_cost_proc($songcfg['cost']);
		if($r === 'MAX') $r = max(1, $ss);
		$nkey = $songcfg['noisekey'];

		if ($ss>=$r){
			$ss-=$r;
			$log.="消耗<span class=\"yellow b\">{$r}</span>点歌魂，歌唱了<span class=\"yellow b\">{$noiseinfo[$nkey]}</span>。<br><br>";
		}else{
			$log.="需要至少<span class=\"yellow b\">{$r}</span>点歌魂才能唱这首歌！<br>";
			return;
		}
		addnews($now,'song',$name,$plsinfo[$pls],$songcfg['songname']);
		
		//歌词显示部分
		$lyricnum = sizeof($songcfg['lyrics']);
		$songchat = '';$songprog = 1;
		$songloop = defined('MOD_SKILL1003');
		if($songloop) {
			$songprog = empty($songcfg['lyricdisp']) ? 1 : $songcfg['lyricdisp'];
			$songkind = \skillbase\skill_getvalue(1003,'songkind');
			$songpos = $songkind == $sn ? (int)\skillbase\skill_getvalue(1003,'songpos') : 0;//如果上一次唱的不是这首歌则从头
		}
		if(isset($songcfg['lyrics_ruby'])) {
			$song_font_size = 16;
			$song_line_height = 28;
		}
		//生成要显示和发送的歌词列表
		$songshowlist = $songchatlist = array();
		
		//显示歌词列表
		//如果技能1003存在，且有同时显示的歌词数目限制，则只显示那些歌词。否则全部显示
		if($songloop && !empty($songcfg['lyricdisp'])) {
			for($i=$songpos; $i < $songprog+$songpos; $i++)
				$songshowlist[] = $i;
		}else{
			$songshowlist = range(0,$lyricnum-1);
		}
		
		//聊天记录列表
		//如果技能1003存在，会按顺序发聊天记录，否则只发前两句加省略号
		if($songloop) {
			for($i=$songpos; $i < $songprog+$songpos; $i++)
				$songchatlist[] = $i;
		}else{
			for($i=0;$i<$songchatlimit;$i++)
				$songchatlist[] = $i;
		}
		
		//显示歌词
		foreach($songshowlist as $i)
		{
			$ss_log = '<span style="font-size:<:fs:>px;line-height:<:lh:>px">'.$songcfg['lyrics'][$i].'</span>';
			if(isset($songcfg['lyrics_ruby'])) {
				$ss_log = '<ruby>'.$ss_log.'<rt>'.$songcfg['lyrics_ruby'][$i].'</rt></ruby>';
				$replace = '28px';
			}
			$ss_log = str_replace('<:lh:>', $song_line_height, str_replace('<:fs:>', $song_font_size, $ss_log));
			$log .= $ss_log.'<br>';
		}
		
		//显示聊天记录
		if(sizeof($songchatlist) <= 1) $songchat .= $songcfg['lyrics'][$songchatlist[0]];
		else {
			foreach($songchatlist as $i)
			{
				$songchat .= $songcfg['lyrics'][$i].'　';
			}
			$songchat .= '……';
		}
		$log .= '<br>';
		\sys\addchat(0, $songchat, $name);
		
		if($songloop) {
			if($songpos + $songprog >= sizeof($songcfg['lyrics']) ) $songpos = 0;
			else $songpos += $songprog;
			\skillbase\skill_setvalue(1003,'songpos',$songpos);
			\skillbase\skill_setvalue(1003,'songkind',$sn);
		}
		if (defined('MOD_NOISE') && !empty($nkey)) \noise\addnoise($pls,$nkey,$pid);
		
		//歌效果处理核心函数
		ss_data_proc($sn, get_song_effect($songcfg), $r);
		//唱完之后学会这首歌
		learn_song_process($sn);
		
		return;
	}
	
	function learn_song_process($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('song'));
		//如果该歌曲存在，则记录
		//考虑到之后可能会有技能不需要唱就能学会，所以还是做一个是否存在的判定
		foreach($songlist as $skey => $sval)
		{
			if ($sval['songname'] === $sn)
			{
				$learnedsongs = \skillbase\skill_getvalue(1003,'learnedsongs');
				if (empty($learnedsongs)) $ls = array();
				else $ls = explode('_',$learnedsongs);
				if (!in_array($skey, $ls)) 
				{
					if(empty($ls)){//第一次学会唱歌给一个提示
						$tip = '<br>你耳边传来奇怪的低语：<span class="white b">“是爱唱歌的孩子呢！如果你学会了10首歌曲，我会额外教你一首歌哦！”</span><br><br>';
					}
					$ls[] = $skey;
					if(sizeof($ls) == 10) {//学会第10首歌给一首额外的
						$present = 16;
						if(!in_array($present,$ls)){
							eval(import_module('noise'));
							$ls[] = $present;
							$tip = '<br><span class="white b">“你真的学了10首歌？……好吧，这是你应得的（笑）”</span><br>奇怪的声音额外教会了你<span class="yellow b">'.$noiseinfo['ss_NG'].'</span>！<br><img src="img/ng.gif" style="width:350px;width:270px" /><br><br>';
						}else{
							$tip = '<br><span class="white b">“奇怪，你已经学会了，怎么回事呢？”</span><br><br>';
						}
					}
					$learnedsongs = implode('_',$ls);
					\skillbase\skill_setvalue(1003,'learnedsongs',$learnedsongs);
					eval(import_module('logger'));
					$log .= "你学会了歌曲<span class=\"yellow b\">《{$sval['songname']}》</span>！<br>";
					
					if(!empty($tip)) $log .= $tip;
				}
				break;
			}
		}
	}
	
	function get_song_effect($songcfg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $songcfg['effect'];
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		eval(import_module('sys','player','logger','song'));
		if (strpos ( $itmk, 'HM' ) === 0) {
			$mss+=$itme;
			$ss+=$itme;//2023.11.05 现在和生命体力一样，同时增加最大歌魂和当前歌魂，没必要弄那一点克扣了
//			if($ss+$itme <= $mss) $ss+=$itme;//现有歌魂加完以后不会超限时，也增加物品的效果值
//			elseif($ss <= $mss) $ss = $mss;//现有歌魂加完以后会超限，加到最大歌魂
			//现有歌魂已经比最大歌魂大时，不加
			$log .= "你使用了<span class=\"red b\">$itm</span>，增加了<span class=\"yellow b\">$itme</span>点歌魂上限。<br>";
			\itemmain\itms_reduce($theitem);
			return;
		}elseif (strpos ( $itmk, 'HT' ) === 0) {
			$ssup=$itme;
			if ($ss < $mss) {
				$oldss = $ss;
				$ss += $ssup;
				$ss = $ss > $mss ? $mss : $ss;
				$oldss = $ss - $oldss;
				$log .= "你使用了<span class=\"red b\">$itm</span>，恢复了<span class=\"yellow b\">$oldss</span>点歌魂。<br>";
				\itemmain\itms_reduce($theitem);
			} else {
				$log .= '你的歌魂不需要恢复。<br>';
			}
			return;
		} 
		
		if (strpos ( $itmk, 'ss' ) === 0)
		{
			$eqp = 'art';
			$noeqp = '';
			
			if (($noeqp && strpos ( ${$eqp.'k'}, $noeqp ) === 0) || ! ${$eqp.'s'}) {
				${$eqp} = $itm;
				${$eqp.'k'} = $itmk;
				${$eqp.'e'} = $itme;
				${$eqp.'s'} = $itms;
				${$eqp.'sk'} = $itmsk;
				$log .= "装备了<span class=\"yellow b\">$itm</span>。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
			} else {
				swap(${$eqp},$itm);
				swap(${$eqp.'k'},$itmk);
				swap(${$eqp.'e'},$itme);
				swap(${$eqp.'s'},$itms);
				swap(${$eqp.'sk'},$itmsk);
				$log .= "卸下了<span class=\"red b\">$itm</span>，装备了<span class=\"yellow b\">${$eqp}</span>。<br>";
			}
			return;
		}
		
		$chprocess($theitem);
	}
	
	function check_sname($sname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		foreach(array('【','】','《','》') as $tv)
			$sname=str_replace($tv,'',$sname);
		//以下特判
		if(strpos($sname, '空想道具')!==false) $sname = '空想神话';
		elseif('歌词卡片星空'==$sname) $sname = '星めぐりの歌';//括号去掉了……
		elseif('歌词卡片大地'==$sname) $sname = '遥か彼方';
		elseif('歌词卡片海洋'==$sname) $sname = 'CANOE';
		elseif('爸爸野猪'==$sname) $sname = 'Baba yetu';
		elseif('快说小仓唯唱歌贼！好！听！'==$sname) $sname = 'Clear Morning';
		elseif('赠送的芙蓉王'==$sname) $sname = '『勇者』';
		return $sname;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		eval(import_module('sys'));
		if ($mode == 'command' && $command == 'song')
		{
			eval(import_module('sys','player','logger','song'));
			$song_choice = get_var_in_module('song_choice', 'input');
			if (!empty($song_choice))
			{
				$z = (int)$song_choice;
				if (isset($songlist[$z]['songname']))
				{
					ss_sing($songlist[$z]['songname']);
					$mode = 'command';
					return;
				}
				else
				{
					$log .= '参数不合法。<br>';
				}
			}
			else
			{
				$songkind = get_var_in_module('songkind', 'input');
				if (!empty($songkind))
				{
					ss_sing($songkind);
					$mode = 'command';
					return;
				}
			}
			include template(MOD_SONG_SING);
			$cmd = ob_get_contents();
			ob_clean();
			return;
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'song') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}</span>在<span class=\"yellow b\">{$b}</span>歌唱了<span class=\"red b\">{$c}</span>。</li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
