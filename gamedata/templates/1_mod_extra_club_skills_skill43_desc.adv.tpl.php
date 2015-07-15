<?php if(!defined('IN_GAME')) exit('Access Denied'); __MODULE_NULLFUNCTION__();  global $___LOCAL_ITEMMAIN__VARS__item_equip_list,$___LOCAL_ITEMMAIN__VARS__nosta,$___LOCAL_ITEMMAIN__VARS__nospk,$___LOCAL_ITEMMAIN__VARS__item_obbs,$___LOCAL_ITEMMAIN__VARS__map_noitemdrop_arealist,$___LOCAL_ITEMMAIN__VARS__iteminfo,$___LOCAL_ITEMMAIN__VARS__itemspkinfo; $item_equip_list=&$___LOCAL_ITEMMAIN__VARS__item_equip_list; $nosta=&$___LOCAL_ITEMMAIN__VARS__nosta; $nospk=&$___LOCAL_ITEMMAIN__VARS__nospk; $item_obbs=&$___LOCAL_ITEMMAIN__VARS__item_obbs; $map_noitemdrop_arealist=&$___LOCAL_ITEMMAIN__VARS__map_noitemdrop_arealist; $iteminfo=&$___LOCAL_ITEMMAIN__VARS__iteminfo; $itemspkinfo=&$___LOCAL_ITEMMAIN__VARS__itemspkinfo;   $___TEMP_SKILL_ID=43; $nchoice = \skillbase\skill_getvalue(43,'choice');  include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
龙胆
</span>
<?php } else { echo '___aajo'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
获得技能“<span class="yellow">神速</span>”或“<span class="yellow">恃勇</span>”。<br>
<input type="button" onclick="$('mode').value='special';$('command').value='skill43_special';$('subcmd').value='upgrade';$('skillpara1').value=1;postCmd('gamecmd','command.php');this.disabled=true;" value="获得技能“神速”">
<input type="button" onclick="$('mode').value='special';$('command').value='skill43_special';$('subcmd').value='upgrade';$('skillpara1').value=2;postCmd('gamecmd','command.php');this.disabled=true;" value="获得技能“恃勇”">
</span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aajp'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
3级时解锁
</span>
<?php } else { echo '___aaf.'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

