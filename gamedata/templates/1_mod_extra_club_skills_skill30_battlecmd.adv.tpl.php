<?php if(!defined('IN_GAME')) exit('Access Denied'); $rageneed = \skill30\get_rage_cost30(); if($rage>=$rageneed) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="压制" 
onclick="$('command').value='<?php } else { echo '___aafM'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=30;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
<?php } else { echo '___aafN'; } ?><?php $hpcost = min($hp-1,round($mhp*0.15)); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>消耗<span class="red"><?php } else { echo '___aafO'; } ?><?php echo $hpcost?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点生命值，附加相同的伤害。消耗<span class="red"><?php } else { echo '___aafP'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafQ'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="压制" disabled="true"><br />
<div style="width:90%; text-align:left;">
需要<span class="red"><?php } else { echo '___aafR'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气方可发动
</div>
<?php } else { echo '___aafS'; } ?><?php } ?>

