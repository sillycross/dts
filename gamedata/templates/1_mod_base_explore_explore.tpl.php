<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<input type="button" class="cmdbutton" id="search" name="search" value="搜寻" onclick="$('command').value='search';postCmd('gamecmd','command.php');this.disabled=true;" 
<?php if(array_search($pls,$arealist) <= $areanum && !$hack) { ?>
disabled
<?php } ?>
>
<select id="moveto" name="moveto" onchange="$('command').value='move';replay_record_DOM_path(this.options[this.selectedIndex]);postCmd('gamecmd','command.php');this.disabled=true;">
<?php include template('move'); ?>
</select>
<br /> 
