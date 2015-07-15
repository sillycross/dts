<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="banlist" onsubmit="admin.php">
<input type="hidden" name="mode" value="banlistmng">
<input type="hidden" name="command" id="command" value="">
<table class="admin">
<tr>
<th colspan="2">屏蔽列表管理</th>
</tr>
<tr>
<td colspan="2">输入要屏蔽的用户名和IP段的正则表达式。修改之前请弄明白你正在做什么。</td>
</tr>
<tr>
<th>用户名屏蔽</th>
<th>IP段屏蔽</th>
</tr>
<tr>
<td><textarea name="postnmlmt" style="width:400;height:200"><?php } else { echo '___aakH'; } ?><?php echo $nmlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></textarea></td>
<td><textarea name="postiplmt" style="width:400;height:200"><?php } else { echo '___aakI'; } ?><?php echo $iplimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></textarea></td>
</tr>
</table>
<input type="submit" value="提交" onclick="$('command').value='write'">
</form> <?php } else { echo '___aakJ'; } ?>