<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>你想要合成什么？<br>

<input type="hidden" name="mode" value="itemmain">
<input type="hidden" name="command" id="command" value="menu">
<br>
<?php } else { echo '___aacV'; } ?><?php if($itms1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="checkbox" id="mitm1" name="mitm1" value="0"><a onclick="$('mitm1').click();" href="javascript:void(0);"><?php } else { echo '___aacW'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></a><br>
<?php } else { echo '___aacX'; } ?><?php } if($itms2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="checkbox" id="mitm2" name="mitm2" value="0"><a onclick="$('mitm2').click();" href="javascript:void(0);"><?php } else { echo '___aacY'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></a><br>
<?php } else { echo '___aacX'; } ?><?php } if($itms3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="checkbox" id="mitm3" name="mitm3" value="0"><a onclick="$('mitm3').click();" href="javascript:void(0);"><?php } else { echo '___aacZ'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></a><br>
<?php } else { echo '___aacX'; } ?><?php } if($itms4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="checkbox" id="mitm4" name="mitm4" value="0"><a onclick="$('mitm4').click();" href="javascript:void(0);"><?php } else { echo '___aac0'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></a><br>
<?php } else { echo '___aacX'; } ?><?php } if($itms5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="checkbox" id="mitm5" name="mitm5" value="0"><a onclick="$('mitm5').click();" href="javascript:void(0);"><?php } else { echo '___aac1'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></a><br>
<?php } else { echo '___aacX'; } ?><?php } if($itms6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="checkbox" id="mitm6" name="mitm6" value="0"><a onclick="$('mitm6').click();" href="javascript:void(0);"><?php } else { echo '___aac2'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></a><br>
<?php } else { echo '___aacX'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<input type="button" class="cmdbutton" name="submit" value="提交" onclick="$('command').value='itemmix';itemmixchooser();postCmd('gamecmd','command.php');this.disabled=true;">
<input type="button" class="cmdbutton" name="submit" value="放弃" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aac3'; } ?>