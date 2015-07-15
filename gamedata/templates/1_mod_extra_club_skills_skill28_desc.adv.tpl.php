<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=28; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
毅重
</span>
<?php } else { echo '___aag9'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
当你的武器没有任何属性时，你视为装备着“<span class="yellow">全系防御</span>”。
</span></td>
<td class=b3 width=46>

</td>
<?php } else { echo '___aag.'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
3级时解锁
</span>
<?php } else { echo '___aagf'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

