<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill52')); $___TEMP_SKILL_ID=52; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
臂力
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php $clv=(int)\skillbase\skill_getvalue(52,'lvl'); $totlv = count($upgradecost)-1;  ?>
<span class="lime">当前级别<?php echo $clv?>/<?php echo $totlv?></span>&nbsp;
<?php if($upgradecost[$clv] !== -1) { ?>
消耗<span class="lime"><?php echo $upgradecost[$clv]?></span>点技能点升至下一级
<?php } ?>
<br>
手持投系武器时，反击率<span class="yellow">+<?php echo $counterperc[$clv]?>%</span></td>
<td class=b3 width=46>
<?php if($upgradecost[$clv] !== -1) { if($skillpoint>=$upgradecost[$clv]) { ?>
<input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill52_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { ?>
<input type="button" style="width:46px" disabled="true" value="升级">
<?php } } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
