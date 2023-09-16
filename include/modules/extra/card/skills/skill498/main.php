<?php

namespace skill498
{
	function init() 
	{
		define('MOD_SKILL498_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[498] = '朋友';
	}
	
	function acquire498(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(498,'fv',0,$pa);
	}
	
	function lost498(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(498,'fv',$pa);
	}
	
	function check_unlocked498(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function ss_data_proc_single($sname, &$pdata, $effect, $sscost=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($sname, $pdata, $effect, $sscost);
		if('ぼくのフレンド' == $sname && \skillbase\skill_query(498, $pdata)){
			$skill498var = (int)\skillbase\skill_getvalue(498,'fv',$pdata);
			$skill498var ++;
			
			foreach(array('wp','wk','wg','wc','wd','wf') as $skv){
				$pdata[$skv] += $skill498var;
			}
			
			$ret_a = $ret; $ret_b = '';
			if(strpos($ret_a,'<!--SPERATOR-->')!==false) {
				list($ret_a,$ret_b) = explode('<!--SPERATOR-->', $ret_a);
			}
			$ret = $ret_a.'你的全系熟练度<span class="cyan b">增加了'.$skill498var.'</span><br>'.$ret_b;
			if($skill498var<11){
				\skillbase\skill_setvalue(498,'fv',$skill498var,$pdata);
			}else{//达到第11次时，失去本技能，获得无敌技能
				\skillbase\skill_lost(498,$pdata);
				\skillbase\skill_acquire(499, $pdata);
				$ret .= '“我不能游，不会飞，跑得也不快。但为了朋友，我无所不能。”<span class="yellow b">你感到心中涌出了无尽的勇气。</span><br>' ;
			}
		}
		return $ret;
	}
}

?>