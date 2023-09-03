<?php

namespace skill528
{
	$skill528_expup = 7;
	$skill528_skillup = 7;
	$skill528_gmlist = Array('admin','Yoshiko_G');

	function init() 
	{
		define('MOD_SKILL528_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[528] = '骇客';
	}
	
	function acquire528(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(528,'flag','0',$pa);
		\skillbase\skill_setvalue(528,'rmt','77',$pa);
		\skillbase\skill_setvalue(528,'cmd','',$pa);
	}
	
	function lost528(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked528(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//在init_playerdata的最后一步判定标记是否没有消除，如果是，触发skill528_effect
	//之后标记一下
	function init_playerdata()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		//2023年8月18日的晚上，玩家低维生物第一次证明了幻境是可以实现维度跳跃的。后世的史学家把那一日称之为“维度元年”。
		//多年以后，时空护卫推动建立的人类联邦政府的最强权力机关——时空管理局颁布了一条永久禁令，那就是禁止进行维度跳跃
		//所以维度越高，BUG越多；BUG越多，修正越多；修正越多，维度越低。所以维度越高，维度越低
		if (in_array($GLOBALS['___CURSCRIPT'], array('GAME', 'ACT')) && \skillbase\skill_query(528)) {
		//if (\skillbase\skill_query(528)) {
			eval(import_module('player','input'));
			if(1 == \skillbase\skill_getvalue(528,'flag',$sdata)){
				//如果标记没有消除，认为中间程序异常了，触发技能效果
				skill528_effect();
				\skillbase\skill_setvalue(528,'flag','0',$sdata);
			}else{//否则标记一下
				if(!empty($command)) {
					\skillbase\skill_setvalue(528,'cmd',$command,$sdata);
					\skillbase\skill_setvalue(528,'flag','1',$sdata);
				}
			}		
			//立刻储存，要说有问题也就是这里了
			\player\player_save($sdata, 1);
		}
		return;
	}
	
	//在command_act.php执行的最后，player_save()之前，把标记抹除
	function before_last_player_save_event()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		if(\skillbase\skill_query(528)){
			eval(import_module('player'));
			\skillbase\skill_setvalue(528,'flag','0',$sdata);
		}
		return;
	}
	
	function skill528_effect()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (\skillbase\skill_query(528)) {
			eval(import_module('sys', 'player', 'logger', 'skill528', 'weapon'));
			$rmt = \skillbase\skill_getvalue(528,'rmt',$sdata);
			if($rmt > 0) {
				$log .= '你成功定位到了一处损坏代码，并利用它让你的<span class="lime b">经验值上升了'.$skill528_expup.'点、全系熟练度上升了'.$skill528_skillup.'点！</span><br>你还给这个游戏的天然呆程序员发去了一封站内信以示嘲讽。<br><br>';
				$sdata['exp'] += $skill528_expup;
				foreach(Array_keys($skilltypeinfo) as $v){
					if(isset($sdata[$v])) $sdata[$v] += $skill528_skillup;
				}
				$rmt --; if($rmt < 0) $rmt = 0;
				\skillbase\skill_setvalue(528,'rmt',$rmt,$sdata);
			}else{
				$log .= '你成功定位到了一处损坏代码，不过你已经无法获得经验值和全系熟练度了，当然你还是可以用垃圾邮件轰炸管理员。<br><br>';
			}
			include_once './include/messages.func.php';
			foreach($skill528_gmlist as $v){
				$r = fetch_udata('uid', "username='$v'");
				if(empty($r)) continue;
				message_create(
					$v,
					'这个游戏有big！',
					"{$name}汇报一处big：在".($groomtype ? '房间' : '') . "第{$gamenum}局中，{$name}在执行".(\skillbase\skill_getvalue(528,'cmd',$sdata))."时触发了一个big。"
				);
			}	
		}
		return;
	}
}

?>