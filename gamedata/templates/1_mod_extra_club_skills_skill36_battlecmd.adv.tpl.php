<?php if(!defined('IN_GAME')) exit('Access Denied'); $rageneed = \skill36\get_rage_cost36(); if($rage>=$rageneed) { if($wepk == 'WP') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="偷袭" 
onclick="$('command').value='<?php } else { echo '___aahe'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=36;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
必定触发技能“<span class="yellow">猛击</span>”且不会被反击。消耗<span class="red"><?php } else { echo '___aahf'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafX'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="偷袭" disabled="true"><br />
<div style="width:90%; text-align:left;">
手持<span class="yellow">殴系武器</span>方可发动
</div>
<?php } else { echo '___aahg'; } ?><?php } } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="偷袭" disabled="true"><br />
<div style="width:90%; text-align:left;">
需要<span class="red"><?php } else { echo '___aahh'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气方可发动
</div>
<?php } else { echo '___aafZ'; } ?><?php } ?>

