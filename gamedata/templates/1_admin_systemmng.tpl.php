<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="systemmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="systemmng">
<input type="hidden" id="command" name="command" value="">    
<table class="admin">
<tr>
<th><?php } else { echo '___aakb'; } ?><?php echo $lang['variable']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th><?php } else { echo '___aakc'; } ?><?php echo $lang['value']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th><?php } else { echo '___aakc'; } ?><?php echo $lang['comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
</tr>
<tr>
<td><?php } else { echo '___aakd'; } ?><?php echo $lang['adminmsg']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><textarea cols="30" rows="4" style="overflow:auto" name="adminmsg" value=""><?php } else { echo '___aake'; } ?><?php echo $adminmsg?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></textarea></td>
<td><?php } else { echo '___aakf'; } ?><?php echo $lang['adminmsg_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['systemmsg']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><textarea cols="30" rows="4" style="overflow:auto" name="systemmsg" value=""><?php } else { echo '___aakh'; } ?><?php echo $systemmsg?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></textarea></td>
<td><?php } else { echo '___aakf'; } ?><?php echo $lang['systemmsg_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['startmode']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $startmode_input?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $lang['startmode_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['starthour']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="starthour" value="<?php } else { echo '___aakj'; } ?><?php echo $starthour?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['starthour_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['startmin']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="startmin" value="<?php } else { echo '___aakl'; } ?><?php echo $startmin?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['startmin_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['iplimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="iplimit" value="<?php } else { echo '___aakm'; } ?><?php echo $iplimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['iplimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['newslimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="newslimit" value="<?php } else { echo '___aakn'; } ?><?php echo $newslimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['newslimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['alivelimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="alivelimit" value="<?php } else { echo '___aako'; } ?><?php echo $alivelimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['alivelimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['winlimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="winlimit" value="<?php } else { echo '___aakp'; } ?><?php echo $winlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['winlimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['noiselimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="noiselimit" value="<?php } else { echo '___aakq'; } ?><?php echo $noiselimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['noiselimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['chatlimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="chatlimit" value="<?php } else { echo '___aakr'; } ?><?php echo $chatlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['chatlimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['chatrefresh']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="chatrefresh" value="<?php } else { echo '___aaks'; } ?><?php echo $chatrefresh?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['chatrefresh_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['chatinnews']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="chatinnews" value="<?php } else { echo '___aakt'; } ?><?php echo $chatinnews?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['chatinnews_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
</table>
<input type="submit" value="修改" onclick="$('command').value='edit';">
</form> <?php } else { echo '___aaku'; } ?>