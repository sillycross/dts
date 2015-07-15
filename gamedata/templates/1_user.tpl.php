<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div class="subtitle" align="center">帐号资料</div>

<p align="center" class="linen">
需要修改密码则输入原密码和新密码，留空为不修改。<br />
注意：若玩家已进入游戏，对性别和头像的修改将在下一局生效。<br />
<span id="notice"></span><br />
<span id="info" class="yellow"></span>
</p>
<center>
<form method="post" action="user.php" name="userdata">
<input type="hidden" name="mode" value="edit">
<span class ="yellow">账户基本资料</span>
<?php } else { echo '___aap9'; } ?><?php include template('userbasicdata'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<span class ="yellow">账户个性化资料</span>
<?php } else { echo '___aaoQ'; } ?><?php include template('usergdicon'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaa4'; } ?><?php include template('userwords'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<div id="postdata">
<input type="submit" id="post" onClick="postCmd('userdata','user.php');return false;" value="提交">
<input type="reset" id="reset" name="reset" value="重设">
</div>
</form>
</center>
<br />
<?php } else { echo '___aap.'; } ?><?php include template('footer'); ?>
 