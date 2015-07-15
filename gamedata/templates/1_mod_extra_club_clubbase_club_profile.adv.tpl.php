<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_CLUBBASE__VARS__max_club_choice_num,$___LOCAL_CLUBBASE__VARS__clublist,$___LOCAL_CLUBBASE__VARS__clubinfo,$___LOCAL_CLUBBASE__VARS__clubskillname; $max_club_choice_num=&$___LOCAL_CLUBBASE__VARS__max_club_choice_num; $clublist=&$___LOCAL_CLUBBASE__VARS__clublist; $clubinfo=&$___LOCAL_CLUBBASE__VARS__clubinfo; $clubskillname=&$___LOCAL_CLUBBASE__VARS__clubskillname;   ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b2"><span>内定称号</span></td>
<td class="b3"><span>
<?php } else { echo '___aaer'; } ?><?php if($club!=0) { ?>
<?php echo $clubinfo[$club]?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><select id="clubsel" name="clubsel" onchange="$('mode').value='special';$('command').value=$('clubsel').value;replay_record_DOM_path(this.options[this.selectedIndex]);postCmd('gamecmd','command.php');return false;" 
<?php } else { echo '___aaes'; } ?><?php if(CURSCRIPT != 'game' || $mode != 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled
<?php } else { echo '___aaaJ'; } ?><?php } ?>
>
<?php if(is_array(\clubbase\get_club_choice_array())) { foreach(\clubbase\get_club_choice_array() as $key => $value) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="clubsel<?php } else { echo '___aaet'; } ?><?php echo $key?>"
<?php if($club == $key) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected
<?php } else { echo '___aaeu'; } ?><?php } ?>
><?php echo $clubinfo[$value]?>
<?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<?php } else { echo '___aaev'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<?php } else { echo '___aaew'; } ?>