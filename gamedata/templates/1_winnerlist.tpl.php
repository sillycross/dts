<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<br>

<form method="post" name="info" onsubmit="winner.php">
<input type="hidden" id="command" name="command" value="info">
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
<?php if(is_array($winfo)) { foreach($winfo as $gid => $info) { ?>
<TR height="40">
<TD class="b2"><span><?php echo $gid?></span></TD>
<TD class="b3"><span><?php echo $gwin[$info['wmode']]?></span></TD>
<TD class="b3" style="white-space: nowrap;">
<?php if($info['name']) { ?>
<span class="evergreen"><?php echo $info['name']?></span>
<?php } else { ?>
<span class="grey">无</span>
<?php } ?>
</TD>
<TD class="b3"><span><img src="img/<?php echo $info['iconImg']?>" style="width:70;height:40;"></span></TD>
<TD class="b3"><span><?php echo $info['date']?></span><br><span><?php echo $info['time']?></span></TD>
<TD class="b3">
<?php if($info['motto']) { ?>
<span class="white"><?php echo $info['motto']?></span>
<?php } else { ?>
<span class="grey">无</span>
<?php } ?>
</TD>
<TD class="b3">
<?php if($info['wep']) { ?>
<span class="white"><?php echo $info['wep']?></span>
<?php } else { ?>
<span class="grey">无</span>
<?php } ?>
</TD>
<TD class="b3">
<?php if($info['hdmg']) { ?>
<span class="white"><?php echo $info['hdp']?></span>
<?php } else { ?>
<span class="grey">无</span>
<?php } ?>
</TD>
<TD class="b3">
<?php if($info['hkill']) { ?>
<span class="white"><?php echo $info['hkp']?></span>
<?php } else { ?>
<span class="grey">无</span>
<?php } ?>
</TD>
<?php if(defined('MOD_REPLAY')) { ?>
<TD class="b3">
<span>
<input type="button" style="width:60px;height:40px;" value="录像回放" 
<?php if($info['wmode'] && $info['wmode'] !=4 ) { ?>
onclick="document['list']['start'].value = '<?php echo $start?>'; $('command').value='replay';$('gnum').value='<?php echo $gid?>';document.info.submit();"
<?php } else { ?>
disabled
<?php } ?>
>
</span>
</TD>
<?php } else { ?>
<td></td>
<?php } ?>
<TD class="b3" width=60px>
<span>
<input type="button" style="width:60px;height:20px;" value="角色信息" 
<?php if($info['wmode'] && $info['wmode'] != 1 && $info['wmode'] !=4 && $info['wmode'] != 6) { ?>
onclick="$('command').value='info';$('gnum').value='<?php echo $gid?>';document.info.submit();"
<?php } else { ?>
disabled
<?php } ?>
>
<input type="button" style="width:60px;height:20px;" value="该局状况" 
<?php if($info['wmode'] && $info['wmode'] !=4) { ?>
onclick="$('command').value='news';$('gnum').value='<?php echo $gid?>';document.info.submit();"
<?php } else { ?>
disabled
<?php } ?>
>
</span>
</TD>
</TR>
<?php } } ?>
</TABLE>
</center>
</form>

<form method="post" name="list" action="winner.php">
<input type="hidden" name="command" value="list">
<input type="hidden" name="start" value="<?php echo $gamenum?>">
<input style="width: 120px;" type="button" value="最近 <?php echo $winlimit?> 回" onClick="document['list'].submit();">
<br>
<?php if(isset($listinfo)) { ?>
<?php echo $listinfo?>
<?php } ?>
</form>
<?php if($command=='replay' && defined('MOD_REPLAY')) { \replay\get_replay_by_gnum($gnum,$rep_winmode); } ?>
