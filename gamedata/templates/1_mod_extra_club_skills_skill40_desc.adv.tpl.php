<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=40; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
活化
</span>
<?php } else { echo '___aae3'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
每次攻击基础攻击<span class="yellow">+1</span>点，每次被攻击基础防御<span class="yellow">+1</span>点
</span></td>
<td class=b3 width=46>

</td>
<?php } else { echo '___aae4'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">

</span>
<?php } else { echo '___aae5'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

