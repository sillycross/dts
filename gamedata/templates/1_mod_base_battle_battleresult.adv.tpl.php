<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="mode" value="command">
<input type="hidden" name="command" value="back">
<input type="button" class="cmdbutton" name="submit" value="确定" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aaeq'; } ?>