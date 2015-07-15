<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('rest')); ?>
<input type="button" class="cmdbutton" id="rest1" name="rest1" value="睡眠" onclick="$('command').value='rest1';postCmd('gamecmd','command.php');this.disabled=true;">
<input type="button" class="cmdbutton" id="rest2" name="rest2" value="治疗" onclick="$('command').value='rest2';postCmd('gamecmd','command.php');this.disabled=true;">
<?php if(in_array($pls,$rest_hospital_list)) { ?>
<input type="button" class="cmdbutton" id="rest3" name="rest3" value="静养" onclick="$('command').value='rest3';postCmd('gamecmd','command.php');this.disabled=true;">
<?php } ?>
<br />
