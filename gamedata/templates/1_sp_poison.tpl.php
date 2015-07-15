<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>想检查什么？<br>

<input type="hidden" name="mode" value="special">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br><br>
<?php } else { echo '___aaqj'; } ?><?php if($itms1 && ((strpos($itmk1,'P') === 0) || (strpos($itmk1,'H') === 0))) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="chkp1" value="chkp1"><a onclick=sl('chkp1'); href="javascript:void(0);" ><?php } else { echo '___aaqk'; } ?><?php echo $itm1?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabx'; } ?><?php } if($itms2 && ((strpos($itmk2,'P') === 0) || (strpos($itmk2,'H') === 0))) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="chkp2" value="chkp2"><a onclick=sl('chkp2'); href="javascript:void(0);" ><?php } else { echo '___aaql'; } ?><?php echo $itm2?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabx'; } ?><?php } if($itms3 && ((strpos($itmk3,'P') === 0) || (strpos($itmk3,'H') === 0))) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="chkp3" value="chkp3"><a onclick=sl('chkp3'); href="javascript:void(0);" ><?php } else { echo '___aaqm'; } ?><?php echo $itm3?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabx'; } ?><?php } if($itms4 && ((strpos($itmk4,'P') === 0) || (strpos($itmk4,'H') === 0))) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="chkp4" value="chkp4"><a onclick=sl('chkp4'); href="javascript:void(0);" ><?php } else { echo '___aaqn'; } ?><?php echo $itm4?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabx'; } ?><?php } if($itms5 && ((strpos($itmk5,'P') === 0) || (strpos($itmk5,'H') === 0))) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="chkp5" value="chkp5"><a onclick=sl('chkp5'); href="javascript:void(0);" ><?php } else { echo '___aaqo'; } ?><?php echo $itm5?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabx'; } ?><?php } if($itms6 && ((strpos($itmk6,'P') === 0) || (strpos($itmk6,'H') === 0))) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="chkp6" value="chkp6"><a onclick=sl('chkp6'); href="javascript:void(0);" ><?php } else { echo '___aaqp'; } ?><?php echo $itm6?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> </a><br>
<?php } else { echo '___aabx'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br><br>

<input type="button" class="cmdbutton" name="submit" value="提交" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aaqq'; } ?>