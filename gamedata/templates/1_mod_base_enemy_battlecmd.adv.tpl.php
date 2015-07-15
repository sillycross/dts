<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>现在想要做什么？<br><br>
向对手大喊：<br><input size="30" type="text" name="message" maxlength="60"><br><br>
<input type="hidden" name="mode" value="combat">
<input type="hidden" id="command" name="command" value="back">
<?php } else { echo '___aaea'; } ?><?php if((!defined('MOD_CLUBBASE'))) { if((defined('MOD_WEAPON'))) { include template('MOD_WEAPON_ATT_METHOD'); } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br><input type="button" class="cmdbutton" name="back" value="逃跑" onclick="postCmd('gamecmd','command.php');this.disabled=true;"> 
<?php } else { echo '___aaeb'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="hidden" id="bskill" name="bskill" value="0">
<input type="hidden" id="bskillpara" name="bskillpara" value="">
<table cellSpacing=0 cellPadding=0>
<tr><td width=100px align="center" style="vertical-align:top">
<?php } else { echo '___aaec'; } ?><?php if((defined('MOD_WEAPON'))) { include template('MOD_WEAPON_ATT_METHOD'); } \clubbase\get_battle_skill_entry(3); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br><input type="button" class="cmdbutton" name="back" value="逃跑" onclick="postCmd('gamecmd','command.php');this.disabled=true;"> 
</td>
<td width=100px align="center" style="vertical-align:top">
<?php } else { echo '___aaed'; } ?><?php \clubbase\get_battle_skill_entry(1); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" style="vertical-align:top">
<?php } else { echo '___aaee'; } ?><?php \clubbase\get_battle_skill_entry(2); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr></table>
<?php } else { echo '___aaef'; } ?><?php } ?>

