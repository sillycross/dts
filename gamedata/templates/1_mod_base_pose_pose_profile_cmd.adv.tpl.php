<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_POSE__VARS__poseinfo,$___LOCAL_POSE__VARS__pose_player_usable,$___LOCAL_POSE__VARS__pose_itemfind_obbs,$___LOCAL_POSE__VARS__pose_meetman_obbs,$___LOCAL_POSE__VARS__pose_hide_obbs,$___LOCAL_POSE__VARS__pose_active_obbs,$___LOCAL_POSE__VARS__pose_dactive_obbs,$___LOCAL_POSE__VARS__pose_attack_modifier,$___LOCAL_POSE__VARS__pose_defend_modifier; $poseinfo=&$___LOCAL_POSE__VARS__poseinfo; $pose_player_usable=&$___LOCAL_POSE__VARS__pose_player_usable; $pose_itemfind_obbs=&$___LOCAL_POSE__VARS__pose_itemfind_obbs; $pose_meetman_obbs=&$___LOCAL_POSE__VARS__pose_meetman_obbs; $pose_hide_obbs=&$___LOCAL_POSE__VARS__pose_hide_obbs; $pose_active_obbs=&$___LOCAL_POSE__VARS__pose_active_obbs; $pose_dactive_obbs=&$___LOCAL_POSE__VARS__pose_dactive_obbs; $pose_attack_modifier=&$___LOCAL_POSE__VARS__pose_attack_modifier; $pose_defend_modifier=&$___LOCAL_POSE__VARS__pose_defend_modifier;   ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span>基础姿态</span></td>
<td class="b3">
<span>
<select id="pose" name="pose" onchange="$('mode').value='special';$('command').value=$('pose').value;postCmd('gamecmd','command.php');return false;" 
<?php } else { echo '___aahr'; } ?><?php if(CURSCRIPT != 'game' || $mode != 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled
<?php } else { echo '___aaaJ'; } ?><?php } ?>
>
<?php if(is_array($poseinfo)) { foreach($poseinfo as $key => $value) { if(($value)&&($pose_player_usable[$key])) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="pose<?php } else { echo '___aahs'; } ?><?php echo $key?>"
<?php if($pose == $key) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $value?>
<?php } } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
</span>
</td><?php } else { echo '___aae-'; } ?>