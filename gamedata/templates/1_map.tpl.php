<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div class="subtitle" >虚拟世界地图</div>
<div id="notice"></div>
<center>
<div><?php } else { echo '___aamF'; } ?><?php echo $mapcontent?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<p><span class="red">红字=禁区</span>；<span class="yellow">黄字=即将成为禁区</span>；<span class="lime">绿字=正常通行</span></p>
</center>
<?php } else { echo '___aamG'; } ?><?php include template('footer'); ?>

