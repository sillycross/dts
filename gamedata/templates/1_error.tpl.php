<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div class="subtitle" ><?php } else { echo '___aamT'; } ?><?php echo $message?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<?php } else { echo '___aajK'; } ?><?php if($errorinfo) { ?>
file=<?php echo $file?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br \>line=<?php } else { echo '___aamU'; } ?><?php echo $line?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
<?php } else { echo '___aamV'; } ?><?php include template('footer'); ?>

