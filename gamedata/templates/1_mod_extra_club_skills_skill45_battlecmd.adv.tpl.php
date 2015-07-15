<?php if(!defined('IN_GAME')) exit('Access Denied'); $rageneed = \skill45\get_rage_cost45(); if($rage>=$rageneed) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="重拳" 
onclick="$('command').value='<?php } else { echo '___aagg'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=45;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
造成<span class="yellow">130%</span>物理伤害；消耗<span class="red"><?php } else { echo '___aagh'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafX'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="重拳" disabled="true"><br />
<div style="width:90%; text-align:left;">
需要<span class="red"><?php } else { echo '___aagi'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气方可发动
</div>
<?php } else { echo '___aafZ'; } ?><?php } ?>

