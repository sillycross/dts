<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table align="center">
<?php } else { echo '___aasv'; } ?><?php if(CURSCRIPT == 'register') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td>账号</td>
<td><input type="text" name="username" size="15" maxlength="15" value=""></td>
<td>最长15个英文字符或者汉字，不能出现半角符号</td>
</tr>
<tr>
<td>新密码</td>
<td><input type="password" id="npass" name="npass" size="24" maxlength="24" value=""></td>
<td>最长24个字符，留空为不修改</td>
</tr>
<tr>
<td>重复新密码</td>
<td><input type="password" id="rnpass" name="rnpass" size="24" maxlength="24" value=""></td>
<td>最长24个字符，留空为不修改</td>
</tr>
<?php } else { echo '___aasw'; } ?><?php } elseif(CURSCRIPT == 'user') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td>账号</td>
<td><?php } else { echo '___aasx'; } ?><?php echo $username?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td> </td>
</tr>
<tr>
<td>原密码</td>
<td><input type="password" id="opass" name="opass" size="24" maxlength="24" value=""></td>
<td> </td>
</tr>
<tr>
<td>新密码</td>
<td><input type="password" id="npass" name="npass" size="24" maxlength="24" value=""></td>
<td>最长24个字符，留空为不修改</td>
</tr>
<tr>
<td>重复新密码</td>
<td><input type="password" id="rnpass" name="rnpass" size="24" maxlength="24" value=""></td>
<td>最长24个字符，留空为不修改</td>
</tr>
<?php } else { echo '___aasy'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td>账号</td>
<td><?php } else { echo '___aasx'; } ?><?php echo $username?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td> </td>
</tr>
<tr>
<td>密码</td>
<td>!cannotedit!</td>
<td> </td>
</tr>
<?php } else { echo '___aasz'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></table><?php } else { echo '___aab8'; } ?>