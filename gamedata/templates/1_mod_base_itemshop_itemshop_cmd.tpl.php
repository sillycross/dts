<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('itemshop')); if(in_array($pls,$shops)) { ?>
<input type="button" class="cmdbutton" id="sp_shop" name="sp_shop" value="商店" onclick="$('command').value='special';$('subcmd').name='sp_cmd';$('subcmd').value='sp_shop';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } ?>
 
