<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=12; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
治疗
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
消耗<span class="lime">1</span>技能点，解除全部受伤与异常状态
</span></td>
<td class=b3 width=46>
<?php if($skillpoint >= 1) { ?>
<input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill12_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="治疗">
<?php } else { ?>
<input type="button" style="width:46px" disabled="true" value="治疗">
<?php } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
