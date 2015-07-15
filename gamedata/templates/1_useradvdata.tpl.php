<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>账户个性化资料</span>
<table>
<tr>
<td>性别</td>
<td>
<input type="radio" id="male" name="gender" onclick="userIconMover()" value="m" 
<?php } else { echo '___aanE'; } ?><?php if($gender != "f") { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked
<?php } else { echo '___aadN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> ><?php } else { echo '___aanF'; } ?><?php echo $sexinfo['m']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<input type="radio" name="gender" onclick="userIconMover()" value="f" 
<?php } else { echo '___aanG'; } ?><?php if($gender == "f") { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked
<?php } else { echo '___aadN'; } ?><?php } ?>
><?php echo $sexinfo['f']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td> </td>
</tr>
<tr>
<td>头像</td>
<td><select id="icon" name="icon" onchange="userIconMover()">
<?php } else { echo '___aanH'; } ?><?php if(is_array($iconarray)) { foreach($iconarray as $icon) { ?>
<?php echo $icon?>
<?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>（0为随机）</td>
<td>
<div id="userIconImg" class="iconImg" >
<img src="img/
<?php } else { echo '___aanI'; } ?><?php if($gender != 'f') { ?>
m
<?php } else { ?>
f
<?php } ?>
_<?php echo $select_icon?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>.gif" alt="<?php } else { echo '___aanJ'; } ?><?php echo $select_icon?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
</div>
</td>
</tr>
</table><?php } else { echo '___aanK'; } ?>