<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill44')); $___TEMP_SKILL_ID=44; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
金刚
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php $clv=(int)\skillbase\skill_getvalue(44,'lvl'); $choice=(int)\skillbase\skill_getvalue(44,'choice'); $totlv = count($upgradecost)-1;  $redperc = min(50,$dmgreduction[$clv]*$def); ?>
<span class="lime">当前级别<?php echo $clv?>/<?php echo $totlv?></span>&nbsp;
<?php if($upgradecost[$clv] !== -1) { ?>
消耗<span class="lime"><?php echo $upgradecost[$clv]?></span>点技能点升至下一级
<?php } ?>
<br>
每一点基础防御降低<span class="yellow"><?php echo $dmgreduction[$clv]?>%</span>固定伤害或爆炸伤害(最高50%)<br>
当前选择：降低你受到的<span class="yellow">
<?php if($choice==0) { ?>
固定伤害
<?php } else { ?>
爆炸伤害
<?php } ?>
</span> 
<input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill44_special';$('subcmd').value='upgrade';$('skillpara1').value=2;postCmd('gamecmd','command.php');this.disabled=true;" value="切换">
</span></td>
<td class=b3 width=46>
<?php if($upgradecost[$clv] !== -1) { if($skillpoint>=$upgradecost[$clv]) { ?>
<input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill44_special';$('subcmd').value='upgrade';$('skillpara1').value=1;postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { ?>
<input type="button" style="width:46px" disabled="true" value="升级">
<?php } } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
3级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
