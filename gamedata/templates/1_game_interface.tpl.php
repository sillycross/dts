<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div id="notice"></div>
<table id="game_interface" border="0" cellspacing="10" cellpadding="0" align="center">
<tr valign=top>
<td>
<div id="main">
<?php } else { echo '___aajY'; } ?><?php if($main=='battle') { include template('battle'); } else { include template('profile'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
</td>
<td valign="top" rowspan="2">
<table border="0" width="300" height="560" cellspacing="0" cellpadding="0" >
<tr>
<td height="20" class="b1">
<div>
<span class="yellow" id="pls"><?php } else { echo '___aajZ'; } ?><?php echo $plsinfo[$pls]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>【<span class="red">剩余：<span id="anum"><?php } else { echo '___aaj0'; } ?><?php echo $alivenum?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>人</span>】
</div>
</td>
</tr>
<tr>
<td valign="top" class="b3" height="540" style="text-align: left;overflow:auto;overflow-x:hidden;">
<div id="log"><?php } else { echo '___aaj1'; } ?><?php echo $log?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<div>
<form method="post" id="gamecmd" name="gamecmd" style="margin: 0px" >
<input type="hidden" id="oprecorder" name="oprecorder" value=""> 
<div id="cmd">
<?php } else { echo '___aaj2'; } ?><?php if($hp <= 0) { include template('death'); } elseif($state >=1 && $state <= 3) { include template('rest'); } elseif($itms0) { include template('MOD_ITEMMAIN_ITEMFIND'); } elseif($cmd) { ?>
<?php echo $cmd?>
<?php } else { include template('command'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>                        
</form>
</div>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<?php } else { echo '___aaj3'; } ?><?php include template('chat'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
</table> 
<?php } else { echo '___aaj4'; } ?><?php if(CURSCRIPT=='game' && !defined('IN_REPLAY')) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><script type="text/javascript">
$('mode').value='command';$('command').value='enter';postCmd('gamecmd','command.php');
</script>
<?php } else { echo '___aaj5'; } ?><?php } ?>

