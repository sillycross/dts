<?php if(!defined('IN_GAME')) exit('Access Denied'); __MODULE_NULLFUNCTION__();  global $___LOCAL_ITEMMAIN__VARS__item_equip_list,$___LOCAL_ITEMMAIN__VARS__nosta,$___LOCAL_ITEMMAIN__VARS__nospk,$___LOCAL_ITEMMAIN__VARS__item_obbs,$___LOCAL_ITEMMAIN__VARS__map_noitemdrop_arealist,$___LOCAL_ITEMMAIN__VARS__iteminfo,$___LOCAL_ITEMMAIN__VARS__itemspkinfo; $item_equip_list=&$___LOCAL_ITEMMAIN__VARS__item_equip_list; $nosta=&$___LOCAL_ITEMMAIN__VARS__nosta; $nospk=&$___LOCAL_ITEMMAIN__VARS__nospk; $item_obbs=&$___LOCAL_ITEMMAIN__VARS__item_obbs; $map_noitemdrop_arealist=&$___LOCAL_ITEMMAIN__VARS__map_noitemdrop_arealist; $iteminfo=&$___LOCAL_ITEMMAIN__VARS__iteminfo; $itemspkinfo=&$___LOCAL_ITEMMAIN__VARS__itemspkinfo;   $___TEMP_SKILL_ID=34; $nchoice = \skillbase\skill_getvalue(34,'choice');  include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
百战
</span>
<?php } else { echo '___aaiX'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
选择任一单体减半防御属性，你视为具有它。<br>
当前选择是：<span class="yellow">
<?php } else { echo '___aaiY'; } ?><?php if($nchoice!='') { ?>
<?php echo $itemspkinfo[$nchoice]?>
<?php } else { ?>
无
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
&nbsp;
变更选择为：
<select name="skill34_choice" id="skill34_choice" onchange="$('mode').value='special';$('command').value='skill34_special';$('subcmd').value='upgrade';$('skillpara1').value=$('skill34_choice').value;postCmd('gamecmd','command.php');this.disabled=true;">
<?php } else { echo '___aaiZ'; } ?><?php if($nchoice=='') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="" selected>无<br />
<?php } else { echo '___aai0'; } ?><?php } if(is_array(\skill34\get_avaliable_attr())) { foreach(\skill34\get_avaliable_attr() as $key) { if($key!=$nchoice) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="<?php } else { echo '___aai1'; } ?><?php echo $key?>"><?php echo $itemspkinfo[$key]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="<?php } else { echo '___aai1'; } ?><?php echo $key?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" selected><?php } else { echo '___aai2'; } ?><?php echo $itemspkinfo[$key]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php } } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
&nbsp;
可随时改变
<br>
</span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aai3'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
在“格挡”和“猛击”上共计花费至少15技能点以解锁<br>
<?php } else { echo '___aai4'; } ?><?php $skill34_cprogress=\skill34\get_unlock34_progress($sdata); if($skill34_cprogress>0) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="clan">当前进度<?php } else { echo '___aai5'; } ?><?php echo $skill34_cprogress?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>/15</span>
<?php } else { echo '___aai6'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

