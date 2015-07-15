<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="mode" value="command">
<input type="hidden" name="command" value="menu">
<input type="button" class="cmdbutton" name="submit" value="返回" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aadt'; } ?>