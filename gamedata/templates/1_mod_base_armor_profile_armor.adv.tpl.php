<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<TD class=b2 height="26"><span>
<?php } else { echo '___aadn'; } ?><?php if($arbs) { ?>
<?php echo $iteminfo['DB']?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey"><?php } else { echo '___aado'; } ?><?php echo $iteminfo['DB']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3>
<?php } else { echo '___aadp'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command' && $arbe) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarb';postCmd('gamecmd','command.php');return false;"</span>
<?php } else { echo '___aagR'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
<?php } else { echo '___aadr'; } ?><?php if($arbs) { ?>
<?php echo $arb?>
<?php } else { ?>
<?php echo $noarb?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</TD>
<TD class=b3><span><?php } else { echo '___aads'; } ?><?php echo $tpldata['arbsk_words']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arbe?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arbs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
</tr>
<tr>
<TD class=b2 height="26"><span>
<?php } else { echo '___aacb'; } ?><?php if($arhs) { ?>
<?php echo $iteminfo['DH']?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey"><?php } else { echo '___aado'; } ?><?php echo $iteminfo['DH']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3>
<?php } else { echo '___aadp'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command' && $arhs) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarh';postCmd('gamecmd','command.php');return false;"</span>
<?php } else { echo '___aagS'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
<?php } else { echo '___aadr'; } ?><?php if($arhs) { ?>
<?php echo $arh?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</TD>
<TD class=b3><span><?php } else { echo '___aads'; } ?><?php echo $tpldata['arhsk_words']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arhe?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arhs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
</tr>
<tr>
<TD class=b2 height="26"><span>
<?php } else { echo '___aacb'; } ?><?php if($aras) { ?>
<?php echo $iteminfo['DA']?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey"><?php } else { echo '___aado'; } ?><?php echo $iteminfo['DA']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3>
<?php } else { echo '___aadp'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command' && $aras) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offara';postCmd('gamecmd','command.php');return false;"</span>
<?php } else { echo '___aagT'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
<?php } else { echo '___aadr'; } ?><?php if($aras) { ?>
<?php echo $ara?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</TD>
<TD class=b3><span><?php } else { echo '___aads'; } ?><?php echo $tpldata['arask_words']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arae?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $aras?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
</tr>
<tr>
<TD class=b2 height="26"><span>
<?php } else { echo '___aacb'; } ?><?php if($arfs) { ?>
<?php echo $iteminfo['DF']?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="grey"><?php } else { echo '___aado'; } ?><?php echo $iteminfo['DF']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3>
<?php } else { echo '___aadp'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command' && $arfs) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarf';postCmd('gamecmd','command.php');return false;"</span>
<?php } else { echo '___aagU'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
<?php } else { echo '___aadr'; } ?><?php if($arfs) { ?>
<?php echo $arf?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</TD>
<TD class=b3><span><?php } else { echo '___aads'; } ?><?php echo $tpldata['arfsk_words']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arfe?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class=b3><span><?php } else { echo '___aaca'; } ?><?php echo $arfs?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
</tr>
<?php } else { echo '___aagV'; } ?>