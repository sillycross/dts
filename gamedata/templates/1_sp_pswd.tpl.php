<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>请输入旧密码和新密码，20个字以内。留空为不修改。<br>

<input type="hidden" name="mode" value="chgpassword">
<div>旧密码：<input size="20" type="password" name="oldpswd" maxlength="20" value=""></div>
<br />
<div>新密码：<input size="20" type="password" name="newpswd" maxlength="20" value=""></div>
<br />
<div>重复输入：<input size="20" type="password" name="newpswd2" maxlength="20" value=""></div>
<br />

<br />
<input type="button" class="cmdbutton" name="submit" value="提交" onclick="postCmd('gamecmd','command.php');this.disabled=true;"><?php } else { echo '___aanU'; } ?>