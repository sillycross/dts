<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill50')); $___TEMP_SKILL_ID=50; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
枭眼
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
如果你的武器射程不小于敌人，你对其先制攻击率<span class="yellow">+10%</span>，<br>其攻击你时命中率<span class="yellow">-12%</span>，连击命中系数<span class="yellow">-8%</span>
</span></td>
<td class=b3 width=46>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
9级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
