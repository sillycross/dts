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
		echo '<table border="0" cellSpacing=0 cellPadding=0 style="table-layout:fixed;" width="653px">';
		echo '<tr><td class="b1" width="80px">技能来源</td>';
		
		$is_show_cost=$callback_funcname('show_cost');
		$caller_id=$callback_funcname('caller_id');
		
		for ($i=1; $i<=4; $i++) 
		{
			if ($is_show_cost)
				echo '<td class="b1" width="40px">技能</td><td class="b1" width="20px">费</td><td class="b1" width="80px">操作</td>';
			else  echo '<td class="b1" width="60px">技能</td><td class="b1" width="80px">操作</td>';
		}
		echo '</tr>';
		
		$___TEMP_tlis=Array();
		foreach ($clublist as $nowclub => $arr)
			if ($nowclub!=$club)
			{
				$lis=Array();
				foreach ($arr['skills'] as $skillid)
					if (sklearn_basecheck($skillid))
						if ($callback_funcname('is_learnable',$skillid))
							array_push($lis,$skillid);
				
				$sz=count($lis);
				if ($sz%4==0) $lz=4; else if ($sz%3==0) $lz=3; else if ($sz%4>=$sz%3) $lz=4; else $lz=3;
				$rz=floor($sz/$lz); $rz=(int)$rz; if ($sz%$lz!=0) $rz++;
				for ($i=0; $i<$rz; $i++)
				{
					echo '<tr>';
					if ($i==0) echo '<td class="b1" rowspan='.$rz.'>'.$clubinfo[$nowclub].'</td>';
					$cz=$lz; if ($i==$rz-1 && $sz%$lz!=0) $cz=$sz%$lz;
					for ($j=0; $j<$cz; $j++)
					{
						$skillid=$lis[$i*$lz+$j];
						echo '<td class="b3">'.$clubskillname[$skillid].'</td>';
						if (\skillbase\skill_query($skillid))
						{
							if ($is_show_cost) echo '<td class="b3"><span class="grey">∞</span></td>';
							echo '<td class="b3"><span class="grey">已经获得</span></td>';
						}
						else
						{
							if ($is_show_cost)
							{
								$cost=$callback_funcname('query_cost',$skillid);
								if ($cost>0)
									echo '<td class="b3"><span class="lime">'.$cost.'</span></td>';
								else  echo '<td class="b3"><span class="grey">-</span></td>';
							}
							echo '<td class="b3"><span style="width:40px;" onmouseover="$(\'skl_util_'.$caller_id.'_skilllearn_'.$skillid.'\').style.display=\'block\'; $(\'skl_util_'.$caller_id.'_skilllearn_'.$skillid.'\').style.top=Number(jQuery(this).offset().top-jQuery(window).scrollTop()+10).toString()+\'px\'; $(\'skl_util_'.$caller_id.'_skilllearn_'.$skillid.'\').style.left=Number(jQuery(this).offset().left-jQuery(window).scrollLeft()+20'.($j<3?'':'-420').').toString()+\'px\'; " onmouseout="$(\'skl_util_'.$caller_id.'_skilllearn_'.$skillid.'\').style.display=\'none\';"><input type="button" style="width:38px" value="查看"></span>';
							if ($callback_funcname('now_learnable',$skillid))
								echo '<span style="width:40px;"><input type="button" style="width:38px" onclick="$(\'mode\').value=\'special\';$(\'command\').value=\'skill'.$caller_id.'_special\';$(\'subcmd\').value=\'upgrade\';$(\'skillpara1\').value=\''.$skillid.'\';postCmd(\'gamecmd\',\'command.php\');this.disabled=true;" value="学习"></span>';
							else  echo '<span style="width:40px;"><input type="button" style="width:38px" disabled="true" value="学习"></span>';
							array_push($___TEMP_tlis,$skillid);
							echo '</td>';
						}
					}
					for ($j=$cz; $j<4; $j++) 
						if ($is_show_cost) 
							echo '<td></td><td></td><td></td>';
						else  echo '<td></td><td></td>';
					echo '</tr>';
				}
			}
		echo '</table>';
		
		$___TEMP_str='';
		foreach ($___TEMP_tlis as $___TEMP_now_skillid)
		{
			echo '<div id="skl_util_'.$caller_id.'_skilllearn_'.$___TEMP_now_skillid.'" style="width:420px;z-index:10000;position:fixed;display:none; filter:alpha(opacity=95); opacity:0.95; background-color:#000000">';
			echo '<table width=100% height=100%><tr id="skl_util_'.$caller_id.'_skilllearn_tabrow_'.$___TEMP_now_skillid.'">';
			global $___TEMP_IN_SKLEARN_FLAG; $___TEMP_IN_SKLEARN_FLAG=1;
			ob_start();
			include template(constant('MOD_SKILL'.$___TEMP_now_skillid.'_DESC'));
			$str=ob_get_contents();
			ob_end_clean();
			$i=strpos($str,'_____TEMP_SKLEARN_START_FLAG_____')+strlen('_____TEMP_SKLEARN_START_FLAG_____');
			$j=strpos($str,'_____TEMP_SKLEARN_END_FLAG_____');
			echo substr($str,$i,$j-$i);
			unset($i); unset($j); unset($str);
			$___TEMP_IN_SKLEARN_FLAG=0;
			echo '</tr></table>';
			echo '</div>';
			$___TEMP_str.='$(\'skl_util_'.$caller_id.'_skilllearn_tabrow_'.$___TEMP_now_skillid.'\').deleteCell(1);';
		}
		echo '<img style="display:none;" type="hidden" src="img/blank.png" onload="'.$___TEMP_str.'">';
	}	
}

?>
