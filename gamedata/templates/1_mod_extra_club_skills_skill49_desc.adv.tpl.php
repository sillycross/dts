<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL49__VARS__ragecost,$___LOCAL_SKILL49__VARS__unlockcost; $ragecost=&$___LOCAL_SKILL49__VARS__ragecost; $unlockcost=&$___LOCAL_SKILL49__VARS__unlockcost;   $___TEMP_SKILL_ID=49; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
潜能
</span>
<?php } else { echo '___aahA'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<span class="yellow">战斗技</span>：本次攻击必中且物理伤害<span class="yellow">+20%</span><br>
持投系武器方可发动，消耗<span class="yellow"><?php } else { echo '___aahB'; } ?><?php echo $ragecost?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点怒气
</span></td>
<td class=b3 width=46>
<!--这个只是一个假的按钮，实际点击的是下面那个-->
<?php } else { echo '___aahC'; } ?><?php if(!$___TEMP_THIS_SKILL_ACQUIRED) { if($skillpoint>=$unlockcost) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input id="skill49_unlock_button" type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill49_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="解锁">
<?php } else { echo '___aahD'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" disabled="true" value="解锁">
<?php } else { echo '___aahE'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<?php } else { echo '___aaaX'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
<span style="float:left;width:46px;min-width:46px;max-width:46px;">&nbsp;</span>
消耗<span><?php } else { echo '___aahF'; } ?><?php echo $unlockcost?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点技能点解锁
<span style="float:right;margin-right:1px;">
<?php } else { echo '___aahG'; } ?><?php if($skillpoint>=$unlockcost) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:47px" onclick="$('mode').value='special';$('command').value='skill49_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');$('skill49_unlock_button').disabled=true;this.disabled=true;" value="解锁">
<?php } else { echo '___aahH'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:47px" disabled="true" value="解锁">
<?php } else { echo '___aahI'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</span>
<?php } else { echo '___aahJ'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

