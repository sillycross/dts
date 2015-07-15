<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>发现了物品 <span class="yellow"><?php } else { echo '___aab7'; } ?><?php echo $itm0?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>，类型：<?php } else { echo '___aab8'; } ?><?php echo $tpldata['itmk0_words']?>
<?php if(($itmsk0) && !is_numeric($itmsk0)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>，属性：<?php } else { echo '___aab9'; } ?><?php echo $tpldata['itmsk0_words']?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>，效：<?php } else { echo '___aab.'; } ?><?php echo $itme0?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>，耐：<?php } else { echo '___aab-'; } ?><?php echo $itms0?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>。
<br>
<br>
你想如何处理？
<br>
<input type="hidden" id="mode" name="mode" value="itemmain">
<input type="hidden" id="command" name="command" value="itemget">
<input type="button" class="cmdbutton" name="itemget" value="拾取" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><br><br>
<input type="button" class="cmdbutton" name="dropitm0" value="丢弃" onclick="$('command').value='dropitm0';postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aaca'; } ?>