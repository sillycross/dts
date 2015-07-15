<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_TACTIC__VARS__tacinfo,$___LOCAL_TACTIC__VARS__tactic_player_usable,$___LOCAL_TACTIC__VARS__tactic_hide_obbs,$___LOCAL_TACTIC__VARS__pose_meetman_obbs,$___LOCAL_TACTIC__VARS__tactic_attack_modifier,$___LOCAL_TACTIC__VARS__tactic_defend_modifier; $tacinfo=&$___LOCAL_TACTIC__VARS__tacinfo; $tactic_player_usable=&$___LOCAL_TACTIC__VARS__tactic_player_usable; $tactic_hide_obbs=&$___LOCAL_TACTIC__VARS__tactic_hide_obbs; $pose_meetman_obbs=&$___LOCAL_TACTIC__VARS__pose_meetman_obbs; $tactic_attack_modifier=&$___LOCAL_TACTIC__VARS__tactic_attack_modifier; $tactic_defend_modifier=&$___LOCAL_TACTIC__VARS__tactic_defend_modifier;   ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2" height=20px><span>应战策略</span></td>
<td class="b3"><span>
<?php } else { echo '___aae6'; } ?><?php if((isset($tacinfo[$tdata['tactic']]))) { ?>
<?php echo $tacinfo[$tdata['tactic']]?>
<?php } else { ?>
<?php echo $tdata['tactic']?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<?php } else { echo '___aaew'; } ?>