<?php

namespace autopower
{
	global $max_autopower_num; $max_autopower_num = 15;	//一次装载的钉子/磨刀石数量
	global $autopower_penalty; $autopower_penalty = 1.5;	//使用自动钉/磨刀的CD惩罚，玩家将进入一个物品CD×使用钉子数量×惩罚的CD中
	
	function init() {}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('autopower','sys','player','itemmain','cooldown','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];

		$itemselect = get_var_input('itemselect');
		
		if (strpos($itmk,'Z')===0 && ($itm=='全自动订书机' || $itm=='全自动磨刀砂轮'))
		{
			if (!$coldtimeon)
			{
				$log.='服务器没有开启冷却时间，本功能不可用。<br>';
				return;
			}
			if ($itme==0)
			{
				$lis = Array();
				for ($i=1; $i<=6; $i++)
				{
					if (strpos(${'itmk'.$i},'Y')===0 || strpos(${'itmk'.$i},'Z')===0)
					{
						if ($itm=='全自动订书机' && (preg_match("/钉$/",${'itm'.$i}) || preg_match("/钉\[/",${'itm'.$i})))
							array_push($lis,$i);
					
						if ($itm=='全自动磨刀砂轮' && strpos(${'itm'.$i},'磨刀石')!==false)
							array_push($lis,$i);
					}
				}
				
				if (count($lis)==0)
				{
					if ($itm=='全自动订书机')
						$log.='你的背包中没有钉子。<br>';
					else  $log.='你的背包中没有磨刀石。<br>';
					return;
				}
				
				if (count($lis)>1)
				{
					if (empty($itemselect))
					{
						$cmd.='<input type="hidden" id="mode" name="mode" value="command">';
						$cmd.='<input type="hidden" id="command" name="command" value="itm'.$theitem['itmn'].'">';
						$cmd.='<input type="hidden" id="itemselect" name="itemselect" value="999">';
						$cmd.= "请选择你想装填进{$itm}的道具：<br><br>";
						foreach ($lis as $i)
						{
							$cmd.="<input type=\"button\" class=\"cmdbutton\"  style=\"width:200\" value=\"".${'itm'.$i}.'/'.${'itme'.$i}.'/'.${'itms'.$i}."\" onclick=\"$('itemselect').value='".$i."';postCmd('gamecmd','command.php');this.disabled=true;\">";
						}
						$cmd.="<input type=\"button\" class=\"cmdbutton\"  style=\"width:200\" value=\"返回\" onclick=\"postCmd('gamecmd','command.php');this.disabled=true;\">";
						return;
					}
					else
					{
						$i=(int)$itemselect;
						if (!in_array($i,$lis))
						{
							$mode='command'; return; 
						}
						$choice = $i;
					}
				}
				else  $choice = $lis[0];
				
				$num = min($max_autopower_num, ${'itms'.$choice});
				
				$itme = ${'itme'.$choice}; $itms = $num;
				
				$log.="你将{$num}个<span class=\"yellow b\">".${'itm'.$choice}."</span>装填进了{$itm}。<br>";
				${'itms'.$choice} -= $num;
				if (${'itms'.$choice}<=0)
				{
					$log .= "<span class=\"red b\">".${'itm'.$choice}."</span>用光了。<br>";
					${'itm'.$choice} = ${'itmk'.$choice} = ${'itmsk'.$choice} = '';
					${'itme'.$choice} = ${'itms'.$choice} = 0;
				}
			}
			else
			{
				$log.="<span class=\"yellow b\">你启动了机器，饶有兴致地看着机器工作着……</span><br>";
				$success_count = 0;
				for ($i=1; $i<=$itms; $i++)
				{
					$log.='<span id="autopower'.$i.'" style="display:none">';
					if ($itm=='全自动订书机') $funcname = '\empowers\use_nail'; else $funcname = '\empowers\use_hone';
					$flag = $funcname($itm,$itme);
					if (!$flag || $i==$itms) 
					{
						$log.='<span class="yellow b">'.$itm.'停止了工作。<br></span></span>';
						if (!$flag) break;
					}
					else  $log.='</span>';
					$success_count++;
				}
				$log.='<span id="autopower_curnum" style="display:none">1</span>';
				$log.='<span id="autopower_totnum" style="display:none">'.$success_count.'</span>';
				$log.='<span id="autopower_cd" style="display:none">'.round($itemusecoldtime*$autopower_penalty).'</span>';
				\cooldown\set_coldtime(round($itemusecoldtime*$autopower_penalty)*$success_count, true);
				if ($success_count==$itms)
				{
					$itme=0; $itms=1;
				}
				else
				{
					$itms-=$success_count;
				}
			}
			return;
		}
		$chprocess($theitem);
	}	
}

?>
