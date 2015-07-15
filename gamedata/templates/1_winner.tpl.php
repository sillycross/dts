<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="subtitle">历史优胜者</span>
<?php } else { echo '___aary'; } ?><?php if($command == 'info') { include template('winnerinfo'); } elseif($command == 'news') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="info" action="winner.php">
<input type="submit" value="返回优胜列表">
<div align="left">
<?php } else { echo '___aarz'; } ?><?php echo $hnewsinfo?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<input type="submit" value="返回优胜列表">
</form>
<?php } else { echo '___aarA'; } ?><?php } else { include template('winnerlist'); } include template('footer'); ?>

