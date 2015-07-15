<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill32')); $___TEMP_SKILL_ID=32; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
格挡
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php $clv = (int)\skillbase\skill_getvalue(32,'lvl');  $totlv = count($upgradecost)-1;  ?>
<span class="lime">当前级别<?php echo $clv?>/<?php echo $totlv?></span>&nbsp;
<?php if($upgradecost[$clv] !== -1) { ?>
消耗<span class="lime"><?php echo $upgradecost[$clv]?></span>点技能点升至下一级
<?php } ?>
<br>
持殴系武器时，武器效果值的<span class="yellow"><?php echo $defgain[$clv]?>%</span>计入防御力(最多2000点)<br>
</span></td>
<td class=b3 width=46>
<?php if($upgradecost[$clv] !== -1) { if($skillpoint>=$upgradecost[$clv]) { ?>
<input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill32_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { ?>
<input type="button" style="width:46px" disabled="true" value="升级">
<?php } } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
