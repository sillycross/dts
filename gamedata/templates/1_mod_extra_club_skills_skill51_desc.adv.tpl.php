<?php if(!defined('IN_GAME')) exit('Access Denied'); __MODULE_NULLFUNCTION__();  $___TEMP_SKILL_ID=51; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
百出
</span>
<?php } else { echo '___aajh'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<!--由于这个技能未解锁提示比描述本身还长，只好在没解锁时候多放两个空行撑开距离了……-->
<?php } else { echo '___aaji'; } ?><?php if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span style="height:8px; display:block;">&nbsp;</span>
<?php } else { echo '___aajj'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>持投系武器时物理伤害<span class="yellow">+2x%</span>，其中<span class="yellow">x</span>是你发动“<span class="yellow">附魔</span>”的次数<br>
<?php } else { echo '___aajk'; } ?><?php if($___TEMP_THIS_SKILL_ACQUIRED) { $sk51cadd=\skill51\get_skill51_multiplier($sdata); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>当前情况：持投系武器时，你造成的物理伤害将<span class="yellow">+<?php } else { echo '___aajl'; } ?><?php echo $sk51cadd?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>%</span>
<?php } else { echo '___aaif'; } ?><?php } if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span style="height:8px; display:block;">&nbsp;</span>
<?php } else { echo '___aajj'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aah6'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
<?php } else { echo '___aagb'; } ?><?php $sk51progress=\skill51\get_skill48_maxbuff($sdata); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>“附魔”中最高的属性伤害加成达到120%时解锁<br>
<span class="clan">当前进度<?php } else { echo '___aajm'; } ?><?php echo $sk51progress?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>/120</span>
</span>
<?php } else { echo '___aajn'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

