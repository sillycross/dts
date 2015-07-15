<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('rest')); ?>
<?php echo $restinfo[$state]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 中。。。<br>
<input type="hidden" id="mode" name="mode" value="rest">
<input type="hidden" id="command" name="command" value="rest">
<input type="button" class="cmdbutton" name="rest" value="<?php } else { echo '___aanL'; } ?><?php echo $restinfo[$state]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="postCmd('gamecmd','command.php');this.disabled=true;">
<input type="button" class="cmdbutton" name="back" value="返回" onclick="$('command').value='back';postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aanM'; } ?>