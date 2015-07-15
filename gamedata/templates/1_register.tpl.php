<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div class="subtitle" align="center">账号注册</div>

<p align="center">
<span id="notice"></span>
<span id="info" class="yellow"></span>
</p>
<center>
<form method="post" id="reg" name="reg">
<input type="hidden" name="cmd" value="post_register">
<span class ="yellow">账户基本资料</span>
<?php } else { echo '___aaoH'; } ?><?php include template('userbasicdata'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<span class ="yellow">账户个性化资料</span>
<?php } else { echo '___aaoI'; } ?><?php include template('usergdicon'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<?php } else { echo '___aaaX'; } ?><?php include template('userwords'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br />
<div id="postreg">
<input type="submit" id="post" onClick="postCmd('reg','register.php');return false;" value="提交">
<input type="reset" id="reset" name="reset" value="重设">
</div>
</form>
</center>
<br />
<?php } else { echo '___aaoJ'; } ?><?php include template('footer'); ?>

