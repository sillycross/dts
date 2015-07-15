<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill34')); eval(import_module('itemmain')); $___TEMP_SKILL_ID=34; $nchoice = \skillbase\skill_getvalue(34,'choice');  include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
百战
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
选择任一单体减半防御属性，你视为具有它。<br>
当前选择是：<span class="yellow">
<?php if($nchoice!='') { ?>
<?php echo $itemspkinfo[$nchoice]?>
<?php } else { ?>
无
<?php } ?>
</span>
&nbsp;
变更选择为：
<select name="skill34_choice" id="skill34_choice" onchange="$('mode').value='special';$('command').value='skill34_special';$('subcmd').value='upgrade';$('skillpara1').value=$('skill34_choice').value;postCmd('gamecmd','command.php');this.disabled=true;">
<?php if($nchoice=='') { ?>
<option value="" selected>无<br />
<?php } if(is_array(\skill34\get_avaliable_attr())) { foreach(\skill34\get_avaliable_attr() as $key) { if($key!=$nchoice) { ?>
<option value="<?php echo $key?>"><?php echo $itemspkinfo[$key]?><br />
<?php } else { ?>
<option value="<?php echo $key?>" selected><?php echo $itemspkinfo[$key]?><br />
<?php } } } ?>
</select>
&nbsp;
可随时改变
<br>
</span></td>
<td class=b3 width=46>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
在“格挡”和“猛击”上共计花费至少15技能点以解锁<br>
<?php $skill34_cprogress=\skill34\get_unlock34_progress($sdata); if($skill34_cprogress>0) { ?>
<span class="clan">当前进度<?php echo $skill34_cprogress?>/15</span>
<?php } ?>
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
