<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>你想要做什么？<br>

<input type="hidden" name="mode" value="itemmain">
<input type="radio" name="command" id="menu" value="menu"><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br>
<input type="radio" name="command" id="itemmerge" value="itemmerge"><a onclick=sl('itemmerge'); href="javascript:void(0);">合并两个道具</a><br>
<input type="radio" name="command" id="itemmove" value="itemmove" checked><a onclick=sl('itemmove'); href="javascript:void(0);">移动或交换道具的位置</a><br>
<br>
请选择参与合并/移动/交换位置的两个道具：<br><br>
<input type="hidden" name="from" id="from" value="0">
<select name="merge1" id="merge1" href="javascript:void(0);">
<option style="width:220px;" value="0">■ 道具一 ■<br />
<?php } else { echo '___aaa2'; } ?><?php if($itms1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="1">包裹1：<?php } else { echo '___aaa3'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php } if($itms2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="2">包裹2：<?php } else { echo '___aaa5'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php } if($itms3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="3">包裹3：<?php } else { echo '___aaa6'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php } if($itms4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="4">包裹4：<?php } else { echo '___aaa7'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php } if($itms5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="5">包裹5：<?php } else { echo '___aaa8'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php } if($itms6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="6">包裹6：<?php } else { echo '___aaa9'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<br><br>
<input type="hidden" name="to" id="to" value="0">
<select name="merge2" id="merge2" href="javascript:void(0);">
<option style="width:220px;" value="0">■ 道具二 ■<br />
<?php } else { echo '___aaa.'; } ?><?php if($itms1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="1">包裹1：<?php } else { echo '___aaa3'; } ?><?php echo $itm1?>/<?php echo $itme1?>/<?php echo $itms1?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="1">包裹1：将道具1移动到这里
<?php } else { echo '___aaa-'; } ?><?php } if($itms2) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="2">包裹2：<?php } else { echo '___aaa5'; } ?><?php echo $itm2?>/<?php echo $itme2?>/<?php echo $itms2?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="2">包裹2：将道具1移动到这里
<?php } else { echo '___aaba'; } ?><?php } if($itms3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="3">包裹3：<?php } else { echo '___aaa6'; } ?><?php echo $itm3?>/<?php echo $itme3?>/<?php echo $itms3?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="3">包裹3：将道具1移动到这里
<?php } else { echo '___aabb'; } ?><?php } if($itms4) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="4">包裹4：<?php } else { echo '___aaa7'; } ?><?php echo $itm4?>/<?php echo $itme4?>/<?php echo $itms4?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="4">包裹4：将道具1移动到这里
<?php } else { echo '___aabc'; } ?><?php } if($itms5) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="5">包裹5：<?php } else { echo '___aaa8'; } ?><?php echo $itm5?>/<?php echo $itme5?>/<?php echo $itms5?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="5">包裹5：将道具1移动到这里
<?php } else { echo '___aabd'; } ?><?php } if($itms6) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="6">包裹6：<?php } else { echo '___aaa9'; } ?><?php echo $itm6?>/<?php echo $itme6?>/<?php echo $itms6?>
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="6">包裹6：将道具1移动到这里
<?php } else { echo '___aabe'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<br><br>
<input type="button" class="cmdbutton" name="submit" value="提交" onclick="$('from').value=$('merge1').value;$('to').value=$('merge2').value;postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aabf'; } ?>