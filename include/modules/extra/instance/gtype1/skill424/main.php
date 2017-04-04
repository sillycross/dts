<?php

namespace skill424
{
	function init() 
	{
		define('MOD_SKILL424_INFO','club;active;locked;');
		eval(import_module('clubbase'));
		$clubskillname[424] = '除错';
	}
	
	function acquire424(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(424,'lvl',0,$pa);
		\skillbase\skill_setvalue(424,'cur1',0,$pa);
		\skillbase\skill_setvalue(424,'cur2',1,$pa);
	}
	
	function lost424(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(424,'lvl',$pa);
		\skillbase\skill_delvalue(424,'cur1',$pa);
		\skillbase\skill_delvalue(424,'cur2',$pa);
	}
	
	function check_unlocked424(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
		
	function wdebug(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger','skill424','skillbase','map'));
		if(\skillbase\skill_query(424)){
			$req1=$goal424[\skillbase\skill_getvalue(424,'cur1')];
			$req2=$goal424[\skillbase\skill_getvalue(424,'cur2')];
			$clv=\skillbase\skill_getvalue(424,'lvl');
			$position = 0;
			foreach(Array(1,2,3,4,5,6) as $imn){
				global ${'itm'.$imn},${'itmk'.$imn},${'itme'.$imn},${'itms'.$imn},${'itmsk'.$imn};
				if((${'itm'.$imn}==$req1)||(${'itm'.$imn}==$req2)){
					$position = $imn;
					break;
				}
			}
			if($position){
				$itm = ${'itm'.$position};
				$log .= "<span class=\"yellow\">除错成功。</span><br />";
				$log .= "<span class=\"red\">$itm</span>用光了。<br />";
				addnews ( 0, 'skill424', $name, $clv+1);
				${'itm'.$position} = ${'itmk'.$position} = ${'itmsk'.$position} = '';
				${'itme'.$position} =${'itms'.$position} =0;
				$gdice=rand(1,3);
				$money+=200;
				$skillpoint++;
				$log .="<span class=\"yellow\">获得了200元和1个技能点。</span><br />";
				if ($gdice==1){
					$wp+=10;$wk+=10;$wc+=10;$wd+=10;$wg+=10;$wf+=10;
					$log .="<span class=\"yellow\">获得了10点全熟练。</span><br />";
				}
				if ($gdice==2){
					$att+=10;$def+=10;
					$log .="<span class=\"yellow\">获得了10点基础攻防。</span><br />";
				}	
				if ($gdice==3){
					$mhp+=10;$msp+=10;$hp+=10;$sp+=10;
					$log .="<span class=\"yellow\">获得了10点命体上限。</span><br />";
				}
				$clv++;
				$t=635;
				$nx=rand(0,$t);
				$ed=$goal424[$nx];
				$log .="下次除错需要物品<span class=\"yellow\">{$ed}</span>或";
				\skillbase\skill_setvalue(424,'cur1',$nx);	
				$nx1=rand(0,$t);
				while ($nx1==$nx) $nx1=rand(0,$t);
				$ed=$goal424[$nx1];
				$log .="<span class=\"yellow\">{$ed}</span>。<br />";
				\skillbase\skill_setvalue(424,'cur2',$nx1);				
				\skillbase\skill_setvalue(424,'lvl',$clv);
				$mode = 'command';
				return;
			}else{
				$log .= "本次除错需要物品<span class=\"yellow\">$req1</span>或<span class=\"yellow\">$req2</span>。你没有进行除错所需的物品。<br />";
				$mode = 'command';
				return;
			}
		}else{
			$log .= '<span class="red">你没有这个技能！</span><br />';
			$mode = 'command';
			return;
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','input'));
	
		if ($mode == 'special' && $command == 'skill424_special' && $subcmd=='wdebug') 
		{
			wdebug();
			return;
		}
			
		$chprocess();
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'skill424') 
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}完成了第{$b}次<span class=\"yellow\">「除错」</span>尝试</span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>