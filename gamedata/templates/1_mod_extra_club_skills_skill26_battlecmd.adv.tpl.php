<?php if(!defined('IN_GAME')) exit('Access Denied'); $rageneed = \skill26\get_rage_cost26(); $sklvl = \skillbase\skill_getvalue(26,'lvl'); if($rage>=$rageneed) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="聚能" 
onclick="$('command').value='<?php } else { echo '___aajc'; } ?><?php echo $w1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';$('bskill').value=26;postCmd('gamecmd','command.php');this.disabled=true;"><br />
<div style="width:90%; text-align:left;">
<?php } else { echo '___aajd'; } ?><?php if($sklvl == 1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>本次攻击伤害全部视为<span class="red">火焰</span>伤害
<?php } else { echo '___aaje'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>本次攻击伤害全部视为<span class="red">灼焰</span>伤害
<?php } else { echo '___aajf'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>消耗<span class="red"><?php } else { echo '___aafV'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气
</div>
<?php } else { echo '___aafX'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" style="width:98" name="" value="聚能" disabled="true"><br />
<div style="width:90%; text-align:left;">
需要<span class="red"><?php } else { echo '___aajg'; } ?><?php echo $rageneed?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>怒气方可发动
</div>
<?php } else { echo '___aafZ'; } ?><?php } ?>

