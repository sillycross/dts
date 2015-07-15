<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="button" class="cmdbutton" id="search" name="search" value="搜寻" onclick="$('command').value='search';postCmd('gamecmd','command.php');this.disabled=true;" 
<?php } else { echo '___aaaI'; } ?><?php if(array_search($pls,$arealist) <= $areanum && !$hack) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled
<?php } else { echo '___aaaJ'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>
<select id="moveto" name="moveto" onchange="$('command').value='move';replay_record_DOM_path(this.options[this.selectedIndex]);postCmd('gamecmd','command.php');this.disabled=true;">
<?php } else { echo '___aaaK'; } ?><?php include template('move'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<br /> 
<?php } else { echo '___aaaL'; } ?>