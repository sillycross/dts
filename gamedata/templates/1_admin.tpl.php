<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><center>
<div class="subtitle" >游戏管理</div>
<div><span class="yellow"><?php } else { echo '___aauK'; } ?><?php echo $lang['mygroup']?> <?php echo $mygroup?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></div>
<div><span class="yellow"><?php } else { echo '___aauL'; } ?><?php echo $cmd_info?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></div>
<?php } else { echo '___aauM'; } ?><?php if($showdata) { ?>
<?php echo $showdata?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div>
<form method="post" name="goto_menu" action="admin.php">
<input type="submit" name="enter" value="<?php } else { echo '___aauN'; } ?><?php echo $lang['goto_menu']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
</form>
</div>
<?php } else { echo '___aauO'; } ?><?php } else { include template('admin_menu'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></center>
<?php } else { echo '___aauP'; } ?><?php include template('footer'); ?>

