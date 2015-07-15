<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL48__VARS__ragecost,$___LOCAL_SKILL48__VARS__skill48_ex_map,$___LOCAL_SKILL48__VARS__skill48_ex_kind_list; $ragecost=&$___LOCAL_SKILL48__VARS__ragecost; $skill48_ex_map=&$___LOCAL_SKILL48__VARS__skill48_ex_map; $skill48_ex_kind_list=&$___LOCAL_SKILL48__VARS__skill48_ex_kind_list;   $___TEMP_SKILL_ID=48; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
附魔
</span>
<?php } else { echo '___aah3'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<span class="yellow">战斗技</span>：攻击后随机选择一种本次攻击造成的属性伤害，<br>你使用投系武器造成的该属性伤害永久<span class="yellow">+3%</span>(最高150%)<br>
持投系武器方可发动，消耗<span class="yellow"><?php } else { echo '___aah4'; } ?><?php echo $ragecost?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点怒气。目前各属性加成统计：<br>
<span style="height:4px; display:block;">&nbsp;</span>
<?php } else { echo '___aah5'; } ?><?php \skill48\get_status_html48(); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aah6'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
5级时解锁
</span>
<?php } else { echo '___aah7'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

