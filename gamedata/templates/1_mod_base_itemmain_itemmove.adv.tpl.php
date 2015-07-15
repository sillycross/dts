<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>先选择要移位的道具，再选择要移动到的位置。<br>

<input type="hidden" name="mode" value="itemmain">
<input type="hidden" name="command" id="command" value="menu">
<br>
将：
<select name="from">
<option value="0">■ 道  具 ■
<?php } else { echo '___aaa-'; } ?><?php if($itms1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="1"><?php } else { echo '___aaba'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?>
<?php } if($itms2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="2"><?php } else { echo '___aabb'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?>
<?php } if($itms3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="3"><?php } else { echo '___aabc'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?>
<?php } if($itms4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="4"><?php } else { echo '___aabd'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?>
<?php } if($itms5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="5"><?php } else { echo '___aabe'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?>
<?php } if($itms6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="6"><?php } else { echo '___aabf'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<br>
移动至/与之交换：
<select name="to">
<option value="0">■ 位  置 ■
<?php } else { echo '___aabg'; } ?><?php if($itms1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="1">包裹1：<?php } else { echo '___aaaW'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="1">包裹1
<?php } else { echo '___aabh'; } ?><?php } if($itms2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="2">包裹2：<?php } else { echo '___aaaY'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="2">包裹2
<?php } else { echo '___aabi'; } ?><?php } if($itms3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="3">包裹3：<?php } else { echo '___aaaZ'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="3">包裹3
<?php } else { echo '___aabj'; } ?><?php } if($itms4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="4">包裹4：<?php } else { echo '___aaa0'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="4">包裹4
<?php } else { echo '___aabk'; } ?><?php } if($itms5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="5">包裹5：<?php } else { echo '___aaa1'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="5">包裹5
<?php } else { echo '___aabl'; } ?><?php } if($itms6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="6">包裹6：<?php } else { echo '___aaa2'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="6">包裹6
<?php } else { echo '___aabm'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<br>
<input type="button" class="cmdbutton" name="submit" value="提交" onclick="$('command').value='itemmove';postCmd('gamecmd','command.php');this.disabled=true;">
<input type="button" class="cmdbutton" name="submit" value="放弃" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aabn'; } ?>