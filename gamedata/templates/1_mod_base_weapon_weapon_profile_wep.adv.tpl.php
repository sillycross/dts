<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<TD class=b2 height="26"><span>
<?php } else { echo '___aadu'; } ?><?php if($tpldata['wepk_words']) { ?>
<?php echo $tpldata['wepk_words']?>
<?php } else { ?>
<?php echo $mltwk?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3>
<?php } else { echo '___aadw'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command' && $wepe) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offwep';postCmd('gamecmd','command.php');return false;"</span>
<?php } else { echo '___aae3'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
<?php } else { echo '___aady'; } ?><?php if($weps) { ?>
<?php echo $wep?>
<?php } else { ?>
<?php echo $nowep?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</TD>
<TD class=b3><span><?php } else { echo '___aadz'; } ?><?php echo $tpldata['wepsk_words']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aach'; } ?><?php echo $wepe?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aach'; } ?><?php echo $weps?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
</tr> 
<?php } else { echo '___aae4'; } ?>