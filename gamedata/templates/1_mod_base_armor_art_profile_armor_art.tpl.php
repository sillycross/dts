<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<tr>
<TD class=b2 height="26"><span>
<?php if($arts) { ?>
<?php echo $tpldata['artk_words']?>
<?php } else { ?>
<span class="grey"><?php echo $iteminfo['A']?></span>
<?php } ?>
</span></TD>
<TD class=b3>
<?php if(CURSCRIPT == 'game' && $mode == 'command' && $arts) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offart';postCmd('gamecmd','command.php');return false;"</span>
<?php } ?>
<span>
<?php if($arts) { ?>
<?php echo $art?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span>
</TD>
<TD class=b3><span><?php echo $tpldata['artsk_words']?></span></TD>
<TD class=b3><span><?php echo $arte?></span></TD>
<TD class=b3><span><?php echo $arts?></span></TD>
</tr> 