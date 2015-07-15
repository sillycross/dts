<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL54__VARS__upgradecost,$___LOCAL_SKILL54__VARS__dmgreduction; $upgradecost=&$___LOCAL_SKILL54__VARS__upgradecost; $dmgreduction=&$___LOCAL_SKILL54__VARS__dmgreduction;   $___TEMP_SKILL_ID=54; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
圣盾
</span>
<?php } else { echo '___aaia'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php } else { echo '___aaf1'; } ?><?php $clv=(int)\skillbase\skill_getvalue(54,'lvl'); $totlv = count($upgradecost)-1;  ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>你受到的所有属性伤害<span class="yellow">-<?php } else { echo '___aaib'; } ?><?php echo $dmgreduction[$clv]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>%</span><br>
<?php } else { echo '___aaic'; } ?><?php if($upgradecost[$clv] !== -1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>可花费<span class="lime"><?php } else { echo '___aaid'; } ?><?php echo $upgradecost[$clv]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点技能升级，升级后减伤提高至<span class="yellow">-<?php } else { echo '___aaie'; } ?><?php echo $dmgreduction['1']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>%</span>
<?php } else { echo '___aaif'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
</td>
<td class=b3 width=46>
<?php } else { echo '___aaig'; } ?><?php if($upgradecost[$clv] !== -1) { if($skillpoint>=$upgradecost[$clv]) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill54_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { echo '___aaih'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" disabled="true" value="升级">
<?php } else { echo '___aaf.'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<?php } else { echo '___aaaX'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
3级时解锁
</span>
<?php } else { echo '___aagf'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

