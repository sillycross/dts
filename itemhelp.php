<?php

define('CURSCRIPT', 'help');

require './include/common.inc.php';
$mapitemfile = GAME_ROOT.'/include/modules/base/itemmain/config/mapitem.config.php';
$writefile = GAME_ROOT.TPLDIR.'/itemhelp.htm';
if(filemtime($mapitemfile) > filemtime($writefile))
{
	$mixhelpinfo = 
	"<div align=\"middle\"><table>
		<tr>
			<td class=\"b1\" height=20px><span>所在地点</span></td>
			<td class=\"b1\"><span>物品名称</span></td>
			<td class=\"b1\"><span>物品类型</span></td>
			<td class=\"b1\"><span>效/耐</span></td>
			<td class=\"b1\"><span>物品属性</span></td>
			<td class=\"b1\"><span>刷新时间与数量</span></td>
		</tr>
		";
	$file = GAME_ROOT.'/include/modules/base/itemmain/config/mapitem.config.php';
	$itemlist = openfile($file);
	$in = sizeof($itemlist);
	for($i = 1; $i < $in; $i++) 
		if(!empty($itemlist[$i]) && strpos($itemlist[$i],',')!==false)
		{
			list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
			if ($imap==99) $mixhelpinfo.="<tr><td class=\"b3\" height=20px><span>全图随机</span></td>\n"; else $mixhelpinfo.="<tr><td class=\"b3\" height=20px><span>{$plsinfo[$imap]}</span></td>\n";
			$mixhelpinfo.="<td class=\"b3\"><span>{$iname}</span></td>\n";
			$mixhelpinfo.="<td class=\"b3\"><span>";
			$mixhelpinfo.=\itemmain\parse_itmk_words($ikind,0);			
			if (substr($ikind,0,2)=="TO")
				$mixhelpinfo.="（已埋设）";
			else  if (substr($ikind,0,2)=="TN")
				$mixhelpinfo.="（可拾取）";
			else  if ($ikind[0]=="P") 
			{
				if ($ikind[strlen($ikind)-1]=="2") $mixhelpinfo.="（猛毒）"; else $mixhelpinfo.="（有毒）";
			}
			$mixhelpinfo.="</span></td>\n";
			$mixhelpinfo.="<td class=\"b3\"><span>{$ieff}/{$ista}</span></td>\n";
			$mixhelpinfo.="<td class=\"b3\"><span>";
			$mixhelpinfo.=\itemmain\parse_itmsk_words($iskind,0);
			$mixhelpinfo.="</span></td>\n";
			$mixhelpinfo.="<td class=\"b3\"><span>";
			if ($iarea==99) $mixhelpinfo.="每禁"; else $mixhelpinfo.="{$iarea}禁";
			$mixhelpinfo.="刷新{$inum}个</span></td></tr>\n";
		}
	$mixhelpinfo.="</table></div><br>\n";
	writeover($writefile,$mixhelpinfo);
}

include template('itemhelpmain');

?>