<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill53')); $___TEMP_SKILL_ID=53; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
天才
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php if(\skillbase\skill_getvalue(53,'l')=='0') { ?>
学习任意一个非战斗非限定技能
<?php \bubblebox\bubblebox_set_style('id:learntable;height:400;width:655;cancellable:1;margin-right:8;'); include template('MOD_BUBBLEBOX_START'); ?>
<p style="margin-left:0px; margin-top:0px; margin-right:0px; margin-bottom:0px;">
以下是你可以学习的称号技能列表。战斗技和限定技不可以被学习，因此没有显示。<br>
<span class="yellow">请注意使用“查看”按钮查看技能时，技能的解锁条件没有显示。</span>请阅读帮助以获得完整的技能解锁要求。<br>
<br>
</p>
<?php \sklearn_util\get_skilllearn_table('\\skill53\\sklearn_checker53'); include template('MOD_BUBBLEBOX_END'); } else { $sk53_learnt=$clubskillname[(int)\skillbase\skill_getvalue(53,'l')]; ?>
你已经学习了技能“<span class="yellow"><?php echo $sk53_learnt?></span>”
<?php } ?>
</span></td>
<td class=b3 width=46>
<?php if(\skillbase\skill_getvalue(53,'l')=='0') { ?>
<input type="button" style="width:46px" onclick="bubblebox_show('learntable');" value="学习">
<?php } else { ?>
<input type="button" style="width:46px" disabled="true" value="学习">
<?php } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
11级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
