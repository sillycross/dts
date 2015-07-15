<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=10; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
生命
</span>
<?php } else { echo '___aaaZ'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
消耗<span class="lime">1</span>技能点，
生命上限<span class="yellow">+2</span>点
</span></td>
<td class=b3 width=46>
<?php } else { echo '___aaa0'; } ?><?php if($skillpoint >= 1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill10_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="使用">
<?php } else { echo '___aaa1'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" disabled="true" value="使用">
<?php } else { echo '___aaaW'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<?php } else { echo '___aaaX'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
</span>
<?php } else { echo '___aaaY'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

