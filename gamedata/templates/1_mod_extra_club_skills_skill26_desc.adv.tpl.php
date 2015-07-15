<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL26__VARS__upgradecost,$___LOCAL_SKILL26__VARS__ragecost; $upgradecost=&$___LOCAL_SKILL26__VARS__upgradecost; $ragecost=&$___LOCAL_SKILL26__VARS__ragecost;   $___TEMP_SKILL_ID=26; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
聚能
</span>
<?php } else { echo '___aai7'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php } else { echo '___aaf1'; } ?><?php $clv = (int)\skillbase\skill_getvalue(26,'lvl');  if($clv <= 1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">战斗技</span>：消耗<span class="yellow"><?php } else { echo '___aai8'; } ?><?php echo $ragecost['1']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点怒气发动，本次攻击伤害全部视为<span class="red">火焰</span>伤害<br>
可花费<span class="lime"><?php } else { echo '___aai9'; } ?><?php echo $upgradecost?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点技能点升级，升级后伤害变为<span class="red">灼焰</span>伤害，消耗<span class="yellow"><?php } else { echo '___aai.'; } ?><?php echo $ragecost['2']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点怒气
<?php } else { echo '___aai-'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">战斗技</span>：消耗<span class="yellow"><?php } else { echo '___aai8'; } ?><?php echo $ragecost['2']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点怒气发动，本次攻击伤害全部视为<span class="red">灼焰</span>伤害<br>
<?php } else { echo '___aaja'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class=b3 width=46>
<?php } else { echo '___aagA'; } ?><?php if($clv==1 && $skillpoint>=12) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill26_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { echo '___aajb'; } ?><?php } elseif($clv==1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" disabled="true" value="升级">
<?php } else { echo '___aaf.'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<?php } else { echo '___aaaX'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
3级时解锁
</span>
<?php } else { echo '___aagf'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

