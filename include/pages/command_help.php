<?php
if(empty($GLOBALS['___IN_HELP'])) {
	exit('Access Denied');
}

eval(import_module('pose','tactic','itemmain','npc'));

include_once GAME_ROOT . './include/itemplace.func.php';


$mixfile = GAME_ROOT.'./include/modules/base/itemmix/itemmix/config/itemmix.config.php';
include $mixfile;
$writefile = GAME_ROOT.TPLDIR.'/tmp_mixhelp.htm';
//通常合成表自动生成
if(!file_exists($writefile) || filemtime($mixfile) > filemtime($writefile)){
	if(empty($iplacedata)) init_item_place();//初始化itemplace数据
	$mixitem = array();
	foreach($mixinfo as $mix){
		if($mix['class'] !== 'hidden'){
			$mixitmk = \itemmain\parse_itmk_words($mix['result'][1],1);
			$mixitmsk = '';
			if(!empty($mix['result'][4])) $mixitmsk = \itemmain\parse_itmsk_words($mix['result'][4]);
			if ($mixitmsk == '--') $mixitmsk = '';
			$resultjwords = get_resultjwords($mix['result']);

			$mixitem[$mix['class']][] = array('stuff' => $mix['stuff'], 'result' => array($mix['result'][0],$mixitmk,$mix['result'][2],$mix['result'][3],$mixitmsk), 'resultjwords' => $resultjwords);
		}
		
	}
	
	$mixclass = array(
		'wp'=> array('殴系武器','yellow b'),
		'wk'=> array('斩系武器','yellow b'),
		'wg'=> array('射系武器','yellow b'),
		'wc'=> array('投系武器','yellow b'),
		'wd'=> array('爆系武器','yellow b'),
		'wf'=> array('灵系武器','yellow b'),
		'w' => array('其他武器','yellow b'),
		'd' => array('防具','yellow b'),
		'h' => array('补给品','lime b'),
		'wp_mrm'=> array('殴系武器（人形降临系列）','yellow b'),
		'wf_pn'=> array('灵系武器（帕秋莉·诺雷姬系列）','yellow b'),
		'ut'=> array('灵系武器（地下系）','yellow b'),
		'pokemon'=> array('小黄系武器','yellow b'),
		'ocg'=> array('游戏王系武器','cyan b'),
		'key'=> array('KEY系道具','lime b'),
		'cube'=> array('方块系道具','yellow b'),
		'madoka'=> array('最终战术系道具','yellow b'),
		'item'=> array('其他道具','yellow b'),
		);
	$mixhelpinfo = '';
	foreach($mixitem as $class => $list){
		$classname = $mixclass[$class][0];
		$classcolor = $mixclass[$class][1];
		$mixhelpinfo .= "<p><span class=\"$classcolor\">{$classname}合成表</span>：</p>\n";
		$mixhelpinfo .= <<<'MIXITEM_HELP_TABLE_TITLE'
<table>
	<tr>
		<td class="b1" height=20px><span>素材1</span></td>
		<td class="b1"><span>素材2</span></td>
		<td class="b1"><span>素材3</span></td>
		<td class="b1"><span>素材4</span></td>
		<td class="b1"><span>素材5</span></td>
		<td class="b1"><span>素材6</span></td>
		<td class="b1"></td>
		<td class="b1"><span>合成结果</span></td>
		<td class="b1"><span>用途</span></td>
	</tr>
MIXITEM_HELP_TABLE_TITLE;
		foreach($list as $val){
			$itmskword = '';
			if(!empty($val['result'][4])) $itmskword = '/'.$val['result'][4];
			for($i = 2;$i < 6; $i++) {
				if(!isset($val['stuff'][$i])){$val['stuff'][$i] = '-';}
			}
			$mixhelpinfo .= 
			"<tr>
				<td class=\"b3\" height='19px' title='" . get_item_place_single ( $val ['stuff'] [0] ) . "'><span>{$val['stuff'][0]}</span></td>
				";
			for($i = 1; $i < 6; $i ++) {
				$mixhelpinfo .= "<td class=\"b3\"";
				if ($val ['stuff'] [$i] != '-') {
					$mixhelpinfo .= "title='" . get_item_place_single ( $val ['stuff'] [$i] ) . "'";
				}
				$mixhelpinfo .= "><span>{$val['stuff'][$i]}</span></td>
				";
			}
			$resultjwords = '';
			if(!empty($val['resultjwords'])) $resultjwords = ' title="'.$val['resultjwords'].'"';
			$mixhelpinfo .= "<td class=\"b3\"><span>→</span></td>
				<td class=\"b3\"{$resultjwords}><span>{$val['result'][0]}</span></td>
				<td class=\"b3\"><span>{$val['result'][1]}/{$val['result'][2]}/{$val['result'][3]}{$itmskword}</span></td>
			</tr>
			";
		}
		$mixhelpinfo .= "</table>\n";
	}
	
	writeover($writefile,$mixhelpinfo);
}

