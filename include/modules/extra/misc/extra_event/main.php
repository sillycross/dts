<?php

namespace extra_event
{
	$extra_event_para = Array();//事件参数池，在载入事件时生成

	$event_start_flag = 0;
	$event_end_flag = 0;

	function init() {}
	
	//本模块用于生成带选项的事件

	//获取事件对应选择文本的函数
	function extra_event_get_branch_text_core($now_event, $now_pace)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = Array();
		eval(import_module('extra_event'));
		if(!empty($extra_event_texts[$now_event])) {
			$ret = $extra_event_texts[$now_event];
			if(!empty($ret['texts'][$now_pace])) {
				$ret['now_pace'] = $ret['texts'][$now_pace];
			}
		}
		return $ret;
	}

	//判定当前游戏模式是否允许扩展事件
	function is_extra_event_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return in_array(get_var_in_module('gametype', 'sys'), get_var_in_module('extra_event_gametype', 'extra_event'));
	}

	//扩展事件主函数，用于判定具体执行哪个扩展事件。
	//如果玩家skill1003技能记录了执行到一半的事件，优先执行这个。否则根据这里的规则确定执行哪一个事件
	function extra_event_main()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;

		eval(import_module('extra_event'));
		list($now_event, $now_pace) = extra_event_get_eid_pace();
		if(!$now_event) {//玩家不在任何一个事件中，根据$extra_event_list决定执行哪个事件
			$pls = get_var_in_module('pls', 'player');
			if(!empty($extra_event_list[$pls])) {
				if(is_array($extra_event_list[$pls])) $now_event = array_randompick($extra_event_list[$pls]);
				else $now_event = (int)$extra_event_list[$pls];
				$event_start_flag = 1;
			}else{
				$now_event = 0;
			}
		}
		if(!$now_event) return 0;

		if(!$now_pace) $now_pace = 0;
		
		$selection = get_var_input('event_selection');
		//调用核心函数
		$ret = extra_event_core($now_event, $now_pace, $selection);
		//执行完毕后，根据事件是否继续，执行储存或者清空
		if(!empty($now_event)) {
			extra_event_save_eid_pace($now_event, $now_pace);
			if(!empty($extra_event_para)) {
				extra_event_save_event_para($extra_event_para);
			}
		}else{
			extra_event_save_eid_pace(0, 0);
			extra_event_save_event_para(Array());
		}
		return $ret;
	}

	//扩展事件核心函数，用于显示和判定效果
	//文本在config，具体判定在函数过程里
	function extra_event_core(&$now_event, &$now_pace, $selection)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//如果没有给出选项，则清空事件。当然部分事件可能不允许清空。
		eval(import_module('player','extra_event', 'logger'));
		if(empty($event_start_flag) && empty($selection) && is_event_exitable($now_event)) {
			$now_event = $now_pace = 0;
			return 0;
		}
		//载入事件参数
		extra_event_para_load($now_event, $now_pace);
		//载入文本
		$now_texts = extra_event_get_branch_text_core($now_event, $now_pace);

		//如果遭遇事件次数已达上限则只显示expired文本
		$occurred_times = extra_event_get_occurred_times($now_event, $now_pace);
		if(!empty($occurred_times[$now_event]) && !empty($extra_event_occurred_times_limit[$now_event]) && $occurred_times[$now_event] >= $extra_event_occurred_times_limit[$now_event]) 
		{
			$log .= '<br>'.$now_texts['expired'].'<br>';
			$now_event = $now_pace = 0;
			return 1;
		}

		//真正进入判定
		//如果有提交选项，则先判定选项合法性，并显示本层result
		if(!empty($selection)) {
			$correct_flag = 1;
			if(!is_numeric($selection) || $selection < 0) $correct_flag = 0;
			if(empty($now_texts['now_pace']['branches'][$selection])) $correct_flag = 0;
			if(!$correct_flag) {
				$log .= '错误的事件选项！<br>';
				$selection = 0;
			}else{
				//判定选项效果并显示文本
				extra_event_selection_process($now_event, $now_pace, $selection);

				//判定是否进入下一层。是，$now_pace加1；不是，把$event_end_flag设为1
				if(!empty($now_texts['texts'][$now_pace + 1])) {
					$now_pace ++;
					//重新生成事件参数
					extra_event_para_prepare($now_event, $now_pace);
					//重新载入文本
					$now_texts = extra_event_get_branch_text_core($now_event, $now_pace);
				}else{
					$event_end_flag = 1;
				}
			}
		}

		//事件没有终止的话，显示本层文本和选项
		if(empty($event_end_flag)) {
			if(!$now_pace) {
				$log .= '<br>'.$extra_event_texts[$now_event]['overview'].'<br>';
			}

			$log .= $now_texts['now_pace']['request'].'<br>';

			ob_start();
			include template(MOD_EXTRA_EVENT_COMMON_EVENT_PAGE);
			$cmd = ob_get_contents();
			ob_end_clean();
		}else{//事件已终止，记录次数，并把$now_event设为0
			$occurred_times = extra_event_get_occurred_times($now_event, $now_pace);
			if(empty($occurred_times[$now_event])){
				$occurred_times[$now_event] = 1;
			}else{
				$occurred_times[$now_event] ++ ;
			}
			extra_event_save_occurred_times($now_event, $now_pace, $occurred_times);
			$now_event = 0;
		}
		return 1;
	}

	//事件选项效果处理，并显示结果文本
	//因为类型和变化较多，处理写在这里而不是config
	function extra_event_selection_process(&$now_event, &$now_pace, $selection)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','extra_event','logger'));
		switch ($now_event) {
			case 1://愿望事件
				if(0 == $now_pace) {//很多事件只有一两层，用if比较不容易混淆
					
					switch ($selection) {
						case 1://失去最大生命值获得攻击力和防御力
							\event\event_get_field($extra_event_para['para1'], 'att');
							\event\event_get_field($extra_event_para['para1'], 'def');
							\event\event_get_field(-$extra_event_para['para2'], 'mhp');
							\event\event_get_field(-$extra_event_para['para2'], 'hp');
							break;
						
						case 2://失去经验值获得技能
							event_get_skill($extra_event_para['para3']);
							\event\event_get_field(-$extra_event_para['para4'], 'exp');
							break;

						case 3://失去最大体力值获得金钱
							\event\event_get_money($extra_event_para['para5']);
							\event\event_get_field(-$extra_event_para['para6'], 'msp');
							\event\event_get_field(-$extra_event_para['para6'], 'sp');
							break;
					}
				}
				break;
			
			default:
				$log .= '错误的事件参数：A！<br>';
				break;
		}
		if(empty($result)) {
			$now_texts = extra_event_get_branch_text_core($now_event, $now_pace);
			$result = $now_texts['now_pace']['results'][$selection];
		}
		$log .= $result.'<br>';
	}

	//载入事件选项参数。
	//如果已经储存过玩家选项，则直接调用，否则重新生成选项
	function extra_event_para_load($now_event, $now_pace)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('extra_event'));
		$extra_event_para = extra_event_get_event_para($now_event, $now_pace);
		if(empty($extra_event_para)) {
			extra_event_para_prepare($now_event, $now_pace);
		}
	}

	//事件选项参数的准备
	//需要根据一定规则生成并记录参数（比如随机值）则需要修改此函数
	//没有返回值，会直接修改本模块的$extra_event_para变量
	function extra_event_para_prepare($now_event, $now_pace)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','extra_event','logger','clubbase'));
		switch ($now_event) {
			case 1://愿望事件
				if(0 == $now_pace) {
					$extra_event_para['para1'] = rand(300, 500);//攻防增加值

					$extra_event_para['para2'] = rand(50, 100);//生命上限减少值
					if($extra_event_para['para2'] >= $mhp) {
						$o_val = $extra_event_para['para2'];
						$extra_event_para['para2'] = $mhp - 1;
						$extra_event_para['para1'] = ceil($extra_event_para['para1'] * $extra_event_para['para2'] / $o_val);
					}

					//随机获得技能
					do{
						if(defined('MOD_ITEM_RANDSKILLS')) {
							$get_skill_id = \item_randskills\get_rand_clubskill($sdata, 1)[0];
						}else{
							$get_skill_id = array_randompick(Array(13, 14, 15, 16, 17, 18));
						}
						
					}while(\skillbase\skill_query($get_skill_id, $sdata) && empty($clubskillname[$get_skill_id]));
					$extra_event_para['para3'] = $get_skill_id;
					
					$extra_event_para['para3_skillname'] = $clubskillname[$get_skill_id];

					$extra_event_para['para4'] = rand(60, 90);//经验值减少值

					$extra_event_para['para5'] = rand(1000, 3000);//金钱增加值

					$extra_event_para['para6'] = rand(200, 300);//体力上限减少值
					if($extra_event_para['para6'] > $msp - 16) {
						$o_val = $extra_event_para['para6'];
						$extra_event_para['para6'] = $msp - 16;
						$extra_event_para['para5'] = ceil($extra_event_para['para5'] * $extra_event_para['para6'] / $o_val);
					}

					break;
				}
			
			default:
				$log .= '错误的事件参数：B！<br>';
				break;
		}
	}

	//事件选项文本的处理。本模块单纯是把文本中的变量名替换为$extra_event_para中的对应变量值
	function extra_event_text_parse($now_event, $now_pace, $text)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $text;
		if(false !== strpos($text, '<:') && false !== strpos($text, ':>')) {
			$ret = preg_replace_callback('/\<\:(.+?)\:\>/is', '\extra_event\extra_event_text_parse_callback', $ret);
		}
		return $ret;
	}

	function extra_event_text_parse_callback($matches)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$extra_event_para = get_var_in_module('extra_event_para', 'extra_event');
		if(isset($extra_event_para[$matches[1]])) {
			return $extra_event_para[$matches[1]];
		}
		return 'MISSING_PARAMETER:'.$matches[1];
	}

	//获取当前玩家处于的事件的eid和步数pace，需要skill1003支持
	//返回数组，请用list承接
	function extra_event_get_eid_pace()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$now_event = (int)\skillbase\skill_getvalue(1003, 'now_event');
		$now_pace = (int)\skillbase\skill_getvalue(1003, 'now_pace');
		return Array($now_event, $now_pace);
	}

	//储存当前玩家处于的事件的eid和步数pace，需要skill1003支持
	function extra_event_save_eid_pace($now_event, $now_pace)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1003, 'now_event', $now_event);
		\skillbase\skill_setvalue(1003, 'now_pace', $now_pace);
	}

	//获取当前玩家已经遭遇事件的次数数组（全部事件），需要skill1003支持
	function extra_event_get_occurred_times($now_event, $now_pace)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$arr = \skillbase\skill_getvalue(1003, 'event_occurred_times');
		if(!empty($arr)){
			$arr = gdecode($arr, 1);
			if(!is_array($arr)) {
				$arr = Array();
			}
		}else{
			$arr = Array();
		}
		return $arr;
	}

	//储存当前玩家已经遭遇事件的次数数组
	function extra_event_save_occurred_times($now_event, $now_pace, $arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$arr = gencode($arr);
		\skillbase\skill_setvalue(1003, 'event_occurred_times', $arr);
	}

	//获取当前玩家的事件随机参数值，需要skill1003支持
	//自带判定当前事件和步数是否正确。如果不正确会返回一个空值
	function extra_event_get_event_para($now_event, $now_pace)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($saved_id, $saved_pace) = extra_event_get_eid_pace();
		$para = Array();
		if($saved_id == $now_event && $saved_pace == $now_pace) {
			$para = \skillbase\skill_getvalue(1003, 'now_event_para');
			if(!empty($para)){
				$para = gdecode($para, 1);
				if(!is_array($para)) {
					$para = Array();
				}
			}else{
				$para = Array();
			}
		}
		
		return $para;
	}

	//储存当前玩家的事件随机参数值
	function extra_event_save_event_para($para)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$para = gencode($para);
		\skillbase\skill_setvalue(1003, 'now_event_para', $para);
	}

	//判定事件是否可以中途退出
	function is_event_exitable($eid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('extra_event'));
		return in_array($eid, $extra_event_allow_exit);
	}

	//特殊事件入口1，从事件模块进入
	function event_main()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','extra_event'));
		if(!empty($extra_event_list[$pls]) && is_extra_event_available()) {
			$ret = extra_event_main();
		}
		if(empty($ret)) {
			$ret = $chprocess();
		}
		return $ret;
	}

	//特殊事件入口2，从act()进入
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if('event' == $command && is_extra_event_available()) {
			extra_event_main();
			return;
		}
		$chprocess();
	}

	//玩家记录有当前事件但是并没有发出事件选项指令的情况下，相应处理
	function pre_act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(\skillbase\skill_query(1003)){
			$now_event = extra_event_get_eid_pace()[0];
			if(!empty($now_event) && 'event' != $command) {
				if(is_event_exitable($now_event)){//事件可以退出，那么清空事件，继续判定
					extra_event_save_eid_pace(0,0);
				}else{//否则把指令改为进入事件
					$mode = 'command';
					$command = 'event';
				}
			}
		}
		$chprocess();
	}

	//因事件获得技能
	function event_get_skill($skillid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!empty($skillid)) {
			eval(import_module('logger', 'clubbase'));
			\skillbase\skill_acquire($skillid);
			$log .= '<span class="cyan b">获得了技能『'.$clubskillname[$skillid].'』。</span><br>';
		}
	}
}
?>