<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<tr>
<TD class=b2 height="26"><span>
<?php if($arbs) { ?>
<?php echo $iteminfo['DB']?>
<?php } else { ?>
<span class="grey"><?php echo $iteminfo['DB']?></span>
<?php } ?>
</span></TD>
<TD class=b3>
<?php if(CURSCRIPT == 'game' && $mode == 'command' && $arbe) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarb';postCmd('gamecmd','command.php');return false;"</span>
<?php } ?>
<span>
<?php if($arbs) { ?>
<?php echo $arb?>
<?php } else { ?>
<?php echo $noarb?>
<?php } ?>
</span>
</TD>
<TD class=b3><span><?php echo $tpldata['arbsk_words']?></span></TD>
<TD class=b3><span><?php echo $arbe?></span></TD>
<TD class=b3><span><?php echo $arbs?></span></TD>
</tr>
<tr>
<TD class=b2 height="26"><span>
<?php if($arhs) { ?>
<?php echo $iteminfo['DH']?>
<?php } else { ?>
<span class="grey"><?php echo $iteminfo['DH']?></span>
<?php } ?>
</span></TD>
<TD class=b3>
<?php if(CURSCRIPT == 'game' && $mode == 'command' && $arhs) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarh';postCmd('gamecmd','command.php');return false;"</span>
<?php } ?>
<span>
<?php if($arhs) { ?>
<?php echo $arh?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span>
</TD>
<TD class=b3><span><?php echo $tpldata['arhsk_words']?></span></TD>
<TD class=b3><span><?php echo $arhe?></span></TD>
<TD class=b3><span><?php echo $arhs?></span></TD>
</tr>
<tr>
<TD class=b2 height="26"><span>
<?php if($aras) { ?>
<?php echo $iteminfo['DA']?>
<?php } else { ?>
<span class="grey"><?php echo $iteminfo['DA']?></span>
<?php } ?>
</span></TD>
<TD class=b3>
<?php if(CURSCRIPT == 'game' && $mode == 'command' && $aras) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offara';postCmd('gamecmd','command.php');return false;"</span>
<?php } ?>
<span>
<?php if($aras) { ?>
<?php echo $ara?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span>
</TD>
<TD class=b3><span><?php echo $tpldata['arask_words']?></span></TD>
<TD class=b3><span><?php echo $arae?></span></TD>
<TD class=b3><span><?php echo $aras?></span></TD>
</tr>
<tr>
<TD class=b2 height="26"><span>
<?php if($arfs) { ?>
<?php echo $iteminfo['DF']?>
<?php } else { ?>
<span class="grey"><?php echo $iteminfo['DF']?></span>
<?php } ?>
</span></TD>
<TD class=b3>
<?php if(CURSCRIPT == 'game' && $mode == 'command' && $arfs) { ?>
<span><input type="button" value="卸下" onclick="$('mode').value='itemmain';$('command').value='offarf';postCmd('gamecmd','command.php');return false;"</span>
<?php } ?>
<span>
<?php if($arfs) { ?>
<?php echo $arf?>
<?php } else { ?>
<?php echo $noitm?>
<?php } ?>
</span>
</TD>
<TD class=b3><span><?php echo $tpldata['arfsk_words']?></span></TD>
<TD class=b3><span><?php echo $arfe?></span></TD>
<TD class=b3><span><?php echo $arfs?></span></TD>
</tr>
