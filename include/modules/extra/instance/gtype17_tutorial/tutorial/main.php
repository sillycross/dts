<?php

namespace tutorial
{
	function init() {}
	
	function init_current_tutorial(){//主要是教程的界面处理
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$ct = get_current_tutorial();
		if(!is_array($ct)) {
			$r = Array('教程参数或代码错误，请检查tutorial模块代码<br>');
		}else{
			if(!empty($ct['pulse'])) {//闪烁指令
				$effect['pulse'][] = $ct['pulse'];
			}
			$r = Array(
				$ct['tips'],
				$ct['allowed']
			);
		}		
		return $r;
	}
	
	function get_current_tutorial(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill1000','logger'));
		$s = \skillbase\skill_getvalue(1000,'step',$pa);
		//$log .= '目前的step为'.$s;
		if (!$s) {
			return false;
		}
		else {return get_tutorial_setting($s);}
	}
	
	function get_tutorial_setting($no){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','tutorial','logger'));
		if($no < 0){
			$log.='教程参数小于0，将重置至10<br>';
			$no = 10;
		}elseif(!isset($tutorialsetting[$no])){
			$log.='教程参数不存在，将重置至10<br>';
			$no = 10;
		}
		return $tutorialsetting[$no];
	}
	
	function act(){//教程模式下的continue依赖于skill1000，其他模式下如果有别的需求可以在这里扩展
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
	
		if ($command == 'continue'){
			if($gametype == 17){
				tutorial_forward_process();
				return;
			}			
		}
		return $chprocess();
	}
	
	function tutorial_forward_process(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$ct = get_current_tutorial();
		if($ct['next'] < 0){//游戏结束判定
			$log.='教程结束。这句话最终版应该删掉。<br>';
		}else{
			\skillbase\skill_setvalue(1000,'step',$ct['next'],$pa);
			//$log.='教程推进到下一阶段。这句话最终版应该删掉。<br>';
		}
		return;
	}
}

?>