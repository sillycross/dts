<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" id="itemmix" name="itemmix" value="道具合成" onclick="$('command').value='itemmain';$('subcmd').name='itemcmd';$('subcmd').value='itemmix';postCmd('gamecmd','command.php');this.disabled=true;">
 
<?php } else { echo '___aac-'; } ?>