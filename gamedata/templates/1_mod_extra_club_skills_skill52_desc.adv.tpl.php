<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL52__VARS__upgradecost,$___LOCAL_SKILL52__VARS__counterperc; $upgradecost=&$___LOCAL_SKILL52__VARS__upgradecost; $counterperc=&$___LOCAL_SKILL52__VARS__counterperc;   $___TEMP_SKILL_ID=52; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
臂力
</span>
<?php } else { echo '___aagr'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php } else { echo '___aaf1'; } ?><?php $clv=(int)\skillbase\skill_getvalue(52,'lvl'); $totlv = count($upgradecost)-1;  ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="lime">当前级别<?php } else { echo '___aaf2'; } ?><?php echo $clv?>/<?php echo $totlv?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>&nbsp;
<?php } else { echo '___aaf3'; } ?><?php if($upgradecost[$clv] !== -1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>消耗<span class="lime"><?php } else { echo '___aaf4'; } ?><?php echo $upgradecost[$clv]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点技能点升至下一级
<?php } else { echo '___aaf5'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>
手持投系武器时，反击率<span class="yellow">+<?php } else { echo '___aags'; } ?><?php echo $counterperc[$clv]?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>%</span></td>
<td class=b3 width=46>
<?php } else { echo '___aagt'; } ?><?php if($upgradecost[$clv] !== -1) { if($skillpoint>=$upgradecost[$clv]) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill52_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } else { echo '___aagu'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" disabled="true" value="升级">
<?php } else { echo '___aaf.'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<?php } else { echo '___aaaX'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
</span>
<?php } else { echo '___aaaY'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

