<?php if(!defined('IN_GAME')) exit('Access Denied'); \bubblebox\bubblebox_set_style('id:gnum_replay_error;height:200;width:500;cancellable:1;'); include template('MOD_BUBBLEBOX_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table height=198px width=100%><tr><td align="center" valign="middle">
<span><font size="50px">录像文件不存在</font><br><br>
可能是由于录像功能当时还未开放，或出现了一个系统错误<br><br>
请点击气泡框外任意位置返回
</td></tr></table>
<?php } else { echo '___aaaD'; } ?><?php include template('MOD_BUBBLEBOX_END'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><script type="text/javascript">bubblebox_show('gnum_replay_error');</script><?php } else { echo '___aaaE'; } ?>