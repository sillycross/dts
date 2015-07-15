<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div id="notice"></div>
<div id="aliveinfo">
<center>
<span class="subtitle">幸存者一览</span>
<form method="post" name="alive" onSubmit="return false;">
<input type="hidden" id="alivemode" name="alivemode" value="last">
<input type="button" name="enter" value="显示前<?php } else { echo '___aaph'; } ?><?php echo $alivelimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>名幸存者" onClick="$('alivemode').value='last';postCmd('alive','alive.php');">
<input type="button" name="enter" value="显示全部幸存者" onClick="$('alivemode').value='all';postCmd('alive','alive.php');">
</form>
<div id="alivelist">
<?php } else { echo '___aapi'; } ?><?php include template('alivelist'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
</center>
</div>
<?php } else { echo '___aapj'; } ?><?php include template('footer'); ?>