//同调合成表自动生成
$syncfile = GAME_ROOT.'./include/modules/base/itemmix/itemmix_sync/config/sync.config.php';
$writefile = GAME_ROOT.TPLDIR.'/tmp_mixhelp_sync.htm';
if(!file_exists($writefile) || filemtime($syncfile) > filemtime($writefile)){
	if(empty($iplacedata)) init_item_place();//初始化itemplace数据
	$syncinfo=openfile($syncfile);
	$syncitem = array();
	$syncitem_special = array();
	foreach($syncinfo as $sync){
		$sync_arr=array_combine(array('itm', 'itmk', 'itme', 'itms', 'itmsk', 'star', 'special'), array_slice(explode(',',$sync), 0, 7));
		$sync_arr['resultjwords'] = get_resultjwords($sync_arr);
		$sync_arr['itmk'] = \itemmain\parse_itmk_words($sync_arr['itmk'],1);
		$sync_arr['itmsk'] = \itemmain\parse_itmsk_words($sync_arr['itmsk']);
		if(!empty($sync_arr['special'])){
			$sync_arr['special'] = explode('+',$sync_arr['special']);
			$special_tags = preg_replace('/\d/', '', $sync_arr['special']);
			//似乎原本此处的sm判定不起作用
			if(!in_array('t',$special_tags) && !in_array('st',$special_tags) && !in_array('sm',$special_tags) ){
				$sync_x = 0;
				foreach($sync_arr['special'] as $ssv){
					preg_match('/★(\d+)/s', $ssv, $matches);
					if($matches) $sync_x += $matches[1];
				}
				$otherstar = $sync_arr['star'] - $sync_x;
				if(1 == $otherstar) $sync_arr['special'][] = '星数为1的素材';
				else $sync_arr['special'][] = '星数合计为'.$otherstar.'的素材';
			}else{
				foreach($sync_arr['special'] as &$ssv){
					if(strpos($ssv,'t')===0){
						$tnum = (int)substr($ssv,1);
						if($tnum < 1) $tnum = 1;
						$ssv = ' 带“调整”属性的素材'.$tnum.'体 ';
					}
					elseif('st' == $ssv) $ssv = ' 带“同调”和“调整”属性的素材1体 ';
					elseif(strpos($ssv,'sm')===0){
						$smnum = (int)substr($ssv,2);
						if($smnum < 1) $smnum = 1;
						$ssv = ' 带“同调”但不带“调整”属性的素材'.$smnum.'体以上 ';
					}
				}
			}			
			$syncitem_special[] = $sync_arr;
		}else{
			$syncitem[] = $sync_arr;
		}
	}
	$synchelpinfo = '<p><span class="yellow b">通常同调合成表</span>：</p>';
	$synchelpinfo .= <<<'SYNC_HELP_INFO_DOC'
<table>
	<tr>
		<td class="b1" height=20px><span>同调产物</span></td>
		<td class="b1"><span>用途</span></td>
	</tr>
SYNC_HELP_INFO_DOC;
	$synchelpinfo_special = '<p><span class="lime b">特殊同调合成表</span>：</p>';
	$synchelpinfo_special .= <<<'SYNC_HELP_INFO_SPEC_DOC'
<table>
	<tr>
		<td class="b1" height=20px><span>同调素材一</span></td>
		<td class="b1"><span>同调素材二</span></td>
		<td class="b1"><span>同调素材三</span></td>
		<td class="b1"><span>同调素材四</span></td>
		<td class="b1"><span>同调素材五</span></td>
		<td class="b1"></td>
		<td class="b1"><span>同调产物</span></td>
		<td class="b1"><span>用途</span></td>
	</tr>
SYNC_HELP_INFO_SPEC_DOC;
	
	foreach ($syncitem as $sval){
		if(!empty($sval['itmsk'])){$itmskwords = '/'.$sval['itmsk'];}
		else{$itmskwords = '';}
		$resultjwords = '';
		if($sval['resultjwords']) $resultjwords = ' title="'.$sval['resultjwords'].'"';
		$synchelpinfo .= <<<SYNC_HELP_INFO_DOC_TR
	<tr>
		<td class='b3' height='20px' {$resultjwords}><span>{$sval['itm']}</span></td>
		<td class='b3'><span>{$sval['itmk']}/{$sval['itme']}/{$sval['itms']}{$itmskwords}</span></td>
	</tr>
SYNC_HELP_INFO_DOC_TR;
	}
	$synchelpinfo .= '</table>';
	
	foreach ($syncitem_special as $sval){
		if(!empty($sval['itmsk'])){$itmskwords = '/'.$sval['itmsk'];}
		else{$itmskwords = '';}
		$synchelpinfo_special .= '<tr>';
		for($i = 0; $i <= 4; $i ++) {
			$synchelpinfo_special .= '<td class="b3"';
			if (isset($sval['special'][$i])) $synchelpinfo_special .= "title='" . get_item_place_single ( $sval['special'][$i] ) . "'";
			$synchelpinfo_special .= isset($sval['special'][$i]) ? "><span>{$sval['special'][$i]}</span></td>" : "><span>-</span></td>";
		}
		$resultjwords = '';
		if($sval['resultjwords']) $resultjwords = ' title="'.$sval['resultjwords'].'"';
		$synchelpinfo_special .=<<<SYNC_HELP_INFO_SPEC_DOC_TR
		<td class='b3'>→</td>
		<td class='b3'{$resultjwords}><span>{$sval['itm']}</span></td>
		<td class='b3'><span>{$sval['itmk']}/{$sval['itme']}/{$sval['itms']}{$itmskwords}</span></td>
	</tr>
SYNC_HELP_INFO_SPEC_DOC_TR;
	}
	$synchelpinfo_special .= '</table>';
	
	$writecont=<<<SYNC_HELP_WRITE_CONTENT
<p>以下是可能获得的同调结果的列表。</p>
{$synchelpinfo}
<p>另外，上述只是一般情况。有一些同调结果除了<span class="yellow b">星数符合要求、“调整”属性道具数目正确</span>之外，还必须<span class="yellow b">包含特定的素材</span>才能合成，这些合成将在下表中列出。</p>
{$synchelpinfo_special}
<br>
SYNC_HELP_WRITE_CONTENT;
	writeover($writefile,$writecont);
}
//超量合成表自动生成
$overlayfile = GAME_ROOT.'./include/modules/base/itemmix/itemmix_overlay/config/overlay.config.php';
$writefile = GAME_ROOT.TPLDIR.'/tmp_mixhelp_overlay.htm';
if(!file_exists($writefile) || filemtime($overlayfile) > filemtime($writefile)){
	if(empty($iplacedata)) init_item_place();//初始化itemplace数据
	$overlayinfo=openfile($overlayfile);
	$overlayitem = array();
	foreach($overlayinfo as $overlay){
		$overlay_arr=array_combine(array('itm', 'itmk', 'itme', 'itms', 'itmsk', 'star', 'num'), array_slice(explode(',',$overlay), 0, 7));
		$overlay_arr['resultjwords'] = get_resultjwords($overlay_arr);
		$overlay_arr['itmk'] = \itemmain\parse_itmk_words($overlay_arr['itmk'],1);
		$overlay_arr['itmsk'] = \itemmain\parse_itmsk_words($overlay_arr['itmsk']);
		
		$overlayitem[] = $overlay_arr;
	}
	$overlayhelpinfo = '<p><span class="yellow b">超量合成表</span>：</p>';
	$overlayhelpinfo .= <<<'OVERLAY_HELP_INFO_DOC'
<table>
	<tr>
		<td class="b1" height=20px><span>超量素材类型</span></td>
		<td class="b1"><span>数目</span></td>
		<td class="b1"><span>超量产物</span></td>
		<td class="b1"><span>用途</span></td>
	</tr>
OVERLAY_HELP_INFO_DOC;
	$overlay_star_words = array(
		1 => '游戏王一星素材',
		2 => '游戏王二星素材',
		3 => '游戏王三星素材',
		4 => '游戏王四星素材',
		5 => '游戏王五星素材',
		6 => '游戏王六星素材',
		7 => '游戏王七星素材',
		8 => '游戏王八星素材',
		9 => '游戏王九星素材',
		10 => '游戏王十星素材'
	);
	foreach ($overlayitem as $oval){
		if(!empty($oval['itmsk'])){$itmskwords = '/'.$oval['itmsk'];}
		else{$itmskwords = '';}
		$resultjwords = '';
		if($oval['resultjwords']) $resultjwords = ' title="'.$oval['resultjwords'].'"';
		$overlayhelpinfo .= <<<OVERLAY_HELP_INFO_DOC_TR
	<tr>
		<td class='b3' height='20px'><span>{$overlay_star_words[$oval['star']]}</span></td>
		<td class='b3'><span> × {$oval['num']}</span></td>
		<td class='b3' {$resultjwords}><span>{$oval['itm']}</span></td>
		<td class='b3'><span>{$oval['itmk']}/{$oval['itme']}/{$oval['itms']}{$itmskwords}</span></td>
	</tr>
OVERLAY_HELP_INFO_DOC_TR;
	}
	$overlayhelpinfo .= '</table>';
	
	$writecont=<<<OVERLAY_HELP_WRITE_CONTENT
<p>以下是可能获得的超量结果的列表。</p>
{$overlayhelpinfo}
OVERLAY_HELP_WRITE_CONTENT;
	writeover($writefile,$writecont);
}

