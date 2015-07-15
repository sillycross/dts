<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="gamecfgmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamecfgmng">
<input type="hidden" id="command" name="command" value="">
<table class="admin">
<tr>
<th><?php } else { echo '___aaou'; } ?><?php echo $lang['variable']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th><?php } else { echo '___aakj'; } ?><?php echo $lang['value']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th><?php } else { echo '___aakj'; } ?><?php echo $lang['comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
</tr>
<tr>
<td><?php } else { echo '___aakk'; } ?><?php echo $lang['areahour']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="areahour" value="<?php } else { echo '___aaov'; } ?><?php echo $areahour?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['areahour_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['areaadd']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="areaadd" value="<?php } else { echo '___aaow'; } ?><?php echo $areaadd?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['areaadd_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['arealimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="arealimit" value="<?php } else { echo '___aaox'; } ?><?php echo $arealimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['arealimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['areaesc']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="areaesc" value="<?php } else { echo '___aaoy'; } ?><?php echo $areaesc?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['areaesc_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['antiAFKertime']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="antiAFKertime" value="<?php } else { echo '___aaoz'; } ?><?php echo $antiAFKertime?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['antiAFKertime_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['corpseprotect']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="corpseprotect" value="<?php } else { echo '___aaoA'; } ?><?php echo $corpseprotect?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['corpseprotect_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['coldtimeon']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="radio" name="coldtimeon" value="1" 
<?php } else { echo '___aaoB'; } ?><?php if($coldtimeon) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaoC'; } ?><?php } ?>
><?php echo $lang['on']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="coldtimeon" value="0" 
<?php } else { echo '___aaoD'; } ?><?php if(!$coldtimeon) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaoC'; } ?><?php } ?>
><?php echo $lang['off']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $lang['coldtimeon_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['showcoldtimer']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="radio" name="showcoldtimer" value="1" 
<?php } else { echo '___aaoE'; } ?><?php if($showcoldtimer) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaoC'; } ?><?php } ?>
><?php echo $lang['on']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="showcoldtimer" value="0" 
<?php } else { echo '___aaoF'; } ?><?php if(!$showcoldtimer) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaoC'; } ?><?php } ?>
><?php echo $lang['off']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><?php } else { echo '___aakp'; } ?><?php echo $lang['showcoldtimer_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['validlimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="validlimit" value="<?php } else { echo '___aaoG'; } ?><?php echo $validlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['validlimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['combolimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="combolimit" value="<?php } else { echo '___aaoH'; } ?><?php echo $combolimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['combolimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['deathlimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="deathlimit" value="<?php } else { echo '___aaoI'; } ?><?php echo $deathlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['deathlimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['splimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="splimit" value="<?php } else { echo '___aaoJ'; } ?><?php echo $splimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['splimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['hplimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="hplimit" value="<?php } else { echo '___aaoK'; } ?><?php echo $hplimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['hplimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['sleep_time']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="sleep_time" value="<?php } else { echo '___aaoL'; } ?><?php echo $sleep_time?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['sleep_time_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['heal_time']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="heal_time" value="<?php } else { echo '___aaoM'; } ?><?php echo $heal_time?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['heal_time_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td><?php } else { echo '___aakn'; } ?><?php echo $lang['teamlimit']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td><input type="text" name="teamlimit" value="<?php } else { echo '___aaoN'; } ?><?php echo $teamlimit?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
<td><?php } else { echo '___aakr'; } ?><?php echo $lang['teamlimit_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
</table>
<input type="submit" value="修改" onclick="$('command').value='edit';">
</form><?php } else { echo '___aaoO'; } ?>