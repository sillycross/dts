<?php

namespace skill572
{
	function init() 
	{
		define('MOD_SKILL572_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[572] = '丰收';
	}
	
	function acquire572(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		add_skill572_mapitem();
		\skillbase\skill_lost(572);
	}
	
	function lost572(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function add_skill572_mapitem()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','itemmain'));
		
		$plsnum = sizeof($plsinfo);
		$iqry = '';
		$file = __DIR__.'/config/skill572.config.php';
		$itemlist = openfile($file);
		$in = sizeof($itemlist);
		for($i = 1; $i < $in; $i++) {
			if(!empty($itemlist[$i]) && strpos($itemlist[$i],',')!==false){
				list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = \itemmain\mapitem_data_process(explode(',',$itemlist[$i]));
				for($j = $inum; $j>0; $j--) {
					if ($imap == 99)
					{
						do {
							$rmap = rand(0,$plsnum-1);
						} while (in_array($rmap,$map_noitemdrop_arealist));
					}
					else  $rmap = $imap;
					list($iname, $ikind, $ieff, $ista, $iskind, $rmap) = \itemmain\mapitem_single_data_process($iname, $ikind, $ieff, $ista, $iskind, $rmap);
					$iqry .= "('$iname', '$ikind','$ieff','$ista','$iskind','$rmap'),";
				}
			}
		}
		if(!empty($iqry)){
			$iqry = "INSERT INTO {$tablepre}mapitem (itm,itmk,itme,itms,itmsk,pls) VALUES ".substr($iqry, 0, -1);
			$db->query($iqry);
		}	
	}
	
}

?>