//NPC列表自动生成
$npcfile = GAME_ROOT.'./include/modules/base/npc/config/npc.data.config.php';
$npcfile_i8 = GAME_ROOT.'./include/modules/extra/instance/instance8_proud/config/npc.data.config.php';
$npcfile_i9 = GAME_ROOT.'./include/modules/extra/instance/instance9_rush/config/npc.data.config.php';
$npcfile_av = GAME_ROOT.'./include/modules/base/addnpc/config/addnpc.config.php';
$npcfile_ev = GAME_ROOT.'./include/modules/extra/club/skills/skill21/config/evonpc.config.php';
include $npcfile_i8;
include $npcfile_i9;
include $npcfile_av;
include $npcfile_ev;
$enpcinfo_show = array();
foreach ($enpcinfo as $enkey => $enval){
	$enpcinfo_show[$enkey] = array('sub' => array());
	foreach($enval as $enval2){
		$enpcinfo_show[$enkey]['sub'][] = $enval2;
	}
}
$srcfile = GAME_ROOT.TPLDIR.'/npchelp.htm';
$writefile = GAME_ROOT.TPLDIR.'/tmp_npchelp.htm';

$need_refresh = 0;
if(!file_exists($writefile)){
	$need_refresh = 1;
}else{
	foreach(array($npcfile, $npcfile_i8, $npcfile_i9, $npcfile_av, $npcfile_ev, $srcfile ) as $fv){
		if(filemtime($fv) > filemtime($writefile)) $need_refresh = 1;
	}
}

