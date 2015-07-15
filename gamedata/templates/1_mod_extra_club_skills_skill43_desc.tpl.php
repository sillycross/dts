<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill43')); eval(import_module('itemmain')); $___TEMP_SKILL_ID=43; $nchoice = \skillbase\skill_getvalue(43,'choice');  include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
龙胆
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
获得技能“<span class="yellow">神速</span>”或“<span class="yellow">恃勇</span>”。<br>
<input type="button" onclick="$('mode').value='special';$('command').value='skill43_special';$('subcmd').value='upgrade';$('skillpara1').value=1;postCmd('gamecmd','command.php');this.disabled=true;" value="获得技能“神速”">
<input type="button" onclick="$('mode').value='special';$('command').value='skill43_special';$('subcmd').value='upgrade';$('skillpara1').value=2;postCmd('gamecmd','command.php');this.disabled=true;" value="获得技能“恃勇”">
</span></td>
<td class=b3 width=46>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
3级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
