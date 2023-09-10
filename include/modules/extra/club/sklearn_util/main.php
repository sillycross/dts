<?php

namespace sklearn_util
{
	function init() {}
	
	function sklearn_basecheck($skillid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (	defined('MOD_SKILL'.$skillid.'_INFO') && 
			strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'club;')!==false && 
			strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'locked;')===false && 
			strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;')===false &&
			strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'limited;')===false &&
			strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')===false)
				return 1;
		return 0;
	}
	
	function get_skilllearn_table($callback_funcname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','clubbase'));
		
		$is_show_cost=$callback_funcname('show_cost');
		$caller_id=$callback_funcname('caller_id');
		
		$___TEMP_tlis=Array();
		$lis = Array();
		foreach ($clublist as $nowclub => $arr)
			if ($nowclub!=$club)
			{
				foreach ($arr['skills'] as $skillid){
					if (sklearn_basecheck($skillid)){
						if ($callback_funcname('is_learnable',$skillid)){
							$cost = $callback_funcname('query_cost',$skillid);
							$now_learnable = $callback_funcname('now_learnable',$skillid);
							if(!isset($lis[$nowclub])) $lis[$nowclub] = array();
							array_push($lis[$nowclub],array($skillid, $cost, $now_learnable));
							if(!\skillbase\skill_query($skillid)) array_push($___TEMP_tlis,$skillid);
						}
					}
				}
			}
		include template(MOD_SKLEARN_UTIL_SKILLLEARN_TABLE);
		$___TEMP_str='';
		global $___tmp_disable_codeadv3;
		$___tmp_disable_codeadv3_old = $___tmp_disable_codeadv3;
		$___tmp_disable_codeadv3 = 1;
		foreach ($___TEMP_tlis as $___TEMP_now_skillid)
		{
			global $___TEMP_IN_SKLEARN_FLAG; $___TEMP_IN_SKLEARN_FLAG=1;
			ob_start();
			include template(constant('MOD_SKILL'.$___TEMP_now_skillid.'_DESC'));
			$str=ob_get_contents();
			ob_end_clean();
			$showcont = parse_skilllearn_desc($str);
			unset($i); unset($j); unset($str);
			$___TEMP_IN_SKLEARN_FLAG=0;
			
			include template(MOD_SKLEARN_UTIL_SKILLLEARN_DESC);
			
			//$___TEMP_str.='$(\'skl_util_'.$caller_id.'_skilllearn_tabrow_'.$___TEMP_now_skillid.'\').deleteCell(1);';
		}
		$___tmp_disable_codeadv3 = $___tmp_disable_codeadv3_old;
		//echo '<img style="display:none;" type="hidden" src="img/blank.png" onload="'.$___TEMP_str.'">';
	}
	
	//把desc.htm生成的整个内容裁剪到只剩中间介绍部分
	function parse_skilllearn_desc($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//取得desc.htm中间的介绍部分
		$i=strpos($str,'_____TEMP_SKLEARN_START_FLAG_____')+strlen('_____TEMP_SKLEARN_START_FLAG_____');
		$j=strpos($str,'_____TEMP_SKLEARN_END_FLAG_____');
		$str = trim(substr($str,$i,$j-$i));
		//提取第一个td中间的部分，这里需要用到正则表达式
		preg_match('|<td[^>]*?skilldesc_left.*?>(.*?)<\\/td>\s*<td[^>]*?skilldesc_right|s', $str, $matches);
		if($matches) $str = $matches[1];
		else $str = '';
		return $str;
	}
	
	//学习类技能遗忘原技能的共用函数，如果原技能同时也是当前称号的技能，那么不遗忘
	//虽然跟显示学习界面没有关系，但是学习类技能都会继承这个模块的吧，就也放在这里吧
	//暂时应该只有窃取用到了这个函数（学习、灵感和家教都不会学到自己称号的技能）
	function sklearn_skill_lost($skillid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','clubbase'));
		if(!empty($club) && in_array($skillid, $clublist[$club]['skills'])) return;
		\skillbase\skill_lost($skillid);
	}
}

?>