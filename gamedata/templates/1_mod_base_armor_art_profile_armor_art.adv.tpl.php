<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<TD class=b2 height="26"><span>
<?php } else { echo '___aadn'; } ?><?php if($arts) { ?>
<?php echo $tpldata['artk_words']?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey"><?php } else { echo '___aado'; } ?><?php echo $iteminfo['A']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3>
<?php } else { echo '___aadp'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command' && $arts) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offart';postCmd('gamecmd','command.php');return false;"</span>
<?php } else { echo '___aadq'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
<?php } else { echo '___aadr'; } ?><?php if($arts) { ?>
<?php echo $art?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</TD>
<TD class=b3><span><?php } else { echo '___aads'; } ?><?php echo $tpldata['artsk_words']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arte?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arts?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
</tr> <?php } else { echo '___aadt'; } ?>