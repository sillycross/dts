<?php

namespace ex_attr_song
{
	//每一级激奏增加的歌曲效果
	$sa_factor = 20;
	
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^sv'] = '变奏';
		$itemspkdesc['^sv'] = '作战或偷袭姿态时，使歌唱的效果变为歌曲的隐藏效果';
		$itemspkremark['^sv'] = '仅对部分歌曲有效';
		$itemspkinfo['^sa'] = '激奏';
		$itemspkdesc['^sa'] = '增强歌唱的效果，每1级提升20%';
		$itemspkremark['^sa'] = '仅提升数值变化效果';
		$itemspkinfo['^sa1'] = '激奏1';
		$itemspkinfo['^sa2'] = '激奏2';
		$itemspkinfo['^sa3'] = '激奏3';
		$itemspkinfo['^sa4'] = '激奏4';
		$itemspkinfo['^sa5'] = '激奏5';
		$itemspkinfo['^sa6'] = '激奏6';

	}
	
	//变奏改变歌唱效果
	function get_song_effect($songcfg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\attrbase\check_itmsk('^sv') && isset($songcfg['effect_sv']))
		{
			eval(import_module('player'));
			if (($pose == 1) || ($pose == 4)) return $songcfg['effect_sv'];
		}
		return $songcfg['effect'];
	}
	
	//激奏增加歌唱效果加成系数
	function ss_factor(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pdata);
		$dummy = \player\create_dummy_playerdata();
		$skarr = \attrbase\get_ex_def_array($dummy, $pdata, 0);	
		$sa_lvl = \itemmain\check_in_itmsk('^sa', $skarr, 1);
		if ($sa_lvl)
		{
			eval(import_module('ex_attr_song'));
			$ret *= 1 + $sa_lvl * $sa_factor / 100;
		}
		return $ret;
	}
	
}

?>
