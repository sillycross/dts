<?php

define('CURSCRIPT', 'help');

require './include/common.inc.php';
$selffile = GAME_ROOT.'/itemhelp.php';
$mapitemfile = GAME_ROOT.'/include/modules/base/itemmain/config/mapitem.config.php';
$mapitemfile_i8 = GAME_ROOT.'./include/modules/extra/instance/instance8_proud/config/mapitem.config.php';
$mapitemfile_i9 = GAME_ROOT.'./include/modules/extra/instance/instance9_rush/config/mapitem.config.php';
$writefile = GAME_ROOT.TPLDIR.'/tmp_itemhelp.htm';
if(!file_exists($writefile) || filemtime($selffile) > filemtime($writefile) || filemtime($mapitemfile) > filemtime($writefile) || filemtime($mapitemfile_i8) > filemtime($writefile)  || filemtime($mapitemfile_i9) > filemtime($writefile))
{
	$iinfo_all = array();
	$list = Array('标准局道具列表' => $mapitemfile, '荣耀模式道具列表' => $mapitemfile_i8, '极速模式道具列表' => $mapitemfile_i9);
	foreach ($list as $fkey => $fval){
		$iinfo_list = array();
		$itemlist = openfile($fval);
		$in = sizeof($itemlist);
		for($i = 1; $i < $in; $i++) {
			if(!empty($itemlist[$i]) && strpos($itemlist[$i],',')!==false)
			{
				list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
				if ($imap==99) $imap_w = '全图随机'; else $imap_w = $plsinfo[$imap];
				$ikind_w=\itemmain\parse_itmk_words($ikind,0);
				if (substr($ikind,0,2)=="TO")
					$ikind_w.="（已埋设）";
				elseif (substr($ikind,0,2)=="TN")
					$ikind_w.="（可拾取）";
				elseif ($ikind[0]=="P") 
				{
					$psign = strlen($ikind) >= 3 ? substr($ikind,2,1) : 0;
					if('1' === $psign) $psign=1.5;
					if(!$psign) $ikind_w.="（有毒）";
					else $ikind_w.="（{$psign}倍毒）";
					//if ($ikind[strlen($ikind)-1]>="2") $ikind_w.="（猛毒）"; else $ikind_w.="（有毒）";
				}
				$iskind_w = \itemmain\parse_itmsk_words($iskind,0);
				if ($iarea==99) $iarea_w = "每禁"; 
				elseif ($iarea==98) $iarea_w = "1禁后每禁"; 
				else $iarea_w = "{$iarea}禁";
				$iarea_w .= "刷新{$inum}个";
				$iinfo_list[] = array($iarea_w,$imap_w,$inum,$iname,$ikind_w,$ieff,$ista,$iskind_w);
			}
		}
		$iinfo_all[$fkey] = $iinfo_list;
	}
	$itemdata = dump_template('itemhelpcont');
	writeover($writefile,$itemdata);
}
$itemhelpcont = file_get_contents($writefile);
include template('itemhelpmain');

?>