<?php
define ( 'CURSCRIPT', 'help' );
define ( 'IN_HELP', TRUE );

require './include/common.inc.php';

eval ( import_module ( 'itemmain' ) );

$mixfile = GAME_ROOT . './include/modules/base/itemmix/itemmix/config/itemmix.config.php';
require $mixfile;
$writefile = GAME_ROOT . TPLDIR . '/mixhelp.htm';

if (filemtime ( $mixfile ) > filemtime ( $writefile )) {
	$mixitem = array ();
	foreach ( $mixinfo as $mix ) {
		if ($mix ['class'] !== 'hidden') {
			$mixitmk = \itemmain\parse_itmk_words ( $mix ['result'] [1] );
			$mixitmsk = '';
			if (count ( $mix ['result'] ) > 4) {
				$mixitmsk = \itemmain\parse_itmsk_words ( $mix ['result'] [4] );
			}
			if ($mixitmsk == '--')
				$mixitmsk = '';
			$mixitem [$mix ['class']] [] = array (
					'stuff' => $mix ['stuff'],
					'result' => array (
							$mix ['result'] [0],
							$mixitmk,
							$mix ['result'] [2],
							$mix ['result'] [3],
							$mixitmsk 
					) 
			);
		}
	}
	
	$mixclass = array (
			'wp' => array (
					'殴系武器',
					'yellow' 
			),
			'wk' => array (
					'斩系武器',
					'yellow' 
			),
			'wg' => array (
					'射系武器',
					'yellow' 
			),
			'wc' => array (
					'投系武器',
					'yellow' 
			),
			'wd' => array (
					'爆系武器',
					'yellow' 
			),
			'wf' => array (
					'灵系武器',
					'yellow' 
			),
			'w' => array (
					'其他装备',
					'yellow' 
			),
			'h' => array (
					'补给品',
					'lime' 
			),
			'pokemon' => array (
					'小黄系道具',
					'yellow' 
			),
			'ocg' => array (
					'游戏王系道具',
					'clan' 
			),
			'key' => array (
					'KEY系道具',
					'lime' 
			),
			'cube' => array (
					'方块系道具',
					'yellow' 
			),
			'item' => array (
					'其他道具',
					'yellow' 
			) 
	);
	$mixhelpinfo = '';
	include_once GAME_ROOT . './include/itemplace.func.php';
	
	foreach ( $mixitem as $class => $list ) {
		$classname = $mixclass [$class] [0];
		$classcolor = $mixclass [$class] [1];
		$mixhelpinfo .= "<p><span class=\"$classcolor\">{$classname}合成表</span>：</p>\n";
		$mixhelpinfo .= "<table>
			<tr>
				<td class=\"b1\" height=20px><span>合成材料一</span></td>
				<td class=\"b1\"><span>合成材料二</span></td>
				<td class=\"b1\"><span>合成材料三</span></td>
				<td class=\"b1\"><span>合成材料四</span></td>
				<td class=\"b1\"><span>合成材料五</span></td>
				<td class=\"b1\"></td>
				<td class=\"b1\"><span>合成结果</span></td>
				<td class=\"b1\"><span>用途</span></td>
			</tr>
			";
		foreach ( $list as $val ) {
			if (! empty ( $val ['result'] [4] )) {
				$itmskword = '/' . $val ['result'] [4];
			} else {
				$itmskword = '';
			}
			if (! isset ( $val ['stuff'] [2] )) {
				$val ['stuff'] [2] = '-';
			}
			if (! isset ( $val ['stuff'] [3] )) {
				$val ['stuff'] [3] = '-';
			}
			if (! isset ( $val ['stuff'] [4] )) {
				$val ['stuff'] [4] = '-';
			}
			$mixhelpinfo .= "<tr>
				<td class=\"b3\" height='19px' title='" . get_item_place ( $val ['stuff'] [0] ) . "'><span>{$val['stuff'][0]}</span></td>";
			for($i = 1; $i < 5; $i ++) {
				$mixhelpinfo .= "<td class=\"b3\"";
				if ($val ['stuff'] [$i] != '-') {
					$mixhelpinfo .= "title='" . get_item_place ( $val ['stuff'] [$i] ) . "'";
				}
				$mixhelpinfo .= "><span>{$val['stuff'][$i]}</span></td>";
			}
			$mixhelpinfo .= "<td class=\"b3\">→</td>
				<td class=\"b3\"><span>{$val['result'][0]}</span></td>
				<td class=\"b3\"><span>{$val['result'][1]}/{$val['result'][2]}/{$val['result'][3]}{$itmskword}</span></td>
			</tr>
			";
		}
		$mixhelpinfo .= "</table>\n";
	}
	
	writeover ( $writefile, $mixhelpinfo );
}

$extrahead = <<<EOT
<STYLE type=text/css>
BODY {
	FONT-SIZE: 10pt;MARGIN: 0; color:#eee; FONT-FAMILY: "Trebuchet MS","Gill Sans","Microsoft Sans Serif",sans-serif;
}
A {
	COLOR: #eee
}
A:visited {
	COLOR: #eee
}
A:active {
	color: #98fb98;text-decoration:underline
}
P{ line-height:16px
}

.subtitle2 {
	font-family: "微软雅黑"; color: #98fb98; width: 100%;font-size: 16px;font-weight:900;
}

DIV.FAQ {
	PADDING-LEFT: 1em; line-height:16px
}
DIV.FAQ DT {
	COLOR: #98fb98
}
DIV.FAQ DD {
	
}

</STYLE>
EOT;

include template ( 'help' );

?>