<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('clubbase')); ?>
<td class="b2"><span>内定称号</span></td>
<td class="b3"><span>
<?php if($club!=0) { ?>
<?php echo $clubinfo[$club]?>
<?php } else { ?>
<select id="clubsel" name="clubsel" onchange="$('mode').value='special';$('command').value=$('clubsel').value;replay_record_DOM_path(this.options[this.selectedIndex]);postCmd('gamecmd','command.php');return false;" 
<?php if(CURSCRIPT != 'game' || $mode != 'command') { ?>
disabled
<?php } ?>
>
<?php if(is_array(\clubbase\get_club_choice_array())) { foreach(\clubbase\get_club_choice_array() as $key => $value) { ?>
<option value="clubsel<?php echo $key?>"
<?php if($club == $key) { ?>
selected
<?php } ?>
><?php echo $clubinfo[$value]?>
<?php } } ?>
</select>
<?php } ?>
</span></td>
