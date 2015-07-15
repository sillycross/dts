<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL38__VARS__ragecost; $ragecost=&$___LOCAL_SKILL38__VARS__ragecost;   $___TEMP_SKILL_ID=38; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
闷棍
</span>
<?php } else { echo '___aahi'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<span class="yellow">战斗技</span>：本次攻击必定触发技能“<span class="yellow">猛击</span>”，<br>并额外造成(<span class="yellow">敌方体力上限减当前体力</span>)点伤害。<br>
持殴系武器方可发动，发动消耗<span class="yellow"><?php } else { echo '___aahj'; } ?><?php echo $ragecost?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点怒气。<br>
</span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aafR'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
11级时解锁
</span>
<?php } else { echo '___aagD'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

