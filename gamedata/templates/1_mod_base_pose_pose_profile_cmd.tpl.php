<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('pose')); ?>
<td class="b2"><span>基础姿态</span></td>
<td class="b3">
<span>
<select id="pose" name="pose" onchange="$('mode').value='special';$('command').value=$('pose').value;postCmd('gamecmd','command.php');return false;" 
<?php if(CURSCRIPT != 'game' || $mode != 'command') { ?>
disabled
<?php } ?>
>
<?php if(is_array($poseinfo)) { foreach($poseinfo as $key => $value) { if(($value)&&($pose_player_usable[$key])) { ?>
<option value="pose<?php echo $key?>"
<?php if($pose == $key) { ?>
selected
<?php } ?>
><?php echo $value?>
<?php } } } ?>
</select>
</span>
</td>