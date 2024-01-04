<?php

namespace explore
{
	function init() {}
	
	function calculate_move_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map'));
		
		$movesp = 15;
		
		return $movesp;
	}
	
	function move_to_area($moveto)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		
		$log .= $areainfo[$pls].'<br>';
		
		return discover('move');
	}
	
	function move($moveto = 99) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		
		$movesp=allow_move_check($moveto);
		if(false !== $movesp){
			$sp -= $movesp;	
			$log .= "你消耗<span class=\"yellow b\">{$movesp}</span>点体力，移动到了$plsinfo[$moveto]。<br>";			
			$pls = $moveto;			
			move_to_area($moveto);
		}

		return;
	}

	function calculate_search_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map'));
		
		$schsp = 15;
		
		return $schsp;
	}
	
	//原地搜索的壳子，返回是否有结果（遭遇敌人或者摸到东西）
	function search_area()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return discover('search');
	}
	
	//判断是否满足移动条件，返回移动应消耗的体力
	function allow_move_check($moveto)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		
		$plsnum = sizeof($plsinfo);
		if($moveto == 'main' || $moveto < 0  || $moveto >= $plsnum){
			$log .= '请选择正确的移动地点。<br>';
			return false;
		} elseif($pls == $moveto){
			$log .= '相同地点，不需要移动。<br>';
			return false;
		} elseif(array_search($moveto,$arealist) <= $areanum && !$hack){
			$log .= $plsinfo[$moveto].'是禁区，还是离远点吧！<br>';
			return false;
		}
		
		$movesp=max(calculate_move_sp_cost(),1);
		
		if($sp <= $movesp){
			$log .= "体力不足，不能移动！<br>还是先睡会儿吧！<br>";
			return false;
		}
		
		return $movesp;
	}
	
	//判断是否满足搜索条件，返回搜索应消耗的体力
	function allow_search_check()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		
		if(array_search($pls,$arealist) <= $areanum && !$hack){
			$log .= $plsinfo[$pls].'是禁区，还是赶快逃跑吧！<br>';
			return false;
		}

		$schsp=max(1,calculate_search_sp_cost());
		
		if($sp <= $schsp){
			$log .= "体力不足，不能探索！<br>还是先睡会儿吧！<br>";
			return false;	
		}
		
		return $schsp;
	}
	
	//探索主函数
	function search(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		$schsp = allow_search_check();
		if(false !== $schsp) {
			$sp -= $schsp;
			$log .= "消耗<span class=\"yellow b\">{$schsp}</span>点体力，你搜索着周围的一切……<br>";
			search_area();
		}
	
		return;
	}
	
	//目前的判定顺序：
	//1.事件（event），如果遭遇就不继续
	//2.陷阱（trap），如果遭遇就不继续
	//3.探人（metman，衍生出team、enemy、corpse），如果是探物姿态，在没有探到人的情况下会继续探索道具
	//4.探物（itemmain）
	//返回是否成功发现的bool
	function discover($schmode) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//echo 'explore ';
		eval(import_module('logger'));
		$log .= "但是什么都没有发现。<br>";
		return false;
	}

	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));

		if($mode == 'command') 
		{
			if ($command == 'move') 
			{
				move(get_var_input('moveto'));
			} 
			elseif ($command == 'search') 
			{
				search();
			} 
		}
		
		$chprocess();
	}
}

?>