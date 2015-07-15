<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>

<form method="post" name="info" onsubmit="winner.php">
<input type="hidden" id="command" name="command" value="info">
<input type="hidden" id="start" name="start" value="<?php } else { echo '___aan3'; } ?><?php echo $gamenum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" id="gnum" name="gnum" value="">
<center>
<TABLE border="1" cellspacing="0" cellpadding="0">
<TR height="20">
<TD class="b1"><span>回</span></TD>
<TD class="b1"><span>胜利方式</span></TD>
<TD class="b1"><span>优胜者名</span></TD>
<TD class="b1"><span>头像</span></TD>
<TD class="b1"><span>游戏结束时间</span></TD>
<TD class="b1"><span>胜利者留言</span></TD>
<TD class="b1"><span>使用武器</span></TD>
<TD class="b1"><span>最高伤害</span></TD>
<TD class="b1"><span>杀人最多</span></TD>
<TD class="b1" colspan=2><span>查看信息</span></TD>
</TR>
<?php } else { echo '___aan4'; } ?><?php if(is_array($winfo)) { foreach($winfo as $gid => $info) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><TR height="40">
<TD class="b2"><span><?php } else { echo '___aan5'; } ?><?php echo $gid?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span><?php } else { echo '___aan6'; } ?><?php echo $gwin[$info['wmode']]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3" style="white-space: nowrap;">
<?php } else { echo '___aan7'; } ?><?php if($info['name']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="evergreen"><?php } else { echo '___aan8'; } ?><?php echo $info['name']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey">无</span>
<?php } else { echo '___aan9'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></TD>
<TD class="b3"><span><img src="img/<?php } else { echo '___aan.'; } ?><?php echo $info['iconImg']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" style="width:70;height:40;"></span></TD>
<TD class="b3"><span><?php } else { echo '___aan-'; } ?><?php echo $info['date']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span><br><span><?php } else { echo '___aaoa'; } ?><?php echo $info['time']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3">
<?php } else { echo '___aaob'; } ?><?php if($info['motto']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="white"><?php } else { echo '___aaoc'; } ?><?php echo $info['motto']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey">无</span>
<?php } else { echo '___aan9'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></TD>
<TD class="b3">
<?php } else { echo '___aaod'; } ?><?php if($info['wep']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="white"><?php } else { echo '___aaoc'; } ?><?php echo $info['wep']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey">无</span>
<?php } else { echo '___aan9'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></TD>
<TD class="b3">
<?php } else { echo '___aaod'; } ?><?php if($info['hdmg']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="white"><?php } else { echo '___aaoc'; } ?><?php echo $info['hdp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey">无</span>
<?php } else { echo '___aan9'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></TD>
<TD class="b3">
<?php } else { echo '___aaod'; } ?><?php if($info['hkill']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="white"><?php } else { echo '___aaoc'; } ?><?php echo $info['hkp']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey">无</span>
<?php } else { echo '___aan9'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></TD>
<?php } else { echo '___aaoe'; } ?><?php if(defined('MOD_REPLAY')) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><TD class="b3">
<span>
<input type="button" style="width:80px;height:40px;-webkit-appearance:default-button;" value="录像回放" 
<?php } else { echo '___aaof'; } ?><?php if($info['wmode'] && $info['wmode'] !=4 ) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('start').value = '<?php } else { echo '___aaog'; } ?><?php echo $start?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>'; $('command').value='replay';$('gnum').value='<?php } else { echo '___aaoh'; } ?><?php echo $gid?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';document.info.submit();"
<?php } else { echo '___aaoi'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled
<?php } else { echo '___aaaQ'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>
</span>
</TD>
<?php } else { echo '___aaoj'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td></td>
<?php } else { echo '___aaok'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><TD class="b3" width=60px>
<span>
<input type="button" style="width:80px;height:20px;" value="角色信息" 
<?php } else { echo '___aaol'; } ?><?php if($info['wmode'] && $info['wmode'] != 1 && $info['wmode'] !=4 && $info['wmode'] != 6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('command').value='info';$('gnum').value='<?php } else { echo '___aaom'; } ?><?php echo $gid?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';document.info.submit();"
<?php } else { echo '___aaoi'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled
<?php } else { echo '___aaaQ'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>
<input type="button" style="width:80px;height:20px;" value="该局状况" 
<?php } else { echo '___aaon'; } ?><?php if($info['wmode'] && $info['wmode'] !=4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('command').value='news';$('gnum').value='<?php } else { echo '___aaoo'; } ?><?php echo $gid?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';document.info.submit();"
<?php } else { echo '___aaoi'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled
<?php } else { echo '___aaaQ'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>
</span>
</TD>
</TR>
<?php } else { echo '___aaop'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></TABLE>
</center>
</form>

<form method="post" name="list" action="winner.php">
<input type="hidden" name="command" value="list">
<input type="hidden" name="start" value="<?php } else { echo '___aaoq'; } ?><?php echo $gamenum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input style="width: 120px;" type="button" value="最近 <?php } else { echo '___aaor'; } ?><?php echo $winlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 回" onClick="document['list'].submit();">
<br>
<?php } else { echo '___aaos'; } ?><?php if(isset($listinfo)) { ?>
<?php echo $listinfo?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></form>
<?php } else { echo '___aaot'; } ?><?php if($command=='replay' && defined('MOD_REPLAY')) { \replay\get_replay_by_gnum($gnum,$rep_winmode); } ?>

