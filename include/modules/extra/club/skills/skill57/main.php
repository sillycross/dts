<?php

namespace skill57
{
	function init() 
	{
		define('MOD_SKILL57_INFO','club;hidden;');
	}
	
	function acquire57(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//雇佣者PID
		\skillbase\skill_setvalue(57,'p','0',$pa);
		//自身编号
		\skillbase\skill_setvalue(57,'l','0',$pa);
	}
	
	function skill57_set_hpid($hpid,&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(57,'p',$hpid,$pa);
	}
	
	function skill57_set_label($z,&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(57,'l',$z,$pa);
	}
	
	function lost57(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(57,$pa) && $pa['hp']>0)
		{
			$i=\skillbase\skill_getvalue(57,'l',$pa);
			$z=\skillbase\skill_getvalue(57,'p',$pa);
			eval(import_module('sys','player','skill56','logger'));
			//判断触发者是否就是雇佣者本身
			if ($z==$pid) $employer=NULL; else $employer=\player\fetch_playerdata_by_pid($z);
			
			$infochanged = 0;
			
			//收钱判断
			$is_hired=(int)\skillbase\skill_getvalue(56,'h'.$i,$employer);
			if ($is_hired==1)
			{
				$lastsal=(int)\skillbase\skill_getvalue(56,'l'.$i,$employer);
				$ty=(int)\skillbase\skill_getvalue(56,'s'.$i);
				while ($now-$lastsal>=60)
				{
					$mercsal=$skill56_npc['sub'][$ty]['mercsalary'];
					if ($employer!==NULL) $emoney = &$employer['money']; else $emoney=&$money;
					if ($emoney>=$mercsal)
					{
						//收钱成功
						$pa['money']+=$mercsal;
						$emoney -=$mercsal;
						$lastsal+=60;
						\skillbase\skill_setvalue(56,'l'.$i,$lastsal,$employer);
						$w_log = '你支付了<span class="yellow b">'.$mercsal.'</span>元作为佣兵<span class="yellow b">'.$skill56_npc['sub'][$ty]['name'].'</span>的薪水！<br>';
						\logger\logsave($z, $lastsal, $w_log ,'s');
						$infochanged = 1;
						$pa['infochanged']=1;
					}
					else
					{
						if ($now-$lastsal>=$merc_leave_timeout)
						{
							//强制解雇
							$pa['money']+=$emoney;
							$emoney=0;
							$lastsal+=$merc_leave_timeout;
							\skillbase\skill_setvalue(56,'l'.$i,$lastsal,$employer);
							\skillbase\skill_setvalue(56,'h'.$i,2,$employer);
							if ($skill56_npc['sub'][$ty]['mercfireaction']==1) $pa['pls']=999;
							$w_log = '<span class="red b">因为你长期拖欠佣兵<span class="yellow b">'.$skill56_npc['sub'][$ty]['name'].'</span>的薪水，佣兵<span class="yellow b">'.$skill56_npc['sub'][$ty]['name'].'</span>与你解除了雇佣合同！</span><br>';
							\logger\logsave($z, $lastsal, $w_log ,'s');
							$infochanged = 1;
							$pa['infochanged']=1;
							break;
						}
						else  break;
					}
				}
			}
			if ($infochanged && $employer!==NULL) \player\player_save($employer);
		}
		
		$chprocess($pa);
	}
}

?>
