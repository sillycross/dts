<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=25; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
圣光
</span>
<?php } else { echo '___aahH'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
你造成的属性伤害<span class="yellow">+15%</span>，<br>
如果敌人已有对应异常状态，伤害额外增加<span class="yellow">+50%</span>
</span></td>
<td class=b3 width=46>

</td>
<?php } else { echo '___aahI'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
3级时解锁
</span>
<?php } else { echo '___aaf.'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

