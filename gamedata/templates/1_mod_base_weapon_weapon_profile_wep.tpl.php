<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<tr>
<TD class=b2 height="26"><span>
<?php if($tpldata['wepk_words']) { ?>
<?php echo $tpldata['wepk_words']?>
<?php } else { ?>
<?php echo $mltwk?>
<?php } ?>
</span></TD>
<TD class=b3>
<?php if(CURSCRIPT == 'game' && $mode == 'command' && $wepe) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offwep';postCmd('gamecmd','command.php');return false;"</span>
<?php } ?>
<span>
<?php if($weps) { ?>
<?php echo $wep?>
<?php } else { ?>
<?php echo $nowep?>
<?php } ?>
</span>
</TD>
<TD class=b3><span><?php echo $tpldata['wepsk_words']?></span></TD>
<TD class=b3><span><?php echo $wepe?></span></TD>
<TD class=b3><span><?php echo $weps?></span></TD>
</tr> 
