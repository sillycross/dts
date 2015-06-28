<?php

define('CURSCRIPT', 'map');

require './include/common.inc.php';

$mapvcoordinate = Array('A','B','C','D','E','F','G','H','I','J');

for($i=0;$i<count($plsinfo);$i++){
	if($hack || array_search($i,$arealist) > ($areanum + $areaadd)){
		$plscolor[$i] = 'mapspanlime';
	} elseif(array_search($i,$arealist) <= $areanum) {
		$plscolor[$i] = 'mapspanred';
	} else {
		$plscolor[$i] = 'mapspanyellow';
	}
//	$position[$i] = array(substr($xyinfo[$i],0,1),substr($xyinfo[$i],3,1));
	$position=explode('-',$xyinfo[$i]);
	$mpp[$position[0]][$position[1]]=$i;
//	$vposition[$i] = substr($xyinfo[$i],0,1);
//	$hposition[$i] = substr($xyinfo[$i],3,1);
}


$mapcontent = '<TABLE border="1" cellspacing="0" cellpadding="0" align=center background="map/neomap.jpg" style="position:relative;background-repeat:no-repeat;background-position:right bottom;">';
$mapcontent .= 
	'<TR align="center">
		<TD width="48" height="48" class=map align=center><div class=nttx>坐标</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>1</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>2</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>3</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>4</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>5</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>6</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>7</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>8</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>9</div></TD>
		<TD width="48" height="48" class=map align=center><div class=nttx>10</div></TD>
	</TR>';
for($i=0;$i<10;$i++){
	$mapcontent .= '<tr align="center"><TD class=map align=center><div class=nttx>'.$mapvcoordinate[$i].'</div></TD>';
	for($j=1;$j<=10;$j++){
		if(isset($mpp[$mapvcoordinate[$i]][$j])){
			$mapcontent .= '<td width="48" height="48" class="map2" align=middle><span class="'.$plscolor[$mpp[$mapvcoordinate[$i]][$j]].'">'.$plsinfo[$mpp[$mapvcoordinate[$i]][$j]].'</span></td>';
		}else{
			$mapcontent .= '<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>';
		}
	}
	$mapcontent .= '</tr>';
}
$mapcontent .= '</table>';

include template('map');

?>

