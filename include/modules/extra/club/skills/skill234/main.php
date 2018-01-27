<?php

namespace skill234
{
	function init() 
	{
		define('MOD_SKILL234_INFO','club;active;locked;');
		eval(import_module('clubbase'));
		$clubskillname[234] = '破解';
	}
	
	function acquire234(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(234,'lvl',0,$pa);
		\skillbase\skill_setvalue(234,'cur1',0,$pa);
		\skillbase\skill_setvalue(234,'cur2',1,$pa);
	}
	
	function lost234(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(234,'lvl',$pa);
		\skillbase\skill_delvalue(234,'cur1',$pa);
		\skillbase\skill_delvalue(234,'cur2',$pa);
	}
	
	function check_unlocked234(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
		
	function wdecode(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger','skill234','skillbase','map'));
		if(\skillbase\skill_query(234)){
			$req1=$goal234[\skillbase\skill_getvalue(234,'cur1')];
			$req2=$goal234[\skillbase\skill_getvalue(234,'cur2')];
			$clv=\skillbase\skill_getvalue(234,'lvl');
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
				$log .= "<span class=\"yellow\">破解成功。</span><br />";
				$log .= "<span class=\"red\">$itm</span>用光了。<br />";
				addnews ( 0, 'skill234', $name, $clv+1);
				${'itm'.$position} = ${'itmk'.$position} = ${'itmsk'.$position} = '';
				${'itme'.$position} =${'itms'.$position} =0;
				if ($clv==50){
					$log .="<span class=\"evergreen\">游戏系统已经完全破解。</span><br />";
					${'itm'.$position} = '游戏解除钥匙';
					${'itmk'.$position} = 'Y';
					${'itmsk'.$position} = 'Z';
					${'itme'.$position} =1;
					${'itms'.$position} =1;
					\skillbase\skill_lost(234);
					
					if ((($areanum/$areaadd)<4)&&(!in_array($gametype,$qiegao_ignore_mode))){
						$get_card_id=63;
						$ext = '您在'.($room_prefix ? '房间' : '').'第'.$gamenum.'局完成了破解流程，获得了奖励卡！';
						\cardbase\get_card_message($get_card_id,$ext);
						$log.='<span class="yellow">您获得了活动奖励卡，请前往“站内邮件”查收。</span><br>';
//						$null = NULL;
//						if (\cardbase\get_card(63,$null,1)==1){
//							$log.="恭喜您获得了活动奖励卡<span class=\"orange\">lemon</span>！<br>";
//						}else{
//							$log.="您已经拥有活动奖励卡了，系统奖励您<span class=\"yellow\">500</span>切糕！<br>";
//							\cardbase\get_qiegao(500);
//						}
					}
					
					$mode = 'command';
					return;
				}
				$gdice=rand(1,4);
				if ($clv<5){
					if ($gdice==1){
						$exp+=10;
						$log .="<span class=\"yellow\">获得了10点经验值。</span><br />";
					}
					if ($gdice==2){
						$att+=10;
						$log .="<span class=\"yellow\">获得了10点基础攻击。</span><br />";
					}
					if ($gdice==3){ 
						$def+=10;
						$log .="<span class=\"yellow\">获得了10点基础防御。</span><br />";
					}if ($gdice==4){
						$wp+=10;$wk+=10;$wc+=10;$wd+=10;$wg+=10;$wf+=10;
						$log .="<span class=\"yellow\">获得了10点全熟练。</span><br />";
					}					
				}else if ($clv<20){
					if ($gdice==1){
						$money+=200;
						$log .="<span class=\"yellow\">获得了180元。</span><br />";
					}
					if ($gdice==2){
						$mhp+=7;
						$log .="<span class=\"yellow\">生命上限提高了7点。</span><br />";
					}
					if ($gdice==3){
						$att+=10;$def+=10;
						$log .="<span class=\"yellow\">基础攻防提高了10点。</span><br />";
					}
					if ($gdice==4){
						$wp+=10;$wk+=10;$wc+=10;$wd+=10;$wg+=10;$wf+=10;
						$log .="<span class=\"yellow\">获得了10点全熟练。</span><br />";
					}		
				}else{
					if ($gdice==1){
						$money+=320;
						$log .="<span class=\"yellow\">获得了320元。</span><br />";
					}
					if ($gdice==2){
						$mhp+=12;
						$log .="<span class=\"yellow\">生命上限提高了12点。</span><br />";
					}
					if ($gdice==3){
						$att+=15;$def+=15;
						$log .="<span class=\"yellow\">基础攻防提高了15点。</span><br />";
					}
					if ($gdice==4){
						$wp+=13;$wk+=13;$wc+=13;$wd+=13;$wg+=13;$wf+=13;
						$log .="<span class=\"yellow\">获得了13点全熟练。</span><br />";
					}		
				}
				$clv++;
				$t=count($goal234)-1;
				if ($clv<10) $t=19;
				if ($clv<5) $t=9;
				$nx=rand(0,$t);
				$ed=$goal234[$nx];
				$log .="下次破解需要物品<span class=\"yellow\">{$ed}</span>或";
				\skillbase\skill_setvalue(234,'cur1',$nx);	
				$nx1=rand(0,$t);
				while ($nx1==$nx) $nx1=rand(0,$t);
				$ed=$goal234[$nx1];
				$log .="<span class=\"yellow\">{$ed}</span>。<br />";
				\skillbase\skill_setvalue(234,'cur2',$nx1);				
				\skillbase\skill_setvalue(234,'lvl',$clv);
				$mode = 'command';
				return;
			}else{
				$log .= "你没有进行破解所需的物品。<br />";
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
	
		if ($mode == 'special' && $command == 'skill234_special' && $subcmd=='wdecode') 
		{
			wdecode();
			return;
		}
			
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'skill234') 
			if ($b==51)
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"evergreen\">{$a}完成了对幻境系统的破解</span></li>";
			else
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}完成了第{$b}次<span class=\"yellow\">「破解」</span>尝试</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>