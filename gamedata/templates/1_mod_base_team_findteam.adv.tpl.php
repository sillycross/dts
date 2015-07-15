<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>现在想要做什么？<br><br>
<input type="hidden" name="mode" value="senditem">
<input type="hidden" id="command" name="command" value="back">
留言：<br><input size="30" type="text" name="message" maxlength="60"><br><br>
<input type="button" class="cmdbutton" name="back" value="返回" onclick="postCmd('gamecmd','command.php');"><br><br>
<?php } else { echo '___aad-'; } ?><?php if($itms1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>转让：<input type="button" name="item1" value="<?php } else { echo '___aaea'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='item1';postCmd('gamecmd','command.php');"><br>
<?php } else { echo '___aaeb'; } ?><?php } if($itms2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>转让：<input type="button" name="item2" value="<?php } else { echo '___aaec'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='item2';postCmd('gamecmd','command.php');"><br>
<?php } else { echo '___aaed'; } ?><?php } if($itms3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>转让：<input type="button" name="item3" value="<?php } else { echo '___aaee'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='item3';postCmd('gamecmd','command.php');"><br>
<?php } else { echo '___aaef'; } ?><?php } if($itms4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>转让：<input type="button" name="item4" value="<?php } else { echo '___aaeg'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='item4';postCmd('gamecmd','command.php');"><br>
<?php } else { echo '___aaeh'; } ?><?php } if($itms5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>转让：<input type="button" name="item5" value="<?php } else { echo '___aaei'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='item5';postCmd('gamecmd','command.php');"><br>
<?php } else { echo '___aaej'; } ?><?php } if($itms6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>转让：<input type="button" name="item6" value="<?php } else { echo '___aaek'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='item6';postCmd('gamecmd','command.php');"><br>
<?php } else { echo '___aael'; } ?><?php } ?>

