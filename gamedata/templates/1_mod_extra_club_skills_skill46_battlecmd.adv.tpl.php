<?php if(!defined('IN_GAME')) exit('Access Denied'); $remtime=(int)\skill46\get_remaintime46(); if($remtime>0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="暴打" 
onclick="$('command').value='<?php } else { echo '___aagg'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=46;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
本次攻击物理伤害<span class="yellow">+65%</span>；本次战斗中敌方所有称号技能无效；本局还可发动<span class="red"><?php } else { echo '___aagh'; } ?><?php echo $remtime?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>次
</div>
<?php } else { echo '___aagi'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="暴打" disabled="true"><br />
<div style="width:90%; text-align:left;">
本局发动次数已达上限
</div>
<?php } else { echo '___aagj'; } ?><?php } ?>

