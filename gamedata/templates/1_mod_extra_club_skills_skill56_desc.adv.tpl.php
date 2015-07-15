<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL56__VARS__skill56_need,$___LOCAL_SKILL56__VARS__skill56_lim,$___LOCAL_SKILL56__VARS__skill56_npc; $skill56_need=&$___LOCAL_SKILL56__VARS__skill56_need; $skill56_lim=&$___LOCAL_SKILL56__VARS__skill56_lim; $skill56_npc=&$___LOCAL_SKILL56__VARS__skill56_npc;   $___TEMP_SKILL_ID=56; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
保镖
</span>
<?php } else { echo '___aajx'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php } else { echo '___aaf1'; } ?><?php $skill56_cnt=$skill56_lim-(int)\skillbase\skill_getvalue(56,'t'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red">限定技&nbsp;本局还可发动<span><?php } else { echo '___aajy'; } ?><?php echo $skill56_cnt?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/<span><?php } else { echo '___aajz'; } ?><?php echo $skill56_lim?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>次</span><br>    
花费<span class="yellow"><?php } else { echo '___aajA'; } ?><?php echo $skill56_need?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>元，在当前地点随机召唤一名佣兵；<br>
雇佣关系存在时，你的佣兵不会与你进入战斗界面；<br>雇佣佣兵后需每分钟耗费一定数量的金钱以维持雇佣关系；<br>
<input type="button" onclick="bubblebox_show('skill56_merc')" value="点我查看可能召唤出的佣兵列表">
<?php } else { echo '___aajB'; } ?><?php \bubblebox\bubblebox_set_style('id:skill56_merc;height:560;width:772;cancellable:1;margin-top:20px;margin-bottom:20px;margin-left:20px;margin-right:10px;'); include template('MOD_BUBBLEBOX_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><p style="margin-left:0px; margin-top:0px; margin-right:0px; margin-bottom:0px;">
以下是所有可能被召唤出的佣兵的列表：<br><br>
</p>
<?php } else { echo '___aajC'; } ?><?php \npcinfo\npcinfo_get_npc_description_all(25); include template('MOD_BUBBLEBOX_END'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aah6'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
15级时解锁
</span>
<?php } else { echo '___aagm'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

