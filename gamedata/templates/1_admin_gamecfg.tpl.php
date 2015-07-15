<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="gamecfgmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamecfgmng">
<input type="hidden" id="command" name="command" value="">
<table class="admin">
<tr>
<th><?php } else { echo '___aaom'; } ?><?php echo $lang['variable']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th><?php } else { echo '___aakc'; } ?><?php echo $lang['value']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th><?php } else { echo '___aakc'; } ?><?php echo $lang['comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
</tr>
<tr>
<td><?php } else { echo '___aakd'; } ?><?php echo $lang['areahour']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="areahour" value="<?php } else { echo '___aaon'; } ?><?php echo $areahour?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['areahour_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['areaadd']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="areaadd" value="<?php } else { echo '___aaoo'; } ?><?php echo $areaadd?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['areaadd_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['arealimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="arealimit" value="<?php } else { echo '___aaop'; } ?><?php echo $arealimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['arealimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['areaesc']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="areaesc" value="<?php } else { echo '___aaoq'; } ?><?php echo $areaesc?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['areaesc_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['antiAFKertime']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="antiAFKertime" value="<?php } else { echo '___aaor'; } ?><?php echo $antiAFKertime?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['antiAFKertime_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['corpseprotect']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="corpseprotect" value="<?php } else { echo '___aaos'; } ?><?php echo $corpseprotect?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['corpseprotect_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['coldtimeon']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="radio" name="coldtimeon" value="1" 
<?php } else { echo '___aaot'; } ?><?php if($coldtimeon) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaou'; } ?><?php } ?>
><?php echo $lang['on']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="coldtimeon" value="0" 
<?php } else { echo '___aaov'; } ?><?php if(!$coldtimeon) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaou'; } ?><?php } ?>
><?php echo $lang['off']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $lang['coldtimeon_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['showcoldtimer']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="radio" name="showcoldtimer" value="1" 
<?php } else { echo '___aaow'; } ?><?php if($showcoldtimer) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaou'; } ?><?php } ?>
><?php echo $lang['on']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="showcoldtimer" value="0" 
<?php } else { echo '___aaox'; } ?><?php if(!$showcoldtimer) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaou'; } ?><?php } ?>
><?php echo $lang['off']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aaki'; } ?><?php echo $lang['showcoldtimer_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['validlimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="validlimit" value="<?php } else { echo '___aaoy'; } ?><?php echo $validlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['validlimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['combolimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="combolimit" value="<?php } else { echo '___aaoz'; } ?><?php echo $combolimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['combolimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['deathlimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="deathlimit" value="<?php } else { echo '___aaoA'; } ?><?php echo $deathlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['deathlimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['splimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="splimit" value="<?php } else { echo '___aaoB'; } ?><?php echo $splimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['splimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['hplimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="hplimit" value="<?php } else { echo '___aaoC'; } ?><?php echo $hplimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['hplimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['sleep_time']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="sleep_time" value="<?php } else { echo '___aaoD'; } ?><?php echo $sleep_time?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['sleep_time_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['heal_time']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="heal_time" value="<?php } else { echo '___aaoE'; } ?><?php echo $heal_time?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['heal_time_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakg'; } ?><?php echo $lang['teamlimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="teamlimit" value="<?php } else { echo '___aaoF'; } ?><?php echo $teamlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['teamlimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
</table>
<input type="submit" value="修改" onclick="$('command').value='edit';">
</form><?php } else { echo '___aaoG'; } ?>