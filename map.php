<?php

define('CURSCRIPT', 'map');

require './include/common.inc.php';

$vcoor = Array('A','B','C','D','E','F','G','H','I','J');
$hcoor = range(0,10);

for($i=0;$i < count($plsinfo);$i++){
	if($hack || !\map\check_in_forbidden_area($i, 1)){
		$plscolor[$i] = 'mapspanlime';
	} elseif(\map\check_in_forbidden_area($i)) {
		$plscolor[$i] = 'mapspanred';
	} else {
		$plscolor[$i] = 'mapspanyellow';
	}
	list($v, $h) = explode('-',$xyinfo[$i]);
	$mpp[$v][$h]=$i;
}

//$plsref = array();
//for($i=0;$i < count($plsinfo);$i++){
//	//先判定周围8格有没有临近地区
//	list($v, $h) = explode('-',$xyinfo[$i]);
//	$vn = array_search($v, $vcoor);
//	$vrange = array($vn - 1, $vn, $vn + 1);
//	$hrange = array($h - 1, $h, $h + 1);
//	foreach($vrange as $vval){
//		if($vval >= 0 && $vval < count($vcoor) ){
//			foreach($hrange as $hval){
//				if($hval > 0 && $hval <= count($hcoor)){
//					if(isset($mpp[$vval][$hval])){//存在临近地区（斜角也算）
//						if(!isset($plsref[$i])) $plsref[$i] = array($mpp[$vval][$hval]);
//						else $plsref[$i][] = $mpp[$vval][$hval];
//					}
//				}
//			}
//		}
//	}
//}

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
	$mapcontent .= '<tr align="center"><TD class=map align=center><div class=nttx>'.$vcoor[$i].'</div></TD>';
	for($j=1;$j<=10;$j++){
		if(isset($mpp[$vcoor[$i]][$j])){
			$mapcontent .= '<td width="48" height="48" class="map2" align=middle><span class="'.$plscolor[$mpp[$vcoor[$i]][$j]].'">'.$plsinfo[$mpp[$vcoor[$i]][$j]].'</span></td>';
		}else{
			$mapcontent .= '<td width="48" height="48" class="map2" align=middle><IMG src="map/blank.gif" width="48" height="48" border=0></td>';
		}
	}
	$mapcontent .= '</tr>';
}
$mapcontent .= '</table>';

include template('map');

?>

