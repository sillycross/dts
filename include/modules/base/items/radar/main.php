<?php

namespace radar
{
	global $radarscreen;
	
	function init()
	{
		eval(import_module('itemmain'));
		$iteminfo['ER'] = '探测仪器';
	}
	
	function newradar($mms = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','map','radar','logger'));
		if(!$mode) {
			$log .= '仪器使用失败！<br>';
			return;
		}
		
		$npctplist = Array(90,2,5,6,7,11,14);
		$tdheight = 20;
		$screenheight = count($plsinfo)*$tdheight;
		$result = $db->query("SELECT type,pls FROM {$tablepre}players WHERE hp>0");
		while($cd = $db->fetch_array($result)) {
			$chdata[] = $cd;
		}
		$radar = array();
		foreach ($chdata as $data){
			if(isset($radar[$data['pls']][$data['type']])){$radar[$data['pls']][$data['type']]+=1;}
			else{$radar[$data['pls']][$data['type']]=1;}
		}
		$radarscreen = '<table height='.$screenheight.'px width=720px border="0" cellspacing="0" cellpadding="0" valign="middle"><tbody>';
		$radarscreen .= "<tr>
			<td class=b2 height={$tdheight}px width=120px><div class=nttx></div></td>
			<td class=b2><div class=nttx>{$typeinfo[0]}</div></td>";
		foreach ($npctplist as $value){
			$radarscreen .= "<td class=b2><div class=nttx>{$typeinfo[$value]}</div></td>";
		}
		$radarscreen .= '</tr>';
		for($i=0;$i<count($plsinfo);$i++) {
			$radarscreen .= "<tr><td class=b2 height={$tdheight}px><div class=nttx>{$plsinfo[$i]}</div></td>";
			if((array_search($i,$arealist) > $areanum) || $hack) {
				if($i==$pls) {
					$num0 = $radar[$i][0];
					foreach ($npctplist as $j){
						if($gamestate == 50){${'num'.$j} = 0;}
						else{
							${'num'.$j} = isset($radar[$i][$j]) ? $radar[$i][$j] : 0;
						}
					}
					if($num0){
						$pnum[$i] ="<span class=\"yellow b\">$num0</span>";
					} else {
						$pnum[$i] ='<span class="yellow b">-</span>';
					}
					foreach ($npctplist as $j){
						if(${'num'.$j}){
						${'npc'.$j.'num'}[$i] ="<span class=\"yellow b\">${'num'.$j}</span>";
						} else {
						${'npc'.$j.'num'}[$i] ='<span class="yellow b">-</span>';
						}
					}
				} elseif($mms == 2) {
					$num0 = isset($radar[$i][0]) ? $radar[$i][0] : 0;
					foreach ($npctplist as $j){
						if($gamestate == 50){${'num'.$j} = 0;}
						else{
							${'num'.$j} = isset($radar[$i][$j]) ? $radar[$i][$j] : 0;
						}
						
					}
					if($num0){
						$pnum[$i] =$num0;
					} else {
						$pnum[$i] ='-';
					}
					foreach ($npctplist as $j){
						if(${'num'.$j}){
						${'npc'.$j.'num'}[$i] =${'num'.$j};
						} else {
						${'npc'.$j.'num'}[$i] ='-';
						}
					}
				} else {
					$pnum[$i] = '？';
					foreach ($npctplist as $j){
						${'npc'.$j.'num'}[$i] = '？';
					}
				}
			} else {
				$pnum[$i] = '<span class="red b">×</span>';
				foreach ($npctplist as $j){
					${'npc'.$j.'num'}[$i] = '<span class="red b">×</span>';
				}
			}
			$radarscreen .= "<td class=b3><div class=nttx>{$pnum[$i]}</div></td>";
			foreach ($npctplist as $j){
				$radarscreen .= "<td class=b3><div class=nttx>{${'npc'.$j.'num'}[$i]}</div></td>";
			}	
			$radarscreen .= '</tr>';
		}
		$radarscreen .= '</tbody></table>';
		$log .= '白色数字：该区域内的人数<br><span class="yellow b">黄色数字</span>：自己所在区域的人数<br><span class="red b">×</span>：禁区<br><br>';
		include template(MOD_RADAR_RADARCMD);
		$cmd = ob_get_contents();
		ob_clean();
		$main = MOD_RADAR_RADAR;
		return;
	}

	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'ER' ) === 0) {//雷达
			if ($itme > 0) {
				$log .= "使用了<span class=\"red\">$itm</span>。<br>";
				newradar ( $itmsk );
				$itme--;
				$log .= "消耗了<span class=\"yellow\">$itm</span>的电力。<br>";
				if ($itme <= 0) {
					$log .= $itm . '的电力用光了，请使用电池充电。<br>';
				}
			} else {
				$itme = 0;
				$log .= $itm . '没有电了，请先充电。<br>';
			}
			return;
		}
		$chprocess($theitem);
	}
	
}

?>
