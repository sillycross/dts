<?php

namespace song
{
	$ef_type = array(
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
	function ss_data_proc_single(&$pdata, $effect)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ss_log = $ss_log_2 = array();
		if(empty($effect)) return $ss_log;
		eval(import_module('song'));
		$pdata['mrage'] = \rage\get_max_rage($pdata);
		foreach($effect as $ek => $ev){
			if(isset($pdata[$ek])){
				//如果变化量是数值，那么变化量乘以一个系数
				//生成提示变化了哪里，目前仅支持HP SP 歌魂 怒气 金钱 攻防 装备道具的效果和耐久值
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
					if(is_numeric($change)) $change *= ss_factor($pdata);
					$pdata[$ek] = $change;
					$ss_log[] = $ss_tn.'<span class="yellow">变成了'.$change.'</span>';
				}elseif(is_numeric($ev)) {
					$change = $ev;
					if(is_numeric($change)) $change *= ss_factor($pdata);
					if($change > 0) {//增加值，要判定是否最大值
						if(!isset($pdata['m'.$ek]) || $pdata[$ek] < $pdata['m'.$ek]){
							if(isset($pdata['m'.$ek]) && $pdata[$ek] + $change > $pdata['m'.$ek]) {
								$change = $pdata['m'.$ek] - $pdata[$ek];
							}
							$pdata[$ek] += $change;
						}else{//超过最大值的不改动
							$change = 0;
						}
						if($change) $ss_log[] = $ss_tn.'<span class="clan">增加了'.$change.'</span>';
					}else{//减少值直接减
						if($pdata[$ek] + $change < 1 && in_array($ek, array('hp','mhp','sp','msp'))) {
							$change = 1 - $pdata[$ek];//生命体力不会降低到小于1，也就是不会唱死人
						}elseif($pdata[$ek] + $change < 0){
							$change = -$pdata[$ek];
						}
						$pdata[$ek] += $change;
						if($change != 0) $ss_log[] = $ss_tn.'<span class="red">减少了'.(0-$change).'</span>';
					}
				}
				//装备耐久变成0的情况
				if(!$pdata[$ek] && (((strpos($ek,'wep')===0 || strpos($ek,'ar')===0) && 's' == substr($ek,3,1)) || (strpos($ek,'itm')===0 && 's' == substr($ek,4,1)))){
					$itmpos = (strpos($ek,'wep')===0 || strpos($ek,'ar')===0) ? substr($ek,0,3) : substr($ek,0,3).substr($ek,3,1);
					$itmname = $pdata[$itmpos];
					$ss_log_2[] = $itmname;
				}
			}
		}
		unset($pdata['mrage']);
		$ss_log_f = '歌声让你的'.implode('，',$ss_log).'。<br>';
		if(!empty($ss_log_2)) $ss_log_f .= '<span class="red">你的'.implode('、',$ss_log_2).'损坏了！</span><br>';
		return $ss_log_f;
	}
	
	//歌效果处理
	function ss_data_proc($effect)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if(empty($effect)) return;
		//先处理自己
		eval(import_module('sys','player','logger'));
		$log .= ss_data_proc_single($sdata, $effect);
		//获取所有影响到的玩家		
		$pdlist = ss_get_affected_players($pls);
		if(empty($pdlist)) return;
		//依次处理玩家
		foreach($pdlist as $pdata){
			$ss_log = ss_data_proc_single($pdata, $effect);
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
	
	function ss_sing($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','noise','song'));
		$songcfg = NULL;
		foreach($songlist as $sval){
			if($sval['songname'] == $sn) {
				$songcfg = $sval;
				break;
			}
		}
		if(!$songcfg) {
			$log .= '好像不存在这样一首歌呢……<br>';
			return;
		}
		$r = ss_cost_proc($songcfg['cost']);
		$nkey = $songcfg['noisekey'];

		if ($ss>=$r){
			$ss-=$r;
			$log.="消耗<span class=\"yellow\">{$r}</span>点歌魂，歌唱了<span class=\"yellow\">{$noiseinfo[$nkey]}</span>。<br>";
		}else{
			$log.="需要<span class=\"yellow\">{$r}</span>歌魂才能唱这首歌！<br>";
			return;
		}
		addnews($now,'song',$name,$plsinfo[$pls],$songcfg['songname']);
		
		//歌词显示部分
		$lyricnum = sizeof($songcfg['lyrics']);
		$songchat = '';
		$songloop = defined('MOD_SKILL1003');//如果技能1003存在，会按顺序唱歌词，否则唱前两句
		if($songloop) {
			$songkind = \skillbase\skill_getvalue(1003,'songkind');
			$songpos = $songkind == $sn ? (int)\skillbase\skill_getvalue(1003,'songpos') : 0;//如果上一次唱的不是这首歌则从头
		}
		if(isset($songcfg['lyrics_ruby'])) {
			$song_font_size = 16;
			$song_line_height = 28;
		}
		for($i=0;$i<$lyricnum;$i++){
			$ss_log = '<span style="font-size:<:fs:>px;line-height:<:lh:>px">'.$songcfg['lyrics'][$i].'</span>';
			if(isset($songcfg['lyrics_ruby'])) {
				$ss_log = '<ruby>'.$ss_log.'<rt>'.$songcfg['lyrics_ruby'][$i].'</rt></ruby>';
				$replace = '28px';
			}
			$ss_log = str_replace('<:lh:>', $song_line_height, str_replace('<:fs:>', $song_font_size, $ss_log));
			$log .= $ss_log.'<br>';
			if($songloop) {
				if ($i==$songpos) $songchat .= $songcfg['lyrics'][$i];
			}else{
				if($i < $songchatlimit) $songchat .= $songcfg['lyrics'][$i].'　';
				if($i == $songchatlimit-1) $songchat .= '……';
			}
		}
		$log .= '<br>';
		\sys\addchat(0, $songchat, $name);
		if($songloop) {
			if($songpos+1 >= sizeof($songcfg['lyrics']) ) $songpos = 0;
			else $songpos ++;
			\skillbase\skill_setvalue(1003,'songpos',$songpos);
			\skillbase\skill_setvalue(1003,'songkind',$sn);
		}
		if (defined('MOD_NOISE') && !empty($nkey)) \noise\addnoise($pls,$nkey,$pid);
		
		//歌效果处理核心函数
		ss_data_proc($songcfg['effect']);
		
		return;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		eval(import_module('sys','player','logger'));
		if (strpos ( $itmk, 'HM' ) === 0) {
			$mss+=$itme;
			if($ss+$itme <= $mss) $ss+=$itme;//现有歌魂加完以后不会超限时，也增加物品的效果值
			elseif($ss <= $mss) $ss = $mss;//现有歌魂加完以后会超限，加到最大歌魂
			//现有歌魂已经比最大歌魂大时，不加
			$log .= "你使用了<span class=\"red\">$itm</span>，增加了<span class=\"yellow\">$itme</span>点歌魂。<br>";
			\itemmain\itms_reduce($theitem);
			return;
		}elseif (strpos ( $itmk, 'HT' ) === 0) {
			$ssup=$itme;
			if ($ss < $mss) {
				$oldss = $ss;
				$ss += $ssup;
				$ss = $ss > $mss ? $mss : $ss;
				$oldss = $ss - $oldss;
				$log .= "你使用了<span class=\"red\">$itm</span>，恢复了<span class=\"yellow\">$oldss</span>点歌魂。<br>";
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
				$log .= "装备了<span class=\"yellow\">$itm</span>。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
			} else {
				swap(${$eqp},$itm);
				swap(${$eqp.'k'},$itmk);
				swap(${$eqp.'e'},$itme);
				swap(${$eqp.'s'},$itms);
				swap(${$eqp.'sk'},$itmsk);
				$log .= "卸下了<span class=\"red\">$itm</span>，装备了<span class=\"yellow\">${$eqp}</span>。<br>";
			}
			return;
		}
		
		$chprocess($theitem);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if($mode == 'command' && $command == 'song') {
			$sname=trim(trim($art,'【'),'】');
			ss_sing($sname);
			return;
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'song') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}</span>在<span class=\"yellow\">{$b}</span>歌唱了<span class=\"red\">{$c}</span>。</li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>