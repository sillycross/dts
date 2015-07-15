<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_ITEMSHOP__VARS__shops; $shops=&$___LOCAL_ITEMSHOP__VARS__shops;   if(in_array($pls,$shops)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" id="sp_shop" name="sp_shop" value="商店" onclick="$('command').value='special';$('subcmd').name='sp_cmd';$('subcmd').value='sp_shop';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } else { echo '___aadr'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> 
<?php } else { echo '___aads'; } ?>