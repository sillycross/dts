<?php if(!defined('IN_GAME')) exit('Access Denied'); $rageneed = \skill48\get_rage_cost48(); if($rage>=$rageneed) { if(substr($wepk,0,2) == 'WC') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="附魔" 
onclick="$('command').value='<?php } else { echo '___aah1'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=48;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
随机选择一种本次攻击造成的属性伤害，你造成的该种属性伤害将永久<span class="yellow">+3%</span>；消耗<span class="red"><?php } else { echo '___aah2'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafQ'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="附魔" disabled="true"><br />
<div style="width:90%; text-align:left;">
手持<span class="yellow">投系武器</span>方可发动
</div>
<?php } else { echo '___aah3'; } ?><?php } } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="附魔" disabled="true"><br />
<div style="width:90%; text-align:left;">
需要<span class="red"><?php } else { echo '___aah4'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气方可发动
</div>
<?php } else { echo '___aafS'; } ?><?php } ?>

