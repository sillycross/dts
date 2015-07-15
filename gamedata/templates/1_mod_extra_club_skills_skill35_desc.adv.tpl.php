<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL35__VARS__attgain,$___LOCAL_SKILL35__VARS__upgradecost,$___LOCAL_SKILL35__VARS__proc_rate; $attgain=&$___LOCAL_SKILL35__VARS__attgain; $upgradecost=&$___LOCAL_SKILL35__VARS__upgradecost; $proc_rate=&$___LOCAL_SKILL35__VARS__proc_rate;   $___TEMP_SKILL_ID=35; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
猛击
</span>
<?php } else { echo '___aafT'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php } else { echo '___aafU'; } ?><?php $clv = (int)\skillbase\skill_getvalue(35,'lvl');  $totlv = count($upgradecost)-1;  ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="lime">当前级别<?php } else { echo '___aafV'; } ?><?php echo $clv?>/<?php echo $totlv?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>&nbsp;
<?php } else { echo '___aafW'; } ?><?php if($upgradecost[$clv] !== -1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>消耗<span class="lime"><?php } else { echo '___aafX'; } ?><?php echo $upgradecost[$clv]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点技能点升至下一级
<?php } else { echo '___aafY'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
持殴系武器战斗时<span class="yellow"><?php } else { echo '___aafZ'; } ?><?php echo $proc_rate?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>%</span>几率触发，触发则物理伤害增加<span class="yellow"><?php } else { echo '___aaf0'; } ?><?php echo $attgain[$clv]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>%</span><br>
</span></td>
<td class=b3 width=46>
<?php } else { echo '___aaf1'; } ?><?php if($upgradecost[$clv] !== -1) { if($skillpoint>=$upgradecost[$clv]) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill35_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { echo '___aaf2'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" disabled="true" value="升级">
<?php } else { echo '___aaf3'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<?php } else { echo '___aaaQ'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
</span>
<?php } else { echo '___aaaR'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

