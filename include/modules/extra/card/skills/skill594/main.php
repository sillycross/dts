<?php

namespace skill594
{
	function init() 
	{
		define('MOD_SKILL594_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[594] = '无双';
	}
	
	function acquire594(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$usecount = \skillbase\skill_getvalue(594,'usecount',$pa);
		eval(import_module('logger'));		
		if ($usecount >= 3)
		{
			$log .= '<span class="yellow b">看起来你的身体无法承受药剂的能量……<br>果然这一点都不值得……<br></span>';
			$pa['state'] = 34;
			\player\update_sdata();
			$pa['sourceless'] = 1;
			\player\kill($pa,$pa);
			\player\player_save($pa);
			\player\load_playerdata($pa);
		}
		else
		{
			\skillbase\skill_setvalue(594,'usecount',(int)$usecount + 1,$pa);
			$log .= '<span class="yellow b">你感到力量充满了全身！<br></span>';
			$pa['mhp'] += 300;
			$pa['hp'] += 300;
			$pa['msp'] += 300;
			$pa['sp'] += 300;
			$pa['att'] += 888;
			$pa['def'] += 888;
		}
	}
	
	function lost594(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['mhp'] -= 200;
		if ($pa['mhp'] <= 0) $pa['mhp'] = 1;
		$pa['hp'] = min($pa['hp'], $pa['mhp']);
		$pa['msp'] -= 200;
		if ($pa['msp'] <= 0) $pa['msp'] = 1;
		$pa['sp'] = min($pa['sp'], $pa['msp']);
		$pa['att'] -= 666;
		$pa['def'] -= 666;
	}

}

?>
