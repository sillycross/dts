<?php

namespace npcinfo
{
	$bubblebox_style = Array();
	
	function init() {}
	
	function npcinfo_gen_item_description($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		echo $itm;
		if ($itmk!='') echo '/'.\itemmain\parse_itmk_words($itmk);
		if ($itme!='') echo '/'.$itme;
		if ($itms!='') echo '/'.$itms;
		if (\itemmain\count_itmsk_num($itmsk)>0)
		{
			echo '/'.\itemmain\parse_itmsk_words($itmsk);
		}
	}
	
	function npcinfo_get_npc_description($npckind, $npcsubkind)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','player','clubbase','npc','tactic','pose','map'));
		$nownpc = array_merge($npcinit,$npcinfo[$npckind]);
		$nownpc = array_merge($nownpc,$npcinfo[$npckind]['sub'][$npcsubkind]);
		$nownpc['___count']=ceil($npcinfo[$npckind]['num']/sizeof($npcinfo[$npckind]['sub']));
		include template('MOD_NPCINFO_NPCINFO');
	}
	
	function npcinfo_get_npc_description_all($npckind)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('npc'));
		foreach ($npcinfo[$npckind]['sub'] as $key => $value)
		{
			npcinfo_get_npc_description($npckind, $key);
			echo '<br>';
		}
	}
}

?>
