<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_TACTIC__VARS__tacinfo,$___LOCAL_TACTIC__VARS__tactic_player_usable,$___LOCAL_TACTIC__VARS__tactic_hide_obbs,$___LOCAL_TACTIC__VARS__pose_meetman_obbs,$___LOCAL_TACTIC__VARS__tactic_attack_modifier,$___LOCAL_TACTIC__VARS__tactic_defend_modifier; $tacinfo=&$___LOCAL_TACTIC__VARS__tacinfo; $tactic_player_usable=&$___LOCAL_TACTIC__VARS__tactic_player_usable; $tactic_hide_obbs=&$___LOCAL_TACTIC__VARS__tactic_hide_obbs; $pose_meetman_obbs=&$___LOCAL_TACTIC__VARS__pose_meetman_obbs; $tactic_attack_modifier=&$___LOCAL_TACTIC__VARS__tactic_attack_modifier; $tactic_defend_modifier=&$___LOCAL_TACTIC__VARS__tactic_defend_modifier;   ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span>应战策略</span></td>
<td class="b3">
<span>
<select id="tactic" name="tactic" onchange="$('mode').value='special';$('command').value=$('tactic').value;postCmd('gamecmd','command.php');return false;" 
<?php } else { echo '___aae9'; } ?><?php if(CURSCRIPT != 'game' || $mode != 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled
<?php } else { echo '___aaaJ'; } ?><?php } ?>
>
<?php if(is_array($tacinfo)) { foreach($tacinfo as $key => $value) { if(($tactic_player_usable[$key] && $value!='')) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="tac<?php } else { echo '___aae.'; } ?><?php echo $key?>"
<?php if($tactic == $key) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $value?>
<?php } } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
</span>
</td><?php } else { echo '___aae-'; } ?>