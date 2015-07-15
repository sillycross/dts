<?php if(!defined('IN_GAME')) exit('Access Denied'); $rageneed = \skill38\get_rage_cost38(); if($rage>=$rageneed) { if($wepk == 'WP') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="闷棍" 
onclick="$('command').value='<?php } else { echo '___aahd'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=38;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
必定触发技能“<span class="yellow">猛击</span>”，并附加(<span class="yellow">敌方体力上限减当前体力</span>)点伤害。消耗<span class="red"><?php } else { echo '___aahe'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafQ'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="闷棍" disabled="true"><br />
<div style="width:90%; text-align:left;">
手持<span class="yellow">殴系武器</span>方可发动
</div>
<?php } else { echo '___aahf'; } ?><?php } } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="闷棍" disabled="true"><br />
<div style="width:90%; text-align:left;">
需要<span class="red"><?php } else { echo '___aahg'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气方可发动
</div>
<?php } else { echo '___aafS'; } ?><?php } ?>

