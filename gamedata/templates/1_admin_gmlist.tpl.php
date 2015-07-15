<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="gmlist" onsubmit="admin.php">
<input type="hidden" id="mode" name="mode" value="gmlist">
<input type="hidden" id="command" name="command" value="">
<input type="hidden" id="adminuid" name="adminuid" value="">
<table class="admin">
<tr>
<th>UID</th>
<th width="250px">账号</th>
<th>权限</th>
<th>操作</th>
</tr>
<?php } else { echo '___aat2'; } ?><?php if(is_array($gmdata)) { foreach($gmdata as $n => $gm) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td><?php } else { echo '___aat3'; } ?><?php echo $gm['uid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $gm['username']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>    
<td>
<?php } else { echo '___aat4'; } ?><?php if($gm['groupid'] >= $mygroup) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red"><?php } else { echo '___aat5'; } ?><?php echo $gm['groupid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacU'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><select name="<?php } else { echo '___aat6'; } ?><?php echo $gm['uid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>_group">
<?php } else { echo '___aat7'; } ?><?php if(is_array(Array(2,3,4,5,6,7,8,9))) { foreach(Array(2,3,4,5,6,7,8,9) as $i) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="<?php } else { echo '___aai1'; } ?><?php echo $i?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" 
<?php } else { echo '___aatB'; } ?><?php if($gm['groupid'] == $i) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>selected="true"
<?php } else { echo '___aat8'; } ?><?php } ?>
><?php echo $i?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></option>
<?php } else { echo '___aat9'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
<?php } else { echo '___aaeC'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>        
<td>
<input type="submit" value="编辑" 
<?php } else { echo '___aat.'; } ?><?php if($gm['groupid'] >= $mygroup) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarO'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="$('command').value='edit';$('adminuid').value='<?php } else { echo '___aat-'; } ?><?php echo $gm['uid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';">
<input type="submit" value="删除" 
<?php } else { echo '___aaua'; } ?><?php if($gm['groupid'] >= $mygroup) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarO'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="$('command').value='del';$('adminuid').value='<?php } else { echo '___aaub'; } ?><?php echo $gm['uid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>';">
</td>
</tr>
<?php } else { echo '___aauc'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td>新增</td>
<td><input type="text" name="addname" value="" size="30" maxlength="15"></td>    
<td>
<select name="addgroup">
<option value="2" selected="true">2</option>
<?php } else { echo '___aaud'; } ?><?php if(is_array(Array(3,4,5,6,7,8,9))) { foreach(Array(3,4,5,6,7,8,9) as $i) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="<?php } else { echo '___aai1'; } ?><?php echo $i?>"><?php echo $i?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></option>
<?php } else { echo '___aat9'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
</td>
<td><input type="submit" value="新增" onclick="$('command').value='add'"></td>
</tr>
</table>
</form>
<?php } else { echo '___aaue'; } ?>