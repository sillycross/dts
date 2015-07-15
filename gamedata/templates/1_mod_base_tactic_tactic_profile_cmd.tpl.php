<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('tactic')); ?>
<td class="b2"><span>应战策略</span></td>
<td class="b3">
<span>
<select id="tactic" name="tactic" onchange="$('mode').value='special';$('command').value=$('tactic').value;postCmd('gamecmd','command.php');return false;" 
<?php if(CURSCRIPT != 'game' || $mode != 'command') { ?>
disabled
<?php } ?>
>
<?php if(is_array($tacinfo)) { foreach($tacinfo as $key => $value) { if(($tactic_player_usable[$key] && $value!='')) { ?>
<option value="tac<?php echo $key?>"
<?php if($tactic == $key) { ?>
selected
<?php } ?>
><?php echo $value?>
<?php } } } ?>
</select>
</span>
</td>