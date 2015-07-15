<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" name="mode" value="special">
现在想要做什么？<br /><br />
<?php } else { echo '___aapf'; } ?><?php if($command == 'back') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="menu" value="menu"><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br />
<?php } else { echo '___aapg'; } ?><?php } ?>

