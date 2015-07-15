<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="mode" value="itemmain">
<input type="hidden" name="command" value="itemmerge">
<input type="hidden" name="merge1" value="0">
<input type="hidden" id="merge2" name="merge2" value="n">
<br>
是否将 <span class="yellow"><?php } else { echo '___aabK'; } ?><?php echo $itm0?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>与以下物品合并？
<br><br>
<?php } else { echo '___aabL'; } ?><?php if(in_array(1,$sameitem)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" id="itm1" value="<?php } else { echo '___aabM'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('merge2').value='1';postCmd('gamecmd','command.php');return false;"><br> 
<?php } else { echo '___aabN'; } ?><?php } if(in_array(2,$sameitem)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" id="itm2" value="<?php } else { echo '___aabO'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('merge2').value='2';postCmd('gamecmd','command.php');return false;"><br> 
<?php } else { echo '___aabP'; } ?><?php } if(in_array(3,$sameitem)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" id="itm3" value="<?php } else { echo '___aabQ'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('merge2').value='3';postCmd('gamecmd','command.php');return false;"><br> 
<?php } else { echo '___aabR'; } ?><?php } if(in_array(4,$sameitem)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" id="itm4" value="<?php } else { echo '___aabS'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('merge2').value='4';postCmd('gamecmd','command.php');return false;"><br> 
<?php } else { echo '___aabT'; } ?><?php } if(in_array(5,$sameitem)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" id="itm5" value="<?php } else { echo '___aabU'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('merge2').value='5';postCmd('gamecmd','command.php');return false;"><br> 
<?php } else { echo '___aabV'; } ?><?php } if(in_array(6,$sameitem)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" id="itm6" value="<?php } else { echo '___aabW'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('merge2').value='6';postCmd('gamecmd','command.php');return false;"><br> 
<?php } else { echo '___aabX'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<input type="button" class="cmdbutton" name="no" value="不合并" onclick="postCmd('gamecmd','command.php');return false;"><?php } else { echo '___aabY'; } ?>