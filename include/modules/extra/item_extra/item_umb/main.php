<?php

namespace item_umb
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['MB'] = '状态药物';
		$itemspkinfo['^mbid'] = '状态药物技能编号';//实际上这个是不会显示的
		$itemspkinfo['^mbtime'] = '状态药物技能时效';//这个也是不会显示的
		$itemspkinfo['^mblvl'] = '状态药物技能等级';//这个还是不会显示的
	}

	function itemuse_um(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm = &$theitem['itm'];
		$itmk = &$theitem['itmk'];
		$itmsk = &$theitem['itmsk'];
		
		//状态药物MB，效果是获得时效buff类的技能
		if (0 === strpos($itmk, 'MB'))
		{
			eval(import_module('logger'));
			
			$log .= "你服用了<span class=\"red b\">$itm</span>。<br>";
			
			//建议把技能编号以^mbidXXX的形式记录，如果非时效技能，可额外设置^mbtimeXXX表示获得该技能多长时间
			$flag = \attrbase\check_in_itmsk('^mbid', $itmsk);
			if(!empty($flag)) {
				$buff_id = (int)$flag;
			}
			elseif(is_numeric($itmsk)) {//兼容数字属性，但不建议使用
				$buff_id = (int)$itmsk;
			}
			
			if ($buff_id < 1) $buff_id = 1;
			
			//注意仅适用于非时效技能，否则可能有怪问题
			$buff_time = \attrbase\check_in_itmsk('^mbtime', $itmsk);
			//技能等级
			$buff_lvl = \attrbase\check_in_itmsk('^mblvl', $itmsk);
			buff_acquire_process($buff_id, $buff_time, $buff_lvl);
			
			\itemmain\itms_reduce($theitem);
		}
		else $chprocess($theitem);
	}
	
	//添加单个时效buff或临时技能的处理
	function buff_acquire_process($buff_id, $buff_time = NULL, $buff_lvl = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','clubbase','player','logger'));
		if (defined('MOD_SKILL'.$buff_id) && !empty($clubskillname[$buff_id]))//这里暗示需要给状态技能定义名称
		{
			if (\skillbase\skill_query($buff_id))
			{
				if (empty(\skillbase\skill_getvalue($buff_id, 'tsk_expire')))
				{
					//临时技能不会盖掉非临时的技能
					$log .= "但是这里面的技巧你早就刻在DNA里了！<br>";
					return;
				}
				//否则失去该技能，用于刷新该技能状态
				\skillbase\skill_lost($buff_id);
			}
			if (!empty($buff_time))
			{
				$log .= "你获得了临时技能「<span class=\"yellow b\">".$clubskillname[$buff_id]."</span>」，持续时间<span class=\"yellow b\">".$buff_time."</span>秒！<br>";
			}
			else $log .= "你获得了状态「<span class=\"yellow b\">".$clubskillname[$buff_id]."</span>」！<br>";
			\skillbase\skill_acquire($buff_id);
			//如果是需要发动一次的技能（如隐身），立刻发动
			$activate_funcname = 'skill'.$buff_id.'\\activate'.$buff_id;
			if(function_exists($activate_funcname)) {
				$tmp_log = $log;//阻止中途的函数显示
				$activate_funcname();
				$log = $tmp_log;
			}
			//如果$buff_time非空，则设置持续时间；仅适用于非时效技能
			if (!empty($buff_time)) \skillbase\skill_setvalue($buff_id, 'tsk_expire', $now + $buff_time);
			//如果$buff_lvl非空，则设置技能等级
			if (!empty($buff_lvl)) \skillbase\skill_setvalue($buff_id, 'lvl', $buff_lvl);
		}
		else
		{
			$log .= "参数错误，这应该是一个BUG，请联系管理员。<br>";
			return;
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys','player'));
		$arr = \skillbase\get_acquired_skill_array($sdata);
		foreach ($arr as $key)
		{
			if (defined('MOD_SKILL'.$key.'_INFO') && (strpos(constant('MOD_SKILL'.$key.'_INFO'),'club;')!==false || strpos(constant('MOD_SKILL'.$key.'_INFO'),'card;')!==false))
			{
				$tsk_expire = \skillbase\skill_getvalue($key, 'tsk_expire', $pa);
				if(!empty($tsk_expire))	init_buff_timing($key, $tsk_expire - $now);
			}
		}
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		
		$arr = \skillbase\get_acquired_skill_array($sdata);
		$tsk_idlist = array();
		$tsk_rmlist = array();
		$buff_tmax = 0;
		//所有临时技能共用一个icon，要不然感觉有显示不下的风险
		foreach ($arr as $key)
		{
			if (defined('MOD_SKILL'.$key.'_INFO') && (strpos(constant('MOD_SKILL'.$key.'_INFO'),'club;')!==false || strpos(constant('MOD_SKILL'.$key.'_INFO'),'card;')!==false))
			{
				$tsk_expire = \skillbase\skill_getvalue($key, 'tsk_expire', $pa);
				if (!empty($tsk_expire))
				{
					$tsk_idlist[] = $key;
					$tsk_rmlist[] = $tsk_expire - $now;
					if ($tsk_expire > $buff_tmax) $buff_tmax = $tsk_expire;
				}
			}
		}
		$buff_rmmax = $buff_tmax - $now;
		if (!empty($tsk_idlist))
		{
			$z = array(
				'disappear' => 1,
			);
			if ($now < $buff_tmax)
			{
				$z['clickable'] = 1;
				$z['style'] = 1;
				//可能同时有多个时长不同的buff，icon仅提示有buff存在
				$z['totsec'] = $buff_rmmax;
				$z['nowsec'] = 0;
				$z['hint'] = show_buff_hint($tsk_idlist);
			}
			else
			{
				$z['clickable'] = 0;
				$z['style'] = 3;
				$z['activate_hint'] = "临时技能生效时间已经结束";
			}
			\bufficons\bufficon_show('img/icon_mb.gif',$z);
		}
		$chprocess();
	}
	
	function show_buff_hint($idlist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$s = '';
		foreach ($idlist as $id)
		{
			eval(import_module('sys','clubbase'));
			//显示每个技能的剩余时间。因为只有act()后会执行init_buff_timing()，载入game.php则没有定义，所以这里需要做个判定。
			$display_t = !empty($uip['timing']['skill'.$id]['timing_r']) ? $uip['timing']['skill'.$id]['timing_r'] : '---';
			$s .= "「{$clubskillname[$id]}」 剩<span class=\"yellow b\" id=\"skill{$id}\">{$display_t}</span>秒<br>";
		}
		return $s;
	}
	
	function init_buff_timing($buffid, $buffrm){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if(!isset($uip['timing'])) $uip['timing'] = array();
		$timing_r = sprintf("%02d", floor($buffrm/60)).':'.sprintf("%02d", $buffrm%60);
		$uip['timing']['skill'.$buffid] = array(
			'on' => true,
			'mode' => 0,
			'timing' => $buffrm * 1000,
			'timing_r' => $timing_r,
			'format' => 'mm:ss'
		);
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if('^mbid' == $cinfo[0]) return false;
			if('^mbtime' == $cinfo[0]) return false;
			if('^mblvl' == $cinfo[0]) return false;
		}
		return $ret;
	}

}

?>