if($need_refresh){
	$writecont = dump_template('npchelp');
	writeover($writefile,$writecont);
}

//称号技能列表自动生成
eval(import_module('skillbase','clubbase'));//这个是动态的所以横竖都得载入
$sdata = \player\create_dummy_playerdata();

$srcfile = GAME_ROOT.TPLDIR.'/clubhelp.htm';
$writefile = GAME_ROOT.TPLDIR.'/tmp_clubhelp.htm';
$tmp_clublist=Array(1,2,3,4,5,9,6,7,8,10,11,13,14,18,19,20,21,24,25,26);//需要展示的称号

$need_refresh = 0;
if(!file_exists($writefile) || filemtime($srcfile) > filemtime($writefile)){//称号涉及的东西太多了，用clubhelp.htm来当开关吧，不一个一个技能判定了
	$need_refresh = 1;
}

if($need_refresh){
	$writecont = dump_template('clubhelp');
	writeover($writefile,$writecont);
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

DL {
	line-height:20px; font-size: 10pt;
}
DT {
	COLOR: #98fb98;font-weight:bold;
}
DD {
	
}

DIV.help_body {
	margin-left:12px;text-align:left;width:900px
}

DIV.help_body A {
	color:#98fb98
}

DIV.help_body img {
	margin:10px 20px;border:1.5px dashed #999;border-radius:5px;
}
DIV.help_body table td.b2h{
	border:1px #000 solid;
	border-width:1px 0;
}
DIV.help_body table td.b3{
	border:1px rgba(98,135,163,0.3) solid;
	border-width:1px 0;
}
</STYLE>
EOT;

include template ( 'help' );

/* End of file command_help.php */
/* Location: /include/pages/command_help.php */