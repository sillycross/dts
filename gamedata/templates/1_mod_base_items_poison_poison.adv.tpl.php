<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="mode" value="item">
<input type="hidden" name="usemode" value="poison">
<input type="hidden" name="itmp" value="<?php } else { echo '___aagH'; } ?><?php echo $theitem['itmn']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" id="command" name="command" value="menu">
你想对什么下毒？<br>
<br>
<?php } else { echo '___aagI'; } ?><?php if((strpos ( $itmk1, 'H' ) === 0) || (strpos ( $itmk1, 'P' ) === 0)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" onclick="$('command').value='itm1';postCmd('gamecmd','command.php');this.disabled=true;" value="<?php } else { echo '___aagJ'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br>
<?php } else { echo '___aagK'; } ?><?php } if((strpos ( $itmk2, 'H' ) === 0) || (strpos ( $itmk2, 'P' ) === 0)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" onclick="$('command').value='itm2';postCmd('gamecmd','command.php');this.disabled=true;" value="<?php } else { echo '___aagL'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br>
<?php } else { echo '___aagK'; } ?><?php } if((strpos ( $itmk3, 'H' ) === 0) || (strpos ( $itmk3, 'P' ) === 0)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" onclick="$('command').value='itm3';postCmd('gamecmd','command.php');this.disabled=true;" value="<?php } else { echo '___aagM'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br>
<?php } else { echo '___aagK'; } ?><?php } if((strpos ( $itmk4, 'H' ) === 0) || (strpos ( $itmk4, 'P' ) === 0)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" onclick="$('command').value='itm4';postCmd('gamecmd','command.php');this.disabled=true;" value="<?php } else { echo '___aagN'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br>
<?php } else { echo '___aagK'; } ?><?php } if((strpos ( $itmk5, 'H' ) === 0) || (strpos ( $itmk5, 'P' ) === 0)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" onclick="$('command').value='itm5';postCmd('gamecmd','command.php');this.disabled=true;" value="<?php } else { echo '___aagO'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br>
<?php } else { echo '___aagK'; } ?><?php } if((strpos ( $itmk6, 'H' ) === 0) || (strpos ( $itmk6, 'P' ) === 0)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" onclick="$('command').value='itm6';postCmd('gamecmd','command.php');this.disabled=true;" value="<?php } else { echo '___aagP'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br>
<?php } else { echo '___aagK'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<input type="button" class="cmdbutton" onclick="postCmd('gamecmd','command.php');this.disabled=true;" value="放弃"><?php } else { echo '___aagQ'; } ?>