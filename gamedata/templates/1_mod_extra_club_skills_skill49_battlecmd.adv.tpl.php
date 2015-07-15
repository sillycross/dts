<?php if(!defined('IN_GAME')) exit('Access Denied'); $rageneed = \skill49\get_rage_cost49(); if($rage>=$rageneed) { if(substr($wepk,0,2) == 'WC') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="潜能" 
onclick="$('command').value='<?php } else { echo '___aahK'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=49;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
攻击必中且物理伤害<span class="yellow">+20%</span><br>消耗<span class="red"><?php } else { echo '___aahL'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafX'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="潜能" disabled="true"><br />
<div style="width:90%; text-align:left;">
需持<span class="yellow">投系武器</span>
</div>
<?php } else { echo '___aahM'; } ?><?php } } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="潜能" disabled="true"><br />
<div style="width:90%; text-align:left;">
需要<span class="red"><?php } else { echo '___aahN'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafX'; } ?><?php } ?>

