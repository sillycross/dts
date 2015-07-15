<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>想采取什么基本姿态？<br>

<input type="hidden" name="mode" value="special">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br><br>
<?php } else { echo '___aaqj'; } ?><?php if(is_array($poseinfo)) { foreach($poseinfo as $key => $value) { if($value) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="pose<?php } else { echo '___aaqk'; } ?><?php echo $key?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" value="pose<?php } else { echo '___aaql'; } ?><?php echo $key?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><a onclick=sl('pose<?php } else { echo '___aaqm'; } ?><?php echo $key?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>'); href="javascript:void(0);" ><?php } else { echo '___aaqn'; } ?><?php echo $value?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabq'; } ?><?php } } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> 

<?php } else { echo '___aaka'; } ?>