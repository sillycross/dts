<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<div class="subtitle"><?php } else { echo '___aaru'; } ?><?php echo $stateinfo[$state]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<div><?php } else { echo '___aarv'; } ?><?php echo $dinfo[$state]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<div>死亡时间：<?php } else { echo '___aajC'; } ?><?php echo $dtime?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<?php } else { echo '___aajD'; } ?><?php if(!empty($kname) && (in_array($state,Array(20,21,22,23,24,28,29)))) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div>凶手：<?php } else { echo '___aajE'; } ?><?php echo $kname?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<?php } else { echo '___aajD'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
<span class="dmg">你死了。</span>
<br><br>
<?php } else { echo '___aarw'; } ?><?php if(isset($weibolog) && strpos($gameurl,'dianbo')!==false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" value="我靠！怒电波之！" onclick="window.location.href='http://dianbo.me/index.php?app=home&mod=Widget&act=share&url=http%3A%2F%2Flg.dianbo.me%2F&title=%<?php } else { echo '___aarx'; } ?><?php echo $weibolog?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>%'">
<?php } else { echo '___aary'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" value="我靠！" disabled>
<?php } else { echo '___aarz'; } ?><?php } ?>

