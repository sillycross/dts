<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=42; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
恃勇
</span>
<?php } else { echo '___aajh'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
先制攻击率<span class="yellow">+12%</span><br>
战斗中基础防御力<span class="yellow">+35%</span>，每次被攻击基础防御<span class="yellow">+2</span>点<br>
每击杀一个敌人基础攻击<span class="yellow">+2</span>点，基础防御<span class="yellow">+4</span>点<br>
但战斗中敌方视为具有技能“<span class="yellow">神速</span>”
</span></td>
<td class=b3 width=46>

</td>
<?php } else { echo '___aaji'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
<?php } else { echo '___aaf6'; } ?><?php if(\skillbase\skill_query(43)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>通过技能“龙胆”解锁
<?php } else { echo '___aaf7'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

