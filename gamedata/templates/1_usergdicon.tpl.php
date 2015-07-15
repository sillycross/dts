<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table align="center">
<tr>
<td>性别</td>
<td>
<input type="radio" id="male" name="gender" onclick="userIconMover()" value="m" 
<?php } else { echo '___aanV'; } ?><?php if($gender != "f") { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked
<?php } else { echo '___aadU'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> ><?php } else { echo '___aanM'; } ?><?php echo $sexinfo['m']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<input type="radio" name="gender" onclick="userIconMover()" value="f" 
<?php } else { echo '___aanN'; } ?><?php if($gender == "f") { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked
<?php } else { echo '___aadU'; } ?><?php } ?>
><?php echo $sexinfo['f']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td> </td>
</tr>
<tr>
<td>头像</td>
<td>
<select id="icon" name="icon" onchange="userIconMover()">
<?php } else { echo '___aanW'; } ?><?php if(is_array($iconarray)) { foreach($iconarray as $icon) { ?>
<?php echo $icon?>
<?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>（0为随机）
</td>
<td>
<div id="userIconImg" class="iconImg" >
<img src="img/
<?php } else { echo '___aanX'; } ?><?php if($gender != 'f') { ?>
m
<?php } else { ?>
f
<?php } ?>
_<?php echo $select_icon?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>.gif" alt="<?php } else { echo '___aanQ'; } ?><?php echo $select_icon?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
</div>
</td>
</tr>
</table><?php } else { echo '___aanR'; } ?>