<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><?phpphp include GAME_ROOT.'./report/ddgb.php'; ?>

<form method="post" name="backindex" action="index.php">
<input type="submit" name="enter" value="返回首页">
</form>
<?php } else { echo '___aasQ'; } ?><?php include template('footer'); ?>

