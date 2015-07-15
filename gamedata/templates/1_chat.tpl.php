<?php if(!defined('IN_GAME')) exit('Access Denied'); if(defined('IN_REPLAY')) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div style="position:relative; height:100%; width:100%;">
<div class="skill_unacquired">
<?php } else { echo '___aatN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table id="chat" border="0" width="720" cellspacing="0" cellpadding="0" style="valign:top">
<tr>
<td height="20px" width="100%" class="b1"><span>消息</span></td>
</tr>
<tr>
<td valign="top" class="b3" style="text-align: left" height="1px">
<div id="chatlist" class="chatlist">
<?php } else { echo '___aatO'; } ?><?php if(!defined('IN_REPLAY')) { if(is_array($chatdata['msg'])) { foreach($chatdata['msg'] as $msg) { ?>
<?php echo $msg?>
<?php } } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
</td>
</tr>
<tr>
<td class="b3" height="5"></td>
</tr>
<tr>
<td class="b3" height="35">
<div>
<form type="post" id="sendchat" name="sendchat" action="chat.php" onsubmit="return false;" >
<input type="hidden" id="lastcid" name="lastcid" value="<?php } else { echo '___aatP'; } ?><?php echo $chatdata['lastcid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" id="teamID" name="teamID" value="<?php } else { echo '___aatQ'; } ?><?php echo $teamID?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<input type="hidden" id="sendmode" name="sendmode" value="ref">
<span id="chattype">
<select name="chattype" value="2">
<option value="0" selected><?php } else { echo '___aatR'; } ?><?php echo $chatinfo['0']?>
<?php if($teamID) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><option value="1" ><?php } else { echo '___aatS'; } ?><?php echo $chatinfo['1']?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></select>
</span>
<input type="text" id="chatmsg" name="chatmsg" maxlength="60" >
<input type="button" id="send" onClick="document['sendchat']['sendmode'].value='send';chat('send',<?php } else { echo '___aatT'; } ?><?php echo $chatrefresh?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>);return false;" value="发送">
<input type="button" id="ref" onClick="document['sendchat']['sendmode'].value='ref';chat('ref',<?php } else { echo '___aatU'; } ?><?php echo $chatrefresh?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>);return false;" value="刷新">
</form>
<?php } else { echo '___aatV'; } ?><?php if(!defined('IN_REPLAY')) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><script type="text/javascript">chat('ref',<?php } else { echo '___aatW'; } ?><?php echo $chatrefresh?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>);</script>
<?php } else { echo '___aatX'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
</td>
</tr>
</table> 
<?php } else { echo '___aatY'; } ?><?php if(defined('IN_REPLAY')) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></div>
<div class="skill_unacquired_hint">
<table border="0" cellSpacing=0 cellPadding=0 height=100% width=100%>
<tr><td valign="center" align="center">
回放录像聊天记录暂未实现
</td></tr>
</table>
</div></div>
<?php } else { echo '___aatZ'; } ?><?php } ?>

