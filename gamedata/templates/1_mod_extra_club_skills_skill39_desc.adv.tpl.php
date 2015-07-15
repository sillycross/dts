<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=39; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
攻防
</span>
<?php } else { echo '___aaaM'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
消耗<span class="lime">1</span>技能点，基础攻击<span class="yellow">+7</span>点，基础防御<span class="yellow">+11</span>点
</span></td>
<td class=b3 width=46>
<?php } else { echo '___aacl'; } ?><?php if($skillpoint >= 1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill39_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="使用">
<?php } else { echo '___aacm'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" style="width:46px" disabled="true" value="使用">
<?php } else { echo '___aaaP'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<?php } else { echo '___aaaQ'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
</span>
<?php } else { echo '___aaaR'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

