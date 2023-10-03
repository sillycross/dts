<?php

namespace item_umb
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['MB'] = '状态药物';
		$itemspkinfo['^mbid'] = '状态药物技能编号';//实际上这个是不会显示的
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
			eval(import_module('clubbase','player','logger'));
			
			$log .= "你服用了<span class=\"red b\">$itm</span>。<br>";
			
			//建议把技能编号以^mbidXXX的形式记录
			$flag = \attrbase\check_in_itmsk('^mbid', $itmsk);
			if(!empty($flag)) {
				$buff_id = (int)$flag;
			}
			elseif(is_numeric($itmsk)) {//兼容数字属性，但不建议使用
				$buff_id = (int)$itmsk;
			}
			
			if ($buff_id < 1) $buff_id = 1;
			
			if (defined('MOD_SKILL'.$buff_id) && !empty($clubskillname[$buff_id]))//这里暗示需要给状态技能定义名称
			{
				//失去该技能，用于刷新该技能状态
				if (\skillbase\skill_query($buff_id)) \skillbase\skill_lost($buff_id);
				$log .= "你获得了状态「<span class=\"yellow b\">".$clubskillname[$buff_id]."</span>」！<br>";
				\skillbase\skill_acquire($buff_id);
				//如果是需要发动一次的技能（如隐身），立刻发动
				$activate_funcname = 'skill'.$buff_id.'\\activate'.$buff_id;
				if(function_exists($activate_funcname)) {
					$tmp_log = $log;//阻止中途的函数显示
					$activate_funcname();
					$log = $tmp_log;
				}
				
			}
			else
			{
				$log .= "参数错误，这应该是一个BUG，请联系管理员。<br>";
				return;
			}
			
			\itemmain\itms_reduce($theitem);
		}
		else $chprocess($theitem);
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if('^mbid' == $cinfo[0]) return false;
		}
		return $ret;
	}
}

?>
