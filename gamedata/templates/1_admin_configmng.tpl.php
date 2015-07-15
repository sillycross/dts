<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><form method="post" name="configmng" onsubmit="admin.php">
<input type="hidden" id="mode" name="mode" value="configmng">
<input type="hidden" id="command" name="command" value="">

<table class="admin">
<tr>
<th><?php } else { echo '___aas1'; } ?><?php echo $lang['variable']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th><?php } else { echo '___aakj'; } ?><?php echo $lang['value']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
<th><?php } else { echo '___aakj'; } ?><?php echo $lang['comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></th>
</tr>
<tr>
  <td><?php } else { echo '___aas2'; } ?><?php echo $lang['moveut']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><input type="text" name="moveut" value="<?php } else { echo '___aas3'; } ?><?php echo $moveut?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="5">小时<input type="text" name="moveutmin" value="<?php } else { echo '___aas4'; } ?><?php echo $moveutmin?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="5">分钟</td>
  <td><?php } else { echo '___aas5'; } ?><?php echo $lang['moveut_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br><?php } else { echo '___aaqd'; } ?><?php echo $lang['orin_time']?><?php echo $orin_time?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br><?php } else { echo '___aaqd'; } ?><?php echo $lang['set_time']?><?php echo $set_time?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
  <td><?php } else { echo '___aas6'; } ?><?php echo $lang['authkey']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><input type="text" name="authkey" value="<?php } else { echo '___aas7'; } ?><?php echo $authkey?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30" disabled="true"></td>
  <td><?php } else { echo '___aas8'; } ?><?php echo $lang['authkey_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
  <td><?php } else { echo '___aas6'; } ?><?php echo $lang['tplrefresh']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><input type="radio" name="tplrefresh" value="1" 
<?php } else { echo '___aas9'; } ?><?php if($tplrefresh) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaoC'; } ?><?php } ?>
><?php echo $lang['on']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="tplrefresh" value="0" 
<?php } else { echo '___aas.'; } ?><?php if(!$tplrefresh) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaoC'; } ?><?php } ?>
><?php echo $lang['off']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><?php } else { echo '___aas-'; } ?><?php echo $lang['tplrefresh_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
  <td><?php } else { echo '___aas6'; } ?><?php echo $lang['errorinfo']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><input type="radio" name="errorinfo" value="1" 
<?php } else { echo '___aata'; } ?><?php if($errorinfo) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaoC'; } ?><?php } ?>
><?php echo $lang['on']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="errorinfo" value="0" 
<?php } else { echo '___aatb'; } ?><?php if(!$errorinfo) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>checked="true"
<?php } else { echo '___aaoC'; } ?><?php } ?>
><?php echo $lang['off']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><?php } else { echo '___aas-'; } ?><?php echo $lang['errorinfo_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
  <td><?php } else { echo '___aas6'; } ?><?php echo $lang['bbsurl']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><input type="text" name="bbsurl" value="<?php } else { echo '___aatc'; } ?><?php echo $bbsurl?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
  <td><?php } else { echo '___aatd'; } ?><?php echo $lang['bbsurl_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
  <td><?php } else { echo '___aas6'; } ?><?php echo $lang['gameurl']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><input type="text" name="gameurl" value="<?php } else { echo '___aate'; } ?><?php echo $gameurl?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
  <td><?php } else { echo '___aatd'; } ?><?php echo $lang['gameurl_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
  <td><?php } else { echo '___aas6'; } ?><?php echo $lang['homepage']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
  <td><input type="text" name="homepage" value="<?php } else { echo '___aatf'; } ?><?php echo $homepage?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" size="30"></td>
  <td><?php } else { echo '___aatd'; } ?><?php echo $lang['homepage_comment']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
</table>
<input type="submit" value="提交" onclick="$('command').value='edit';">
</form><?php } else { echo '___aatg'; } ?>