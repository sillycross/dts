<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=41; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
神速
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
战斗中基础攻击力<span class="yellow">+10%</span>，每次攻击<span class="yellow">50%</span>几率基础攻击<span class="yellow">+1</span>点<br>
受射程内攻击时<span class="yellow">65%</span>几率触发，触发则反击
</span></td>
<td class=b3 width=46>

</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
<?php if(\skillbase\skill_query(43)) { ?>
通过技能“龙胆”解锁
<?php } ?>
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
