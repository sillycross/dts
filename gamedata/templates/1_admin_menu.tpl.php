<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="admin" onsubmit="admin.php">
<input type="hidden" name="mode" id="mode" value="admin_menu">
<input type="hidden" name="command" id="command" value="menu">
<table>
<tr>
<td valign="top">
<table class="admin">
<tr>
<td colspan=3 class="tdtitle"><?php } else { echo '___aarA'; } ?><?php echo $lang['emenu']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<th><?php } else { echo '___aarB'; } ?><?php echo $lang['options']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th width="240"><?php } else { echo '___aarC'; } ?><?php echo $lang['comments']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th width="30"><?php } else { echo '___aarD'; } ?><?php echo $lang['groups']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarE'; } ?><?php echo $lang['configmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='configmng'" 
<?php } else { echo '___aarF'; } ?><?php if($mygroup < $admin_cmd_list['configmng']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['configmng_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['configmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['systemmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='systemmng'" 
<?php } else { echo '___aarJ'; } ?><?php if($mygroup < $admin_cmd_list['systemmng']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['systemmng_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['systemmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['gamecfgmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='gamecfgmng'" 
<?php } else { echo '___aarK'; } ?><?php if($mygroup < $admin_cmd_list['gamecfgmng']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['gamecfgmng_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['gamecfgmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['banlistmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='banlistmng'" 
<?php } else { echo '___aarL'; } ?><?php if($mygroup < $admin_cmd_list['banlistmng']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['banlistmng_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['banlistmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['gmlist']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='gmlist'" 
<?php } else { echo '___aarM'; } ?><?php if($mygroup < $admin_cmd_list['gmlist']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['gmlist_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['gmlist']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['urlist']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='urlist'" 
<?php } else { echo '___aarN'; } ?><?php if($mygroup < $admin_cmd_list['urlist']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['urlist_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['urlist']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>

</table>
</td>
<td valign="top">    
<table class="admin">
<tr>
<td colspan=3 class="tdtitle"><?php } else { echo '___aarO'; } ?><?php echo $lang['gmenu']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<th><?php } else { echo '___aarB'; } ?><?php echo $lang['options']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th width="240"><?php } else { echo '___aarC'; } ?><?php echo $lang['comments']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th width="30"><?php } else { echo '___aarD'; } ?><?php echo $lang['groups']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarE'; } ?><?php echo $lang['pcmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='pcmng'" 
<?php } else { echo '___aarP'; } ?><?php if($mygroup < $admin_cmd_list['pcmng']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['pcmng_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['pcmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['npcmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='npcmng'" 
<?php } else { echo '___aarQ'; } ?><?php if($mygroup < $admin_cmd_list['npcmng']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['npcmng_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['npcmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['gameinfomng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='gameinfomng'" 
<?php } else { echo '___aarR'; } ?><?php if($mygroup < $admin_cmd_list['gameinfomng']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['gameinfomng_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['gameinfomng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['antiAFKmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='antiAFKmng'" 
<?php } else { echo '___aarS'; } ?><?php if($mygroup < $admin_cmd_list['antiAFKmng']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['antiAFKmng_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['antiAFKmng']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr height="45px">
<td><input type="submit" style="width:100;height:40;" value="<?php } else { echo '___aarI'; } ?><?php echo $lang['gamecheck']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" onclick="$('command').value='gamecheck'" 
<?php } else { echo '___aarT'; } ?><?php if($mygroup < $admin_cmd_list['gamecheck']) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>disabled="true"
<?php } else { echo '___aarG'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></td>
<td><?php } else { echo '___aarH'; } ?><?php echo $lang['gamecheck_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $admin_cmd_list['gamecheck']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
</table>
</td>
</tr>
</table>

</form> <?php } else { echo '___aarU'; } ?>